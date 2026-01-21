#!/bin/bash

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  TEST COMPLETO TUTTE LE ROUTE UNINOTES${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Avvia server
echo -e "${YELLOW}[1/3] Avvio server PHP...${NC}"
cd public
php -S localhost:8000 > /dev/null 2>&1 &
SERVER_PID=$!
cd ..
sleep 4

echo -e "${GREEN}✅ Server avviato su http://localhost:8000${NC}"
echo ""

# Test accessibilità
echo -e "${YELLOW}[2/3] Test accessibilità su tutte le route...${NC}"
mkdir -p reports

# Tutte le route dal tuo routes.php (solo GET accessibili via browser)
declare -A ROUTES=(
    ["home"]="/"
    ["search"]="/search"
    ["login"]="/login"
    ["register"]="/register"
    ["note-detail"]="/note/1"
    ["log"]="/log"
    ["user-dashboard"]="/user/dashboard"
    ["admin"]="/admin"
    ["ranking"]="/ranking"
)

TOTAL_ROUTES=${#ROUTES[@]}
CURRENT=0
TOTAL_ERRORS=0
TOTAL_WARNINGS=0
TESTED=0
SKIPPED=0

echo "Route da testare: $TOTAL_ROUTES"
echo ""

for name in "${!ROUTES[@]}"; do
    CURRENT=$((CURRENT + 1))
    route="${ROUTES[$name]}"
    URL="http://localhost:8000$route"
    
    echo -e "   [$CURRENT/$TOTAL_ROUTES] Testing: ${BLUE}$name${NC} ($route)"
    
    # Verifica se la pagina esiste
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$URL")
    
    if [[ "$HTTP_CODE" =~ ^(200|302)$ ]]; then
        # Esegui Pa11y
        pa11y "$URL" --config .pa11yrc.json > "reports/pa11y-$name.txt" 2>&1
        EXIT_CODE=$?
        TESTED=$((TESTED + 1))
        
        if [ $EXIT_CODE -eq 0 ]; then
            echo -e "      ${GREEN}✅ Nessun errore (HTTP $HTTP_CODE)${NC}"
        elif [ $EXIT_CODE -eq 2 ]; then
            ERRORS=$(grep -c "• Error:" "reports/pa11y-$name.txt" 2>/dev/null)
            WARNINGS=$(grep -c "• Warning:" "reports/pa11y-$name.txt" 2>/dev/null)
            
            # Se grep non trova nulla, restituisce errore, quindi gestiamo
            if [ -z "$ERRORS" ]; then ERRORS=0; fi
            if [ -z "$WARNINGS" ]; then WARNINGS=0; fi
            
            TOTAL_ERRORS=$((TOTAL_ERRORS + ERRORS))
            TOTAL_WARNINGS=$((TOTAL_WARNINGS + WARNINGS))
            
            if [ "$ERRORS" -eq 0 ]; then
                echo -e "      ${GREEN}✅ 0 errori${NC}, ${YELLOW}$WARNINGS avvisi${NC} (HTTP $HTTP_CODE)"
            else
                echo -e "      ${YELLOW}⚠️  $ERRORS errori, $WARNINGS avvisi${NC} (HTTP $HTTP_CODE)"
            fi
        else
            echo -e "      ${RED}❌ Errore durante il test (exit code: $EXIT_CODE)${NC}"
        fi
    else
        echo -e "      ${YELLOW}⏭️  Saltata (HTTP $HTTP_CODE - potrebbe richiedere autenticazione)${NC}"
        SKIPPED=$((SKIPPED + 1))
    fi
done

echo ""

# Chiudi server
echo -e "${YELLOW}[3/3] Chiusura server...${NC}"
kill $SERVER_PID 2>/dev/null
echo -e "${GREEN}✅ Server chiuso${NC}"
echo ""

# Riepilogo
echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}           RIEPILOGO FINALE             ${NC}"
echo -e "${BLUE}========================================${NC}"
echo -e "Route totali:       ${GREEN}$TOTAL_ROUTES${NC}"
echo -e "Pagine testate:     ${GREEN}$TESTED${NC}"
echo -e "Pagine saltate:     ${YELLOW}$SKIPPED${NC}"
echo -e "Errori totali:      $([ $TOTAL_ERRORS -eq 0 ] && echo -e "${GREEN}$TOTAL_ERRORS${NC}" || echo -e "${YELLOW}$TOTAL_ERRORS${NC}")"
echo -e "Avvisi totali:      ${YELLOW}$TOTAL_WARNINGS${NC}"
echo ""

if [ $TOTAL_ERRORS -eq 0 ]; then
    echo -e "${GREEN}🎉 Ottimo! Nessun errore di accessibilità critico!${NC}"
else
    echo -e "${YELLOW}💡 Trovati $TOTAL_ERRORS errori di accessibilità da correggere${NC}"
fi

echo ""
echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}        TOP 5 ERRORI PIÙ COMUNI        ${NC}"
echo -e "${BLUE}========================================${NC}"
grep -h "• Error:" reports/pa11y-*.txt 2>/dev/null | \
    sed 's/^.*├── //' | cut -d'.' -f1-3 | \
    sort | uniq -c | sort -rn | head -5 | \
    while read count error; do
        echo -e "${YELLOW}[$count]${NC} $error"
    done

echo ""
echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}         DETTAGLI PER PAGINA            ${NC}"
echo -e "${BLUE}========================================${NC}"

for name in "${!ROUTES[@]}"; do
    if [ -f "reports/pa11y-$name.txt" ]; then
        ERRORS=$(grep -c "• Error:" "reports/pa11y-$name.txt" 2>/dev/null || echo 0)
        if [ "$ERRORS" -gt 0 ]; then
            echo -e "${YELLOW}$name${NC}: $ERRORS errori"
        fi
    fi
done

echo ""
echo "📁 Report salvati in: ${BLUE}./reports/${NC}"
echo ""
echo "Per vedere tutti gli errori di una pagina:"
echo "  cat reports/pa11y-home.txt"
echo "  cat reports/pa11y-login.txt"
echo "  cat reports/pa11y-ranking.txt"
echo ""
echo "Per vedere TUTTI gli errori:"
echo "  grep -h '• Error:' reports/pa11y-*.txt"
echo ""

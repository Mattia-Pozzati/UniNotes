#!/usr/bin/env bash
set -euo pipefail

# Path to the SQL file (adjust if necessary)
SQL="Core/Database/populate_database.sql"
OUT_BASE="storage/upload"

mkdir -p "$OUT_BASE"

sanitize() {
  echo "$1" | iconv -c -t ascii//TRANSLIT 2>/dev/null | sed 's/[^A-Za-z0-9._-]/_/g' | sed 's/_\+/_/g' | sed 's/^_//; s/_$//'
}

# Parse explicit INSERT ... VALUES tuples for NOTE and extract id, title, format
perl -0777 -ne '
while (/\(\s*(\d+)\s*,\s*\d+\s*,\s*\'([^\']*)\'\s*,\s*\'([^\']*)\'\s*,\s*\'([^\']*)\'\s*,\s*\'([^\']*)\'/g) {
  print "$1\t$2\t$5\n";
}
' "$SQL" | while IFS=$'\t' read -r id title format; do
  ext="$format"
  case "$format" in
    pdf|md|tex) ext="$format" ;;
    *) ext="txt" ;;
  esac
  fname="$(sanitize "$title").$ext"
  dir="$OUT_BASE/note_$id"
  mkdir -p "$dir"
  printf 'Titolo: %s\nID: %s\nFormato: %s\n' "$title" "$id" "$format" > "$dir/$fname"
done

# Handle generated notes 11..65 (reproduce ELT((id % 3)+1, 'pdf','md','tex'))
for id in $(seq 11 65); do
  rem=$((id % 3))
  if [ $rem -eq 0 ]; then fmt=pdf
  elif [ $rem -eq 1 ]; then fmt=md
  else fmt=tex
  fi
  title="Nota $id"
  fname="$(sanitize "$title").$fmt"
  dir="$OUT_BASE/note_$id"
  mkdir -p "$dir"
  printf 'Titolo: %s\nID: %s\nFormato: %s\n' "$title" "$id" "$fmt" > "$dir/$fname"
done

echo "Fatto: creati file per le note (output in $OUT_BASE)."

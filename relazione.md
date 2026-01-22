# Relazione Tecnica - UniNotes

## Panoramica del Progetto
UniNotes è una piattaforma web per la condivisione di appunti universitari sviluppata in PHP nativo senza framework esterni. Il sistema permette agli studenti di caricare, condividere e commentare materiale didattico, con funzionalità di ricerca avanzata, sistema di reputazione e notifiche.

## Architettura Tecnica

### Pattern Architetturali
Il progetto adotta un'architettura **MVC personalizzata** con separazione netta tra logica applicativa, presentazione e accesso ai dati:

- **Model**: ORM custom (`BaseModel`) con query builder fluente per interazioni database
- **View**: Sistema di templating PHP con componenti riutilizzabili e layout gerarchici
- **Controller**: Gestione della logica applicativa e coordinamento tra Model e View

### Componenti Core

**Router (`Core/Routing/Router.php`)**: Singleton pattern per gestione delle route con supporto a parametri dinamici (`/note/{id}`) e dispatching automatico ai controller.

**ORM Custom (`Core/ORM/BaseModel.php`)**: Query builder type-safe che implementa interfaccia fluente per operazioni CRUD, con supporto a:
- Join multipli (INNER, LEFT, RIGHT)
- Paginazione nativa
- Where conditions con operatori SQL standard
- Group by e Order by

**Logger (`Core/Helper/Logger.php`)**: Sistema di logging centralizzato con sanitizzazione automatica di dati sensibili (password, email parziali) e categorizzazione per livello (DEBUG, INFO, ERROR, WARNING).

**SessionManager (`Core/Helper/SessionManager.php`)**: Gestione sicura delle sessioni con:
- Rigenerazione periodica degli ID
- Flash messages monouso
- Controllo ruoli (student/admin)

## Database
Schema relazionale normalizzato con 9 tabelle principali:
- `USER`: Gestione utenti con sistema di reputazione
- `NOTE`: Contenitore logico per appunti con soft-delete
- `FILE`: Storage metadati file con hash per integrità
- `COURSE`, `NOTE_COURSE`: Relazione many-to-many per categorizzazione
- `COMMENT`: Sistema di commenti con thread annidati (parent_comment_id)
- `LIKE`, `NOTIFICATION`, `NOTE_DOWNLOAD`: Tracking interazioni utente

## Funzionalità Implementate

### Gestione Appunti
- Upload file (PDF, MD, TEX) con validazione MIME type
- Aggiornamento file (sovrascrittura con notifica ai downloader)
- Ricerca full-text con filtri multipli (corso, formato, tipo)
- Paginazione server-side per performance
- Sistema di visibilità (public/course/private)

### Interazioni Utente
- Like/unlike con aggiornamento reputazione autore
- Commenti con risposte annidate
- Sistema di notifiche real-time per like, commenti e aggiornamenti
- Dashboard personalizzata per studenti e admin

### Sicurezza
- Password hashing con `password_hash()` (bcrypt)
- Prepared statements per prevenzione SQL injection
- Validazione input lato server con sanitizzazione
- Session fixation protection con rigenerazione ID
- CSRF protection attraverso validazione metodo HTTP

### Funzionalità Avanzate
- **Chat AI**: Integrazione OpenRouter API per Q&A su contenuto note (estrazione testo da PDF con pdftotext)
- **Dark Mode**: Sistema di temi CSS con variabili custom e persistenza localStorage
- **Responsive Design**: UI mobile-first con Bootstrap 5.3

## Scelte Implementative

**PHP Nativo**: Scelta didattica per comprendere internals del linguaggio senza astrazione framework.

**ORM Custom vs PDO Diretto**: Trade-off tra leggibilità del codice e flessibilità SQL. Il query builder riduce boilerplate del 60% mantenendo type safety.

**File Storage**: Filesystem locale con metadati in database. Pro: semplicità. Contro: scaling orizzontale limitato (mitigabile con NFS o object storage futuro).

**No Versioning File**: Aggiornamenti sovrascrivono file esistente. Razionale: semplicità MVP. Feature future: audit log o S3 versioning.

## Performance e Scalabilità
- Indici database su colonne filtrate frequentemente (`created_at`, `student_id`, `note_id`)
- Paginazione per limitare memoria utilizzata (default 12 item/pagina)
- Lazy loading componenti UI (tab-based navigation)

## Conclusioni
UniNotes dimostra implementazione completa di pattern enterprise (MVC, Singleton, Repository) senza dipendenze esterne. Il codice privilegia leggibilità e manutenibilità attraverso separazione delle responsabilità e documentazione inline. Il sistema è production-ready per deployment LAMP stack con spazio per ottimizzazioni future (caching, CDN, microservizi).
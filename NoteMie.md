Cose da fare ora:
project-root/
│
├─ public/                   # Document root esposta al webserver
│   ├─ index.php             # Entry point front controller
│   ├─ css/
│   ├─ js/
│   ├─ assets/               # immagini, SVG, font, media
│   └─ vendor/ (solo per libs JS locali)
│
├─ app/                      # Codice dell'applicazione vera e propria
│   ├─ Controllers/          
│   ├─ Models/
│   ├─ Views/                # Template HTML/PHP includibili
│   ├─ Services/             # Logiche applicative (es: Mailer, Auth)
│   └─ Middlewares/
│
├─ core/                     # Componenti basilari del "micro-framework"
│   ├─ Router/               # Custom router (Route, Dispatcher, ecc.)
│   ├─ ORM/                  # Custom ORM (QueryBuilder, ModelBase)
│   ├─ Http/                 # Request, Response, Cookie, Session wrapper
│   ├─ Database/             # Connessione DB, config e driver
│   ├─ Exceptions/           
│   └─ Helpers/              # Funzioni globali, utility
│
├─ config/
│   ├─ app.php               # Configurazioni generali
│   ├─ database.php          # Config DB
│   └─ routes.php            # Definizione delle route
│
├─ storage/
│   ├─ logs/
│   ├─ cache/
│   └─ uploads/
│
├─ tests/
│
├─ scripts/                  # Script vari (migrazioni, seed, strumenti CLI)
│   └─ cli.php
│
├─ vendor/                   # Solo se usi Composer (anche solo per autoload)
│
├─ .env                      # Variabili di ambiente (ignorato in git)
├─ composer.json             # Anche solo per autoload PSR-4
└─ README.md

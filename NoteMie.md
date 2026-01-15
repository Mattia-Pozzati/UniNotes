Project Plan — Backend Notes & Notifications

## 1. Database

 Database migrations: creare migration versionate per users, note, file, file_version, comment, like, notification, tag, note_tag, note_course, note_download (up/down + runner).
 Seed data: script per popolare dev DB (users, courses, sample notes, file, notifications).
## 2. ORM (core)

 BaseModel.php: centralizzare builder (buildQuery), supporto JOIN sicuro, quoting identificatori.
 Aggiungere count(), sum(), exists() che riusano buildQuery.
 paginate($page,$perPage) → ritorna ['data','meta'] e funziona con JOIN/filters.
 Transaction helpers: beginTransaction(), commit(), rollback().
## 3. Models

 Note.php: proprietà pubbliche per PDO::FETCH_CLASS, toArray(), jsonSerialize(), helper withAuthor().
 File.php: metadata (original_name, stored_name, path, mime, size, checksum, version).
 Notification.php: createFor(), markRead(), unreadCount() helpers.
## 4. Data-only Controllers / Services

 NotesController.php (static/data-only): list, get, create, update, delete (REST-like, JSON or arrays), con paginazione e check ownership.
 App/Controller/FilesController.php: uploadForNote(noteId, $_FILES['...']).
 App/Controller/NotificationsController.php: list(user), markRead(id|all), unreadCount(user).
## 5. Page Controllers (rendering)

 PageController.php usa i Data APIs per popolare view e chiama App/View/View::render(); nessuna logica di accesso dati nelle view.
## 6. File storage

 Storage.php: path fuori webroot, safe filenames, MIME detection (finfo), size limits, move_uploaded_file().
 Versioning: checksum e versione, cleanup su errore.

## 7. Notifications & Background

 Creare notification al create/update note (inserto DB o enqueue job).
 Minimal job queue (DB-backed) + worker CLI per task asincroni (notifications, file processing).

## 8. Security

 CSRF: token in sessione + validazione per POST/PUT/DELETE.
 Auth & ownership: middleware/central check; enforce nelle API.
 Input validation + prepared statements; output escaping (htmlspecialchars nelle view).

## 9. Testing & CI

 Unit tests: BaseModel, Note, File, Notification.
 Integration tests: API endpoints, upload, auth flows.
 CI: GitHub Actions (lint, PHPStan/Psalm, composer install, PHPUnit).

## 10. Observability & Docs

 Centralizzare logging (Core/Helper/Logger), capture exceptions, log slow queries.
 README.md + docs/ con setup locale, migrations, worker, env.
 .env.example e checklist deployment.


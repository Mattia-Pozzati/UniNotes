-- Popolamento database con dati di esempio

SET FOREIGN_KEY_CHECKS=0;

-- Pulisci tabelle esistenti
DELETE FROM NOTE_DOWNLOAD;
DELETE FROM NOTIFICATION;
DELETE FROM `LIKE`;
DELETE FROM `COMMENT`;
DELETE FROM NOTE_COURSE;
DELETE FROM COURSE;
DELETE FROM FILE;
DELETE FROM NOTE;
DELETE FROM `USER`;

SET FOREIGN_KEY_CHECKS=1;

-- Users (password hash per 'password')
INSERT INTO `USER` (id, name, email, password_hash, university, role, reputation) VALUES
(1, 'Alice Rossi', 'alice@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Bologna', 'student', 15),
(2, 'Luca Bianchi', 'luca@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Politecnico di Milano', 'student', 8),
(3, 'Admin', 'admin@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', 0),
(4, 'Mario Verdi', 'mario@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Bologna', 'student', 12),
(5, 'Sara Neri', 'sara@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Roma', 'student', 20);

-- Courses
INSERT INTO COURSE (id, name) VALUES
(1, 'Analisi Matematica 1'),
(2, 'Fisica 1'),
(3, 'Programmazione'),
(4, 'Algoritmi e Strutture Dati'),
(5, 'Database');

-- Notes
INSERT INTO NOTE (id, student_id, title, description, note_type, format, university, visibility, created_at) VALUES
(1, 1, 'Appunti di Analisi', 'Appunti completi del corso di Analisi Matematica con tutti i teoremi e dimostrazioni', 'riassunto', 'pdf', 'Università di Bologna', 'public', NOW()),
(2, 2, 'Esercizi Fisica', 'Soluzioni dettagliate degli esercizi del capitolo 3 sulla meccanica', 'esercizi', 'pdf', 'Politecnico di Milano', 'public', NOW()),
(3, 1, 'Formulario Matematica', 'Formule utili per il compito di Analisi 1', 'formulario', 'pdf', 'Università di Bologna', 'public', NOW()),
(4, 4, 'Programmazione C++', 'Esempi di codice C++ con spiegazioni', 'note', 'pdf', 'Università di Bologna', 'public', NOW()),
(5, 5, 'Database Relazionali', 'Teoria e pratica dei database relazionali', 'riassunto', 'pdf', 'Università di Roma', 'public', NOW());

-- Note-Course links
INSERT INTO NOTE_COURSE (note_id, course_id) VALUES
(1, 1),
(2, 2),
(3, 1),
(4, 3),
(5, 5);

-- Files
INSERT INTO FILE (id, note_id, filename, filepath, mime_type, size, format, hash, uploaded_at) VALUES
(1, 1, 'riassunto_analisi1.txt', 'storage/upload/note_1/file_1.txt', 'text/plain', 20, 'txt', 'hash1', NOW()),
(2, 2, 'esercizi_fisica.txt', 'storage/upload/note_2/file_2.txt', 'text/plain', 17, 'txt', 'hash2', NOW()),
(3, 3, 'formulario_mat.txt', 'storage/upload/note_3/file_3.txt', 'text/plain', 23, 'txt', 'hash3', NOW());

-- Comments
INSERT INTO `COMMENT` (id, note_id, student_id, content, created_at) VALUES
(1, 1, 2, 'Ottimi appunti, molto chiari! Grazie per la condivisione.', NOW()),
(2, 1, 1, 'Grazie a te! Sono contenta che ti siano utili.', NOW()),
(3, 2, 4, 'Gli esercizi sono ben spiegati, mi hanno aiutato molto!', NOW()),
(4, 1, 4, 'Potresti aggiungere anche gli esercizi svolti?', NOW());

-- Likes
INSERT INTO `LIKE` (student_id, note_id, created_at) VALUES
(2, 1, NOW()),
(4, 1, NOW()),
(5, 1, NOW()),
(1, 2, NOW()),
(4, 2, NOW());

-- Notifications
INSERT INTO NOTIFICATION (id, sender_id, recipient_id, type, message, payload, created_at) VALUES
(1, 2, 1, 'like', 'Luca ha messo like alla tua nota "Appunti di Analisi"', JSON_OBJECT('note_id', 1), NOW()),
(2, 2, 1, 'comment', 'Luca ha commentato la tua nota "Appunti di Analisi"', JSON_OBJECT('note_id', 1, 'comment_id', 1), NOW()),
(3, 4, 1, 'like', 'Mario ha messo like alla tua nota "Appunti di Analisi"', JSON_OBJECT('note_id', 1), NOW());

-- Note downloads
INSERT INTO NOTE_DOWNLOAD (student_id, note_id, downloaded_at) VALUES
(2, 1, NOW()),
(4, 1, NOW()),
(1, 2, NOW()),
(5, 1, NOW());

-- Reset AUTO_INCREMENT
ALTER TABLE `USER` AUTO_INCREMENT = 100;
ALTER TABLE `NOTE` AUTO_INCREMENT = 100;
ALTER TABLE `FILE` AUTO_INCREMENT = 100;
ALTER TABLE `COMMENT` AUTO_INCREMENT = 100;
ALTER TABLE `NOTIFICATION` AUTO_INCREMENT = 100;
ALTER TABLE `COURSE` AUTO_INCREMENT = 100;
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
INSERT INTO `USER` (id, name, email, password_hash, university, role) VALUES
(1, 'Alice Rossi', 'alice@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Bologna', 'student'),
(2, 'Luca Bianchi', 'luca@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Politecnico di Milano', 'student'),
(3, 'Admin', 'admin@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin'),
(4, 'Mario Verdi', 'mario@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Bologna', 'student'),
(5, 'Sara Neri', 'sara@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Roma', 'student');

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
INSERT INTO FILE (id, note_id, filename, filepath, mime_type, size, hash, uploaded_at) VALUES
(1, 1, 'riassunto_analisi1.txt', 'storage/upload/note_1/file_1.txt', 'text/plain', 20, 'hash1', NOW()),
(2, 2, 'esercizi_fisica.txt', 'storage/upload/note_2/file_2.txt', 'text/plain', 17, 'hash2', NOW()),
(3, 3, 'formulario_mat.txt', 'storage/upload/note_3/file_3.txt', 'text/plain', 23, 'hash3', NOW());

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
-- NOTE: schema expects (id, note_id, sender_id, recipient_id, type, message, payload, created_at)
INSERT INTO NOTIFICATION (id, note_id, sender_id, recipient_id, type, message, payload, created_at) VALUES
(1, 1, 2, 1, 'like', 'Luca ha messo like alla tua nota "Appunti di Analisi"', JSON_OBJECT('note_id', 1), NOW()),
(2, 1, 2, 1, 'comment', 'Luca ha commentato la tua nota "Appunti di Analisi"', JSON_OBJECT('note_id', 1, 'comment_id', 1), NOW()),
(3, 1, 4, 1, 'like', 'Mario ha messo like alla tua nota "Appunti di Analisi"', JSON_OBJECT('note_id', 1), NOW());

-- Note downloads
INSERT INTO NOTE_DOWNLOAD (student_id, note_id, downloaded_at) VALUES
(2, 1, NOW()),
(4, 1, NOW()),
(1, 2, NOW()),
(5, 1, NOW());

-- Aggiungo molti utenti aggiuntivi (password: "password")
INSERT INTO `USER` (id, name, email, password_hash, university, role) VALUES
(6, 'Giulia Romano', 'giulia6@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Napoli', 'student'),
(7, 'Federico Greco', 'federico7@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Padova', 'student'),
(8, 'Elena Ferri', 'elena8@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Bologna', 'student'),
(9, 'Andrea Conti', 'andrea9@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Politecnico di Milano', 'student'),
(10, 'Martina Villa', 'martina10@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Roma', 'student'),
(11, 'Davide Sala', 'davide11@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Torino', 'student'),
(12, 'Chiara Bruno', 'chiara12@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Pisa', 'student'),
(13, 'Simone Costa', 'simone13@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Napoli', 'student'),
(14, 'Laura Galli', 'laura14@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Bologna', 'student'),
(15, 'Nicola Rizzo', 'nicola15@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Politecnico di Torino', 'student'),
(16, 'Francesca De Luca', 'francesca16@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Roma', 'student'),
(17, 'Stefano Marchetti', 'stefano17@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Padova', 'student'),
(18, 'Valentina Moretti', 'valentina18@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Milano', 'student'),
(19, 'Alessandro Romano', 'alessandro19@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Bologna', 'student'),
(20, 'Martina Ferraro', 'martina20@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Bari', 'student'),
(21, 'Giorgio Berti', 'giorgio21@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Palermo', 'student'),
(22, 'Ilaria Pini', 'ilaria22@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Genova', 'student'),
(23, 'Michele Longo', 'michele23@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Pisa', 'student'),
(24, 'Sara Colombo', 'sara24@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Milano', 'student'),
(25, 'Riccardo Serra', 'riccardo25@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Torino', 'student'),
(26, 'Elisa Vitale', 'elisa26@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Roma', 'student'),
(27, 'Matteo Rinaldi', 'matteo27@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Politecnico di Milano', 'student'),
(28, 'Noemi Ferri', 'noemi28@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Bologna', 'student'),
(29, 'Lorenzo Romano', 'lorenzo29@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Padova', 'student'),
(30, 'Arianna Bellini', 'arianna30@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Pisa', 'student'),
(31, 'Paolo Marchetti', 'paolo31@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Napoli', 'student'),
(32, 'Beatrice Riva', 'beatrice32@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Bari', 'student'),
(33, 'Edoardo Fiore', 'edoardo33@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Palermo', 'student'),
(34, 'Claudia Neri', 'claudia34@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Genova', 'student'),
(35, 'Tommaso Greco', 'tommaso35@uninotes.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Università di Torino', 'student');

-- Aggiungo molte note (6..65)
INSERT INTO NOTE (id, student_id, title, description, note_type, format, university, visibility, created_at) VALUES
(6, 6, 'Nota 6', 'Descrizione della nota 6', 'riassunto', 'pdf', 'Università di Napoli', 'public', NOW()),
(7, 7, 'Nota 7', 'Descrizione della nota 7', 'esercizi', 'pdf', 'Università di Padova', 'public', NOW()),
(8, 8, 'Nota 8', 'Descrizione della nota 8', 'formulario', 'md', 'Università di Bologna', 'course', NOW()),
(9, 9, 'Nota 9', 'Descrizione della nota 9', 'note', 'pdf', 'Politecnico di Milano', 'public', NOW()),
(10, 10, 'Nota 10', 'Descrizione della nota 10', 'altro', 'tex', 'Università di Roma', 'private', NOW());

-- Genero notes 11..65
INSERT INTO NOTE (id, student_id, title, description, note_type, format, university, visibility, created_at)
SELECT seq.id, ((seq.id % 35) + 1) as student_id,
			 CONCAT('Nota ', seq.id) as title,
			 CONCAT('Descrizione generata per la nota ', seq.id) as description,
			 ELT((seq.id % 5)+1, 'riassunto','formulario','esercizi','note','altro') as note_type,
			 ELT((seq.id % 3)+1, 'pdf','md','tex') as format,
			 ELT((seq.id % 6)+1, 'Università di Bologna','Politecnico di Milano','Università di Roma','Università di Pisa','Università di Napoli','Università di Torino') as university,
			 ELT((seq.id % 3)+1, 'public','course','private') as visibility,
			 NOW()
FROM (
	SELECT 11 as id UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15
	UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20
	UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL SELECT 24 UNION ALL SELECT 25
	UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL SELECT 29 UNION ALL SELECT 30
	UNION ALL SELECT 31 UNION ALL SELECT 32 UNION ALL SELECT 33 UNION ALL SELECT 34 UNION ALL SELECT 35
	UNION ALL SELECT 36 UNION ALL SELECT 37 UNION ALL SELECT 38 UNION ALL SELECT 39 UNION ALL SELECT 40
	UNION ALL SELECT 41 UNION ALL SELECT 42 UNION ALL SELECT 43 UNION ALL SELECT 44 UNION ALL SELECT 45
	UNION ALL SELECT 46 UNION ALL SELECT 47 UNION ALL SELECT 48 UNION ALL SELECT 49 UNION ALL SELECT 50
	UNION ALL SELECT 51 UNION ALL SELECT 52 UNION ALL SELECT 53 UNION ALL SELECT 54 UNION ALL SELECT 55
	UNION ALL SELECT 56 UNION ALL SELECT 57 UNION ALL SELECT 58 UNION ALL SELECT 59 UNION ALL SELECT 60
	UNION ALL SELECT 61 UNION ALL SELECT 62 UNION ALL SELECT 63 UNION ALL SELECT 64 UNION ALL SELECT 65
) seq;

-- Note-Course links for the new notes
INSERT INTO NOTE_COURSE (note_id, course_id)
SELECT n.id, ((n.id % 5) + 1)
FROM NOTE n
WHERE n.id BETWEEN 6 AND 65;

-- Files for some notes (every 3rd note)
INSERT INTO FILE (id, note_id, filename, filepath, mime_type, size, hash, uploaded_at)
SELECT (100 + n.id), n.id, CONCAT('file_note_', n.id, '.txt'), CONCAT('storage/upload/note_', n.id, '/file_', n.id, '.txt'), 'application/pdf', 100 + (n.id % 50), CONCAT('hash', n.id), NOW()
FROM NOTE n
WHERE n.id BETWEEN 6 AND 65 AND (n.id % 3) = 0;

-- Comments: two comments per new note
INSERT INTO `COMMENT` (id, note_id, student_id, content, created_at)
SELECT (200 + (n.id*2 - 1)), n.id, ((n.id + 1) % 35) + 1, CONCAT('Commento automatico 1 sulla nota ', n.id), NOW()
FROM NOTE n WHERE n.id BETWEEN 6 AND 65
UNION ALL
SELECT (200 + (n.id*2)), n.id, ((n.id + 2) % 35) + 1, CONCAT('Commento automatico 2 sulla nota ', n.id), NOW()
FROM NOTE n WHERE n.id BETWEEN 6 AND 65;

-- Likes: three likes per new note
INSERT INTO `LIKE` (student_id, note_id, created_at)
SELECT ((n.id + 3) % 35) + 1, n.id, NOW() FROM NOTE n WHERE n.id BETWEEN 6 AND 65
UNION ALL
SELECT ((n.id + 4) % 35) + 1, n.id, NOW() FROM NOTE n WHERE n.id BETWEEN 6 AND 65
UNION ALL
SELECT ((n.id + 5) % 35) + 1, n.id, NOW() FROM NOTE n WHERE n.id BETWEEN 6 AND 65;

-- Notifications for first like and first comment of each new note
-- NOTE: schema expects (id, note_id, sender_id, recipient_id, type, message, payload, created_at)
INSERT INTO NOTIFICATION (id, note_id, sender_id, recipient_id, type, message, payload, created_at)
SELECT (300 + n.id), n.id, ((n.id + 3) % 35) + 1, n.student_id, 'like', CONCAT('Utente ha messo like alla tua nota "', n.title, '"'), JSON_OBJECT('note_id', n.id), NOW()
FROM NOTE n WHERE n.id BETWEEN 6 AND 65
UNION ALL
SELECT (400 + n.id), n.id, ((n.id + 1) % 35) + 1, n.student_id, 'comment', CONCAT('Utente ha commentato la tua nota "', n.title, '"'), JSON_OBJECT('note_id', n.id), NOW()
FROM NOTE n WHERE n.id BETWEEN 6 AND 65;

-- Additional note downloads for new notes
INSERT INTO NOTE_DOWNLOAD (student_id, note_id, downloaded_at)
SELECT ((n.id + 2) % 35) + 1, n.id, NOW() FROM NOTE n WHERE n.id BETWEEN 6 AND 65 AND (n.id % 4) = 0;

-- Reset AUTO_INCREMENT to avoid collisions
ALTER TABLE `USER` AUTO_INCREMENT = 1000;
ALTER TABLE `NOTE` AUTO_INCREMENT = 2000;
ALTER TABLE `FILE` AUTO_INCREMENT = 3000;
ALTER TABLE `COMMENT` AUTO_INCREMENT = 4000;
ALTER TABLE `NOTIFICATION` AUTO_INCREMENT = 5000;
ALTER TABLE `COURSE` AUTO_INCREMENT = 100;
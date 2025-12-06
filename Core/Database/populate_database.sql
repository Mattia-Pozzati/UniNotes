USE share_notes_app;

-- -----------------------------
-- UTENTI
-- -----------------------------
INSERT INTO user (name, email, password_hash, role, reputation) VALUES
('Mario Rossi', 'mario.rossi@example.com', MD5('password1'), 'student', 10),
('Lucia Bianchi', 'lucia.bianchi@example.com', MD5('password2'), 'student', 5),
('Admin', 'admin@example.com', MD5('adminpass'), 'admin', 0);

-- -----------------------------
-- CORSI
-- -----------------------------
INSERT INTO course (name) VALUES
('Matematica 1'),
('Fisica 2'),
('Informatica');

-- -----------------------------
-- TAG
-- -----------------------------
INSERT INTO tag (name) VALUES
('riassunto'),
('esercizi'),
('formulario'),
('teoria');

-- -----------------------------
-- NOTE
-- -----------------------------
INSERT INTO note (student_id, title, visibility) VALUES
(1, 'Appunti Matematica 1 - Integrali', 'public'),
(2, 'Fisica 2 - Appunti Meccanica', 'course'),
(1, 'Informatica - Algoritmi', 'private');

-- -----------------------------
-- NOTE ↔ CORSI
-- -----------------------------
INSERT INTO note_course (note_id, course_id) VALUES
(1, 1),
(2, 2),
(3, 3);

-- -----------------------------
-- NOTE ↔ TAG
-- -----------------------------
INSERT INTO note_tag (note_id, tag_id) VALUES
(1, 1),
(1, 3),
(2, 2),
(3, 4);

-- -----------------------------
-- FILE
-- -----------------------------
INSERT INTO file (note_id, filename, filepath, mime_type, size, current_version) VALUES
(1, 'integrali.pdf', '/files/note_1/file_1/v1.pdf', 'application/pdf', 204800, 1),
(2, 'meccanica.docx', '/files/note_2/file_2/v1.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 102400, 1),
(3, 'algoritmi.txt', '/files/note_3/file_3/v1.txt', 'text/plain', 1024, 1);

-- -----------------------------
-- FILE_VERSION
-- -----------------------------
INSERT INTO file_version (file_id, version_number, filepath, size, hash) VALUES
(1, 1, '/files/note_1/file_1/v1.pdf', 204800, MD5('v1')),
(1, 2, '/files/note_1/file_1/v2.pdf', 205000, MD5('v2')),
(2, 1, '/files/note_2/file_2/v1.docx', 102400, MD5('v1')),
(3, 1, '/files/note_3/file_3/v1.txt', 1024, MD5('v1'));

-- -----------------------------
-- COMMENTI
-- -----------------------------
INSERT INTO comment (note_id, student_id, content) VALUES
(1, 2, 'Ottimo riassunto, grazie!'),
(2, 1, 'Non capisco il passaggio sulla dinamica.'),
(3, 2, 'Interessante, puoi spiegare meglio l’algoritmo?');

-- -----------------------------
-- LIKE
-- -----------------------------
INSERT INTO `like` (student_id, note_id) VALUES
(2, 1),
(1, 2);

-- -----------------------------
-- NOTIFICHE
-- -----------------------------
INSERT INTO notification (student_id, type, message) VALUES
(1, 'like', 'Lucia ha messo like alla tua nota "Appunti Matematica 1 - Integrali".'),
(2, 'comment', 'Mario ha commentato la tua nota "Fisica 2 - Appunti Meccanica".');

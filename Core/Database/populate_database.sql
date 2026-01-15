USE `uninotes`;

-- Clear existing (safe to run on dev)
SET FOREIGN_KEY_CHECKS=0;
-- use DELETE (safer with FK relationships / when running commands interactively)
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

-- Users
INSERT INTO `USER` (id,name,email,password_hash,university,role,reputation) VALUES
(1,'Alice Rossi','alice@example.com','$2y$…', 'Università di A', 'student', 10),
(2,'Luca Bianchi','luca@example.com','$2y$…', 'Università di B', 'student', 5),
(3,'Admin','admin@example.com','$2y$…', NULL, 'admin', 0);

-- Courses
INSERT INTO COURSE (id,name) VALUES
(1,'Analisi 1'),
(2,'Fisica 1');

-- Notes
INSERT INTO NOTE (id,student_id,title,description,note_type,visibility,created_at,updated_at) VALUES
(1,1,'Riassunto Analisi 1','Breve riassunto degli argomenti principali', 'riassunto','public', NOW(), NOW()),
(2,2,'Esercizi Fisica','Soluzioni esercizi del capitolo 3', 'esercizi','course', NOW(), NOW()),
(3,1,'Formulario Matematica','Formule utili per il compito', 'formulario','private', NOW(), NOW());

-- Note-Course links
INSERT INTO NOTE_COURSE (note_id,course_id) VALUES
(1,1),
(2,2);

-- Files (pointing to files/ directories — created by script)
INSERT INTO FILE (id,note_id,filename,filepath,mime_type,size,format,hash) VALUES
(1,1,'riassunto_analisi1.txt','files/note_1/file_1.txt','text/plain', 1234,'txt','hash1'),
(2,2,'esercizi_fisica.txt','files/note_2/file_2.txt','text/plain', 2345,'txt','hash2'),
(3,3,'formulario_mat.txt','files/note_3/file_3.txt','text/plain', 1500,'txt','hash3');

-- Comments
-- include created_at column to match values
INSERT INTO `COMMENT` (id,note_id,student_id,content,created_at) VALUES
(1,1,2,'Ottimo riassunto, grazie!', NOW()),
(2,1,1,'Grazie a te!', NOW());

-- Likes
INSERT INTO `LIKE` (student_id,note_id) VALUES
(2,1),
(1,2);

-- Notifications
INSERT INTO NOTIFICATION (id,sender_id,recipient_id,type,message,payload) VALUES
(1,2,1,'like','Luca ha messo like alla tua nota', JSON_OBJECT('note_id',1)),
(2,1,2,'comment','Alice ha commentato la tua nota', JSON_OBJECT('note_id',1,'comment_id',1));

-- Note downloads
INSERT INTO NOTE_DOWNLOAD (student_id,note_id) VALUES
(2,1),
(1,2);

-- Adjust AUTO_INCREMENTs
ALTER TABLE `USER` AUTO_INCREMENT = 100;
ALTER TABLE `NOTE` AUTO_INCREMENT = 100;
ALTER TABLE `FILE` AUTO_INCREMENT = 100;
ALTER TABLE `COMMENT` AUTO_INCREMENT = 100;
ALTER TABLE `NOTIFICATION` AUTO_INCREMENT = 100;

SELECT 'Seed complete' AS msg;
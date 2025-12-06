-- Creazione del database  
CREATE DATABASE IF NOT EXISTS uninotes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE uninotes;

-- Tabella utenti  
CREATE TABLE user (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(255) NOT NULL,
    email         VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('student','admin') NOT NULL DEFAULT 'student',
    reputation    INT UNSIGNED NOT NULL DEFAULT 0,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabella corsi  
CREATE TABLE course (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- Tabella tag  
CREATE TABLE tag (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Tabella note  
CREATE TABLE note (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id  INT UNSIGNED NOT NULL,
    title       VARCHAR(255) NOT NULL,
    visibility  ENUM('public','course','private') NOT NULL DEFAULT 'public',
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_deleted  TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (student_id) REFERENCES user(id)
) ENGINE=InnoDB;

-- Tabella many‑to‑many note ↔ corsi  
CREATE TABLE note_course (
    note_id   INT UNSIGNED NOT NULL,
    course_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (note_id, course_id),
    FOREIGN KEY (note_id)   REFERENCES note(id)   ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES course(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabella many‑to‑many note ↔ tag  
CREATE TABLE note_tag (
    note_id INT UNSIGNED NOT NULL,
    tag_id  INT UNSIGNED NOT NULL,
    PRIMARY KEY (note_id, tag_id),
    FOREIGN KEY (note_id) REFERENCES note(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id)  REFERENCES tag(id)  ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabella file principale  
CREATE TABLE file (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    note_id         INT UNSIGNED NOT NULL,
    filename        VARCHAR(255) NOT NULL,
    filepath        VARCHAR(255) NOT NULL,
    mime_type       VARCHAR(100) NOT NULL,
    size            INT UNSIGNED NOT NULL,
    current_version INT UNSIGNED NOT NULL DEFAULT 1,
    FOREIGN KEY (note_id) REFERENCES note(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabella versioni file  
CREATE TABLE file_version (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    file_id        INT UNSIGNED NOT NULL,
    version_number INT UNSIGNED NOT NULL,
    filepath       VARCHAR(255) NOT NULL,
    size           INT UNSIGNED NOT NULL,
    hash           VARCHAR(255) NOT NULL,
    created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (file_id) REFERENCES file(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabella commenti  
CREATE TABLE comment (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    note_id    INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    content    TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (note_id)    REFERENCES note(id)    ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES user(id)    ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabella like  
CREATE TABLE `like` (
    student_id INT UNSIGNED NOT NULL,
    note_id    INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (student_id, note_id),
    FOREIGN KEY (student_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (note_id)    REFERENCES note(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabella notifiche  
CREATE TABLE notification (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id  INT UNSIGNED NOT NULL,
    type        ENUM('comment','like','system') NOT NULL,
    message     VARCHAR(500) NOT NULL,
    is_read     TINYINT(1) NOT NULL DEFAULT 0,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES user(id) ON DELETE CASCADE
) ENGINE=InnoDB;

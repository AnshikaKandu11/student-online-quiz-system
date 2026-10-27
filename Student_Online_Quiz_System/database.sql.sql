-- ============================================================
-- STUDENT ONLINE QUIZ SYSTEM
-- COMPLETE DATABASE SQL
-- ============================================================

-- ============================================================
-- 1. CREATE DATABASE
-- ============================================================

CREATE DATABASE IF NOT EXISTS `online_quiz`
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE `online_quiz`;

-- ============================================================
-- 2. SQL SETTINGS
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 3. REMOVE OLD TABLES IF THEY EXIST
-- ============================================================

DROP TABLE IF EXISTS `results`;
DROP TABLE IF EXISTS `questions`;
DROP TABLE IF EXISTS `quizzes`;
DROP TABLE IF EXISTS `users`;

-- ============================================================
-- 4. USERS TABLE
-- ============================================================

CREATE TABLE `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin','student') NOT NULL DEFAULT 'student',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- ============================================================
-- 5. QUIZZES TABLE
-- ============================================================

CREATE TABLE `quizzes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,

    `title` VARCHAR(200) NOT NULL,
    `description` TEXT DEFAULT NULL,

    `start_at` DATETIME DEFAULT NULL,
    `end_at` DATETIME DEFAULT NULL,

    `duration` INT(11) NOT NULL DEFAULT 10,

    `max_attempts` INT(11) NOT NULL DEFAULT 1,

    `passing_percentage` DECIMAL(5,2) NOT NULL DEFAULT 50.00,

    `random_questions` TINYINT(1) NOT NULL DEFAULT 0,

    `random_options` TINYINT(1) NOT NULL DEFAULT 0,

    `show_result` TINYINT(1) NOT NULL DEFAULT 1,

    `allow_review` TINYINT(1) NOT NULL DEFAULT 1,

    `negative_marking` TINYINT(1) NOT NULL DEFAULT 0,

    `negative_marks` DECIMAL(5,2) NOT NULL DEFAULT 0.00,

    `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',

    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- ============================================================
-- 6. QUESTIONS TABLE
-- ============================================================

CREATE TABLE `questions` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,

    `quiz_id` INT(11) NOT NULL,

    `question` TEXT NOT NULL,

    `option_a` VARCHAR(500) NOT NULL,
    `option_b` VARCHAR(500) NOT NULL,
    `option_c` VARCHAR(500) NOT NULL,
    `option_d` VARCHAR(500) NOT NULL,

    `correct_answer` ENUM('A','B','C','D') NOT NULL,

    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    `marks` DECIMAL(5,2) NOT NULL DEFAULT 1.00,

    PRIMARY KEY (`id`),

    KEY `quiz_id` (`quiz_id`),

    CONSTRAINT `questions_ibfk_1`
        FOREIGN KEY (`quiz_id`)
        REFERENCES `quizzes` (`id`)
        ON DELETE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- ============================================================
-- 7. RESULTS TABLE
-- ============================================================

CREATE TABLE `results` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,

    `user_id` INT(11) NOT NULL,

    `quiz_id` INT(11) NOT NULL,

    `score` INT(11) NOT NULL,

    `total_questions` INT(11) NOT NULL,

    `percentage` DECIMAL(5,2) NOT NULL,

    `attempted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    `passed` TINYINT(1) NOT NULL DEFAULT 0,

    PRIMARY KEY (`id`),

    KEY `user_id` (`user_id`),

    KEY `quiz_id` (`quiz_id`),

    CONSTRAINT `results_ibfk_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE,

    CONSTRAINT `results_ibfk_2`
        FOREIGN KEY (`quiz_id`)
        REFERENCES `quizzes` (`id`)
        ON DELETE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- ============================================================
-- 8. INSERT ADMIN AND STUDENT USERS
-- ============================================================

INSERT INTO `users`
(
    `id`,
    `name`,
    `email`,
    `password`,
    `role`,
    `created_at`
)
VALUES
(
    1,
    'Administrator',
    'admin@example.com',
    '$2y$10$lC.VTZchI6HigtcqVIzajuDnLFLtlxmXc1fBHE0ICkQC8AYv0LGLG',
    'admin',
    '2026-09-23 15:56:06'
),
(
    2,
    'student2',
    '1@gmail.com',
    '$2y$10$1EbXZ0LHH7eDUwkxV.t7mOHtsKRPX9XbjpVsMg1JcQW7K3IVMir6S',
    'student',
    '2026-09-23 15:57:34'
),
(
    3,
    'sample',
    'sample@gmail.com',
    '$2y$10$MZhST.AtCqGUsjG8ekCltOfX/fZpnklTyTX3eSozTEIiRBu81BdDm',
    'student',
    '2026-09-24 15:46:09'
);

-- ============================================================
-- 9. INSERT QUIZZES
-- ============================================================

INSERT INTO `quizzes`
(
    `id`,
    `title`,
    `description`,
    `start_at`,
    `end_at`,
    `duration`,
    `max_attempts`,
    `passing_percentage`,
    `random_questions`,
    `random_options`,
    `show_result`,
    `allow_review`,
    `negative_marking`,
    `negative_marks`,
    `status`,
    `created_at`
)
VALUES
(
    1,
    'Computer Science Fundamentals',
    'Basic Computer Science online assessment.',
    NULL,
    NULL,
    10,
    1,
    50.00,
    0,
    0,
    1,
    1,
    0,
    0.00,
    'active',
    '2026-09-23 15:56:06'
),
(
    2,
    'practical',
    '',
    NULL,
    NULL,
    10,
    1,
    50.00,
    0,
    0,
    1,
    1,
    0,
    0.00,
    'active',
    '2026-09-24 15:48:34'
),
(
    3,
    'sample',
    'new',
    NULL,
    NULL,
    10,
    1,
    50.00,
    0,
    0,
    1,
    1,
    0,
    0.00,
    'active',
    '2026-09-24 15:59:41'
);

-- ============================================================
-- 10. INSERT QUESTIONS
-- ============================================================

INSERT INTO `questions`
(
    `id`,
    `quiz_id`,
    `question`,
    `option_a`,
    `option_b`,
    `option_c`,
    `option_d`,
    `correct_answer`,
    `created_at`,
    `marks`
)
VALUES
(
    1,
    1,
    'Which language is used to structure web pages?',
    'HTML',
    'Python',
    'SQL',
    'Java',
    'A',
    '2026-09-23 15:56:06',
    1.00
),
(
    2,
    1,
    'Which language is mainly used for styling web pages?',
    'PHP',
    'CSS',
    'Python',
    'SQL',
    'B',
    '2026-09-23 15:56:06',
    1.00
),
(
    3,
    1,
    'Which database is used in this project?',
    'MongoDB',
    'Oracle',
    'MySQL',
    'Firebase',
    'C',
    '2026-09-23 15:56:06',
    1.00
),
(
    4,
    1,
    'What does PHP mainly provide in this project?',
    'Backend processing',
    'Image editing',
    'Operating system',
    'Antivirus',
    'A',
    '2026-09-23 15:56:06',
    1.00
),
(
    5,
    1,
    'Which language is used to structure web pages?',
    'a',
    'b',
    'c',
    'd',
    'A',
    '2026-09-24 16:29:29',
    1.00
);

-- ============================================================
-- 11. INSERT EXISTING RESULTS
-- ============================================================

INSERT INTO `results`
(
    `id`,
    `user_id`,
    `quiz_id`,
    `score`,
    `total_questions`,
    `percentage`,
    `attempted_at`,
    `passed`
)
VALUES
(
    1,
    2,
    1,
    0,
    4,
    0.00,
    '2026-09-23 15:57:52',
    0
),
(
    2,
    3,
    1,
    3,
    5,
    60.00,
    '2026-09-24 16:30:29',
    0
),
(
    3,
    3,
    1,
    0,
    5,
    0.00,
    '2026-09-24 16:32:54',
    0
),
(
    4,
    3,
    1,
    4,
    5,
    80.00,
    '2026-09-24 16:36:05',
    0
);

-- ============================================================
-- 12. SET AUTO-INCREMENT VALUES
-- ============================================================

ALTER TABLE `users`
    AUTO_INCREMENT = 4;

ALTER TABLE `quizzes`
    AUTO_INCREMENT = 4;

ALTER TABLE `questions`
    AUTO_INCREMENT = 6;

ALTER TABLE `results`
    AUTO_INCREMENT = 5;

-- ============================================================
-- 13. ENABLE FOREIGN KEY CHECKS
-- ============================================================

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- DATABASE SETUP COMPLETE
-- ============================================================
USE online_quiz;

-- ============================================================
-- DATASET 1: COMPUTER SCIENCE THEORY QUIZ
-- quiz_id = 1
-- ============================================================

INSERT INTO questions
(quiz_id, question, option_a, option_b, option_c, option_d, correct_answer, marks)
VALUES

(1, 'Which language is mainly used for creating the structure of a web page?',
'HTML', 'CSS', 'SQL', 'Python', 'A', 1),

(1, 'Which language is mainly used to style HTML web pages?',
'Java', 'CSS', 'SQL', 'PHP', 'B', 1),

(1, 'Which language is commonly used for database queries?',
'HTML', 'Python', 'SQL', 'CSS', 'C', 1),

(1, 'What does CPU stand for?',
'Central Processing Unit', 'Computer Personal Unit', 'Central Program Utility', 'Computer Processing Utility', 'A', 1),

(1, 'Which of the following is an operating system?',
'Python', 'Windows', 'HTML', 'MySQL', 'B', 1),

(1, 'Which data structure follows LIFO principle?',
'Queue', 'Array', 'Stack', 'Linked List', 'C', 1),

(1, 'Which data structure follows FIFO principle?',
'Stack', 'Queue', 'Tree', 'Graph', 'B', 1),

(1, 'What is the full form of RAM?',
'Random Access Memory', 'Read Access Memory', 'Rapid Access Module', 'Random Application Memory', 'A', 1),

(1, 'Which of the following is a programming language?',
'HTML', 'CSS', 'Python', 'HTTP', 'C', 1),

(1, 'Which software is used to manage relational databases?',
'MySQL', 'Photoshop', 'PowerPoint', 'Notepad', 'A', 1);


-- ============================================================
-- DATASET 2: PRACTICAL BASED CODING QUIZ
-- quiz_id = 2
-- ============================================================

INSERT INTO questions
(quiz_id, question, option_a, option_b, option_c, option_d, correct_answer, marks)
VALUES

(2, 'Which HTML tag is used to create a hyperlink?',
'<link>', '<a>', '<href>', '<url>', 'B', 1),

(2, 'Which HTML tag is used to display an image?',
'<image>', '<pic>', '<img>', '<src>', 'C', 1),

(2, 'Which HTML tag is used to create a paragraph?',
'<para>', '<p>', '<paragraph>', '<text>', 'B', 1),

(2, 'Which CSS property is used to change text color?',
'font-style', 'text-color', 'color', 'font-color', 'C', 1),

(2, 'Which CSS property is used to change the background color?',
'background-color', 'bg', 'color-background', 'background-style', 'A', 1),

(2, 'Which Python function is used to display output?',
'display()', 'show()', 'print()', 'output()', 'C', 1),

(2, 'Which symbol is used for a single-line comment in Python?',
'//', '/* */', '#', '<!-- -->', 'C', 1),

(2, 'Which SQL command is used to retrieve data from a table?',
'GET', 'SELECT', 'FETCH', 'SHOWDATA', 'B', 1),

(2, 'Which SQL command is used to add a new record?',
'ADD', 'INSERT', 'UPDATE', 'CREATE', 'B', 1),

(2, 'Which SQL command is used to modify existing records?',
'CHANGE', 'MODIFY', 'UPDATE', 'EDIT', 'C', 1),

(2, 'Which keyword is used to define a class in Python?',
'object', 'class', 'define', 'struct', 'B', 1),

(2, 'Which Python data type stores multiple values in an ordered collection?',
'Integer', 'Boolean', 'List', 'Float', 'C', 1),

(2, 'What will print(2 + 3) display in Python?',
'23', '5', '6', 'Error', 'B', 1),

(2, 'What will print(10 > 5) display in Python?',
'10', '5', 'True', 'False', 'C', 1),

(2, 'Which operator is used for equality comparison in Python?',
'=', '==', '===', '!=', 'B', 1);


-- ============================================================
-- DATASET 3: SAMPLE QUIZ
-- quiz_id = 3
-- ============================================================

INSERT INTO questions
(quiz_id, question, option_a, option_b, option_c, option_d, correct_answer, marks)
VALUES

(3, 'Which of these is used to store data permanently in a database?',
'Variable', 'Table', 'Loop', 'Function', 'B', 1),

(3, 'Which key uniquely identifies a record in a database table?',
'Foreign Key', 'Primary Key', 'Candidate Key', 'Normal Key', 'B', 1),

(3, 'Which language is widely used for object-oriented programming?',
'Java', 'HTML', 'SQL', 'CSS', 'A', 1),

(3, 'Which symbol is commonly used to start a comment in SQL?',
'//', '#', '<!--', '/*', 'D', 1),

(3, 'Which command creates a new database in MySQL?',
'MAKE DATABASE', 'NEW DATABASE', 'CREATE DATABASE', 'ADD DATABASE', 'C', 1),

(3, 'Which command removes a table from a database?',
'DELETE TABLE', 'DROP TABLE', 'REMOVE TABLE', 'CLEAR TABLE', 'B', 1),

(3, 'Which HTML tag creates the largest heading?',
'<h6>', '<head>', '<h1>', '<heading>', 'C', 1),

(3, 'Which HTML tag is used to create a line break?',
'<break>', '<br>', '<lb>', '<line>', 'B', 1),

(3, 'Which Python keyword is used for a condition?',
'if', 'check', 'condition', 'when', 'A', 1),

(3, 'Which loop is commonly used when the number of iterations is known?',
'if', 'for', 'switch', 'try', 'B', 1),

(3, 'Which of the following is NOT a programming language?',
'Python', 'Java', 'HTML', 'C++', 'C', 1),

(3, 'What is used to connect a web page to a CSS file?',
'<css>', '<style>', '<link>', '<connect>', 'C', 1);

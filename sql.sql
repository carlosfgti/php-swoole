create database if not exists phpswoole;
use phpswoole;
CREATE TABLE example (
    id INT AUTO_INCREMENT PRIMARY KEY,
    column_a VARCHAR(255) NOT NULL,
    column_b VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

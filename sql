CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL
);

CREATE TABLE passwords (
id INT AUTO_INCREMENT PRIMARY KEY,
service_name VARCHAR(255),
username VARCHAR(255),
encrypted_password TEXT,
user_id INT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id)
);

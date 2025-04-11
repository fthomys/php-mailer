USE projekt;

CREATE TABLE user (
                      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                      username VARCHAR(50) NOT NULL UNIQUE,
                      display_name VARCHAR(50) NOT NULL,
                      password_hash VARCHAR(255) NOT NULL,
                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE messages (
                          id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                          sender_id INT UNSIGNED NOT NULL,
                          receiver_id INT UNSIGNED NOT NULL,
                          message TEXT NOT NULL,
                          sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          read_at TIMESTAMP NULL DEFAULT NULL,

                          FOREIGN KEY (sender_id) REFERENCES user(id) ON DELETE CASCADE,
                          FOREIGN KEY (receiver_id) REFERENCES user(id) ON DELETE CASCADE
) ENGINE=InnoDB;

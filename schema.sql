USE projekt;

CREATE TABLE user (
                      id BIGINT UNSIGNED PRIMARY KEY,
                      username VARCHAR(50) NOT NULL UNIQUE,
                      display_name VARCHAR(50) NOT NULL,
                      password_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE messages (
                          id BIGINT UNSIGNED PRIMARY KEY,
                          sender_id BIGINT UNSIGNED NOT NULL,
                          receiver_id BIGINT UNSIGNED NOT NULL,
                          message TEXT NOT NULL,
                          sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          read_at TIMESTAMP NULL DEFAULT NULL,

                          FOREIGN KEY (sender_id) REFERENCES user(id) ON DELETE CASCADE,
                          FOREIGN KEY (receiver_id) REFERENCES user(id) ON DELETE CASCADE
) ENGINE=InnoDB;

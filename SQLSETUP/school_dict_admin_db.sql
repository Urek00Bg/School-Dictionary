CREATE TABLE administrators (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 1,      
    is_superadmin TINYINT(1) NOT NULL DEFAULT 0,   
    force_password_change TINYINT(1) NOT NULL DEFAULT 0, 
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    last_password_reset_at DATETIME DEFAULT NULL,
    last_password_reset_by INT DEFAULT NULL,
    FOREIGN KEY (last_password_reset_by) REFERENCES administrators(id) ON DELETE SET NULL
);
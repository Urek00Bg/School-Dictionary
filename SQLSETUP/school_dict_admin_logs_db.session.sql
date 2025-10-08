CREATE TABLE IF NOT EXISTS admin_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  actor_admin_id INT NULL,
  target_admin_id INT NULL,
  action VARCHAR(64) NOT NULL,
  meta JSON NULL,
  ip VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (created_at),
  INDEX (action),
  INDEX (actor_admin_id),
  INDEX (target_admin_id),
  CONSTRAINT fk_logs_actor  FOREIGN KEY (actor_admin_id)
    REFERENCES administrators(id) ON DELETE SET NULL,
  CONSTRAINT fk_logs_target FOREIGN KEY (target_admin_id)
    REFERENCES administrators(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET GLOBAL event_scheduler = ON;

CREATE EVENT IF NOT EXISTS cleanup_admin_logs
ON SCHEDULE EVERY 1 DAY
DO
  DELETE FROM admin_logs
  WHERE (action = 'login_success' AND created_at < NOW() - INTERVAL 90 DAY)
     OR (action = 'login_failed' AND created_at < NOW() - INTERVAL 180 DAY);

SHOW EVENTS LIKE 'cleanup_admin_logs';

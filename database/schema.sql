CREATE DATABASE IF NOT EXISTS service_on_phone
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE service_on_phone;

CREATE TABLE IF NOT EXISTS service_leads (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(24) NOT NULL,
  email VARCHAR(190) NULL,
  message TEXT NULL,
  source VARCHAR(24) NOT NULL DEFAULT 'contact',
  ip_address VARCHAR(45) NULL,
  user_agent VARCHAR(500) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY service_leads_created_at_index (created_at),
  KEY service_leads_phone_index (phone),
  KEY service_leads_source_index (source)
) ENGINE=InnoDB
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

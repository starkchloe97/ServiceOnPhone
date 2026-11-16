-- Cloudflare D1 uses SQLite syntax.
CREATE TABLE IF NOT EXISTS service_leads (
  id TEXT PRIMARY KEY NOT NULL,
  name TEXT NOT NULL CHECK (length(name) <= 100),
  phone TEXT NOT NULL CHECK (length(phone) <= 24),
  email TEXT,
  message TEXT,
  source TEXT NOT NULL DEFAULT 'contact'
    CHECK (source IN ('contact', 'callback', 'service')),
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_service_leads_created_at
  ON service_leads (created_at);

CREATE INDEX IF NOT EXISTS idx_service_leads_phone
  ON service_leads (phone);

DROP TRIGGER IF EXISTS content_checksum_insert;
DROP TRIGGER IF EXISTS content_checksum_update;
CREATE TRIGGER content_checksum_insert BEFORE INSERT ON PropertiesContent FOR EACH ROW SET NEW.cs=SHA1(NEW.content);
CREATE TRIGGER content_checksum_update BEFORE UPDATE ON PropertiesContent FOR EACH ROW SET NEW.cs=SHA1(NEW.content);

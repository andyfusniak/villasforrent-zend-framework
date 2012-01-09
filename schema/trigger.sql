DROP TRIGGER IF EXISTS mycs_insert;
DROP TRIGGER IF EXISTS mycs_update;
CREATE TRIGGER mycs_insert BEFORE INSERT ON PropertiesContent FOR EACH ROW SET NEW.cs=SHA1(NEW.content) COLLATE utf8_general_ci;
CREATE TRIGGER mycs_update BEFORE UPDATE ON PropertiesContent FOR EACH ROW SET NEW.cs=SHA1(NEW.content) COLLATE utf8_general_ci;

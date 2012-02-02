CREATE TRIGGER mycs_insert BEFORE INSERT ON PropertiesContent FOR EACH ROW SET NEW.cs=SHA1(NEW.content);
CREATE TRIGGER mycs_update BEFORE UPDATE ON PropertiesContent FOR EACH ROW SET NEW.cs=SHA1(NEW.content);

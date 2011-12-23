CREATE TRIGGER mycs_test_insert BEFORE INSERT ON test_content FOR EACH ROW SET NEW.checksum=SHA1(NEW.content);
CREATE TRIGGER mycs_test_update BEFORE UPDATE ON test_content FOR EACH ROW SET NEW.checksum=SHA1(NEW.content);

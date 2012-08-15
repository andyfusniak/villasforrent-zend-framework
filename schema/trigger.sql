DROP TRIGGER IF EXISTS content_checksum_insert;
DROP TRIGGER IF EXISTS content_checksum_update;
CREATE TRIGGER content_checksum_insert BEFORE INSERT ON PropertiesContent FOR EACH ROW SET NEW.cs=SHA1(NEW.content);
CREATE TRIGGER content_checksum_update BEFORE UPDATE ON PropertiesContent FOR EACH ROW SET NEW.cs=SHA1(NEW.content);

DROP TRIGGER IF EXISTS locationcontent_checksum_insert;
DROP TRIGGER IF EXISTS locationcontent_checksum_update;
CREATE TRIGGER locationcontent_checksum_insert BEFORE INSERT ON LocationsContent FOR EACH ROW SET NEW.cs=SHA1(NEW.content);
CREATE TRIGGER locationcontent_checksum_update BEFORE UPDATE ON LocationsContent FOR EACH ROW SET NEW.cs=SHA1(NEW.content);

DROP TRIGGER IF EXISTS MessageThread_LastMessageTrigger;
CREATE TRIGGER MessageThread_LastMessageTrigger AFTER INSERT ON Messages FOR EACH ROW UPDATE MessageThreads SET lastMessage = NEW.added, summary = LEFT(NEW.body, 64), messageCount = messageCount + 1 WHERE idMessageThread = NEW.idMessageThread;


DROP TRIGGER IF EXISTS InvoiceItem_TotalTrigger_insert;
CREATE TRIGGER InvoiceItem_TotalTrigger_insert AFTER INSERT ON InvoiceItems FOR EACH ROW UPDATE Invoices SET total = (SELECT SUM(lineTotal) FROM InvoiceItems WHERE idInvoice = NEW.idInvoice);

DROP TRIGGER IF EXISTS InvoiceItem_TotalTrigger_update;
CREATE TRIGGER InvoiceItem_TotalTrigger_update AFTER UPDATE ON InvoiceItems FOR EACH ROW UPDATE Invoices SET total = (SELECT SUM(lineTotal) FROM InvoiceItems WHERE idInvoice = NEW.idInvoice);

DROP TRIGGER IF EXISTS InvoiceItem_TotalTrigger_delete;
CREATE TRIGGER InvoiceItem_TotalTrigger_delete AFTER DELETE ON InvoiceItems FOR EACH ROW UPDATE Invoices SET total = (SELECT SUM(lineTotal) FROM InvoiceItems WHERE idInvoice = OLD.idInvoice);


DROP TRIGGER IF EXISTS CreditNoteItems_TotalTrigger_insert;
CREATE TRIGGER CreditNoteItem_TotalTrigger_insert AFTER INSERT ON CreditNoteItems FOR EACH ROW UPDATE CreditNotes SET total = (SELECT SUM(lineTotal) FROM CreditNoteItems WHERE idCreditNote = NEW.idCreditNote);

DROP TRIGGER IF EXISTS CreditNoteItems_TotalTrigger_update;
CREATE TRIGGER CreditNoteItem_TotalTrigger_update AFTER UPDATE ON CreditNoteItems FOR EACH ROW UPDATE CreditNotes SET total = (SELECT SUM(lineTotal) FROM CreditNoteItems WHERE idCreditNote = NEW.idCreditNote);

DROP TRIGGER IF EXISTS CreditNoteItems_TotalTrigger_delete;
CREATE TRIGGER CreditNoteItem_TotalTrigger_delete AFTER DELETE ON CreditNoteItems FOR EACH ROW UPDATE CreditNotes SET total = (SELECT SUM(lineTotal) FROM CreditNoteItems WHERE idCreditNote = OLD.idCreditNote);

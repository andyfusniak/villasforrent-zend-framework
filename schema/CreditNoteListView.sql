DROP VIEW IF EXISTS CreditNoteListView;
CREATE VIEW CreditNoteListView AS
SELECT idCreditNote, creditNoteDate, name, total, currency, refunded
FROM CreditNotes AS C
INNER JOIN Addresses AS A
    ON A.idAddress = C.idCreditAddress;

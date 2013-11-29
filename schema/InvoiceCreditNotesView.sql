DROP VIEW IF EXISTS InvoiceCreditNotesView;
CREATE VIEW InvoiceCreditNotesView AS
SELECT IC.idInvoiceCreditNote, C.idCreditNote, IC.idInvoice,
       C.total, C.currency, C.idCreditAddress, C.itemLastAdded, C.refunded,
       DATE(IC.added) AS appliedDate, C.added AS creditAdded,
       C.updated AS creditUpdated
FROM CreditNotes AS C
INNER JOIN InvoiceCreditNotes AS IC
    ON C.idCreditNote = IC.idCreditNote;

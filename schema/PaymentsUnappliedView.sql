DROP VIEW IF EXISTS PaymentsUnappliedView;
CREATE VIEW PaymentsUnappliedView AS
SELECT P.*, IP.idInvoice FROM Payments AS P
LEFT OUTER JOIN InvoicePayments AS IP
    ON P.idPayment = IP.idPayment
WHERE IP.idPayment IS NULL;

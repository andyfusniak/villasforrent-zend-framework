DROP VIEW IF EXISTS PaymentsInvoicePaymentsView;
CREATE VIEW PaymentsInvoicePaymentsView AS
SELECT P.*, IP.idInvoice FROM Payments AS P
LEFT OUTER JOIN InvoicePayments AS IP
    ON IP.idPayment = P.idPayment;

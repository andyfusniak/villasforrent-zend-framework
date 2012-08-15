DROP VIEW IF EXISTS InvoicePaymentsView;
CREATE VIEW InvoicePaymentsView AS
SELECT IP.idInvoicePayment, P.idPayment, IP.idInvoice, P.dateReceived,
       P.amount, P.currency, P.method,
       P.notes, DATE(IP.added) AS appliedDate,
       P.added as paymentAdded,
       P.updated as paymentUpdated
FROM Payments AS P
INNER JOIN InvoicePayments AS IP
    ON P.idPayment = IP.idPayment;

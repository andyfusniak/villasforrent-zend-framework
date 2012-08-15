DROP VIEW IF EXISTS InvoiceListView;
CREATE VIEW InvoiceListView AS
SELECT idInvoice, invoiceDate, name, total, currency, status
FROM Invoices AS I
INNER JOIN Addresses AS A
    ON A.idAddress = I.idBillingAddress;

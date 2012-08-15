CREATE TABLE IF NOT EXISTS InvoicePayments (
    idInvoicePayment int(10) unsigned NOT NULL AUTO_INCREMENT,
    idPayment int(8) unsigned zerofill NOT NULL,
    idInvoice int(8) unsigned zerofill NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idInvoicePayment),
    UNIQUE KEY idPayment_CompositeKey (idPayment, idInvoice),
    KEY idPayment (idPayment),
    KEY idInvoice (idInvoice),
    KEY added (added),
    KEY updated (updated),
    CONSTRAINT invoicepayments_ibfk_1 FOREIGN KEY (idPayment) REFERENCES Payments (idPayment),
    CONSTRAINT invoicepayments_ibfk_2 FOREIGN KEY (idInvoice) REFERENCES Invoices (idInvoice)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS InvoiceCreditNotes (
    idInvoiceCreditNote int(10) unsigned NOT NULL AUTO_INCREMENT,
    idInvoice int(8) unsigned zerofill NOT NULL,
    idCreditNote int(8) unsigned zerofill NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idInvoiceCreditNote),
    KEY idInvoice (idInvoice),
    KEY idCreditNote (idCreditNote),
    KEY invoiceCreditNoteCompositeKey (idInvoice,idCreditNote),
    KEY added (added),
    KEY updated (updated),
    CONSTRAINT idInvoice_fk FOREIGN KEY (idInvoice) REFERENCES Invoices (idInvoice),
    CONSTRAINT idCreditNote_fk FOREIGN KEY (idCreditNote) REFERENCES CreditNotes (idCreditNote)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

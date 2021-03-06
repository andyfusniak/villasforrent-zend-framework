CREATE TABLE IF NOT EXISTS Invoices (
    idInvoice int(8) unsigned zerofill NOT NULL AUTO_INCREMENT,
    invoiceDate date NOT NULL,
    total decimal(8,2) unsigned NOT NULL,
    currency char(3) CHARACTER SET ascii NOT NULL DEFAULT 'GBP',
    idFromAddress int(10) unsigned NOT NULL DEFAULT '1',
    idBillingAddress int(10) unsigned NOT NULL,
    itemLastAdded datetime DEFAULT NULL,
    `status` enum('OPEN','CLOSED') CHARACTER SET ascii NOT NULL DEFAULT 'OPEN',
    void int(1) unsigned NOT NULL DEFAULT '0',
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idInvoice),
    KEY invoiceDate (invoiceDate),
    KEY total (total),
    KEY currency (currency),
    KEY billingAddress (idBillingAddress),
    KEY itemLastAdded (itemLastAdded),
    KEY paid (`status`),
    KEY added (added),
    KEY updated (updated),
    KEY idOurAddress (idFromAddress),
    KEY void (void),
    CONSTRAINT invoices_ibfk_6 FOREIGN KEY (currency) REFERENCES Currencies (iso3char),
    CONSTRAINT invoices_ibfk_7 FOREIGN KEY (idFromAddress) REFERENCES Addresses (idAddress) ON UPDATE CASCADE,
    CONSTRAINT invoices_ibfk_8 FOREIGN KEY (idBillingAddress) REFERENCES Addresses (idAddress) ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

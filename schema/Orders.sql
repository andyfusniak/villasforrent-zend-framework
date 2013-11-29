CREATE TABLE IF NOT EXISTS Orders (
    idOrder int(10) unsigned NOT NULL AUTO_INCREMENT,
    idAdvertiser int(10) unsigned NOT NULL,
    taxCode char(3) CHARACTER SET ascii NOT NULL,
    net decimal(8,2) NOT NULL DEFAULT '0.00',
    vat decimal(8,2) NOT NULL DEFAULT '0.00',
    total decimal(8,2) NOT NULL DEFAULT '0.00',
    placed datetime NOT NULL,
    PRIMARY KEY (idOrder),
    KEY net (net),
    KEY vat (vat),
    KEY total (total),
    KEY placed (placed),
    KEY taxCode (taxCode),
    KEY idAdvertiser (idAdvertiser),
    CONSTRAINT orders_ibfk_1 FOREIGN KEY (idAdvertiser) REFERENCES Advertisers (idAdvertiser) ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT orders_ibfk_2 FOREIGN KEY (taxCode) REFERENCES TaxCodes (taxCode) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

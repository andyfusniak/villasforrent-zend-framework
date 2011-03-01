CREATE TABLE IF NOT EXISTS Payments (
    idPayment int(10) unsigned NOT NULL AUTO_INCREMENT,
    idAdvertiser int(10) unsigned NOT NULL,
    paymentType char(2) CHARACTER SET ascii NOT NULL DEFAULT 'CC',
    paymentDate datetime NOT NULL,
    total decimal(8,2) NOT NULL DEFAULT '0.00',
    PRIMARY KEY (idPayment),
    KEY paymentType (paymentType),
    KEY idAdvertiser (idAdvertiser),
    CONSTRAINT payments_ibfk_1 FOREIGN KEY (idAdvertiser) REFERENCES Advertisers (idAdvertiser) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
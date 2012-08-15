CREATE TABLE IF NOT EXISTS Payments (
    idPayment int(8) unsigned zerofill NOT NULL AUTO_INCREMENT,
    dateReceived date NOT NULL,
    amount decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
    currency char(3) CHARACTER SET ascii NOT NULL,
    method enum('SECPAY','PAYPAL','BACS','CASH','WIRE') CHARACTER SET ascii NOT NULL,
    notes text,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idPayment),
    KEY dateReceived (dateReceived),
    KEY amount (amount),
    KEY currency (currency),
    KEY method (method),
    KEY added (added),
    KEY updated (updated),
    CONSTRAINT current_ibfk FOREIGN KEY (currency) REFERENCES Currencies (iso3char)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

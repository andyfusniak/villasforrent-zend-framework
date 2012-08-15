CREATE TABLE IF NOT EXISTS Refunds (
    idRefund int(8) unsigned zerofill NOT NULL AUTO_INCREMENT,
    dateSent date NOT NULL,
    amount decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
    currency char(3) CHARACTER SET ascii NOT NULL,
    method varchar(32) CHARACTER SET ascii NOT NULL,
    notes text,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idRefund),
    KEY dateSent (dateSent),
    KEY amount (amount),
    KEY currency (currency),
    KEY method (method),
    KEY added (added),
    KEY updated (updated),
    CONSTRAINT currency_fk FOREIGN KEY (currency) REFERENCES Currencies (iso3char)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

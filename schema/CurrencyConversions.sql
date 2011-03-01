CREATE TABLE IF NOT EXISTS CurrencyConversions (
    dest char(2) CHARACTER SET ascii NOT NULL,
    source char(2) CHARACTER SET ascii NOT NULL,
    rates float(4,4) NOT NULL DEFAULT '1.0000',
    added datetime NOT NULL,
    updated datetime NOT NULL,
    lastModifiedBy varchar(32) DEFAULT NULL,
    PRIMARY KEY (dest, source),
    KEY added (added),
    KEY updated (updated),
    KEY source (source),
    KEY lastModifiedBy (lastModifiedBy),
    CONSTRAINT currency_conversions_ibfk_1 FOREIGN KEY (dest) REFERENCES CountryList (iso2char) ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT currency_conversions_ibfk_2 FOREIGN KEY (source) REFERENCES CountryList (iso2char) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
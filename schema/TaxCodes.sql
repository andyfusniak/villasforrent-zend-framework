CREATE TABLE IF NOT EXISTS TaxCodes (
    taxCode char(3) CHARACTER SET ascii NOT NULL,
    name varchar(255) DEFAULT NULL,
    added datetime DEFAULT NULL,
    updated datetime DEFAULT NULL,
    PRIMARY KEY (taxCode),
    KEY name (name),
    KEY added (added),
    KEY updated (updated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

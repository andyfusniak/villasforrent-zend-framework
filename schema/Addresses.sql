CREATE TABLE IF NOT EXISTS Addresses (
    idAddress int(10) unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) DEFAULT NULL,
    line1 varchar(255) DEFAULT NULL,
    line2 varchar(255) DEFAULT NULL,
    line3 varchar(255) DEFAULT NULL,
    townCity varchar(64) DEFAULT NULL,
    county varchar(255) DEFAULT NULL,
    postcode varchar(32) DEFAULT NULL,
    country varchar(255) DEFAULT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idAddress),
    KEY postcode (postcode),
    KEY added (added, updated)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS CountryList (
    iso2char char(2) CHARACTER SET ascii NOT NULL,
    name varchar(255) NOT NULL,
    displayName varchar(255) NOT NULL,
    iso3char char(3) CHARACTER SET ascii NOT NULL,
    displayPriority int(10) unsigned NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    lastModifiedBy varchar(32) NOT NULL,
    visible int(1) NOT NULL,
    PRIMARY KEY (iso2char),
    UNIQUE KEY iso3char (iso3char),
    KEY visible (visible),
    KEY displayPriority (displayPriority)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

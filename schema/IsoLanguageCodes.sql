CREATE TABLE IF NOT EXISTS IsoLanguageCodes (
    iso2char char(2) CHARACTER SET ascii NOT NULL,
    name varchar(128) NOT NULL,
    PRIMARY KEY (iso2char),
    KEY name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

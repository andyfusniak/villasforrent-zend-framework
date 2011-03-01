CREATE TABLE IF NOT EXISTS CountriesQuickCount (
    idCountry int(10) unsigned NOT NULL,
    totalVisible int(10) unsigned NOT NULL DEFAULT '0',
    total int(10) unsigned NOT NULL DEFAULT '0',
    updated datetime NOT NULL,
    PRIMARY KEY (idCountry),
    KEY updated (updated),
    CONSTRAINT countries_quick_count_ibfk_1 FOREIGN KEY (idCountry) REFERENCES Countries (idCountry) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
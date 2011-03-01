CREATE TABLE IF NOT EXISTS RegionsQuickCount (
    idRegion int(10) unsigned NOT NULL,
    totalVisible int(10) unsigned NOT NULL DEFAULT '0',
    total int(10) unsigned NOT NULL DEFAULT '0',
    updated datetime NOT NULL,
    PRIMARY KEY (idRegion),
    KEY updated (updated),
    CONSTRAINT regions_quick_count_ibfk_1 FOREIGN KEY (idRegion) REFERENCES Regions (idRegion) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
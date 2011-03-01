CREATE TABLE IF NOT EXISTS DestinationsQuickCount (
    idDestination int(10) unsigned NOT NULL,
    totalVisible int(10) unsigned NOT NULL DEFAULT '0',
    total int(10) unsigned NOT NULL DEFAULT '0',
    updated datetime NOT NULL,
    PRIMARY KEY (idDestination),
    KEY updated (updated),
    CONSTRAINT destinations_quick_count_ibfk_1 FOREIGN KEY (idDestination) REFERENCES Destinations (idDestination) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS PropertiesStatistics (
    idProperty int(10) unsigned NOT NULL,
    statsDate date NOT NULL,
    hits int(10) unsigned NOT NULL DEFAULT '0',
    visits int(10) unsigned NOT NULL DEFAULT '0',
    webenquiries int(10) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (idProperty, statsDate),
    KEY hits (hits),
    KEY visits (visits),
    KEY webenquiries (webenquiries)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

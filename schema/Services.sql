CREATE TABLE IF NOT EXISTS Services (
    idService int(10) unsigned NOT NULL AUTO_INCREMENT,
    description varchar(255) NOT NULL,
    net decimal(8,2) NOT NULL DEFAULT '0.00',
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idService),
    KEY description (description),
    KEY net (net),
    KEY added (added),
    KEY updated (updated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

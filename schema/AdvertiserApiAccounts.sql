CREATE TABLE IF NOT EXISTS AdvertiserApiAccounts (
    idAdvertiserApiAccount int(10) unsigned NOT NULL AUTO_INCREMENT,
    idAdvertiser int(10) unsigned NOT NULL,
    idApiAccount int(10) unsigned NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idAdvertiserApiAccount),
    UNIQUE KEY idAdvertiser_2 (idAdvertiser,idApiAccount),
    KEY idApiAccount (idApiAccount),
    KEY idAdvertiser (idAdvertiser),
    CONSTRAINT AdvertiserApiAccounts_ibfk_1 FOREIGN KEY (idApiAccount) REFERENCES ApiAccounts (idApiAccount) ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT AdvertiserApiAccounts_ibfk_2 FOREIGN KEY (idAdvertiser) REFERENCES Advertisers (idAdvertiser) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
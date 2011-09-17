CREATE TABLE IF NOT EXISTS AdvertisersReset (
  idAdvertiserReset int(10) unsigned NOT NULL AUTO_INCREMENT,
  idAdvertiser int(10) unsigned DEFAULT NULL,
  token varchar(60) CHARACTER SET ascii DEFAULT NULL,
  expires datetime DEFAULT NULL,
  added datetime DEFAULT NULL,
  PRIMARY KEY (idAdvertiserReset),
  UNIQUE KEY token (token),
  KEY idAdvertiser (idAdvertiser),
  KEY expires (expires),
  KEY added (added),
  CONSTRAINT AdvertisersReset_ibfk_1 FOREIGN KEY (idAdvertiser) REFERENCES Advertisers (idAdvertiser) ON DELETE CASCADE ON UPDATE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

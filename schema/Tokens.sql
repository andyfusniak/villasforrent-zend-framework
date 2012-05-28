CREATE TABLE IF NOT EXISTS Tokens (
    idToken int(10) unsigned NOT NULL AUTO_INCREMENT,
    idAdvertiser int(10) unsigned DEFAULT NULL,
    type varchar(12) CHARACTER SET ascii NOT NULL,
    token varchar(60) CHARACTER SET ascii DEFAULT NULL,
    expires datetime DEFAULT NULL,
    added datetime DEFAULT NULL,
    PRIMARY KEY (idToken),
    UNIQUE KEY token (token),
    KEY idAdvertiser (idAdvertiser),
    KEY type (type),
    KEY expires (expires),
    KEY added (added),
    CONSTRAINT Tokens_ibfk_1 FOREIGN KEY (idAdvertiser) REFERENCES Advertisers (idAdvertiser) ON DELETE CASCADE ON UPDATE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

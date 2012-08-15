CREATE TABLE IF NOT EXISTS Tokens (
    idToken int(10) unsigned NOT NULL AUTO_INCREMENT,
    idAdvertiser int(10) unsigned DEFAULT NULL,
    idMember int(10) unsigned DEFAULT NULL,
    type varchar(12) CHARACTER SET ascii NOT NULL,
    token varchar(60) CHARACTER SET ascii DEFAULT NULL,
    expires datetime DEFAULT NULL,
    added datetime DEFAULT NULL,
    PRIMARY KEY (idToken),
    UNIQUE KEY token (token),
    KEY idAdvertiser (idAdvertiser),
    KEY idMember (idMember),
    KEY type (type),
    KEY expires (expires),
    KEY added (added),
    CONSTRAINT idMember_ibfk FOREIGN KEY (idMember) REFERENCES Members (idMember) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT idAdvertiser_ibfk FOREIGN KEY (idAdvertiser) REFERENCES Advertisers (idAdvertiser) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

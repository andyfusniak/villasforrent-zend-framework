CREATE TABLE IF NOT EXISTS OAuthGrants (
    idOAuthGrant int(10) unsigned not null AUTO_INCREMENT,
    clientId char(15) CHARACTER SET ascii not null,
    code char(64) CHARACTER SET ascii not null,
    redirectUri varchar(512) CHARACTER SET ascii not null,
    expiry datetime not null,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idOAuthGrant),
    UNIQUE KEY clientId (clientId),
    UNIQUE KEY code (code),
    KEY redirectUri (redirectUri),
    KEY expiry (expiry),
    KEY added (added),
    KEY updated (updated),
    CONSTRAINT clientId_fk FOREIGN KEY (clientId) REFERENCES OAuthApps (clientId) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

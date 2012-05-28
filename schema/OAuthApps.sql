CREATE TABLE IF NOT EXISTS OAuthApps (
    idOAuthApps int(10) unsigned not null AUTO_INCREMENT,
    clientId char(15) CHARACTER SET ascii not null,
    clientSecret char(64) CHARACTER SET ascii not null,
    redirectUri varchar(512) CHARACTER SET ascii not null,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idOAuthApps),
    UNIQUE KEY clientId (clientId),
    UNIQUE KEY clientSecret (clientSecret),
    KEY redirectUri (redirectUri),
    KEY added (added),
    KEY updated (updated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
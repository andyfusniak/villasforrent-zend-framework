CREATE TABLE IF NOT EXISTS OAuthAccessTokens (
    idOAuthAccessToken int(10) unsigned not null AUTO_INCREMENT,
    clientId char(15) CHARACTER SET ascii not null,
    oAuthAccessToken char(64) CHARACTER SET ascii not null,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idOAuthAccessToken),
    UNIQUE KEY clientId (clientId),
    UNIQUE KEY oAuthAccessToken (oAuthAccessToken),
    KEY added (added),
    KEY updated (updated),
    CONSTRAINT clientId_fk FOREIGN KEY (clientId) REFERENCES OAuthApps (clientId) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

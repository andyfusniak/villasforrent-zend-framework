CREATE TABLE IF NOT EXISTS UrlRedirects (
    idUrlRedirect int(10) unsigned NOT NULL AUTO_INCREMENT,
    incomingUrl varchar(512) CHARACTER SET ascii NOT NULL,
    redirectUrl varchar(512) CHARACTER SET ascii NOT NULL,
    responseCode int(10) unsigned NOT NULL,
    groupName varchar(32) CHARACTER SET ascii NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idUrlRedirect),
    UNIQUE KEY incomingUrl (incomingUrl),
    KEY redirectUrl (redirectUrl),
    KEY responseCode (responseCode),
    KEY groupName (groupName),
    KEY added (added),
    KEY updated (updated)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

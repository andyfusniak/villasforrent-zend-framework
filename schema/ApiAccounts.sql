CREATE TABLE IF NOT EXISTS ApiAccounts (
    idApiAccount int(10) unsigned NOT NULL AUTO_INCREMENT,
    apikey char(40) CHARACTER SET ascii NOT NULL,
    ip text CHARACTER SET ascii,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idApiAccount),
    UNIQUE KEY apikey (apikey),
    KEY added (added),
    KEY updated (updated)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

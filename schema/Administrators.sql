CREATE TABLE IF NOT EXISTS Administrators (
    idAdministrator int(10) unsigned NOT NULL AUTO_INCREMENT,
    username varchar(16) NOT NULL,
    passwd varchar(32) NOT NULL,
    emailAddress varchar(255) NOT NULL,
    firstname varchar(255) DEFAULT NULL,
    lastname varchar(255) DEFAULT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idAdministrator),
    UNIQUE KEY username (username),
    KEY added (added),
    KEY updated (updated),
    KEY passwd (passwd)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

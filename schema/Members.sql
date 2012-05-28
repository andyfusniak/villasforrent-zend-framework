CREATE TABLE IF NOT EXISTS Members (
    idMember int(10) unsigned NOT NULL AUTO_INCREMENT,
    username varchar(16) NOT NULL,
    passwd varchar(32) NOT NULL,
    emailAddress varchar(255) CHARACTER SET ascii NOT NULL,
    firstname varchar(255) NOT NULL,
    lastname varchar(255) NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    lastModifiedBy varchar(32) CHARACTER SET ascii NOT NULL DEFAULT 'system',
    PRIMARY KEY (idMember),
    UNIQUE KEY username (username),
    KEY passwd (passwd),
    KEY added (added),
    KEY updated (updated),
    KEY lastname (lastname),
    KEY emailAddress (emailAddress)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

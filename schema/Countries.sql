CREATE TABLE IF NOT EXISTS Countries (
    idCountry int(10) unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    displayPriority int(10) unsigned NOT NULL,
    prefix varchar(255) DEFAULT NULL,
    postfix varchar(255) DEFAULT NULL,
    visible tinyint(1) DEFAULT '1',
    added datetime NOT NULL,
    updated datetime NOT NULL,
    lastModifiedBy varchar(32) CHARACTER SET ascii NOT NULL DEFAULT 'system',
    PRIMARY KEY (idCountry),
    UNIQUE KEY name (name),
    KEY added (added),
    KEY updated (updated),
    KEY visible (visible),
    KEY displayPriority (displayPriority),
    KEY lastModifiedBy (lastModifiedBy)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS PropertyTypes (
    idPropertyType int(10) unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    displayPriority int(10) unsigned NOT NULL,
    inUse int(1) NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    lastModifiedBy varchar(32) CHARACTER SET ascii NOT NULL DEFAULT 'system',
    PRIMARY KEY (idPropertyType),
    KEY name (name),
    KEY added (added),
    KEY updated (updated),
    KEY displayPriority (displayPriority),
    KEY lastModifiedBy (lastModifiedBy),
    KEY inUse (inUse)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS Currencies (
    iso3char char(3) CHARACTER SET ascii NOT NULL,
    name varchar(255) NOT NULL,
    visible int(1) NOT NULL DEFAULT '1',
    inUse int(1) unsigned NOT NULL,
    displayPriority int(10) unsigned NOT NULL DEFAULT '1',
    lastModifiedBy varchar(32) NOT NULL DEFAULT 'system',
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (iso3char),
    KEY name (name),
    KEY visible (visible),
    KEY inUse (inUse),
    KEY added (added),
    KEY updated (updated),
    KEY lastModifiedBy (lastModifiedBy),
    KEY displayPriority (displayPriority)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
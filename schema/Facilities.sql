CREATE TABLE IF NOT EXISTS Facilities (
    facilityCode char(3) CHARACTER SET ascii NOT NULL,
    name varchar(255) NOT NULL,
    displayPriority int(10) unsigned NOT NULL,
    inUse int(1) NOT NULL DEFAULT '1',
    added datetime NOT NULL,
    updated datetime NOT NULL,
    lastModifiedBy varchar(32) CHARACTER SET ascii NOT NULL DEFAULT 'system',
    PRIMARY KEY (facilityCode),
    KEY added (added),
    KEY updated (updated),
    KEY name (name),
    KEY displayPriority (displayPriority),
    KEY inUse (inUse),
    KEY lastModifiedBy (lastModifiedBy)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

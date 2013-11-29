CREATE TABLE IF NOT EXISTS PhotoApprovals (
    idPhotoApproval int(10) unsigned NOT NULL,
    displayPriority int(10) unsigned NOT NULL DEFAULT '1',
    inUse int(1) NOT NULL DEFAULT '1',
    name varchar(255) NOT NULL,
    description text NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    lastModifiedBy varchar(32) CHARACTER SET ascii NOT NULL DEFAULT 'system',
    PRIMARY KEY (idPhotoApproval),
    KEY displayPriority (displayPriority),
    KEY inUse (inUse)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

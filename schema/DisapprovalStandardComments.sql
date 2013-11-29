CREATE TABLE IF NOT EXISTS DisapprovalStandardComments (
    idDisapprovalStandardComments int(10) unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    displayPriority int(10) unsigned NOT NULL DEFAULT '1',
    inUse int(1) unsigned NOT NULL DEFAULT '1',
    description text NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idDisapprovalStandardComments),
    KEY added (added, updated),
    KEY name (name),
    KEY displayPriority (displayPriority),
    KEY inUse (inUse)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

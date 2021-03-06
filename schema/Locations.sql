CREATE TABLE IF NOT EXISTS Locations (
    idLocation int(10) unsigned NOT NULL AUTO_INCREMENT,
    idParent int(10) unsigned DEFAULT NULL,
    url varchar(255) CHARACTER SET ascii NOT NULL,
    lt int(4) unsigned DEFAULT NULL,
    rt int(4) unsigned DEFAULT NULL,
    depth tinyint(3) unsigned NOT NULL DEFAULT '1',
    rowurl varchar(255) CHARACTER SET ascii NOT NULL,
    idProperty int(10) unsigned DEFAULT NULL,
    displayPriority int(10) unsigned NOT NULL DEFAULT '1',
    totalVisible int(10) unsigned DEFAULT NULL,
    total int(10) unsigned DEFAULT NULL,
    name varchar(255) NOT NULL,
    rowname varchar(255) NOT NULL,
    prefix varchar(255) NOT NULL,
    postfix varchar(255) NOT NULL,
    visible int(10) unsigned NOT NULL DEFAULT '1',
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idLocation),
    KEY idParent (idParent),
    UNIQUE KEY url (url),
    KEY lt (lt),
    KEY rt (rt),
    KEY depth (depth),
    KEY rowurl (rowurl),
    KEY idProperty (idProperty),
    KEY displayPriority (displayPriority),
    KEY totalVisible (totalVisible),
    KEY total (total),
    KEY name (name),
    KEY rowname (rowname),
    KEY visible (visible),
    KEY added (added),
    KEY updated (updated)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

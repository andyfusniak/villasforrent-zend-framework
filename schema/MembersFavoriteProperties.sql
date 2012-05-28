CREATE TABLE IF NOT EXISTS MembersFavoriteProperties (
    idMember int(10) unsigned NOT NULL,
    idProperty int(10) unsigned NOT NULL,
    displayPriority int(10) unsigned NOT NULL DEFAULT '1',
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idMember, idProperty),
    KEY added (added),
    KEY updated (updated),
    KEY displayPriority (displayPriority),
    KEY idProperty (idProperty)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

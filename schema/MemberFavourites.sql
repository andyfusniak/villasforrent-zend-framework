CREATE TABLE IF NOT EXISTS MemberFavourites (
    idMemberFavourite int(10) unsigned NOT NULL AUTO_INCREMENT,
    idMember int(10) unsigned NOT NULL,
    idProperty int(10) unsigned DEFAULT NULL,
    rank int(10) unsigned NOT NULL DEFAULT '10',
    priority int(10) unsigned NOT NULL DEFAULT '1',
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idMemberFavourite),
    UNIQUE KEY idMemberPropertyComposite (idMember, idProperty),
    KEY idMember (idMember),
    KEY idProperty (idProperty),
    KEY rank (rank),
    KEY priority (priority),
    KEY added (added),
    KEY updated (updated),
    CONSTRAINT `memberfavourites_ibfk_2` FOREIGN KEY (`idProperty`) REFERENCES `Properties` (`idProperty`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `memberfavourites_ibfk_1` FOREIGN KEY (`idMember`) REFERENCES `Members` (`idMember`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS LocationsContent (
    idLocationsContent int(10) unsigned NOT NULL AUTO_INCREMENT,
    idLocation int(10) unsigned NOT NULL,
    lang char(2) CHARACTER SET ascii NOT NULL DEFAULT 'EN',
    fieldTag varchar(32) CHARACTER SET ascii NOT NULL,
    priority int(10) unsigned NOT NULL DEFAULT 1,
    content mediumtext,
    cs char(40) CHARACTER SET ascii DEFAULT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idLocationsContent),
    UNIQUE KEY composite (idLocation,lang,fieldTag, priority),
    KEY lang (lang),
    KEY added (added),
    KEY updated (updated),
    KEY cs (cs),
    CONSTRAINT locationscontent_ibfk_1 FOREIGN KEY (idLocation) REFERENCES Locations (idLocation) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

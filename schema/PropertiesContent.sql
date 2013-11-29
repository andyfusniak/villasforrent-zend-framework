CREATE TABLE IF NOT EXISTS PropertiesContent (
    idPropertyContent int(10) unsigned NOT NULL AUTO_INCREMENT,
    idProperty int(10) unsigned NOT NULL,
    version int(10) unsigned NOT NULL DEFAULT '1',
    idPropertyContentField int(10) unsigned NOT NULL,
    iso2char char(2) CHARACTER SET ascii NOT NULL,
    content mediumtext,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idPropertyContent),
    UNIQUE KEY idProperty (idProperty, version, idPropertyContentField, iso2char),
    KEY iso2char (iso2char),
    KEY idProperty2 (idProperty),
    KEY idPropertyContentField (idPropertyContentField),
    KEY added (added),
    KEY updated (updated),
    CONSTRAINT properties_content_ibfk_1 FOREIGN KEY (idProperty) REFERENCES Properties (idProperty) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT properties_content_ibfk_2 FOREIGN KEY (iso2char) REFERENCES IsoLanguageCodes (iso2char) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

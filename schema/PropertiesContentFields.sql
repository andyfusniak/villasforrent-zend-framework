CREATE TABLE IF NOT EXISTS PropertiesContentFields (
    idPropertyContentField int(10) unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    description text,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idPropertyContentField),
    KEY name (name, added, updated),
    KEY added (added),
    KEY updated (updated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
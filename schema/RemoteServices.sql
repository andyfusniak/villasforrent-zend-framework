CREATE TABLE IF NOT EXISTS RemoteServices (
    idRemoteService int(4) unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    added datetime NOT NULL,
    PRIMARY KEY (idRemoteService),
    KEY name (name, added)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS WebServices (
    idWebService int(10) unsigned NOT NULL AUTO_INCREMENT,
    description text NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idWebService),
    KEY added (added),
    KEY updated (updated)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

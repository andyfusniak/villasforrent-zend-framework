CREATE TABLE IF NOT EXISTS Messages (
    idMessage int(10) unsigned NOT NULL AUTO_INCREMENT,
    idMessageThread int(10) unsigned NOT NULL,
    message mediumtext NOT NULL,
    direction int(1) unsigned NOT NULL DEFAULT '2' COMMENT '1 user, 2 advertiser',
    rd int(1) unsigned NOT NULL DEFAULT '0',
    replied int(1) unsigned NOT NULL DEFAULT '0',
    sent datetime NOT NULL,
    PRIMARY KEY (idMessage),
    KEY rd (rd),
    KEY direction (direction),
    KEY replied (replied),
    KEY sent (sent),
    KEY idMessageThread (idMessageThread),
    CONSTRAINT messages_ibfk_1 FOREIGN KEY (idMessageThread) REFERENCES MessageThreads (idMessageThread) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

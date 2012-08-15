CREATE TABLE IF NOT EXISTS Messages (
    idMessage int(10) unsigned NOT NULL AUTO_INCREMENT,
    idMessageThread int(10) unsigned NOT NULL,
    sentBy enum('MEMBER','ADVERTISER') CHARACTER SET ascii NOT NULL,
    body text NOT NULL,
    added datetime NOT NULL,
    PRIMARY KEY (idMessage),
    KEY added (added),
    KEY sentBy (sentBy),
    KEY idMessageThread (idMessageThread),
    CONSTRAINT idMessageThread_ibfk FOREIGN KEY (idMessageThread) REFERENCES MessageThreads (idMessageThread) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

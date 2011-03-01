CREATE TABLE IF NOT EXISTS Enquiries (
    idEnquiry int(10) unsigned NOT NULL AUTO_INCREMENT,
    idMember int(10) unsigned NOT NULL,
    idAdvertiser int(10) unsigned NOT NULL,
    emailTo varchar(255) CHARACTER SET ascii NOT NULL,
    emailFrom varchar(255) CHARACTER SET ascii NOT NULL,
    message text NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idEnquiry),
    KEY added (added),
    KEY updated (updated),
    KEY emailTo (emailTo),
    KEY emailFrom (emailFrom),
    KEY idMember (idMember),
    KEY idAdvertiser (idAdvertiser)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

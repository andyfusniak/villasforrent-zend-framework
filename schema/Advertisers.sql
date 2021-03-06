CREATE TABLE IF NOT EXISTS Advertisers (
    idAdvertiser int(10) unsigned NOT NULL AUTO_INCREMENT,
    iso2char char(2) CHARACTER SET ascii NOT NULL,
    idAdministrator int(10) unsigned NOT NULL DEFAULT '100000',
    username varchar(16) NOT NULL,
    hash varchar(60) CHARACTER SET ascii DEFAULT NULL,
    emailAddress varchar(255) CHARACTER SET ascii NOT NULL,
    changeEmailAddress varchar(255) CHARACTER SET ascii DEFAULT NULL,
    firstname varchar(255) NOT NULL,
    lastname varchar(255) NOT NULL,
    address text,
    postcode varchar(16) DEFAULT NULL,
    telephone varchar(255) DEFAULT NULL,
    fax varchar(255) DEFAULT NULL,
    mobile varchar(255) DEFAULT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    lastlogin datetime NULL,
    emailLastConfirmed varchar(255) CHARACTER SET ascii DEFAULT NULL,
    lastModifiedBy varchar(32) NOT NULL DEFAULT 'system',
    PRIMARY KEY (idAdvertiser),
    UNIQUE emailAddress (emailAddress),
    KEY added (added),
    KEY updated (updated),
    KEY lastname (lastname),
    KEY iso2char (iso2char),
    KEY lastModfiedBy (lastModifiedBy),
    KEY idAdministrator (idAdministrator),
    KEY username (username),
    KEY lastlogin (lastlogin),
    KEY hash (hash),
    KEY changeEmailAddress (changeEmailAddress),
    KEY emailLastConfirmed (emailLastConfirmed)
    CONSTRAINT advertisers_ibfk_1 FOREIGN KEY (iso2char) REFERENCES CountryList (iso2char) ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT advertisers_ibfk_2 FOREIGN KEY (idAdministrator) REFERENCES Administrators (idAdministrator) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
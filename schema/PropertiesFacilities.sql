CREATE TABLE IF NOT EXISTS PropertiesFacilities (
    idPropertyFacility int(10) unsigned NOT NULL AUTO_INCREMENT,
    idProperty int(10) unsigned NOT NULL,
    facilityCode char(3) CHARACTER SET ascii NOT NULL,
    isOn int(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (idPropertyFacility),
    KEY (idProperty, facilityCode),
    KEY isOn (isOn),
    KEY facilityCode (facilityCode),
    CONSTRAINT properties_facilities_ibfk_1 FOREIGN KEY (idProperty) REFERENCES Properties (idProperty) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT properties_facilities_ibfk_2 FOREIGN KEY (facilityCode) REFERENCES Facilities (facilityCode) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS Availability (
    idAvailability int(10) unsigned NOT NULL AUTO_INCREMENT,
    idCalendar int(10) unsigned NOT NULL,
    startDate date NOT NULL,
    endDate date NOT NULL,
    st int(1) unsigned NOT NULL,
    PRIMARY KEY (idAvailability),
    UNIQUE KEY cse_tuple (idCalendar, startDate, endDate),
    KEY st (st),
    CONSTRAINT availability_ibfk_1 FOREIGN KEY (idCalendar) REFERENCES Calendars (idCalendar) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

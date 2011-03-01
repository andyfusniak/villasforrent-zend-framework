CREATE TABLE IF NOT EXISTS Rates (
	idRate int(10) unsigned NOT NULL AUTO_INCREMENT,
    idCalendar int(10) unsigned NOT NULL,
    startDate date NOT NULL,
    endDate date NOT NULL,
    name varchar(255) DEFAULT NULL,
    minStayDays int(10) unsigned NOT NULL DEFAULT '7',
    weeklyRate decimal(8,2) DEFAULT '0.00',
    weekendNightlyRate decimal(8,2) DEFAULT '0.00',
    midweekNightlyRate decimal(8,2) DEFAULT '0.00',
    PRIMARY KEY (idRate), 
	UNIQUE KEY (idCalendar, startDate, endDate),
    KEY minStayDays (minStayDays),
    KEY weeklyRate (weeklyRate),
    KEY weekendNightlyRate (weekendNightlyRate),
    KEY midweekNightlyRate (midweekNightlyRate),
    CONSTRAINT rates_ibfk_1 FOREIGN KEY (idCalendar) REFERENCES Calendars (idCalendar) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

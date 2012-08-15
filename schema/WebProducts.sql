CREATE TABLE IF NOT EXISTS WebProducts (
    idWebProduct int(10) unsigned NOT NULL AUTO_INCREMENT,
    productCode varchar(32) CHARACTER SET ascii NOT NULL,
    name varchar(255) NOT NULL,
    unitPrice decimal(8,2) unsigned DEFAULT NULL,
    repeats varchar(32) CHARACTER SET ascii NOT NULL,
    description text,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idWebProduct),
    UNIQUE KEY productCode (productCode),
    KEY name (name),
    KEY repeats (repeats),
    KEY added (added),
    KEY updated (updated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO WebProducts (idWebProduct, productCode, `name`, unitPrice, repeats, description, added, updated) VALUES(1, 'DNRE-DCOM', 'Domain Renewal (.com)', 30.00, 'YEARLY', 'Yearly domain renewal for .com domain', '2012-07-12 00:00:00', '2012-07-12 00:00:00');
INSERT INTO WebProducts (idWebProduct, productCode, `name`, unitPrice, repeats, description, added, updated) VALUES(2, 'HOST-PREM', 'Premium Website Hosting', 15.00, 'MONTHLY', 'Single domain owner website hosting', '2012-07-12 00:00:00', '2012-07-12 00:00:00');
INSERT INTO WebProducts (idWebProduct, productCode, `name`, unitPrice, repeats, description, added, updated) VALUES(3, 'DNRE-DCUK', 'Domain Renewal (.co.uk)', 40.00, '2YEAR', 'Domain renewal for .co.uk domain name', '2012-07-12 00:00:00', '2012-07-12 00:00:00');
INSERT INTO WebProducts (idWebProduct, productCode, `name`, unitPrice, repeats, description, added, updated) VALUES(4, 'DNRG-DCOM', 'Domain Registration (.com)', 30.00, 'YEARLY', 'Initial domain registration', '2012-07-12 00:00:00', '2012-07-12 00:00:00');
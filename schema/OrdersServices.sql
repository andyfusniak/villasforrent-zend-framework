CREATE TABLE IF NOT EXISTS OrdersServices (
    idOrder int(10) unsigned NOT NULL,
    idService int(10) unsigned NOT NULL,
    qty int(10) unsigned NOT NULL DEFAULT '1',
    PRIMARY KEY (idOrder, idService),
    KEY qty (qty),
    KEY idService (idService),
    CONSTRAINT orders_services_ibfk_1 FOREIGN KEY (idOrder) REFERENCES Orders (idOrder) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT orders_services_ibfk_2 FOREIGN KEY (idService) REFERENCES Services (idService) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

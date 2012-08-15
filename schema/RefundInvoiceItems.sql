CREATE TABLE IF NOT EXISTS RefundInvoiceItems (
    idRefundInvoice int(10) unsigned NOT NULL AUTO_INCREMENT,
    idRefund int(8) unsigned zerofill NOT NULL,
    idInvoiceItem int(10) unsigned NOT NULL,
    qty int(10) unsigned NOT NULL DEFAULT '1',
    unitAmount decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idRefundInvoice),
    UNIQUE KEY idRefund_2 (idRefund, idInvoiceItem),
    KEY idRefund (idRefund),
    KEY idInvoiceItem (idInvoiceItem),
    KEY qty (qty),
    KEY unitAmount (unitAmount),
    KEY added (added, updated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

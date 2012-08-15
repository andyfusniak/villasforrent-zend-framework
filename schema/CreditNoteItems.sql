CREATE TABLE IF NOT EXISTS CreditNoteItems (
    idCreditNoteItem int(10) unsigned NOT NULL AUTO_INCREMENT,
    idCreditNote int(8) unsigned zerofill NOT NULL,
    qty int(10) unsigned NOT NULL DEFAULT '1',
    unitAmount decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
    description text,
    lineTotal decimal(8,2) unsigned NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idCreditNoteItem),
    KEY idCreditNote (idCreditNote),
    KEY added (added),
    KEY updated (updated)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

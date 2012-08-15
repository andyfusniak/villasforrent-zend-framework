CREATE TABLE IF NOT EXISTS CreditNoteRefunds (
    idCreditNoteRefund int(10) unsigned NOT NULL AUTO_INCREMENT,
    idCreditNote int(8) unsigned zerofill NOT NULL,
    idRefund int(8) unsigned zerofill NOT NULL,
    added datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (idCreditNoteRefund),
    KEY idCreditNote (idCreditNote,idRefund),
    KEY added (added),
    KEY updated (updated)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

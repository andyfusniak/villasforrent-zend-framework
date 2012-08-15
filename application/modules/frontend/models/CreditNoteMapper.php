<?php
class Frontend_Model_CreditNoteMapper extends Frontend_Model_FinanceMapperAbstract
{
    /**
     * @param int $idCreditNote the credit-note id (primary key)
     * @return Common_Model_CrediteNote the credit-note object
     */
    public function getCreditNote($idCreditNote)
    {
        // load the necessary resource to build the full credit note object
        $addressResource = $this->getResource('Address');
        $creditNoteResource = $this->getResource('CreditNote');

        // create the container credit-note object
        $creditNoteRow = $creditNoteResource->getCreditNoteByCreditNoteId(
            $idCreditNote
        );

        if ($creditNoteRow->idCreditAddress) {
            $creditAddressRow = $addressResource->getAddressByAddressId(
                $creditNoteRow->idCreditAddress
            );

            $creditAddressObj = $this->_createAddressObjFromAddressRow($creditAddressRow);
        } else {
            $creditAddressObj = null;
        }

        $creditNoteObj = $this->_createCreditNoteObjFromCreditNoteRow(
            $creditNoteRow,
            $creditAddressObj
        );
        $creditNoteObj->setCreditAddress($creditAddressObj);

        return $creditNoteObj;
    }

    /**
     * Creates a credit-note object from a credit-note DB row
     *
     * @param Common_Resource_CreditNote_Row $creditNoteRow the credit note db row
     * @param Frontend_Model_Address $creditAddressObj the address object of the creditor
     * @return Frontend_Model_CreditNote the credit-note model object
     */
    private function _createCreditNoteObjFromCreditNoteRow(Common_Resource_CreditNote_Row $creditNoteRow, Frontend_Model_Address $creditAddressObj)
    {
        $creditNoteObj = new Frontend_Model_CreditNote();
        $creditNoteObj->setCreditNoteId($creditNoteRow->idCreditNote)
                      ->setCreditNoteDate($creditNoteRow->creditNoteDate)
                      ->setTotal($creditNoteRow->total)
                      ->setCurrency($creditNoteRow->currency)
                      ->setCreditAddress($creditAddressObj)
                      ->setItemLastAdded($creditNoteRow->itemLastAdded)
                      ->setRefunded($creditNoteRow->refunded)
                      ->setAdded($creditNoteRow->added)
                      ->setUpdated($creditNoteRow->updated);

        return $creditNoteObj;
    }
}

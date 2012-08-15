<?php
class Frontend_Model_InvoiceMapper extends Frontend_Model_FinanceMapperAbstract
{

    /**
     * @param int $idInvoice the invoice id
     * @return bool true or false
     */
    public function invoiceExists($idInvoice)
    {
        $invoiceResource = $this->getResource('Invoice');
        return $invoiceResource->invoiceExists($idInvoice);
    }

    /**
     * @param int $idInvoice the invoice id (primary key)
     * @return Common_Model_Invoice the invoice object
     */
    public function getInvoice($idInvoice)
    {
        // load the necessary resource to build the full invoice object
        $invoiceResource = $this->getResource('Invoice');
        $invoicePaymentViewResource = $this->getResource('InvoicePaymentView');
        $invoiceCreditNoteViewResource = $this->getResource('InvoiceCreditNoteView');
        $addressResource = $this->getResource('Address');
        $invoiceItemResource = $this->getResource('InvoiceItem');
        $creditNoteItemResource = $this->getResource('CreditNoteItem');
        $webProductService  = $this->getService('WebProductsMap');
        $webServiceResource = $this->getResource('WebService');

        // create the container invoice object
        $invoiceRow = $invoiceResource->getInvoiceByInvoiceId($idInvoice);
        $invoiceObj = new Frontend_Model_Invoice();
        $invoiceObj->setInvoiceId($invoiceRow->idInvoice)
                   ->setInvoiceDate($invoiceRow->invoiceDate)
                   ->setTotal($invoiceRow->total)
                   ->setCurrency($invoiceRow->currency)
                   ->setItemLastAdded($invoiceRow->itemLastAdded)
                   ->setStatus($invoiceRow->status)
                   ->setAdded($invoiceRow->added)
                   ->setUpdated($invoiceRow->updated);

        // address object
        if ($invoiceRow->idFromAddress) {
            $addressRow = $addressResource->getAddressByAddressId(
                $invoiceRow->idFromAddress
            );

            $addressObj = $this->_createAddressObjFromAddressRow($addressRow);
            $invoiceObj->setFromAddress($addressObj);
        }

        // billing object
        if ($invoiceRow->idBillingAddress) {
            $billingAddressRow = $addressResource->getAddressByAddressId(
                $invoiceRow->idBillingAddress
            );

            $billingObj = $this->_createAddressObjFromAddressRow($billingAddressRow);
            $invoiceObj->setBillingAddress($billingObj);
        }

        // create the invoice items sub structure
        $invoiceItemRowset = $invoiceItemResource->getInvoiceItemsByInvoiceId(
            $idInvoice
        );
        $webProductsHash = $webProductService->getAllWebProductsMap();
        foreach ($invoiceItemRowset as $invoiceItemRow) {
            $invoiceItemObj = new Frontend_Model_InvoiceItem();
            $invoiceItemObj->setInvoiceItemId($invoiceItemRow->idInvoiceItem)
                           ->setInvoiceId($invoiceItemRow->idInvoice)
                           ->setQty($invoiceItemRow->qty)
                           ->setUnitAmount($invoiceItemRow->unitAmount)
                           ->setDescription($invoiceItemRow->description)
                           ->setStartDate($invoiceItemRow->startDate)
                           ->setExpiryDate($invoiceItemRow->expiryDate)
                           ->setRepeats($invoiceItemRow->repeats)
                           ->setLineTotal($invoiceItemRow->lineTotal)
                           ->setAdded($invoiceItemRow->added)
                           ->setUpdated($invoiceItemRow->updated);

            // load the web-product and web-service for each of these invoice-items
            // and cache them to prevent reloading the same one over and over
            $invoiceItemObj->setWebProduct(
                $webProductsHash[$invoiceItemRow->idWebProduct]
            );

            $invoiceObj->addInvoiceItem($invoiceItemObj);
        }

        // payments applied against this invoice
        $invoicePaymentViewRowset = $invoicePaymentViewResource->getAppliedPayments(
            $idInvoice
        );
        foreach ($invoicePaymentViewRowset as $invoicePaymentViewRow) {
            $paymentObj = new Frontend_Model_Payment();
            $paymentObj->setPaymentId($invoicePaymentViewRow->idPayment)
                       ->setDateReceived($invoicePaymentViewRow->dateReceived)
                       ->setAmount($invoicePaymentViewRow->amount)
                       ->setCurrency($invoicePaymentViewRow->currency)
                       ->setMethod($invoicePaymentViewRow->method)
                       ->setNotes($invoicePaymentViewRow->notes)
                       ->setApplied($invoicePaymentViewRow->appliedDate)
                       ->setAdded($invoicePaymentViewRow->paymentAdded)
                       ->setUpdated($invoicePaymentViewRow->paymentUpdated);
            $invoiceObj->addAppliedPayment($paymentObj);
        }

        // credit notes applied against this invoice
        $invoiceCreditNoteViewRowset = $invoiceCreditNoteViewResource->getAppliedCreditNotesByInvoiceId(
            $idInvoice
        );
        foreach ($invoiceCreditNoteViewRowset as $invoiceCreditNoteViewRow) {
            $creditAddressRow = $addressResource->getAddressByAddressId(
                $invoiceCreditNoteViewRow->idCreditAddress
            );

            $creditNoteAddressObj = $this->_createAddressObjFromAddressRow(
                $creditAddressRow
            );

            // create the credit-note object
            $creditNoteObj = $this->_createCreditNoteObjFromInvoiceCreditNoteViewRow(
                $invoiceCreditNoteViewRow,
                $creditNoteAddressObj
            );

            // load and attach the credit-note-items
            $creditNoteItemRowset = $creditNoteItemResource->getCreditNoteItemsByCreditNoteId(
                $invoiceCreditNoteViewRow->idCreditNote
            );
            foreach ($creditNoteItemRowset as $creditNoteItemRow) {
                $creditNoteItemObj = $this->_createCreditNoteItemObjFromCreditNoteItemRow(
                    $creditNoteItemRow
                );

                $creditNoteObj->addCreditNoteItem($creditNoteItemObj);
            }

            $invoiceObj->addAppliedCreditNote($creditNoteObj);
        }

        //var_dump($invoiceRow);

        return $invoiceObj;
    }

    /**
     * Create a credit-note-item object from a credit note item db row
     *
     * @param Common_Resource_CreditNoteItem_Row $creditNoteItemRow the credit note item db row
     * @return Frontend_Model_CreditNoteItem
     */
    private function _createCreditNoteItemObjFromCreditNoteItemRow(Common_Resource_CreditNoteItem_Row $creditNoteItemRow)
    {
        $creditNoteItemObj = new Frontend_Model_CreditNoteItem();
        $creditNoteItemObj->setCreditNoteItemId($creditNoteItemRow->idCreditNoteItem)
                          ->setQty($creditNoteItemRow->qty)
                          ->setUnitAmount($creditNoteItemRow->unitAmount)
                          ->setDescription($creditNoteItemRow->description)
                          ->setLineTotal($creditNoteItemRow->lineTotal)
                          ->setAdded($creditNoteItemRow->added)
                          ->setUpdated($creditNoteItemRow->updated);

        return $creditNoteItemObj;
    }

    private function _createCreditNoteObjFromInvoiceCreditNoteViewRow(Common_Resource_InvoiceCreditNoteView_Row $invoiceCreditNoteViewRow, Frontend_Model_Address $creditNoteAddressObj) {
        $creditNoteObj = new Frontend_Model_CreditNote();
        $creditNoteObj->setCreditNoteId($invoiceCreditNoteViewRow->idCreditNote)
                      ->setCreditNoteDate($invoiceCreditNoteViewRow->creditAdded)
                      ->setTotal($invoiceCreditNoteViewRow->total)
                      ->setCurrency($invoiceCreditNoteViewRow->currency)
                      ->setCreditAddress($creditNoteAddressObj)
                      ->setItemLastAdded($invoiceCreditNoteViewRow->itemLastAdded)
                      ->setRefunded($invoiceCreditNoteViewRow->refunded)
                      ->setApplied($invoiceCreditNoteViewRow->appliedDate)
                      ->setAdded($invoiceCreditNoteViewRow->creditAdded)
                      ->setUpdated($invoiceCreditNoteViewRow->creditUpdated);

        return $creditNoteObj;
    }
}

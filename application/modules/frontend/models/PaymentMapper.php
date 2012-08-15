<?php
class Frontend_Model_PaymentMapper extends Frontend_Model_FinanceMapperAbstract
{
    /**
     * Adds a new payment object to the database
     *
     * @param Frontend_Model_Payment $paymentObj the payment object
     * @param int $idInvoice optional invoice to attach the payment to
     * @return int the last insert id of the DB row
     */
    public function addPayment(Frontend_Model_Payment $paymentObj, $idInvoice = null)
    {
        $paymentResource = $this->getResource('Payment');

        $idPayment = $paymentResource->addPayment(
            $paymentObj->getDateReceived(),
            $paymentObj->getAmount(),
            $paymentObj->getCurrency(),
            $paymentObj->getMethod(),
            $paymentObj->getNotes()
        );

        if ($idInvoice) {
            $invoicePaymentResource = $this->getResource('InvoicePayment');
            $invoicePaymentResource->addInvoicePayment($idInvoice, $idPayment);
        }

        return $idPayment;
    }


    /**
     * Get a payment object by payment id
     *
     * @param int $idPayment the payment id
     * @return Frontend_Model_Payment
     */
    public function getPaymentByPaymentId($idPayment)
    {
        $paymentsInvoicePaymentRowViewResource = $this->getResource('PaymentInvoicePaymentView');

        $row = $paymentsInvoicePaymentRowViewResource->getPaymentInvoicePaymentByPaymentId(
            $idPayment
        );

        $paymentObj = new Frontend_Model_Payment();
        $paymentObj->setPaymentId($row->idPayment)
                   ->setInvoiceId($row->idInvoice)
                   ->setDateReceived($row->dateReceived)
                   ->setAmount($row->amount)
                   ->setCurrency($row->currency)
                   ->setMethod($row->method)
                   ->setNotes($row->notes)
                   //->setApplied($row->appliedDate)
                   ->setAdded($row->added);

         return $paymentObj;
    }

    /**
     * Save a payment object
     *
     * @param Frontend_Model_Payment $paymentObj the payment to be updated
     * @return Frontend_Model_PaymentMapper
     */
    public function savePayment(Frontend_Model_Payment $paymentObj)
    {
        $paymentResource = $this->getResource('Payment');

        $dateReceivedUnixTime = strtotime(str_replace("/", "-", $paymentObj->getDateReceived()));
        $paymentResource->updateFullPayment(
            $paymentObj->getPaymentId(),
            strftime("%Y-%m-%d", $dateReceivedUnixTime),
            $paymentObj->getAmount(),
            $paymentObj->getCurrency(),
            $paymentObj->getMethod(),
            $paymentObj->getNotes()
        );

        return $this;
    }
}

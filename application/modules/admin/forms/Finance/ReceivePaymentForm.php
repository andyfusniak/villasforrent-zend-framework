<?php
class Admin_Form_Finance_ReceivePaymentForm extends Zend_Form
{
    private $methodOptions = array(
        '' => '--select--',
        'SECPAY' => 'SecPay.com / PayPoint.NET',
        'PAYPAL' => 'PayPal',
        'BACS'   => 'BACS Transfer',
        'CASH'   => 'Cash Payment',
        'WIRE'   => 'Wire Money Transfer'
    );

    protected $mode;
    protected $idPayment = null;
    protected $dateReceived;
    protected $idInvoice;
    protected $amount;
    protected $currency;
    protected $paymentMethod;
    protected $notes;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setIdPayment($idPayment)
    {
        $this->idPayment = $idPayment;
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function setDateReceived($dateReceived)
    {
        $this->dateReceived = $dateReceived;
    }

    public function setIdInvoice($idInvoice)
    {
        $this->idInvoice = $idInvoice;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/finance-payment/receive');

        $this->addElementPrefixPath('Vfr_Validate', 'Vfr/Validate', 'validate');

        $this->addElement('hidden', 'idPayment',
            array(
                'value' => $this->idPayment
            )
        );

        $this->addElement('hidden', 'mode',
            array(
                'value' => $this->mode
            )
        );

        $this->addElement('text', 'idInvoice', array(
            'required'   => false,
            'value'      => $this->idInvoice,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('InvoicePayment', true)
            )
        ));

        $this->addElement('text', 'dateReceived',
            array(
                'required'   => true,
                'value'      => $this->dateReceived,
                'validators' => array(
                    array('NotEmpty', true, array('messages' => array('isEmpty' => 'Select a received date'))),
                    array('DateInput', true)
                )
            )
        );

        $this->addElement('text', 'amount',
            array(
                'required' => true,
                'value'    => $this->amount,
                'validators' => array(
                    array('NotEmpty', true, array('messages' => array('isEmpty' => 'Enter the amount received'))),
                    array('MoneyInput', true)
                )
            )
        );

        $currencyService = new Common_Service_Currency();
        $currencyList = $currencyService->getCurrencyHash();
        $this->addElement('select', 'currency',
            array(
                'required' => true,
                'value'    => $this->currency,
                'multiOptions' => $currencyList
             )
        );

        $this->addElement('select', 'paymentMethod',
            array(
                'required'     => true,
                'value'        => $this->paymentMethod,
                'multiOptions' => $this->methodOptions
            )
        );

        $this->addElement('textarea', 'notes',
            array(
                'required' => false,
                'value'    => $this->notes
            )
        );
    }
}

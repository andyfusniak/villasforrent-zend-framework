<?php
class Vfr_Finance_Invoice
{
    // POUND SIGN Unicode: U+00A3, UTF-8: C2 A3
    const SYMBOL_GBP = '£';

    // EURO SIGN Unicode: U+20AC, UTF-8: E2 82 AC
    const SYMBOL_EUR = '€';

    // DOLLAR SIGN Unicode: U+0024, UTF-8: 24
    const SYMBOL_USD = '$';

    // THAI CURRENCY SYMBOL BAHT Unicode: U+0E3F, UTF-8: E0 B8 BF€€
    const SYMBOL_THB = '฿';

    /**
     * @var array
     */
    private $placeholders = array(
        'logo'            => array(300, 750),
        'from-address'    => array(400, 810),
        'billing-address' => array(40, 700),
        'invoice-details' => array(420, 700),
        'invoice-items'   => array(40, 500),
        'footer-details'  => array(40, 100)
    );

    /**
     * @var array
     */
    private $_styles = array();

    private $_vfrConfig;


    /**
     * @var float
     */
    private $_lineSpacing = 15.0;

    /**
     * @var float the height (in points) between adjacent invoice items
     */
    private $_invoiceItemLineHeight = 20.0;

    /**
     * @var array associative array of left-offset relative from left edge position (in points)
     */
    private $_invoiceItemLeftOffsets = array(
        'qty'         => 0,
        'unit-amount' => 72,
        'period'      => 144,
        'description' => 216,
        'period'      => 300,
        'line-total'  => 510
    );

    /**
     * @var string
     */
    private $_logoFilePath;

    /**
     * @var Zend_Pdf
     */
    private $_pdf;

    /**
     * @var Zend_Pdf_Page
     */
    private $_page;

    /**
     * @var Frontend_Model_Invoice the invoice object
     */
    private $_invoiceObj;


    public function __construct(Frontend_Model_Invoice $invoiceObj, $options = null)
    {
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $this->_vfrConfig = $bootstrap['vfr'];

        $this->_invoiceObj = $invoiceObj;

        // create a new PDF document
        $this->_pdf = new Zend_Pdf();

        // create a new A4 Page
        $this->_page = $this->_pdf->newPage(Zend_Pdf_Page::SIZE_A4);

        // attach the page to the PDF document
        array_push($this->_pdf->pages, $this->_page);

        // add the default style to the set
        $this->setupDefaultStyle();

        // override the logo file path if necessary
        if (isset($options['logoFilePath'])) {
            $this->_logoFilePath = $options['logoFilePath'];
        } else {
            $this->_logoFilePath = $this->_vfrConfig['finances']['pdf']['logoFilePath'];
        }

        $this->writePlaceholders();
    }

    private function setupDefaultStyle()
    {
        $style = new Zend_Pdf_Style();
        $style->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES), 9);

        $this->_styles['default'] = $style;
        $this->_page->setStyle($style);
    }

    private function placeLogo()
    {
        $logoImage = Zend_Pdf_Image::imageWithPath($this->_logoFilePath);
        $this->_page->drawImage($logoImage,
            $this->placeholders['logo'][0],
            $this->placeholders['logo'][1],
            $this->placeholders['logo'][0] + 92,
            $this->placeholders['logo'][1] + 72
        );
    }

    private function placeCompanyDetails($left, $top)
    {
        $fromAddressObj = $this->_invoiceObj->getFromAddress();

        $fromAddressList = array(
            $fromAddressObj->getName(),
            $fromAddressObj->getLine1(),
            $fromAddressObj->getLine2(),
            $fromAddressObj->getLine3() . ' ' .
            $fromAddressObj->getTownCity(),
            $fromAddressObj->getPostcode(),
            $fromAddressObj->getCountry()
        );

        $offset = 0;
        foreach ($fromAddressList as $current) {
            $this->_page->drawText(
                $current,
                $left,
                $top - $offset,
                'UTF-8'
            );

            $offset += $this->_lineSpacing;
        }
    }

    private function placeBillingDetails($left, $top)
    {
        $billingObj = $this->_invoiceObj->getBillingAddress();

        $billingAddressList = array(
            $billingObj->getName(),
            $billingObj->getLine1(),
            $billingObj->getLine2(),
            $billingObj->getLine3(),
            $billingObj->getTownCity(),
            $billingObj->getCounty(),
            $billingObj->getPostcode(),
            $billingObj->getCountry()
        );

        $offset = 0;
        foreach ($billingAddressList as $current) {
            $this->_page->drawText(
                $current,
                $left,
                $top - $offset,
                'UTF-8'
            );

            if (mb_strlen($current) > 0)
                $offset += $this->_lineSpacing;
        }
    }

    private function placeInvoiceDetails($left, $top)
    {
        $detailList = array(
            'Invoice No:' => $this->invoiceIdPadded($this->_invoiceObj->getInvoiceId()),
            'Invoice Date:' => $this->invoiceDate($this->_invoiceObj->getInvoiceDate())
        );

        // right border
        $boxSize = 120;
        $rightBorder = $left + $boxSize;
        $offset = 0;
        foreach ($detailList as $name => $value) {

            $this->_page->drawText(
                $name,
                $left,
                $top - $offset,
                'UTF-8'
            );

            $this->_page->drawText(
                $value,
                $left + $boxSize - $this->stringWidth($value,
                    $this->_page->getFont(),
                    $this->_page->getFontSize()
                ),
                $top - $offset,
                'UTF-8'
            );

            $offset += $this->_lineSpacing;

        }
    }

    private function placeInvoiceItems($left, $top)
    {
        $this->placeInvoiceItemHeaders($left, $top);

        $offset = $this->_invoiceItemLineHeight;
        foreach ($this->_invoiceObj->getInvoiceItems() as $invoiceItem) {
            $this->placeInvoiceItem($left, $top - $offset, $invoiceItem);

            $offset += $this->_invoiceItemLineHeight;
        }

        return $top - $offset;
    }

    private function placeInvoiceItemHeaders($left, $top)
    {
        $leftOffset = 0;
        $headerItems = array(
            'Qty'         => 'qty',
            'Unit Amount' => 'unit-amount',
            'Description' => 'description',
            'Subscription Period'      => 'period',
            'Line Total'  => 'line-total'
        );

        foreach($headerItems as $headerItem => $offsetName) {
            if ('line-total' === $offsetName) {
                $this->rightAlignDrawText(
                    $headerItem,
                    $left + $this->_invoiceItemLeftOffsets[$offsetName],
                    $top,
                    'UTF-8'
                );
            } else {
                $this->_page->drawText(
                    $headerItem,
                    $left + $this->_invoiceItemLeftOffsets[$offsetName],
                    $top,
                    'UTF-8'
                );
            }

        }
    }

    private function placeInvoiceItem($left, $top, $invoiceItem)
    {
        $offset = 0;

        $this->_page->drawText(
            $invoiceItem->getQty(),
            $left + $this->_invoiceItemLeftOffsets['qty'],
            $top,
            'UTF-8'
        );

        $this->_page->drawText(
            $invoiceItem->getUnitAmount(),
            $left + $this->_invoiceItemLeftOffsets['unit-amount'],
            $top,
            'UTF-8'
        );

        $this->_page->drawText(
            $this->invoiceDate($invoiceItem->getStartDate())
            . ' to '
            . $this->invoiceDate($invoiceItem->getExpiryDate()),
            $left + $this->_invoiceItemLeftOffsets['period'],
            $top,
            'UTF-8'
        );

        $this->_page->drawText(
            $invoiceItem->getDescription(),
            $left + $this->_invoiceItemLeftOffsets['description'],
            $top,
            'UTF-8'
        );

        $this->rightAlignDrawText(
            $this->invoiceMoney($invoiceItem->getLineTotal()),
            $left + $this->_invoiceItemLeftOffsets['line-total'],
            $top,
            'UTF-8'
        );
    }

    private function placeTotals($left, $top)
    {
        $this->_page->drawText(
            'Sub Total',
            $left + $this->_invoiceItemLeftOffsets['description'],
            $top
        );

        $this->rightAlignDrawText(
            $this->invoiceMoney($this->_invoiceObj->getTotal()),
            $left + $this->_invoiceItemLeftOffsets['line-total'],
            $top,
            'UTF-8'
        );
    }

    private function placeFooterDetails($left, $top)
    {
        $footerDetails = $this->_vfrConfig['finances']['pdf']['invoiceFooterDetails'];
        $parts = explode("_", $footerDetails);


        $offset = 0;
        foreach($parts as $next) {
            $this->_page->drawText(
                $next,
                $left,
                $top - $offset,
                'UTF-8'
            );

            $offset += $this->_lineSpacing;
        }

    }

    private function rightAlignDrawText($text, $rightEdge, $top, $encoding)
    {
        $stringLengthPoints = $this->stringWidth(
            $text, $this->_page->getFont(),
            $this->_page->getFontSize()
        );

        $this->_page->drawText(
            $text,
            $rightEdge - $stringLengthPoints,
            $top,
            $encoding
        );
    }

    /**
     * returns a padded version of the invoice to eight padded places
     * @param int $id the integer number to be formated
     * @return string the resulting padded string equivilant of the number
     */
    private function invoiceIdPadded($id)
    {
        return str_pad($id, 8, "0", STR_PAD_LEFT);
    }

    private function invoiceDate($dt)
    {
        return strtoupper(strftime("%e-%b-%y", strtotime($dt)));
    }

    private function invoiceMoney($total, $currency = null)
    {
        $totalString = number_format($total, 2, ".", ",");

        if ($currency)
            $totalString .= ' ' . strtoupper($currency);

        return $totalString;
    }

    /**
     * Calculates the width of the text string in points given the font and size
     * @param string $text the text string to be printed
     * @param Zend_Pdf_Font $font
     * @param int $fontSize the point-size of the font
     *
     * @return float the width of the text string in points
     */
    public static function stringWidth($text, $font, $fontSize)
    {
        $drawingString = iconv('UTF-8', 'UTF-16BE//IGNORE', $text);
        $characters = array();

        for ($i=0; $i < strlen($drawingString); $i++) {
            $characters[] = (ord($drawingString[$i++]) << 8) | ord($drawingString[$i]);
        }

        $glyphs = $font->glyphNumbersForCharacters($characters);
        $widths = $font->widthsForGlyphs($glyphs);
        $stringWidth = (array_sum($widths) / $font->getUnitsPerEm() * $fontSize);

        return $stringWidth;
    }

    private function writePlaceholders()
    {
        // place the components into the page
        $this->placeLogo();

        // from-company details
        $this->placeCompanyDetails(
            $this->placeholders['from-address'][0],
            $this->placeholders['from-address'][1]
        );

        // billing details
        $this->placeBillingDetails(
            $this->placeholders['billing-address'][0],
            $this->placeholders['billing-address'][1]
        );

        // invoice detais
        $this->placeInvoiceDetails(
            $this->placeholders['invoice-details'][0],
            $this->placeholders['invoice-details'][1]
        );

        $nextTop = $this->placeInvoiceItems(
            $this->placeholders['invoice-items'][0],
            $this->placeholders['invoice-items'][1]
        );

        $this->placeTotals(
            $this->placeholders['invoice-items'][0],
            $nextTop
        );

        $this->placeFooterDetails(
            $this->placeholders['footer-details'][0],
            $this->placeholders['footer-details'][1]
        );
    }

    /**
     * @return string the PDF string output
     */
    public function renderPdf()
    {
        return $this->_pdf->render();
    }
}

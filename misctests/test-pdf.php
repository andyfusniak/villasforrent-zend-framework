<?php
require_once 'Zend/Pdf.php';


function theStringWidth($text, $font, $fontSize)
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

$pdf = new Zend_Pdf();
$pdf->properties['Title'] = "TITLE TITLE TITLE";

// create a new A4 Page
$pdfPage = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);

// attach the page to the PDF document
array_push($pdf->pages, $pdfPage);

//$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
$pdfPage->setFont($font, 24);

$text1 = 'The quick € $ £ brown';
$text2 = 'fox jumps over the lazy dog';

//$pdfPage->clipRectangle(50, 50, 100, 600);
$pointSize1 = theStringWidth($text1, $font, 24); 
$pointSize2 = theStringWidth($text2, $font, 24); 

$pdfPage->drawText($text1, 300-$pointSize1, 400, 'UTF-8');
$pdfPage->drawText($text2, 300-$pointSize2, 450, 'UTF-8');

$pdfPage->drawLine(300, 0, 300, 700);


//$pdfPage->setLineWidth(0.1);
//$pdfPage->drawLine(0,0, 400, 400);
//$pdfPage->drawRoundedRectangle(100, 100, 400, 300, 8, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

//echo $pdfPage->getWidth() . PHP_EOL;
//echo $pdfPage->getHeight() . PHP_EOL;

//$fontList = $pdf->extractFonts();
//var_dump($fontList);
//die();

$pdf->save("test.pdf");
//$output = $pdf->render();

//print $output;



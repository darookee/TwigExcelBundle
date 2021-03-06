<?php

namespace MewesK\TwigExcelBundle\Tests\Functional;

use Twig_Error_Runtime;

/**
 * Class DefaultControllerTest
 * @package MewesK\TwigExcelBundle\Tests\Functional
 */
class DefaultControllerTest extends AbstractControllerTest
{
    //
    // PhpUnit
    //

    /**
     * @return array
     */
    public function formatProvider()
    {
        return [['ods'], ['xls'], ['xlsx']];
    }

    //
    // Tests
    //

    /**
     * @param string $format
     *
     * @throws \PHPExcel_Exception
     *
     * @dataProvider formatProvider
     */
    public function testSimple($format)
    {
        try {
            $document = $this->getDocument(static::$router->generate('test_default', ['templateName' => 'simple', '_format' => $format]), $format);
            static::assertNotNull($document, 'Document does not exist');

            $sheet = $document->getSheetByName('Test');
            static::assertNotNull($sheet, 'Sheet does not exist');

            static::assertEquals(100270, $sheet->getCell('B22')->getValue(), 'Unexpected value in B22');

            static::assertEquals('=SUM(B2:B21)', $sheet->getCell('B23')->getValue(), 'Unexpected value in B23');
            static::assertTrue($sheet->getCell('B23')->isFormula(), 'Unexpected value in isFormula');
            static::assertEquals(100270, $sheet->getCell('B23')->getCalculatedValue(), 'Unexpected calculated value in B23');
        } catch (Twig_Error_Runtime $e) {
            static::fail($e->getMessage());
        }
    }
}

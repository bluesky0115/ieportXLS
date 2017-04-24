<?php

use Imtigger\OneExcel\ColumnType;
use Imtigger\OneExcel\Driver;
use Imtigger\OneExcel\Format;
use PHPUnit\Framework\TestCase;

final class LibXLTest extends TestCase {

    private function getCellValue($filename, $cellName)
    {
        $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
        $objExcel = $objReader->load($filename);

        $value = $objExcel->getActiveSheet()->getCell($cellName)->getValue();

        unset($objReader);
        unset($objExcel);

        return $value;
    }

    public function testCreateXLSX()
    {
        $excel = \Imtigger\OneExcel\OneExcelWriterFactory::create(Format::XLSX, Driver::LIBXL);
        $this->assertInstanceOf(\Imtigger\OneExcel\Writer\LibXLWriter::class, $excel);

        $excel->writeCell(1, 0, 'Hello');
        $excel->writeCell(2, 1, 'World');
        $excel->writeCell(3, 2, 3.141592653, ColumnType::NUMERIC);
        $excel->writeRow(4, ['One', 'Excel']);

        $path = 'tests/test-libxl.xlsx';

        $excel->save($path);

        $this->assertFileExists($path);
        $this->assertGreaterThan(0, filesize($path));

        $this->assertEquals('Hello', $this->getCellValue($path, 'A1'));
        $this->assertEquals('World', $this->getCellValue($path, 'B2'));
        $this->assertEquals(3.141592653, $this->getCellValue($path, 'C3'));
        $this->assertEquals('One', $this->getCellValue($path, 'A4'));
        $this->assertEquals('Excel', $this->getCellValue($path, 'B4'));

        unlink($path);
    }

}

<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 28 March 2019, 4:35 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

class CsvReader extends TestCase
{
    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function testRead()
    {
        $this->assertTrue(true);
        $inputFileName = __DIR__ . '/../../../database/vault/UserVault.csv';
        $reader        = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader->setDelimiter(';');
        $reader->setReadEmptyCells(true);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);
        $worksheet   = $spreadsheet->setActiveSheetIndex(0);
        foreach ($worksheet->getRowIterator() as $row)
        {
            foreach ($row->getCellIterator('A', 'M') as $cell)
            {
                echo $cell->getValue() . "\t";
            }
            echo $row->getRowIndex() . "\n";
        }
    }
}

?>

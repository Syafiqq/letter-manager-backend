<?php

use App\Eloquent\Letter;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 01 April 2019, 10:15 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
class LetterSeeder extends RollbackAbleSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function run()
    {
        $inputFileName = __DIR__ . '/../vault/LetterVault.csv';
        $reader        = new Csv();
        $reader->setDelimiter(';');
        $reader->setReadEmptyCells(true);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);
        $worksheet   = $spreadsheet->setActiveSheetIndex(0);
        foreach ($worksheet->getRowIterator() as $row)
        {
            $val = [];
            foreach ($row->getCellIterator('A', 'L') as $cell)
            {
                $val[] = $cell->getValue();
            }
            $model                 = new Letter();
            $model->{'id'}         = $val[0];
            $model->{'title'}      = $val[1];
            $model->{'code'}       = $val[2];
            $model->{'index'}      = $val[3];
            $model->{'number'}     = $val[4];
            $model->{'subject'}    = $val[5];
            $model->{'date'}       = is_null($val[6]) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $val[6]);
            $model->{'kind'}       = $val[7];
            $model->{'file'}       = $val[8];
            $model->{'created_at'} = is_null($val[9]) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $val[9]);
            $model->{'updated_at'} = is_null($val[10]) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $val[10]);
            $model->{'issuer'}     = $val[11];
            $model->save();
        }

    }


    function roll()
    {
        Letter::query()->delete();
    }
}

?>

<?php

use App\Eloquent\Coupon;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class CouponSeeder extends RollbackAbleSeeder
{
    /**
     * Run the database seeds
     *
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function run()
    {
        $inputFileName = __DIR__ . '/../vault/CouponVault.csv';
        $reader        = new Csv();
        $reader->setDelimiter(';');
        $reader->setReadEmptyCells(true);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);
        $worksheet   = $spreadsheet->setActiveSheetIndex(0);
        foreach ($worksheet->getRowIterator() as $row)
        {
            $val = [];
            foreach ($row->getCellIterator('A', 'E') as $cell)
            {
                $val[] = $cell->getValue();
            }
            $model                 = new Coupon();
            $model->{'id'}         = $val[0];
            $model->{'coupon'}     = $val[1];
            $model->{'assignee'}   = $val[2];
            $model->{'usage'}      = $val[3];
            $model->{'created_at'} = is_null($val[4]) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $val[4]);
            $model->save();
        }

    }


    function roll()
    {
        Coupon::query()->delete();
    }
}

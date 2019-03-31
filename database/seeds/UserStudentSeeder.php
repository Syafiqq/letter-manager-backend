<?php

use Carbon\Carbon;

class UserStudentSeeder extends RollbackAbleSeeder
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
        $inputFileName = __DIR__ . '/../vault/UserVault.csv';
        $reader        = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
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
            $model                     = new \App\Eloquents\User();
            $model->{'id'}             = $val[0];
            $model->{'credential'}     = $val[1];
            $model->{'email'}          = $val[2];
            $model->{'name'}           = $val[3];
            $model->{'gender'}         = $val[4];
            $model->{'role'}           = $val[5];
            $model->{'stamp'}          = $val[6];
            $model->{'avatar'}         = $val[7];
            $model->{'password'}       = is_null($val[8]) ? null : app('hash')->make($val[8], []);
            $model->{'remember_token'} = $val[9];
            $model->{'created_at'}     = is_null($val[10]) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $val[10]);
            $model->{'updated_at'}     = is_null($val[11]) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $val[11]);
            $model->save();
        }

    }


    function roll()
    {
        \App\Eloquents\User::query()->delete();
    }
}

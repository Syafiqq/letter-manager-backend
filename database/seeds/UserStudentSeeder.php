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
            $user                     = new \App\Eloquents\User();
            $user->{'id'}             = $val[0];
            $user->{'credential'}     = $val[1];
            $user->{'email'}          = $val[2];
            $user->{'name'}           = $val[3];
            $user->{'gender'}         = $val[4];
            $user->{'role'}           = $val[5];
            $user->{'stamp'}          = $val[6];
            $user->{'avatar'}         = $val[7];
            $user->{'password'}       = is_null($val[8]) ? null : app('hash')->make($val[8], []);
            $user->{'remember_token'} = $val[9];
            $user->{'created_at'}     = is_null($val[10]) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $val[10]);
            $user->{'updated_at'}     = is_null($val[11]) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $val[11]);
            $user->save();
        }

    }


    function roll()
    {
        \App\Eloquents\User::query()->delete();
    }
}

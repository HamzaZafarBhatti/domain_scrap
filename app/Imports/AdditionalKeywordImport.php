<?php

namespace App\Imports;

use App\Models\Keyword;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class AdditionalKeywordImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            Keyword::updateOrCreate(['name' => $row[0]],[
                'name' => $row[0],
            ]);
        }
    }
}

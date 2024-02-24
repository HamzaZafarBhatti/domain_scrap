<?php

namespace App\Imports;

use App\Models\Niche;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class NicheImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            Niche::updateOrCreate(['name' => $row[0]],[
                'name' => $row[0],
                'is_active' => true,
            ]);
        }
    }
}

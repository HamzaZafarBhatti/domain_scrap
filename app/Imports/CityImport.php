<?php

namespace App\Imports;

use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class CityImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            City::updateOrCreate(['name' => $row[0]],[
                'name' => $row[0],
                'is_active' => false,
            ]);
        }
    }
}

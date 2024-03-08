<?php

namespace App\Imports;

use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class CityImport implements ToCollection
{
    public function __construct(private $country_id)
    {
    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!isset($row[0])) continue;
            City::updateOrCreate(['name' => $row[0], 'country_id' => $this->country_id], [
                'name' => $row[0],
                'country_id' => $this->country_id,
                'is_active' => true,
            ]);
        }
    }
}

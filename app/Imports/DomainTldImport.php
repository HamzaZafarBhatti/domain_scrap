<?php

namespace App\Imports;

use App\Models\DomainTld;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DomainTldImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $domain = $row[0];
            if (substr($row[0], 0, 1) !== '.') {
                $domain = '.' . $row[0];
            }
            DomainTld::updateOrCreate(['name' => $row[0]],[
                'name' => $domain,
                'is_active' => true,
            ]);
        }
    }
}

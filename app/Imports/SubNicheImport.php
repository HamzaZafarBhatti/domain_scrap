<?php

namespace App\Imports;

use App\Models\Niche;
use App\Models\SubNiche;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SubNicheImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    private $niche;
    public function __construct($niche)
    {
        $this->niche = $niche;
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if(!isset($row[0])) continue;
            SubNiche::updateOrCreate(['name' => $row[0]], [
                'name' => $row[0],
                'niche_id' => $this->niche,
                'is_active' => true,
            ]);
        }
    }
}

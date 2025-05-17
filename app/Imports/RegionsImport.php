<?php 

namespace App\Imports;

use App\Models\Region;
use Maatwebsite\Excel\Concerns\ToModel;

class RegionsImport implements ToModel
{
    public function model(array $row)
    {
        // Check if the row has the required number of columns
        if (count($row) < 3) {
            // Handle the error (e.g., log it or skip this row)
            return null; // Skip the row if it doesn't have enough columns
        }

        return new Region([
            'id' => $row[0] ?? null, // Use null if the index is not set
            'name' => $row[1] ?? null,
            'admin_id' => $row[2] ?? null,

            // Uncomment and adjust according to your Excel file structure
            // 'federal_id' => $row[3] ?? null,
        ]);
    }
}

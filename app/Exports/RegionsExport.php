<?php
namespace App\Exports;

use App\Models\Region;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Region::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Admin', // Add other headings as necessary
        ];
    }
}

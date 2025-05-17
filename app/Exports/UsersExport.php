<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class UsersExport implements FromCollection, WithHeadings
{

    public function collection()
    {
        return User::all();
    }
     public function headings(): array
    {
        return [
            'Name',
            'Email', // Add other headings as necessary
            'Roles', // Add other headings as necessary
            'Admin/User', // Add other headings as necessary
        ];
    }
}

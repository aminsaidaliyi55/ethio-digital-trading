<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    public function model(array $row)
    {
        return new User([
            'Name' => $row[0],
            'Email' => $row[1],
            'Roles' => $row[2],
            'Admin/User' => $row[3],
            // Map other fields as necessary
            // Ensure password handling is secure
        ]);
    }
}

<?php

namespace App\Exports;

use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings
{
    use Exportable;
    public function collection()
    {
        return User::select(
            "id",
            "name",
            'full_name',
            'status',
            DB::raw(
                "date(created_at)",
            ),
            DB::raw(
                "date(modified_at)",
            ),
        )->get();
    }
    public function headings(): array
    {
        return [
            "User ID",
            "Username",
            "Full Name",
            "Status",
            "Created At",
            'Modified At',
        ];
    }
}

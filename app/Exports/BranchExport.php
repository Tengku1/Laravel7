<?php

namespace App\Exports;

use App\Branch;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BranchExport implements FromCollection, WithHeadings
{
    use Exportable;
    public function collection()
    {
        return Branch::select(
            "code",
            "name",
            'address_name',
            'status',
        )->get();
    }
    public function headings(): array
    {
        return [
            "Branch Code",
            "Branch Name",
            "Address",
            "Status",
        ];
    }
}

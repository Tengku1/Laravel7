<?php

namespace App\Exports;

use App\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct()
    {
    }

    public function collection()
    {
        return Product::select(
            "id",
            "name",
            'sell_price',
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
            "Product ID",
            "Product Name",
            "Sell Price",
            "Status",
            "Created At",
            'Modified At',
        ];
    }
}

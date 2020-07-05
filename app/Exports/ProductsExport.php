<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{

    use Exportable;

    public function collection()
    {
        return Product::get();
    }
    public function headings(): array
    {
        return [
            "Product ID",
            "Product Name",
            "Slug",
            "Sell Price",
            "Status",
            "Created At",
            "Modified At",
        ];
    }
}

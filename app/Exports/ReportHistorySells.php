<?php

namespace App\Exports;

use App\history_sell_product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportHistorySells implements FromCollection, WithHeadings
{
    use Exportable;
    public function collection()
    {
        if (Auth::user()->roles[0] == "Master") {
            return history_sell_product::join("history_sell", "history_sell_product.history_sell", "history_sell.id")
                ->select(
                    "history_sell.id",
                    DB::raw("sum(history_sell_product.qty) as TotalQty")
                )
                ->groupBy("history_sell.id")->get();
        } else {
            return history_sell_product::join("history_sell", "history_sell_product.history_sell", "history_sell.id")
                ->select(
                    "history_sell.id",
                    DB::raw("sum(history_sell_product.qty) as TotalQty")
                )
                ->where("history_sell.modified_user", "=", Auth::user()->name)
                ->groupBy("history_sell.id")->get();
        }
    }
    public function headings(): array
    {
        return [
            "Reff ID",
            "Quantity",
        ];
    }
}

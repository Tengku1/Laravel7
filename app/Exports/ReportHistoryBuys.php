<?php

namespace App\Exports;

use App\history_buy_product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportHistoryBuys implements FromCollection, WithHeadings
{
    use Exportable;

    public function collection()
    {
        if (Auth::user()->roles[0] == "Master") {
            return history_buy_product::join("history_buy", "history_buy_product.history_buy", "history_buy.id")->select(
                "history_buy.id",
                DB::raw("sum(history_buy_product.qty) as TotalQty")
            )->groupBy("history_buy.id")->get();
        } else {
            return history_buy_product::join("history_buy", "history_buy_product.history_buy", "history_buy.id")->select(
                "history_buy.id",
                DB::raw("sum(history_buy_product.qty) as TotalQty")
            )->where("history_buy.modifed_user", "=", Auth::user()->name)->groupBy("history_buy.id")->get();
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

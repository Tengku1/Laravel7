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

    public function __construct($fromDate, $toDate, $branch)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->branch = $branch;
    }

    public function collection()
    {
        if (Auth::user()->roles[0] == "Master") {

            if ($this->fromDate == date("Y-m-d") && $this->toDate == date("Y-m-d")) {
                return history_sell_product::join("history_sell", "history_sell_product.history_sell", "history_sell.id")
                    ->select(
                        "history_sell.id",
                        DB::raw("sum(history_sell_product.qty) as TotalQty")
                    )
                    ->where('history_sell.created_at', 'like', date("Y-m-d") . '%')->where('history_sell.branch_code', 'like', $this->branch)
                    ->groupBy("history_sell.id")->get();
            } else {
                return history_sell_product::join("history_sell", "history_sell_product.history_sell", "history_sell.id")
                    ->select(
                        "history_sell.id",
                        DB::raw("sum(history_sell_product.qty) as TotalQty")
                    )
                    ->where('history_sell.created_at', '>=', $this->fromDate)->where('history_sell.created_at', '<=', $this->toDate)->where('history_sell.branch_code', 'like', $this->branch)
                    ->groupBy("history_sell.id")->get();
            }
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

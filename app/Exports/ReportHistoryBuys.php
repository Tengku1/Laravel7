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

    public function __construct($fromDate, $toDate, $branch)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->branch = $branch;
    }

    public function collection()
    {
        // Check user Roles
        if (Auth::user()->roles[0] == "Master") {
            // Check Selected Date (both) is the same as today or not
            if ($this->fromDate == date("Y-m-d") && $this->toDate == date("Y-m-d")) {
                return history_buy_product::join("history_buy", "history_buy_product.history_buy", "history_buy.id")->select(
                    "history_buy.id",
                    DB::raw("sum(history_buy_product.qty) as TotalQty")
                )->where('history_buy.created_at', 'like', date("Y-m-d") . '%')->where('history_buy.branch_code', 'like', $this->branch)->groupBy("history_buy.id")->get();
            } else {
                return history_buy_product::join("history_buy", "history_buy_product.history_buy", "history_buy.id")->select(
                    "history_buy.id",
                    DB::raw("sum(history_buy_product.qty) as TotalQty")
                )->where('history_buy.created_at', '>=', $this->fromDate)->where('history_buy.created_at', '<=', $this->toDate)->where('history_buy.branch_code', 'like', $this->branch)->groupBy("history_buy.id")->get();
            }
        } else {
            return history_buy_product::join("history_buy", "history_buy_product.history_buy", "history_buy.id")->select(
                "history_buy.id",
                DB::raw("sum(history_buy_product.qty) as TotalQty")
            )->where("history_buy.modified_user", "=", Auth::user()->name)->where('history_buy.branch_code', '=', Auth::user()->branch_code)->groupBy("history_buy.id")->get();
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

<?php

namespace App\Exports;

use App\history_buys;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportDetailHistoryBuys implements FromCollection, WithHeadings
{
    use Exportable;
    public function __construct($reffID, $fromDate, $toDate)
    {
        $this->from = $fromDate;
        $this->to = $toDate;
        $this->Reff = $reffID;
    }
    public function collection()
    {
        if ($this->from == null && $this->to == null) {
            return history_buys::join("history_buy_product", "history_buy_product.history_buy", "history_buy.id")
                ->join("products", "history_buy_product.product_id", "products.id")
                ->join("branch", "history_buy.branch_code", "branch.code")
                ->select(
                    "products.name",
                    "branch.name as BranchName",
                    "qty",
                    "buy_price",
                    DB::raw("sum(qty * buy_price) as SubTotal"),
                    DB::raw("month(history_buy.created_at) as Month"),
                    DB::raw("year(history_buy.created_at) as Year")
                )
                ->where("history_buy.id", "=", $this->Reff)->groupBy("products.name", "BranchName", "qty", "buy_price", "history_buy.created_at")->get();
        } else {
            return history_buys::join("history_buy_product", "history_buy_product.history_buy", "history_buy.id")
                ->join("products", "history_buy_product.product_id", "products.id")
                ->join("branch", "history_buy.branch_code", "branch.code")
                ->select(
                    "products.name",
                    "branch.name as BranchName",
                    "qty",
                    "buy_price",
                    DB::raw("sum(qty * buy_price) as SubTotal"),
                    DB::raw("month(history_buy.created_at) as Month"),
                    DB::raw("year(history_buy.created_at) as Year")
                )
                ->where("history_buy.created_at", ">=", $$this->from)
                ->where("history_buy.created_at", ">=", $this->to)
                ->where("history_buy.id", "=", $this->Reff)->groupBy("products.name", "BranchName", "qty", "buy_price", "history_buy.created_at")->get();
        }
    }
    public function headings(): array
    {
        return [
            "Product Name",
            "Branch Name",
            "Quantity",
            "Buy Price",
            "Sub Total",
            "Month",
            "Year",
        ];
    }
}

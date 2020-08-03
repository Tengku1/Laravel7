<?php

namespace App\Exports;

use App\history_sell;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportDetailHistorySells implements FromCollection, WithHeadings
{
    public function __construct($reffID, $fromDate, $toDate)
    {
        $this->from = $fromDate;
        $this->to = $toDate;
        $this->Reff = $reffID;
    }
    public function collection()
    {
        if ($this->from == null && $this->to == null) {
            return history_sell::join("history_sell_product", "history_sell_product.history_sell", "history_sell.id")
                ->join("products", "history_sell_product.product_id", "products.id")
                ->join("branch", "history_sell.branch_code", "branch.code")
                ->select(
                    "products.name",
                    "branch.name as BranchName",
                    "qty",
                    "buy_price",
                    "products.sell_price",
                    DB::raw("sum(qty * products.sell_price) as SubTotal"),
                    DB::raw("month(history_sell.created_at) as Month"),
                    DB::raw("year(history_sell.created_at) as Year")
                )
                ->where("history_sell.id", "=", $this->Reff)->groupBy("products.name", "BranchName", "qty", "buy_price", "products.sell_price", "history_sell.created_at")->get();
        } else {
            return history_sell::join("history_sell_product", "history_sell_product.history_sell", "history_buy.id")
                ->join("products", "history_sell_product.product_id", "products.id")
                ->join("branch", "history_sell.branch_code", "branch.code")
                ->select(
                    "products.name",
                    "branch.name as BranchName",
                    "qty",
                    "buy_price",
                    "products.sell_price",
                    DB::raw("sum(qty * products.sell_price) as SubTotal"),
                    DB::raw("month(history_sell.created_at) as Month"),
                    DB::raw("year(history_sell.created_at) as Year")
                )
                ->where("history_sell.created_at", ">=", $$this->from)
                ->where("history_sell.created_at", ">=", $this->to)
                ->where("history_sell.id", "=", $this->Reff)->groupBy("products.name", "BranchName", "qty", "buy_price", "products.sell_price", "history_sell.created_at")->get();
        }
    }
    public function headings(): array
    {
        return [
            "Product Name",
            "Branch Name",
            "Quantity",
            "Buy Price",
            "Sell Price",
            "Sub Total",
            "Month",
            "Year",
        ];
    }
}

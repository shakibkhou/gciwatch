<?php
namespace App;
use App\Transfer;
use App\TransferItem;
use App\Product;
use App\Memo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
class TradeExport implements FromCollection, WithMapping, WithHeadings
{
    protected $ids;
    function __construct($ids) {
        $this->ids = $ids;
    }
    public function collection()
    {
        $Invoice= Memo::select('memos.order_total','memos.id as memoid','memo_details.id','memo_details.item_status','retail_resellers.customer_name','products.stock_id','products.model','memo_details.row_total','memo_details.status_updated_date','product_stocks.sku')
        ->join('memo_details', 'memo_details.memo_id', '=', 'memos.id')
        ->join('retail_resellers', 'retail_resellers.id', '=', 'memos.customer_name')
        ->join('products', 'products.id', '=', 'memo_details.product_id')
        ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
        ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
        ->selectRaw('GROUP_CONCAT(model) as model_numbers')
        ->where('memo_details.item_status','=','4')
        ->groupBy('memo_details.memo_id')
            ->whereIn('memo_details.id',$this->ids)
            ->get();

        return $Invoice;

    }



    public function headings(): array

    {

      return [

            'Memo NUmber',
            'Customer name',
            'Stock ID',
            'Model Number',
            'Serial ',
            'Sub Total',
            'Status',
            'Date',
        ];
    }
    /**
    * @var Invoice $invoice
    */
    public function map($invoice): array
    {
        // dd($invoice);
        $statu='';
        if($invoice->item_status == 1)
        {
           $statu='Memo';
        }
        elseif($invoice->item_status==2)
        {
            $statu= "INVOICE";
        }
        elseif($invoice->item_status==3)
        {
            $statu= "RETURN";
        }
        elseif($invoice->item_status==4)
        {
            $statu= "TRADE";
        }
        elseif($invoice->item_status==5)
        {
            $statu= "VOID";
        }
        elseif($invoice->item_status==6)
        {
            $statu= "TRADE NGD";
        }
        $memoNumber="TRD101" .$invoice->memoid;
        $customername='';
      if($invoice->customer_group=='reseller')
      {
          $customername= $invoice->company;
      }
        else
        {
          $customername=  $invoice->customer_name;
        }
								
        return [
            $memoNumber,
            $customername,
            $invoice->stock_id,
            $invoice->model,
            $invoice->sku,
            $invoice->row_total,
            $statu,
            date('m/d/20y', strtotime($invoice->status_updated_date)),

        ];

    }

}

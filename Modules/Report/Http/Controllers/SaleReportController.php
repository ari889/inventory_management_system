<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sale\Entities\Sale;
use Modules\System\Entities\Warehouse;

class SaleReportController extends Controller
{
    protected function setPageData($page_title,$sub_title,$page_icon)
    {
        view()->share(['page_title'=>$page_title,'sub_title'=>$sub_title,'page_icon'=>$page_icon]);
    }

    public function dailySale()
    {
        if(permission('daily-sale-access')){
            $this->setPageData('Daily Sale Report', 'Daily Sale Report', 'fas fa-file-signature');
            $warehouses = Warehouse::where('status', 1)->get();
            return view('report::sale.daily.index', compact('warehouses'));
        }else{
            return redirect('unauthorized');
        }
    }


    /**
     * daily sales post method
     */
    public function dailySaleReport(Request $request){
        if($request->ajax())
        {
            $warehouse_id = $request->warehouse_id;
            $month        = $request->month;
            $year         = $request->year;
            $start        = 1;
            $number_of_day  = cal_days_in_month(CAL_GREGORIAN,$month,$year);
            $total_discount = [];
            $order_discount = [];
            $total_tax     = [];
            $order_tax      = [];
            $shipping_cost  = [];
            $grand_total    = [];
            while ($start <= $number_of_day) {
                $date =  ($start < 10) ? $year.'-'.$month.'-0'.$start : $year.'-'.$month.'-'.$start;
                $query = [
                    'SUM(total_discount) AS total_discount',
                    'SUM(order_discount) AS order_discount',
                    'SUM(total_tax) AS total_tax',
                    'SUM(order_tax) AS order_tax',
                    'SUM(shipping_cost) AS shipping_cost',
                    'SUM(grand_total) AS grand_total',
                ];
                $sale_data = Sale::whereDate('created_at',$date)->selectRaw(implode(',',$query));
                if($warehouse_id  != 0)
                {
                   $sale_data->where('warehouse_id',$warehouse_id);
                }
                $sale_data = $sale_data->get();
                if($sale_data)
                {
                    $total_discount[$start] = $sale_data[0]->total_discount;
                    $order_discount[$start] = $sale_data[0]->order_discount;
                    $total_tax[$start] = $sale_data[0]->total_tax;
                    $order_tax[$start] = $sale_data[0]->order_tax;
                    $shipping_cost[$start] = $sale_data[0]->shipping_cost;
                    $grand_total[$start] = $sale_data[0]->grand_total;
                }
                $start++;
            }

            $start_day = date('w',strtotime($year.'-'.$month.'-01')) + 1;
            $prev_year = date('Y',strtotime('-1 month',strtotime($year.'-'.$month.'-01')));
            $prev_month = date('m',strtotime('-1 month',strtotime($year.'-'.$month.'-01')));
            $next_year = date('Y',strtotime('+1 month',strtotime($year.'-'.$month.'-01')));
            $next_month = date('m',strtotime('+1 month',strtotime($year.'-'.$month.'-01')));

            return view('report::sale.daily.report',compact('total_discount','order_discount','total_tax','order_tax','shipping_cost','grand_total',
            'start_day','year','month','number_of_day','prev_year','prev_month','next_year','next_month'))->render();
        }
    }
}

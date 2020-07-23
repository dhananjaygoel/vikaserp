<?php

namespace App\Exports;

use App\DeliveryChallan;
use Auth;
use Input;
use App\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class SalesDaybookExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return DeliveryChallan::all();
    // }

    public function view(): View
    {
        $data = Input::all();
        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                        ->where('updated_at', 'like', $date1 . '%')
                        ->where('serial_number', 'like', '%P%')
//                       >with('customer.states', 'customer.customerproduct', 'delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'delivery_challan_products.order_product_details.product_category', 'delivery_order', 'user', 'delivery_location', 'challan_loaded_by', 'challan_labours')
                        ->with('delivery_challan_products.order_product_details', 'challan_loaded_by', 'challan_labours')
                        ->orderBy('updated_at', 'desc')
                        ->get();
            } else {
                $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                        ->where('updated_at', '>=', $date1)
                        ->where('updated_at', '<=', $date2 . ' 23:59:59')
                        ->where('serial_number', 'like', '%P%')
//                        ->with('customer.states', 'customer.customerproduct', 'delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'delivery_challan_products.order_product_details.product_category', 'delivery_order', 'user', 'delivery_location', 'challan_loaded_by', 'challan_labours')
                        ->with('delivery_challan_products.order_product_details', 'challan_loaded_by', 'challan_labours')
                        ->orderBy('updated_at', 'desc')
                        ->get();
            }
        } else {
            $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                    ->with('delivery_challan_products.order_product_details', 'challan_loaded_by', 'challan_labours')
                    ->where('serial_number', 'like', '%P%')
                    ->orderBy('updated_at', 'desc')
                    ->get();
        }    
        if (count((array)$allorders) < 1) {
            return redirect('sales_daybook')->with('flash_message', 'Order does not exist.');
        }
       // echo '<pre>';
       // print_r($allorders) ;
       // exit;
        $VchNo = 0;        
        foreach ($allorders as $key => $value) {
            $sr[$VchNo]['date'] = isset($value->updated_at)?date("d/m/Y", strtotime($value->updated_at)):'';
            $sr[$VchNo]['type'] = 'Invoice';
            $sr[$VchNo]['no'] = isset($value->id)?$value->id:'';
            if(isset($value->customer_id) && $value->customer_id != '') {
                $customer = Customer::find($value->customer_id);
                $deliver_location = $customer->delivery_location_id;
                if($deliver_location){
                   // $city = City::find($deliver_location);
                   
                    $city_name = "Place of supply";
                }
                else{
                    $city_name = "";
                }
                if($customer) {
                     if(isset($customer->tally_name) && $customer->tally_name != ""){
                         $tally_name = $customer->tally_name;
                     }
                     elseif(isset($customer->owner_name)){
                         $tally_name = $customer->owner_name;
                     }
                     else{
                         $tally_name = "Anonymous User";
                     }                      
                    $total_btax = @$value['delivery_challan_products'][0]->price;
                    $balance = @$value['delivery_challan_products'][0]->quantity;
                    $total = $total_btax * $balance; //$value->grand_price;
                    $percent = 12 * $total ;
                    $tax = $percent /100 ;//$value->vat_percentage; 
                    $status = 'Open';
                    $invoice_no = $value->doc_number; 
                    $due_date =  date("d/m/Y", strtotime($value->updated_at));
                    $placeof_supply = $city_name;
                    $producttitle = @$value['delivery_challan_products'][0]['order_product_details']->alias_name;

                } else {
                    $tally_name = 'Anonymous User';
                    $total = '0.00';
                    $total_btax = '0.00';
                    $balance = '0.00';
                    $tax = '0.00';
                    $status = '';
                    $invoice_no = '';
                    $due_date =  date("d/m/Y", strtotime($value->updated_at));
                    $placeof_supply = $city_name;
                    $producttitle = @$value['delivery_challan_products'][0]['order_product_details']->alias_name;
                }                                
            } else {
                $tally_name = 'Anonymous User';
                $total = '0.00';
                $total_btax = '0.00';
                $balance = '0.00';
                $tax = '0.00';
                $status = '';
                $invoice_no = '';
                $due_date =  date("d/m/Y", strtotime($value->updated_at));
                $placeof_supply = $city_name;
                $producttitle = @$value['delivery_challan_products'][0]['order_product_details']->alias_name;
            }
            
            $sr[$VchNo]['customer'] = $tally_name;
            $sr[$VchNo]['due_date'] = $due_date;            
            $sr[$VchNo]['balance'] = $balance;            
            $sr[$VchNo]['total_btax'] = $total_btax;
            $sr[$VchNo]['tax'] = $tax;
            $sr[$VchNo]['total'] = $total;
            $sr[$VchNo]['status'] = $status;
            $sr[$VchNo]['invoice_no'] = $invoice_no;
            $sr[$VchNo]['placeof_supply'] = $placeof_supply;
            $sr[$VchNo]['producttitle'] = $producttitle;
            
            $VchNo++;
        }
        return view('excelView.sales', array('allorders' => $allorders));
    }
}

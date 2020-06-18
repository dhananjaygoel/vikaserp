<?php

namespace App\Exports;

use App\Inquiry;
use App\DeliveryLocation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Input;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InquiryExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Inquiry::all();
    // }

    public function view(): View
    {
        $inquiry = Input::all();
        $inquiry_status = '';
        $is_approval = '';
        if ($inquiry['inquiry_status'] == 'Pending' || $inquiry['inquiry_status'] == 'pending') {
            $inquiry_status = 'pending';
            $is_approval = 'yes';
        } elseif ($inquiry['inquiry_status'] == 'Completed' || $inquiry['inquiry_status'] == 'completed') {
            $inquiry_status = 'completed';
            $is_approval = 'yes';
        } elseif ($inquiry['inquiry_status'] == 'Pending_Approval' || $inquiry['inquiry_status'] == 'pending_approval') {
            $inquiry_status = 'pending';
            $is_approval = 'no';
        }
        $inquiry_objects = Inquiry::where('inquiry_status', $inquiry_status)
                ->where('is_approved', '=', $is_approval)
                ->with('inquiry_products.unit', 'inquiry_products.inquiry_product_details', 'customer', 'createdby')
                ->orderBy('created_at', 'desc')
                ->get();
        
        if (count((array)$inquiry_objects) == 0) {
            return redirect::back()->with('flash_message', 'No data found');
        } else {
            $delivery_location = DeliveryLocation::all();
            return view('excelView.inquiry', array('inquiry_objects' => $inquiry_objects, 'delivery_location' => $delivery_location));
        }
    }
}

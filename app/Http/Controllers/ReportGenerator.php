<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PdfReport;

class ReportGenerator extends Controller
{
    public function vendor_products_report($id){
    $vendor = \App\Vendor::with('user')->find($id);
     $products = \App\Products::with('category','vendor','vendor.user')->where('vendor_id','=', $id)->get();
     $price_total =  \App\Products::with('category','vendor','vendor.user')->where('vendor_id','=', $id)->sum('product_price');
     $quantity_total =  \App\Products::with('category','vendor','vendor.user')->where('vendor_id','=', $id)->sum('quantity');
     return view('backoffice.vendors.products_report',compact('products','vendor','price_total','quantity_total'));
    }

    public function influencer_products_report($id){
        $influencer = \App\Influencer::with('user')->find($id);
         $products = \App\Products::with('category','influencer','influencer.user')->where('influencer_id','=', $id)->get();
         $price_total =  \App\Products::with('category','influencer','influencer.user')->where('influencer_id','=', $id)->sum('product_price');
         $quantity_total =  \App\Products::with('category','influencer','influencer.user')->where('influencer_id','=', $id)->sum('quantity');
         return view('backoffice.influencers.products_report',compact('products','influencer','price_total','quantity_total'));
    }


    public function influencer_active_bookings_report($id){
        $influencer = \App\Influencer::with('user')->find($id);
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','active')->get();
        $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','active')->sum('total_cost');
        $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','active')->sum('amount_paid');
        $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','active')->sum('balance');
        $status = "ACTIVE ";
        return view('backoffice.influencers.bookings_report',compact('bookings','influencer','status','amount_paid_total','bookings_total','balance_total'));
   }

   public function influencer_pending_bookings_report($id){
       $influencer = \App\Influencer::with('user')->find($id);
       $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','pending')->get();
       $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','pending')->sum('total_cost');
       $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','pending')->sum('amount_paid');
       $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','pending')->sum('balance');
       $status = "PENDING ";
       return view('backoffice.influencers.bookings_report',compact('bookings','influencer','status','amount_paid_total','bookings_total','balance_total'));
  }

  public function influencer_complete_bookings_report($id){
   $influencer = \App\Influencer::with('user')->find($id);
   $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','complete')->get();
   $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','complete')->sum('total_cost');
   $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','complete')->sum('amount_paid');
   $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','complete')->sum('balance');
   $status = "COMPLETE ";
   return view('backoffice.influencers.bookings_report',compact('bookings','influencer','status','amount_paid_total','bookings_total','balance_total'));
}

public function influencer_revoked_bookings_report($id){
   $influencer = \App\Influencer::with('user')->find($id);
   $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','revoked')->get();
   $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','revoked')->sum('total_cost');
   $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','revoked')->sum('amount_paid');
   $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','revoked')->sum('balance');
   $status = "REVOKED ";
   return view('backoffice.influencers.bookings_report',compact('bookings','influencer','status','amount_paid_total','bookings_total','balance_total'));
}

public function influencer_unserviced_bookings_report($id){
   $influencer = \App\Influencer::with('user')->find($id);
   $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','unserviced')->get();
   $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','unserviced')->sum('total_cost');
   $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','unserviced')->sum('amount_paid');
   $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','unserviced')->sum('balance');
   $status = "UNSERVICED ";
   return view('backoffice.influencers.bookings_report',compact('bookings','influencer','status','amount_paid_total','bookings_total','balance_total'));
}

public function influencer_overdue_bookings_report($id){
   $influencer = \App\Influencer::with('user')->find($id);
   $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','overdue')->get();
   $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','overdue')->sum('total_cost');
   $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','overdue')->sum('amount_paid');
   $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','overdue')->sum('balance');
   $status = "OVERDUE ";
   return view('backoffice.influencers.bookings_report',compact('bookings','influencer','status','amount_paid_total','bookings_total','balance_total'));
}

public function influencer_delivered_bookings_report($id){
   $influencer = \App\Influencer::with('user')->find($id);
   $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','agent-delivered')->get();
   $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','agent-delivered')->sum('total_cost');
   $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','agent-delivered')->sum('amount_paid');
   $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','agent-delivered')->sum('balance');
   $status = "DELIVERED ";
   return view('backoffice.influencers.bookings_report',compact('bookings','influencer','status','amount_paid_total','bookings_total','balance_total'));
}

public function influencer_confirmed_deliveries_report($id){
   $influencer = \App\Influencer::with('user')->find($id);
   $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','delivered')->get();
   $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','delivered')->sum('total_cost');
   $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','delivered')->sum('amount_paid');
   $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','=', $influencer->code)->where('status','=','delivered')->sum('balance');
   $status = "CONFIRMED DELIVERIES ";
   return view('backoffice.influencers.bookings_report',compact('bookings','influencer','status','amount_paid_total','bookings_total','balance_total'));
}


    public function vendor_active_bookings_report(Request $request,$id){
      if ($request->type=='branch') {
        # code...
        $vendor = \App\Vendor::with('user')->find($id)->main_vendor_code;
        $vendor=\App\Vendor::with('user')->whereVendor_code($vendor)->first();
      }
      else{
        $vendor = \App\Vendor::with('user')->find($id);
      }
         
         $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','active')->get();
         $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','active')->sum('total_cost');
         $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','active')->sum('amount_paid');
         $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','active')->sum('balance');
         $status = "ACTIVE ";
         return view('backoffice.vendors.bookings_report',compact('bookings','vendor','status','amount_paid_total','bookings_total','balance_total'));
    }

    public function vendor_pending_bookings_report(Request $request,$id){
       if ($request->type=='branch') {
        # code...
        $vendor = \App\Vendor::with('user')->find($id)->main_vendor_code;
        $vendor=\App\Vendor::with('user')->whereVendor_code($vendor)->first();
      }
      else{
        $vendor = \App\Vendor::with('user')->find($id);
      }
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','pending')->get();
        $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','pending')->sum('total_cost');
        $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','pending')->sum('amount_paid');
        $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','pending')->sum('balance');
        $status = "PENDING ";
        return view('backoffice.vendors.bookings_report',compact('bookings','vendor','status','amount_paid_total','bookings_total','balance_total'));
   }

   public function vendor_complete_bookings_report(Request $request,$id){
        if ($request->type=='branch') {
        # code...
        $vendor = \App\Vendor::with('user')->find($id)->main_vendor_code;
        $vendor=\App\Vendor::with('user')->whereVendor_code($vendor)->first();
      }
      else{
        $vendor = \App\Vendor::with('user')->find($id);
      }
    $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','complete')->get();
    $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','complete')->sum('total_cost');
    $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','complete')->sum('amount_paid');
    $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','complete')->sum('balance');
    $status = "COMPLETE ";
    return view('backoffice.vendors.bookings_report',compact('bookings','vendor','status','amount_paid_total','bookings_total','balance_total'));
}

public function vendor_revoked_bookings_report(Request $request,$id){
     if ($request->type=='branch') {
        # code...
        $vendor = \App\Vendor::with('user')->find($id)->main_vendor_code;
        $vendor=\App\Vendor::with('user')->whereVendor_code($vendor)->first();
      }
      else{
        $vendor = \App\Vendor::with('user')->find($id);
      }
    $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','revoked')->get();
    $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','revoked')->sum('total_cost');
    $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','revoked')->sum('amount_paid');
    $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','revoked')->sum('balance');
    $status = "REVOKED ";
    return view('backoffice.vendors.bookings_report',compact('bookings','vendor','status','amount_paid_total','bookings_total','balance_total'));
}

public function vendor_unserviced_bookings_report(Request $request,$id){
        if ($request->type=='branch') {
        # code...
        $vendor = \App\Vendor::with('user')->find($id)->main_vendor_code;
        $vendor=\App\Vendor::with('user')->whereVendor_code($vendor)->first();
      }
      else{
        $vendor = \App\Vendor::with('user')->find($id);
      }
    $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','unserviced')->get();
    $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','unserviced')->sum('total_cost');
    $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','unserviced')->sum('amount_paid');
    $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','unserviced')->sum('balance');
    $status = "UNSERVICED ";
    return view('backoffice.vendors.bookings_report',compact('bookings','vendor','status','amount_paid_total','bookings_total','balance_total'));
}

public function vendor_overdue_bookings_report(Request $request,$id){
    if ($request->type=='branch') {
        # code...
        $vendor = \App\Vendor::with('user')->find($id)->main_vendor_code;
        $vendor=\App\Vendor::with('user')->whereVendor_code($vendor)->first();
      }
      else{
        $vendor = \App\Vendor::with('user')->find($id);
      }
    $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','overdue')->get();
    $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','overdue')->sum('total_cost');
    $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','overdue')->sum('amount_paid');
    $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','overdue')->sum('balance');
    $status = "OVERDUE ";
    return view('backoffice.vendors.bookings_report',compact('bookings','vendor','status','amount_paid_total','bookings_total','balance_total'));
}

public function vendor_delivered_bookings_report(Request $request,$id){
    if ($request->type=='branch') {
        # code...
        $vendor = \App\Vendor::with('user')->find($id)->main_vendor_code;
        $vendor=\App\Vendor::with('user')->whereVendor_code($vendor)->first();
      }
      else{
        $vendor = \App\Vendor::with('user')->find($id);
      }
    $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','agent-delivered')->get();
    $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','agent-delivered')->sum('total_cost');
    $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','agent-delivered')->sum('amount_paid');
    $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','agent-delivered')->sum('balance');
    $status = "DELIVERED ";
    return view('backoffice.vendors.bookings_report',compact('bookings','vendor','status','amount_paid_total','bookings_total','balance_total'));
}

public function vendor_confirmed_deliveries_report(Request $request,$id){
    if ($request->type=='branch') {
        # code...
        $vendor = \App\Vendor::with('user')->find($id)->main_vendor_code;
        $vendor=\App\Vendor::with('user')->whereVendor_code($vendor)->first();
      }
      else{
        $vendor = \App\Vendor::with('user')->find($id);
      }
    $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','delivered')->get();
    $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','delivered')->sum('total_cost');
    $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','delivered')->sum('amount_paid');
    $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code','=', $vendor->vendor_code)->where('status','=','delivered')->sum('balance');
    $status = "CONFIRMED DELIVERIES ";
    return view('backoffice.vendors.bookings_report',compact('bookings','vendor','status','amount_paid_total','bookings_total','balance_total'));
}

public function agent_products_report($id){
    $agent = \App\Agents::with('user')->find($id);
     $products = \App\Products::with('category','agent','agent.user')->where('agent_id','=', $id)->get();
     $price_total =  \App\Products::with('category','agent','agent.user')->where('agent_id','=', $id)->sum('product_price');
     $quantity_total =  \App\Products::with('category','agent','agent.user')->where('agent_id','=', $id)->sum('quantity');
     return view('backoffice.agents.products_report',compact('products','agent','price_total','quantity_total'));
    }


    public function agent_active_bookings_report($id){
         $agent = \App\Agents::with('user')->find($id);
         $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','active')->get();
         $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','active')->sum('total_cost');
         $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','active')->sum('amount_paid');
         $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','active')->sum('balance');
         $status = "ACTIVE ";
         return view('backoffice.agents.bookings_report',compact('bookings','agent','status','amount_paid_total','bookings_total','balance_total'));
    }

    public function agent_pending_bookings_report($id){
        $agent = \App\Agents::with('user')->find($id);
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','pending')->get();
        $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','pending')->sum('total_cost');
        $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','pending')->sum('amount_paid');
        $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','pending')->sum('balance');
        $status = "PENDING ";
        return view('backoffice.agents.bookings_report',compact('bookings','agent','status','amount_paid_total','bookings_total','balance_total'));
   }

   public function agent_complete_bookings_report($id){
    $agent = \App\Agents::with('user')->find($id);
    $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','complete')->get();
    $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','complete')->sum('total_cost');
    $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','complete')->sum('amount_paid');
    $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','complete')->sum('balance');
    $status = "COMPLETE ";
    return view('backoffice.agents.bookings_report',compact('bookings','agent','status','amount_paid_total','bookings_total','balance_total'));
}

public function agent_revoked_bookings_report($id){
    $agent = \App\Agents::with('user')->find($id);
    $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','revoked')->get();
    $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','revoked')->sum('total_cost');
    $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','revoked')->sum('amount_paid');
    $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','revoked')->sum('balance');
    $status = "REVOKED ";
    return view('backoffice.agents.bookings_report',compact('bookings','agent','status','amount_paid_total','bookings_total','balance_total'));
}

public function agent_unserviced_bookings_report($id){
    $agent = \App\Agents::with('user')->find($id);
    $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','unserviced')->get();
    $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','unserviced')->sum('total_cost');
    $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','unserviced')->sum('amount_paid');
    $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','unserviced')->sum('balance');
    $status = "UNSERVICED ";
    return view('backoffice.agents.bookings_report',compact('bookings','agent','status','amount_paid_total','bookings_total','balance_total'));
}

public function agent_overdue_bookings_report($id){
    $agent = \App\Agents::with('user')->find($id);
    $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','overdue')->get();
    $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','overdue')->sum('total_cost');
    $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','overdue')->sum('amount_paid');
    $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','overdue')->sum('balance');
    $status = "OVERDUE ";
    return view('backoffice.agents.bookings_report',compact('bookings','agent','status','amount_paid_total','bookings_total','balance_total'));
}

public function agent_delivered_bookings_report($id){
    $agent = \App\Agents::with('user')->find($id);
    $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','agent-delivered')->get();
    $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','agent-delivered')->sum('total_cost');
    $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','agent-delivered')->sum('amount_paid');
    $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','agent-delivered')->sum('balance');
    $status = "DELIVERED ";
    return view('backoffice.agents.bookings_report',compact('bookings','agent','status','amount_paid_total','bookings_total','balance_total'));
}

public function agent_confirmed_deliveries_report($id){
    $agent = \App\Agents::with('user')->find($id);
    $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','delivered')->get();
    $bookings_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','delivered')->sum('total_cost');
    $amount_paid_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','delivered')->sum('amount_paid');
    $balance_total =  \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=', $agent->agent_code)->where('status','=','delivered')->sum('balance');
    $status = "CONFIRMED DELIVERIES ";
    return view('backoffice.agents.bookings_report',compact('bookings','agent','status','amount_paid_total','bookings_total','balance_total'));
}


}

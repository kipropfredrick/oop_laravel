@extends('backoffice.app')

@section('content')

<div class="card p-3">
     @if (session()->has('success'))

                <div class="alert alert-success fade show" role="alert">
                    {{ session()->get('success') }}
                </div>

                @elseif (session()->has('error'))

                <div class="alert alert-danger fade show" role="alert">
                    {{ session()->get('error') }}
                </div>

            @endif
@if($vendor->category==0) 

<h6>
    General Categories Management
</h6>
<div class="container">
 <div class="row">
     <div class="col-md-4 col-lg-4 col-sm-12">
          <strong>Name:</strong>  <p>{{$vendor->user->name}}</p>
     </div>
       <div class="col-md-4 col-lg-4 col-sm-12">
          <strong>Business:</strong>  <p>{{$vendor->business_name}}</p>
     </div>
       <div class="col-md-4 col-lg-4 col-sm-12">
          <strong>Category type:</strong>  <p>@if($vendor->category==0) General @else Specific @endif</p>
     </div>
 </div>
</div>
<form method="POST" action="{{route('admin.vendor.update_vendor',$vendor->id)}}">
    {{csrf_field()}}
        <input type="text" value="g_commissionrate" name="type" hidden="">
      @if($vendor->commssionrate_enabled==1)

               <div class="row" id="commissionrate">

               <div class="col-md-6">

               <div class="form-group">
                <label>Commission rate</label>
                    <input required="" value="{{ old('commission_rate') }}" tclass="form-control" name="commission_rate" placeholder="E.g. 5%" type="number" class="form-control @if($errors->has('commission_rate')) invalid_field @endif"  step=".01">
                
                    @error('commission_rate')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                    @enderror
                </div>
               
               </div>

                <div class="col-md-6">
                <div class="form-group">
                    <label>Commission cap</label>
                        <input required=""  value="{{ old('commission_cap') }}"  class="form-control" name="commission_cap" placeholder="E.g. KSh.5000" type="number" class="form-control @if($errors->has('commission_cap')) invalid_field @endif" >
                    
                        @error('commission_cap')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                        @enderror
                </div>
                </div>
               
               </div>
@else

                       <div class="row" id="fixed">

               <div class="col-md-6">

               <div class="form-group">
                <label>Mobile Money</label>
                    <input required=""  value="{{ old('fixed_mobile_money') }}" tclass="form-control" name="fixed_mobile_money" placeholder="E.g. KSh.50" type="number" class="form-control @if($errors->has('fixed_mobile_money')) invalid_field @endif"  step="1">
                
                    @error('fixed_mobile_money')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                    @enderror
                </div>
               
               </div>

                <div class="col-md-6">
                <div class="form-group">
                    <label>Bank/Card Payments</label>
                        <input required="" value="{{ old('fixed_bank') }}"  class="form-control" name="fixed_bank" placeholder="E.g. KSh.100" type="number" class="form-control @if($errors->has('fixed_bank')) invalid_field @endif" >
                    
                        @error('fixed_bank')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                        @enderror
                </div>
                </div>
               
               </div>
@endif
               <button class="btn btn-success">Submit</button>
    
</form>


@endif   
@if($vendor->category==1) 

<h6>
    Specific Categories Management
</h6>
<div class="container">
 <div class="row">
     <div class="col-md-4 col-lg-4 col-sm-12">
          <strong>Name:</strong>  <p>{{$vendor->user->name}}</p>
     </div>
       <div class="col-md-4 col-lg-4 col-sm-12">
          <strong>Business:</strong>  <p>{{$vendor->business_name}}</p>
     </div>
       <div class="col-md-4 col-lg-4 col-sm-12">
          <strong>Category type:</strong>  <p>@if($vendor->category==0) General @else Specific @endif</p>
     </div>
 </div>
</div>
<form method="POST" action="{{route('admin.vendor.update_vendor',$vendor->id)}}">

    {{csrf_field()}}
    <div >
        <select class="form-control" name="subcategory" required="">
            <option disabled >Select Sucategory</option>
            @foreach($subcats as $value)
 <option value="{{$value->id}}">{{$value->subcategory_name}}</option>
            @endforeach
        </select>
    </div>
  
            @if($vendor->commssionrate_enabled==1)
              <input type="text" value="g_sub_rate" name="type" hidden="">
               <div class="row" id="commissionrate">

               <div class="col-md-6">

               <div class="form-group">
                <label>Commission rate</label>
                    <input required="" value="{{ old('commission_rate') }}" tclass="form-control" name="commission_rate" placeholder="E.g. 5%" type="number" class="form-control @if($errors->has('commission_rate')) invalid_field @endif"  step=".01">
                
                    @error('commission_rate')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                    @enderror
                </div>
               
               </div>

                <div class="col-md-6">
                <div class="form-group">
                    <label>Commission cap</label>
                        <input required=""  value="{{ old('commission_cap') }}"  class="form-control" name="commission_cap" placeholder="E.g. KSh.5000" type="number" class="form-control @if($errors->has('commission_cap')) invalid_field @endif" >
                    
                        @error('commission_cap')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                        @enderror
                </div>
                </div>
               
               </div>
               @else


 <input type="text" value="g_sub_fixed" name="type" hidden="">
                       <div class="row" id="fixed">

               <div class="col-md-6">

               <div class="form-group">
                <label>Mobile Money</label>
                    <input required=""  value="{{ old('fixed_mobile_money') }}" tclass="form-control" name="fixed_mobile_money" placeholder="E.g. KSh.50" type="number" class="form-control @if($errors->has('fixed_mobile_money')) invalid_field @endif"  step="1">
                
                    @error('fixed_mobile_money')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                    @enderror
                </div>
               
               </div>

                <div class="col-md-6">
                <div class="form-group">
                    <label>Bank/Card Payments</label>
                        <input required="" value="{{ old('fixed_bank') }}"  class="form-control" name="fixed_bank" placeholder="E.g. KSh.100" type="number" class="form-control @if($errors->has('fixed_bank')) invalid_field @endif" >
                    
                        @error('fixed_bank')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                        @enderror
                </div>
                </div>
               
               </div>
               @endif

               <button class="btn btn-success">Submit</button>
    
</form>  @if($vendor->commssionrate_enabled==1)
<div >
    <table class="table table-striped">
        <th>Subcategory Name</th>
        <th>Commission rate</th>
        <th>Commission cap</th>

        <tbody>
            @foreach($subcats as $value)
<tr>
    <td>
    {{$value->subcategory_name}}
    </td>
        <td>
        {{$value->commission_rate}}
    </td>
    <td>
        {{$value->commission_cap}}
    </td>


</tr>
            @endforeach

        </tbody>
    </table>
</div>
@else
<div >
    <table class="table table-striped">
        <th>Subcategory Name</th>

        <th>Fixed Mobile Money</th>
                <th>Fixed Bank</th>

        <tbody>
            @foreach($subcats as $value)
<tr>
    <td>
    {{$value->subcategory_name}}
    </td>
        
    <td>
        {{$value->fixed_mobile_money}}
    </td>
    <td>
        {{$value->fixed_bank}}
    </td>


</tr>
            @endforeach

        </tbody>
    </table>
</div>
@endif
@endif   

</div>
@endsection

@extends('layouts.master')
@section('title','Add Pending Customers')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('pending_customers')}}">Pending Customer</a></li>
                    <li class="active"><span>Add Pending Customer </span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form id="onenter_prevent" method="POST" action="{{url('add_pending_customers/'.$customer->id)}}" accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            @if (count($errors) > 0)
                            <div role="alert" class="alert alert-warning">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if(Session::has('success'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('success') }} </strong>
                            </div>
                            @endif
                            @if(Session::has('error'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('error') }} </strong>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="owner_name">Owner Name<span class="mandatory">*</span></label>
                                <input id="owner_name" class="form-control" placeholder="Owner Name" name="owner_name" value="{{$customer->owner_name}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="company_name">Company  Name</label>
                                <input id="company_name" class="form-control" placeholder="Company Name" name="company_name" value="{{ $customer->company_name}}" type="text">
                            </div>    
                            <div class="form-group">
                                <label for="contact_person">Contact Person</label>
                                <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{$customer->contact_person}}" type="text">
                            </div>  
                            <div class="form-group">
                                <label for="address1">Address 1</label>
                                <input id="address1" class="form-control" placeholder="Address 1" name="address1" value="{{ $customer->address1}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="address2">Address 2</label>
                                <input id="address2" class="form-control" placeholder="Address 2" name="address2" value="{{ $customer->address2}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="state">State<span class="mandatory">*</span></label>
                                <select class="form-control" id="state" name="state" onchange="state_option()">
                                    <option value="" selected="" disabled="">--Select State--</option>
                                    @foreach($states as $state)
                                    @if(Input::old('state')!='' && Input::old('state')==$state->id)
                                    <option selected="" value="{{$state->id}}">{{$state->state_name}}</option>  
                                    @else
                                    <option value="{{$state->id}}">{{$state->state_name}}</option>  
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city">City<span class="mandatory">*</span></label>
                                <select class="form-control" id="city"  name="city">
                                    <option value="" disabled="">--Select City--</option>
                                    @foreach($cities as $city)
                                    @if(Input::old('city')!='' && Input::old('city')==$city->id)
                                    <option selected="" value="{{$city->id}}">{{$city->city_name}}</option>                                    
                                    @else
                                    <option value="{{$city->id}}">{{$city->city_name}}</option>                                    
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <!--                            <div class="form-group">
                                                            <label for="state">State<span class="mandatory">*</span></label>
                                                            <input id="state" class="form-control" placeholder="State" name="state" value="{{$customer->state}}" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="city">City<span class="mandatory">*</span></label>
                                                            <input id="city" class="form-control" placeholder="City" name="city" value="{{ $customer->city}}" type="text">
                                                        </div>-->
                            <div class="form-group">
                                <label for="zip">Zip</label>
                                <input id="zip" class="form-control" placeholder="Zip" name="zip" value="{{$customer->zip}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="email">Email<span class="mandatory">*</span></label>
                                <input id="email" class="form-control" placeholder="Email" name="email" value="{{$customer->email}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="tally_name">Tally Name<span class="mandatory">*</span></label>
                                <input id="tally_name" class="form-control" placeholder="Tally Name " name="tally_name" value="{{ $customer->tally_name}}" type="text">
                            </div> 
                            <!--                            <div class="form-group">
                                                            <label for="tally_category">Tally Category<span class="mandatory">*</span></label>
                                                            <input id="tally_category" class="form-control" placeholder="Tally Category " name="tally_category" value="{{$customer->tally_category}}" type="text">
                                                        </div> 
                                                        <div class="form-group">
                                                            <label for="tally_sub_category">Tally Subcategory<span class="mandatory">*</span></label>
                                                            <input id="tally_sub_category" class="form-control" placeholder="Tally Subcategory " name="tally_sub_category" value="{{$customer->tally_sub_category}}" type="text">
                                                        </div> -->
                            <div class="form-group">
                                <label for="phone_number1">Phone number 1<span class="mandatory">*</span></label>
                                <input id="phone_number1" class="form-control" placeholder="Phone number " name="phone_number1" value="{{$customer->phone_number1}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="phone_number2">Phone Number 2</label>
                                <input id="phone_number2" class="form-control" placeholder="Phone Number 2" name="phone_number2" value="{{$customer->phone_number2}}" type="text">
                            </div>
                            <!--                            <div class="form-group">
                                                            <label for="vat_tin_number">VAT-TIN Number</label>
                                                            <input id="vat_tin_number" class="form-control" placeholder="VAT-TIN Number" name="vat_tin_number" value="{{$customer->vat_tin_number}}" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="excise_number">Excise Number</label>
                                                            <input id="excise_number" class="form-control" placeholder="Excise Number" name="excise_number" value="{{$customer->excise_number}}" type="text">
                                                        </div>-->
                            <div class="form-group col-md-4 del_loc ">
                                <label for="delivery_location">Delivery Location:<span class="mandatory">*</span></label>
                                <select class="form-control" id="delivery_location" name="delivery_location">
                                    <option value="">Select Delivery Location</option>
                                    @foreach($locations as $l)
                                    @if($l->id == $customer->delivery_location_id || Input::old('delivery_location')!='' && Input::old('delivery_location')==$l->id)
                                    <option value="{{$l->id}}" selected="selected">{{$l->area_name}}</option>                                    
                                    @else
                                    <option value="{{$l->id}}">{{$l->area_name}}</option>                                    
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="username">User Name</label>
                                <input id="username" class="form-control" placeholder="User Name" name="username" value="{{$customer->username}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input id="password" class="form-control" placeholder=" Password" name="password" value="" type="password">
                            </div> 
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input id="confirm_password" class="form-control" placeholder="Confirm Password" name="confirm_password" value="" type="password">
                            </div>
                            <div class="form-group">
                                <label for="credit_period">Credit Period(Days)</label>
                                <input id="credit_period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{$customer->credit_period}}" type="text">
                            </div>
                            <div class="form-group col-md-4 del_loc ">
                                <label for="relationship_manager">Relationship Manager:</label>
                                <select class="form-control" id="relationship_manager" name="relationship_manager">
                                    <option value="" >Select Relation Manager</option>
                                    @foreach($managers as $m)
                                    @if($m->id == $customer->relationship_manager || Input::old('relationship_manager')!='' && Input::old('relationship_manager')==$m->id)
                                    <option value="{{$m->id}}" selected="selected">{{$m->first_name}} {{$m->last_name}}</option>
                                    @else
                                    <option value="{{$m->id}}">{{$m->first_name}} {{$m->last_name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label>Set Prices</label>
                                <br>
                                <div class="checkbox-nice">
                                    <input id="checkbox-inl-1" type="checkbox">
                                    <label for="checkbox-inl-1"> </label>
                                </div>
                                <br>
                                <div class="category_div col-md-12">
                                    <div class="table-responsive">
                                        <table id="table-example" class="table table-hover  ">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Difference</th>

                                                </tr>
                                            </thead>
                                            <tbody>       
                                                @foreach($product_category as $pc)
                                                <tr>
                                                    <td>{{$pc->product_category_name}}</td>
                                                    <td>
                                                        <input class="setprice" type="text" name="product_differrence[]">
                                                        <input type="hidden" name="product_category_id[]" value="{{$pc->id}}">
                                                    </td>                                            
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                            <div>
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url('pending_customers')}}" class="btn btn-default form_button_footer">Back</a>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
@extends('layouts.master')
@section('title','Add Customer')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('customers')}}">Customers</a></li>
                    <li class="active"><span>Add Customer</span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form id="onenter_prevent" method="POST" action="{{url('customers')}}" accept-charset="UTF-8" >
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
                                <input id="owner_name" class="form-control" placeholder="Owner Name" name="owner_name" value="{{ Input::old('owner_name')}}" type="text"  pattern="\d*">
                            </div>
                            <div class="form-group">
                                <label for="company_name">Company  Name</label>
                                <input id="company_name" class="form-control" placeholder="Company Name" name="company_name" value="{{ Input::old('company_name')}}" type="text">
                            </div>    
                            <div class="form-group">
                                <label for="contact_person">Contact Person</label>
                                <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{ Input::old('contact_person')}}" type="text">
                            </div>  
                            <div class="form-group">
                                <label for="address1">Address 1</label>
                                <input id="address1" class="form-control" placeholder="Address 1" name="address1" value="{{ Input::old('address1')}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="address2">Address 2</label>
                                <input id="address2" class="form-control" placeholder="Address 2" name="address2" value="{{ Input::old('address2')}}" type="text">
                            </div>
                            <!--                            <div class="form-group">
                                                            <label for="city">City<span class="mandatory">*</span></label>
                                                            <input id="city" class="form-control" placeholder="City" name="city" value="{{ Input::old('city')}}" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="state">State<span class="mandatory">*</span></label>
                                                            <input id="state" class="form-control" placeholder="State" name="state" value="{{ Input::old('state')}}" type="text">
                                                        </div>-->
                            <div class="form-group">
                                
                                <label for="state">State<span class="mandatory">*</span></label>
                                <select class="form-control" id="state" name="state" onchange="state_option()" >
                                    <option value="" >Select State</option>
                                    @foreach($states as $state)
                                    @if(Input::old('state')!='' && Input::old('state')==$state->id)
                                    <option  selected="" value="{{$state->id}}">{{$state->state_name}}</option>
                                    @else
                                    <option value="{{$state->id}}">{{$state->state_name}}</option>                                    
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city">City<span class="mandatory">*</span></label>
                                <select class="form-control" id="city"  name="city">
                                    <option value="">--Select City--</option>
                                    @foreach($cities as $city)
                                    @if(Input::old('city')!='' && Input::old('city')==$city->id)
                                    <option selected="" value="{{$city->id}}">{{$city->city_name}}</option> 
                                    @else
                                    <option value="{{$city->id}}">{{$city->city_name}}</option>                                    
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="zip">Zip</label>
                                <input id="zip" class="form-control" placeholder="Zip" name="zip" value="{{ Input::old('zip')}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="email">Email<span class="mandatory">*</span></label>
                                <input id="email" class="form-control" placeholder="Email" name="email" value="{{ Input::old('email')}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="tally_name">Tally Name<span class="mandatory">*</span></label>
                                <input id="tally_name" class="form-control" placeholder="Tally Name " name="tally_name" value="{{ Input::old('tally_name')}}" type="text">
                            </div> 
                            <!--                            <div class="form-group">
                                                            <label for="tally_category">Tally Category<span class="mandatory">*</span></label>
                                                            <input id="tally_category" class="form-control" placeholder="Tally Category " name="tally_category" value="{{ Input::old('tally_category')}}" type="text">
                                                        </div> 
                                                        <div class="form-group">
                                                            <label for="tally_sub_category">Tally Subcategory<span class="mandatory">*</span></label>
                                                            <input id="tally_sub_category" class="form-control" placeholder="Tally Subcategory " name="tally_sub_category" value="{{ Input::old('tally_sub_category')}}" type="text">
                                                        </div> -->
                            <div class="form-group">
                                <label for="phone_number1">Phone number 1<span class="mandatory">*</span></label>
                                <input id="phone_number1" class="form-control" placeholder="Phone number " name="phone_number1" value="{{ Input::old('phone_number1')}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="phone_number2">Phone Number 2</label>
                                <input id="phone_number2" class="form-control" placeholder="Phone Number 2" name="phone_number2" value="{{ Input::old('phone_number2')}}" type="text">
                            </div>
                            <!--                            <div class="form-group">
                                                            <label for="vat_tin_number">VAT-TIN Number</label>
                                                            <input id="vat_tin_number" class="form-control" placeholder="VAT-TIN Number" name="vat_tin_number" value="{{ Input::old('vat_tin_number')}}" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="excise_number">Excise Number</label>
                                                            <input id="excise_number" class="form-control" placeholder="Excise Number" name="excise_number" value="{{ Input::old('excise_number')}}" type="text">
                                                        </div>-->
                            <div class="form-group col-md-4 del_loc ">
                                <label for="delivery_location">Delivery Location:<span class="mandatory">*</span></label>
                                <select class="form-control" id="delivery_location" name="delivery_location">
                                    <option value="" selected="">Select Delivery Location</option>
                                    @foreach($locations as $l)
                                    @if(Input::old('delivery_location')!='' && Input::old('delivery_location')==$l->id)
                                    <option selected="" value="{{$l->id}}">{{$l->area_name}}</option> 
                                    @else
                                    <option value="{{$l->id}}">{{$l->area_name}}</option>                                    
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="username">User Name</label>
                                <input id="username" class="form-control" placeholder="User Name" name="username" value="{{ Input::old('username')}}" type="text">
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
                                <input id="credit_period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{ Input::old('credit_period')}}" type="text">
                            </div>
                            <div class="form-group col-md-4 del_loc ">
                                <label for="relationship_manager">Relationship Manager:</label>
                                <select class="form-control" id="relationship_manager" name="relationship_manager">
                                    <option value="" >Select Relation Manager</option>
                                    @foreach($managers as $m)
                                    @if(Input::old('relationship_manager')!='' && Input::old('relationship_manager')==$m->id)
                                    <option selected="" value="{{$m->id}}">{{$m->first_name}} {{$m->last_name}}</option>
                                    @else
                                    <option value="{{$m->id}}">{{$m->first_name}} {{$m->last_name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="clearfix"></div>
<!--                            <div class="form-group">
                                <label>Set Prices</label>
                                <br>
                                <div class="checkbox-nice">
                                    <input id="checkbox-inl-1" type="checkbox">
                                    <label for="checkbox-inl-1"> </label>
                                </div>
                                <br>
                                @if(count($product_category) > 0)
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
                                                    <td><input class="setprice" type="text" name="product_differrence[]"></td>
                                            <input type="hidden" name="product_category_id[]" value="{{$pc->id}}">
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                               @else
                                <p class="text-info">No product category found</p>
                                @endif
                            </div>-->
                            <div class="clearfix"></div>
                            <hr>
                            <div>
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url('customers')}}" class="btn btn-default form_button_footer">Back</a>
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
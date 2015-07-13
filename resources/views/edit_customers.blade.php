@extends('layouts.master')
@section('title','Edit Customer')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('customers')}}">Customers</a></li>
                    <li class="active"><span>Edit Customer</span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form method="POST" action="{{url('customers/'.$customer->id)}}" accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input name="_method" type="hidden" value="PUT">
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
                                    <option value="" selected="">Select State</option>
                                    @foreach($states as $state)
                                    @if($state->id == $customer->state)
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
                                    <option value="" selected="">Select City</option>
                                    @foreach($cities as $city)
                                    @if($customer->city == $city->id)
                                    <option selected="" value="{{$city->id}}">{{$city->city_name}}</option>                                    
                                    @else
                                    <option value="{{$city->id}}">{{$city->city_name}}</option>                                    
                                    @endif
                                    @endforeach
                                </select>
                            </div>
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

                            <div class="form-group">
                                <label for="phone_number1">Phone number 1<span class="mandatory">*</span></label>
                                <input id="phone_number1" class="form-control" placeholder="Phone number " name="phone_number1" value="{{$customer->phone_number1}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="phone_number2">Phone Number 2</label>
                                <input id="phone_number2" class="form-control" placeholder="Phone Number 2" name="phone_number2" value="{{$customer->phone_number2}}" type="text">
                            </div>

                            <div class="form-group col-md-4 del_loc ">
                                <label for="delivery_location">Delivery Location:<span class="mandatory">*</span></label>
                                <select class="form-control" id="delivery_location" name="delivery_location">
                                    <option value="" selected="">Select Delivery Location</option>
                                    @foreach($locations as $l)
                                    @if($l->id == $customer->delivery_location_id)
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
                                    <option value="" selected="">Select Relation Manager</option>
                                    @foreach($managers as $m)
                                    @if($m->id == $customer->relationship_manager)
                                    <option value="{{$m->id}}" selected="selected">{{$m->first_name}} {{$m->last_name}}</option>
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
                                    <input id="checkbox-inl-1" type="checkbox" <?php if(sizeof($customer['customerproduct']) > 0) echo 'checked=""';?>>
                                    <label for="checkbox-inl-1"> </label>
                                </div>
                                <br>

                                @if(count($product_category) > 0)
                                <div class="category_div col-md-12" <?php if(sizeof($customer['customerproduct']) > 0) echo 'style="display:block;"';?>>
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
                                                        <?php
                                                        $price = '';
                                                        foreach ($customer['customerproduct'] as $key => $value) {
                                                            if ($pc->id == $value->product_category_id) {
                                                                $price = $value->difference_amount;
                                                            }
                                                        }
                                                        ?>
                                                        <input class="setprice" type="text" name="product_differrence[]" value="<?= $price ?>">
                                                        <?php ?>
                                                        <input type="hidden" name="product_category_id[]" value="{{$pc->id}}">
                                                    </td>
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
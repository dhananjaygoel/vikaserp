@extends('layouts.master')
@section('title','Bulk Set Price')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('customers')}}">Customers</a></li>
                    <li class="active"><span>Bulk Set Price</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">Bulk Set Price</h1> 

                    <form method="GET" id="searchCustomerForm" action="{{URL::action('CustomerController@bulk_set_price')}}">
                        <div class="form-group  col-md-3  pull-right">
                            <select class="form-control" name="product_filter" onchange="this.form.submit()">
                                <option value="" selected="">--Product category--</option>
                                @foreach($product_type as $prod_type)
                                <option <?php if (Input::get('product_filter') == $prod_type->id) echo 'selected="selected"'; ?> value="{{$prod_type->id}}"> {{$prod_type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        @if(Session::has('success'))
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong> {{ Session::get('success') }} </strong>
                        </div>
                        @endif
                        <form id="onenter_prevent" method="POST" action="{{URL::action('CustomerController@save_all_set_price')}}">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="table-responsive">
                                <table id="table-example" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>Customer</td>
                                            @foreach($product_category as $prod)
                                            <td>{{$prod->product_category_name}}</td>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = ($customer->currentPage() - 1) * $customer->perPage() + 1;
                                        $product_cat_count = sizeof($product_category);
                                        ?>
                                        @foreach($customer as $key => $c)
                                        <tr>                                            
                                            <td>{{$i++}}</td>
                                            <td>{{$c->owner_name}}</td>
                                            @foreach($product_category as $key1=>$prod)
                                            <?php
                                            $price = '';
                                            foreach ($c['customerproduct'] as $setprice) {
                                                if ($prod->id == $setprice->product_category_id && $c->id == $setprice->customer_id) {
                                                    $price = $setprice->difference_amount;
                                                }
                                            }
                                            ?>
                                            <td>
                                                <input type='number' name="set_diff[{{$key}}][{{$key1}}][price]" value="{{ $price }}" style="width: 40px;">
                                                <input type='hidden' name="set_diff[{{$key}}][{{$key1}}][cust_id]" value="{{$c->id}}" style="width: 40px;">
                                                <input type='hidden' name="set_diff[{{$key}}][{{$key1}}][product_id]" value="{{$prod->id}}" style="width: 40px;">
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div >
                                
                                @if(isset($_GET['page']) && Input::get('page') != '')
                                <input type="hidden" name="page" value="{{$_GET['page']}}">
                                @endif
                                
                                @if(isset($_GET['product_filter']) && Input::get('product_filter') != '')
                                <input type="hidden" name="product_filter" value="{{$_GET['product_filter']}}">
                                @endif

                                <input type="submit" class="btn btn-primary form_button_footer" value="Save All">
                                <a href="{{URL::to('customers')}}" class="btn btn-default form_button_footer">Back</a>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                        <div class="clearfix"></div>
                        <span class="pull-right">

                            <?php
                            if (isset($_GET['product_filter']) && Request::get('product_filter') != '') {
                                echo $customer->appends(array('product_filter' => Request::get('product_filter')))->render();
                            } else {
                                echo $customer->render();
                            }
                            ?>

                        </span>

                        <span class="clearfix"></span>
                        @if($customer->lastPage() > 1)
                        <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                            <form class="form-inline" method="GET" action="{{url('customers')}}" id="filter_search">
                                <div class="form-group">
                                    <label for="exampleInputName2"><b>Go To</b></label>
                                    &nbsp;
                                    <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                    &nbsp;
                                    <label for="exampleInputName2"><b>of {{ $customer->lastPage()}} </b></label>
                                    <a onclick="this.form.submit()"></a>
                                </div>
                            </form>
                        </span> 
                        @endif 

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
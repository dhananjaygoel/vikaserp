@extends('layouts.master')
@section('title','Set Price')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('customers')}}">Customers</a></li>
                    <li class="active"><span>Set Price</span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form method="POST" action="{{url('update_set_price')}}" accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input name="customer_id" type="hidden" value="{{$customer_id['id']}}">
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
                            <div class="clearfix"></div>
                            <div class="form-group">                               

                                @if(count($product_category) > 0)
                                <div class="category_div col-md-12" style="display:block">
                                    <div class="table-responsive">
                                        <table id="table-example" class="table table-hover">
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
                                                        foreach ($cutomer_difference as $key => $value) {
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
                            </div>
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
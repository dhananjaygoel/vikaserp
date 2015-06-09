@extends('layouts.master')
@section('title','Customers')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('customers')}}">Customers</a></li>
                    <li class="active"><span>View Customer</span></li>
                </ol>

                <div class="filter-block">
                    <h1 class="pull-left">View Customer</h1>                                 
                    <div class="pull-right top-page-ui">
                        <a href="{{url('customers/1/edit')}}" class="btn btn-primary pull-right">
                            Edit Customer
                        </a>
                    </div>


                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">

                        <div class="table-responsive">
                            <table id="table-example" class="table customerview_table">

                                <tbody>                    


                                    <tr>
                                        <td><span>Owner Name:</span> Owner1</td>

                                    </tr>
                                    <tr>
                                        <td><span>Company Name:</span> Company1</td>

                                    </tr>
                                    <tr>
                                        <td><span>Contact Person:</span> Lorem ipsum</td>

                                    </tr>
                                    <tr>
                                        <td><span>Address1: </span>Lorem Ipsum Dollar</td>

                                    </tr>
                                    <tr>
                                        <td><span>Address2: </span>Lorem Ipsum Dollar</td>

                                    </tr>
                                    <tr>
                                        <td class="col-md-4"><span>City:</span> Ipsum</td>


                                    </tr>
                                    <tr> <td><span>State:</span> Lorem </td></tr>
                                    <tr><td><span>Zip:</span> 302021</td></tr>
                                    <tr>
                                        <td><span>Email:</span> <a href="mailto:"/>Info@company.com</a></td>

                                    </tr>
                                    <tr>
                                        <td><span>Tally Name:</span> Tally1</td>

                                    </tr>
                                    <tr>
                                        <td><span>Tally Category:</span> Lorem</td>

                                    </tr>
                                    <tr>
                                        <td><span>Tally Subcategory:</span> Ipsum</td>

                                    </tr>
                                    <tr>
                                        <td><span>Phone Number1:</span> 123456789</td>


                                    </tr>
                                    <tr><td><span>Phone Number2:</span> 123456789</td></tr>
                                    <tr> <td><span>VAT-TIN Number:</span> 654321</td></tr>
                                    <tr><td><span>Excise Number:</span> 2345678</td></tr>
                                    <tr>
                                        <td><span>Delivery Location:</span> Ex-warehouse</td>

                                    </tr>
                                    <tr>
                                        <td><span>Username:</span> User1</td>

                                    </tr>
                                    <tr><td><span>Password:</span> password</td></tr>
                                    <tr>
                                        <td><span>Credit Period:</span> </td>

                                    </tr>
                                    <tr>
                                        <td><span>Relationship Manager:</span> Admin1</td>

                                    </tr>

                                </tbody>
                            </table>



                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
@extends('layouts.backend')

@section('content')
<div class="wrapper-page">

    <div class="page-title">
        <h1><i class="icon-grid"></i>
            Dashboard
        </h1>
    </div>
    @include('partials.flash')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card border-0">
                        <div class="card-body bg-dutch widget">
                            <div class="d-flex">
                                <div class="align-items-center">
                                    <h4 class="text-white">
                                        
                                    </h4>
                                    <h6 class="text-white">
                                        Today's Sale
                                    </h6>
                                </div>
                                <div class="ms-auto align-items-center">
                                    <span class="text-white widget-icon"><i class="icon-layers"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0">
                        <div class="card-body bg-jade widget">
                            <div class="d-flex">
                                <div class="align-items-center">
                                    <h4 class="text-white">
                                        
                                    </h4>
                                    <h6 class="text-white">
                                        
                                    </h6>
                                </div>
                                <div class="ms-auto align-items-center">
                                    <span class="text-white widget-icon"><i class="icon-doc"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0">
                        <div class="card-body bg-green widget">
                            <div class="d-flex">
                                <div class="align-items-center">
                                    <h4 class="text-white">
                                        
                                    </h4>
                                    <h6 class="text-white">
                                        
                                    </h6>
                                </div>
                                <div class="ms-auto align-items-center">
                                    <span class="text-white widget-icon"><i class="icon-wallet"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0">
                        <div class="card-body bg-blue widget">
                            <div class="d-flex">
                                <div class="align-items-center">
                                    <h4 class="text-white">
                                        
                                    </h4>
                                    <h6 class="text-white">
                                        Total Products
                                    </h6>
                                </div>
                                <div class="ms-auto align-items-center">
                                    <span class="text-white widget-icon"><i class="icon-bag"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card no-border">
                        <div class="content_wrapper">
                            <div class="table_banner clearfix">
                                <h5 class="table_banner_title">
                                    Sales Progress
                                </h5>
                            </div>
                            <div class="table_body text-center">
                                <canvas id="myChart" width="50" height="25"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 form-group">
                    <div class="card no-border no-bgc clearfix">
                        <div class="table_banner clearfix">
                            <h5 class="table_banner_title">
                                Quick Notes
                            </h5>
                            <h5 class="table_banner_title float-end"><i class="icon-notebook"></i></h5>
                        </div>
                        <div class="bg-white">
                            <div class="slimScrollNote">
                                <div class="todo-box-wrap">
                                    <ul class="todo-list">
                                        
                                        <li class="todo-item">
                                            
                                            <div class="checkbox checkbox-default">
                                                <input class="to-do" data-id="" data-value="0" type="checkbox" id="">
                                                <label for=""></label>
                                            </div>
                                            
                                            <div class="checkbox checkbox-default">
                                                <input class="to-do" data-id="" data-value="1" type="checkbox" id="" checked>
                                                <label for=""></label>
                                            </div>
                                            
                                        </li>
                                        <li>
                                            <hr class="light-grey-hr">
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="new-todo">
                            <form method="post" enctype="multipart/form-data" id="add_todo">
                                <div class="input-group">

                                    <input type="text" id="todo_data" name="todo_data" class="form-control" style="border: 1px solid #fff !IMPORTANT; width: 100% !IMPORTANT;" placeholder="Add New Tasks">
                                    <span class="input-group-btn">

                                        <input type="hidden" name="userid" id="userid" value="">

                                        <button type="submit" class="btn btn-success todo-submit"><i class="fa fa-plus"></i></button>
                                    </span>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!--Top 10 product-->
                <div class="col-md-6">
                    <div class="card no-border">
                        <div class="content_wrapper">
                            <div class="table_banner clearfix">
                                <h5 class="table_banner_title">
                                    Top 10 Product
                                </h5>
                                <h5 class="table_banner_title float-end"><i class="icon-handbag"></i></h5>
                            </div>
                            <style>
                                .table tr td {
                                    padding: 15px 10px !IMPORTANT;
                                }
                            </style>
                            <div class="table_body no-padding dash-table-widget" style="padding: 20px; margin-bottom: 0;">
                                <table class="table" style="margin-bottom: 0;">
                                    <!-- start head -->
                                    <thead>
                                        <tr>
                                            <th>
                                                Image
                                            </th>
                                            <th>
                                                Title
                                            </th>
                                            <th>
                                                Sold
                                            </th>
                                        </tr>
                                    </thead>
                                    <!-- end head -->
                                    <!-- start body -->
                                    <tbody>
                                        <!-- start rows -->
                                        
                                        <tr>
                                            <td><img alt="madpos" src="../../../public/assets/img/product/"> </td>
                                            <td>
                                                
                                            </td>
                                            <td>
                                                
                                            </td>
                                        </tr>
                                        
                                        <!-- end rows -->
                                    </tbody>
                                    <!-- end body -->
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Top 10 product end-->
                <!--Top 10 Customer start-->
                <div class="col-md-6">
                    <div class="card no-border">
                        <div class="content_wrapper">
                            <div class="table_banner clearfix">
                                <h5 class="table_banner_title">
                                    Top 10 Customer
                                </h5>
                                <h5 class="table_banner_title float-end"><i class="icon-people"></i></h5>
                            </div>
                            <style>
                                .table tr td {
                                    padding: 15px 10px !IMPORTANT;
                                }
                            </style>
                            <div class="table_body no-padding dash-table-widget" style="padding: 20px; margin-bottom: 0;">
                                <table class="table" style="margin-bottom: 0;">
                                    <!-- start head -->
                                    <thead>
                                        <tr>
                                            <th>
                                                Image
                                            </th>
                                            <th>
                                                Full Name
                                            </th>
                                            <th>
                                                Total
                                            </th>
                                        </tr>
                                    </thead>
                                    <!-- end head -->
                                    <!-- start body -->
                                    <tbody>
                                        <!-- start rows -->
                                        
                                        <tr>
                                            <td>
                                                
                                                <img alt="madpos" src="../../../public/assets/img/customer/">
                                                
                                                <img alt="madpos" src="../../../public/assets/img/user/default.jpg">
                                                
                                            </td>
                                            <td>
                                                
                                            </td>
                                            <td>
                                                
                                            </td>
                                        </tr>
                                        
                                        <!-- end rows -->
                                    </tbody>
                                    <!-- end body -->
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Top 10 Customer end-->
                <!--Todays expense start-->
                <div class="col-md-6">
                    <div class="card no-border">
                        <div class="content_wrapper">
                            <div class="table_banner clearfix">
                                <h5 class="table_banner_title">
                                    Today's Expense
                                </h5>
                                <h5 class="table_banner_title float-end"><i class="icon-calculator"></i></h5>
                            </div>
                            <style>
                                .table tr td {
                                    padding: 15px 10px !IMPORTANT;
                                }
                            </style>
                            <div class="table_body dash-table-widget no-padding" style="padding: 20px; margin-bottom: 0;">
                                <table class="table" style="margin-bottom: 0;">
                                    <!-- start head -->
                                    <thead>
                                        <tr>
                                            <th>
                                                Expense Name
                                            </th>
                                            <th>
                                                Expense By
                                            </th>
                                            <th>
                                                Amount
                                            </th>
                                        </tr>
                                    </thead>
                                    <!-- end head -->
                                    <!-- start body -->
                                    <tbody>
                                        <!-- start rows -->
                                        
                                        <tr>
                                            <td class="text-primary">
                                                
                                            </td>
                                            <td>
                                                
                                            </td>
                                            <td>
                                                
                                            </td>
                                        </tr>
                                        
                                        <!-- end rows -->
                                    </tbody>
                                    <!-- end body -->
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Todays expense end-->
            </div>
        </div>
    </div>
    <!-- /.page-content  -->
</div>
@endsection

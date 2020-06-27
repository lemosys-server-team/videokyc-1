@extends('admin.layouts.valex_app')
@section('content')
<!-- Page Heading -->
<div class="container"> 
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
              <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Schedules</h2>
            </div>
        </div>
    </div>
     <div class="row row-sm">
        <div class="col-xl-12">
        <!-- Content Row -->
            <div class="card">
               
                <div class="card-header py-3 cstm_hdr">
                    <h6 class="m-0 font-weight-bold text-primary">Schedule Details</h6>
                </div>
                <div class="card-body">
                                    <div class="row mg-t-20">
                                        <div class="col-md">
                                            <div class="billed-to">
                                                <span><b>Cusomer :</b>  {{ isset($schedule->sale->name)?$schedule->sale->name:'' }}  </span>
                                                <br>
                                                <?php   
                                                    $date=isset($schedule->datetime)?$schedule->datetime:'';
                                                    $date=date(config('constants.DATE_FORMAT'),strtotime($date)); 

                                                    $time=isset($schedule->datetime)?$schedule->datetime:'';
                                                    $time=date(config('constants.TIME_FORMAT'),strtotime($time));
                                                ?>
                                                <span><b>Date :</b> {{ $date }} </span>
                                                <br>
                                                <span><b>Time  :</b> {{ $time }} </span>
                                                <br>
                                                <span><b>User   :</b>  {{ isset($schedule->user->name)?$schedule->user->name:'' }} 
                                                
                                            </div>
                                        </div>
                                    </div>
                                   <!--  <div class="table-responsive mg-t-40">
                                        <table class="table table-invoice border text-md-nowrap mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="wd-20p">Product </th>
                                                   
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <tr><td></td></tr>
                                            </tbody>
                                        </table>
                                    </div> -->
                </div>  
                <div class="card-footer">
                    <a href="{{route('admin.schedules.index')}}"  class="btn btn-secondary">Back</a>
                </div>
               
            </div>
        </div>
    </div>
</div>
@endsection



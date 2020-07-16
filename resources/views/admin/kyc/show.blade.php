@extends('admin.layouts.valex_app')
@section('content')
<!-- Page Heading -->
<div class="container"> 
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
              <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">KYC Details</h2>
            </div>
        </div>
    </div>
     <div class="row row-sm">
        <div class="col-xl-12">
        <!-- Content Row -->
            <div class="card">
                <div class="card-header py-3 cstm_hdr">
                    <h6 class="m-0 font-weight-bold text-primary">KYC Details</h6>
                </div>
                <div class="card-body">
                    <div class="row mg-t-20">
                        <div class="col-md">
                            <div class="billed-to">
                                <span><b>Cusomer :</b>  {{ isset($schedule->sale->name)?$schedule->sale->name:'' }}  <span><br>
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
                                <span><b>User   :</b>  {{ isset($schedule->user->name)?$schedule->user->name:'' }} </span>
                                <br>
                                <span><b>Status  :</b>  {{ isset($schedule->final_status)?$schedule->final_status:'' }} </span>
                                <br>
                                 <span><b>Video :</b>  <a href="{{ isset($videourl)?$videourl:'' }}" ><li class="fa fa-download"></li></a>   </span>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mg-t-40">
                        <table class="table table-invoice border text-md-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Aadhar </th>
                                    <th>Pan </th>
                                    <th>Photo</th>
                                    <th>SS01</th>
                                    <th>SS02 </th>
                                    <th>SS03 </th>
                                </tr>
                            </thead>
                            <tbody>
                               <tr>
                                   <td>   
                                    <?php 
                                        if (isset($schedule->image_adhar) && $schedule->image_adhar!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_adhar)) { 
                                             echo  '<a  href="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_adhar).'" download />Aadhar XML</a>';
                                        } ?>
                                     </td>
                                     <td>
                                          <?php 
                                        if (isset($schedule->image_pen) && $schedule->image_pen!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_pen)) {
                                             echo  '<img width="100px"  height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_pen).'" />';
                                        } ?>
                                     </td>
                                     <td>
                                        <?php 
                                        if (isset($schedule->image_photo) && $schedule->image_photo!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_photo)) {
                                             echo  '<img width="100px"  height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_photo).'" />';
                                        } ?>
                                     </td>
                                     <td>
                                        <?php 
                                        if (isset($schedule->ss01) && $schedule->ss01!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss01)) {
                                             echo  '<img width="100px"  height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss01).'" />';
                                        } ?>
                                     </td>
                                     <td>
                                        <?php 
                                        if (isset($schedule->ss02) && $schedule->ss02!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss02)) {
                                             echo  '<img width="100px"  height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss02).'" />';
                                        } ?>
                                     </td>
                                     <td>
                                        <?php 
                                        if (isset($schedule->ss03) && $schedule->ss03!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss03)) {
                                             echo  '<img width="100px"  height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss03).'" />';
                                        } ?>
                                     </td>
                               </tr>
                            </tbody>
                        </table>
                    </div> 
                </div>  
                <div class="card-footer">
                    <a href="{{route('admin.kyc.index')}}"  class="btn btn-secondary">Back</a>
                </div>
               
            </div>
        </div>
    </div>
</div>
@endsection



<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
      <div class="page-wrapper">
            <div class="message"></div>
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fa fa-map-o"></i> Logistice Support </h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active"><i class="fa fa-bars" aria-hidden="true"></i> Logistice Support </li>
                    </ol>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row m-b-10"> 
                    <div class="col-12">
                        <button type="button" class="btn btn-info"><i class="fa fa-plus"></i><a data-toggle="modal" data-target="#supportmodel" data-whatever="@getbootstrap" class="text-white"><i class="" aria-hidden="true"></i> Add Logistic Support</a></button>                       
                        <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>Projects/All_Assets" class="text-white"><i class="" aria-hidden="true"></i>  Assets List</a></button>
                        <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>Logistice/Logistic_Support" class="text-white"><i class="" aria-hidden="true"></i>  Logistic Support</a></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white"> Logistic Support List</h4>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Assets</th>
                                                <th>Assign User </th>
                                                <th>Task Name</th>
                                                <th>Qty</th>
                                                <th>End Date</th>
                                                <th>Back Date</th>
                                                <th>Back Qty</th>
                                                <th>Action </th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Assets</th>
                                                <th>Assign User </th>
                                                <th>Task Name</th>
                                                <th>Qty</th>
                                                <th>End Date</th>
                                                <th>Back Date</th>
                                                <th>Back Qty</th>
                                                <th>Action </th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                           <?php foreach($supportview as $value): ?>
                                            <tr>
                                                <td><?php echo $value->ass_name; ?></td>
                                                <td><?php echo $value->first_name.' '.$value->last_name; ?></td>
                                                <td><?php echo substr($value->task_title,0,13).'...'; ?></td>
                                                <td><?php echo $value->log_qty; ?></td>
                                                <td><?php
                                                    $end = $value->end_date; 
                                                    $expire = strtotime($end);
                                                    $todaydate= date("m/d/Y");
                                                    $todate= strtotime($todaydate);
                                                    #echo $todate;
                                                    if($todate >= $expire){
                                                        echo "<span style='color:red'>".$value->end_date."</span>";
                                                    } else {
                                                        echo $value->end_date;
                                                    }
                                                    
                                                    #echo $value->end_date; ?></td>
                                                <td><?php echo $value->back_date; ?></td>
                                                <td><?php echo $value->back_qty; ?></td>
                                                <td class="jsgrid-align-center ">
                                                    <a href="#" title="Edit" class="btn btn-sm btn-info waves-effect waves-light logisticessupport" data-id="<?php echo $value->ass_id; ?>"><i class="fa fa-pencil-square-o "></i></a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                            <!-- sample modal content -->
                        <div class="modal fade" id="supportmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content ">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="exampleModalLabel1"><i class="fa fa-map-o"></i> Add Logistice </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <form method="post" action="Add_Logistic_Support" id="logisticsform" enctype="multipart/form-data">
                                    <div class="modal-body">
                                           <div class="row">
                                            <div class="col-md-6">
                                             <div class="form-group">
                                                <label class="control-label">Logistic List</label>
                                                <select class="select2 form-control custom-select assetsstock" data-placeholder="Choose a Category" tabindex="1" name="logid" style="width:100%" required>
                                                  <option value="">Select Here</option>
                                                   <?php foreach($assets as $value): ?>
                                                    <option value="<?php echo $value->ass_id; ?>"><?php echo $value->ass_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div> 
                                             <div class="form-group">
                                                <label class="control-label">Project</label>
                                                <select class="select2 form-control custom-select" data-placeholder="Choose a Category" tabindex="1" name="proid" id="OnEmValue" style="width:100%" required>
                                                  <option value="">Select Here</option>
                                                   <?php foreach($projects as $value): ?>
                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->pro_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div> 
                                             <div class="form-group">
                                                <label class="control-label">Task List</label>
                                                <select class="form-control custom-select taskclass" data-placeholder="Choose a Category" tabindex="1" name="taskid" id="taskval" required>
                                                  <option value="">Select Here</option>

                                                </select>
                                            </div>  
                                             <div class="form-group">
                                                <label class="control-label">Employee Name</label>
                                                <select class="select2 form-control custom-select" data-placeholder="Choose a Category" tabindex="1" name="assignid" id="assignval" style="width: 100%" required>
                                                  <option value="">Select here</option>
                                                   <?php foreach($employee as $value): ?>
                                                    <option value="<?php echo $value->em_id ?>"><?php echo $value->first_name.' '.$value->last_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div> 
                                            </div>
                                            <div class="col-md-6">                                        
                                            <div class="form-group">
                                                <label class="control-label">Start Date</label>
                                                <input type="text" name="startdate" class="form-control mydatetimepickerFull" id="recipient-name1" value="" >
                                            </div>                                         
                                            <div class="form-group">
                                                <label class="control-label">End Date</label>
                                                <input type="text" name="enddate" class="form-control mydatetimepickerFull" id="recipient-name1" value="" >
                                            </div><!--                                          
                                            <div class="form-group">
                                                <label class="control-label">Back Date</label>
                                                <input type="date" name="backdate" class="form-control" id="recipient-name1" value="" >
                                            </div>-->
                                            <span>In Stock:<div style="color:red" class="qty"> </div></span>                                        
                                            <div class="form-group">
                                                <label class="control-label">Assign Qty</label>
                                                <input type="text" name="assignqty" class="form-control" id="recipient-name1 qty" value="" min="0" max="">
                                            </div><!--                                        
                                            <div class="form-group">
                                                <label class="control-label">Back Qty</label>
                                                <input type="text" name="backqty" class="form-control" id="recipient-name1" value="" >
                                            </div>-->
                                            <div class="form-group">
                                                <label class="control-label">Remarks</label>
                                                <textarea class="form-control col-md-8" name="remarks" id="message-text1"></textarea>
                                            </div>                                            
                                        </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                       <input type="hidden" name="assid" value="">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                            <!-- sample modal content -->
                        <div class="modal fade" id="supportmodelupdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content ">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="exampleModalLabel1">Update Logistice </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <form method="post" action="Add_Logistic_Support" id="logisticsupform" enctype="multipart/form-data">
                                    <div class="modal-body">
                                       <div class="row">
                                       <div class="col-md-6">
                                        <div class="form-group">
                                                <label class="control-label">Project</label>
                                                <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" name="proid" id="OnEmValue" required>
                                                  <option value="">Select Here</option>
                                                   <?php foreach($projects as $value): ?>
                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->pro_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>                                            
                                             <div class="form-group">
                                                <label class="control-label">Logistic List</label>
                                                <select class="form-control custom-select assetsstock" data-placeholder="Choose a Category" tabindex="1" name="logid" required>
                                                  <option value="">Select Here</option>
                                                   <?php foreach($assets as $value): ?>
                                                    <option value="<?php echo $value->ass_id; ?>"><?php echo $value->ass_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>  
                                             <div class="form-group">
                                                <label class="control-label">Task List</label>
                                                <select class="form-control custom-select taskclass" data-placeholder="Choose a Category" tabindex="1" name="taskid" id="taskval" required>
                                                  <option value="">Select Here</option>
                                                  <?php foreach($tasks as $value): ?>
                                                  <option value="<?php echo $value->id ?>"><?php echo $value->task_title ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div> 
                                             <div class="form-group">
                                                <label class="control-label">Assign User</label>
                                                <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" name="assignid" id="assignval" required>
                                                  <option value="">Select here</option>
                                                  <?php foreach($employee as $value): ?>
                                                  <option value="<?php echo $value->em_id ?>"><?php echo $value->first_name.' '.$value->last_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>                                         
                                            <div class="form-group">
                                                <label class="control-label">Start Date</label>
                                                <input type="text" name="startdate" class="form-control mydatetimepickerFull" id="recipient-name1" value="" >
                                            </div>
                                            </div> 
                                            <div class="col-md-6">                                        
                                            <div class="form-group">
                                                <label class="control-label">End Date</label>
                                                <input type="text" name="enddate" class="form-control mydatetimepickerFull" id="recipient-name1" value="" >
                                            </div>                                          
                                            <div class="form-group">
                                                <label class="control-label">Refund Date</label>
                                                <input type="text" name="backdate" class="form-control mydatetimepickerFull" id="recipient-name1" value="" >
                                            </div>
                                            <span>In Stock:</span><div style="color:red" class="qty"> </div>                                        
                                            <div class="form-group">
                                                <label class="control-label">Assign Qty</label>
                                                <input type="text" name="assignqty" class="form-control" id="recipient-name1 qty" value="" min="0" max="">
                                            </div>                                        
                                            <div class="form-group">
                                                <label class="control-label">Refund Qty</label>
                                                <input type="text" name="backqty" class="form-control" id="recipient-name1" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Remarks</label>
                                                <textarea class="form-control col-md-8" name="remarks" id="message-text1"></textarea>
                                            </div>                                            
                                        </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                       <input type="hidden" name="assid" value="">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".assetsstock").change(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = +this.value;
                                                //console.log(this.value);
                                                //"#taskval option:selected" ).text();
                                                $( "#qty" ).change();
                                                //$('#salaryform').trigger("reset");
                                                $.ajax({
                                                    url: 'GetInstock?id=' + this.value,
                                                    method: 'GET',
                                                    data: 'data',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
                                                    $('.qty').html(response);                                                                             $('#logisticsform').find('[name="assignqty"]').attr("max",response);           
												});
                                            });
                                        });
</script>
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $("#OnEmValue").change(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = +this.value;
                                                //console.log(this.value);
                                                //"#taskval option:selected" ).text();
                                                $( "#taskval" ).change();
                                                //$('#salaryform').trigger("reset");
                                                $.ajax({
                                                    url: 'GetTaskforlogistic?id=' + this.value,
                                                    method: 'GET',
                                                    data: 'data'
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
                                                    $('#taskval').html(response);
												});
                                            });
                                        });
</script>  
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".taskclass").change(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = +this.value;
                                                //console.log(this.value);
                                                $( "#assignval" ).change();
                                                //$('#salaryform').trigger("reset");
                                                $.ajax({
                                                    url: 'GetAssignforlogistic?id=' + this.value,
                                                    method: 'GET',
                                                    data: 'data'
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
                                                    $('#assignval').html(response);
												});
                                            });
                                        });
</script>                        
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".logisticessupport").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $('#logisticsupform').trigger("reset");
                                                $('#supportmodelupdate').modal('show');
                                                $.ajax({
                                                    url: 'Logisticesupportbyib?id=' + iid,
                                                    method: 'GET',
                                                    data: '',
                                                    dataType: 'json',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
													$('#logisticsupform').find('[name="assid"]').val(response.logisticsupport.ass_id).end();
                                                    $('#logisticsupform').find('[name="logid"]').val(response.logisticsupport.asset_id).end();
                                                    $('#logisticsupform').find('[name="proid"]').val(response.logisticsupport.project_id).end();
                                                    $('#logisticsupform').find('[name="taskid"]').val(response.logisticsupport.task_id).end();
                                                    $('#logisticsupform').find('[name="assignid"]').val(response.logisticsupport.assign_id).end();
                                                    $('#logisticsupform').find('[name="assignqty"]').val(response.logisticsupport.log_qty).end();
                                                    $('#logisticsupform').find('[name="startdate"]').val(response.logisticsupport.start_date).end();
                                                    $('#logisticsupform').find('[name="enddate"]').val(response.logisticsupport.end_date).end();
                                                    $('#logisticsupform').find('[name="backdate"]').val(response.logisticsupport.back_date).end();
                                                    $('#logisticsupform').find('[name="backqty"]').val(response.logisticsupport.back_qty).end();
                                                    $('#logisticsupform').find('[name="remarks"]').val(response.logisticsupport.remarks).end();
												});
                                            });
                                        });
</script>                        
    <?php $this->load->view('backend/footer'); ?>        
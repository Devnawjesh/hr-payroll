<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
      <div class="page-wrapper">
            <div class="message"></div>
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fa fa-university" aria-hidden="true"></i> Tasks</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Tasks</li>
                    </ol>
                </div>
            </div>
            <div class="container-fluid">

                <div class="row m-b-10"> 
                    <div class="col-12">
                        <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                        
                        <?php } else { ?>                        
                        <button type="button" class="btn btn-info"><i class="fa fa-plus"></i><a data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap" class="text-white"><i class="" aria-hidden="true"></i> Add Task </a></button>
                        <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>Projects/All_Tasks" class="text-white"><i class="" aria-hidden="true"></i>  Project List</a></button>
                        <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>Projects/All_Tasks" class="text-white"><i class="" aria-hidden="true"></i>  Field Visit</a></button>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white"> Task List                   
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Project Title</th>
                                                <th>Tasks Title </th>
                                                <th>Start Date </th>
                                                <th>End Date </th>
                                                <th>Assigned Employee </th>
                                                <!--<th>Action </th>-->
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Project Title</th>
                                                <th>Tasks Title </th>
                                                <th>Start Date </th>
                                                <th>End Date </th>
                                                <th>Assigned Employee </th>
                                                <!--<th>Action </th>-->
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                           <?php foreach($tasks as $value): ?>
                                            <tr>
                                                <td><?php echo substr($value->pro_name,0,25).'...' ?></td>
                                                <td><?php echo substr($value->task_title,0,25).'...' ?></td>
                                                <td><?php echo date('jS \of F Y',strtotime($value->start_date))  ?></td>
                                                <td><?php echo date('jS \of F Y',strtotime($value->end_date)) ?></td>
                                                <td>
                                                <?php
                                                $id = $value->id;
                                                $assignvalue = $this->project_model->getTaskAssignUser($id);  ?>
                                                <?php foreach($assignvalue as $value1): ?>
                                                <img src="<?php echo base_url(); ?>assets/images/users/<?php echo $value1->em_image ?>" height="40px" width="40px" style="border-radius:50px" alt="" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $value1->first_name; ?>">
                                                <?php endforeach; ?>
                                                </td>
<!--                                                <td class="jsgrid-align-center ">
                                                    <a href="#" title="Edit" class="btn btn-sm btn-info waves-effect waves-light taskmodal" data-id="<?php #echo $value->id ?>"><i class="fa fa-pencil-square-o"></i></a>
                                                    <a onclick="alert('Are you sure want to delet this Value?')" href="#" title="Delete" class="btn btn-sm btn-info waves-effect waves-light TasksDelet" data-id="<?php #echo $value->id ?>"><i class="fa fa-trash-o"></i></a>
                                                </td>-->
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
                        <!-- sample modal content -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content ">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="exampleModalLabel1">Add Tasks</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <form method="post" action="Add_Tasks" id="tasksModalform" enctype="multipart/form-data">
                                    <div class="modal-body">
                                             <div class="form-group row">
                                                <label class="control-label col-md-3">Project List</label>
                                                <select class="form-control custom-select col-md-8 proid" data-placeholder="Choose a Category" tabindex="1" name="projectid">
                                                   <?php foreach($projects as $value): ?>
                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->pro_name; ?></option>
                                                   <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3">Project Date</label>
                                                <input type="text" value="" name="prostart" class="form-control col-md-4" id="recipient-name1" readonly>
                                                <input type="text" value="" name="proend" class="form-control col-md-4" id="recipient-name1" readonly>
                                            </div>                                              
                                             <div class="form-group row">
                                                <label class="control-label col-md-3">Assign To</label>
                                                <select class="select2 form-control custom-select col-md-3" data-placeholder="Choose a Category" style="width:25%" tabindex="1" name="teamhead">
                                                  <option value="">Select Here</option>
                                                   <?php foreach($employee as $value): ?>
                                                    <option value="<?php echo $value->em_id; ?>"><?php echo $value->first_name.' '.$value->last_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <label class="control-label col-md-2">Collaborators</label>
                                                <select class="select2 form-control select2-multiple col-md-3" data-placeholder="Choose a Category" multiple="multiple" style="width:25%" tabindex="1" name="assignto[]">
                                                  <option value="">Select Here</option>
                                                   <?php foreach($employee as $value): ?>
                                                    <option value="<?php echo $value->em_id; ?>"><?php echo $value->first_name.' '.$value->last_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>                                                                                   
                                            <div class="form-group row">
                                                <label class="control-label col-md-3">Task Title</label>
                                                <input type="text" name="tasktitle" class="form-control col-md-8" id="recipient-name1" minlength="8" maxlength="250" placeholder="Task....">
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3">Task Start Date</label>
                                                <input type="text" name="startdate" class="form-control col-md-3 mydatetimepickerFull" id="recipient-name1">
                                                
                                                <label class="control-label col-md-2">Task End Date</label>
                                                <input type="text" name="enddate" class="form-control col-md-3 mydatetimepickerFull" id="recipient-name1">
                                            </div>
                                            <div class="form-group row">
                                                <label for="message-text" class="control-label col-md-3">Details</label>
                                                <textarea class="form-control col-md-8" name="details" id="message-text1" minlength="10" maxlength="1400"></textarea>
                                            </div>                                            
                                              <div class="form-group row">
                                               <label class="control-label col-md-3">Status: </label>
                                                <input name="status" type="radio" id="radio_1" data-value="Logistic" class="type" value="complete">
                                                <label for="radio_1">Complete</label>
                                                <input name="status" type="radio" id="radio_2" data-value="Logistic" class="type" value="running">
                                                <label for="radio_2">Running</label>
                                                <input name="status" type="radio" id="radio_3" data-value="Logistic" class="type" value="cancel">
                                                <label for="radio_3">Cancel</label>
                                            </div>                                             
                                              <div class="form-group row">
                                               <label class="control-label col-md-3">Type: </label>
                                                <input name="type" type="radio" id="radio_4" data-value="Logistic" class="type" value="Office">
                                                <label for="radio_4">Office</label>
                                               <!-- <input name="type" type="radio" id="radio_5" data-value="Logistic" class="type" value="Field">
                                                <label for="radio_5">Field</label>-->
                                              </div>  
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="id" class="form-control" id="recipient-name1">                                       
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
                                                    url: '<?php echo base_url();?>logistice/GetInstock?id=' + this.value,
                                                    method: 'GET',
                                                    data: 'data',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
                                                    $('.qty').html(response);                                                                             $('#tasksModalform').find('[name="qty"]').attr("max",response);           
												});
                                            });
                                        });
</script>
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".proid").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).val();
                                                console.log(iid);
                                                $('#tasksModalform').trigger("reset");
                                                $('#tasksmodel').modal('show');
                                                $.ajax({
                                                    url: 'projectbyId?id=' + iid,
                                                    method: 'GET',
                                                    data: '',
                                                    dataType: 'json',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
													$('#tasksModalform').find('[name="prostart"]').val(response.provalue.pro_start_date).end();
                                                    $('#tasksModalform').find('[name="proend"]').val(response.provalue.pro_end_date).end();
												});
                                            });
                                        });
</script>
                                       <script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".taskmodal").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                //console.log(iid);
                                                $('#tasksModalform').trigger("reset");
                                                $('#tasksmodel').modal('show');
                                                $.ajax({
                                                    url: 'TasksById?id=' + iid,
                                                    method: 'GET',
                                                    data: '',
                                                    dataType: 'json',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
													$('#tasksModalform').find('[name="id"]').val(response.tasksvalue.id).end();
                                                    $('#tasksModalform').find('[name="projectid"]').val(response.tasksvalue.pro_id).end();
                                                    $('#tasksModalform').find('[name="assignto"]').val(response.tasksvalue.assigned_id).end();
                                                    $('#tasksModalform').find('[name="tasktitle"]').val(response.tasksvalue.task_title).end();
                                                    $('#tasksModalform').find('[name="startdate"]').val(response.tasksvalue.start_date).end();
                                                    $('#tasksModalform').find('[name="enddate"]').val(response.tasksvalue.end_date).end();
                                                    $('#tasksModalform').find('[name="details"]').val(response.tasksvalue.description).end();
                                                    $('#tasksModalform').find('[name="status"]').val(response.tasksvalue.status).end();
												});
                                            });
                                        });
</script>
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".TasksDelet").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $.ajax({
                                                    url: 'TasksDeletByid?id=' + iid,
                                                    method: 'GET',
                                                    data: 'data',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    $(".message").fadeIn('fast').delay(3000).fadeOut('fast').html(response);
                                                    window.setTimeout(function(){location.reload()},2000)
                                                    // Populate the form fields with the data returned from server
												});
                                            });
                                        });
</script>     
    <?php $this->load->view('backend/footer'); ?>        
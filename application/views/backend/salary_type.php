<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
         <div class="page-wrapper">
            <div class="message"></div>
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">Payroll</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Payroll</li>
                    </ol>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row m-b-10"> 
                    <div class="col-12">
                        <button type="button" class="btn btn-info"><i class="fa fa-plus"></i><a data-toggle="modal" data-target="#TypeModal" data-whatever="@getbootstrap" class="text-white TypeModal"><i class="" aria-hidden="true"></i> Add Payroll </a></button>
                        <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>Payroll/Generate_salary" class="text-white"><i class="" aria-hidden="true"></i>  Generate Payroll</a></button>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white"> Payroll List                       
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>ID </th>
                                                <th>Salary Type</th>
                                                <th>Create Date </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>ID </th>
                                                <th>Salary Type</th>
                                                <th>Create Date </th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                           <?php foreach($typevalue as $value): ?>
                                            <tr>
                                                <td><?php echo $value->id; ?></td>
                                                <td><?php echo $value->salary_type ?></td>
                                                <td><?php echo $value->create_date; ?></td>
                                                <td class="jsgrid-align-center ">
                                                    <a href="" title="Edit" class="btn btn-sm btn-info waves-effect waves-light TypeModal" data-id="<?php echo $value->id; ?>"><i class="fa fa-pencil-square-o"></i></a>
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
                        <div class="modal fade" id="SalaryTypemodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content ">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="exampleModalLabel1">Salary Type</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <form method="post" action="Add_Sallary_Type" id="typeform" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        
                                            <div class="form-group">
                                                <label class="control-label">Salary Type</label>
                                                <input type="text" name="typename" class="form-control" id="recipient-name1" minlength="4" maxlength="25" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Create Date</label>
                                                <input type="date" name="createdate" class="form-control" id="recipient-name1"  value="">
                                            </div>                                          
                                        
                                    </div>
                                    <div class="modal-footer">
                                    <input type="hidden" name="id" value="" class="form-control" id="recipient-name1">                                       
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".TypeModal").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $('#typeform').trigger("reset");
                                                $('#SalaryTypemodel').modal('show');
                                                $.ajax({
                                                    url: 'GetSallaryTypeById?id=' + iid,
                                                    method: 'GET',
                                                    data: '',
                                                    dataType: 'json',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
													$('#typeform').find('[name="id"]').val(response.typevalueid.id).end();
                                                    $('#typeform').find('[name="typename"]').val(response.typevalueid.salary_type).end();
                                                    $('#typeform').find('[name="createdate"]').val(response.typevalueid.create_date).end();
												});
                                            });
                                        });
</script>                             
<?php $this->load->view('backend/footer'); ?>
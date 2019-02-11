<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
      <div class="page-wrapper">
            <div class="message"></div>  
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fa fa-hourglass-1" aria-hidden="true"></i> Grand Loan</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Grand Loan</li>
                    </ol>
                </div>
            </div>
         <div class="container-fluid">
            <div class="row m-b-10"> 
                <div class="col-12">
                    <button type="button" class="btn btn-info"><i class="fa fa-plus"></i><a data-toggle="modal" data-target="#loanmodel" data-whatever="@getbootstrap" class="text-white"><i class="" aria-hidden="true"></i> Add Loan </a></button>
                    <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>Loan/installment" class="text-white"><i class="" aria-hidden="true"></i>  Loan Installment</a></button>
                </div>
            </div> 
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline-info">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white"> Loan List                     
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
                                <table id="loan123" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Employee Code</th>
                                            <th>Amount</th>
<!--                                            <th>Interest Percentage </th>
                                            <th>Installment Period </th>-->
                                            <th>Installment </th>
                                            <th>Total Pay </th>
                                            <th>Total Due </th>
                                            <th>Approve Date </th>
                                            <th>Status </th>
                                            <th>Action </th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Employee Code</th>
                                            <th>Amount</th>
<!--                                            <th>Interest Percentage </th>
                                            <th>Installment Period </th>-->
                                            <th>Installment </th>
                                            <th>Total Pay </th>
                                            <th>Total Due </th>
                                            <th>Approve Date </th>
                                            <th>Status </th>
                                            <th>Action </th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                       <?php foreach($loanview as $value): ?>
                                        <tr>
                                            <td><?php echo $value->first_name.' '.$value->last_name ?></td>
                                            <td><?php echo $value->em_code ?></td>
                                            <td><?php echo $value->amount ?></td>
<!--                                            <td><?php #echo $value->interest_percentage.''.'%' ?></td>
                                            <td><?php #echo $value->install_period ?></td> -->
                                            <td><?php echo $value->installment ?></td> 
                                            <td><?php echo $value->total_pay ?></td>
                                            <td><?php echo $value->total_due ?></td>
                                            <td><?php echo date('jS \of F Y',strtotime($value->approve_date)) ?></td>
                                            <td><?php echo $value->status ?></td>
                                            <td class="jsgrid-align-center ">
                                                <a href="#" title="Edit" class="btn btn-sm btn-info waves-effect waves-light loanmodalclass" data-id="<?php echo $value->id; ?>" ><i class="fa fa-pencil-square-o"></i></a>
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
        </div>       
                        <!-- sample modal content -->
        <div class="modal fade" id="loanmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1">Loan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form role="form" method="post" action="Add_Loan" id="btnSubmit" enctype="multipart/form-data">
                    <div class="modal-body">
                             <div class="form-group row">
                                <label class="control-label col-md-3">Assign To</label>
                                <select class="form-control custom-select col-md-8" data-placeholder="Choose a Category" tabindex="1" name="emid" required>
                                  <option value="">Select Here</option>
                                   <?php foreach($employee as $value): ?>
                                    <option value="<?php echo $value->em_id; ?>"><?php echo $value->first_name.' '.$value->last_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="message-text" class="control-label col-md-3">Amount</label>
                                <input type="text" name="amount" value="" class="form-control col-md-8 amount" id="recipient-name1" required>
                            </div> 
<!--                            <div class="form-group row">
                                <label for="message-text" class="control-label col-md-3">Interest Percentage</label>
                                <input type="number" name="interest" value="" class="form-control col-md-8" id="recipient-name1" required>
                            </div>-->                                                         
                            <div class="form-group row">
                                <label class="control-label col-md-3">Approve Date</label>
                                <input type="text" name="appdate" class="form-control col-md-8 mydatetimepickerFull" id="recipient-name1" value="" required>
                            </div>
                            <div class="form-group row">
                                <label for="message-text" class="control-label col-md-3">Install Period</label>
                                <input type="number" name="install" value="" class="form-control col-md-8 period" id="recipient-name1" required>
                            </div>
                            <div class="form-group row">
                                <label for="message-text" class="control-label col-md-3">Install Amount</label>
                                <input type="number" name="installment" value="" class="form-control col-md-8 installment" id="recipient-name1" readonly>
                            </div>
                            <div class="form-group row">
                                <label for="message-text" class="control-label col-md-3"> Loan No</label>
                                <input type="text" name="loanno" value="<?php echo rand(100000,56000000)?>" class="form-control col-md-8" id="recipient-name1" readonly>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3">Status</label>
                                <select class="form-control custom-select col-md-8" data-placeholder="Choose a Category" tabindex="1" name="status" value="" required>
                                    <option value="">Select here</option>
                                    <option value="Granted">Granted</option>
                                    <option value="Deny">Deny</option>
                                    <option value="Done">Done</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="message-text" class="control-label col-md-3">Loan Details</label>
                                <textarea class="form-control col-md-8" name="details" value="" id="message-text1"></textarea>
                            </div>                                                                        
                        
                    </div>
                    <div class="modal-footer">
                       <input type="hidden" name="id" value="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
                        <!-- /.modal --> 
          <script type="text/javascript">
          $('.amount, .period').on('input',function() {
            var amount = parseInt($('.amount').val());
            var period = parseFloat($('.period').val());
            $('.installment').val((amount / period ? amount / period : 0).toFixed(2));
          });
          </script>
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".loanmodalclass").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $('#btnSubmit').trigger("reset");
                                                $('#loanmodel').modal('show');
                                                $.ajax({
                                                    url: 'LoanByID?id=' + iid,
                                                    method: 'GET',
                                                    data: '',
                                                    dataType: 'json',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
													$('#btnSubmit').find('[name="emid"]').val(response.loanvalue.emp_id).end();
													$('#btnSubmit').find('[name="id"]').val(response.loanvalue.id).end();
                                                    $('#btnSubmit').find('[name="details"]').val(response.loanvalue.loan_details).end();
                                                    $('#btnSubmit').find('[name="appdate"]').val(response.loanvalue.approve_date).end();
                                                    $('#btnSubmit').find('[name="redate"]').val(response.loanvalue.repayment_from).end();
                                                    $('#btnSubmit').find('[name="amount"]').val(response.loanvalue.amount).end();
                                                   /* $('#btnSubmit').find('[name="interest"]').val(response.loanvalue.interest_percentage).end();*/
                                                    $('#btnSubmit').find('[name="install"]').val(response.loanvalue.install_period).end();
                                                    $('#btnSubmit').find('[name="installment"]').val(response.loanvalue.installment).end();
                                                    $('#btnSubmit').find('[name="loanno"]').val(response.loanvalue.loan_number).end();
                                                    $('#btnSubmit').find('[name="status"]').val(response.loanvalue.status).end();
												});
                                            });
                                        });
</script>                            
                      
<?php $this->load->view('backend/footer'); ?>
  <script>
    $('#loan123').DataTable({
        "aaSorting": [[6,'desc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
</script> 
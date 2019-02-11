<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
      <div class="page-wrapper">
        <div class="message"></div>
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fa fa-cart-plus"></i> Assets</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Assets List</li>
                    </ol>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row m-b-10"> 
                    <div class="col-12">
                        <button type="button" class="btn btn-info"><i class="fa fa-plus"></i><a data-toggle="modal" data-target="#assetsmodel" data-whatever="@getbootstrap" class="text-white"><i class="" aria-hidden="true"></i> Add Assets </a></button>
                        <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>Projects/All_Assets" class="text-white"><i class="" aria-hidden="true"></i>  Logistic Support List</a></button>
                        <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>Projects/All_Assets" class="text-white"><i class="" aria-hidden="true"></i>  Logistic Support</a></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white"> Assets List</h4>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr><!--
                                                <th>ID</th>
                                                <th>Type </th>-->
                                                <th>category</th>
                                                <th>Name </th>
                                                <th>Brand </th>
                                                <th>Model</th>
                                                <th>Code </th>
                                                <th>Configuration </th>
                                                <th>InStock </th>
                                                <th>Action </th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
<!--                                                <th>ID</th>
                                                <th>Type </th>-->
                                                <th>category</th>
                                                <th>Name </th>
                                                <th>Brand </th>
                                                <th>Model</th>
                                                <th>Code </th>
                                                <th>Configuration </th>
                                                <th>InStock </th>
                                                <th>Action </th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                           <?php foreach($assets as $value): ?>
                                            <tr>
<!--                                                <td><?php echo $value->ass_id ?></td>
                                                <td><?php echo $value->cat_status ?></td>-->
                                                <td><?php echo $value->cat_name ?></td>
                                                <td><?php echo $value->ass_name ?></td>
                                                <td><?php echo $value->ass_brand ?></td>
                                                <td><?php echo $value->ass_model ?></td>
                                                <td><?php echo $value->ass_code ?></td>
                                                <td><?php echo substr($value->configuration,0,25).'...'?></td>
                                                <td><?php echo $value->in_stock ?></td>
                                                <td class="jsgrid-align-center ">
                                                    <a href="#" title="Edit" class="btn btn-sm btn-info waves-effect waves-light assets" data-id="<?php echo $value->ass_id ?>"><i class="fa fa-pencil-square-o"></i></a>
                                                    <!--<a href="AssetsDelet?D=<?php #echo $value->ass_id; ?>" onclick="confirm('Are you Sure??')" title="Delete" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-trash-o"></i></a>-->
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
                        <div class="modal fade" id="assetsmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content ">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="exampleModalLabel1">Add Asset </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <form method="post" action="Add_Assets" id="btnSubmit" enctype="multipart/form-data">
                                    <div class="modal-body">
                                           <div class="row">
                                               <div class="col-md-6">      
                                            <div class="form-group">
                                                <label class="control-label">Asset name</label>
                                                <input type="text" name="assname" value="" class="form-control" id="recipient-name1" required>
                                            </div>
                                            <div class="form-group">
                                               <label class="control-label">Category Type </label>
                                                <select name="catid" value="" class="select2 form-control custom-select" style="width: 100%" required value="">
                                                    <option>Select Category</option>
                                                    <?php foreach($catvalue as $value): ?>
                                                    <option value="<?php echo $value->cat_id ?>"><?php echo $value->cat_name ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Assets Brand</label>
                                                <input type="text" name="brand" value="" class="form-control" id="recipient-name1">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Assets Model</label>
                                                <input type="text" name="model" value="" class="form-control" id="recipient-name1">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Assets Code</label>
                                                <input type="text" name="code" value="" class="form-control" id="recipient-name1 ">
                                            </div>                                                   
                                               </div>
                                               <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Configuration</label>
                                                <textarea class="form-control" name="config" id="message-text1" required minlength="14" rows="4"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Purchaseing Date</label>
                                                <input type="text" name="purchase" value="" class="form-control mydatepicker" id="recipient-name1">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Price</label>
                                                <input type="number" name="price" value="" class="form-control" id="recipient-name1">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Quantity</label>
                                                <input type="number" name="pqty" value=""
                                                 class="form-control" id="recipient-name1">
                                            </div>                                                   
                                               </div>
                                        </div>
<!--
                                            <div class="form-group">
                                                <input name="type" type="checkbox" id="radio_2" data-value="Logistic" value="Logistic" class="type">
                                                <label for="radio_2">Add To Logistic Support List</label>
                                            </div>-->       
                                    </div>
                                    <div class="modal-footer">
                                       <input type="hidden" name="aid" value="">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".assets").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $('#btnSubmit').trigger("reset");
                                                $('#assetsmodel').modal('show');
                                                $.ajax({
                                                    url: 'AssetsByID?id=' + iid,
                                                    method: 'GET',
                                                    data: '',
                                                    dataType: 'json',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
													$('#btnSubmit').find('[name="aid"]').val(response.assetsByid.ass_id).end();
                                                    $('#btnSubmit').find('[name="catid"]').val(response.assetsByid.cat_id).end();
                                                    $('#btnSubmit').find('[name="assname"]').val(response.assetsByid.ass_name).end();
                                                    $('#btnSubmit').find('[name="brand"]').val(response.assetsByid.ass_brand).end();
                                                    $('#btnSubmit').find('[name="model"]').val(response.assetsByid.ass_model).end();                                                   
                                                    $('#btnSubmit').find('[name="code"]').val(response.assetsByid.ass_code).end();                                                   
                                                    $('#btnSubmit').find('[name="config"]').val(response.assetsByid.configuration).end();          
                                                    $('#btnSubmit').find('[name="purchase"]').val(response.assetsByid.purchasing_date).end();                                              
                                                    $('#btnSubmit').find('[name="price"]').val(response.assetsByid.ass_price).end();                                              
                                                    $('#btnSubmit').find('[name="pqty"]').val(response.assetsByid.ass_qty).end();                                              
//                                                     if (response.assetsByid.Assets_type == 'Logistic')
//                                                   $('#btnSubmit').find(':checkbox[name=type][value="Logistic"]').prop('checked', true);
                                                   
												});
                                            });
                                        });
</script>                        
    <?php $this->load->view('backend/footer'); ?>        
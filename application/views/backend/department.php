<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?> 
         <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fa fa-cubes" style="color:#1976d2"></i> Department</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Department</li>
                    </ol>
                </div>
            </div>
            <div class="message"></div> 
            <div class="container-fluid">         
                <div class="row">
                    <div class="col-lg-5">
                        <?php if (isset($editdepartment)) { ?>
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">Edit Department</h4>
                            </div>
                            
                            <?php echo validation_errors(); ?>
                            <?php echo $this->upload->display_errors(); ?>
                            <?php echo $this->session->flashdata('feedback'); ?>
                            

                            <div class="card-body">
                                    <form method="post" action="<?php echo base_url();?>organization/Update_dep" enctype="multipart/form-data">
                                        <div class="form-body">
                                            <div class="row ">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Department Name</label>
                                                        <input type="text" name="department" id="firstName" value="<?php  echo $editdepartment->dep_name;?>" class="form-control" placeholder="">
                                                        <input type="hidden" name="id" value="<?php  echo $editdepartment->id;?>">
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <!--/row-->
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
                                            <button type="button" class="btn btn-info">Cancel</button>
                                        </div>
                                    </form>
                            </div>
                        </div>
                        <?php } else { ?>                        

                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">Add Department</h4>
                            </div>
                            
                            <?php echo validation_errors(); ?>
                            <?php echo $this->upload->display_errors(); ?>
                            <?php echo $this->session->flashdata('feedback'); ?>
                            

                            <div class="card-body">
                                    <form method="post" action="Save_dep" enctype="multipart/form-data">
                                        <div class="form-body">
                                            <div class="row ">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Department Name</label>
                                                        <input type="text" name="department" id="firstName" value="" class="form-control" placeholder="" minlength="3" required>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <!--/row-->
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
                                            <button type="button" class="btn btn-info">Cancel</button>
                                        </div>
                                    </form>
                            </div>
                        </div>
                        <?php }?>
                    </div>

                    <div class="col-7">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white"> Department List</h4>
                            </div>
                            <?php echo $this->session->flashdata('delsuccess'); ?>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table id="" class="display  table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Department Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Department Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                        
                                        <tbody>
                                            <?php foreach ($department as $value) { ?>
                                            <tr>
                                                <td><?php echo $value->dep_name;?></td>
                                                <td class="jsgrid-align-center ">
                                                    <a href="<?php echo base_url();?>organization/dep_edit/<?php echo $value->id;?>" title="Edit" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-pencil-square-o"></i></a>
                                                    <a onclick="return confirm('Are you sure to delete this data?')" href="<?php echo base_url();?>organization/Delete_dep/<?php echo $value->id;?>" title="Delete" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                            <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <?php $this->load->view('backend/footer'); ?>
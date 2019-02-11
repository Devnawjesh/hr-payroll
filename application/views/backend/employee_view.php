<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
                     <div class="page-wrapper">
                     <div class="message"></div>
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fa fa-user-secret" style="color:#1976d2"></i> <?php echo $basic->first_name .' '.$basic->last_name; ?></h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
                <?php $degvalue = $this->employee_model->getdesignation(); ?>
                <?php $depvalue = $this->employee_model->getdepartment(); ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-xlg-12 col-md-12">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab" style="font-size: 14px;">  Personal Info </a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab" style="font-size: 14px;"> Address </a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#education" role="tab" style="font-size: 14px;"> Education</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#experience" role="tab" style="font-size: 14px;"> Experience</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#bank" role="tab" style="font-size: 14px;"> Bank Account</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#document" role="tab" style="font-size: 14px;"> Document</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#salary" role="tab" style="font-size: 14px;"> Salary</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#leave" role="tab" style="font-size: 14px;"> Leave</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#social" role="tab" style="font-size: 14px;"> Social Media</a> </li>
                                <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#password" role="tab" style="font-size: 14px;"> Change Password</a> </li>
                                <?php } else { ?>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#password1" role="tab" style="font-size: 14px;"> Change Password</a> </li>                                
                                <?php } ?>
                            </ul>
                            <!-- Tab panes -->

                            <div class="tab-content">
                                <div class="tab-pane active" id="home" role="tabpanel">
                                    <div class="card">
				                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <center class="m-t-30">
                                   <?php if(!empty($basic->em_image)){ ?>
                                    <img src="<?php echo base_url(); ?>assets/images/users/<?php echo $basic->em_image; ?>" class="img-circle" width="150" />
                                    <?php } else { ?>
                                    <img src="<?php echo base_url(); ?>assets/images/users/user.png" class="img-circle" width="150" alt="<?php echo $basic->first_name ?>" title="<?php echo $basic->first_name ?>"/>                                   
                                    <?php } ?>
                                    <h4 class="card-title m-t-10"><?php echo $basic->first_name .' '.$basic->last_name; ?></h4>
                                    <h6 class="card-subtitle"><?php echo $basic->des_name; ?></h6>
                                </center>
                            </div>
                            <div>
                                <hr> </div>
                            <div class="card-body"> <small class="text-muted">Email address </small>
                                <h6><?php echo $basic->em_email; ?></h6> <small class="text-muted p-t-30 db">Phone</small>
                                <h6><?php echo $basic->em_phone; ?></h6> 
                                <small class="text-muted p-t-30 db">Social Profile</small>
                                <br/>
                                <a class="btn btn-circle btn-secondary" href="<?php if(!empty($socialmedia->skype_id)) echo $socialmedia->facebook ?>" target="_blank"><i class="fa fa-facebook"></i></a>
                                <a class="btn btn-circle btn-secondary" href="<?php if(!empty($socialmedia->skype_id)) echo $socialmedia->twitter ?>" target="_blank"><i class="fa fa-twitter"></i></a>
                                <a class="btn btn-circle btn-secondary" href="<?php if(!empty($socialmedia->skype_id)) echo $socialmedia->skype_id ?>" target="_blank"><i class="fa fa-skype"></i></a>
                                <a class="btn btn-circle btn-secondary" href="<?php if(!empty($socialmedia->google_Plus)) echo $socialmedia->google_Plus ?>" target="_blank"><i class="fa fa-google"></i></a>
                            </div>
                        </div>                                                    
                                                </div>
                                                <div class="col-md-8">
				                                <form class="row" action="Update" method="post" enctype="multipart/form-data">
				                                    
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Employee PIN </label>
				                                        <input type="text" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="ID" name="eid" value="<?php echo $basic->em_code; ?>" required > 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>First Name</label>
				                                        <input type="text" class="form-control form-control-line" placeholder="Your first name" name="fname" value="<?php echo $basic->first_name; ?>" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> minlength="3" required> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Last Name </label>
				                                        <input type="text" id="" name="lname" class="form-control form-control-line" value="<?php echo $basic->last_name; ?>" placeholder="Your last name" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> minlength="3" required> 
				                                    </div>
                                                    <div class="form-group col-md-4 m-t-10">
                                                        <label>Blood Group </label>
                                                        <select name="blood" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> value="<?php echo $basic->em_blood_group; ?>" class="form-control custom-select">
                                                            <option value="<?php echo $basic->em_blood_group; ?>"><?php echo $basic->em_blood_group; ?></option>
                                                            <option value="O+">O+</option>
                                                            <option value="O-">O-</option>
                                                            <option value="A+">A+</option>
                                                            <option value="A-">A-</option>
                                                            <option value="B+">B+</option>
                                                            <option value="B-">B-</option>
                                                            <option value="AB+">AB+</option>
                                                        </select>
                                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Gender </label>
				                                        <select name="gender" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control custom-select">
				                                           
				                                            <option value="<?php echo $basic->em_gender; ?>"><?php echo $basic->em_gender; ?></option>
				                                            <option value="Male">Male</option>
				                                            <option value="Female">Female</option>
				                                        </select>
				                                    </div>
                                                   <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>  <?php } else { ?> 
                                                    <div class="form-group col-md-4 m-t-10">
                                                        <label>User Type </label>
                                                        <select name="role" class="form-control custom-select" required >
				                                            <option value="<?php echo $basic->em_role; ?>"><?php echo $basic->em_role; ?></option>
                                                            <option value="HR">HR</option>
                                                            <option value="EMPLOYEE">Employee</option>
                                                            <option value="ADMIN">Super Admin</option>
                                                        </select>
                                                    </div>
                                                    <?php } ?>
                                                    <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>  <?php } else { ?> 
                                                    <div class="form-group col-md-4 m-t-10">
                                                        <label>Status </label>
                                                        <select name="status" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control custom-select" required >
				                                            <option value="<?php echo $basic->status; ?>"><?php echo $basic->status; ?></option>
                                                            <option value="ACTIVE">ACTIVE</option>
                                                            <option value="INACTIVE">INACTIVE</option>
                                                        </select>
                                                    </div>
                                                    <?php } ?>				                                    
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Date Of Birth </label>
				                                        <input type="date" id="example-email2" name="dob" class="form-control" placeholder="" value="<?php echo $basic->em_birthday; ?>" required> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>NID Number </label>
				                                        <input type="text" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control"placeholder="NID Number" name="nid" value="<?php echo $basic->em_nid; ?>" minlength="10" required> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Contact Number </label>
				                                        <input type="text" class="form-control" placeholder="" name="contact" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> value="<?php echo $basic->em_phone; ?>" minlength="10" maxlength="15" required> 
				                                    </div>
                                                   <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>  <?php } else { ?> 				                                    
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Department</label>
				                                        <select name="dept" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control custom-select">
				                                            <option value="<?php echo $basic->id; ?>"><?php echo $basic->dep_name; ?></option>
                                            <?Php foreach($depvalue as $value): ?>
                                             <option value="<?php echo $value->id ?>"><?php echo $value->dep_name ?></option>
                                            <?php endforeach; ?>
				                                        </select>
				                                    </div>
				                                    <?php } ?>
                                                   <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>  <?php } else { ?> 				                                    
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Designation </label>
				                                        <select name="deg" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control custom-select">
				                                            <option value="<?php echo $basic->id; ?>"><?php echo $basic->des_name; ?></option>
                                            <?Php foreach($degvalue as $value): ?>
                                            <option value="<?php echo $value->id ?>"><?php echo $value->des_name ?></option>
                                            <?php endforeach; ?>
				                                        </select>
				                                    </div>
				                                    <?php } ?>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Date Of Joining </label>
				                                        <input type="date" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> id="example-email2" name="joindate" class="form-control" value="<?php echo $basic->em_joining_date; ?>" placeholder=""> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Contract End Date</label>
				                                        <input type="date" id="example-email2" name="leavedate" class="form-control" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> value="<?php echo $basic->em_contact_end; ?>" placeholder=""> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Email </label>
				                                        <input type="email" id="example-email2" name="email" class="form-control" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> value="<?php echo $basic->em_email; ?>" placeholder="email@mail.com" minlength="7" required> 
				                                    </div>
				                                    <div class="form-group col-md-12 m-t-10">
                                   <?php if(!empty($basic->em_image)){ ?>
                                    <img src="<?php echo base_url(); ?>assets/images/users/<?php echo $basic->em_image; ?>" class="img-circle" width="150" />
                                    <?php } else { ?>
                                    <img src="<?php echo base_url(); ?>assets/images/users/user.png" class="img-circle" width="150" alt="<?php echo $basic->first_name ?>" title="<?php echo $basic->first_name ?>"/>                                   
                                    <?php } ?>
                                                        <label>Image </label>
                                                        <input type="file" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> name="image_url" class="form-control" value=""> 
                                                    </div>
                                                    <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
				                                    <div class="form-actions col-md-12">
                                                        <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">
				                                        <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
				                                        <button type="button" class="btn btn-info">Cancel</button>
				                                    </div>
				                                    <?php } ?>
				                                </form>
                                                </div>
                                        </div>
				                        </div>
                                    </div>
                                </div>
                                <!--second tab-->
                                <div class="tab-pane" id="profile" role="tabpanel">
                                    <div class="card">
				                        <div class="card-body">
			                        		<h3 class="card-title">Permanent Contact Information</h3>
			                                <form class="row" action="Parmanent_Address" method="post" enctype="multipart/form-data">
			                                    <div class="form-group col-md-12 m-t-5">
			                                        <label>Address</label>
			                                        <textarea name="paraddress" value="<?php if(!empty($permanent->address)) echo $permanent->address  ?>" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" rows="3" minlength="7" required><?php if(!empty($permanent->address)) echo $permanent->address  ?></textarea>
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>City</label>
			                                        <input type="text" name="parcity" class="form-control form-control-line" placeholder="" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> value="<?php if(!empty($permanent->city)) echo $permanent->city ?>" minlength="2" required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Country</label>
			                                        <input type="text" name="parcountry" class="form-control form-control-line" placeholder="" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> value="<?php if(!empty($permanent->country)) echo $permanent->country ?>" minlength="2" required> 
			                                    </div>
                                                    <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>			                                    
			                                    <div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id ?>">
                                                    <input type="hidden" name="id" value="<?php if(!empty($permanent->id)) echo $permanent->id  ?>">                                                    
			                                        <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
			                                    </div>
			                                    <?php } ?>			                                    
			                                    </form>
			                                    
			                                    <div class="">
			                        				<h3 class="col-md-12">Present Contact Information</h3>
			                                    </div>
			                                    <hr>
			                                <form class="row" action="Present_Address" method="post" enctype="multipart/form-data">			                                    
			                                    <div class="form-group col-md-12 m-t-5">
			                                        <label>Address</label>
			                                        <textarea name="presaddress" value="<?php if(!empty($present->address)) echo $present->address  ?>" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" rows="3" minlength="7" required><?php if(!empty($present->address)) echo $present->address  ?></textarea>
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>City</label>
			                                        <input type="text" name="prescity" class="form-control form-control-line" value="<?php if(!empty($present->address)) echo $present->city  ?>" placeholder=" City name" minlength="2" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Country</label>
			                                        <input type="text" name="prescountry" class="form-control form-control-line" placeholder="" value="<?php if(!empty($present->address)) echo $present->country  ?>" minlength="2" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> required> 
			                                    </div>
                                                    <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>			                                    
			                                    <div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id ?>">
                                                    <input type="hidden" name="id" value="<?php if(!empty($present->id)) echo $present->id  ?>">
			                                        <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
			                                    </div>
			                                    <?php } ?>
			                                </form>
				                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="education" role="tabpanel">
                                    <div class="card">
                <div class="card-body">
                    <div class="table-responsive ">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Certificate name</th>
                                    <th>Institute </th>
                                    <th>Result </th>
                                    <th>year</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID </th>
                                    <th>Certificate name</th>
                                    <th>Institute </th>
                                    <th>Result </th>
                                    <th>year</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                               <?php foreach($education as $value): ?>
                                <tr>
                                    <td><?php echo $value->id ?></td>
                                    <td><?php echo $value->edu_type ?></td>
                                    <td><?php echo $value->institute ?></td>
                                    <td><?php echo $value->result ?></td>
                                    <td><?php echo $value->year ?></td>
                                   <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
                                    <td class="jsgrid-align-center ">
                                        <a href="#" title="Edit" class="btn btn-sm btn-info waves-effect waves-light education" data-id="<?php echo $value->id ?>"><i class="fa fa-pencil-square-o"></i></a>
                                        <a onclick="confirm('Are you sure want to delet this Value?')" href="#" title="Delete" class="btn btn-sm btn-info waves-effect waves-light edudelet"  data-id="<?php echo $value->id ?>"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                    <?php } ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>                                    
                                    </div>
                                    <div class="card">
                                      
	                                    <div class="card-body">
			                                <form class="row" action="Add_Education" method="post" enctype="multipart/form-data" id="insert_education">
			                                	<span id="error"></span>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Degree Name</label>
			                                        <input type="text" name="name" class="form-control form-control-line" placeholder=" Degree Name" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> minlength="1" required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Institute name</label>
			                                        <input type="text" name="institute" class="form-control form-control-line" placeholder=" Institute name" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> minlength="3" required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Result</label>
			                                        <input type="text" name="result" class="form-control form-control-line" placeholder=" Result" minlength="2" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Passing Year</label>
			                                        <input type="text" name="year" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="Passing Year"> 
			                                    </div>
			                                  <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
			                                    <div class="form-actions col-md-6">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">
			                                        <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
			                                    </div>
			                                    <?php } ?>
			                                </form>
					                    </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="experience" role="tabpanel">
                                    <div class="card">
                <div class="card-body">
                    <div class="table-responsive ">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Company name</th>
                                    <th>Position </th>
                                    <th>Work Duration </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID </th>
                                    <th>Company name</th>
                                    <th>Position </th>
                                    <th>Work Duration </th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                               <?php foreach($experience as $value): ?>
                                <tr>
                                    <td><?php echo $value->id ?></td>
                                    <td><?php echo $value->exp_company ?></td>
                                    <td><?php echo $value->exp_com_position ?></td>
                                    <td><?php echo $value->exp_workduration ?></td>
                                    <td class="jsgrid-align-center ">
                                       <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                       <?php } else { ?>
                                        <a href="#" title="Edit" class="btn btn-sm btn-info waves-effect waves-light experience" data-id="<?php echo $value->id ?>"><i class="fa fa-pencil-square-o"></i></a>
                                        <a onclick="confirm('Are you sure want to delet this Value?')" href="#" title="Delete" class="btn btn-sm btn-info waves-effect waves-light deletexp" data-id="<?php echo $value->id ?>"><i class="fa fa-trash-o"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>                                     
	                                    <div class="card-body">
			                                <form class="row" action="Add_Experience" method="post" enctype="multipart/form-data">
			                                    	<div class="form-group col-md-6 m-t-5">
			                                    	    <label> Company Name</label>
			                                    	    <input type="text" name="company_name" class="form-control form-control-line company_name" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> placeholder="Company Name" minlength="2" required> 
			                                    	</div>
			                                    	<div class="form-group col-md-6 m-t-5">
			                                    	    <label>Position</label>
			                                    	    <input type="text" name="position_name" class="form-control form-control-line position_name" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> placeholder="Position" minlength="3" required> 
			                                    	</div>
			                                    	<div class="form-group col-md-6 m-t-5">
			                                    	    <label>Address</label>
			                                    	    <input type="text" name="address" class="form-control form-control-line duty" placeholder=" Duty" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> minlength="7" required> 
			                                    	</div>
			                                    	<div class="form-group col-md-6 m-t-5">
			                                    	    <label>Working Duration</label>
			                                    	    <input type="text" name="work_duration" class="form-control form-control-line working_period" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> placeholder="Working Duration" required> 
			                                    	</div>
			                                 <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
		                                    	<div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">                                                
		                                    	    <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
		                                    	</div>
		                                    	<?php } ?>
			                                </form>
					                    </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="bank" role="tabpanel">
                                    <div class="card">
	                                    <div class="card-body">
			                                <form class="row" action="Add_bank_info" method="post" enctype="multipart/form-data">
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label> Bank Holder Name</label>
			                                        <input type="text" name="holder_name" value="<?php if(!empty($bankinfo->holder_name)) echo $bankinfo->holder_name  ?>" class="form-control form-control-line" placeholder="Bank Holder Name" minlength="5" required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Bank Name</label>
			                                        <input type="text" name="bank_name" value="<?php if(!empty($bankinfo->bank_name)) echo $bankinfo->bank_name  ?>" class="form-control form-control-line" placeholder="Bank Name" minlength="5" required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Branch Name</label>
			                                        <input type="text" name="branch_name" value="<?php if(!empty($bankinfo->branch_name)) echo $bankinfo->branch_name  ?>" class="form-control form-control-line" placeholder=" Branch Name"> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Bank Account Number</label>
			                                        <input type="text" name="account_number" value="<?php if(!empty($bankinfo->account_number)) echo $bankinfo->account_number ?>" class="form-control form-control-line" minlength="5" required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Bank Account Type</label>
			                                        <input type="text" name="account_type" value="<?php if(!empty($bankinfo->account_type)) echo $bankinfo->account_type ?>" class="form-control form-control-line" placeholder="Bank Account Type"> 
			                                    </div>
			                                    <div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">
                                                    <input type="hidden" name="id" value="<?php if(!empty($bankinfo->id)) echo $bankinfo->id  ?>">
			                                        <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
			                                    </div>
			                                </form>
					                    </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="document" role="tabpanel">
                                    <div class="card-body">
                    <div class="table-responsive ">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>File Title</th>
                                    <th>File </th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID </th>
                                    <th>File Title</th>
                                    <th>File </th>
                                </tr>
                            </tfoot>
                            <tbody>
                               <?php foreach($fileinfo as $value): ?>
                                <tr>
                                    <td><?php echo $value->id ?></td>
                                    <td><?php echo $value->file_title ?></td>
                                    <td><a href="<?php echo base_url(); ?>assets/images/users/<?php echo $value->file_url ?>" target="_blank"><?php echo $value->file_url ?></a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>                                    
                                    <div class="card-body">
                                        <form class="row" action="Add_File" method="post" enctype="multipart/form-data">
                                            <div class="form-group col-md-6 m-t-5">
                                                <label class="">File Title</label>
                                                <input type="text" name="title" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" required="" aria-invalid="false" minlength="5" required>
                                            </div>
                                            <div class="form-group col-md-6 m-t-5">
                                                <label class="">File</label>
                                                <input type="file" name="file_url" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" required="" aria-invalid="false" required>
                                            </div>
                                            <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input type="hidden" name="em_id" value="<?php echo $basic->em_id; ?>">                                                   
                                                    <button type="submit" class="btn btn-success">Add File</button>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="leave" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
			                        <div class="card">
			                            <div class="card-body">
			                                <h4 class="card-title"> Leave</h4>
                                            <form action="Assign_leave" method="post" enctype="multipart/form-data">
                                                <div class="form-group">
                                                <label class="">Leave Type</label>                                 
                                                 <select name="typeid" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="select2 form-control custom-select" style="width: 100%" id="" required>
                                                  <option value="">Select Here...</option>
                                                   <?php foreach($leavetypes as $value): ?>
                                                    <option value="<?php echo $value->type_id ?>"><?php echo $value->name ?></option>
                                                    <?php endforeach; ?>
                                                </select>          
                                                </div>
			                                 <div class="form-group">
			                                    	<label>day</label>
			                                    	<input type="number" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> name="noday" class="form-control form-control-line noday" placeholder="Leave Day" required> 
			                                 </div>

                                                <div class="form-group">
                                                <label class="">Year</label>                                 <select name="year" class="select2 form-control custom-select" style="width: 100%" id="" required>
                                                 <option value="">Select Here...</option>
                                                  <?php 
                                                   for ($x = 2016; $x < 3000; $x++){
                                                    echo '<option value='.$x.'>'.$x.'</option>';            
                                                   }
                                                    ?>
                                                </select>          
                                                </div>
                                                <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>                 
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input type="hidden" name="em_id" value="<?php echo $basic->em_id; ?>">                                                   
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                </div>
                                            </div>                                                                     <?php } ?>         
                                            </form>
			                            </div>
			                        </div>                                          
                                    </div>
                                    <div class="col-md-6">
			                        <div class="card">
			                            <div class="card-body">
			                                <h4 class="card-title"> Leave/<?php echo date('Y') ?></h4>
                                            <table class="display nowrap table table-hover table-striped table-bordered" width="50%">
                                                <thead>
                                                   <tr class="m-t-50">
                                                    <th>Type</th>
                                                    <th>Dayout/Day</th>       
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   <?php foreach($Leaveinfo as $value): ?>
                                                    <tr>
                                                        <td><?php echo $value->name; ?></td>
                                                        <td><?php echo $value->total_day; ?>/<?php echo $value->day; ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
			                            </div>
			                        </div>                                     
                                    </div>
                                  
                                </div>
                                </div>
                                <div class="tab-pane" id="password1" role="tabpanel">
                                    <div class="card-body">
				                                <form class="row" action="Reset_Password_Hr" method="post" enctype="multipart/form-data">
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Password</label>
				                                        <input type="text" class="form-control" name="new1" value="" required minlength="6"> 
				                                    </div>
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Confirm Password</label>
				                                        <input type="text" id="" name="new2" class="form-control " required minlength="6"> 
				                                    </div>
				                                    <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
				                                    <div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">                                                   
				                                        <button type="submit" class="btn btn-info pull-right"> <i class="fa fa-check"></i> Save</button>
				                                    </div>
				                                    <?php } ?>
				                                </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="social" role="tabpanel">
                                    <div class="card-body">
				                                <form class="row" action="Save_Social" method="post" enctype="multipart/form-data">
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Facebook</label>
				                                        <input type="url" class="form-control" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> name="facebook" value="<?php if(!empty($socialmedia->facebook)) echo $socialmedia->facebook ?>" placeholder="www.facebook.com"> 
				                                    </div>
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Twitter</label>
				                                        <input type="text" class="form-control" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> name="twitter" value="<?php if(!empty($socialmedia->twitter)) echo $socialmedia->twitter ?>" > 
				                                    </div>
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Google +</label>
				                                        <input type="text" id="" name="google" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control " value="<?php if(!empty($socialmedia->google_plus)) echo $socialmedia->google_plus ?>"> 
				                                    </div>
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Skype</label>
				                                        <input type="text" id="" name="skype" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control " value="<?php if(!empty($socialmedia->skype_id)) echo $socialmedia->skype_id ?>"> 
				                                    </div>
				                                <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
				                                    <div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">                                                   
                                                    <input type="hidden" name="id" value="<?php if(!empty($socialmedia->id)) echo $socialmedia->id ?>">                                                   
				                                        <button type="submit" class="btn btn-info pull-right"> <i class="fa fa-check"></i> Save</button>
				                                    </div>
				                                    <?php } ?>
				                                </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="password" role="tabpanel">
                                    <div class="card-body">
				                                <form class="row" action="Reset_Password" method="post" enctype="multipart/form-data">
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Old Password</label>
				                                        <input type="text" class="form-control" name="old" value="" placeholder="old password" required minlength="6"> 
				                                    </div>
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Password</label>
				                                        <input type="text" class="form-control" name="new1" value="" required minlength="6"> 
				                                    </div>
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Confirm Password</label>
				                                        <input type="text" id="" name="new2" class="form-control " required minlength="6"> 
				                                    </div>
				                                    <div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">                                                   
				                                        <button type="submit" class="btn btn-info pull-right"> <i class="fa fa-check"></i> Save</button>
				                                    </div>
				                                </form>
                                    </div>
                                </div>

                                <div class="tab-pane" id="salary" role="tabpanel">
                                    <div class="card">
				                        <div class="card-body">
			                        		<h3 class="card-title">Basic Slary</h3>
			                                <form action="Add_Salary" method="post" enctype="multipart/form-data">
                                           <div class="row">
                                            <div class="form-group col-md-6 m-t-5">
                                                <label class="control-label">Salary Type</label>
                                                <select class="form-control <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> custom-select" data-placeholder="Choose a Category" tabindex="1" name="typeid" required>
                                                   <?php if(empty($salaryvalue->salary_type)){ ?>
                                                    <?php } else { ?>
                                                    <option value="<?php echo $salaryvalue->id; ?>"><?php echo $salaryvalue->salary_type; ?></option>                         <?php } ?>                                      
                                                   <?php foreach($typevalue as $value): ?>
                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->salary_type; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div> 
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Total Salary</label>
			                                        <input type="text" name="total" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line total" placeholder="total salary" value="<?php if(!empty($salaryvalue->total)) echo $salaryvalue->total ?>" minlength="3" required> 
			                                    </div>
                                                </div>
                                                 
			                                    <h3 class="card-title">Addition</h3>
			                                    <div class="row">
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Basic</label>
			                                        <input type="text" name="basic" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line basic" placeholder="Basic..." value="<?php if(!empty($salaryvalue->basic)) echo $salaryvalue->basic ?>" > 
			                                    </div> 
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>House Rent</label>
			                                        <input type="text" name="houserent" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line houserent" placeholder="medical..." value="<?php if(!empty($salaryvalue->house_rent)) echo $salaryvalue->house_rent ?>" > 
			                                    </div> 
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Medical</label>
			                                        <input type="text" name="medical" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line medical" placeholder="medical..." value="<?php if(!empty($salaryvalue->medical)) echo $salaryvalue->medical ?>" > 
			                                    </div> 
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Conveyance</label>
			                                        <input type="text" name="conveyance" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line conveyance" placeholder="conveyance..." value="<?php if(!empty($salaryvalue->conveyance)) echo $salaryvalue->conveyance ?>" > 
			                                    </div>
                                                </div>
                                                
			                                    <h3 class="card-title">Deduction</h3>
			                                    <div class="row">
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Bima</label>
			                                        <input type="text" name="bima" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="bima..." value="<?php if(!empty($salaryvalue->bima)) echo $salaryvalue->bima ?>"> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Tax</label>
			                                        <input type="text" name="tax" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="tax..." value="<?php if(!empty($salaryvalue->tax)) echo $salaryvalue->tax ?>" > 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Provident Fund</label>
			                                        <input type="text" name="provident" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="Provident..." value="<?php if(!empty($salaryvalue->provident_fund)) echo $salaryvalue->provident_fund ?>"> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Others</label>
			                                        <input type="text" name="others" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="others..." value="<?php if(!empty($salaryvalue->others)) echo $salaryvalue->others ?>"> 
			                                    </div>
                                                </div>
                                                <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>"> 
                                                    <?php if(!empty($salaryvalue->salary_id)){ ?>    
                                                    <input type="hidden" name="sid" value="<?php echo $salaryvalue->salary_id; ?>">                                               <?php } ?> 
                                                    <?php if(!empty($salaryvalue->addi_id)){ ?>    
                                                    <input type="hidden" name="aid" value="<?php echo $salaryvalue->addi_id; ?>">                                                  <?php } ?> 
                                                    <?php if(!empty($salaryvalue->de_id)){ ?>
                                                    <input type="hidden" name="did" value="<?php echo $salaryvalue->de_id; ?>">
                                                    <?php } ?>                                                   
                                                    <button <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> disabled <?php } ?> type="submit" style="float: right" class="btn btn-success">Add Salary</button>
                                                </div>
                                                <?php } ?>
                                            </div>                                                		                                    
			                                    </form>
				                        </div>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
          <script type="text/javascript">
          $('.total').on('input',function() {
            var amount = parseInt($('.total').val());
            $('.basic').val((amount * .50 ? amount * .50 : 0).toFixed(2));
            $('.houserent').val((amount * .40 ? amount * .40 : 0).toFixed(2));
            $('.medical').val((amount * .05 ? amount * .05 : 0).toFixed(2));
            $('.conveyance').val((amount * .05 ? amount * .05 : 0).toFixed(2));
          });
          </script>
<?php $this->load->view('backend/em_modal'); ?>                
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".education").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $('#educationmodal').trigger("reset");
                                                $('#EduModal').modal('show');
                                                $.ajax({
                                                    url: 'educationbyib?id=' + iid,
                                                    method: 'GET',
                                                    data: '',
                                                    dataType: 'json',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
													$('#educationmodal').find('[name="id"]').val(response.educationvalue.id).end();
                                                    $('#educationmodal').find('[name="name"]').val(response.educationvalue.edu_type).end();
                                                    $('#educationmodal').find('[name="institute"]').val(response.educationvalue.institute).end();
                                                    $('#educationmodal').find('[name="result"]').val(response.educationvalue.result).end();
                                                    $('#educationmodal').find('[name="year"]').val(response.educationvalue.year).end();
                                                    $('#educationmodal').find('[name="emid"]').val(response.educationvalue.emp_id).end();
												});
                                            });
                                        });
</script>                
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".experience").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $('#experiencemodal').trigger("reset");
                                                $('#ExpModal').modal('show');
                                                $.ajax({
                                                    url: 'experiencebyib?id=' + iid,
                                                    method: 'GET',
                                                    data: '',
                                                    dataType: 'json',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
													$('#experiencemodal').find('[name="id"]').val(response.expvalue.id).end();
                                                    $('#experiencemodal').find('[name="company_name"]').val(response.expvalue.exp_company).end();
                                                    $('#experiencemodal').find('[name="position_name"]').val(response.expvalue.exp_com_position).end();
                                                    $('#experiencemodal').find('[name="address"]').val(response.expvalue.exp_com_address).end();
                                                    $('#experiencemodal').find('[name="work_duration"]').val(response.expvalue.exp_workduration).end();
                                                    $('#experiencemodal').find('[name="emid"]').val(response.expvalue.emp_id).end();
												});
                                            });
                                        });
</script>                
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".deletexp").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $.ajax({
                                                    url: 'EXPvalueDelet?id=' + iid,
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
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".edudelet").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $.ajax({
                                                    url: 'EduvalueDelet?id=' + iid,
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
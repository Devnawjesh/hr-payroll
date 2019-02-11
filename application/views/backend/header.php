<!DOCTYPE html>
<html lang="en">
<?php
date_default_timezone_set('Asia/Dhaka');
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="GenIT Bangladesh">
    <!-- Favicon icon -->
    <?php $settingsvalue = $this->settings_model->GetSettingsValue(); ?>
    <link rel="icon" type="image/ico" sizes="16x16" href="<?php echo base_url(); ?>assets/images/favicon.ico">
    <title><?php echo $settingsvalue->sitetitle; ?></title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/2.0.46/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/plugins/morrisjs/morris.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url(); ?>assets/css/print.css" rel="stylesheet" media='print'>
    <!-- You can change the theme colors from here -->
    <link href="<?php echo base_url(); ?>assets/css/colors/blue.css" id="theme" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <!-- Daterange picker plugins css -->
    <link href="<?php echo base_url(); ?>assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    
    <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
     <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
    <link href="<?php echo base_url(); ?>assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />   
    <link href="<?php echo base_url(); ?>assets/plugins/calendar/dist/fullcalendar.css" rel="stylesheet" type="text/css" />   
</head>

<body class="fix-header fix-sidebar card-no-border">
        <?php 
            $id = $this->session->userdata('user_login_id');
            $basicinfo = $this->employee_model->GetBasic($id); 
            $settingsvalue = $this->settings_model->GetSettingsValue();
            $year =  date('y');
            $y = substr( $year, -2);
            $date = date("m/d/$y");
    #echo $date;
            $leavetoday = $this->leave_model->GetLeaveToday($date); 
        ?>
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <div id="main-wrapper">
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?php echo base_url(); ?>"><b>
                        <img src="<?php echo base_url();?>assets/images/logo-icon.png" alt="DRI" class="DRI-logo" style="width:50px;"/>
                        </b>
                        <!-- Logo text --><span>
                         <img src="<?php echo base_url(); ?>assets/images/<?php echo $settingsvalue->sitelogo; ?>" alt="homepage" class="dark-logo" height="60px" width="100px" />
                         <!-- Light Logo text -->    
                         </span> </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-message"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu mailbox scale-up-left">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <?php foreach($leavetoday as $value): ?>
                                            <a href="#">
                                                <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                                <div class="mail-contnet">
                                                    <h5><?php echo $value->first_name; ?></h5> <span class="mail-desc"><?php echo $value->reason; ?></span> <span class="time"><?php echo $value->start_date; ?></span> </div>
                                            </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo base_url(); ?>assets/images/users/<?php echo $basicinfo->em_image; ?>" alt="Genit" class="profile-pic" style="height:40px;width:40px;border-radius:50px" /></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-img"><img src="<?php echo base_url(); ?>assets/images/users/<?php echo $basicinfo->em_image; ?>" alt="user"></div>
                                            <div class="u-text">
                                                <h4><?php echo $basicinfo->first_name.' '.$basicinfo->last_name; ?></h4>
                                                <p class="text-muted"><?php echo $basicinfo->em_email ?></p>
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?php echo base_url(); ?>employee/view?I=<?php echo base64_encode($basicinfo->em_id); ?>"><i class="ti-user"></i> My Profile</a></li>
                                    <?php if($this->session->userdata('user_type')!='EMPLOYEE'){ ?>
                                    
                                    <li><a href="<?php echo base_url(); ?>settings/Settings"><i class="ti-settings"></i> Account Setting</a></li>
                                    <?php } ?>
                                    <li><a href="<?php echo base_url(); ?>login/logout"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

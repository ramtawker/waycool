<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Registration-CI Login Registration</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" media="screen"
		title="no title">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/validate-js/2.0.1/validate.js"></script>     -->
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA34s9PgamQPY0xqDwAXCMrpPImuOKcu6M&libraries=places"></script>
        <style>
            .error{
                color: red;
            }
            .gimg {
                width: 160px;
            }
        </style>

</head>

<body>
	<span style="background-color:red;">
		<div class="container">
			<!-- container class is used to centered  the body of the browser with some decent width-->
			<div class="row">
				<!-- row class is used for grid system in Bootstrap-->
				<div class="col-md-4 col-md-offset-4">
					<!--col-md-4 is used to create the no of colums in the grid also use for medimum and large devices-->
					<div class="login-panel panel panel-success">
						<div class="panel-heading">
							<h3 class="panel-title">Registration</h3>
						</div>
						<div class="panel-body">
                            <?php
                                $error_msg=$this->session->flashdata('error_msg');
                                if($error_msg){
                                    echo $error_msg;
                                }
                            ?>
							<form role="form" method="post" name='registration' action="<?php echo base_url('user/register_user');?>">
								<fieldset>
									<div class="form-group">
										<input class="form-control" placeholder="Name" name="user_name" type="text"
											autofocus value="<?= !empty($name) ? $name : '' ?>">
									</div>
									<div class="form-group">
										<input class="form-control" placeholder="E-mail" name="user_email" id='email' type="email"
											autofocus value="<?= !empty($email) ? $email : '' ?>">
									</div>
									<!-- <div class="form-group">
										<input class="form-control" placeholder="Password" id='password' name="user_password"
											type="password" value="">
									</div> -->

									<div class="form-group">
										<input class="form-control" placeholder="Age" name="user_age" id='age' type="number"
											value="">
									</div>

									<div class="form-group">
										<input class="form-control" placeholder="Mobile No" id='mobile' name="user_mobile"
											type="number" maxlength='10' value="">
                                    </div>
                                    
                                    <div class="form-group">
                                        <textarea class="form-control" rows="4" cols="50" placeholder="location" readonly id='loc-inp' name="location">
                                        </textarea>
									</div>

									<input class="btn btn-lg btn-success btn-block" onclick="" type="submit"
										value="Register" name="register">

								</fieldset>
							</form>
							<center><b>Already registered ?</b> <br></b><a
                                    href="<?php echo base_url('user/login_view'); ?>">Login here</a></center>
                                    <hr>
 <span class="signbox"><a href="<?=base_url()?>user/google_login"><img class='gimg' src="<?=base_url()?>assets/images/google-btn.png" alt=""/></a></span>
 <span class="signbox"><a href="<?=$authUrl;?>"><img class='gimg' src="<?=base_url()?>assets/images/flogin.png" alt=""/></a></span>
                                    
							<!--for centered text-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</span>
</body>
<script type="text/javascript" src="<?=base_url()?>assets/js/common.js"></script>
</html>

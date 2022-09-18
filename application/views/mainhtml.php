<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$f = "visit.php";
if(!file_exists($f)){
	touch($f);
	$handle =  fopen($f, "w" ) ;
	fwrite($handle,0) ;
	fclose ($handle);
}
?>

<!DOCTYPE html>
<html lang="en-US">
	<head>
        <title>Quick Response (QR) Code Generator</title>
        <link rel="icon" href="<?php echo base_url();?>assets/img/favicon.ico" type="image/png">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">
        <style>
            .errorWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #dd3d36;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
                box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            }
            .succWrap{
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #5cb85c;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
                box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            }
        </style>
        <script src="<?php echo base_url();?>assets/js/navbarclock.js"></script>
	</head>
	<body onload="startTime()">
		<nav class="navbar-inverse" role="navigation">
			<a href=#>
				<img src="img/niemand.png" class="hederimg">
			</a>
			<div id="clockdate">
				<div class="clockdate-wrapper">
					<div id="clock"></div>
					<div id="date"><?php echo date('l, F j, Y'); ?></div>
				</div>
            </div>
            <div class="pagevisit">
				<div class="visitcount">
					<?php
					$handle = fopen($f, "r");
					$counter = ( int ) fread ($handle,20) ;
					fclose ($handle) ;
					
					if(!isset($_POST["submit"])){
						$counter++ ;
					}
					
					echo "This Page is Visited ".$counter." Times";
					$handle =  fopen($f, "w" ) ;
					fwrite($handle,$counter) ;
					fclose ($handle) ;
					?>
				</div>
			</div>
        </nav>
        <div class="myoutput">
			<div><h3 style="margin-right:50px;"><strong>Bulk QR Code Generator</strong></h3></div>
			
			<center>
			<div class="input-field card card-block">
				<h3>Please Fill-out All Fields</h3>
				<form method="post" action="<?php echo base_url(); ?>welcome/qrcodeFormSubmit" id="qrcode-ajax-form" >
					<div class="form-group">
						<label>Item</label>
						<input type="text" class="form-control" name="item" style="width:20em;" placeholder="Enter your Item Name" value="<?php echo @$item; ?>"  />
					</div>
					<div class="form-group">
						<label>Item Code</label>
						<input type="text" class="form-control" name="itemCode" style="width:20em;" placeholder="Enter your  Item Code" value="<?php echo @$itemCode; ?>"  />
					</div>
					<div class="form-group">
						<label>Quantity</label>
						<input type="number" class="form-control" name="quantity" style="width:20em;" value="<?php echo @$quantity; ?>"  pattern="[0-9]" placeholder="Enter your Quantity" ></textarea>
					</div>
					
					<div class="form-group">
						<input type="submit" name="submit" value="ADD" class="btn btn-primary submitBtn" style="width:20em; margin:0;" />
					</div>
					<div class="form-group">
						<a class="btn btn-primary" style="width:20em; margin:0;" href="<?php echo base_url();?>view?qrcode_item=all">VIEW ALL</a>
					</div>
					<div class="form-group">
						<div class="file btn btn-lg btn-primary file-div">
							Scan QR code
							<input 	type="file"
									class="file-input"
									accept="image/*"
									capture=environment
									onclick="return showQRIntro();"
									onchange="openQRCamera(this);"
									tabindex=-1>
						</div>
					</div>
					<div class="successformdiv"></div>
					<div class="errormessageformdiv"></div>
				</form>
			</div>
			</center>
		</div>
		<script src="<?php echo base_url();?>assets/js/jquery.js"></script>
		<script src="<?php echo base_url();?>assets/js/popper.min.js"></script>
		<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/js/qr_packed.js"></script>
		<script src="<?php echo base_url();?>assets/js/qr_packed.js"></script>
        <script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/myfunction.js"></script>

		<div class="modal fade" id="empdetails" role="dialog">

    <div class="modal-dialog">
        <!-- Modal content-->
        <!-- <div class="modal-content"> -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title"><center>Employee Log Details</center></h3>
            </div>
            <div id="demo-modal" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                    data-dismiss="modal">Close</button>
            </div>
        <!-- </div> -->
    </div>
</div>
    </body>
</html>

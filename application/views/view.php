<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<html>
    <head>
        <title>Bulk QR code Generator</title>
        <link rel="icon" href="<?php echo base_url();?>assets/img/favicon.ico" type="image/png">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">
    </head>
    <body>
        <div style="border:2px solid black;" class="container">
            <h2 style="text-align:center">QR Code Result </h2>
            <center>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Select Item
                    </button>
                    <div class="dropdown-menu" id="itemList" aria-labelledby="dropdownMenuButton">
                    </div>
                    <button class="btn btn-secondary" id="addMoreQRCode" type="button" data-toggle="modal" data-target="#myModal" <?php if($_GET['qrcode_item'] == 'all') echo "disabled" ?>>ADD MORE QRCODE</button>
                    <!-- The Modal -->
                    <div class="modal fade" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">ADD MORE QR CODE</h4>
                            <!-- <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-plus"></span>New Item</button> -->
                            
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="modelBody">

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                        </div>

                        </div>
                    </div>
                    </div>
                </div>
                <div class="row qrcodeList">
                </div>
            </center>
        </div>
        <script src="<?php echo base_url();?>assets/js/jquery.js"></script>
		<script src="<?php echo base_url();?>assets/js/popper.min.js"></script>
		<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/myfunction.js"></script>
    </body>
</html>
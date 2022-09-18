var site_url = 'http://localhost/qrcode_mvc/';
$(document).ready(function(e){
	$('#qrcode-ajax-form').addClass('qrcode-ajax-form'); //backward compatability
	$(document).on('submit', '.qrcode-ajax-form', function(e){
		e.preventDefault();
		var ajax_save_form = $(this);
        var formaction = ajax_save_form.attr('action');
		$('#demo-modal').html('');
        
		jQuery.ajax({
			enctype: 'multipart/form-data',
			url: formaction,
			data: new FormData(this),
			method:'post',
			dataType: 'json', 
			processData: false,
			contentType: false,
			cache: false,
			timeout: 600000,
			
			beforeSend: function() {
				$.LoadingOverlay("show");
			},
			success : function(response) {
				if(response.status) {
					$(':input[type="submit"]').prop('disabled', false);
					if(response.redirect_url)
						window.setTimeout(function(){
							// Move to a new location or you can do something else
							window.location.href = response.redirect_url;
					
						}, 2000);
				} else {   
					$('#demo-modal').html(response.message);
					$('#empdetails').modal({
						show : true
					});
					$(':input[type="submit"]').prop('disabled', false);
				}
				$.LoadingOverlay("hide");
			}
        });
        
    });
});    

$(document).ready(function(){

})
function showQRIntro() {
    return confirm("Use your camera to take a picture of a QR code.");
}
function openQRCamera(node) {
    var reader = new FileReader();
    reader.onload = function() {
        node.value = "";
        qrcode.callback = function(res) {
        if(res instanceof Error) {
            alert("No QR code found. Please make sure the QR code is within the camera's frame and try again.");
        } else {
            console.log(res);
            window.open(res, "_blank");
        }
        };
        qrcode.decode(reader.result);
    };
    reader.readAsDataURL(node.files[0]);
}

$(document).ready(function(){
	(function ($) {
		$.extend({
			uriGet: function () {
			var url_string = location.href;
			var url = new URL(url_string);
			var val = url.searchParams.get(arguments[0]);
			return val;
			}
		});
	})(jQuery);
	$( document ).ready(function() {
		var a = $.uriGet('qrcode_item');
		$.ajax({url: site_url+"getItem?qrcode_item="+a, success: function(result){
			let qrcodes = JSON.parse(result);
			qrcodes.forEach((qrcode)=>{
				$('.qrcodeList').append(`
				<div class="col-md-3 mt-10">
					<div class="qrframe" style="border:2px solid black; width:210px; height:210px;">
							<img src="assets/temp/${qrcode.qrcode_image_url}" style="width:200px; height:200px;"><br>
					</div>
					<div>
						<button class="btn btn-primary submitBtn" onclick="deleteQRCode(${qrcode.id});" style="width:210px; margin:5px 0;">Delete</button>
					</div>
					<div>
						<a class="btn btn-primary submitBtn" style="width:210px; margin:5px 0;" href="${site_url}download?file=${qrcode.qrcode_image_url}">Download QR Code</a>
					</div>
				</div>
				`)
			})
			// add value to modal like 
			$('#modelBody').append(`
				<div class="form-group">
					<label>Item</label>
					<input type="text" class="form-control" name="item" id="item" style="width:20em;" placeholder="Enter your Item Name" value="${qrcodes[0].item_name}" disabled/>
				</div>
				<div class="form-group">
					<label>Item Code</label>
					<input type="text" class="form-control" name="itemCode" id="itemCode" style="width:20em;" placeholder="Enter your  Item Code" value="${qrcodes[0].item_code}" disabled/>
				</div>
				<div class="form-group">
					<label>Quantity</label>
					<input type="number" class="form-control" name="quantity" id="quantity" style="width:20em;" value="" required placeholder="Enter your Quantity"></textarea>
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-primary" value="${qrcodes[0].qrcode_id}" onclick="addMoreQRCode(this);" style="width:20em; margin:0;" id="addMore">ADD</button>
					<a href="index.php"><button type="button" class="btn btn-primary" style="width:20em; margin:0px; margin-top:10px;"><span class="glyphicon glyphicon-plus"></span>New Item</button></a>
				</div>
			`)
		}});
		$('#dropdownMenuButton').text(a);
		$.ajax({url: "getItemName", success: function(result){
			let itemList = JSON.parse(result);
			$('#itemList').append(`
				<a class="dropdown-item" href="view?qrcode_item=all">All</a>
			`)
			itemList.forEach((item)=>{
				$('#itemList').append(`
					<a class="dropdown-item" href="view?qrcode_item=${item}">${item}</a>
				`)
			})
		}})
	});
	$('.dropdown-menu').on('click', function(){
		console.log($(this).text())
		$('#dropdownMenuButton').text($(this).text());
	});
})

function deleteQRCode(id){
	console.log(id);
	$.ajax({url: "delete?id="+id, success: function(result){
		alert(result);
		location.reload(true);
	}})
}

function addMoreQRCode(e){
	console.log(e.value);
	const id = e.value;
	const item = $("#item").val();
	const itemCode = $("#itemCode").val();
	const quantity = $("#quantity").val();
	if(quantity == ''){
		alert("Choose number of Item");
	}else if(item == ''){
		alert("Item Field is Empty!!");
	}else if(itemCode == ''){
		alert("Item Field is Empty!!");
	}else if(itemCode == ''){
		alert("Something went wrong! Please Refresh the page!");
	}else{
		console.log($("#item").val());
		console.log($("#itemCode").val());
		console.log($("#quantity").val());
		$.ajax({url: `addMoreItem?qrcode_id=${id}&item=${item}&itemCode=${itemCode}&quantity=${quantity}`, success: function(result){
			location.reload(true);
		}})
	}
	
}
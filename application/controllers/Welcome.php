<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	
	function __construct ()
	{	
		parent::__construct();
		$this->load->library(array('form_validation','phpqrcode/qrlib','email'));
		$this->load->model('Qrcode_model','qrcode');
		$this->load->helper(array('security','url','form'));
	}

	public function index(){
		$this->load->view('mainhtml');
	}

	public function qrcodeFormSubmit(){
		$this->form_validation->set_rules('item', 'Item', 'required|xss_clean');
        $this->form_validation->set_rules('itemCode', 'Item Code', 'required|xss_clean');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|xss_clean|integer');
        	
		if ($this->form_validation->run() === FALSE) {
			echo json_encode(array(
				'status' => false,
				'message' => '<div class="alert alert-danger" style="text-align:center;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' . validation_errors() . '</div>'
			));
		}else{
			$item      	  = $this->security->xss_clean($this->input->post('item'));
			$itemCode     = $this->security->xss_clean($this->input->post('itemCode'));
			$quantity     = $this->security->xss_clean($this->input->post('quantity'));
			$check 		  =	$this->qrcode->checkItemExist($item);
			if($check){
				echo json_encode(array(
					'status' => false,
					'message' => '<div class="alert alert-danger" style="text-align:center;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Item already exist!Please choose one.</div>'
				));
			}else{
				$saveItem = $this->qrcode->saveItemName($item);
				if($saveItem){
					$tempDir = 'assets/temp/';
					for($i = 1; $i<=$quantity; $i++){
						$milliseconds = round(microtime(true) * 1000);
						$filename = $milliseconds .'_'. $item;
						$params = array(
							'qrcode_id'			=>	$saveItem,
							'item_code' 		=>  $itemCode,
							'qrcode_image_url'  =>	$filename.'.png'
						);
						$saveqrcode = $this->qrcode->saveQrCodeDetails($params);
						if($saveqrcode){
							$codeContents = base_url().'scan?item='.$item.'&itemCode='.$itemCode.'_'.$saveqrcode.'&id='.$saveqrcode;
							QRcode::png($codeContents, $tempDir.''.$filename.'.png', QR_ECLEVEL_L, 5);
						}
					}
					echo json_encode(array(
						'status' => true,
						'message' =>'<div class= "alert alert-success"><b>Thank you. Our team will get back to you soon</b></div>' , 
						'redirect_url' => base_url().'view?qrcode_item='.$item
				
					));
				}else{
					echo json_encode(array(
						'status' => false,
						'message' =>'<div class="alert alert-danger" style="text-align:center;">Error : <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong. Please try Again!! </div> ' , 
						'redirect_url' => base_url()
				
					));
				}
				
			}

		}
	}

	public function view(){
		$filename = $this->input->get('qrcode_item');
		if(!isset($filename)){
			redirect(base_url('pageNotFound'), 'auto');
		}else{
			$data['qrcode_item'] =	$filename;
			$this->load->view('view',$data);
		}
	}

	public function getItem(){
		$item = $this->input->get('qrcode_item');
		$get_data = $this->qrcode->getQrCode($item);
		if(!empty($get_data)){
			echo json_encode($get_data);
		}
	}

	public function getItemName(){
		$get_data = $this->qrcode->getItemName();
		if(!empty($get_data)){
			echo json_encode($get_data);
		}
	}

	public function addMoreItem(){
		$qrcode_lastId = $this->input->get('qrcode_id');
		$item = $this->input->get('item');
		$itemCode = $this->input->get('itemCode');
		$quantity = $this->input->get('quantity');
		$tempDir = 'assets/temp/';
		if(!is_numeric($quantity)){
			echo "Quantity should be an Integer!!";
		}else{
			for($i = 1; $i<=$quantity; $i++){
				$milliseconds = round(microtime(true) * 1000);
				$filename = $milliseconds .'_'. $item;
				$params = array(
					'qrcode_id'			=>	$qrcode_lastId,
					'item_code' 		=>  $itemCode,
					'qrcode_image_url'  =>	$filename.'.png'
				);
				$saveqrcode = $this->qrcode->saveQrCodeDetails($params);
				if($saveqrcode){
					$codeContents = base_url().'scan?item='.$item.'&itemCode='.$itemCode.'_'.$saveqrcode.'&id='.$saveqrcode;
					QRcode::png($codeContents, $tempDir.''.$filename.'.png', QR_ECLEVEL_L, 5);
				}

			}
		}
	}

	public function delete(){
		$id = $this->input->get('id');
		$delete = $this->qrcode->deleteQrCode($id);
		if($delete){
			echo "Deleted Successfully";
		}else{
			echo "Something went wrong!!";
		}
	}

	public function download(){
		$fileName = basename($this->input->get('file'));
		$filePath = 'assets/temp/'.$fileName;
		if(!empty($fileName) && file_exists($filePath)){
			header('Content-Length: ' . filesize($filePath));  
			header('Content-Encoding: none');
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=$fileName");
			header("Content-Type: application/zip");
			header("Content-Transfer-Encoding: binary");
			
			// Read the file
			readfile($filePath);
        	exit;
		}else{
			echo 'The File '.$fileName.' does not exist.';
		}

	}

	public function pageNotFound(){
		$this->load->view('pageNotFound');
	}
}

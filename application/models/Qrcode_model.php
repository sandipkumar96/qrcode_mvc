<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Qrcode_model extends CI_Model {

	 public function __construct() {
        parent::__construct();
        $this->qrcode       = "qrcode";
        $this->bulk_qrcode       = "bulk_qrcode";
    }

    public function checkItemExist($item){
        $result = false;
        $this->db->select('*');
        $this->db->from($this->qrcode);
        $this->db->where('item_name', $item);
        $query  = $this->db->get();
        if($query->num_rows() > 0){
            $result = true;
        }
        return $result;
    }

    public function getQrCode($item){
        $result = array();
        $this->db->select('*');
        $this->db->from("{$this->qrcode} qr");
        $this->db->join("{$this->bulk_qrcode} bqr","qr.id = bqr.qrcode_id");
        if($item != 'all'){
            $this->db->where('qr.item_name', $item);
        }
        $query  = $this->db->get();
        if($query){
            $result = $query->result_array();
        }
        return $result;
    }

    public function saveItemName($item){
        $this->db->query("INSERT into qrcode(item_name) values('".$item."')");
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function saveQrCodeDetails($data){
        $this->db->insert("{$this->bulk_qrcode}",$data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function getItemName(){
        $result = array();
        $this->db->select('item_name');
        $this->db->from("{$this->qrcode} qr");
        $query  = $this->db->get();
        if($query){
            $data = $query->result_array();
            foreach($data as $index){
                $result[] = $index['item_name'];
            }
        }
        return $result;
    }

    public function deleteQrCode($id){
        $result = false;
        $this->db->where('id', $id);
        $this->db->delete("{$this->bulk_qrcode}");
        $check = $this->db->affected_rows();
        if($check){
            $result = true;
        }
        return $result;
    }

}
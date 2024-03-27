<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_kendaraan extends CI_Model{
	
	function getdata(){
		$this->load->library('datatables');
		$this->datatables->select('*');
		$this->datatables->from('kendaraan');
		return $this->datatables->generate();
	}

	function getapi(){
		$this->db->select('id_kendaraan');
		$this->db->where('aktivasi',0);
		$dbdata = $this->db->get('kendaraan')->result();
		$dbdata = json_encode($dbdata);
		return $dbdata;	
	}

	function getapiwhere($param){
		$this->db->select('*');
		$this->db->where($param);
		$data = $this->db->get('kendaraan')->result();
		$data = json_encode($data);
		return $data;
	}

}

?>
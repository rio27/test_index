<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit extends CI_Controller{
	function __construct(){
		parent::__construct();

		if(!isset($_SESSION['fuel'])){
			redirect('login');
		}
		
		if($_SESSION['fuel']['level']<>'audit'){
			echo 'access restricted!';exit;
		}
	}

	function log($id,$type,$table=null,$field=null){

			$this->db->where($field,$id);
			$log = $this->db->get($table)->row();
			$log->log_user = $this->session->username;
			$log->log_type = $type;

			if($table=='trans_tank')
				$hasil = $this->db->insert('log_fuel_pengisian',$log); 
			else
			if($table=='trans_induk')
				$hasil = $this->db->insert('log_trans_induk',$log); 
			else
			if($table=='drain')
				$hasil = $this->db->insert('log_drain',$log);
			else
			if($table=='meteransolar')
				$hasil = $this->db->insert('log_meteransolar',$log); 
			else
				$hasil = $this->db->insert('log_data_meteran',$log); 

			if(!$hasil){
				$data = array(
					'header'=>'Penghapusan data gagal',
					'pesan'=>'Data gagal dihapus, refresh halaman atau hubungi admin',
					'type'=>'error'
					);
				$data = json_encode($data);
				echo $data;		
				exit;	
			}
							
	}
	



	function tambah(){

	}

	function edit(){

	}

	function detail_pengisian($id_tank,$periode){
		$this->load->model('M_meteransolar');
		$data['param']=array('id_tank'=>$id_tank,'periode'=>$periode);
		$data['details']=$this->M_meteransolar->get_data($id_tank,$periode);
		$data['content']=array('pengisian/filter','pengisian/view_audit_detail_pengisian');
		$this->load->view('template',$data);
	}

	function update_transaksi(){
		$field = $this->input->post('field');
		$value = $this->input->post('input');
		$data = array(
			$field=>$value
		);
		$id = $this->input->post('id');
		
		$this->log($id,'update','trans_tank','id_no');		
		
		$this->db->where('id_no',$id);
		$datas = $this->db->update('trans_tank',$data);
		if($datas){
				$data = array(
					'header'=>'Update data berhasil',
					'pesan'=>'Berhasil, data berhasil diupdate',
					'type'=>'success'
					);

		}else{
				$data = array(
					'header'=>'Update data Gagal',
					'pesan'=>'Gagal, data Gagal diupdate',
					'type'=>'error'
					);
		}		
				$data = json_encode($data);
				echo $data;	
	}

	function update_terimasolar(){
		$field = $this->input->post('field');
		$value = $this->input->post('input');
		$data = array(
			$field=>$value
		);
		$id = $this->input->post('id');

		$this->log($id,'update','trans_induk','id_no');
		
		$this->db->where('id_no',$id);
		$datas = $this->db->update('trans_induk',$data);
		if($datas){
				$data = array(
					'header'=>'Update data berhasil',
					'pesan'=>'Berhasil, data berhasil diupdate',
					'type'=>'success'
					);

		}else{
				$data = array(
					'header'=>'Update data Gagal',
					'pesan'=>'Gagal, data Gagal diupdate',
					'type'=>'error'
					);
		}		
				$data = json_encode($data);
				echo $data;	
	}

	function update_drain_unit(){
		$field = $this->input->post('field');
		$value = $this->input->post('input');
		$data = array(
			$field=>$value
		);
		$id = $this->input->post('id');
		
		$this->log($id,'update','drain','id');		

		$this->db->where('id',$id);
		$datas = $this->db->update('drain',$data);
		if($datas){
				$data = array(
					'header'=>'Update data berhasil',
					'pesan'=>'Berhasil, data berhasil diupdate',
					'type'=>'success'
					);

		}else{
				$data = array(
					'header'=>'Update data Gagal',
					'pesan'=>'Gagal, data Gagal diupdate',
					'type'=>'error'
					);
		}		
				$data = json_encode($data);
				echo $data;		
	}

	function update_meteran(){
		$field = $this->input->post('field');
		$value = $this->input->post('input');
		$data = array(
			$field=>$value
		);
		$id = $this->input->post('id');
		$this->log($id,'update','data_meteran','id');

		$this->db->where('id',$id);
		$datas = $this->db->update('data_meteran',$data);
		if($datas){
				$data = array(
					'header'=>'Update data berhasil',
					'pesan'=>'Berhasil, data berhasil diupdate',
					'type'=>'success'
					);

		}else{
				$data = array(
					'header'=>'Update data Gagal',
					'pesan'=>'Gagal, data Gagal diupdate',
					'type'=>'error'
					);
		}		
				$data = json_encode($data);
				echo $data;	
	}

	function update_periode(){
		$field = $this->input->post('field');
		$value = $this->input->post('input');
		$data = array(
			$field=>$value
		);
		$id = $this->input->post('id');
		$this->log($id,'update','meteransolar','id');	

		$this->db->where('id',$id);
		$datas = $this->db->update('meteransolar',$data);
		if($datas){
				$data = array(
					'header'=>'Update data berhasil',
					'pesan'=>'Berhasil, data berhasil diupdate',
					'type'=>'success'
					);

		}else{
				$data = array(
					'header'=>'Update data Gagal',
					'pesan'=>'Gagal, data Gagal diupdate',
					'type'=>'error'
					);
		}		
				$data = json_encode($data);
				echo $data;	
	}

	function aktivasi($table=null,$param=null,$val=null,$id=null,$user=null){
		$data = array(
			'audit'=> $val,
			'user_audit'=>$user
			);

		$this->db->where($param,$id);
		$data = $this->db->update($table,$data);

		if($data){
				$data = array(
					'header'=>'Update data berhasil',
					'pesan'=>'Berhasil, data berhasil diupdate',
					'type'=>'success'
					);

		}else{
				$data = array(
					'header'=>'Update data Gagal',
					'pesan'=>'Gagal, data Gagal diupdate',
					'type'=>'error'
					);
		}		
				$data = json_encode($data);
				echo $data;	
	}

	function mark_error($table=null,$param=null,$val=null,$id=null,$user=null)
	{
		$data = array(
			'error'=> $val,
			'user_error'=>$user
		);
		
		$this->db->where($param,$id);
		$data = $this->db->update($table,$data);

		if($data){
				$data = array(
					'header'=>'Update data berhasil',
					'pesan'=>'Berhasil, data berhasil diupdate',
					'type'=>'success'
					);

		}else{
				$data = array(
					'header'=>'Update data Gagal',
					'pesan'=>'Gagal, data Gagal diupdate',
					'type'=>'error'
					);
		}		
				$data = json_encode($data);
				echo $data;	
	}
}

?>
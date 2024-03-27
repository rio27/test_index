<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kendaraan extends CI_Controller{
	function __construct(){
		parent::__construct();
		if(empty($_SESSION['login'])){
			redirect('login/');
		}
		// $this->load->model('Mainmodel');
	}

	function api(){
		header('Content-Type:application/json');
		$this->load->model('M_kendaraan');
		$result = $this->M_kendaraan->getapi();
		echo $result;
	}

	function getwhere(){
		$this->load->model('M_kendaraan');
		if(empty($_POST['id'])){
			show_404();
		}

		header('Content-Type:application/json');		
		$id=$_POST['id'];
		$param = array('id_kendaraan'=>$id);
		$data = $this->M_kendaraan->getapiwhere($param);
		echo $data;		
	}

	function detail($id){
		$data['id']=$id;
		$data['level']='operator';
		$data['title']='Detail Kendaraan';
		$data['content']=array('data_kendaraan/detail_kendaraan','data_perbaikan/view_data_perbaikan');
		$this->load->view('template/template.php',$data);
	}

	function select2(){
		if(isset($_GET['q'])){
		$q = $_GET['q'];
		}else{
		$q = 'a';
		}
		$param = array('nama'=>$q,'nidn'=>$q);	
		$data = $this->M_select2->getdata('kendaraan','id_kendaraan,no_lambung','nama','nama',$param,10);
		echo $data;			
	}

	function tambah(){
		header('Content-Type:application/json');
		if($this->input->post('no_unit')!=null){
				$id = 'BDM-'.$_POST['jenis'].'-'.$_POST['no_unit'];
				$data=array(
					'id_kendaraan'=>$id,
					'no_lambung'=>$id,
					'nomor_polisi'=>$this->input->post('nomor_polisi'),
					'tahun_pembuatan' => $this->input->post('tahun_pembuatan'),
					'pajak' => $this->input->post('pajak'),
					'no_unit'=>$this->input->post('no_unit'),
					'merk'=>$this->input->post('merk'),
					'klasifikasi'=>$this->input->post('klasifikasi'),
					'jenis'=>$this->input->post('jenis'),
					'nomor_rangka'=>$this->input->post('nomor_rangka'),
					'nomor_mesin'=>$this->input->post('nomor_mesin'),
					'bahan_bakar'=>$this->input->post('bahanbakar'),
					'warna'=>$this->input->post('warna'),
					'status'=>$this->input->post('status')
					);
				$data = $this->db->insert('kendaraan',$data);
			 if($data){
			 	$data = array('pesan'=>2);
			 }else{
			 	$data = array('pesan'=>3);
			 }

		}else{
			 	$data = array('pesan'=>4);
		}	

		$data = json_encode($data);
		echo $data;	
	}



	function hapus($id){
		if($this->session->login==null){
			$data = array(
				'header'=>'Penghapusan data dibatalkan!',
				'pesan'=>'Data gagal dihapus anda belum login',
				'type'=>'error'
				);
		}else
		if($id==null){
			$data = array(
				'header'=>'Penghapusan data dibatalkan!',
				'pesan'=>'Data gagal dihapus tidak ada data terpilih, hubungi admin',
				'type'=>'error'
				);
		}else
		if($this->session->admin==null){
			$data = array(
				'header'=>'Penghapusan data ditabalkan!',
				'pesan'=>'Hanya admin yang dapat melakukan penghapusan',
				'type'=>'error'
			);		
		}else
		{
			$this->db->where('id_kendaraan',$id);
			$data = $this->db->delete('kendaraan');
			if($data){
				$data = array(
					'header'=>'Penghapusan berhasil!',
					'pesan'=>'Data berhasil dihapus',
					'type'=>'success',
					'success'=>1,
				);
			}else{
			$data = array(
				'header'=>'Penghapusan data gagal',
				'pesan'=>'Data gagal dihapus, refresh halaman atau hubungi admin',
				'type'=>'error'
				);
			}			
		}

			header('Content-Type:application/json');
			$data = json_encode($data);
			echo $data;		
	}

	function getjson(){
		$this->load->model('M_kendaraan');

		header('Content-Type:application/json');
		$data = $this->M_kendaraan->getdata();
		echo $data;
	}

	function ganti_foto(){
		header('Content-Type:application/json');

		if($this->input->post('trigger')!=null){

			$config['upload_path']='./files/unit/';
			$config['allowed_types']='jpg|jpeg|png';
			$this->load->library('upload',$config);
			if(!$this->upload->do_upload('foto')){
			 	$data = array('pesan'=>1);
			 	$data = json_encode($data);
			 	echo $data;
				exit;
			}else{
				$foto = $this->upload->data();
			}

				$data=array(
					'foto'=>$foto['file_name']
					);
				$this->db->where('id_kendaraan',$this->input->post('id_kendaraan'));
				$data = $this->db->update('kendaraan',$data);

			 if($data){
			 	$data = array('foto'=>$foto['file_name'],'pesan'=>2);

			 }else{
			 	$data = array('pesan'=>3);

			 }

		}else{
			 	$data = array('pesan'=>4);

		}

		$data = json_encode($data);
		echo $data;
	}
}

?>
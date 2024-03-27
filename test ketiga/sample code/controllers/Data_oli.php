<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_oli extends CI_Controller{
	function __construct(){
		parent::__construct();
		if($_SESSION['level']<>'oli'){
			redirect('login/');
		}
	}


	function getCell($worksheet,$index,$row){
		return $worksheet->getCellByColumnAndRow($index, $row)->getValue();
	}

	function import(){

		$this->load->library('excel');
		header('Content-type:application/json');
		if(isset($_FILES["file"]["name"]))
		{	

			$error=0;
			$path = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();

				for($row=2; $row<=$highestRow; $row++)
				{
					$tanggal 		= $this->getCell($worksheet,0,$row);
					$jenis_oli 		= $this->getCell($worksheet,1,$row);
					$merk 			= $this->getCell($worksheet,2,$row);
					$qty 			= $this->getCell($worksheet,3,$row);
					$jenis_pemakaian= $this->getCell($worksheet,4,$row);
				// $jenis_kendaraan= $this->getCell($worksheet,5,$row);
					$type 			= $this->getCell($worksheet,6,$row);
					$no_unit		= $this->getCell($worksheet,7,$row);
					$kontraktor		= 'BDM';
					$id_kendaraan 	= $kontraktor.'-'.$type.'-'.$no_unit;
					$hm 			= $this->getCell($worksheet,8,$row);
					$lokasi_kerja 	= $this->getCell($worksheet,9,$row);
					$penggunaan 	= $this->getCell($worksheet,10,$row);
					$user			= $this->getCell($worksheet,11,$row);
					$keterangan		= $this->getCell($worksheet,12,$row);
					$result = array(
						'tanggal'			=> $tanggal,
						'jenis_oli'			=> $jenis_oli,
						'id_kendaraan'		=> $id_kendaraan,
						'kontraktor' 		=> $kontraktor,
						'jenis_kendaraan'	=> $type,
						'no_unit'			=> $no_unit,
						'merk'				=> $merk,
						'qty' 				=> $qty,
						'jenis_penggunaan'	=> $penggunaan,
						'jenis_pemakaian' 	=> $jenis_pemakaian,
						'hm'				=> $hm,
						'lokasi_kerja'		=> $lokasi_kerja,
						'keterangan' 		=> $keterangan,
						'user'			 	=> $user
					);

					$data[] = $result;

					$tanggal = date('Y-m-d',strtotime($tanggal));
					$tanggal_sekarang = date('Y-m-d');
					if($tanggal>$tanggal_sekarang){
			 			$data = array(
							'caption'=>'Gagal!',
							'isi'=>'Terdapat periode yang yang melebihi hari ini',
							'type'=>'info',
							'alert'=>'swal',
							'status'=>'info'
						);
						$error++;
					}

					if($tanggal=='0000-00-00' || $tanggal=='00-00-0000'){
			 			$data = array(
							'caption'=>'Gagal!',
							'isi'=>'Terdapat periode kosong (0000-00-00)',
							'type'=>'info',
							'alert'=>'swal',
							'status'=>'info'
						);	
						$error++;	
					}

					$this->db->where($result);
					$this->db->limit(1);
					$r = $this->db->get('oli')->row();
					if($r){
			 			$data = array(
							'caption'=>'Gagal!',
							'isi'=>'Terdapat data yang telah ada dalam system, periksa kembali data yang ingin diupload',
							'type'=>'info',
							'alert'=>'swal',
							'status'=>'info'
						);	
						$error++;
					}

					if($error>0){
						$data = json_encode($data);
						echo $data;						
						exit;
					}
				}
			}

				$result = $this->db->insert_batch('oli',$data);

				if($result){

		 			$data = array(
						'caption'=>'Berhasil!',
						'isi'=>'Data berhasil diupload',
						'type'=>'success',
						'alert'=>'swal',
						'status'=>'success'
					);
				}else{
		 			$data = array(
						'caption'=>'Gagal!',
						'isi'=>'Data gagal diupload',
						'type'=>'error',
						'alert'=>'swal',
						'status'=>'error'
					);
				}
			
		}

			$data = json_encode($data);
			echo $data;	
	}		

}

?>
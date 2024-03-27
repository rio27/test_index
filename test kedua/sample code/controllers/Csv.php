<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csv extends CI_Controller{
	function __construct(){
		parent::__construct();
		if(!isset($_SESSION['fuel'])){
			redirect('login');
		}
		
		if($_SESSION['fuel']['level']<>'audit'){
			echo 'access restricted!';exit;
		}
	}

	function validasi_no_unit($no_unit){
		if($no_unit==1 || $no_unit=='1')
			$no_unit = '01';
		else
		if($no_unit==2 || $no_unit=='2')
			$no_unit='02';
		else
		if($no_unit==3 || $no_unit=='3')
			$no_unit='03';
		else
		if($no_unit==4 || $no_unit=='4')
			$no_unit='04';
		else
		if($no_unit==5 || $no_unit=='5')
			$no_unit='05';
		else
		if($no_unit==6 || $no_unit=='6')
			$no_unit='06';
		else
		if($no_unit==7 || $no_unit=='7')
			$no_unit='07';
		else
		if($no_unit==8 || $no_unit=='8')
			$no_unit='08';
		else
		if($no_unit==9 || $no_unit=='9')
			$no_unit='09';

		return $no_unit; 
	}
	
	function upload(){	
		header('Content-Type:application/json');
		$this->load->model('M_csv');
		$this->load->library('upload');		

		$starting_point=0;
		$count_csv = count($_FILES['csv']['name']);
		while($count_csv>$starting_point){
			//START UPLOADING
			if($_FILES['csv']['name'][$starting_point]!=null){
				$nama_file = explode("_",$_FILES['csv']['name'][$starting_point]);
				$file_name = $_FILES['csv']['name'][$starting_point];
				$tmp = $_FILES['csv']['tmp_name'][$starting_point];
				$count = count($nama_file);

				$_FILES['uploaded_file']['name']	=	$_FILES['csv']['name'][$starting_point];
				$_FILES['uploaded_file']['type']	=	$_FILES['csv']['type'][$starting_point];
				$_FILES['uploaded_file']['tmp_name']= 	$_FILES['csv']['tmp_name'][$starting_point];
				$_FILES['uploaded_file']['error']	= 	$_FILES['csv']['error'][$starting_point];
				$_FILES['uploaded_file']['size']	= 	$_FILES['csv']['size'][$starting_point];
				// print_r($_FILES['uploaded_file']);
				//pengecekan format nama file
				if($count<3){
					$message[] = array(
						'type'=>'error',
						'pesan'=>'Nama File yang anda upload tidak sesuai dengan format hubungi ADMIN IT'
					);

				}else{

					$tank  = $nama_file[0];
					$jenis = $nama_file[1];
					$periode = $nama_file[2];

					$verifikasi = $this->cek_data($file_name,$tank,$jenis,$periode);
					
					if($verifikasi==TRUE){
						$c_data_csv['upload_path']='./files/csv/';
						$c_data_csv['allowed_types']='csv';
						$this->upload->initialize($c_data_csv);
						if(!$this->upload->do_upload('uploaded_file')){
							$message[] = array(
								'type'=>'error',
								'pesan'=>'File Gagal Diupload, Pastikan type file yang anda upload berupa CSV'
							);
						}else{
							$rar = $this->upload->data();


							$i=0;
							$ii=0;
							$success=0;
							$fail=0;
					        $file = fopen($tmp, "r");

					        //penginputan data berdasarkan jenis csv
					        if($jenis=='drain'){
						        while (($column = fgetcsv($file, 10000, ',','"')) !== FALSE){

						        	$i++;
						            if(isset($column[0])){
						        	$result = $this->M_csv->insert_data_drain($column);			   

						                if ($result) {
						                	$success++;
						                } else {
						                	$fail++;
						                }
						            }
						        }
									$message[] = array(
										'type'=>'success',
										'pesan'=>'Jumlah keseluruhan <b>Data Drain ('.$file_name.') </b> '.$i.' yang berhasil terinput: '.$success.', dan yang gagal terupload: '.$fail
									);								        
					        }else
							if($jenis=='transaksi'){
						        while (($column = fgetcsv($file, 10000, ',','"')) !== FALSE){
						        	$i++;
						            if(isset($column[0])){
						            	$data = $this->crosscheck_data_transaksi($column);
						                if ($data['result']) {
						                	$success++;

						                	// penginputan data ke table gabungan hm dan bbm
						            		//$result = $this->insert_data_gabungan($data['data_gabungan']);
						                	$result =1;
						                	if($result){
						                		$ii++;
						                	}else{
						                		$ii--;
						                	}					                	
						                } else {
						                	$fail++;
						                }
						            }
						        }		
									$message[] = array(
										'type'=>'success',
										'pesan'=>'Jumlah keseluruhan <b>data transaksi ('.$file_name.') </b> yang berhasil terinput: '.$success.', sedangkan yang gagal terupload: '.$fail.'. Dan jumlah data yang berhasil terjoin adalah '.$ii
									);							 				        
					        }else
					        if($jenis=='meteran'){
						        while (($column = fgetcsv($file, 10000, ',','"')) !== FALSE){
						        	$i++;
						            if(isset($column[0])){

						            	$result = $this->M_csv->insert_data_meteran($column);
			                
						                
						                if ($result) {
						                	$success++;
						                } else {
						                	$fail++;
						                }

						            }
						        }
									$message[] = array(
										'type'=>'success',
										'pesan'=>'Jumlah keseluruhan <b>Data Meteran ('.$file_name.') </b> '.$i.' yang berhasil terinput: '.$success.', dan yang gagal terupload: '.$fail
									);								        
					        }else
					        if($jenis=='terimasolar'){
						        while (($column = fgetcsv($file, 10000, ',','"')) !== FALSE){
						        	$i++;
						            if(isset($column[0])){
						            	$result = $this->M_csv->insert_data_terimasolar($column);
							          
						                if ($result) {
						                	$success++;
						                } else {
						                	$fail++;
						                }
						            }
						        }


									$message[] = array(
										'type'=>'success',
										'pesan'=>'Jumlah keseluruhan <b>Data Terima Solar ('.$file_name.') </b> yang berhasil terinput: '.$success.', dan yang gagal terupload: '.$fail
									);								 	

					        }else
					        if($jenis=='ritase'){
						        while (($column = fgetcsv($file, 10000, ',','"')) !== FALSE){
						        	$i++;
						            if(isset($column[0])){
						            	$result = $this->M_csv->insert_data_ritase($column);

						                if($result){
						                	$success++;
						                } else {
						                	$fail++;
						                }
						            }
						        }			        
									$message[] = array(
										'type'=>'success',
										'pesan'=>'Jumlah keseluruhan <b>Data Ritase ('.$file_name.') </b> '.$i.' yang berhasil terinput: '.$success.', dan yang gagal terupload: '.$fail
									);	
					        }else
					        if($jenis=='meteransolar')
					        {
					        //jenis = meteransolar
						        while (($column = fgetcsv($file, 10000, ',','"')) !== FALSE){
						        	$i++;
						            if(isset($column[0])){
						            	$result = $this->M_csv->insert_data_meteransolar($column);	               							                
						                if ($result) {
											$message[] = array(
												'type'=>'success',
												'pesan'=>'Data <b>Pemakaian Solar('.$file_name.')</b> Berhasil ditambahkan'
											);	
						                } else {
											$message[] = array(
												'type'=>'error',
												'pesan'=>'Data <b>Pemakaian Solar ('.$file_name.')</b> Gagal ditambahkan'
											);		
						                }
						            }
						        }			        
					        }else{
											$message[] = array(
												'type'=>'error',
												'pesan'=>'File Tidak Teridentifikasi, Data Gagal ditambahkan!'
											);						        	
					        }	

							$data = array(
								'nama_file'=>$file_name,
								'tanggal'=>$periode,
								'user'=>$this->session->username,
								'tank'=>$tank,
								'jenis'=>$jenis
							);

							$result = $this->db->insert('log_import',$data);
							//end here
						}
					}else{
						$message[] = array(
							'type'=>'info',
							'pesan'=>'File dengan nama CSV ('.$file_name.') ini telah terupload sebelumnya'
						);						
								 
					}

				}

			}
			//END UPLOADING
			$starting_point++;
		}

		$message = json_encode($message);
		echo $message;

	}



	function crosscheck_data_transaksi($column){
 		$id_tank = preg_replace('/\s+/', '', $column[6]);
		$tanggal= date('Y-m-d',strtotime($column[1]));
		$timestamp = date('Y-m-d h:i:s',strtotime($column[16]));
		//menginisialise variable data;
		$column[5] = $this->validasi_no_unit($column[5]);
		
    	$data = array(
    		'datetime'=>$tanggal,
    		'id_vehicle'=>$column[2],
    		'kontraktor'=>$column[3],
    		'jenis'=>$column[4],
    		'no_unit'=>$column[5],
    		'id_tank'=>$id_tank,
    		'driver'=>$column[9],
    		'operator'=>$column[10],
    		'qty'=>$column[11],
    		'qty_terisi'=>$column[12],
    		'user'=>$column[13],
    		'keterangan'=>$column[14],
    		'lokasi_k'=>$column[20],
    		'kategori'=>$column[21],
    		'timestamp'=>$timestamp,
    		'pit'=>$column[19],
    		'qrcode'=>$column[22],
    		'grup_merk'=>$column[25]
    	);

    	$data_gabungan = array(
    		'datetime'=>$tanggal,
    		'id_vehicle'=>$column[2],
    		'kontraktor'=>$column[3],
    		'jenis'=>$column[4],
    		'no_unit'=>$column[5],
    		'id_tank'=>$id_tank,
    		'qty'=>$column[11],
    		'qty_terisi'=>$column[12]
    	);

    	//datetime dibutuhkan dalam proses crosscheck, datetime digunakan untuk mengambil data sebelum periode tersebut
    	//agar jika audit lupa mengupload data pada periode tertentu, maka ketika data tsb. 
    	//diupload, maka data yang diambil adalah data sebelum periode tersebut, bukan data
    	//periode setelahnya
    	$cek_data_sebelum = $this->cek_data_sebelum_periode($column[2],$column[3],$column[4],$column[5],$tanggal);

    	$cek_data_sama_periode = $this->cek_data_sama_periode($column[2],$column[3],$column[4],$column[5],$tanggal);




    	if($cek_data_sebelum>0){
    		$get_data_sebelum = $this->get_data_sebelum_periode($column[2],$column[3],$column[4],$column[5],$tanggal);
    			$data_periode_sebelum =  $get_data_sebelum->km_pengisian;
    			$km_sekarang= $column[8];
    			$periodex=0;
    			$periodey=0;
    		if($cek_data_sama_periode>0){
    			$get_data_periode = $this->get_data_sama_periode($column[2],$column[3],$column[4],$column[5],$tanggal);


    			$ix=0;
    			$nx = 0;
    			$km_first = 0;
		
    			foreach($get_data_periode as $data_periode_x){
    				$get_data_periode[$ix] = $data_periode_x['km_pengisian'];
    				$ix++;
    			}


    			$ix=0;
    			array_push($get_data_periode,$km_sekarang);

    			sort($get_data_periode);

    			array_unshift($get_data_periode,$data_periode_sebelum);

    			foreach($get_data_periode as $px){
    				
    				if($nx>0){
        					$undo_nx=$nx-1;							            					
    						$km[$nx]['km_sebelum'] = $km[$undo_nx]['km_pengisian'];
    						$km[$nx]['km_pengisian'] = $px;  

	            			if($px==$km_sekarang){
	            				$data['km_sebelum']= $km[$undo_nx]['km_pengisian'];
	            				$data['km_pengisian']= $px;
	            			}else{
	            				$data_old['km_sebelum'] = $km[$undo_nx]['km_pengisian'];
	            				$data_old['km_pengisian'] = $px;
	            				$this->db->where('km_pengisian',$px);
	            				$result = $this->db->update('trans_tank',$data_old);
	            				if($result){
	            					$periodex++;
	            				}else{
	            					$periodey++;
	            				}
	            			}
    				}else{
    						$km[$nx]['km_pengisian']=$px;
    				}

    				$nx++;
    			}


    		}else{
        		$data['km_sebelum']=$data_periode_sebelum;
        		$data['km_pengisian']=$km_sekarang;
    		}

    		$data_gabungan['km_sebelum'] = $data_periode_sebelum;
    	}else{

    		if($cek_data_sama_periode>0){

    			$get_data_periode = $this->get_data_sama_periode($column[2],$column[3],$column[4],$column[5],$tanggal);

    			$ix=0;
    			$nx = 0;
    			$km_first = 0;
    			$km_sekarang= $column[8];
    			$periodex=0;
    			$periodey=0;					            			
    			foreach($get_data_periode as $data_periode_x){
    				$get_data_periode[$ix] = $data_periode_x['km_pengisian'];
    				$datax_km_sebelum[$ix] = $data_periode_x['km_sebelum'];
    				$ix++;
    			}


    			$ix=0;
    			array_push($get_data_periode,$km_sekarang);

    			sort($get_data_periode);

    			array_push($datax_km_sebelum,$km_sekarang);

    			sort($datax_km_sebelum);	

    			foreach($get_data_periode as $px){
    				
    				if($nx>0){   					
        					$undo_nx=$nx-1;	
    						$km[$nx]['km_sebelum'] = $km[$undo_nx]['km_pengisian'];
    						$km[$nx]['km_pengisian'] = $px;  

	            			if($px==$km_sekarang){
	            				$data['km_sebelum']= $km[$undo_nx]['km_pengisian'];
	            				$data['km_pengisian']= $px;
	            			}else{
	            				$data_old['km_sebelum'] = $km[$undo_nx]['km_pengisian'];
	            				$data_old['km_pengisian'] = $px;
	            				$this->db->where('km_pengisian',$px);
	            				$result = $this->db->update('trans_tank',$data_old);
	            				if($result){
	            					$periodex++;
	            				}else{
	            					$periodey++;
	            				}
	            			}

    				}else{

    						$km[$nx]['km_sebelum']=$datax_km_sebelum;  					
    						$km[$nx]['km_pengisian']=$px;


	            			if($px==$km_sekarang){
	            				$data['km_sebelum']= $km[$nx]['km_pengisian'];
	            				$data['km_pengisian']= $px;
	            			}else{
	            				$data_old['km_sebelum'] = $km[$nx]['km_pengisian'];
	            				$data_old['km_pengisian'] = $px;
	            				$this->db->where('km_pengisian',$px);
	            				$result = $this->db->update('trans_tank',$data_old);
	            				if($result){
	            					$periodex++;
	            				}else{
	            					$periodey++;
	            				}
	            			}
    				}

    				$nx++;
    			}

    			$data_gabungan['km_sebelum'] = $datax_km_sebelum[0];

    		}else{
    			$periodex=0;
    			$periodey=0;							            			
        		$data['km_sebelum']=$column[7];
        		$data['km_pengisian']=$column[8];
    			$data_gabungan['km_sebelum'] = $column[7];
    		}
    	}

    	//$result='';

    	if(isset($column[24]))
    		$data['status_unit']=$column[24];

    	$data_gabungan['km_pengisian']=$column[8];
    	// $result['periodey']=$periodey;
    	// $result['periodex']=$periodex;
    	// $result['data_gabungan'] = $data_gabungan;
    	$rs['result'] = $this->db->insert('trans_tank',$data);	

    	return $rs;	
	}


	private function cek_data_sebelum_periode($id_vehicle,$kontraktor,$jenis,$no_unit,$datetime){

		$param = array(
			'id_vehicle'=>$id_vehicle,
			'kontraktor'=>$kontraktor,
			'jenis'=>$jenis,
			'no_unit'=>$no_unit,
			'datetime<'=>$datetime
		);

		$result = $this->M_csv->cek_data_transaksi($param);

		return $result;
	}

	private function get_data_sebelum_periode($id_vehicle,$kontraktor,$jenis,$no_unit,$datetime){

		$param = array(
			'id_vehicle'=>$id_vehicle,
			'kontraktor'=>$kontraktor,
			'jenis'=>$jenis,
			'no_unit'=>$no_unit,
			'datetime<'=>$datetime
		);

		$result = $this->M_csv->get_data_transaksi_sebelum($param);

		return $result;
	}

	private function cek_data_sama_periode($id_vehicle,$kontraktor,$jenis,$no_unit,$datetime){

		$param = array(
			'id_vehicle'=>$id_vehicle,
			'kontraktor'=>$kontraktor,
			'jenis'=>$jenis,
			'no_unit'=>$no_unit,
			'datetime='=>$datetime
		);

		$result = $this->M_csv->cek_data_transaksi($param);

		return $result;
	}	

	private function get_data_sama_periode($id_vehicle,$kontraktor,$jenis,$no_unit,$datetime){

		$param = array(
			'id_vehicle'=>$id_vehicle,
			'kontraktor'=>$kontraktor,
			'jenis'=>$jenis,
			'no_unit'=>$no_unit,
			'datetime='=>$datetime
		);

		$result = $this->M_csv->get_data_transaksi($param);

		return $result;
	}	

	function insert_data_gabungan($data){
		$param = array(
			'periode'=>$data['datetime'],
			'kontraktor'=>$data['kontraktor'],
			'jenis'=>$data['jenis'],
			'no_unit'=>$data['no_unit']
		);
		
		$cek = $this->M_csv->cek_data_gabungan($param);


		if($cek){
			$data = array(
				'periode'=>$data['datetime'],
				'kontraktor'=>$data['kontraktor'],
				'jenis'=>$data['jenis'],
				'no_unit'=>$data['no_unit'],
				'km_sebelum'=>$data['km_sebelum'],
				'km_pengisian'=>$data['km_pengisian'],
				'total_solar'=>$data['qty_terisi']				
			);

			if($data['km_sebelum']>=$cek->km_sebelum){
				$data['km_sebelum']=$cek->km_sebelum;
			}

			if($data['km_pengisian']<=$cek->km_pengisian){
				$data['km_pengisian']=$cek->km_pengisian;
			}


			$data['total_solar']+=$cek->total_solar;
			$result = $this->M_csv->update_data_gabungan($param,$data);
		}else{ 
		//jika data tidak ada
		$data = array(
			'periode'=>$data['datetime'],
			'kontraktor'=>$data['kontraktor'],
			'jenis'=>$data['jenis'],
			'no_unit'=>$data['no_unit'],
			'km_sebelum'=>$data['km_sebelum'],
			'km_pengisian'=>$data['km_pengisian'],
			'total_solar'=>$data['qty_terisi']
		);
			$result = $this->M_csv->insert_data_gabungan($data);
		}

		return $result;
	}




	function km_sebelum($datetime,$id_vehicle,$kontraktor,$jenis,$no_unit){
		$km_sebelum = $this->M_csv->get_km_sebelum($id_vehicle,$kontraktor,$jenis,$no_unit);
		return $km_sebelum;
	}


	function cek_transaksi_ganda($id_vehicle,$kontraktor,$jenis,$no_unit){
		$km_sebelum = $this->M_csv->get_km_sebelum($id_vehicle,$kontraktor,$jenis,$no_unit,1);
		return $km_sebelum;
	}


	function cek_data($file_name,$tank,$jenis,$periode){
		$verifikasi = $this->M_csv->cek_data($file_name,$tank,$jenis,$periode);

		if($verifikasi==TRUE){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}

?>
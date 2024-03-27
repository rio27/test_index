<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_csv extends CI_Model{
	function __construct(){
		parent::__construct();		
	}

	function insert_data_meteran($column){
		$tanggal= date('Y-m-d',strtotime($column[1]));
 		$id_tank = preg_replace('/\s+/', '', $column[2]);
    	$data = array(
    		'tanggal'=>$tanggal,
    		'id_tank'=>$id_tank,
    		'nama_meteran'=>$column[3],
    		'meteran_awal'=>$column[4],
    		'meteran_akhir'=>$column[5],
    		'meteran_total'=>$column[5]-$column[4],
    		'waktu_aktivasi'=>$column[6],
    	);

    	$result = $this->db->insert('data_meteran',$data);	
	}

	function insert_data_drain($column){
		$tanggal= date('Y-m-d',strtotime($column[1]));
 		$tangki_sumber = preg_replace('/\s+/', '', $column[2]);
 		$tangki_tujuan = preg_replace('/\s+/', '', $column[3]);		
		//input data
    	$data = array(
    		'tanggal'=>$tanggal,
    		'tangki_sumber'=>$tangki_sumber,
    		'tangki_tujuan'=>$tangki_tujuan,
    		'qty'=>$column[4],
    		'datetime'=>$column[5]
    	);

    	return $result = $this->db->insert('drain',$data);		
	}

	function insert_data_ritase($column){
		$tanggal= date('Y-m-d',strtotime($column[1]));
		//input data
    	$data = array(
    		'tanggal'=>$tanggal,
    		'id_kendaraan'=>$column[2],
    		'ritase_hauling'=>$column[3],
    		'ritase_feeding'=>$column[4],
    		'ritase_intermediate'=>$column[5],
    		'jatah_hauling'=>$column[6],
    		'jatah_feeding'=>$column[7],
    		'jatah_intermediate'=>$column[8],
    		'total_jatah'=>$column[9],
    		'pemakaian_hauling'=>$column[10],
    		'pemakaian_feeding'=>$column[11],
    		'pemakaian_intermediate'=>$column[12],
    		'total_pemakaian'=>$column[13],
    		'jenis_ritase'=>$column[14],
    		'ritase'=>$column[15],
    		'ritase_awal'=>$column[16],
    		'ritase_tambahan'=>$column[17],
    		'jatah_solar'=>$column[18],
    		'keterangan'=>$column[19],
    		'sisa_hauling'=>$column[20],
    		'sisa_feeding'=>$column[21],
    		'sisa_intermediate'=>$column[22],
    		'sisa'=>$column[23],
    		'tambahan_feeding'=>$column[24],
    		'tambahan_hauling'=>$column[25],
    		'tambahan_intermediate'=>$column[26],
    		'kontraktor'=>$column[27],
    		'jenis'=>$column[28],
    		'no_unit'=>$column[29]
    	);

    	return $result = $this->db->insert('ritase',$data);		
	}

	function insert_data_terimasolar($column){
 		$id_induk = preg_replace('/\s+/', '', $column[2]);
  		$id_tank  = preg_replace('/\s+/', '', $column[3]);		
		$tanggal= date('Y-m-d',strtotime($column[1]));
		$timestamp = date('Y-m-d h:i:s',strtotime($column[7]));
    	$data = array(
    		'datetime'=>$tanggal,
    		'id_induk'=>$id_induk,
    		'id_tank'=>$id_tank,
    		'qty'=>$column[4],
    		'operator'=>$column[5],
    		'user'=>$column[6],
    		'timestamp'=>$timestamp,
    		'sisa_solar'=>$column[8],
    		'sisa_solar_sebelum'=>$column[9],
    		'keterangan'=> $column[10],
    		'aktif'=> $column[11],
    		// versi stabil aktifkan ini
    		// 'kategori'=> $column[12],
    	);
    	// versi stabil hapus ini 
    	if($data['id_tank']=='TD11'){
			$data['kategori']=1;	    		
    	}

    	return $result = $this->db->insert('trans_induk',$data);					              		
	}

	function insert_data_meteransolar($column){
  		$id_tank  = preg_replace('/\s+/', '', $column[2]);
		$tanggal= date('Y-m-d',strtotime($column[1]));


		if(!is_numeric($column[12]))
			$column[12] = (int)$column[12];

		if(!is_numeric($column[6]))
			$column[6] = (int)$column[6];

		//input data
    	$data = array(
    		'tanggal'=>$tanggal,
    		'id_tank'=>$id_tank,
    		'meteran_awal'=>$column[3],
    		'meteran_akhir'=>$column[4],
    		'sisa_solar'=>$column[5],
    		'sisa_solar_sebelum'=>$column[7],
    		'penyesuaian'=>$column[8],
    		'transfer'=>$column[9],
    		'drain'=>$column[10],
    		'pemakaian'=>$column[11],
    		'terima_solar'=>$column[12]+$column[6]
    	);

    	$this->db->select('id_tank');
    	$this->db->where($data);
    	$cek_double_data = $this->db->get('meteransolar')->result_array();
    	
    	if($cek_double_data){
    		$result=true;
    	}else{
       		$result = $this->db->insert('meteransolar',$data);	 		
    	}

    	return $result;
	}	
	
	function count_jenis_data(){
		$this->db->select('id');
		$result = $this->db->get('jenis_data')->num_rows();
		return $result;
	}

	function count_uploaded_data($param){
		$this->db->select('id');

		$this->db->where($param);
		$result = $this->db->get('log_import')->num_rows();
		
		return $result;
	}

	function get_uploaded_data($periode,$tank){
		$param = array(
			'tanggal'=>$periode,
			'tank'=>$tank
		);

		$this->db->where($param);
		$uploaded = $this->db->get('log_import')->result_array();

		$csv=null;
		$i=0;
		foreach($uploaded as $data){
			$jenis = $data['jenis'];
			$csv[$i]=$jenis;
			$i++;
		}
		$this->db->where_not_in('nama',$csv);
		$not_uploaded = $this->db->get('jenis_data')->result_array();

		$data = array(
			'uploaded'=>$uploaded,
			'not_uploaded'=>$not_uploaded,
			'periode'=>$periode
		);

		return $data;
		
	}

	function cek_data_transaksi($param){
		$this->db->select('id_no');

		$this->db->where($param);

		$result = $this->db->get('trans_tank')->num_rows();

		return $result;
	}


	function get_data_transaksi($param){
		$this->db->select('km_pengisian,km_sebelum');

		$this->db->where($param);
		
		$this->db->order_by('datetime','ASC');

		$result = $this->db->get('trans_tank')->result_array();

		return $result;
	}



	function get_data_transaksi_sebelum($param){
		$this->db->select('*');

		$this->db->where($param);
		
		$this->db->limit(1);
		
		$this->db->order_by('km_pengisian','DESC');
		$this->db->order_by('datetime','DESC');
		
		$result = $this->db->get('trans_tank')->row();

		return $result;
	}


	function cek_data($file_name,$tank,$jenis,$periode){
		$this->db->select('id');

		$param = array(
			'tank'=>$tank,
			'jenis'=>$jenis,
			'tanggal'=>$periode
		);

		$this->db->where($param);
		$this->db->or_like('nama_file',$file_name);
		$this->db->from('log_import');
		$result = $this->db->count_all_results();

		if($result>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}


	function cek_data_gabungan($param){
		$this->db->where($param);
		$result = $this->db->get('data_gabungan')->row();
		return $result;
	}

	function update_data_gabungan($param,$data){
		$this->db->where($param);
		$result = $this->db->update('data_gabungan',$data);
		return $result;
	}

	function insert_data_gabungan($data){
		$result = $this->db->insert('data_gabungan',$data);
		return $result;
	}

	function cek_transaksi($id_vehicle,$kontraktor,$jenis,$no_unit,$datetime=null){
		$this->db->select('id');
		$param = array(
			'kontraktor'=>$kontraktor,
			'jenis'=>$jenis,
			'no_unit'=>$no_unit
		);
		if($datetime<>null || $datetime<>''){
			$param['datetime']=$datetime;
		}
		$this->db->where($param);
		$this->db->from('trans_tank');
		$result = $this->db->count_all_results();
		if($result>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}

	function get_km_sebelum($id_vehicle,$kontraktor,$jenis,$no_unit,$datetime=null){
		$this->db->select('km_pengisian,km_sebelum,id_no');
		$param = array(
			'kontraktor'=>$kontraktor,
			'jenis'=>$jenis,
			'no_unit'=>$no_unit
		);
		if($datetime<>null || $datetime<>''){
			$datetime = date('Y-m-d');
			$param['datetime']=$datetime;
		}
		$this->db->where($param);
		$this->db->order_by('km_pengisian','DESC');
		$this->db->limit(1);
		$result = $this->db->get('trans_tank')->row();
		return $result;
	}

}


?>
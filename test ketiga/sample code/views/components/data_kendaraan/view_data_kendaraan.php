<div class="row">
  <section class="content-header margin-bottom-20">
      <h1>
        Data Kendaraan
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Data Kendaraan</li>
      </ol>
  </section> 



  <!-- Tambah data -->
  <form id="form_tambah_kendaraan">
    <div class="col-md-12">
      <div class="box box-danger box-solid no-border">
        <div class="box-header">
          <h3 class="box-title">Tambah Data</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Jenis</label>
                <select class="form-control" name="jenis" id="jenis-kendaraan">
                  <option>-Pilih Pilihan-</option>

                </select>
              </div>

              <div class="form-group">
                <label>Warna</label>
                <input type="text" class="form-control" name="warna">
              </div>  
                        
              <div class="form-group">
                <label>Type / Klasifikasi</label>
                <input type="text" class="form-control" name="klasifikasi">
              </div>
            </div>
            <div class="col-md-4">            
              <div class="form-group">
                <label>No. Unit</label>
                <div class="row">
                  <div class="col-sm-12">
                    <input type="text" class="form-control" name="no_unit">
                  </div>                
                </div>
              </div>
              <div class="form-group">
                <label>Merk</label>
                <input type="text" class="form-control" name="merk">
              </div>                
              <div class="form-group">
                <label>Nomor Rangka</label>
                <input type="text" class="form-control" name="nomor_rangka">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>No. Mesin</label>
                <input type="text" class="form-control" name="nomor_mesin">
              </div>            
              <div class="form-group">
                <label>Bahan Bakar</label>
                <div class="row">
                  <div class="col-md-6">
                    <div class="input-group">
                          <span class="input-group-addon">
                            <input type="radio" value="Bensin" name="bahanbakar">
                          </span>
                      <input type="text" class="form-control" value="Bensin" readonly>
                    </div>               
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                          <span class="input-group-addon">
                            <input type="radio" value="solar" name="bahanbakar">
                          </span>
                      <input type="text" class="form-control" value="Solar" readonly>
                    </div>                 
                  </div>              
                </div>               
              </div>
              <div class="form-group">
                <label>Status</label>
                <input type="text" class="form-control" name="status">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>No. Polisi</label>
                <input type="text" class="form-control" name="nomor_polisi">
              </div>              
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Tahun Pembuatan</label>
                <input type="text" class="form-control" name="tahun_pembuatan">
              </div>              
            </div>            
            <div class="col-md-4">
              <div class="form-group">
                <label>Pajak Kendaraan</label>
                <input type="date" class="form-control" name="pajak">
              </div>              
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" name="keterangan"></textarea>
              </div>            
            </div>
          </div>
        </div>
        <!-- /.card-body -->
        <div class="box-footer">
          <span class="pull-right"><button type="submit" class="btn btn-warning">Tambah</button></span>        
        </div>      
      </div>
      <!-- /.card -->
    </div>
  </form>
  <!-- end of tambah data -->

 
  <form id="form-edit-kendaraan">
    
  </form>

  <!-- alert message -->
  <div class="col-md-12">
    <div id="message-alert"></div>
  </div>
  <!-- end of alert message -->
  
  <!-- table data -->
  <div class="col-md-12">
    <div class="box box-danger">
      <div class="box-body">
        <!-- table -->
        <table id="table_kendaraan" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th>ID Mobil</th>
            <th>Merk</th>
            <th>Type</th>
            <th>Warna</th>
            <th>Klasifikasi</th>
            <th>Nomor Rangka</th>
            <th>Nomor Mesin</th>
            <th>Bahan Bakar</th>
            <!-- status nantinya berisi informasi apakah kendaraan tersebut aktif atau rusak -->
            <th>Status</th> 
            <th width="180px">Action</th>
          </tr>
          </thead>
          <tbody>

          </tbody>
        </table>                
        <!-- end of table -->
      </div>
    </div>            
  </div>
</div>

<script type="text/javascript">
    //datatable
    var table = $('#table_kendaraan').DataTable( {
        "serverSide":true,
        "processing":true,

        "ajax": {
          "url":"<?php echo base_url('database/kendaraan/'); ?>",
          "type":"POST"
        },
        "columns": [
            { "data": "id_kendaraan" },
            { "data": "merk" },
            { "data": "jenis"},
            { "data": "warna" },
            { "data": "klasifikasi"},
            { "data": "nomor_rangka"},
            { "data": "nomor_mesin"},
            { "data": "bahan_bakar"},
            { "data": "status"},
            { "data": "id_kendaraan",
              "render" :function(data,type,row,meta){
                return"<a type='btn' target='_blank' href='<?php echo base_url('kendaraan/detail/') ?>"+data+"' class='btn btn-primary btn-sm' value='"+data+"'><i class='fa fa-eye'></i></a> <button type='button' value='"+data+"' class='btn btn-warning edit-button btn-sm'><i class='fa fa-edit'></i></button> <button type='button' value='"+data+"' id='hapus-button' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></button>";
              },
            }
        ],
        "order": [[1, 'asc']]
    });

  // tambah data
  $('#form_tambah_kendaraan').on('submit',function(){
    $.ajax({
      url:"<?php echo base_url('kendaraan/tambah/') ?>",
      type:'POST',  
      data: new FormData( this ),
      processData: false,
      contentType: false,
      success:function(data){
      $('#form_tambah_kendaraan')[0].reset();
          if(data.pesan==3){
            var data = {
              item:'#message-alert',
              type:'alert-danger',
              icon:'fa-close',
              caption:'Data gagal ditambahkan!',
              isi:'Kesalahan pada saat penginputan data',
              delay:4000
            };
            alertmessageforall(data); 
          }else
          if(data.pesan==4){
            var data = {
              item:'#message-alert',
              type:'alert-danger',
              icon:'fa-close',
              caption:'data gagal ditambahkan!',
              isi:'Nama tidak boleh kosong',
              delay:4000
            };
            alertmessageforall(data); 
          }else
          if(data.pesan==2){
            table.ajax.reload();
             var data = {
              item:'#message-alert',
              type:'alert-success',
              icon:'fa-check',
              caption:'Penambahan data berhasil',
              isi:'Data Berhasil ditambahkan',
              delay:2500
              };
              alertmessageforall(data);              
          }else{
            var data = {
              item:'#message-alert',
              type:'alert-danger',
              icon:'fa-close',
              caption:'data gagal ditambahkan!',
              isi:'Hubungi Admin untuk pesan kesalahan ini',
              delay:4000
            };
            alertmessageforall(data); 
          }       
      },
      error:function(){
        var data = {
          item:'#message-alert',
          type:'alert-warning',
          icon:'fa-warning',
          caption:'Penambahan data gagal',
          isi:'Terjadi kesalahan penginputan data, refresh halaman atau hubungi admin',
          delay:4600
        };
        alertmessageforall(data);         
      }
    })
    return false;
  })


  $('#table_kendaraan tbody').on('click','.edit-button',function(){
    var id = $(this).val();
    var tr = $(this).closest('tr');
    var row = table.row( tr );
    var editdata = {
        id:'edit-data',
        row_data:row.data(),
        size:12,
        formId:'form-edit-kendaraan',
        row_type:{
          id_kendaraan:{
            type:'text',
            extens:'readonly'
          },
          warna:{
            type:'text'
          },
          merk:{
            type:'text'
          },
          nomor_rangka:{
            type:'text'
          },
          no_mesin:{
            type:'text'
          },
          bahan_bakar:{
            type:'dropdown',
            option:[
            {text:'Bensin',value:'Bensin'},
            {text:'Solar',value:'Solar'}
            ]
          },
          status:{
            type:'text'
          },
          nomor_polisi:{
            type:'text'
          },
          tahun_pembuatan:{
            type:'text'
          },
          pajak:{
            type:'date'
          },
          jadwal_service:{
            type:'date'
          }
        }
    }
    blueEdit(editdata);

  })

  $('#form-edit-kendaraan').on('submit',function(){
    execute_edit('form-edit-kendaraan','<?php echo base_url("public_api/edit/kendaraan"); ?>');
    table.ajax.reload();
    return false;
  })

  //hapus button
  $('#table_kendaraan tbody').on('click','#hapus-button',function(){
        var id= $(this).val();
        swal({
          title: 'Hapus Data?',
          text: "Tekan Ya Untuk Menghapus Data",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya'
        }).then(function () {
          $.ajax({
            url:"<?php echo base_url('kendaraan/hapus/') ?>"+id,
            method:'GET',     
            success:function(data){
                swal(
                  data.header,
                  data.pesan,
                  data.type
                  );
                if(data.success!=null){
                  table.ajax.reload();
                }
            }     
            })
             return false;
        });
  });

    //get data dropdown jenis kendaraan
    var jenis_kendaraan= {
      component:['#jenis-kendaraan'],
      url:'<?php echo base_url(); ?>public_api/dropdown',
      data:{
        table:'jenis_kendaraan',
        option_value:'jenis',
        option:'jenis'
      }
    }
    dropdown(jenis_kendaraan);

</script>
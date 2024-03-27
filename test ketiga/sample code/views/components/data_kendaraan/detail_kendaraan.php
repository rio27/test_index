<!-- modal ganti foto -->
<form id="form-ganti-foto" method="POST" enctype="multipart/form-data" action="<?php echo base_url('data_dosen/ganti_foto'); ?>">
  <div class="modal fade" id="modal-gambar">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          Ganti Foto
          <span class="pull-right" data-dismiss="modal">
            <i class="fa fa-close"></i>
          </span>
        </div>
        <div class="modal-body">
          <input type="hidden" name="trigger" value="27cwf">
          <input type="hidden" name="id_kendaraan" id="id_kendaraan" value="<?php echo $id ?>">
          <div class="form-group">
            <label>Foto</label>
            <input type="file" class="form-control" name="foto">
            <p class="help-block">Foto Harus Berformat PNG atau JPG dan diasarankan berdimensi 300x300 atau 800x800</p>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" type="submit" name="submit" value="1">Ganti</button>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- end of modal ganti foto -->
<div class="row">
	<div class="col-md-4">
		<div class="box box-danger">
			<div class="box-header">
				<label class="box-title">Gambar Unit</label>
			</div>
			<div class="box-body">
		      	<div id="foto-kendaraan">
                <img class="img default-user-image2">                
            </div>
			</div>
      <div class="box-footer">
          <div id="message-alert"></div>
          <button class="btn btn-warning btn-flat btn-block" data-target="#modal-gambar" id="btn-ganti-foto" data-toggle="modal"><i class="fa fa-camera"></i> Ganti Foto</button>                
      </div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="box box-danger">
			<div class="box-header">
				<label class="box-title">Informasi Unit</label>
			</div>
			<div class="box-body">
				<div id="box-informasi"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
function format ( d ) {
  var pajak = new Date(d.pajak);
  var sekarang = new Date();
  if(pajak < sekarang){
    var alert_pajak='<span class="label bg-red"> Pajak Mati</span>';
  }else{
     var alert_pajak='<span class="label bg-green"> Masih Berlaku</span>';
  }
    // `d` is the original data object for the row
        html =      '<table class="table" style="background-color:white">'+
                        '<tr  style="border-top:none">'+
                            '<td width="10%">No. unit</td>'+
                            '<td width="20%">: '+d.no_unit+'</td>'+
                            '<td width="10%">Nomor Lambung</td>'+
                            '<td width="20%">: '+d.no_lambung+'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td>Merk</td>'+
                            '<td>: '+d.merk+'</td>'+

                            '<td>Klasifikasi</td>'+
                            '<td>: '+d.klasifikasi+'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td>Jenis </td>'+
                            '<td>: '+d.jenis+'</td>'+

                            '<td>Nomor Polisi</td>'+
                            '<td>: '+d.nomor_polisi+'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td>Warna</td>'+
                            '<td>: '+d.warna+'</td>'+

                            '<td>Bahan Bakar</td>'+
                            '<td>: '+d.bahan_bakar+'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td>Nomor Mesin </td>'+
                            '<td>: '+d.nomor_mesin+'</td>'+
                            '<td></td>'+
                            '<td></td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td>Nomor Rangka </td>'+
                            '<td>: '+d.nomor_rangka+'</td>'+
                            '<td></td>'+
                            '<td></td>'+                            
                        '</tr>'+
                        '<tr>'+
                            '<td>Tahun Pembuatan </td>'+
                            '<td>: '+d.tahun_pembuatan+'</td>'+
                            '<td></td>'+
                            '<td></td>'+
                        '</tr>'+                                                
                        '<tr>'+
                            '<td>Masa Berlaku</td>'+
                            '<td>: '+d.pajak+' '+alert_pajak+'</td>'+
                            '<td></td>'+
                            '<td></td>'+                            
                        '</tr>'+
                    '</table>';
        return html;
  
}


//sementara kosong
// function gambar(d){
// 	html = '<img  src="<?php echo base_url('files/dosen/') ?>'+d+'" alt="Foto Dosen">';
// 	return html;
// }

$.ajax({
	"url":"<?php echo base_url('kendaraan/getwhere/') ?>",
	"type":'POST',
	"data":{id:'<?php echo $id ?>'},
	"success":function(data){
		        $('#box-informasi').html(format(data[0]));
            if(data[0].foto!=''){
              var imgdata = '<img class="img default-user-image2"  src="<?php echo base_url()?>files/unit/'+data[0].foto+'" alt="Foto Kendaraan">';
              $('#foto-kendaraan').html(imgdata);
            }
                     
		// $('.box-gambar').html(gambar(data[0].foto));
	}
})


  $('#form-ganti-foto').on('submit',function(){
    $.ajax({
        url:"<?php echo base_url('kendaraan/ganti_foto') ?>",
        type:'POST',  
        data: new FormData( this ),
        processData: false,
        contentType: false,
        success:function(data){
        $('#form-ganti-foto')[0].reset();
        $('#modal-gambar').modal('hide');
          if(data.pesan==1){
            var data = {
              item:'#message-alert',
              type:'alert-warning',
              icon:'fa-warning',
              caption:'Upload File Foto Gagal!',
              isi:'Terjadi kesalahan saat mengupload gambar periksa kembali apakah ekstensi file foto sudah benar',
              delay:4600
            };
            alertmessageforall(data);           
          }else
          if(data.pesan==3){
            var data = {
              item:'#message-alert',
              type:'alert-danger',
              icon:'fa-close',
              caption:'Foto gagal dirubah!',
              isi:'Foto gagal dirubah, refresh halaman dan coba lagi',
              delay:4000
            };
            alertmessageforall(data); 
          }else
          if(data.pesan==4){

          }else
          if(data.pesan==2){
             var imgdata = '<img class="img default-user-image2"" src="<?php echo base_url()?>files/unit/'+data.foto+'" alt="Foto Kendaraan">';
             $('#foto-kendaraan').html(imgdata);
             var data = {
              item:'#message-alert',
              type:'alert-success',
              icon:'fa-check',
              caption:'Perubahan Foto berhasil',
              isi:'Foto Berhasil dirubah',
              delay:4000
              };
              alertmessageforall(data);              
          }else{
            var data = {
              item:'#message-alert',
              type:'alert-danger',
              icon:'fa-close',
              caption:'Foto gagal dirubah!',
              isi:'Foto gagal dirubah, refresh halaman dan coba lagi',
              delay:4000
            };
            alertmessageforall(data); 
          }         
        }
    })
    return false;
  })


})	

</script>
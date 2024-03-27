<div class="row">
	<div class="col-md-4">
		<form id="form_upload_csv" enctype="multipart/form-data" method="POST" >
			<div class="box box-solid box-warning">
				<div class="box-header">
					<label class="box-title">Upload Data</label>
				</div>
				<div class="box-body">
					<input class="form-control" type="file" name="csv[]" multiple>
				</div>
				<div class="box-footer">
					<button class="btn btn-primary pull-right" type="submit"><i class="fa fa-upload"></i> Upload</button>
				</div>
			</div>			
		</form>

		<div class="box box-solid box-primary">
			<div class="box-header">
				<label>Upload Data </label><small class="pull-right"></small>
			</div>
			<div class="box-body">
				<div class="form-group">
					<label>Periode</label>
					<input type="date" class="form-control" id="csv_periode" name="periode" value="<?php echo $this->session->csv_periode ?>">
				</div>		
				<div id="tank_data"></div>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="row">
			<section class="content-header" style="margin-top:-16px">
		      <h1>
		        Recent
		        <small>Upload</small>
		      </h1>
		    </section>			
			</br>
		</div>

		<?php include'view.php'; ?>
	</div>
</div>


<script type="text/javascript">


	$(function(){

    var table = $('#pengisian').DataTable( {
        lengthMenu:[[5,10,25,50,100,-1],[5,10,25,50,100,'Semua']],
        displayLength:5,
        serverSide:true,
        processing:true,
        stateSave:false,
        ordering:true,
        ajax: {
          "url":"<?php echo base_url('datatable/csv/'); ?>",
          "type":"POST",
          "data":function(data){
            if($('#filter_jenis').val()!='' || $('#filter_periode').val()!='' || $('#filter_tangki').val()!='' || $('#filter_no_unit').val()!='' || $('#filter_kontraktor').val()!=''){
              data = {
                jenis : $('#filter_jenis').val(),
                no_unit : $('#filter_no_unit').val(),
                kontraktor : $('#filter_kontraktor').val(),
                tangki : $('#filter_tangki').val()
              }
            }
          }
        },
        columns: [
            { "data": "tanggal" },
            { "data": "nama_file",
              "render" :function(data){
                      return "<a href='<?php echo base_url('files/csv/') ?>"+data+"'>"+data+"</a>";
              }
            },
            { "data": "tank"},
            { "data": "jenis"},
            { "data": "user"},
        ],

        order: [[1, 'asc']]
    } );
     
    $('#pengisian tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            row.child( fpenelitian(row.data()) ).show();
            tr.addClass('shown');
        }
    });

		var periode = $('#csv_periode').val();
		get_tank_data(periode);

	$('#form_upload_csv').on('submit',function(){
		$('#detail_csv_tangki').html('');
		var loader = '<img id="loader-image" style="position:center;margin-left:30%" src="<?php echo base_url('assets/dist/img/loader.gif'); ?>">';
		$('#detail_csv_tangki').prepend(loader);
		$.ajax({
			url:"<?php echo base_url('/csv/upload'); ?>",
			type:'POST',  
			data: new FormData( this ),
			processData: false,
			contentType: false,
			success:function(result){
				table.ajax.reload();
				var periode = $('#csv_periode').val();
				$('#detail_csv_tangki').html('');
				get_tank_data(periode);
				$('#form_upload_csv')[0].reset();
				$.each(result,function(x,i){
					if(i.type=='success'){
			            var data = {
			              item:'#detail_csv_tangki',
			              type:'alert-success',
			              icon:'fa-check',
			              caption:'Upload CSV Berhasil!',
			              isi:i.pesan,
			              delay:0
			            };				
					}else
					if(i.type=='info'){
			            var data = {
			              item:'#detail_csv_tangki',
			              type:'alert-warning',
			              icon:'fa-info',
			              caption:'File telah terupload sebelumnya!',
			              isi:i.pesan,
			              delay:0
			            };	
					}else{
			            var data = {
			              item:'#detail_csv_tangki',
			              type:'alert-danger',
			              icon:'fa-close',
			              caption:'Upload File CSV Gagal!',
			              isi:i.pesan,
			              delay:0
			            };					
					}					
           			 alertmessageforall(data); 
				})


       		
			}
		})
		return false;
	})	

	function get_tank_data(periode){

		$.ajax({
			url:"<?php echo base_url('/tank/get/') ?>"+periode,
			success:function(d){
				$('#tank_data').html('');
				$.each(d,function(i,x){
				if(x.not_uploaded>0){
				html = '<div class="alert" style="color:white;background-color:#4b6157;padding-bottom:1px !important;padding-top:1px !important;margin-bottom:15px !important">'+
					'<h5><b><a href="#" class="id_tank" tank="'+x.tank_name+'" value="'+x.label+'">'+x.tank_name+'</a> <small class="label pull-right bg-red">Not Uploaded: '+x.not_uploaded+'</small></b></h5>'+
				'</div>';
				}else{
				html='';
				}

				$('#tank_data').append(html);
				})
			}
		})

	}	

	$('#csv_periode').on('change',function(){
		var periode = $(this).val();
		get_tank_data(periode);
	})


	$('#detail_csv_tangki').on('click','.box-header .btn-close',function(){
		$(this).closest('.box').remove();
	})

	$('#tank_data').on('click','.alert .id_tank',function(){
		var id_tank = $(this).attr('value');
		var tank = $(this).attr('tank');
		var periode = $('#csv_periode').val();
		var content='';
		var html='';
		$.ajax({
			url:'<?php echo base_url("tank/get_uploaded_data/") ?>'+periode+'/'+id_tank,
			success:function(d){

				$.each(d.not_uploaded,function(i,x){
					content += 	'<div class="callout callout-danger" style="padding-bottom:1px 			!important;padding-top:1px !important;margin-bottom:10px !important">'+
						          '<h5><b>'+x.nama+'.csv</b></h5>'+
						        '</div>';
				})

				$.each(d.uploaded,function(i,x){
					content += 	'<div class="callout callout-success" style="padding-bottom:1px 			!important;padding-top:1px !important;margin-bottom:10px !important">'+
						          '<h5><b>'+x.jenis+'.csv</b></h5>'+
						        '</div>';
				})



				var html = 	'<div class="box box-solid box-warning no-border" >'+
						      '<div class="box-header" style="background-color:#4b6157">'+
						        '<label>'+tank+'</label><span class="pull-right" style="margin-right:40px">'+d.periode+'</span>'+
								'<div class="box-tools pull-right">'+
									'<button type="button" class="btn btn-box-tool btn-close" data-widget="remove"><i class="fa fa-times"></i></button>'+
								'</div>'+
						      '</div>'+
						      '<div class="box-body" >'+
						      content+
						      '</div>'+
						    '</div>';       
		        $('#detail_csv_tangki').prepend(html);

			}
		})
		

		return false;		
	})

	})
</script>



<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login System</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url('/plugins/bootstrap/dist/css/bootstrap.min.css'); ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('/plugins/font-awesome/css/font-awesome.min.css'); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url('/plugins/Ionicons/css/ionicons.min.css'); ?>">
  <!-- select2-->
  <link rel="stylesheet" href="<?php echo base_url('/plugins/select2/dist/css/select2.min.css');?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('/plugins/dist/css/AdminLTE.min.css');?>">
  <!-- AdminLTE Skins-->
  <link rel="stylesheet" href="<?php echo base_url('/plugins/dist/css/skins/skin-blue-light.min.css');?>">
    <!-- Sweet Alert -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('/plugins/sweetalert/sweetalert2.min.css') ?>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style type="text/css">
    .margin-top-10{
      margin-top:10px;
    }
    .modal-header{
      background-color:#3c8dbc;
      border-color:#367fa9 !important;
      color:white;
      font-size:18px;
    }
    .button-close{
    cursor:pointer;
    }
  </style>
  <!-- Google Font -->
<!--   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>System</b> Login</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Login untuk masuk ke panel System</p>

    <form action="<?php echo base_url('auth/login'); ?>" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="username" placeholder="Username" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
<!--           <button type="button" data-target="#modal-register" data-toggle="modal" class="btn btn-success btn-flat">Daftar Akun</button> -->
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" name="login" value="Login" class="btn btn-primary btn-block btn-flat"> Login</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
  <img src="<?php echo base_url(); ?>assets/dist/img/uncp-1.png" class="pull-left" style="margin-top:10px;margin-left: 4%" >
  <a href="<?php echo base_url();?>" class="navbar-brand"><span style="font-size:16px"><b>BINTANG DELAPAN FUEL SYSTEM</b></span></a>
          
</div>
<!-- /.login-box -->


<!-- jQuery 3 -->
<script src="<?php echo base_url('/plugins/jquery/dist/jquery.min.js'); ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('/plugins/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
<!-- Select2 -->
<script src="<?php echo base_url('/plugins/select2/dist/js/select2.full.min.js');?>"></script>
<!-- sweetalert -->
<script type="text/javascript" src="<?php echo base_url('/plugins/sweetalert/sweetalert2.min.js') ?>"></script>

<script type="text/javascript">
$(document).ready(function(){
  $('.form_fakultas').select2({
    allowClear:true,
    placeholder: {
      'id':0,
      'value':'',
      'text': 'Semua'
    },
    ajax: {
      url:'<?php echo base_url("select2/fakultasbyid/"); ?>',
      dataType: 'json',
      delay: 250,
      processResults:function(data){
        return{
          results: data
        };
      },
    }
  });

  $( ".form_fakultas" ).change(function() {
   getprodibyfakultas($(this).val(),$(this).attr('id'));
  });

  function getprodibyfakultas(id,component){
    $.ajax({
      url:"<?php echo base_url('select2/getprodibyfakultas/') ?>"+id,
      success:function(data){
        var option='<option selected="selected"></option>';
        $.each(data,function(i,obj){
        option+= '<option value="'+obj.id+'">'+obj.nama+'</option>';
        })
        $('.prodi').html(option);
      }

    })
  }

  $('.prodi').select2({
    allowClear:true,
    // minimumInputLength:3,
    placeholder: {
      'value':'',
      'text': 'Dipilih Setelah Fakultas'
    }
  });

<?php
if($pesan = $this->session->flashdata('pesan')){
?>
      swal('','<?php echo $pesan ?>','<?php echo $this->session->type ?>');
<?php
}
?>
});
</script>
</body>
</html>

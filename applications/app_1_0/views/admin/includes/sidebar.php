<?php 
$admin_info	=	$this->session->userdata('admin');
/*
* Badge number count
*/

?>
<!-- START SIDEBAR -->
<div class="sidebar clearfix">
<div class="dropdown link">
      <a href="#" data-toggle="dropdown" class="dropdown-toggle profilebox">
     <div class="user-name"><b>How are you <?php echo $admin_info['name'] ?? 'Admin';?> ?</b><span class="caret"></span></div></a>
      
       
       
    </div>
<ul class="sidebar-panel nav">
  <li class="sidetitle">Main Menu</li>
  <li><a href="<?php echo base_url('admin')?>"><span class="icon color5"><i class="fa fa-dashboard"></i></span>Dashboard</a></li>
  <li><a href="<?php echo base_url('admin/users')?>"><span class="icon color6"><i class="fa fa-shopping-cart"></i></span>Users <span class="label label-warning"><?php echo $users ?? 0;?></span></a></li>

  
  <li class="sidetitle">Admin Menu</li>
  <li><a href="<?php echo base_url('admin/admins')?>"><span class="icon color6"><i class="fa fa-sliders"></i></span>Admins</a></li>
  <li><a href="<?php echo base_url('admin/settings')?>"><span class="icon color6"><i class="fa fa-sliders"></i></span>Settings</a></li>
  <li><a href="<?php echo base_url('admin/log')?>"><span class="icon color6"><i class="fa fa-warning"></i></span>System Log</a></li>
  <li><a href="<?php echo base_url('admin/logout')?>"><span class="icon color6"><i class="fa fa-power-off"></i></span>Logout</a></li>

  
  <li class="sidetitle">API</li>
  <li><a href="<?php echo base_url('admin/api')?>"><span class="icon color6"><i class="fa fa-sliders"></i></span>API</a></li>
  <li><a href="<?php echo base_url('admin/log')?>"><span class="icon color6"><i class="fa fa-warning"></i></span>API Logs</a></li>
</ul>



</div>
<!-- END SIDEBAR -->
<!-- //////////////////////////////////////////////////////////////////////////// --> 

<div class="content">

<!-- Start Page breadcrumb -->
<div class="page-header">

    <ol class="breadcrumb">
  <li><a href="<?php echo base_url('admin')?>"><i class='fa fa-dashboard'></i> Dashboard</a></li>
    <?php if($this->router->fetch_class()!='home'){ echo '<li><a href="'.base_url('admin/'.$this->router->fetch_class()).'">'.ucfirst($this->router->fetch_class()).'</a></li>';}?>
      <?php if($this->router->fetch_method()!='index'){ echo "<li><a href='".base_url('admin/'.$this->router->fetch_method())."'>".ucfirst($this->router->fetch_method()).'</a></li>';}?>
    </ol>
</div>
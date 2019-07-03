<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends Web_Controller {
	function __construct(){
		parent::__construct();
		$this->login->check_admin_login();
		$this->load->helper('number');
	}

	public function index($file=NULL){
		$data['title']			=	'Error Logs';
		$data['heading']		=	'Error Logs';
		$dir    				=	$this->config->item('log_path');
		$data['directory']		=	$dir;
		$files2 				=	scandir($dir, 1);
		$data['files'] 			=	$files2;
		if($file!=NULL){
			$data['content']	=	file_get_contents($dir.DIRECTORY_SEPARATOR.base64_decode($file));
			$data['file_name'] 	=	base64_decode($file);

		}
		$this->_render_admin('admin/logs', $data);

		// $this->load->view('admin/includes/header',$data);
		// $this->load->view('',$data);
		// $this->load->view('admin/includes/footer',$data);
	}

	public function del($file_name){
		$dir    = $this->config->item('log_path');
		$ds 	=	DIRECTORY_SEPARATOR;
		
		$file_name 	=	$dir.$ds.base64_decode($file_name);

		if(file_exists($file_name)){
			unlink($file_name);
		}

		$this->lib->redirect_msg('File deleted successfully!','success','admin/log');

	}

	public function load_file($file_name){
		if($file_name!=NULL){
			$dir    	= $this->config->item('log_path');
			if(!file_exists($dir.DIRECTORY_SEPARATOR.base64_decode($file_name))){
				echo "File does not exist or deleted";
				exit();
			}
			$content	=	file_get_contents($dir.DIRECTORY_SEPARATOR.base64_decode($file_name));
			if(!$content){
				echo "unable to open file";
				exit();
			}
			$file_name 	=	base64_decode($file_name);
			?>
			<h3>Log content for file : <?php if(isset($file_name)){ echo $file_name;};?></h3>
			<span>
				Modified <?php echo timespan(filemtime($dir.DIRECTORY_SEPARATOR.$file_name),time(),1);?> ago &bull;
				Size: <?php echo byte_format(filesize($dir.DIRECTORY_SEPARATOR.$file_name));?>
			</span>
			<div class="well file_content" id="file_content_well">
				<small>
				<?php 
				if(isset($content)){
					echo nl2br($content);
				}
				?>
				</small>
			</div>
		
			<script type="text/javascript">
				divx = document.getElementById("file_content_well");
	        	divx.scrollTop      =    divx.scrollHeight;
			</script>
			<?php
		}
	}
}

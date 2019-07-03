<section class="login-col">
    <div class="container">
        <div class="row">
                <div class="col-xs-12 mb-lg-left">
            <div class="login-bx row">
        	<div class="col-lg-8 col-md-7 col-xs-12 login-dtl hidden-sm hidden-xs animated zoomIn delay-1s">
                <div class="text-center">
                	<h1>Mobi-Hub for mobile device</h1>
                    <p>Easy to install easy to use.</p>

                    <img src="<?php echo base_url('static/front/images/bg_login_bx3.jpg?v='.date('Y'))?>" alt="trade mobile" style="width:60%;">
 <p>A simple, social and user-friendly business app for consumer electronic traders. Mobi-Hub will connect you to thousands of traders across the globe.</p>
<!--                   <a target="_blank" href="http://demo.mobi-hub.com/" class="btn btn-lg btn-primary">View demo</a>
-->                  <div class="clearfix"></div>
                  <div class="appstore">
                  	<ul>
                      <li class="col-xs-4"><a target="_blank" href="<?php echo $this->lib->get_settings('url_google_play');?>" title="Google Store"><img src="<?php echo base_url('static/front/images/img_google_play.png')?>" alt="googleplay_img" /></a></li>                
                    <li class="col-xs-4"><a target="_blank" href="http://demo.mobi-hub.com/" class="btn btn-lg btn-primary" style="height: 45px; background: black; line-height: 26px; font-size: 18p; width: 100%;">View demo</a></li>
                    <li class="col-xs-4"><a target="_blank" href="<?php echo $this->lib->get_settings('url_apple_store');?>" title="App Store"><img src="<?php echo base_url('static/front/images/img_app_store.png')?>" alt="app_store_img" /></a></li>
                    </ul>
                   </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-5 col-md-push-0 col-sm-8 col-sm-push-2 col-xs-12 col-xs-push-0 login-frm animated fadeInDown delay-2s">
                <div class="mb-card text-center">
                    <form class="form-signin form-sign-in" action="<?php echo base_url('login/validate')?>" method="post">    
                        <?php create_csrf();?>   
                        <h2 class="heading">Please login to your account <?php /*?><?php echo $this->lib->get_settings('sitename');?><?php */?></h2>
                        <?php echo $this->lib->alert_message();?>
                        <div class="form-group">
                        	<label for="username" class="pull-left"><i class="fa fa-user"></i> Username</label>
                            <input type="text" id="username" min-length="4" maxlength="20" class="form-control input-md" name="username" value="<?php if(isset($username)){ echo $username;}?>" placeholder="Username" required autofocus />
                        </div>
                        
                        <div class="form-group">
                        	<label for="password" class="pull-left"><i class="fa fa-lock"></i> Password</label>
                            <input id="password" minlength="6" type="password" class="form-control input-md" name="password" value="<?php if(isset($password)){ echo $password;}?>" placeholder="Password" required/> 
                        </div>
                        <input type="hidden" class="hidden" name="redirect" value="<?php if(isset($_GET['redirect'])){ echo $_GET['redirect'];}?>">
                        <input type="hidden" class="hidden user_timezone" name="timezone" >
                       
                        
                            <label class="checkbox pull-left" style="margin: 10px 20px 10px 20px;">
                                <input type="checkbox" value="1" id="rememberMe" name="rememberMe" <?php if(isset($password)){ echo "checked";}?>> Remember me
                            </label>
                            <span class="forgot-password pull-right" style="margin:10px;"><a href="<?php echo base_url('login/reset')?>"><i class="fa fa-question-circle"></i> Forgot password?</a></span> 
                            <div class="clearfix"></div>
                            <div class="clearfix mb-divider mb-mg-top mb-mg-bottom"></div>
                            <div class="clearfix"></div>
                        <button class="btn btn-lg btn-primary btn-block mb-btn mb-btn-blue mb-fll-width mb-radius-square mb-mg-bottom" type="submit"><i class="fa fa-sign-in"></i> Login</button>   
                        
                        
                        
                    
                        <a class="btn btn-lg btn-primary btn-block mb-btn mb-btn-white mb-fll-width mb-radius-square mb-mg-bottom" href="<?php echo base_url('register') ?>?new=true"><i class="fa fa-user-plus"></i> Create New Account</a>
                        <!-- <p class="text-center"><a href="<?php //echo base_url(VSM_URL)?>"><i class="fa fa-black-tie"></i> Login as VSM?</a></p> -->
                    </form>
                    <div class="clearfix"></div>
                    
                    <div class="hidden-lg hidden-md">
                    <div style="max-width: 480px;margin: 15px auto;display:block;padding:0 15px;">
                        <a target="_blank" href="http://demo.mobi-hub.com/" class="btn btn-lg btn-primary btn-block mb-btn mb-btn-white mb-fll-width mb-radius-square mb-mg-bottom">View demo</a>
                        <!--btn btn-lg btn-primary btn-block mb-btn mb-btn-blue mb-fll-width mb-radius-square mb-mg-bottom-->
                    </div>
                  <div class="clearfix"></div>
                      <div class="appstore">
                        <ul>

                        <li class="col-xs-6"><a href="<?php echo $this->lib->get_settings('url_apple_store');?>" title="App Store"><img src="<?php echo base_url('static/front/images/img_app_store.png')?>" alt="app_store_img" /></a></li>
                        <li class="col-xs-6"><a href="<?php echo $this->lib->get_settings('url_google_play');?>" title="Google Store"><img src="<?php echo base_url('static/front/images/img_google_play.png')?>" alt="googleplay_img" /></a></li>

                        </ul>
                       </div>
                    </div>
                     <div class="clearfix"></div>
                </div>              
            </div>
            </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function(){
        $('.user_timezone').val(moment.tz.guess());
        <?php 
            if(isset($_GET['forget_password'])):
        ?>
        $('#forgot_password').click();
        <?php endif;?>
        
    });
</script>

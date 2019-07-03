<section class="login-col">
    <div class="container pages-content">
        <div class="row">
            <div class="col-xs-12">
                <div class="mb-mg-top mb-pd-top mb-mg-bottom"> 
                        <h2 class="mb-mg-top mb-pd-top heading text-uppercase text-center "><i class="fa fa-edit fa-fw"></i> WRITE TO US</h2>
                <div class="row">
                    <div class="col-md-6 col-xs-12 text-center mb-mg-bottom">
                    
                    <p class="social">Cant find the answer to your question? Please fill out this form or send us an email at <?php echo auto_link('info@mobi-hub.com');?></p>
                    <?php
                    if(isset($msg_show)){

                      $this->lib->display_alert($msg_show);
                    
                    }

                     ?>
                      <?php echo $this->lib->alert_message();?>  
                    <!--<div class="form-signin"></div>-->
                    <form class="row contact-us-form" action="<?php echo base_url('contact_us/save')?>" method="post">  
                        <?php create_csrf();?>
                        <div class="form-group col-sm-12">
                        
                            <input autofocus id="signup-name" type="text" maxlength="50" name="name" autofocus="false" class="form-control input-lg" placeholder="Your Name*" value="<?php if(isset($user_info)): echo $user_info->user_first_name.' '.$user_info->user_last_name; endif;?>" <?php echo !empty($user_info)?"readonly":"required";  ?> >
                        </div>
                        <div class="form-group col-sm-12">
                        
                            <input type="email" class="form-control input-lg" maxlength="50" name="email" placeholder="Your valid email*" value="<?php if(isset($user_info)){ echo $user_info->user_email;} ?>" <?php echo !empty($user_info)?"readonly":"required";  ?>>
                        </div>
                        
                        <div class="form-group col-sm-12">
                                <textarea id="form-message" name="message" class="form-control input-lg" placeholder="Write us a message*" rows="4" required="required" data-error="Please,leave us a message."><?php if(isset($pre_text)){echo $pre_text;} ?></textarea>
                        </div>
                        <div class="col-sm-12">
                        <button class="text-center btn btn-primary btn-lg btn-block" type="submit"><i class="fa fa-send-o"></i> Send Message</button> 
                        </div>
                    </form>
                    </div>
                    
                    <div class="col-md-6 col-xs-12">
                    	<div class="contacts-info-bottom text-center mb-mg-top">
                        <!-- <h2 style="font-size: 20px;color: #007fbd;">CONTACT US</h2> -->
                            <ul class="social-media">
                                <!-- <li><a href=""><i class="fa fa-phone" aria-hidden="true"></i><span>Call us- +44 203 603 9777</span></a></li>
                                <li><a href=""><i class="fa fa-whatsapp" aria-hidden="true"></i><span>Chat with us in Whatsapp</span></a></li>
                                <li><a href=""><i class="fa fa-linkedin" aria-hidden="true"></i><span>Connect us on Linkedin</span></a></li>
                                <li><a href=""><i class="fa fa-facebook" aria-hidden="true"></i><span>Follow us on Facebook</span></a></li>
                                <li><a href=""><i class="fa fa-twitter" aria-hidden="true"></i><span>Get the latest news on Twitter</span></a></li> -->
                                <li class="phone"><a href="tel:<?php echo $this->lib->get_settings('phone')?>" class="btn btn-lg btn-primary btn-block mb-btn mb-btn-white mb-fll-width mb-radius-square mb-mg-bottom"><i class="fa fa-phone" aria-hidden="true"></i> Call us  <?php echo $this->lib->get_settings('phone')?></a></li>
                                
                                <li class="whatsapp"><a href="https://wa.me/447477364114" class="btn btn-lg btn-primary btn-block mb-btn mb-btn-white mb-fll-width mb-radius-square mb-mg-bottom"><i class="fa fa-whatsapp" aria-hidden="true"></i> Chat with us in WhatsApp</a></li>
                                
                                <li class="linkedin"><a href="<?php echo $this->lib->get_settings('linkedin')?>" class="btn btn-lg btn-primary btn-block mb-btn mb-btn-white mb-fll-width mb-radius-square mb-mg-bottom"><i class="fa fa-linkedin" aria-hidden="true"></i> Connect us on LinkedIn</a></li>
                                
                                <li class="facebook"><a href="<?php echo $this->lib->get_settings('facebook')?>" class="btn btn-lg btn-primary btn-block mb-btn mb-btn-white mb-fll-width mb-radius-square mb-mg-bottom"><i class="fa fa-facebook" aria-hidden="true"></i> Follow us on Facebook</a></li>
                                
                                <li class="instagram"><a href="<?php echo $this->lib->get_settings('instagram')?>" class="btn btn-lg btn-primary btn-block mb-btn mb-btn-white mb-fll-width mb-radius-square mb-mg-bottom"><i class="fa fa-instagram" aria-hidden="true"></i> Follow us on Instagram</a></li>
                                
                                <li class="twitter"><a href="<?php echo $this->lib->get_settings('twitter')?>" class="btn btn-lg btn-primary btn-block mb-btn mb-btn-white mb-fll-width mb-radius-square mb-mg-bottom"><i class="fa fa-twitter" aria-hidden="true"></i> Get the latest news on Twitter</a></li>
                                
                            </ul>
                        <!-- <p class="hidden-lg hidden-md ">
                        	<br>
                            <b class="fa-lg">or</b>
                        </p> -->
                        <!-- <h3>Call us <a href="tel:<?php echo $this->lib->get_settings('phone')?>"><?php echo $this->lib->get_settings('phone')?></a></h3>
                        <p>Write to us&nbsp;<a href="mailto:<?php echo $this->lib->get_settings('email')?>"><b><?php echo $this->lib->get_settings('email')?></b></a></p>
                        <p><small><i class="fa fa-map-marker fa-fw"></i> Unit 1 Amberley way , London, TW4 6BX, United Kingdom</small></p> -->
                        
                      </div>

                    </div>
                    

                    
                    
                </div>   
                </div>           
            </div>
        </div>
    </div>
</section>
<script>
    // contact-us-form
    $(document).on("load",function(){

    });
  //  alert("dffd");
</script>

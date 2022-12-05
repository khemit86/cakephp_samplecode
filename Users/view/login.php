<?php 
use Cake\ORM\TableRegistry; 

$host = $_SERVER['HTTP_HOST'];
$subdomain = explode('.', $host)[0];
$compinfo = TableRegistry::getTableLocator()->get('Companies')->find()->where(['Companies.subdomain' => $subdomain])->first();
if(!empty($compinfo)){
    $brandings= TableRegistry::getTableLocator()->get('TenantBrandings')->find()->where(array('company_id'=>$compinfo['id']))->first();
}else{
    $brandings=TableRegistry::getTableLocator()->get('AdminBrandings')->find()->first();
}

?>
<style type="text/css">
    <?php if(@$brandings['primary_text_color']!=""):?>
        .form-control.form-control-solid{
            border-color: <?php echo $brandings['primary_text_color'];?>;
        }
        .text-hover-primary:hover ,a.text-hover-primary:hover {
            color:<?php echo $brandings['primary_text_color']?>!important
        }
    <?php endif;?> 
    <?php if(@$brandings['btn_success']!=""):?>
        .btn.btn-success{
            background: <?php echo $brandings['btn_success']?>;
            border-color: <?php echo $brandings['btn_success']?>;
        }
        .btn-success{
            background: <?php echo $brandings['btn_success']?>;
            border-color: <?php echo $brandings['btn_success']?>;
        }
        .alert.alert-success {
            background: <?php echo $brandings['btn_success']?>;
            border-color: <?php echo $brandings['btn_success']?>;
        }
        .btn.btn-success.focus:not(.btn-text), .btn.btn-success:focus:not(.btn-text), .btn.btn-success:hover:not(.btn-text):not(:disabled):not(.disabled){
            color:#fff;
            background-color:<?php echo $brandings['btn_success']?>;
            border-color:<?php echo $brandings['btn_success']?>;
        }
        .btn.btn-success:not(:disabled):not(.disabled).active, .btn.btn-success:not(:disabled):not(.disabled):active:not(.btn-text), .show .btn.btn-success.btn-dropdown, .show>.btn.btn-success.dropdown-toggle {
            color: #fff;
            background-color: <?php echo $brandings['btn_success']?>;
            border-color: <?php echo $brandings['btn_success']?>;
        }
        .btn.btn-success.disabled, .btn.btn-success:disabled {
            background-color: <?php echo $brandings['btn_success']?>;
            border-color: <?php echo $brandings['btn_success']?>;
        }
    <?php endif;?>  
    <?php if(@$brandings['btn_danger']!=""):?>
        .btn.btn-danger{
            background: <?php echo $brandings['btn_danger']?>;
            border-color: <?php echo $brandings['btn_danger']?>;
        }
        .btn-danger{
            background: <?php echo $brandings['btn_danger']?>;
            border-color: <?php echo $brandings['btn_danger']?>;
        }
        .alert.alert-danger {
            background: <?php echo $brandings['btn_danger']?>;
            border-color: <?php echo $brandings['btn_danger']?>;
        }
    <?php endif;?> 
	<?php if(@$brandings['login_page_heading_color']!=""):?>
        .login_heading{
            color: <?php echo $brandings['login_page_heading_color']?>!important;
        }
    <?php endif;?> 
	<?php if(@$brandings['login_page_paragraph_text_color']!=""):?>
        .login_subheading{
            color: <?php echo $brandings['login_page_paragraph_text_color']?> !important;
        }
    <?php endif;?> 
	
	
</style>

<div class="login-aside d-flex flex-row-auto bgi-size-cover bgi-no-repeat p-10 p-lg-10" style="background: <?php if(@$brandings['login_page_background_color']!=""){ echo $brandings['login_page_background_color']; }else{ echo 'url('.$this->Url->webroot.'/new_theme/assets/media/bg/bg-4.jpg)'; } ?>">
    <!--begin: Aside Container-->
    <div class="d-flex flex-row-fluid flex-column justify-content-between">
        <!--begin: Aside header-->
        <!-- <a class="brand" href=""><img src="<?php echo $this->Url->webroot ?>/img/logox.png" alt="logo" /> </a> -->
        <a href=""  style= "<?php if(@$brandings['login_page_background_color']!=""): ?> background: <?php echo $brandings['login_page_background_color']?><?php endif;?>" >
			<?php
			if(!empty($brandings['svg_logo_login_forgot_pass_event_page'])){
				echo $brandings['svg_logo_login_forgot_pass_event_page'];	
			}else if(!empty($brandings['svg_logo'])){
				echo $brandings['svg_logo'];	
			}else{
			?>
				<?php if(@$brandings['logo']!=""){ ?>
					<img src="<?php echo CAKEPHP_URL; ?>/img/logo/<?php echo $brandings['logo'];?>" alt="Logo" style="max-height:70px"> 
				<?php }else{ ?>
				   <img src="<?php echo CAKEPHP_URL; ?>/img/logox.png" alt="Logo"> 
				<?php } ?>
			<?php
			}
			?>
            
        </a>
        <!--end: Aside header-->
        <!--begin: Aside content-->
        <div class="flex-column-fluid d-flex flex-column justify-content-center">
            <h3 class="font-size-h1 mb-5 text-white login_heading"><?php if(@$brandings['heading']!=""){ echo $brandings['heading']; }else{ echo "Welcome to XPOBAY"; } ?></h3>
            <p class="font-weight-lighter text-white opacity-80 login_subheading"><?php if(@$brandings['sub_heading']!=""){ echo $brandings['sub_heading']; }else{ echo "The world's best online event portal that increases exhibitor satisfaction, enhances exhibitor retention, and saves exhibition organisers time and money. <br>Big time."; } ?></p>
        </div>
        <div class="d-none flex-column-auto d-lg-flex justify-content-between mt-10">
            <div class="opacity-70 font-weight-bold text-white">Powered by © <a href="https://xpobay.com" target="blank" class="opacity-70 font-weight-bold text-white">XPOBAY</a> <?php echo date('Y'); ?></div>
        </div>
        <!--end: Aside content-->
        <!--begin: Aside footer for desktop-->
       
        <!--end: Aside footer for desktop-->
    </div>
    <!--end: Aside Container-->
</div>
<!--begin::Aside-->
<!--begin::Content-->
<div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden">
    <!--begin::Content header-->
    
    <!--end::Content header-->
    <!--begin::Content body-->
    <div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
        <!--begin::Signin-->
        <div class="login-form login-signin">
            <div class="text-center mb-10 mb-lg-20">
                <h3 class="font-size-h1">Sign In</h3>
                <p class="text-muted font-weight-bold">Enter your email and password</p>
            </div>
            <!--begin::Form-->
             <?php echo $this->Flash->render() ?>
            <?php echo $this->Form->create();?>

                <div class="form-group">
                    <input class="form-control form-control-solid h-auto py-5 px-6" type="text" placeholder="Email" autocomplete="on" name="email" />
                </div>
                <div class="form-group">
                    <input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Password" name="password" autocomplete="off"/>
                </div>
                <!--begin::Action-->
                <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                    <a href="<?php echo $this->Url->webroot;?>/system/request_reset_password" class="text-dark-50 text-hover-primary my-3 mr-2" id="kt_login_forgot">Forgot Password ?</a>
                   
                    <button type="submit" id="kt_login_signin_submit" class="btn btn-success font-weight-bold px-9 py-4 my-3" name="Submit">Sign In</button>
                </div>
                <!--end::Action-->
            <?php echo $this->Form->end() ?>
            <!--end::Form-->
        </div>
        <!--end::Signin-->
        <!--begin::Signup-->
        <!--end::Signup-->
        <!--begin::Forgot-->
       
        <!--end::Forgot-->
    </div>
    <!--end::Content body-->
    <!--begin::Content footer for mobile-->
    <!-- <div class="d-flex d-lg-none flex-column-auto flex-column flex-sm-row justify-content-between align-items-center mt-5 p-5">
        <div class="text-dark-50 font-weight-bold order-2 order-sm-1 my-2">© 2020 Xpobay</div>
    </div> -->
    <!--end::Content footer for mobile-->
</div>
<!--end::Content-->

<!-- <div class="lock-head"> Login </div>
    <div class="lock-body">
        <div class="pull-left lock-avatar-block"></div>
            <?php echo $this->Form->create();?> -->
            
            <!--<h4>Amanda Smith</h4>-->
           <!--  <div class="form-group">
              <input  class="form-control placeholder-no-fix" placeholder="Email" autocomplete="on" type="text" name="email" >
            </div>

            <div class="form-group">
               <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" /> 
            </div>

            <div class="form-actions">
                <button type="submit" class="btn red uppercase login-btn" name="Submit">Login</button>
            </div>
        <?php echo $this->Form->end() ?>
    </div>
    <div class="lock-bottom">
                
    <?php echo $this->Html->link(__('Forgot Your Password?', true), array('controller'=>'system','action' => 'request_reset_password')); ?>
  </div>

</div>
 -->
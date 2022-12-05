<?php use Cake\Routing\Router; ?>
<style>
    .page-content-row .page-content-col {
    padding-left: 0px !important;
    padding-right: 20px !important;
}
.input{
  margin-bottom: 15px;
}
select {
    outline: 0!important;
    box-shadow: none!important;
    width: 23%;
    height: 34px;
    padding: 6px 12px;
    background-color: #fff;
    border: 1px solid #c2cad8;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    font-size: 14px;
    line-height: 1.42857;
    color: #555;
   
}
label {
    font-weight: 400;
    display: block;
}
.submit{
  display: inline;
}
.datepicker > div {
     display: block;
}
</style>

<?php echo $this->Html->script('/js/tiny_mce/tiny_mce'); ?>

<?php
    $compniesList = array();
    foreach($companies as $data) {
        $compniesList[$data['id']] = $data['company_name'];
    }
    
 ?>



<div class="card card-custom">
  <div class="card-header">
    <div class="card-title">
      <span class="card-icon">
        <i class="flaticon2-layers text-primary"></i>
      </span>
      <h3 class="card-label">New Event</h3>
    </div>
    <div class="card-toolbar">
      
    </div>
  </div>
  <div class="card-body">
    <?php echo $this->Form->create();?>
    <div class="form-group row">
      <div class="col-md-6">
        <?php echo $this->Form->control('name',array('class'=>'form-control','required'=>true)); ?>
        <?php echo $this->Form->control('prefix' ,array('class'=>'form-control')); ?>
        <?php echo $this->Form->control('description',array('class'=>'form-control')); ?>
        <?php echo $this->Form->control('location',array('class'=>'form-control')); ?>
        <?php echo $this->Form->control('country',array('class'=>'form-control', 'maxlength' => '2')); ?>
        <?php echo $this->Form->control('state',array('class'=>'form-control', 'maxlength' => '32')); ?>
        <?php echo $this->Form->control('city',array('class'=>'form-control')); ?>
        <?php echo $this->Form->control('zipcode',array('class'=>'form-control')); ?>

        <label for="EventStartDateMonth">Company</label>
        <select id="EventCompanyId" name="company_id" required class="form-control"  onchange="eventcompany(this.value)">
         <option value="">Select Company</option>
         <?php if($compniesList){?>
         <?php foreach($compniesList as $key=>$val){ ?>
           <option value="<?php echo $key ?>"><?php echo $val ?></option>
         <?php } ?>
       <?php }?>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <div class="col-md-6">
        <label for="EventFileDescription">Start Date</label>
        <?php //echo $this->Form->control('start_date',array('class'=>'form-control')); ?>
        <input name="start_date" class="form-control" maxlength="128" type="text"  id="kt_datepicker_3" required >
        <?php //echo $this->Form->control('end_date',array('class'=>'form-control')); ?> 
        <label for="EventFileDescription">End Date</label>
        <input name="end_date" class="form-control" maxlength="128" type="text" id="kt_datepicker_3" required>

        <p><strong>Fill in the email sender detail</strong></p>
        <p><strong>Make sure you enter a valid email address</strong></p>
        <?php
          echo $this->Form->control('event_email_sender', array('label' => 'Default Email sender name','class'=>'form-control','required'=>true));
          echo $this->Form->control('event_email_address', array('label' => 'Default Email sender address','class'=>'form-control','required'=>true));
        ?>

        <?php echo $this->Form->control('feed_security_key', array('value'=>$feed_security_key,'class'=>'form-control','readonly'=>true)); ?>
        <small>XML Feed URL: <a target="blank" href="<?php echo $xml_url= Router::url('/', true).'feeds/xmlfeed/'.$feed_security_key; ?>"><?php echo $xml_url= Router::url('/', true).'feeds/xmlfeed/'.$feed_security_key; ?></a></small><br>
        <small>JSON Feed URL: <a target="blank" href="<?php echo $xml_url= Router::url('/', true).'feeds/jsonfeed/'.$feed_security_key; ?>"><?php echo $xml_url= Router::url('/', true).'feeds/jsonfeed/'.$feed_security_key; ?></a></small>
        <!-- <br><br>
        <label for="EventThemeLayout">Select Event Theme</label>
        <select id="" name="theme_layout" required class="form-control">
           <option value="old">Metronic 1 (old)</option>
           <option value="new">Metronic 2 (new)</option>
        </select> -->
      </div>
    </div>
    <hr>
    <div class="form-group row">
      <div class="col-md-6">
        <div class="row">
          <!-- <div class="col-md-12 checkbox-inline">
            <label class="checkbox">
              <input type="checkbox" name="enable_cs_cart" value="1"> <span></span> Enable Marketplace
            </label>
          </div> -->

          <div class="col-md-12 checkbox-inline">
            <label class="checkbox">
              <input type="checkbox" name="enable_agent" value="1"> <span></span> Enable Additional Users
            </label>
          </div>

          <div class="col-md-12 checkbox-inline" style="display: none;">
            <label class="checkbox">
              <input type="checkbox" name="enable_exhibitor_invite" value="1" checked="true"> <span></span> Enable Exhibitor Marketing Toolkit
            </label>
          </div>

          <div class="col-md-12 checkbox-inline">
            <label class="checkbox">
              <input type="checkbox" name="enable_form_approval" value="1"> <span></span> Enable Form Approval
            </label>
          </div>

          <div class="col-md-12 checkbox-inline">
            <label class="checkbox">
              <input type="checkbox" name="enable_payment_forms" value="1"> <span></span> Enable Payment Forms 
            </label>
          </div>  

        </div>
      </div>
    </div>
    <hr>
    <?php if((!$this->getRequest()->getSession()->check('user.reseller_user')) || (!empty($compniesList))):?>
    <div class="form-group row">
      <div class="col-md-6">
        <div class="row">

          <div class="col-md-12 checkbox-inline">
            <label class="checkbox">
              <input type="checkbox" name="clone_another_event" value="1" id="clone_another_event"> <span></span> Clone Another Event
            </label>
          </div>

          <div class="col-md-12 event-field" style="display:none;margin-bottom: 25px;">
            <select name="cloned_event_id" class="form-control" onchange="showCloneArea(this.value)">
              <option value="">Select Event</option>
              <?php foreach($comp_events as $event){ ?>
                <option value="<?php echo $event['id'] ?>"><?php echo $event['name'] ?></option>
              <?php } ?>  
            </select>
          </div>
          
        </div>

        <div class="row clone-area" style="display:none;">
          
          <div class="col-md-12 checkbox-inline">
            <label class="checkbox">
              <input type="checkbox" name="clone_forms" value="1" id="clone_forms"> <span></span> Clone Forms
            </label>
          </div>

          <div class="col-md-12 checkbox-inline">
            <label class="checkbox">
              <input type="checkbox" name="clone_pages" value="1" id="clone_forms"> <span></span> Clone All Pages And Navigation
            </label>
          </div>

          <div class="col-md-12 checkbox-inline">
            <label class="checkbox">
              <input type="checkbox" name="clone_content_blocks" value="1" id="clone_forms"> <span></span> Clone Content Blocks
            </label>
          </div>

          <div class="col-md-12 checkbox-inline">
            <label class="checkbox">
              <!-- <input type="checkbox" name="clone_exib_types_and_stand_type" value="1" id="clone_forms"> <span></span> Clone Exhibitor Types & Stand Types -->
              <input type="checkbox" name="clone_exib_types_and_stand_type" value="1" id="clone_forms"> <span></span> Clone User Types & User Categories
            </label>
          </div>

          <div class="col-md-12 checkbox-inline">
            <label class="checkbox">
              <input type="checkbox" name="clone_branding" value="1" id="clone_forms"> <span></span> Clone Branding
            </label>
          </div>

        </div>
      </div>
    </div>
  <?php endif;?>
    <div class="form-group row">
      <div class="col-md-6">
        <a href="/events" class="btn btn-secondary mr-2">Cancel</a>
        <?php echo $this->Form->submit(__('Save',true), array('class'=>'btn btn-success')); 
        echo $this->Form->end();?>
      </div>
    </div>
  </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  $('#clone_another_event').click(function(){
     if($('#clone_another_event').prop("checked") == true){
        $('.event-field').css('display','block');
     }else{
        $('.event-field').css('display','none');
        $('.clone-area').css('display','none');
     }
  })

})

function showCloneArea(value){
   if(value){
      $('.clone-area').css('display','block');
   }else{
      $('.clone-area').css('display','none');
   }
}  
/*  $('#kt_datepicker_3').datepicker({
               rtl: KTUtil.isRTL(),
               todayBtn: "linked",
               clearBtn: true,
               todayHighlight: true,
               templates: arrows
              }); */
 function eventcompany(value){
  if(value=='add_new'){
    bootbox.dialog({
          backdrop: true,
          title: "Add Company",
          message: '<div class="row">  ' +
                    '<div class="mode col-md-12"> ' +
                      '<form id="customform">'+
                      
                      '<div class="form-group">'+
                        '<span style="">Company Name</span>'+
                        '<input type="text" name="company_name" id="company_name" class="form-control">'+
                        '<div id="err_msg"></div>'+
                      '</div>'+
                      '<div class="form-group">'+
                        '<span style="">company_description</span>'+
                        '<input type="text" name="company_description" id="company_description" class="form-control">'+
                        '<div id="err_msg"></div>'+
                      '</div>'+
                      '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                          '<div class="checkbox-inline">'+
                            '<label class="checkbox">'+
                              '<input type="checkbox" name="can_create_event" value="1" id="can_create_event" onclick="create_event()"> <span></span>'+
                                'Super user may create events'+
                            '</label>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                      '<div class="event_credit_area" style="display:none">'+
                        '<div class="form-group row">'+
                          '<div class="col-md-12">'+
                           '<?php echo $this->Form->control('event_credit', array('id'=>'event_credit', 'type'=>'number', 'min'=>'0', 'class'=>'form-control')); ?>'+
                          '</div>'+
                        '</div>'+
                        '<div class="form-group row">'+
                            '<div class="col-md-12">'+
                             '<?php echo $this->Form->control('invoice_number', array('id'=>'invoice_number', 'class'=>'form-control')); ?>'+
                           '</div>'+
                        '</div>'+
                        '<div class="form-group row">'+
                          '<div class="col-md-12">'+
                            '<div class="checkbox-inline">'+
                              '<label class="checkbox">'+
                                '<input type="checkbox" name="can_enable_agent" value="1" id="can_enable_agent"> <span></span>Super user may enable Agents'+
                              '</label>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                        '<div class="form-group row">'+
                          '<div class="col-md-12">'+
                            '<div class="checkbox-inline">'+
                              '<label class="checkbox">'+
                                  '<input type="checkbox" name="can_enable_exhibitor_invite" value="1" id="can_enable_exhibitor_invite"> <span></span>'+
                                  'Super user may enable Exhibitor Invites'+                                  
                              '</label>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+

                      '<div class="form-group"><button type="button" class="formsubmt btn btn-success" onclick="formsubmit()">Save</button></div>'+
                      
                      '</form>'+
                     
                    '</div></div>',
            });

  }
}
function create_event() {
    if($('#can_create_event').prop("checked") == true){
       $('.event_credit_area').css('display','block');
       $('#event_credit').attr('required',true);
       $('#invoice_number').attr('required',true);

    }else{
       $('.event_credit_area').css('display','none');
       $('#event_credit').attr('required',false);
       $('#invoice_number').attr('required',false);
    }
}



function formsubmit(){
  var company_name= $('#company_name').val();
  if(company_name==''){
     $('#err_msg').html('<span style="color:red">Please Enter company name</span>');
     return false;
  }
  $('.formsubmt').html('Wait.....');
  $('.formsubmt').attr('disabled',true);
  var formdata=$('#customform').serialize();
  var csrfToken = $('meta[name="csrfToken"]').attr('content');
  var path="<?php echo $this->Url->webroot ?>/events/addCompanyAjax";
    
   $.ajax({
      type:"POST",
      url:path,
      data:formdata,
      headers: {
         'X-CSRF-Token': csrfToken
      },
      success:function(result){
         var result= result.trim();
         if($.isNumeric(result)){
            $("#EventCompanyId option:last").before('<option value="'+result+'">'+company_name+'</option>');
            $("#EventCompanyId").val(result);
            bootbox.hideAll();
         }else if(result=='exist'){
            toastr.options = { "positionClass": "toast-top-right"}
            toastr.error('Invoice already exist, Choose another one.', 'Error');

            $('.formsubmt').attr('disabled',false);
            $('.formsubmt').html('Save');
         }else{
            toastr.options = { "positionClass": "toast-top-right"}
            toastr.error('Company name could not be inserted! please try again', 'Error');

            $('.formsubmt').attr('disabled',false);
            $('.formsubmt').html('Save');
         }

         
      }
   });

} 


</script>


<?php /*
<div class="page-content-container">
  <div class="page-content-row">

<!-- BEGIN PAGE SIDEBAR -->
     <div class="page-sidebar">
      <div class="col-md-12">
        <h3>Action </h3>
        <nav class="navbar" role="navigation">
          <ul class="nav navbar-nav margin-bottom-35">
              <li class="active"><?php echo $this->Html->link(__('New Event', true), array('action' => 'add')); ?></li>
              <li><?php echo $this->Html->link(__('List Events', true), array('action' => 'index')); ?></li>
          </ul>
        </nav>
      </div>
     </div>



     <div class="page-content-col">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
          <div class="col-md-12">
            <div class="portlet box blue">
              <div class="portlet-title">
                 <div class="caption">
                    <i class="fa fa-gift"></i>New Event</div>
                 <div class="tools">
                    <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
                 </div>
              </div>
              <div class="portlet-body">
                <?php echo $this->Form->create();?>
                                                            
                <legend><?php __('Add Event'); ?></legend>
                <?php echo $this->Form->control('name',array('class'=>'form-control','required'=>true)); ?>
                <?php echo $this->Form->control('prefix' ,array('class'=>'form-control')); ?>
                <?php echo $this->Form->control('description',array('class'=>'form-control')); ?>
                <?php echo $this->Form->control('location',array('class'=>'form-control')); ?>
                <?php echo $this->Form->control('country',array('class'=>'form-control', 'maxlength' => '2')); ?>
                <?php echo $this->Form->control('state',array('class'=>'form-control', 'maxlength' => '32')); ?>
                <?php echo $this->Form->control('city',array('class'=>'form-control')); ?>
                <?php echo $this->Form->control('zipcode',array('class'=>'form-control')); ?>
                  
                <?php // echo $this->Form->input('company_id',array('class'=>'form-control','empty'=> 'Select Company', 'options' => $compniesList, 'required'=>true)); ?>
                <label for="EventStartDateMonth">Company</label>
                <select id="EventCompanyId" name="company_id" required>
                 <option value="">Select Company</option>
                 <?php foreach($compniesList as $key=>$val){ ?>
                   <option value="<?php echo $key ?>"><?php echo $val ?></option>
                 <?php } ?>
                </select><br>

                <?php echo $this->Form->control('start_date',array('class'=>'form-control')); ?>
                <?php echo $this->Form->control('end_date',array('class'=>'form-control')); ?>  
                                                  
                                                            
                <br/>
                <p><strong>Fill in the email sender detail</strong></p>
                <p><strong>Make sure you enter a valid email address</strong></p>
                <?php
                  echo $this->Form->control('event_email_sender', array('label' => 'Default Email sender name','class'=>'form-control','required'=>true));
                  echo $this->Form->control('event_email_address', array('label' => 'Default Email sender address','class'=>'form-control','required'=>true));
                ?>
                 
                
                <?php echo $this->Form->control('feed_security_key', array('value'=>$feed_security_key,'class'=>'form-control','readonly'=>true)); ?>
                <small>XML Feed URL: <a target="blank" href="<?php echo $xml_url= Router::url('/', true).'feeds/xmlfeed/'.$feed_security_key; ?>"><?php echo $xml_url= Router::url('/', true).'feeds/xmlfeed/'.$feed_security_key; ?></a></small>

                <!-- <div class="input text">
                  <input type="checkbox" name="data[Event][enable_cs_cart]" value="1"> Enable Marketplace
                </div> -->

                <hr>
                <div class="row">
                  <div class="col-md-12">
                    <label class="mt-checkbox">
                      <input type="checkbox" name="enable_cs_cart" value="1"> Enable Marketplace
                      <span></span>
                    </label>
                  </div>

                  <div class="col-md-12">
                    <label class="mt-checkbox">
                      <input type="checkbox" name="enable_agent" value="1"> Enable Additional Users
                      <span></span>
                    </label>
                  </div>

                  <div class="col-md-12">
                    <label class="mt-checkbox">
                      <input type="checkbox" name="enable_exhibitor_invite" value="1"> Enable Exhibitor Invites
                      <span></span>
                    </label>
                  </div>

                  <div class="col-md-12">
                    <label class="mt-checkbox">
                      <input type="checkbox" name="enable_form_approval" value="1"> Enable Form Approval
                      <span></span>
                    </label>
                  </div>

                </div>
                

                 <hr>

                <div class="row">

                  <div class="col-md-12">
                    <label class="mt-checkbox">
                      <input type="checkbox" name="clone_another_event" value="1" id="clone_another_event"> Clone Another Event
                      <span></span>
                    </label>
                  </div>

                  <div class="col-md-5 event-field" style="display:none;margin-bottom: 25px;">
                    <select name="cloned_event_id" class="form-control" onchange="showCloneArea(this.value)">
                      <option value="">Select Event</option>
                      <?php foreach($comp_events as $event){ ?>
                        <option value="<?php echo $event['id'] ?>"><?php echo $event['name'] ?></option>
                      <?php } ?>  
                    </select>
                  </div>
                  
                </div>

                <div class="row clone-area" style="display:none;">
                  
                  <div class="col-md-12">
                    <label class="mt-checkbox">
                      <input type="checkbox" name="clone_forms" value="1" id="clone_forms"> Clone Forms
                      <span></span>
                    </label>
                  </div>

                  <div class="col-md-12">
                    <label class="mt-checkbox">
                      <input type="checkbox" name="clone_pages" value="1" id="clone_forms"> Clone All Pages And Navigation
                      <span></span>
                    </label>
                  </div>

                  <div class="col-md-12">
                    <label class="mt-checkbox">
                      <input type="checkbox" name="clone_content_blocks" value="1" id="clone_forms"> Clone Content Blocks
                      <span></span>
                    </label>
                  </div>

                  <div class="col-md-12">
                    <label class="mt-checkbox">
                      <input type="checkbox" name="clone_exib_types_and_stand_type" value="1" id="clone_forms"> Clone Exhibitor Types & Stand Types
                      <span></span>
                    </label>
                  </div>

                  <div class="col-md-12">
                    <label class="mt-checkbox">
                      <input type="checkbox" name="clone_branding" value="1" id="clone_forms"> Clone Branding
                      <span></span>
                    </label>
                  </div>

                </div>
                                                            
                 <br><br>
                 <?php echo $this->Form->submit(__('Submit',true), array('class'=>'btn btn-success')); 
                 echo $this->Form->end();?>
                </div>
            </div>
          </div>
        </div>
      </div>
         
  </div>
 </div>
 */?>
<!-- END PAGE SIDEBAR -->
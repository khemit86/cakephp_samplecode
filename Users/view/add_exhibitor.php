<style type="text/css">
 dd {
    margin-bottom: 10px;
}


.checkboxMultiple > li {
    float: left;
    list-style: outside none none;
    padding: 20px 70px 21px 0;
}

ul.checkboxMultiple {
    padding-left: 0px;
     width: 100%;
}

.modal-dialog {
    margin: 156px auto 30px;
    width: 600px;
}   
 #toast-container>div{
    opacity: 1;
}
</style>

<div class="card card-custom">
  <div class="card-header">
    <div class="card-title">
      <span class="card-icon">
        <i class="fa fa-user text-primary"></i>
      </span>
      <h3 class="card-label">Add User</h3>
    </div>
    <div class="card-toolbar">
    </div>
  </div>
</div>
    <?php if($step == "step1"):?>
      <div class="card card-custom">
        <div class="card-body">
          <?php echo $this->Form->create(null,array('id'=>'UserAddExhibitorForm','url'=>array('action'=>$this->request->getParam('action'), 'step'=>'step2')));?>
            <div class="row form-body">
              <div class="col-md-6">
                <div class="form-group">
                   <label><p>Please enter the User email address<br/>
                    Note: Each email address can only associcated to one person/User</p>
                 </label>
                 
                   <div class="input-group">
                      <input type="text" name="email" class="form-control" id="UserEmail" placeholder="Email" maxlength="120">
                      <div class="input-group-append">
                        <button class="btn btn-secondary" type="button"><span class="input-group-addon"><i class="fa fa-envelope font-red"></i>
                        </span></button>
                      </div>
                      
                   </div>
                </div>
              </div>
            </div>

            <div class="row form-body">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <?php echo $this->Html->link('Cancel',array('controller'=>'users','action'=>'index'),array('class'=>'btn btn-secondary mr-3')) ?> 
                    <button type="submit" class="btn btn-success">Continue</button>
                  </div>
                </div>
              </div>
            </div>

          <?php echo $this->Form->end();?>
        </div>
      </div>
    <!-- ========= Step 2 ===========  -->
    <?php elseif($step == "step2"):?>
        <!-- =========================================  -->
          <link href="<?php echo $this->Url->webroot ?>/assets/pages/css/profile.min.css" rel="stylesheet" type="text/css"/>
          <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
          <style type="text/css">
            dd {
                margin-bottom: 10px;
            }
            .newclass{
              min-height:87px;
              margin-bottom:25px;
              padding:10px;
              position:relative;
            }
            .modal-content {
                margin-top: 150px;
            }
            #label_ex_type{
               display: none; 
            }

            .checkboxMultiple > li {
              float: left;
              list-style: outside none none;
              padding: 0px 5px 5px 0;
              width: 50%;
            }

            ul.checkboxMultiple {
                padding-left: 0px;
            }

            .control-label .required, .form-group .required{
              color: #333;
            }

            /*.checkboxMultiple > li {
                float: left;
                list-style: outside none none;
                padding: 0px 35px 5px 0;
            }*/

            .profile-userpic img{
               border-radius: 1%!important;
            }
          </style>
            <?php echo $this->Form->create(null, array('id'=>'UserAddExhibitorForm','type' => 'file','url'=>array('action'=>$this->request->getParam('action'), "step2")));?>
              <input type="hidden" name="uid" value="<?=$user['id']?>"/>
              <div class="row" style="margin-top: 1.5%;">
                <div class="col-xl-3">
                  <div class="card card-custom">
                      <div class="card-body">
                        <div class="portlet profile-sidebar-portlet borderedss">
                          <div class="profile-userpic">
                            
                            <?php if($user['logo']){ ?> 
                              <input type="file" name="logo" id="input-file-blogo" class="dropify" data-default-file="<?php echo $this->Url->webroot.'/img/logo/'.$user['logo'] ?>"/>
                            <?php }else{ ?>
                              <input type="file" name="logo" id="input-file-blogo" class="dropify" data-default-file="<?php echo $this->Url->webroot.'/img/logo/no-logo.png'; ?>"/>
                            <?php } ?>  
                            
                          </div>
                          
                          <div class="profile-usertitle">
                            <div class="profile-usertitle-name"> <?php echo $user['firstname']." ".$user['lastname'] ?> </div>
                            <div class="profile-usertitle-job"> <?php // echo $this->data['ExhibitionRegistration']['booth_name'] ?> </div>
                          </div>
                        </div>
                        <div class="portlet bordered">
                                
                          <div class=" list-separated profile-stat">
                            <div class="checkbox-inline" style="text-align:center;">
                               <label class="checkbox">
                               <input type="checkbox" name="send_email" value="yes"><span></span>
                                Send Welcome Email
                                
                               </label>
                            </div>
                          </div>
                          
                        </div>
                      </div>
                  </div>
                </div>

                <div class="col-xl-9">
                  <div class="card card-custom">
                    <div class="card-body">
                      <div class="">
                       
                        <div class="col-md-12">
                          <div class="card card-custom gutter-b"  style="box-shadow: none;">
                            <!--begin::Header-->
                            <div class="card-header card-header-tabs-line custom">
                              <div class="card-toolbar">
                                <ul class="nav nav-tabs nav-tabs-space-lg nav-tabs-line nav-bold nav-tabs-line-3x" role="tablist">
                                  <li class="nav-item active">
                                    <a class="nav-link active" data-toggle="tab" href="#kt_apps_contacts_view_tab_1">
                                      <span class="nav-text">Contact Details</span>
                                    </a>
                                  </li>
                                  <?php //if($is_event_stand==1){ ?>
                                    <li class="nav-item mr-3">
                                      <a class="nav-link" data-toggle="tab" href="#kt_apps_contacts_view_tab_2">
                                        <span class="nav-text">Stand Details</span>
                                      </a>
                                    </li>
                                  <?php //}?>
                                  <li class="nav-item mr-3">
                                    <a class="nav-link" data-toggle="tab" href="#kt_apps_contacts_view_tab_4">
                                      <span class="nav-text">User Types & Categories</span>
                                    </a>
                                  </li>
                                  <li class="nav-item mr-3" style="display: <?php echo (empty($custom_fields)) ? 'none' : 'block'; ?>">
                                    <a class="nav-link" data-toggle="tab" href="#kt_apps_contacts_view_tab_3">
                                      <span class="nav-text">Custom Fields</span>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body px-0">
                              <div class="row">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-8">
                                  <div class="tab-content pt-5">
                                    <div class="tab-pane active" id="kt_apps_contacts_view_tab_1" role="tabpanel">
                                      
                                      <div class="caption caption-md">
                                        <i class="fa fa-phone theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase tab-heading font-weight-bold">Contact Details</span>
                                        <hr>
                                      </div>
                                      
                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label class="control-label">First Name</label>
                                            <?php echo $this->Form->input('firstname',array('value'=>$user['firstname'],'class'=>'form-control','label'=>false)); ?> 
                                          </div>
                                        </div>
                                             
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label class="control-label">Last Name</label>
                                            <?php echo $this->Form->input('lastname',array('value'=>$user['lastname'],'class'=>'form-control','label'=>false)); ?>
                                          </div>
                                        </div>   
                                      </div>

                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label class="control-label">Email</label>
                                            <?php echo $this->Form->input('email', array('value'=>$user['email'], 'disabled'=>'disabled','id'=>'emaildisplay', 'name'=>'emaildisplay','class'=>'form-control','label'=>false));
                                                             ?>
                                          </div>
                                        </div>
                                             
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label class="control-label">Company</label>
                                            <?php echo $this->Form->input('company_name',array('value'=>$user['company_name'],'class'=>'form-control', 'label' => false)); ?>
                                          </div>
                                        </div>   
                                      </div>

                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label class="control-label">Mobile</label>
                                            <?php echo $this->Form->input('contact_mob',array('value'=>$user['contact_mob'],'class'=>'form-control', 'label'=>false)); ?> 
                                          </div>
                                        </div>
                                             
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label class="control-label">Telephone</label>
                                            <input type="text" id="UserContactTel" maxlength="39" name="contact_tel_num" class="form-control" value="<?php echo $user['contact_tel_num'] ?>">
                                          </div>
                                        </div>   
                                      </div>

                                      <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label class="control-label">External Username</label>
                                              <?php echo $this->Form->input('external_username',array('value'=>$user['external_username'],'class'=>'form-control','label'=>false)) ?>
                                            </div>
                                        </div>
                                             
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label class="control-label">External Password</label>
                                            <?php echo $this->Form->input('external_password',array('value'=>$user['external_password'],'class'=>'form-control','label'=>false)) ?>
                                          </div>
                                        </div>   
                                      </div>
                                         
                                      <!-- <h3 class="form-section">Address</h3> <hr> -->
                                      
                                      <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison text-bold uppercase font-weight-bold">Address</span>
                                      </div><hr>

                                      <div class="row">
                                        <div class="col-md-12 ">
                                          <div class="form-group">
                                            <label>Street</label>
                                            <?php echo $this->Form->input('company_addr_st',array('value'=>$user['company_addr_st'],'class'=>'form-control', 'label' =>false)); ?>
                                          </div>
                                         </div>
                                      </div>

                                      <div class="row">
                                        <div class="col-md-6">
                                           <div class="form-group">
                                              <label>City</label>
                                              <?php echo $this->Form->input('company_addr_city',array('value'=>$user['company_addr_city'],'class'=>'form-control', 'label'=>false)); ?> 
                                           </div>
                                        </div>
                                          
                                        <div class="col-md-6">
                                           <div class="form-group">
                                              <label>State</label>
                                              <?php $state = array( 'ACT' => 'ACT','NSW' => 'NSW','NT' => 'NT','QLD'  => 'QLD','SA' => 'SA','TAS' => 'TAS','VIC' => 'VIC','WA' => 'WA','Other' => 'Other');
                                                                echo $this->Form->input('company_addr_state', array('value'=>$user['company_addr_state'],'class'=>'form-control','label'=>false));
                                              ?> 
                                           </div>
                                        </div>
                                      </div>

                                      <div class="row">
                                        <div class="col-md-6">
                                           <div class="form-group">
                                              <label>Post Code</label>
                                              <?php echo $this->Form->input('company_addr_postcode',array('value'=>$user['company_addr_postcode'],'class'=>'form-control', 'label' => false)); ?>
                                           </div>
                                        </div>
                                         
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label>Country</label>
                                              <span style="display:none">
                                                <?php echo $this->Lang->countrySelect('company_addr_country', array(
                                                                      'label' => __('Choose a Country', true),
                                                                      'default' => 'au',
                                                                      'class'=>'form-control',
                                                                      'label' => false
                                                                    )); ?>
                                              </span> 
                                              <input type="text" id="UserCompanyAddrCountryText" name="data[company_addr_country]" class="form-control" value="<?php echo $user['company_addr_country'] ?>">
                                            </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-12 ">
                                            <div class="form-group">
                                                  <label class="control-label">Preferred Language</label>
                                                  <select name="preferred_language" id="UserPreferredLanguage" class="form-control">
                                                     <?php foreach($languages as $lang){ ?>
                                                     <option value="<?php echo $lang ?>"><?php echo $lang ?></option> 
                                                     <?php } ?>
                                                  </select>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="kt_apps_contacts_view_tab_2" role="tabpanel">
                                      <div class="caption caption-md">
                                        <i class="fa fa-check theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase tab-heading font-weight-bold">Stand Details</span>
                                        <hr>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label class="control-label">Stand Name</label>
                                              <?php echo $this->Form->input('booth_name', array('name'=>'booth_name','value'=> '','class'=>'form-control','label'=>false));?>
                                            </div>
                                            <div class="form-group">
                                              <label class="control-label">Stand Number <?php if($is_event_stand==1){ ?><span class="text-danger">*</span><?php }?></label>
                                              <?php echo $this->Form->input('booth_no', array('name'=>'booth_no','value'=>'','class'=>'form-control','label'=>false,'id'=>'std_no'));?>
                                            </div>
                                            <?php /*<div class="form-group">
                                              <label class="control-label">Stand Type <span class="text-danger">*</span></label>
                                              <select name="booth_type_id" id="UserEventBoothTypeId" onchange="add_new_stand_type(this.value)" class="form-control">
                                                <option value=""></option>
                                                <?php foreach($eventBoothTypes as $key=>$val){ ?>
                                                <option value="<?php echo $key ?>" <?php echo ($key==$default_booth_type) ? 'selected' : ''; ?>><?php echo $val ?></option>
                                                <?php } ?>
                                                <option value="" disabled>----------------------------</option>
                                                <option value="add_new"> + Add new</option>
                                              </select>
                                            </div>*/?>
                                            <?php /*<div class="form-group">
                                              <label class="control-label">User Categories <span class="text-danger">*</span></label>
                                              <?php echo $this->Enthtml->checkboxMultiple('User Categories', "booth_type_id", $eventBoothTypes, '', true); ?>
                                           </div>
                                            <div class="form-group">
                                              <label class="control-label">Exhibitor Type <span class="text-danger">*</span></label>
                                              <?php echo $this->Enthtml->checkboxMultiple('Exhibitor Type', "event_exhibitor_types", $eventExhibitorTypes, '', true);?>
                                            </div>

                                            <div class="row" style="margin-bottom:40px;">
                                              <div class="col-md-12">
                                                <a href="javascript:void(0)" onclick="add_exhibitor_type()"> + Add Exhibitor type</a>
                                              </div>
                                            </div>*/?>
                                        </div>

                                        <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label">Stand Location</label>
                                                <select name="event_location_id" id="UserEventLocation" onchange="add_new_location(this.value)" class="form-control">
                                                  <option value=""></option>
                                                  <?php foreach($eventLocations as $key=>$val){ ?>
                                                  <option value="<?php echo $key ?>"><?php echo $val ?></option>
                                                  <?php } ?>
                                                  <option value="" disabled>----------------------------</option>
                                                  <option value="add_new"> + Add new</option>
                                                </select>  
                                          </div>
                                          <div class="form-group">
                                              <label class="control-label">Stand Dimension</label>
                                              <select name="event_dimension_id" id="UserEventDimension" onchange="add_new_dimenssion(this.value)" class="form-control">
                                                <option value=""></option>
                                                <?php foreach($eventDimensions as $key=>$val){ ?>
                                                <option value="<?php echo $key ?>"><?php echo $val ?></option>
                                                <?php } ?>
                                                <option value="" disabled>----------------------------</option>
                                                <option value="add_new"> + Add new</option>
                                              </select>
                                          </div>
                                          
                                          <div class="form-group" style="display:none">
                                            <label class="control-label">Promo Code</label>
                                            <input type="text" name="promo_code" class="form-control">
                                          </div>

                                        </div> 
                                      </div>
                                    </div>
                                    <div class="tab-pane" id="kt_apps_contacts_view_tab_4" role="tabpanel">
                                      <div class="caption caption-md">
                                        <i class="fa fa-check theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase tab-heading font-weight-bold">User Types & Categories</span>
                                        <hr>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-6">
                                           <div class="form-group">
                                              <label class="control-label">User Types <span class="text-danger">*</span></label>
                                              <?php echo $this->Enthtml->checkboxMultiple('Exhibitor Type', "event_exhibitor_types", $eventExhibitorTypes, '', true);?>
                                              <div class="row" style="margin-bottom:40px;">
                                              <div class="col-md-12">
                                                <a href="javascript:void(0)" onclick="add_exhibitor_type()"> + Add User type</a>
                                              </div>
                                            </div>
                                           </div>
                                        
                                          <div class="form-group">
                                            <label class="control-label">User Categories <span class="text-danger">*</span></label>
                                            <?php echo $this->Enthtml->checkboxMultiple('User Categories', "booth_type_id", $eventBoothTypes, '', true); ?>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="tab-pane" id="kt_apps_contacts_view_tab_3" role="tabpanel">
                                      <div class="row">
                                        <div class="col-md-6">
                                          <?php if(empty($custom_fields)){ ?>
                                              <div class="form-group no-more-div">Custom fields not found</div>
                                          <?php } ?>
                                          <?php foreach($custom_fields as $field){ ?>
                                            <div class="form-group row">        
                                              <div class="col-md-10" style="margin-bottom: 15px;">
                                              <label>
                                                <?php echo $field['field_key'] ?>
                                              </label>
                                              <input type="text" name="<?php echo $field['field_key'] ?>" class="form-control">
                                              </div> 
                                            </div>
                                            <?php } ?>
                                            <div class="custom-field-div"></div>     
                                        </div>  
                                      </div>
                                    </div>
                                  </div>
                                  <div class="form-actions right">
                                    <hr>
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <button type="button" class="btn btn-success save-continue mr-2"> Save and continue</button>
                                        <button type="button" class="btn btn-success save-btn" style="display:none"> Save</button>
                                      </div>
                                    </div> 
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php echo $this->Form->end();?>
          

    <?php endif; ?>

 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  $('.dropify').dropify();

  var val = '<?php echo @$default_exhib_type; ?>';
  $('input:checkbox[value="' + val + '"]').attr('checked', true);

  $('.nav-link').click(function(){
      $(this).parent('li').addClass('active');
    });
});


//===============Event Stand Type===========================
function add_new_stand_type(val){
    if(val=="add_new"){
      bootbox.dialog({
          backdrop: true,
          title: "Add Event User Category",
          message: '<div class="row">  ' +
                    '<div class="mode col-md-12"> ' +
                      '<form id="customform">'+
                      
                      '<div class="form-group">'+
                        '<span style="">Event User Category</span>'+
                        '<input type="text" name="stand_type" id="stand_type" class="form-control" maxlength="45">'+
                        '<div id="err_msg"></div>'+
                      '</div>'+

                      '<div class="form-group"><button type="button" class="formsubmt btn btn-success" onclick="formsubmit()">Save</button></div>'+
                      
                      '</form>'+
                     
                    '</div></div>',
            });

          // remove error msg
          $('#stand_type').click(function(){
            $('#err_msg').html('');
          }) 
          
    }
}

function formsubmit(){
  var stand_type= $('#stand_type').val();
  if(stand_type==''){
     $('#err_msg').html('<span style="color:red">Please Enter User Category</span>');
     return false;
  }
  $('.formsubmt').html('Wait.....');
  $('.formsubmt').attr('disabled',true);
  var formdata=$('#customform').serialize();
  var csrfToken = $('meta[name="csrfToken"]').attr('content');
  var path="<?php echo $this->Url->webroot ?>/users/addStandTypeAjax";
    
   $.ajax({
      type:"POST",
      url:path,
      data:formdata,
      headers: {
         'X-CSRF-Token': csrfToken
      },
      success:function(result){
         var result= result.trim();
         if(isNumeric(result)){
            $("#UserEventBoothTypeId option:last").before('<option value="'+result+'">'+stand_type+'</option>');
            $("#UserEventBoothTypeId").val(result);
            bootbox.hideAll();
         }else if(result=='exist'){
            toastr.options = { "positionClass": "toast-top-right"}
            toastr.error('Stand name already exist, Choose another one.', 'Error');

            $('.formsubmt').attr('disabled',false);
            $('.formsubmt').html('Save');
         }else{
            toastr.options = { "positionClass": "toast-top-right"}
            toastr.error('Stand name could not be inserted! please try again', 'Error');

            $('.formsubmt').attr('disabled',false);
            $('.formsubmt').html('Save');
         }

         
      }
   });

} 

//===================Event Dimension====================

function add_new_dimenssion(val){
    if(val=="add_new"){
      bootbox.dialog({
          backdrop: true,
          title: "Add Event Dimension",
          message: '<div class="row">  ' +
                    '<div class="mode col-md-12"> ' +
                      '<form id="customform">'+
                      
                      '<div class="form-group">'+
                        '<span style="">Event Dimension</span>'+
                        '<input type="text" name="dimension" id="dimension" class="form-control" maxlength="45">'+
                        '<div id="err_msg"></div>'+
                      '</div>'+

                      '<div class="form-group"><button type="button" class="formsubmt btn btn-success" onclick="dimensionsubmit()">Save</button></div>'+
                      
                      '</form>'+
                     
                    '</div></div>',
            });

          // remove error msg
          $('#dimension').click(function(){
            $('#err_msg').html('');
          }) 
          
    }
}

function dimensionsubmit(){
  var dimension= $('#dimension').val();
  if(dimension==''){
     $('#err_msg').html('<span style="color:red">Please Enter Dimension</span>');
     return false;
  }
  $('.formsubmt').html('Wait.....');
  $('.formsubmt').attr('disabled',true);
  var formdata=$('#customform').serialize();
  var csrfToken = $('meta[name="csrfToken"]').attr('content');
  var path="<?php echo $this->Url->webroot ?>/users/addDimensionAjax";
    
   $.ajax({
      type:"POST",
      url:path,
      data:formdata,
      headers: {
        'X-CSRF-Token': csrfToken
      },
      success:function(result){
         var result= result.trim();
         if(isNumeric(result)){
            $("#UserEventDimension option:last").before('<option value="'+result+'">'+dimension+'</option>');
            $("#UserEventDimension").val(result);
            bootbox.hideAll();
         }else if(result=='exist'){
            toastr.options = { "positionClass": "toast-top-right"}
            toastr.error('Dimension name already exist, Choose another one.', 'Error');

            $('.formsubmt').attr('disabled',false);
            $('.formsubmt').html('Save');
         }else{
            toastr.options = { "positionClass": "toast-top-right"}
            toastr.error('Dimension name could not be inserted! please try again', 'Error');

            $('.formsubmt').attr('disabled',false);
            $('.formsubmt').html('Save');
         }

         
      }
   });

} 

//==============Event Location==================

function add_new_location(val){
    if(val=="add_new"){
      bootbox.dialog({
          backdrop: true,
          title: "Add Event Location",
          message: '<div class="row">  ' +
                    '<div class="mode col-md-12"> ' +
                      '<form id="customform">'+
                      
                      '<div class="form-group">'+
                        '<span style="">Event Location</span>'+
                        '<input type="text" name="location" id="location" class="form-control" maxlength="45">'+
                        '<div id="err_msg"></div>'+
                      '</div>'+

                      '<div class="form-group"><button type="button" class="formsubmt btn btn-success" onclick="locationsubmit()">Save</button></div>'+
                      
                      '</form>'+
                     
                    '</div></div>',
            });

          // remove error msg
          $('#location').click(function(){
            $('#err_msg').html('');
          }) 
          
    }
}

function locationsubmit(){
  var location= $('#location').val();
  if(location==''){
     $('#err_msg').html('<span style="color:red">Please Enter Location</span>');
     return false;
  }
  $('.formsubmt').html('Wait.....');
  $('.formsubmt').attr('disabled',true);
  var formdata=$('#customform').serialize();
  var csrfToken = $('meta[name="csrfToken"]').attr('content');
  var path="<?php echo $this->Url->webroot ?>/users/addLocationAjax";
    
   $.ajax({
      type:"POST",
      url:path,
      data:formdata,
      headers: {
        'X-CSRF-Token': csrfToken
      },
      success:function(result){
         var result= result.trim();
         if(isNumeric(result)){
            $("#UserEventLocation option:last").before('<option value="'+result+'">'+location+'</option>');
            $("#UserEventLocation").val(result);
            bootbox.hideAll();
         }else if(result=='exist'){
            toastr.options = { "positionClass": "toast-top-right"}
            toastr.error('Location name already exist, Choose another one.', 'Error');

            $('.formsubmt').attr('disabled',false);
            $('.formsubmt').html('Save');
         }else{
            toastr.options = { "positionClass": "toast-top-right"}
            toastr.error('Location name could not be inserted! please try again', 'Error');

            $('.formsubmt').attr('disabled',false);
            $('.formsubmt').html('Save');
         }

         
      }
   });

} 

//==============Add exhibitor type================

function add_exhibitor_type(){
  bootbox.dialog({
          backdrop: true,
          title: "Add User Type",
          message: '<div class="row">  ' +
                    '<div class="mode col-md-12"> ' +
                      '<form id="customform">'+
                      
                      '<div class="form-group">'+
                        '<span style="">User Type</span>'+
                        '<input type="text" name="exhibitor_type" id="exhibitor_type" class="form-control" maxlength="45">'+
                        '<div id="err_msg"></div>'+
                      '</div>'+

                      '<div class="form-group"><button type="button" class="formsubmt btn btn-success" onclick="exhibitorTypeSubmit()">Save</button></div>'+
                      
                      '</form>'+
                     
                    '</div></div>',
  });

  // remove error msg
  $('#exhibitor_type').click(function(){
    $('#err_msg').html('');
  }) 

}


function exhibitorTypeSubmit(){
  var exhibitor_type= $('#exhibitor_type').val();
  if(exhibitor_type==''){
     $('#err_msg').html('<span style="color:red">Please Enter User Type</span>');
     return false;
  }
  $('.formsubmt').html('Wait.....');
  $('.formsubmt').attr('disabled',true);
  var formdata=$('#customform').serialize();
  var csrfToken = $('meta[name="csrfToken"]').attr('content');
  var path="<?php echo $this->Url->webroot ?>/users/addExhibitorTypeAjax";
    
   $.ajax({
      type:"POST",
      url:path,
      data:formdata,
      headers: {
         'X-CSRF-Token': csrfToken
      },
      success:function(result){
         var result= result.trim();
         if(isNumeric(result)){
            //$(".checkboxMultiple").append('<li><input type="checkbox" name="event_exhibitor_types[]" value="'+result+'" checked> '+exhibitor_type+'</li>');

            $(".checkboxMultiple").append('<li><div class="checkbox-inline">'+
                               '<label class="checkbox">'+
                               '<input type="checkbox" name="event_exhibitor_types[]" value="'+result+'" checked><span></span> &nbsp;'+exhibitor_type+''+
                               '</label>'+
                            '</div></li>');
            bootbox.hideAll();
         }else if(result=='exist'){
            toastr.options = { "positionClass": "toast-top-right"}
            toastr.error('User type already exist, Choose another one.', 'Error');

            $('.formsubmt').attr('disabled',false);
            $('.formsubmt').html('Save');
         }else{
            toastr.options = { "positionClass": "toast-top-right"}
            toastr.error('User type could not be inserted! please try again', 'Error');

            $('.formsubmt').attr('disabled',false);
            $('.formsubmt').html('Save');
         }

         
      }
   });

}

//=============================================
function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
} 


$(document).ready(function(){
  var i=1;
  $('.checkboxMultiple').each(function(){
    $(this).attr('id','checkbox'+i);
    i++;
  });
   var isCustom= '<?php echo (empty($custom_fields)) ? "no" : "yes" ?>';

   $('.add_custom_field_tab').click(function(){
     $('.tab_custom_field').css('display','block');
     $('.tab_a_custom_field').trigger('click');
     isCustom="yes";
   }) 

   //========

   $('ul.nav-tabs li').click(function(e){ 
     var tab =$(this).find('.nav-text').html();
     $('.tab-heading').html(tab);
     
     if(tab=='Contact Details'){
        $('.save-continue').css('display','inline-block');
        $('.save-btn').css('display','none');
        $('.save-continue').removeAttr('disabled'); 
     }else if(tab=='Stand Details'){
       $('.save-btn').css('display','inline-block');
       $('.save-continue').removeAttr('disabled');
       /*if(isCustom=='no'){
          $('.save-continue').css('display','none');
       }else{
          $('.save-continue').css('display','inline-block');
       }*/
       $('.save-continue').css('display','inline-block');
       $('.save-btn').css('display','none');
       var std_no = $('#std_no').val();
       var std_type = $('#UserEventBoothTypeId option:selected').val();
        //if(std_no=="" || std_type=="" || $('.checkboxMultiple :checkbox:checked').length > 0){
      <?php if($is_event_stand==1){ ?>
        if(std_no==""){
          $('.save-continue').attr('disabled','true');
          $('.save-btn').attr('disabled','true');
        }
      <?php }?>
    }else if(tab=='User Types &amp; Categories'){
       $('.save-btn').css('display','inline-block');
       $('.save-btn').removeAttr('disabled');
       if(isCustom=='no'){
          $('.save-continue').css('display','none');
       }else{
          $('.save-continue').css('display','inline-block');
       }
      // var std_no = $('#std_no').val();
       //var std_type = $('#UserEventBoothTypeId option:selected').val();
        /*if($('.checkboxMultiple :checkbox:checked').length > 0){
          $('.save-continue').attr('disabled','true');
          $('.save-btn').attr('disabled','true');
        }*/
        if($('#checkbox1 :checkbox:checked').length<=0 || $('#checkbox2 :checkbox:checked').length<=0){
          $('.save-continue').attr('disabled','true');
          $('.save-btn').attr('disabled','true');
        }
    }else if(tab=='Custom Fields'){
       $('.save-continue').css('display','none');
       $('.save-btn').css('display','inline-block');
    }
     
  });
  //========= 

  $('.save-btn').click(function(){ 
    $('#UserAddExhibitorForm').submit();
  });

  $('.save-continue').click(function(){
    var selected= $('ul.nav-tabs').find('li.active'); 
    
    var tab= selected.next('li').find('span').html();

    if(tab=='Stand Details'){
       $('.save-continue').removeAttr('disabled');
       /*if(isCustom=='no'){
          $('.save-continue').css('display','none');
       }else{
          $('.save-continue').css('display','inline-block');
       }*/
       $('.save-continue').css('display','inline-block');
       $('.save-btn').css('display','none');
       var std_no = $('#std_no').val();
       var std_type = $('#UserEventBoothTypeId option:selected').val();
        //if(std_no=="" || std_type=="" || $('.checkboxMultiple :checkbox:checked').length > 0){
      <?php if($is_event_stand==1){ ?>
        if(std_no==""){
          $('.save-continue').attr('disabled','true');
          $('.save-btn').attr('disabled','true');
        }
      <?php }?>
    }
    if(tab=='User Types &amp; Categories'){
       $('.save-btn').removeAttr('disabled');
       if(isCustom=='no'){
          $('.save-continue').css('display','none');
       }else{
          $('.save-continue').css('display','inline-block');
       }
       //var std_no = $('#std_no').val();
      // var std_type = $('#UserEventBoothTypeId option:selected').val();
        /*if($('.checkboxMultiple :checkbox:checked').length > 0){
          $('.save-continue').attr('disabled','true');
          $('.save-btn').attr('disabled','true');
        }*/
        if($('#checkbox1 :checkbox:checked').length<=0 || $('#checkbox2 :checkbox:checked').length<=0){
          $('.save-continue').attr('disabled','true');
          $('.save-btn').attr('disabled','true');
        }
    }

    if(tab=='Custom Fields'){
       $('.save-continue').css('display','none');
    }
    selected.removeClass('active'); 
    selected.next('li').find('a').trigger('click');
    selected.next('li').addClass('active'); 
  });
  $('.col-xl-9 .nav-tabs li').click(function(){
    $('.col-xl-9 .nav-tabs .nav-item').removeClass('active');
    $(this).addClass('active');
  });
  $('#std_no').keyup(function(){
    <?php if($is_event_stand==1){ ?>
    if($('#std_no').val()!=""){
      //if($('#UserEventBoothTypeId option:selected').val()!=""){
        //if($('.checkboxMultiple :checkbox:checked').length>0){
          $('.save-continue').removeAttr('disabled');
          $('.save-btn').removeAttr('disabled'); 
        //}
      //}
    }else{
        $('.save-continue').attr('disabled','true');
        $('.save-btn').attr('disabled','true');
     }
   <?php }?>
  });
  /*$('#UserEventBoothTypeId').change(function(){
    if($('#UserEventBoothTypeId option:selected').val()!=""){
      if($('#std_no').val()!=""){
        if($('.checkboxMultiple :checkbox:checked').length>0){
          $('.save-continue').removeAttr('disabled'); 
          $('.save-btn').removeAttr('disabled'); 
        }
      }
    }else{
        $('.save-continue').attr('disabled','true');
        $('.save-btn').attr('disabled','true');
     }
  });*/

  /*$('.checkboxMultiple :checkbox').click(function(){
     if($('.checkboxMultiple :checkbox:checked').length>0){
      if($('#UserEventBoothTypeId option:selected').val()!=""){
        if($('#std_no').val()!=""){
          $('.save-continue').removeAttr('disabled'); 
          $('.save-btn').removeAttr('disabled'); 
        }
      }
     }else{
        $('.save-continue').attr('disabled','true');
        $('.save-btn').attr('disabled','true');
     }
  });*/
  $('#checkbox2 :checkbox').click(function(){
     if($('#checkbox2 :checkbox:checked').length>0){
      if($('#checkbox1 :checkbox:checked').length>0){
          $('.save-continue').removeAttr('disabled'); 
          $('.save-btn').removeAttr('disabled'); 
      }
     }else{
        $('.save-continue').attr('disabled','true');
        $('.save-btn').attr('disabled','true');
     }
  });
  $('#checkbox1 :checkbox').click(function(){
     if($('#checkbox1 :checkbox:checked').length>0){
      if($('#checkbox2 :checkbox:checked').length>0){
          $('.save-continue').removeAttr('disabled'); 
          $('.save-btn').removeAttr('disabled'); 
      }
     }else{
        $('.save-continue').attr('disabled','true');
        $('.save-btn').attr('disabled','true');
     }
  })

})


</script>

<!-- BEGIN SAMPLE FORM PORTLET-->
<?php /*
<div class="page-content-row">

<div class="page-content-col">
<div class="row">
  <?php if($step == "step1"):?>
  <div class="col-md-12">
    <div class="portlet box blue">
        <div class="portlet-title">
           <div class="caption"><i class="fa fa-gift"></i>Add Exhibitor</div>
           <div class="tools">
             <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
           </div>
        </div>
        <div class="portlet-body form" style="width: 100% ! important;">
          
           <?php echo $this->Form->create(null,array('id'=>'UserAddExhibitorForm','url'=>array('action'=>$this->request->getParam('action'), 'step'=>'step2')));?>
           <div class="row form-body">
              <div class="col-md-7">
                <div class="form-group">
                   <label><p>Please enter the Exhibitor email address<br/>
                    Note: Each email address can only associcated to one person/exhibitor</p>
                 </label>
                 
                   <div class="input-group">
                      <input type="text" name="email" class="form-control" id="UserEmail" placeholder="Email" maxlength="120">
                      <span class="input-group-addon"><i class="fa fa-envelope font-red"></i>
                      </span>
                   </div>
                </div>
              </div>
            </div>

            <div class="form-actions">
              <div class="col-md-12">
               <?php echo $this->Html->link('Cancel',array('controller'=>'users','action'=>'index'),array('class'=>'btn red')) ?>
               <button type="submit" class="btn blue">Continue</button>
              </div>
              <?php echo $this->Form->end();?>
            </div>
        </div>
      </div>
   </div>

   <!-- ========= Step 2 ===========  -->

<?php elseif($step == "step2"):?>
  
<!-- =========================================  -->
<link href="<?php echo $this->Url->webroot ?>/assets/pages/css/profile.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style type="text/css">
 dd {
    margin-bottom: 10px;
}
.newclass{
  min-height:87px;
  margin-bottom:25px;
  padding:10px;
  position:relative;
}
.modal-content {
    margin-top: 150px;
}

.page-content-row .page-content-col {
    vertical-align: top;
    display: table-cell;
    padding-left: 8px !important;
    padding-right: 8px !important;
}

select {
    outline: 0 !important;
    box-shadow: none !important;
    width: 100%;
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

.checkboxMultiple > li {
    float: left;
    list-style: outside none none;
    padding: 20px 70px 21px 0;
}

ul.checkboxMultiple {
    padding-left: 0px;
}

.editable-cancel{
  padding: 3px 2px !important;
}
.editable-submit{
  padding: 3px 1px !important;
}

.control-label .required, .form-group .required{
  color: #333;
}

.checkboxMultiple > li {
    float: left;
    list-style: outside none none;
    padding: 0px 35px 5px 0;
}

#label_ex_type{
   display: none; 
}

.profile-userpic img{
   border-radius: 1%!important;
}
</style>

<div class="page-content-container">
  <div class="page-content-row">
  
    <div class="page-content-col">
    <!-- BEGIN PAGE BASE CONTENT -->
       <div class="row">
          <div class="col-md-12">
            <div class="portlet box blue">
               <div class="portlet-title">
                   <div class="caption">
                       <i class="fa fa-gift"></i>Add Exhibitor</div>
                   <div class="tools">
                       <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
                   </div>
                </div>
                <div class="portlet-body">

                <div class="row">
                    <?php echo $this->Form->create(null, array('id'=>'UserAddExhibitorForm','type' => 'file','url'=>array('action'=>$this->request->getParam('action'), "step2")));?>
                    <input type="hidden" name="uid" value="<?=$user['id']?>"/>
                    
                    <div class="col-md-12">
                      <div class="profile-sidebar">
                        <div class="portlet light profile-sidebar-portlet borderedss">
                            <div class="profile-userpicxxx">
                              
                              <?php if($user['logo']){ ?> 
                                <input type="file" name="logo" id="input-file-blogo" class="dropify" data-default-file="<?php echo $this->Url->webroot.'/img/logo/'.$user['logo'] ?>"/>
                              <?php }else{ ?>
                                <input type="file" name="logo" id="input-file-blogo" class="dropify" data-default-file="<?php echo $this->Url->webroot.'/img/logo/no-logo.png'; ?>"/>
                              <?php } ?>  
                              
                            </div>
                            
                            <div class="profile-usertitle">
                              <div class="profile-usertitle-name"> <?php echo $user['firstname']." ".$user['lastname'] ?> </div>
                              <div class="profile-usertitle-job"> <?php // echo $this->data['ExhibitionRegistration']['booth_name'] ?> </div>
                            </div>

                        </div>
                            
                        <div class="portlet light bordered">
                                
                          <div class="row list-separated profile-stat">
                            <div class="mt-checkbox-inline" style="text-align:center;">
                               <label class="mt-checkbox">
                               <input type="checkbox" name="send_email" value="yes">
                                Send Welcome Email
                                <span></span>
                               </label>
                            </div>
                          </div>
                          
                        </div>
                            <!-- END PORTLET MAIN -->
                      </div>
                      
                    <div class="profile-content">
                      <div class="row">
                        <div class="col-md-12">
                           <div class="portlet light bordered">
                              <div class="portlet-title tabbable-line">
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-blue-madison bold uppercase tab-heading">Contact Details</span>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1_1" data-toggle="tab">Contact Details</a>
                                    </li>
                                    <li>
                                        <a href="#tab_1_2" data-toggle="tab">Stand Details</a>
                                    </li>
                                    
                                    <li class="tab_custom_field" style="display: <?php echo (empty($custom_fields)) ? 'none' : 'block'; ?>">
                                        <a class="tab_a_custom_field" href="#tab_1_3" data-toggle="tab">Custom Fields</a>
                                    </li>
                                </ul>
                              </div>
                              <div class="portlet-body">

                                 <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1_1">
                                        
                                        <div class="row">
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                 <label class="control-label">First Name</label>
                                                 <?php echo $this->Form->input('firstname',array('value'=>$user['firstname'],'class'=>'form-control','label'=>false)); ?> 
                                              </div>
                                          </div>
                                               
                                          <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Last Name</label>
                                                <?php echo $this->Form->input('lastname',array('value'=>$user['lastname'],'class'=>'form-control','label'=>false)); ?>
                                            </div>
                                          </div>   
                                        </div>

                                          <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label class="control-label">Email</label>
                                                   <?php echo $this->Form->input('email', array('value'=>$user['email'], 'disabled'=>'disabled','id'=>'emaildisplay', 'name'=>'emaildisplay','class'=>'form-control','label'=>false));
                                                   ?>
                                                </div>
                                            </div>
                                                 
                                              <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Company</label>
                                                    <?php echo $this->Form->input('company_name',array('value'=>$user['company_name'],'class'=>'form-control', 'label' => false)); ?>
                                                </div>
                                              </div>   
                                          </div>

                                          <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label class="control-label">Mobile</label>
                                                   <?php echo $this->Form->input('contact_mob',array('value'=>$user['contact_mob'],'class'=>'form-control', 'label'=>false)); ?> 
                                                </div>
                                            </div>
                                                 
                                              <div class="col-md-6">
                                                <div class="form-group">
                                                   <label class="control-label">Telephone</label>
                                                   <input type="text" id="UserContactTel" maxlength="39" name="contact_tel_num" class="form-control" value="<?php echo $user['contact_tel_num'] ?>">
                                                </div>
                                              </div>   
                                          </div>

                                          <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label class="control-label">External Username</label>
                                                   <?php echo $this->Form->input('external_username',array('value'=>$user['external_username'],'class'=>'form-control','label'=>false)) ?>
                                                </div>
                                            </div>
                                                 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label class="control-label">External Password</label>
                                                  <?php echo $this->Form->input('external_password',array('value'=>$user['external_password'],'class'=>'form-control','label'=>false)) ?>
                                                </div>
                                            </div>   
                                          </div>
                                             
                                          <!-- <h3 class="form-section">Address</h3> <hr> -->
                                          <hr>
                                          <div class="caption caption-md">
                                              <i class="icon-globe theme-font hide"></i>
                                              <span class="caption-subject font-blue-madison bold uppercase">Address</span>
                                          </div><hr>

                                          <div class="row">
                                            <div class="col-md-12 ">
                                              <div class="form-group">
                                                <label>Street</label>
                                                <?php echo $this->Form->input('company_addr_st',array('value'=>$user['company_addr_st'],'class'=>'form-control', 'label' =>false)); ?>
                                              </div>
                                             </div>
                                          </div>

                                          <div class="row">
                                             <div class="col-md-6">
                                               <div class="form-group">
                                                  <label>City</label>
                                                  <?php echo $this->Form->input('company_addr_city',array('value'=>$user['company_addr_city'],'class'=>'form-control', 'label'=>false)); ?> 
                                               </div>
                                             </div>
                                              
                                             <div class="col-md-6">
                                               <div class="form-group">
                                                  <label>State</label>
                                                  <?php $state = array( 'ACT' => 'ACT','NSW' => 'NSW','NT' => 'NT','QLD'  => 'QLD','SA' => 'SA','TAS' => 'TAS','VIC' => 'VIC','WA' => 'WA','Other' => 'Other');
                                                    echo $this->Form->input('company_addr_state', array('value'=>$user['company_addr_state'],'class'=>'form-control','label'=>false)); 
                                                  ?> 
                                               </div>
                                             </div>
                                          </div>

                                            <div class="row">
                                               <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label>Post Code</label>
                                                    <?php echo $this->Form->input('company_addr_postcode',array('value'=>$user['company_addr_postcode'],'class'=>'form-control', 'label' => false)); ?> 
                                                 </div>
                                               </div>
                                               
                                               <div class="col-md-6">
                                                  <div class="form-group">
                                                    <label>Country</label>
                                                    <span style="display:none">
                                                     <?php echo $this->Lang->countrySelect('company_addr_country', array(
                                                          'label' => __('Choose a Country', true),
                                                          'default' => 'au',
                                                          'class'=>'form-control',
                                                          'label' => false
                                                        )); ?>
                                                    </span> 
                                                    <input type="text" id="UserCompanyAddrCountryText" name="data[company_addr_country]" class="form-control" value="<?php echo $user['company_addr_country'] ?>">
                                                  </div>
                                               </div>
                                            </div>
                                        
                                    </div>
                                            
                                      <div class="tab-pane" id="tab_1_2">
                                        <div class="row">
                                           <div class="col-md-6">
                                             <div class="form-group">
                                                <label class="control-label">Stand Name</label>
                                                <?php echo $this->Form->input('booth_name', array('name'=>'booth_name','value'=> '','class'=>'form-control','label'=>false));?>
                                             </div>
                                             <div class="form-group">
                                                <label class="control-label">Stand Number</label>
                                                <?php echo $this->Form->input('booth_no', array('name'=>'booth_no','value'=>'','class'=>'form-control','label'=>false));?>
                                             </div>
                                             <div class="form-group">
                                                <label class="control-label">Stand Type</label>
                                                <select name="booth_type_id" id="UserEventBoothTypeId" onchange="add_new_stand_type(this.value)">
                                                  <option value=""></option>
                                                  <?php foreach($eventBoothTypes as $key=>$val){ ?>
                                                  <option value="<?php echo $key ?>"><?php echo $val ?></option>
                                                  <?php } ?>
                                                  <option value="" disabled>----------------------------</option>
                                                  <option value="add_new"> + Add new</option>
                                                </select>
                                             </div>
                                             <div class="form-group">
                                                <label class="control-label">Exhibitor Type</label>
                                                <?php echo $this->Enthtml->checkboxMultiple('Exhibitor Type', "event_exhibitor_types", $eventExhibitorTypes, '', true);?>
                                             </div>

                                             <div class="row" style="margin-bottom:40px;">
                                                <div class="col-md-12">
                                                  <a href="javascript:void(0)" onclick="add_exhibitor_type()"> + Add Exhibitor type</a>
                                                </div>
                                              </div>
                                           </div>

                                           <div class="col-md-6">
                                             <div class="form-group">
                                                <label class="control-label">Stand Location</label>
                                                <select name="event_location_id" id="UserEventLocation" onchange="add_new_location(this.value)">
                                                  <option value=""></option>
                                                  <?php foreach($eventLocations as $key=>$val){ ?>
                                                  <option value="<?php echo $key ?>"><?php echo $val ?></option>
                                                  <?php } ?>
                                                  <option value="" disabled>----------------------------</option>
                                                  <option value="add_new"> + Add new</option>
                                                </select> 
                                             </div>
                                             <div class="form-group">
                                                <label class="control-label">Stand Dimension</label>
                                                <select name="event_dimension_id" id="UserEventDimension" onchange="add_new_dimenssion(this.value)">
                                                  <option value=""></option>
                                                  <?php foreach($eventDimensions as $key=>$val){ ?>
                                                  <option value="<?php echo $key ?>"><?php echo $val ?></option>
                                                  <?php } ?>
                                                  <option value="" disabled>----------------------------</option>
                                                  <option value="add_new"> + Add new</option>
                                                </select>
                                             </div>
                                             <div class="form-group">
                                                <label class="control-label">Preferred Language</label>
                                                <select name="preferred_language" id="UserPreferredLanguage">
                                                   <?php foreach($languages as $lang){ ?>
                                                   <option value="<?php echo $lang ?>"><?php echo $lang ?></option> 
                                                   <?php } ?>
                                                </select>
                                             </div>
                                             <div class="form-group">
                                                <label class="control-label">Promo Code</label>
                                                <input type="text" name="promo_code" class="form-control">
                                             </div>

                                             

                                           </div> 
                                        </div>
                                        
                                      </div>
                                       
                                      <div class="tab-pane" id="tab_1_3">
                                        <!-- <div class="row">
                                          <div class="col-md-12">
                                          <h4 style="font-size: 18px; font-weight: bold;">
                                            <a href="javascript:void(0)" class="btn btn-xs blue add_field_button">Add Field (+) </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                            <a href="javascript:void(0)" class="btn btn-xs blue import_custom_field">Import Fields (+)</a>
                                          </h4><hr>
                                          </div>
                                        </div> -->

                                        <div class="row">
                                          <div class="col-md-6">
                                            <?php if(empty($custom_fields)){ ?>
                                              <div class="form-group no-more-div">Custom fields not found</div>
                                            <?php } ?>
                                            <?php foreach($custom_fields as $field){ ?>
                                              <div class="form-group">        
                                                <div class="col-md-10" style="margin-bottom: 15px;">
                                                <label>
                                                  <?php echo $field['field_key'] ?>
                                                </label>
                                                <input type="text" name="<?php echo $field['field_key'] ?>" class="form-control">
                                                </div> 
                                              </div>
                                              <?php } ?>
                                              <div class="custom-field-div"></div>   
                                          </div>  
                                        </div>

                                      </div>
                                </div>
                                <div class="form-actions">
                                  <hr>
                                  <div class="row">
                                    <div class="col-md-2">
                                      <button type="button" class="btn blue save-continue"> Save and continue</button>
                                    </div>
                                    <div class="col-md-2">
                                      <button type="button" class="btn blue save-btn" style="display:none"> Save</button>
                                    </div>
                                  </div>

                                  </div>
                                
                              </div>
                            </div>
                          </div>
                       </div>
                    </div>
                        
                    </div>
                    <?php echo $this->Form->end();?>
                </div>

               </div>
            </div>
          </div>
        </div>
      </div>

  </div>
</div>


<?php endif; ?>

</div>
</div>


</div>
*/?>
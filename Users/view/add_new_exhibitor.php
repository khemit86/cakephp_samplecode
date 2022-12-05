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
    padding-left: 0px !important;
    padding-right: 0px !important;
}


.checkboxMultiple > li {
    float: left;
    list-style: outside none none;
    padding: 0px 5px 5px 0;
    width: 50%;
}

ul.checkboxMultiple {
    padding-left: 0px;
    width: 100%;
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

#label_ex_type{
   display: none; 
}

.profile-userpic img{
   border-radius: 1%!important;
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
  
    <?php echo $this->Form->create(null, array('id'=>'UserAddNewExhibitorForm','type' => 'file', 'url'  => array('controller'=>$this->request->getParam('controller'), 'action' => $this->request->getParam('action'),$input_email)));?>
      <?php echo $this->Form->input('data[User][user_type]', array('type'=>'hidden', 'value' => 'exhibitor')); ?>
      <div class="row" style="margin-top: 1.5%;">
        

        <div class="col-xl-3">
          <div class="card card-custom">
            <div class="card-body">
              <div class="portlet profile-sidebar-portlet borderedss">
                  <div class="profile-userpic">
                    <input type="file" name="logo" id="input-file-blogo" class="dropify" data-default-file="<?php echo $this->Url->webroot.'/img/logo/no-logo.png'; ?>"/>
                  </div>
                  <div class="profile-usertitle">
                    <div class="profile-usertitle-name">  </div>
                    <div class="profile-usertitle-job">  </div>
                  </div>
              </div>
                  
              <div class="portlet bordered">
                <div class="list-separated profile-stat">
                  <div class="checkbox-inline" style="text-align:center;">
                     <label class="checkbox">
                     <input type="checkbox" name="send_email" value="yes">
                      <span></span>
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
                        <?php ///}?>
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
                                       <?php echo $this->Form->input('data[User][firstname]',array('value'=>@$this->getRequest()->getSession()->read('userdata')['firstname'],'class'=>'form-control','label'=>false)); ?> 
                                    </div>
                                </div>
                                     
                                  <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Last Name</label>
                                        <?php echo $this->Form->input('data[User][lastname]',array('value'=>@$this->getRequest()->getSession()->read('userdata')['lastname'],'class'=>'form-control','label'=>false)); ?>
                                    </div>
                                  </div>   
                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Email</label>
                                       <?php echo $this->Form->input('email', array('value'=>$input_email, 'disabled'=>'disabled','id'=>'emaildisplay', 'name'=>'emaildisplay','class'=>'form-control','label'=>false));
                                         echo $this->Form->input('data[User][email]', array('type'=>'hidden', 'value'=>$input_email));
                                       ?>
                                    </div>
                                </div>
                                     
                                  <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Company</label>
                                        <?php echo $this->Form->input('data[User][company_name]',array('value'=>@$this->getRequest()->getSession()->read('userdata')['company_name'],'class'=>'form-control', 'label' => false)); ?>
                                    </div>
                                  </div>   
                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Mobile</label>
                                       <?php echo $this->Form->input('data[User][contact_mob]',array('value'=>@$this->getRequest()->getSession()->read('userdata')['contact_mob'],'class'=>'form-control', 'label'=>false)); ?> 
                                    </div>
                                </div>
                                     
                                  <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Telephone</label>
                                       <input type="text" id="UserContactTel" maxlength="39" name="data[User][contact_tel_num]" value="<?php echo @$this->getRequest()->getSession()->read('userdata')['contact_tel_num']; ?>" class="form-control">
                                    </div>
                                  </div>   
                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">External Username</label>
                                       <?php echo $this->Form->input('data[User][external_username]',array('value'=>@$this->getRequest()->getSession()->read('userdata')['external_username'],'class'=>'form-control','label'=>false)) ?>
                                    </div>
                                </div>
                                     
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label class="control-label">External Password</label>
                                      <?php echo $this->Form->input('data[User][external_password]',array('value'=>@$this->getRequest()->getSession()->read('userdata')['external_password'],'class'=>'form-control','label'=>false)) ?>
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
                                    <?php echo $this->Form->input('data[User][company_addr_st]',array('value'=>@$this->getRequest()->getSession()->read('userdata')['company_addr_st'],'class'=>'form-control', 'label' =>false)); ?>
                                  </div>
                                 </div>
                              </div>

                              <div class="row">
                                 <div class="col-md-6">
                                   <div class="form-group">
                                      <label>City</label>
                                      <?php echo $this->Form->input('data[User][company_addr_city]',array('value'=>@$this->getRequest()->getSession()->read('userdata')['company_addr_city'],'class'=>'form-control', 'label'=>false)); ?> 
                                   </div>
                                 </div>
                                  
                                 <div class="col-md-6">
                                   <div class="form-group">
                                      <label>State</label>
                                      <?php $state = array( 'ACT' => 'ACT','NSW' => 'NSW','NT' => 'NT','QLD'  => 'QLD','SA' => 'SA','TAS' => 'TAS','VIC' => 'VIC','WA' => 'WA','Other' => 'Other');
                                        echo $this->Form->input('data[User][company_addr_state]', array('value'=>@$this->getRequest()->getSession()->read('userdata')['company_addr_state'],'class'=>'form-control','label'=>false)); 
                                      ?> 
                                   </div>
                                 </div>
                              </div>

                              <div class="row">
                                 <div class="col-md-6">
                                   <div class="form-group">
                                      <label>Post Code</label>
                                      <?php echo $this->Form->input('data[User][company_addr_postcode]',array('value'=>@$this->getRequest()->getSession()->read('userdata')['company_addr_postcode'],'class'=>'form-control', 'label' => false)); ?> 
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
                                      <input type="text" id="UserCompanyAddrCountryText" name="data[User][company_addr_country]" value="<?php echo @$this->getRequest()->getSession()->read('userdata')['company_addr_country'] ?>" class="form-control">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label">Preferred Language</label>
                                      <select name="data[ExhibitionRegistration][preferred_language]" id="UserPreferredLanguage" class="form-control">
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
                                      <?php echo $this->Form->input('booth_name', array('name'=>'data[ExhibitionRegistration][booth_name]','value'=> @$this->getRequest()->getSession()->read('exhib_data')['booth_name'],'class'=>'form-control','label'=>false)); ?>
                                   </div>
                                   <div class="form-group">
                                      <label class="control-label">Stand Number <?php if($is_event_stand==1){ ?><span class="text-danger">*</span><?php }?></label>
                                      <?php echo $this->Form->input('booth_no', array('name'=>'data[ExhibitionRegistration][booth_no]','value'=> @$this->getRequest()->getSession()->read('exhib_data')['booth_no'],'class'=>'form-control','label'=>false,'required'=>true, 'id'=>'std_no')); ?>
                                   </div>
                                   <?php /*<div class="form-group">
                                      <label class="control-label">Stand Type <span class="text-danger">*</span></label>
                                      <select name="data[ExhibitionRegistration][booth_type_id]" id="UserEventBoothTypeId" onchange="add_new_stand_type(this.value)" class="form-control" required>
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
                                      <?php echo $this->Enthtml->checkboxMultiple('User Categories', "data[ExhibitionRegistration][booth_type_id]", $eventBoothTypes, '', true); ?>
                                   </div>
                                   <div class="form-group">
                                      <label class="control-label">Exhibitor Type <span class="text-danger">*</span></label>
                                      <?php echo $this->Enthtml->checkboxMultiple('Exhibitor Type', "data[ExhibitionRegistration][event_exhibitor_types]", $eventExhibitorTypes, '', true); ?>
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
                                      <select name="data[ExhibitionRegistration][event_location_id]" id="UserEventLocation" onchange="add_new_location(this.value)" class="form-control">
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
                                      <select name="data[ExhibitionRegistration][event_dimension_id]" id="UserEventDimension" onchange="add_new_dimenssion(this.value)" class="form-control">
                                        <option value=""></option>
                                        <?php foreach($eventDimensions as $key=>$val){ ?>
                                        <option value="<?php echo $key ?>"><?php echo $val ?></option>
                                        <?php } ?>
                                        <option value="" disabled>----------------------------</option>
                                        <option value="add_new"> + Add new</option>
                                      </select>
                                   </div>
                                   <div class="form-group" style="display:none;">
                                      <label class="control-label">Promo Code</label>
                                      <input type="text" name="data[ExhibitionRegistration][promo_code]" class="form-control" value="<?php echo @$exhib_data['promo_code'] ?>">
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
                                      <?php echo $this->Enthtml->checkboxMultiple('Exhibitor Type', "data[ExhibitionRegistration][event_exhibitor_types]", $eventExhibitorTypes, '', true); ?>
                                   </div>
                                    <div class="row" style="margin-bottom:40px;">
                                      <div class="col-md-12">
                                        <a href="javascript:void(0)" onclick="add_exhibitor_type()"> + Add User type</a>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="control-label">User Categories <span class="text-danger">*</span></label>
                                      <?php echo $this->Enthtml->checkboxMultiple('User Categories', "data[ExhibitionRegistration][booth_type_id]", $eventBoothTypes, '', true); ?>
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
                                    <div class="form-group">        
                                      <div class="" style="margin-bottom: 15px;">
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
  


<div class="modal fade" id="uploadExhibLogo" role="dialog">
    <div class="modal-dialog" style="margin-top: 200px;">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Upload Logo</h4>
        </div>
        <div class="modal-body">
          <div class="row">
             <form id="do_upload_exhib_logo" method="post" enctype="multipart/form-data">
               <input type="hidden" name="user_id" value="">
               <div class="col-md-12">
                 <div class="form-group">
                   <input type="file" accept="image/*" name="exhib_logo" id="upload_exhib_logo" class="form-control" required>  
                 </div>
               </div>

               <div class="col-md-12">
                 <div class="form-group">
                   <button type="submit" class="btn btn-sm btn-success">Upload</button>
                 </div>
               </div>
             </form>
          </div>
        </div>

      </div>
      
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
  var val = '<?php echo $default_exhib_type; ?>';
  $('input:checkbox[value="' + val + '"]').attr('checked', true);

  $('.dropify').dropify();
});

//==========================================

$(document).ready(function() {

    var max_fields      = 100; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
   
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        $('.no-more-div').empty();

        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $('.custom-field-div').append('<div class="form-group">'+ 
                                           '<div class="col-md-12">'+       
                          '<label><input type="text" name="custom_label[]" id="custom_label" class="form-control" placeholder="Label" required></label>'+
                          '<input type="text" name="custom_value[]" class="form-control" placeholder="Custom Value" required>'+
                                          '</div>'+ 
                        '</div>');
        }
    });
   
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
        //$(this).parent('div').remove();
        $(this).closest('tr').remove();
        x--;
    })
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
         if(isNumeric(result)){
            $("#UserEventBoothTypeId option:last").before('<option value="'+result+'">'+stand_type+'</option>');
            $("#UserEventBoothTypeId").val(result);
            bootbox.hideAll();
         }else if(result.trim()=='exist'){
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
         if(isNumeric(result)){
            $("#UserEventDimension option:last").before('<option value="'+result+'">'+dimension+'</option>');
            $("#UserEventDimension").val(result);
            bootbox.hideAll();
         }else if(result.trim()=='exist'){
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
         if(isNumeric(result)){
            $("#UserEventLocation option:last").before('<option value="'+result+'">'+location+'</option>');
            $("#UserEventLocation").val(result);
            bootbox.hideAll();
         }else if(result.trim()=='exist'){
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
         if(isNumeric(result)){
            //$(".checkboxMultiple").append('<li><input type="checkbox" name="event_exhibitor_types[]" value="'+result+'" checked> '+exhibitor_type+'</li>');

            $(".checkboxMultiple").append('<li><div class="checkbox-inline">'+
                               '<label class="checkbox">'+
                               '<input type="checkbox" name="data[ExhibitionRegistration][event_exhibitor_types][]" value="'+result+'" checked="checked"> <span></span> &nbsp;'+exhibitor_type+''+
                                
                               '</label>'+
                            '</div></li>');

            //$('.event_exhib_type_area').append('<input type="checkbox" name="data[ExhibitionRegistration][event_exhibitor_types][]" value="'+result+'" checked="checked">');

            //$("#UserEventLocation").val(result);
            bootbox.hideAll();
         }else if(result.trim()=='exist'){
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
        $('.save-continue').removeAttr('disabled');
        $('.save-btn').css('display','none');
     }else if(tab=='Stand Details'){
      $('.save-continue').removeAttr('disabled');
       $('.save-btn').css('display','inline-block');
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
      $('.save-btn').removeAttr('disabled');
       $('.save-btn').css('display','inline-block');
       if(isCustom=='no'){
          $('.save-continue').css('display','none');
       }else{
          $('.save-continue').css('display','inline-block');
       }
       /*var std_no = $('#std_no').val();
       var std_type = $('#UserEventBoothTypeId option:selected').val();
        if(std_no=="" || std_type=="" || $('.checkboxMultiple :checkbox:checked').length > 0){
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
    $('#UserAddNewExhibitorForm').submit();
  });

  $('.save-continue').click(function(){
    $('.alert-danger').css('display','none');
    var selected= $('ul.nav-tabs').find('li.active');
    
    var tab= selected.next('li').find('span').html();

    if(tab=='Stand Details'){
       
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
       var std_no = $('#std_no').val();
       var std_type = $('#UserEventBoothTypeId option:selected').val();
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
      if($('#UserEventBoothTypeId option:selected').val()!=""){
        if($('.checkboxMultiple :checkbox:checked').length>0){
          $('.save-continue').removeAttr('disabled');
          $('.save-btn').removeAttr('disabled'); 
        }
      }
    }else{
        $('.save-continue').attr('disabled','true');
        $('.save-btn').attr('disabled','true');
     }
   <?php }?>
  });
  $('#UserEventBoothTypeId').change(function(){
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
  });
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

<?php /*
<div class="page-content-container">
  <div class="page-content-row">
  
      <!-- BEGIN PAGE SIDEBAR -->
     <!-- <div class="page-sidebar">
       <div style="padding-right: 10px;">
          <h3>Action</h3>
          <nav class="navbar" role="navigation">
              <ul class="nav navbar-nav margin-bottom-35">
                <li><?php echo $this->Html->link(__('List Exhibitors', true), array('action' => 'index'));?></li>
              </ul>
          </nav>
       </div>
    </div> -->



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
                    <?php echo $this->Form->create(null, array('id'=>'UserAddNewExhibitorForm','type' => 'file', 'url'  => array('controller'=>$this->request->getParam('controller'), 'action' => $this->request->getParam('action'),$input_email)));?>
                    <?php echo $this->Form->input('data[User][user_type]', array('type'=>'hidden', 'value' => 'exhibitor')); ?>
                    <div class="col-md-12">
                      <div class="profile-sidebar">
                        <div class="portlet light profile-sidebar-portlet borderedss">
                            <div class="profile-userpic">
                              
                              <input type="file" name="logo" id="input-file-blogo" class="dropify" data-default-file="<?php echo $this->Url->webroot.'/img/logo/no-logo.png'; ?>"/>
                            </div>
                            
                            <div class="profile-usertitle">
                              <div class="profile-usertitle-name">  </div>
                              <div class="profile-usertitle-job">  </div>
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
                                                   <?php echo $this->Form->input('data[User][firstname]',array('value'=>@$userdata['firstname'],'class'=>'form-control','label'=>false)); ?> 
                                                </div>
                                            </div>
                                                 
                                              <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Last Name</label>
                                                    <?php echo $this->Form->input('data[User][lastname]',array('value'=>@$userdata['lastname'],'class'=>'form-control','label'=>false)); ?>
                                                </div>
                                              </div>   
                                          </div>

                                          <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label class="control-label">Email</label>
                                                   <?php echo $this->Form->input('email', array('value'=>$input_email, 'disabled'=>'disabled','id'=>'emaildisplay', 'name'=>'emaildisplay','class'=>'form-control','label'=>false));
                                                     echo $this->Form->input('data[User][email]', array('type'=>'hidden', 'value'=>$input_email));
                                                   ?>
                                                </div>
                                            </div>
                                                 
                                              <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Company</label>
                                                    <?php echo $this->Form->input('data[User][company_name]',array('value'=>@$userdata['company_name'],'class'=>'form-control', 'label' => false)); ?>
                                                </div>
                                              </div>   
                                          </div>

                                          <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label class="control-label">Mobile</label>
                                                   <?php echo $this->Form->input('data[User][contact_mob]',array('value'=>@$userdata['contact_mob'],'class'=>'form-control', 'label'=>false)); ?> 
                                                </div>
                                            </div>
                                                 
                                              <div class="col-md-6">
                                                <div class="form-group">
                                                   <label class="control-label">Telephone</label>
                                                   <input type="text" id="UserContactTel" maxlength="39" name="data[User][contact_tel_num]" value="<?php echo @$userdata['contact_tel_num']; ?>" class="form-control">
                                                </div>
                                              </div>   
                                          </div>

                                          <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label class="control-label">External Username</label>
                                                   <?php echo $this->Form->input('data[User][external_username]',array('value'=>@$userdata['external_username'],'class'=>'form-control','label'=>false)) ?>
                                                </div>
                                            </div>
                                                 
                                              <div class="col-md-6">
                                                <div class="form-group">
                                                  <label class="control-label">External Password</label>
                                                  <?php echo $this->Form->input('data[User][external_password]',array('value'=>@$userdata['external_password'],'class'=>'form-control','label'=>false)) ?>
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
                                                <?php echo $this->Form->input('data[User][company_addr_st]',array('value'=>@$userdata['company_addr_st'],'class'=>'form-control', 'label' =>false)); ?>
                                              </div>
                                             </div>
                                          </div>

                                          <div class="row">
                                             <div class="col-md-6">
                                               <div class="form-group">
                                                  <label>City</label>
                                                  <?php echo $this->Form->input('data[User][company_addr_city]',array('value'=>@$userdata['company_addr_city'],'class'=>'form-control', 'label'=>false)); ?> 
                                               </div>
                                             </div>
                                              
                                             <div class="col-md-6">
                                               <div class="form-group">
                                                  <label>State</label>
                                                  <?php $state = array( 'ACT' => 'ACT','NSW' => 'NSW','NT' => 'NT','QLD'  => 'QLD','SA' => 'SA','TAS' => 'TAS','VIC' => 'VIC','WA' => 'WA','Other' => 'Other');
                                                    echo $this->Form->input('data[User][company_addr_state]', array('value'=>@$userdata['company_addr_state'],'class'=>'form-control','label'=>false)); 
                                                  ?> 
                                               </div>
                                             </div>
                                          </div>

                                            <div class="row">
                                               <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label>Post Code</label>
                                                    <?php echo $this->Form->input('data[User][company_addr_postcode]',array('value'=>@$userdata['company_addr_postcode'],'class'=>'form-control', 'label' => false)); ?> 
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
                                                    <input type="text" id="UserCompanyAddrCountryText" name="data[User][company_addr_country]" value="<?php echo @$userdata['company_addr_country'] ?>" class="form-control">
                                                  </div>
                                               </div>
                                            </div>
                                        
                                    </div>
                                            
                                      <div class="tab-pane" id="tab_1_2">
                                        <div class="row">
                                           <div class="col-md-6">
                                             <div class="form-group">
                                                <label class="control-label">Stand Name</label>
                                                <?php echo $this->Form->input('booth_name', array('name'=>'data[ExhibitionRegistration][booth_name]','value'=> @$exhib_data['booth_name'],'class'=>'form-control','label'=>false)); ?>
                                             </div>
                                             <div class="form-group">
                                                <label class="control-label">Stand Number</label>
                                                <?php echo $this->Form->input('booth_no', array('name'=>'data[ExhibitionRegistration][booth_no]','value'=> @$exhib_data['booth_no'],'class'=>'form-control','label'=>false)); ?>
                                             </div>
                                             <div class="form-group">
                                                <label class="control-label">Stand Type</label>
                                                <select name="data[ExhibitionRegistration][booth_type_id]" id="UserEventBoothTypeId" onchange="add_new_stand_type(this.value)">
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
                                                <?php echo $this->Enthtml->checkboxMultiple('Exhibitor Type', "data[ExhibitionRegistration][event_exhibitor_types]", $eventExhibitorTypes, '', true); ?>
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
                                                <select name="data[ExhibitionRegistration][event_location_id]" id="UserEventLocation" onchange="add_new_location(this.value)">
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
                                                <select name="data[ExhibitionRegistration][event_dimension_id]" id="UserEventDimension" onchange="add_new_dimenssion(this.value)">
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
                                                <select name="data[ExhibitionRegistration][preferred_language]" id="UserPreferredLanguage">
                                                   <?php foreach($languages as $lang){ ?>
                                                   <option value="<?php echo $lang ?>"><?php echo $lang ?></option> 
                                                   <?php } ?>
                                                </select>
                                             </div>
                                             <div class="form-group">
                                                <label class="control-label">Promo Code</label>
                                                <input type="text" name="data[ExhibitionRegistration][promo_code]" class="form-control" value="<?php echo @$exhib_data['promo_code'] ?>">
                                             </div>

                                             <?php /* if(empty($custom_fields)){ ?>
                                             <div class="form-group">
                                                <a href="#tab_1_3" data-toggle="tab" class="btn btn-xs default add_custom_field_tab">Add Custom Fields</a>
                                             </div>
                                             <?php } */ ?><?php /*

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
                                <div class="form-actions right">
                                  <hr>
                                    
                                    <div class="row">
                                      <div class="col-md-2">
                                        <button type="button" class="btn blue save-continue"> Save and continue</button>
                                      </div>
                                      <div class="col-md-2">
                                        <button type="button" class="btn blue save-btn" style="display:none"> Save</button>
                                      </div>
                                    </div> 
                                    
                                    <?php /*
                                    <div class="btn-group" style="margin-bottom:75px">
                                      <button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-sm blue dropdown-toggle"> Actions
                                          <i class="fa fa-angle-down"></i>
                                      </button>
                                      <ul role="menu" class="dropdown-menu">
                                        <li>
                                          <a href="javascript:void(0)" class="save-btn"> Save </a>
                                        </li>

                                        <li class="divider"> </li>

                                        <li>
                                          <a href="javascript:void(0)" class="save-continue"> Save and continue </a>
                                        </li>
                                      </ul>
                                   </div> */ ?><?php /*

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
*/?>

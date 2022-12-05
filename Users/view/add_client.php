<style>
    .page-content-row .page-content-col {
    padding-left: 0px !important;
    padding-right: 20px !important;
}
.input{
  margin-bottom: 15px;
}
.checkboxMultiple li {
    width: 30%;    float: left;
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
.comp-list{
  display: none;
}

.checkboxMultiple li {
    float: left;
    width: 100%;
    margin-bottom: 7px;
}
li{
  list-style-type: none;
}
.submit{
  display: inline;
}
</style>

<?php echo $this->Html->script('/js/tiny_mce/tiny_mce'); ?>

<?php 
    
  

    $company = array();
    foreach ($companies as $comp) {
      $company[$comp['id']] = $comp['company_name'];
    }
   
?>


<div class="card card-custom">
  <div class="card-header">
    <div class="card-title">
      <span class="card-icon">
        <i class="flaticon2-user text-primary"></i>
      </span>
      <h3 class="card-label">New Organiser</h3>
    </div>
    <div class="card-toolbar">
     
    </div>
  </div>
  <div class="card-body">
    <?php echo $this->Form->create();?>
      <div class="form-group row">
        <div class="col-md-6">
          <select name="user_type" id="UserUserType" style="margin-bottom: 20px;display: none;" class="form-control">
            <option>Select role</option>
            <option value="client" selected="true"> Organizer</option>
            <option value="venue"> Venue</option>
          </select>
          <?php
            echo $this->Form->control('email' ,array('maxlength'=>120,'class'=>'form-control','required'=>true));
            echo $this->Form->control('firstname',array('class'=>'form-control','required'=>true));
            echo $this->Form->control('lastname',array('class'=>'form-control'));
            echo $this->Form->control('position',array('class'=>'form-control'));
          ?>
          <div class="">
            <?php echo $this->Form->control('company_name',array('class'=>'form-control')); ?>
          </div>
          <?php
            echo $this->Form->control('company_info',array('class'=>'form-control'));
            echo $this->Form->control('company_addr_st',array('class'=>'form-control'));
            echo $this->Form->control('company_addr_city',array('class'=>'form-control'));
            
            $state = array('ACT' => 'ACT','NSW' => 'NSW','NT' => 'NT','QLD' => 'QLD','SA' => 'SA','TAS' => 'TAS','VIC' => 'VIC','WA' => 'WA','Other' => 'Other');       
            echo $this->Form->control('company_addr_state', array('options'=>$state, 'class'=>'form-control'));
            echo $this->Form->control('company_addr_postcode',array('class'=>'form-control'));
          ?>
          <?php echo $this->Lang->countrySelect('company_addr_country', array('label' => __('Choose a Country', true),'default' => 'au','class' => 'some-class form-control'));  ?>
        </div>
      </div>
        
      <div class="form-group row">
        <div class="col-md-6">
          <label for="UserContactTel">Contact Tel</label>
            <div class="row">
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="UserContactTelArea" maxlength="6" name="contact_tel_areacode" style="">
                </div>
                <div class="col-lg-9">
                  <input type="text" class="form-control" id="UserContactTel" maxlength="39" name="contact_tel_num" style="">
                </div>
            </div>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-md-6">
          <label for="UserContactFax">Contact Fax</label>
          <div class="row">
            <div class="col-lg-3">
              <input type="text"  class="form-control"  id="UserContactFaxArea" maxlength="6" name="contact_fax_areacode" style="">
            </div>
            <div class="col-lg-9">
              <input type="text"  class="form-control"  id="UserContactFax" maxlength="39" name="contact_fax_num" style="">
            </div>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-md-6">
          <?=$this->Form->control('contact_mob',array('class'=>'form-control','label'=>'Mobile'));?>
          <div class="comp-lists">
            <label for="UserCompanyName">Event Company</label>
            <select name="event_company_id" onchange="get_event(this.value)" required class="form-control">
              <option value="">Select company</option>
              <?php foreach($company as $key=>$val){ ?>
               <option value="<?php echo $key ?>"><?php echo $val ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
		</div>
		
	  <div class="form-group row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-lg-12">
                  <div class="checkbox-inline">
					<label class="checkbox">
					  <input type="checkbox" name="is_allow_access_api" value="1">
					  <span></span>Allow Access Api
					</label>
				  <!-- </div>
				  <div class="checkbox-inline"> -->
					
				  </div>
                </div>
            </div>
        </div>
      </div>
		
		
        <div class="form-group row">
      <div class="col-md-12">
        <br/> <h3>Event</h3><br/>
        <hr/>

        <div id="event-msg" style="display:none;font-size:18px;color:green">Loading...</div>
        <div id="event-block"></div>
      </div>
      </div>

      <div class="form-group row">
        <div class="col-md-12">
          <a href="/users/organisers" class="btn btn-secondary mr-2">Cancel</a>
          <?php echo $this->Form->submit(__('Save',true), array('class'=>'btn btn-success')); 
          echo $this->Form->end();?>
        </div>
      </div>
  </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
jQuery(function($){
  var ctxt =  $('.comp-text').html();
  var clst = $('.comp-list').html();
  //alert(clst);
    $('#UserUserType').on('change', function(){
      //alert($(this).val());
      if($(this).val() == 'client'){
        $('.comp-text').html('');
        $('.comp-text').hide();
        $('.comp-list').show();
        $('.comp-list').html(clst);
      }else{        
        $('.comp-text').show();
        $('.comp-text').html(ctxt);
        $('.comp-list').hide();
        $('.comp-list').html('');
      }
    })
})


function get_event(company_id){
  if(company_id){
     var csrfToken = $('meta[name="csrfToken"]').attr('content');
     var path="<?php echo $this->Url->webroot ?>/users/getEventByCompanyAjax";
     $('#event-msg').css('display','block');
     $.ajax({
          type:"POST",
          url:path,
          data:{company_id:company_id},
          headers: {
             'X-CSRF-Token': csrfToken
          },
          success:function(result){
             if(result){
                $('#event-block').html(result);
             }

             $('#event-msg').css('display','none');
          }
     });
  }
}
</script>

<?php /*
<div class="page-content-container">
  <div class="page-content-row">

  <!-- BEGIN PAGE SIDEBAR -->
     <div class="page-sidebar">
      <div class="col-md-12">   
         <h3>Action</h3>
        <nav class="navbar" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <!-- Collect the nav links, forms, and other content for toggling -->
            <ul class="nav navbar-nav margin-bottom-35">
              <li><?php echo $this->Html->link(__('List Users', true), array('action' => 'listClients'));?></li>
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
               <i class="fa fa-gift"></i>New Client</div>
             <div class="tools">
               <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
             </div>
         </div>
         <div class="portlet-body">                                                   
          <?php echo $this->Form->create();?>
          <fieldset>
            <legend><?php __('Add Client'); ?></legend>
           
            <select name="user_type" id="UserUserType" style="margin-bottom: 20px;">
              <option>Select role</option>
              <option value="client"> Organizer</option>
              <option value="venue"> Venue</option>
            </select>
            <?php
            echo $this->Form->control('email' ,array('maxlength'=>120,'class'=>'form-control','required'=>true));
            echo $this->Form->control('firstname',array('class'=>'form-control','required'=>true));
            echo $this->Form->control('lastname',array('class'=>'form-control'));
            echo $this->Form->control('position',array('class'=>'form-control'));

            ?>
            <div class="">
              <?php echo $this->Form->control('company_name',array('class'=>'form-control')); ?>
            </div>

            

            <?php

            echo $this->Form->control('company_info',array('class'=>'form-control'));
            echo $this->Form->control('company_addr_st',array('class'=>'form-control'));
            echo $this->Form->control('company_addr_city',array('class'=>'form-control'));
            
            $state = array('ACT' => 'ACT','NSW' => 'NSW','NT' => 'NT','QLD' => 'QLD','SA' => 'SA','TAS' => 'TAS','VIC' => 'VIC','WA' => 'WA','Other' => 'Other');       
            echo $this->Form->control('company_addr_state', array('options'=>$state));
            echo $this->Form->control('company_addr_postcode',array('class'=>'form-control'));

            ?>
            <div class="input text">
              <?php echo $this->Lang->countrySelect('company_addr_country', array('label' => __('Choose a Country', true),'default' => 'au','class' => 'some-class form-control'));  ?>
            </div>
            <div class="input text">
              <label for="UserContactTel">Contact Tel</label>
                <div class="row">
                    <div class="col-lg-1">
                        <input type="text" class="form-control" id="UserContactTelArea" maxlength="6" name="contact_tel_areacode" style="width:80px;">
                    </div>
                    <div class="col-lg-1">
                        <input type="text" class="form-control" id="UserContactTel" maxlength="39" name="contact_tel_num" style="width:250px;">
                    </div>
                </div>
            </div>
            <div class="input text">
              <label for="UserContactFax">Contact Fax</label>
              <div class="row">
                <div class="col-lg-1">
                   <input type="text"  class="form-control"  id="UserContactFaxArea" maxlength="6" name="contact_fax_areacode" style="width:80px;">
                 </div>
                 <div class="col-lg-1">
                    <input type="text"  class="form-control"  id="UserContactFax" maxlength="39" name="contact_fax_num" style="width:250px;">
                 </div>
              </div>
            </div>

            <?=$this->Form->control('contact_mob',array('class'=>'form-control','label'=>'Mobile'));?>
            

            <div class="comp-lists">
              <label for="UserCompanyName">Event Company</label>
              <select name="event_company_id" onchange="get_event(this.value)" required>
                <option value="">Select company</option>
                <?php foreach($company as $key=>$val){ ?>
                 <option value="<?php echo $key ?>"><?php echo $val ?></option>
                <?php } ?>
              </select>
            </div>

            <br/> <h3>Event</h3><br/>
            <hr/>

            <div id="event-msg" style="display:none;font-size:18px;color:green">Loading...</div>
            <div id="event-block"></div>

            <?php // echo $this->Enthtml->checkboxMultiple('Events', "data[ExhibitionRegistration][events]", $events); ?>
          </fieldset>
          <br> <hr>
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

                                    
                                    
                                    
                                    
                                                               
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    

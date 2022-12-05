<?php use Cake\Routing\Router; ?>
<style>
.input{
  margin-bottom: 15px;
}
.submit{
  display: inline;
}
</style>

<?php echo $this->Html->script('/js/tiny_mce/tiny_mce'); ?>
<?php
    $compniesList = array();
    foreach ($companies as $data) {
      $compniesList[$data['id']] = $data['company_name'];
    }
    
 ?>

<div class="card card-custom">
  <div class="card-header">
    <div class="card-title">
      <span class="card-icon">
        <i class="flaticon2-layers text-primary"></i>
      </span>
      <h3 class="card-label">Edit Event</h3>
    </div>
    <div class="card-toolbar">
    </div>
  </div>
  <div class="card-body">
    <?php echo $this->Form->create($event);?>
      <div class="form-group row">
        <div class="col-md-6">
          <?php
              echo $this->Form->control('id',array('class'=>'form-control'));
              echo $this->Form->control('name' ,array('class'=>'form-control'));
              echo $this->Form->control('prefix' ,array('class'=>'form-control'));
              echo $this->Form->control('description' ,array('class'=>'form-control'));
              echo $this->Form->control('location',array('class'=>'form-control'));
              echo $this->Form->control('country',array('class'=>'form-control', 'maxlength' => '2'));
              echo $this->Form->control('state',array('class'=>'form-control', 'maxlength' => '32'));
              echo $this->Form->control('city',array('class'=>'form-control'));
              echo $this->Form->control('zipcode',array('class'=>'form-control'));
              echo $this->Form->control('company_id',array('class'=>'form-control','empty'=> 'Select Company', 'options' => $compniesList, 'required' => true));
              
              $strt_year = date('Y',strtotime($event['start_date']));
              $strt_month = date('m',strtotime($event['start_date'])); 
              $strt_day = date('d',strtotime($event['start_date']));
              $end_year = date('Y',strtotime($event['end_date']));
              $end_month = date('m',strtotime($event['end_date'])); 
              $end_day = date('d',strtotime($event['end_date']));
            ?>
        </div>
      </div>
      <div class="input date">
        <label for="EventStartDateMonth">Start Date</label>
        <div class="row">
          <div class="col-md-2">
            <select name="data[start_date][month]" id="EventStartDateMonth" class="form-control">
                <option value=""></option>
                <option value="01" <?=$strt_month == '01' ? 'selected' : ''; ?>  >January</option>
                <option value="02" <?=$strt_month == '02' ? 'selected' : ''; ?> >February</option>
                <option value="03" <?=$strt_month == '03' ? 'selected' : ''; ?> >March</option>
                <option value="04" <?=$strt_month == '04' ? 'selected' : ''; ?> >April</option>
                <option value="05" <?=$strt_month == '05' ? 'selected' : ''; ?> >May</option>
                <option value="06" <?=$strt_month == '06' ? 'selected' : ''; ?> >June</option>
                <option value="07" <?=$strt_month == '07' ? 'selected' : ''; ?> >July</option>
                <option value="08" <?=$strt_month == '08' ? 'selected' : ''; ?> >August</option>
                <option value="09" <?=$strt_month == '09' ? 'selected' : ''; ?> >September</option>
                <option value="10" <?=$strt_month == '10' ? 'selected' : ''; ?> >October</option>
                <option value="11" <?=$strt_month == '11' ? 'selected' : ''; ?> >November</option>
                <option value="12" <?=$strt_month == '12' ? 'selected' : ''; ?> >December</option>
            </select>
          </div>
          <div class="col-md-2">
            <select name="data[start_date][day]" id="EventStartDateDay" class="form-control">
              <option value="01" <?=$strt_day == '01' ? 'selected' : ''; ?> >1</option>
              <option value="02" <?=$strt_day == '02' ? 'selected' : ''; ?> >2</option>
              <option value="03" <?=$strt_day == '03' ? 'selected' : ''; ?> >3</option>
              <option value="04" <?=$strt_day == '04' ? 'selected' : ''; ?> >4</option>
              <option value="05" <?=$strt_day == '05' ? 'selected' : ''; ?> >5</option>
              <option value="06" <?=$strt_day == '06' ? 'selected' : ''; ?> >6</option>
              <option value="07" <?=$strt_day == '07' ? 'selected' : ''; ?> >7</option>
              <option value="08" <?=$strt_day == '08' ? 'selected' : ''; ?> >8</option>
              <option value="09" <?=$strt_day == '09' ? 'selected' : ''; ?> >9</option>
              <option value="10" <?=$strt_day == '10' ? 'selected' : ''; ?> >10</option>
              <option value="11" <?=$strt_day == '11' ? 'selected' : ''; ?> >11</option>
              <option value="12" <?=$strt_day == '12' ? 'selected' : ''; ?> >12</option>
              <option value="13" <?=$strt_day == '13' ? 'selected' : ''; ?> >13</option>
              <option value="14" <?=$strt_day == '14' ? 'selected' : ''; ?> >14</option>
              <option value="15" <?=$strt_day == '15' ? 'selected' : ''; ?> >15</option>
              <option value="16" <?=$strt_day == '16' ? 'selected' : ''; ?> >16</option>
              <option value="17" <?=$strt_day == '17' ? 'selected' : ''; ?> >17</option>
              <option value="18" <?=$strt_day == '18' ? 'selected' : ''; ?> >18</option>
              <option value="19" <?=$strt_day == '19' ? 'selected' : ''; ?> >19</option>
              <option value="20" <?=$strt_day == '20' ? 'selected' : ''; ?> >20</option>
              <option value="21" <?=$strt_day == '21' ? 'selected' : ''; ?> >21</option>
              <option value="22" <?=$strt_day == '22' ? 'selected' : ''; ?> >22</option>
              <option value="23" <?=$strt_day == '23' ? 'selected' : ''; ?> >23</option>
              <option value="24" <?=$strt_day == '24' ? 'selected' : ''; ?> >24</option>
              <option value="25" <?=$strt_day == '25' ? 'selected' : ''; ?> >25</option>
              <option value="26" <?=$strt_day == '26' ? 'selected' : ''; ?> >26</option>
              <option value="27" <?=$strt_day == '27' ? 'selected' : ''; ?>>27</option>
              <option value="28" <?=$strt_day == '28' ? 'selected' : ''; ?> >28</option>
              <option value="29" <?=$strt_day == '29' ? 'selected' : ''; ?> >29</option>
              <option value="30" <?=$strt_day == '30' ? 'selected' : ''; ?> >30</option>
              <option value="31" <?=$strt_day == '31' ? 'selected' : ''; ?> >31</option>
            </select>
          </div>
          <div class="col-md-2">
            <select name="data[start_date][year]" id="EventStartDateYear" class="form-control">
              <option value=""></option>
              <?php $y = date('Y');
                $last= (int)$y+20; 
                while ($y<= $last) {
                  if($y == $strt_year){
                       echo "<option value='$y' selected >$y</option>";
                  }else{
                     echo "<option value='$y' >$y</option>";
                  }
                 $y++;
                }
               ?>
            </select>
          </div>
        </div>
      </div>

      <div class="input date">
        <label for="EventStartDateMonth">End Date</label>
        <div class="row">
          <div class="col-md-2">
            <select name="data[end_date][month]" id="EventEndDateMonth" class="form-control">
                <option value=""></option>
                <option value="01" <?=$end_month == '01' ? 'selected' : ''; ?>  >January</option>
                <option value="02" <?=$end_month == '02' ? 'selected' : ''; ?> >February</option>
                <option value="03" <?=$end_month == '03' ? 'selected' : ''; ?> >March</option>
                <option value="04" <?=$end_month == '04' ? 'selected' : ''; ?> >April</option>
                <option value="05" <?=$end_month == '05' ? 'selected' : ''; ?> >May</option>
                <option value="06" <?=$end_month == '06' ? 'selected' : ''; ?> >June</option>
                <option value="07" <?=$end_month == '07' ? 'selected' : ''; ?> >July</option>
                <option value="08" <?=$end_month == '08' ? 'selected' : ''; ?> >August</option>
                <option value="09" <?=$end_month == '09' ? 'selected' : ''; ?> >September</option>
                <option value="10" <?=$end_month == '10' ? 'selected' : ''; ?> >October</option>
                <option value="11" <?=$end_month == '11' ? 'selected' : ''; ?> >November</option>
                <option value="12" <?=$end_month == '12' ? 'selected' : ''; ?> >December</option>
            </select>
          </div>
          <div class="col-md-2">
            <select name="data[end_date][day]" id="EventEndDateDay" class="form-control">
                <option value=""></option>
                <option value="01" <?=$end_day == '01' ? 'selected' : ''; ?> >1</option>
                <option value="02" <?=$end_day == '02' ? 'selected' : ''; ?> >2</option>
                <option value="03" <?=$end_day == '03' ? 'selected' : ''; ?> >3</option>
                <option value="04" <?=$end_day == '04' ? 'selected' : ''; ?> >4</option>
                <option value="05" <?=$end_day == '05' ? 'selected' : ''; ?> >5</option>
                <option value="06" <?=$end_day == '06' ? 'selected' : ''; ?> >6</option>
                <option value="07" <?=$end_day == '07' ? 'selected' : ''; ?> >7</option>
                <option value="08" <?=$end_day == '08' ? 'selected' : ''; ?> >8</option>
                <option value="09" <?=$end_day == '09' ? 'selected' : ''; ?> >9</option>
                <option value="10" <?=$end_day == '10' ? 'selected' : ''; ?> >10</option>
                <option value="11" <?=$end_day == '11' ? 'selected' : ''; ?> >11</option>
                <option value="12" <?=$end_day == '12' ? 'selected' : ''; ?> >12</option>
                <option value="13" <?=$end_day == '13' ? 'selected' : ''; ?> >13</option>
                <option value="14" <?=$end_day == '14' ? 'selected' : ''; ?> >14</option>
                <option value="15" <?=$end_day == '15' ? 'selected' : ''; ?> >15</option>
                <option value="16" <?=$end_day == '16' ? 'selected' : ''; ?> >16</option>
                <option value="17" <?=$end_day == '17' ? 'selected' : ''; ?> >17</option>
                <option value="18" <?=$end_day == '18' ? 'selected' : ''; ?> >18</option>
                <option value="19" <?=$end_day == '19' ? 'selected' : ''; ?> >19</option>
                <option value="20" <?=$end_day == '20' ? 'selected' : ''; ?> >20</option>
                <option value="21" <?=$end_day == '21' ? 'selected' : ''; ?> >21</option>
                <option value="22" <?=$end_day == '22' ? 'selected' : ''; ?> >22</option>
                <option value="23" <?=$end_day == '23' ? 'selected' : ''; ?> >23</option>
                <option value="24" <?=$end_day == '24' ? 'selected' : ''; ?> >24</option>
                <option value="25" <?=$end_day == '25' ? 'selected' : ''; ?> >25</option>
                <option value="26" <?=$end_day == '26' ? 'selected' : ''; ?> >26</option>
                <option value="27" <?=$end_day == '27' ? 'selected' : ''; ?>>27</option>
                <option value="28" <?=$end_day == '28' ? 'selected' : ''; ?> >28</option>
                <option value="29" <?=$end_day == '29' ? 'selected' : ''; ?> >29</option>
                <option value="30" <?=$end_day == '30' ? 'selected' : ''; ?> >30</option>
                <option value="31" <?=$end_day == '31' ? 'selected' : ''; ?> >31</option>
            </select> 
          </div>
          <div class="col-md-2">
            <select name="data[end_date][year]" id="EventEndDateYear" class="form-control">
                <option value=""></option>
                <?php $y = date('Y');
                  $last= (int)$y+20; 
                  while ($y<= $last) {
                    if($y == $end_year){
                         echo "<option value='$y' selected >$y</option>";
                    }else{
                       echo "<option value='$y' >$y</option>";
                    }
                   $y++;
                  }
                 ?>                  
            </select>
          </div>
        </div>
      </div>
      <br/>
      <p><strong>Fill in the email sender detail</strong></p>
      <p><strong>Make sure you enter a valid email address</strong></p>
     
      <div class="input text">
        <div class="form-group row">
          <div class="col-md-6">
            <?php 
              echo $this->Form->control('event_email_sender', array('label' => 'Default Email sender name','class'=>'form-control'));
              echo $this->Form->control('event_email_address', array('label' => 'Default Email sender address','class'=>'form-control'));
            ?>
            <label>Feed Security Key</label>
            <input type="text" value="<?php echo $event['feed_security_key'] ?>" class="form-control" readonly>
            <small>XML Feed URL: <a target="blank" href="<?php echo $xml_url= Router::url('/', true).'feeds/xmlfeed/'.$event['feed_security_key']; ?>"><?php echo $xml_url= Router::url('/', true).'feeds/xmlfeed/'.$event['feed_security_key']; ?></a></small><br/>
            <small>JSON Feed URL: <a target="blank" href="<?php echo $xml_url= Router::url('/', true).'feeds/jsonfeed/'.$event['feed_security_key']; ?>"><?php echo $xml_url= Router::url('/', true).'feeds/jsonfeed/'.$event['feed_security_key']; ?></a></small>
            <!-- <br>
            <br>
            <label for="EventThemeLayout">Select Event Theme</label>
            <select id="" name="theme_layout" required class="form-control">
               <option value="old" <?php echo ($event['theme_layout']=='old') ? 'selected' : ''; ?>>Metronic 1 (old)</option>
               <option value="new" <?php echo ($event['theme_layout']=='new') ? 'selected' : ''; ?>>Metronic 2 (new)</option>
            </select> -->
          </div>
        </div>
      </div>
      
      <hr>
      <div class="row">
        <!-- <div class="col-md-12 checkbox-inline">
          <label class="checkbox">
            <input type="checkbox" name="enable_cs_cart" value="1" <?php echo ($event['enable_cs_cart']==1) ? 'checked' : '' ?>> <span></span> Enable Marketplace
            
          </label>
        </div> -->

        <div class="col-md-12 checkbox-inline">
          <label class="checkbox">
            <input type="checkbox" name="enable_agent" value="1" <?php echo ($event['enable_agent']==1) ? 'checked' : '' ?>><span></span> Enable Additional Users
            
          </label>
        </div>

        <div class="col-md-12 checkbox-inline">
          <label class="checkbox">
            <input type="checkbox" name="enable_exhibitor_invite" value="1" <?php echo ($event['enable_exhibitor_invite']==1) ? 'checked' : '' ?>> <span></span> Enable Exhibitor Marketing Toolkit
            
          </label>
        </div>

        <div class="col-md-12 checkbox-inline" style="display:none;">
          <label class="checkbox">
            <input type="checkbox" name="enable_form_approval" value="1" checked="true"> <?php echo ($event['enable_form_approval']==1) ? 'checked' : '' ?>> <span></span> Enable Form Approval
            
          </label>
        </div>

        <div class="col-md-12 checkbox-inline">
          <label class="checkbox">
            <input type="checkbox" name="enable_payment_forms" value="1" <?php echo ($event['enable_form_approval']==1) ? 'checked' : '' ?>> <span></span> Enable Payment Forms 
          </label>
        </div>

      </div>
                              
      <br>
      <input type="hidden" name="old_event_name" value="<?php echo $event['name'] ?>">
      <input type="hidden" name="old_company_id" value="<?php echo $event['company_id'] ?>">
      <div class="form-group row">
        <div class="col-md-6">
          <a href="/events" class="btn btn-secondary mr-2">Cancel</a>
          <?php echo $this->Form->submit(__('Save',true), array('class'=>'btn btn-success')); 
        echo $this->Form->end();?>
      </div>
    </div>
  </div>
</div>
 

<script type="text/javascript" src="<?php echo $this->Url->webroot ?>js/jquery-1.9.1.js"></script>

<?php /*
<div class="page-content-container">
  <div class="page-content-row">

           <!-- BEGIN PAGE SIDEBAR -->
  <div class="page-sidebar">
  <div class="col-md-12">
   <h3>Action</h3>
        <nav class="navbar" role="navigation">
            <ul class="nav navbar-nav margin-bottom-35">
                     
              <li><?php echo $this->Html->link(__('New Event', true), array('action' => 'add')); ?></li>
              <li><?php echo $this->Html->link(__('List Events', true), array('action' => 'index')); ?></li>
            </ul>
        </nav>
     </div>
    </div>

  <div class="page-content-col">
    <div class="row">
      <div class="col-md-12">
          <div class="portlet box blue">
              <div class="portlet-title">
                 <div class="caption">
                     <i class="fa fa-gift"></i>Edit Event</div>
                 <div class="tools">
                     <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
                 </div>
              </div>
              <div class="portlet-body">
                <?php echo $this->Form->create($event);?>
                  <legend><?php __('Edit Event'); ?></legend>
                  <?php

                      echo $this->Form->control('id',array('class'=>'form-control'));
                      echo $this->Form->control('name' ,array('class'=>'form-control'));
                      echo $this->Form->control('prefix' ,array('class'=>'form-control'));
                      echo $this->Form->control('description' ,array('class'=>'form-control'));
                      echo $this->Form->control('location',array('class'=>'form-control'));
                      echo $this->Form->control('country',array('class'=>'form-control', 'maxlength' => '2'));
                      echo $this->Form->control('state',array('class'=>'form-control', 'maxlength' => '32'));
                      echo $this->Form->control('city',array('class'=>'form-control'));
                      echo $this->Form->control('zipcode',array('class'=>'form-control'));
                      echo $this->Form->control('company_id',array('class'=>'form-control','empty'=> 'Select Company', 'options' => $compniesList, 'required' => true));
                      
                      $strt_year = date('Y',strtotime($event['start_date']));
                      $strt_month = date('m',strtotime($event['start_date'])); 
                      $strt_day = date('d',strtotime($event['start_date']));
                      $end_year = date('Y',strtotime($event['end_date']));
                      $end_month = date('m',strtotime($event['end_date'])); 
                      $end_day = date('d',strtotime($event['end_date']));
                  ?>
              <div class="input date">
              <label for="EventStartDateMonth">Start Date</label>
              <select name="data[start_date][month]" id="EventStartDateMonth">
                  <option value=""></option>
                  <option value="01" <?=$strt_month == '01' ? 'selected' : ''; ?>  >January</option>
                  <option value="02" <?=$strt_month == '02' ? 'selected' : ''; ?> >February</option>
                  <option value="03" <?=$strt_month == '03' ? 'selected' : ''; ?> >March</option>
                  <option value="04" <?=$strt_month == '04' ? 'selected' : ''; ?> >April</option>
                  <option value="05" <?=$strt_month == '05' ? 'selected' : ''; ?> >May</option>
                  <option value="06" <?=$strt_month == '06' ? 'selected' : ''; ?> >June</option>
                  <option value="07" <?=$strt_month == '07' ? 'selected' : ''; ?> >July</option>
                  <option value="08" <?=$strt_month == '08' ? 'selected' : ''; ?> >August</option>
                  <option value="09" <?=$strt_month == '09' ? 'selected' : ''; ?> >September</option>
                  <option value="10" <?=$strt_month == '10' ? 'selected' : ''; ?> >October</option>
                  <option value="11" <?=$strt_month == '11' ? 'selected' : ''; ?> >November</option>
                  <option value="12" <?=$strt_month == '12' ? 'selected' : ''; ?> >December</option>
              </select>-
              <select name="data[start_date][day]" id="EventStartDateDay">
                            <option value="01" <?=$strt_day == '01' ? 'selected' : ''; ?> >1</option>
                  <option value="02" <?=$strt_day == '02' ? 'selected' : ''; ?> >2</option>
                  <option value="03" <?=$strt_day == '03' ? 'selected' : ''; ?> >3</option>
                  <option value="04" <?=$strt_day == '04' ? 'selected' : ''; ?> >4</option>
                  <option value="05" <?=$strt_day == '05' ? 'selected' : ''; ?> >5</option>
                  <option value="06" <?=$strt_day == '06' ? 'selected' : ''; ?> >6</option>
                  <option value="07" <?=$strt_day == '07' ? 'selected' : ''; ?> >7</option>
                  <option value="08" <?=$strt_day == '08' ? 'selected' : ''; ?> >8</option>
                  <option value="09" <?=$strt_day == '09' ? 'selected' : ''; ?> >9</option>
                  <option value="10" <?=$strt_day == '10' ? 'selected' : ''; ?> >10</option>
                  <option value="11" <?=$strt_day == '11' ? 'selected' : ''; ?> >11</option>
                  <option value="12" <?=$strt_day == '12' ? 'selected' : ''; ?> >12</option>
                  <option value="13" <?=$strt_day == '13' ? 'selected' : ''; ?> >13</option>
                  <option value="14" <?=$strt_day == '14' ? 'selected' : ''; ?> >14</option>
                  <option value="15" <?=$strt_day == '15' ? 'selected' : ''; ?> >15</option>
                  <option value="16" <?=$strt_day == '16' ? 'selected' : ''; ?> >16</option>
                  <option value="17" <?=$strt_day == '17' ? 'selected' : ''; ?> >17</option>
                  <option value="18" <?=$strt_day == '18' ? 'selected' : ''; ?> >18</option>
                  <option value="19" <?=$strt_day == '19' ? 'selected' : ''; ?> >19</option>
                  <option value="20" <?=$strt_day == '20' ? 'selected' : ''; ?> >20</option>
                  <option value="21" <?=$strt_day == '21' ? 'selected' : ''; ?> >21</option>
                  <option value="22" <?=$strt_day == '22' ? 'selected' : ''; ?> >22</option>
                  <option value="23" <?=$strt_day == '23' ? 'selected' : ''; ?> >23</option>
                  <option value="24" <?=$strt_day == '24' ? 'selected' : ''; ?> >24</option>
                  <option value="25" <?=$strt_day == '25' ? 'selected' : ''; ?> >25</option>
                  <option value="26" <?=$strt_day == '26' ? 'selected' : ''; ?> >26</option>
                  <option value="27" <?=$strt_day == '27' ? 'selected' : ''; ?>>27</option>
                  <option value="28" <?=$strt_day == '28' ? 'selected' : ''; ?> >28</option>
                  <option value="29" <?=$strt_day == '29' ? 'selected' : ''; ?> >29</option>
                  <option value="30" <?=$strt_day == '30' ? 'selected' : ''; ?> >30</option>
                  <option value="31" <?=$strt_day == '31' ? 'selected' : ''; ?> >31</option>
              </select>-
              <select name="data[start_date][year]" id="EventStartDateYear">
                  <option value=""></option>
                  <?php $y = 1996; 

                    while ($y<= 2036) {
                      if($y == $strt_year){
                           echo "<option value='$y' selected >$y</option>";
                      }else{
                         echo "<option value='$y' >$y</option>";
                      }
                     $y++;
                    }
                   ?>
              </select>
          </div>
          <div class="input date">
              <label for="EventStartDateMonth">End Date</label>
              <select name="data[end_date][month]" id="EventEndDateMonth">
                  <option value=""></option>
                  <option value="01" <?=$end_month == '01' ? 'selected' : ''; ?>  >January</option>
                  <option value="02" <?=$end_month == '02' ? 'selected' : ''; ?> >February</option>
                  <option value="03" <?=$end_month == '03' ? 'selected' : ''; ?> >March</option>
                  <option value="04" <?=$end_month == '04' ? 'selected' : ''; ?> >April</option>
                  <option value="05" <?=$end_month == '05' ? 'selected' : ''; ?> >May</option>
                  <option value="06" <?=$end_month == '06' ? 'selected' : ''; ?> >June</option>
                  <option value="07" <?=$end_month == '07' ? 'selected' : ''; ?> >July</option>
                  <option value="08" <?=$end_month == '08' ? 'selected' : ''; ?> >August</option>
                  <option value="09" <?=$end_month == '09' ? 'selected' : ''; ?> >September</option>
                  <option value="10" <?=$end_month == '10' ? 'selected' : ''; ?> >October</option>
                  <option value="11" <?=$end_month == '11' ? 'selected' : ''; ?> >November</option>
                  <option value="12" <?=$end_month == '12' ? 'selected' : ''; ?> >December</option>
              </select>-
              <select name="data[end_date][day]" id="EventEndDateDay">
                  <option value=""></option>
                  <option value="01" <?=$end_day == '01' ? 'selected' : ''; ?> >1</option>
                  <option value="02" <?=$end_day == '02' ? 'selected' : ''; ?> >2</option>
                  <option value="03" <?=$end_day == '03' ? 'selected' : ''; ?> >3</option>
                  <option value="04" <?=$end_day == '04' ? 'selected' : ''; ?> >4</option>
                  <option value="05" <?=$end_day == '05' ? 'selected' : ''; ?> >5</option>
                  <option value="06" <?=$end_day == '06' ? 'selected' : ''; ?> >6</option>
                  <option value="07" <?=$end_day == '07' ? 'selected' : ''; ?> >7</option>
                  <option value="08" <?=$end_day == '08' ? 'selected' : ''; ?> >8</option>
                  <option value="09" <?=$end_day == '09' ? 'selected' : ''; ?> >9</option>
                  <option value="10" <?=$end_day == '10' ? 'selected' : ''; ?> >10</option>
                  <option value="11" <?=$end_day == '11' ? 'selected' : ''; ?> >11</option>
                  <option value="12" <?=$end_day == '12' ? 'selected' : ''; ?> >12</option>
                  <option value="13" <?=$end_day == '13' ? 'selected' : ''; ?> >13</option>
                  <option value="14" <?=$end_day == '14' ? 'selected' : ''; ?> >14</option>
                  <option value="15" <?=$end_day == '15' ? 'selected' : ''; ?> >15</option>
                  <option value="16" <?=$end_day == '16' ? 'selected' : ''; ?> >16</option>
                  <option value="17" <?=$end_day == '17' ? 'selected' : ''; ?> >17</option>
                  <option value="18" <?=$end_day == '18' ? 'selected' : ''; ?> >18</option>
                  <option value="19" <?=$end_day == '19' ? 'selected' : ''; ?> >19</option>
                  <option value="20" <?=$end_day == '20' ? 'selected' : ''; ?> >20</option>
                  <option value="21" <?=$end_day == '21' ? 'selected' : ''; ?> >21</option>
                  <option value="22" <?=$end_day == '22' ? 'selected' : ''; ?> >22</option>
                  <option value="23" <?=$end_day == '23' ? 'selected' : ''; ?> >23</option>
                  <option value="24" <?=$end_day == '24' ? 'selected' : ''; ?> >24</option>
                  <option value="25" <?=$end_day == '25' ? 'selected' : ''; ?> >25</option>
                  <option value="26" <?=$end_day == '26' ? 'selected' : ''; ?> >26</option>
                  <option value="27" <?=$end_day == '27' ? 'selected' : ''; ?>>27</option>
                  <option value="28" <?=$end_day == '28' ? 'selected' : ''; ?> >28</option>
                  <option value="29" <?=$end_day == '29' ? 'selected' : ''; ?> >29</option>
                  <option value="30" <?=$end_day == '30' ? 'selected' : ''; ?> >30</option>
                  <option value="31" <?=$end_day == '31' ? 'selected' : ''; ?> >31</option>
              </select>-
              <select name="data[end_date][year]" id="EventEndDateYear">
                  <option value=""></option>
                  <?php $y = 1996; 

                    while ($y<= 2036) {
                      if($y == $end_year){
                           echo "<option value='$y' selected >$y</option>";
                      }else{
                         echo "<option value='$y' >$y</option>";
                      }
                     $y++;
                    }
                   ?>                  
              </select>
          </div>
          <br/>
          <p><strong>Fill in the email sender detail</strong></p>
          <p><strong>Make sure you enter a valid email address</strong></p>
            <?php 
              echo $this->Form->control('event_email_sender', array('label' => 'Default Email sender name','class'=>'form-control'));
              echo $this->Form->control('event_email_address', array('label' => 'Default Email sender address','class'=>'form-control'));
            ?>
                                    
             
            <div class="input text">
              <label>Feed Security Key</label>
              <input type="text" value="<?php echo $event['feed_security_key'] ?>" class="form-control" readonly>
              <small>XML Feed URL: <a target="blank" href="<?php echo $xml_url= Router::url('/', true).'feeds/xmlfeed/'.$event['feed_security_key']; ?>"><?php echo $xml_url= Router::url('/', true).'feeds/xmlfeed/'.$event['feed_security_key']; ?></a></small>
            </div>
            
            <hr>
            <div class="row">
              <div class="col-md-12">
                <label class="mt-checkbox">
                  <input type="checkbox" name="enable_cs_cart" value="1" <?php echo ($event['enable_cs_cart']==1) ? 'checked' : '' ?>> Enable Marketplace
                  <span></span>
                </label>
              </div>

              <div class="col-md-12">
                <label class="mt-checkbox">
                  <input type="checkbox" name="enable_agent" value="1" <?php echo ($event['enable_agent']==1) ? 'checked' : '' ?>> Enable Additional Users
                  <span></span>
                </label>
              </div>

              <div class="col-md-12">
                <label class="mt-checkbox">
                  <input type="checkbox" name="enable_exhibitor_invite" value="1" <?php echo ($event['enable_exhibitor_invite']==1) ? 'checked' : '' ?>> Enable Exhibitor Invites
                  <span></span>
                </label>
              </div>

              <div class="col-md-12">
                <label class="mt-checkbox">
                  <input type="checkbox" name="enable_form_approval" value="1" <?php echo ($event['enable_form_approval']==1) ? 'checked' : '' ?>> Enable Form Approval
                  <span></span>
                </label>
              </div>

            </div>
                                    
            <br>
            <input type="hidden" name="old_event_name" value="<?php echo $event['name'] ?>">
            <input type="hidden" name="old_company_id" value="<?php echo $event['company_id'] ?>">
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
<?php use Cake\ORM\TableRegistry; ?>
<?php use Cake\Routing\Router; ?>
<style type="text/css">
  thead tr th a{
    color: #3f4254!important;
    font-weight: bold;
  }
  thead tr th a:hover{
    color: #8950fc!important;
    font-weight: bold;
  }
  .pagination li a:hover{
    color: #8950fc!important;
    font-weight: bold;
  }
  form{
    min-width: 50%;
  }
  .modal-dialog {
    margin: 156px auto 30px;
    width: 600px;
}
.minHeight{
  min-height: 200px
}
 #toast-container>div{
    opacity: 1;
}
</style>


<div class="card card-custom">
  <div class="card-header">
    <div class="card-title">
      <span class="card-icon">
        <i class="fa fa-group text-primary"></i>
      </span>
      <h3 class="card-label">Users List</h3>
    </div>
    <div class="card-toolbar">
      
      <!--begin::Button-->
      <!--end::Button-->
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <?php //echo $this->Form->create(null); ?>
      <div class="col-md-12">
        <div class="form-group row">
          <div class="col-lg-3">
            <?php //echo $this->Form->input('key',array('class'=>'form-control','placeholder'=>'Search','label'=>false,'value'=>$key)); ?>
            <input type="text" class="form_search_input form-control" id="search_key" name="key" value="<?php echo @$search['key'] ?>">
          </div>
          <div class="col-lg-6">
            <button class="btn btn-primary kt-btn kt-btn--icon d-block" type="submit"  name="search" id="exhibitor_search_index">
              <span>
                <i class="la la-search"></i>    
                <span>Search</span>
              </span>
            </button>
          </div>
        </div>
      </div>
      <?php //echo $this->Form->end(); ?>
    </div>
    <!--begin: Datatable-->
    <div class="table-responsive minHeight" style="margin-bottom: 1%;">
      <table class="table table-separate table-head-custom table-checkable table-hover" id="kt_datatable">
        <thead>
          <tr>
            <th><?php echo $this->Paginator->sort('email');?></th>
            <th><?php echo $this->Paginator->sort('firstname');?></th>
            <th><?php echo $this->Paginator->sort('lastname');?></th>           
            <th><?php echo $this->Paginator->sort('user_type');?></th>           
            <th style="color: #3f4254!important;font-weight: bold;min-width:115px;">Events</th>           
            <th style="color: #3f4254!important;font-weight: bold;min-width:115px;">Actions</th>
          </tr>
        </thead>
        <?php 
          if(count($users)!=0){
          foreach ($users as $user): ?>
            <?php $exhib_regs = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.user_id' => $user['id']]);

            $exb_event='';
            foreach($exhib_regs as $exhib_reg){
                $event = TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $exhib_reg['event_id']])->toArray();
                $exb_event.= $event[0]['name']." , ";
            }
            ?>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['firstname']; ?></td>
            <td><?php echo $user['lastname']; ?></td>
            <td><?php echo $user['user_type']; ?></td>
            <td><?php echo rtrim($exb_event,' , '); ?></td>
            
            <td class="actions">
              <?php $delUrl= $this->Url->build(array('controller' => 'users', 'action' => 'deleteUsers',$user['id'])); 
              $tab="User"; ?> 
              
              <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="confirm_del('<?php echo $delUrl ?>','<?php echo $tab ?>')">Delete</a>
            </td>
          </tr>
        <?php endforeach; }else{
          ?>
          <tr>
            <td colspan="10" class="text-center">
            No Record Found
          </td>
          </tr>

        <?php } ?>
        
      </table>
    </div>
    <div class="row">
      <div class="col-sm-3 col-md-3">
          Show 
            <select  class="custom-select custom-select-sm form-control-sm" style="width: 60px;">
              <option value="10" <?php if(@$search['set_limit']=='10'){
                echo "selected";
              } ?>>10</option>
              <option value="25" <?php if(@$search['set_limit']=='25'){
                echo "selected";
              } ?>>25</option>
              <option value="50" <?php if(@$search['set_limit']=='' || @$search['set_limit']=="50"){
                echo "selected";
              } ?>>50</option>
              <option value="100" <?php if(@$search['set_limit']=='100'){
                echo "selected";
              } ?>>100</option>
            </select> entries
      </div>
    </div>
    <div class="row" style="margin-top: 10px;">
      <div class="col-md-6">
        <span>
          <?php echo $this->Paginator->counter('Showing {{start}} to {{end}} of {{count}} entries'); ?>
        </span>
      </div>

      <div class="col-md-6">
        <div class="pagination-large" style="float: right;">
         <ul class="pagination" style="visibility: visible;">
            <?php echo $this->Paginator->prev('<') ?>
            <?php echo $this->Paginator->numbers() ?>
            <?php echo $this->Paginator->next('>') ?>
         </ul>
       </div>
     </div>
    </div>
    <!--end: Datatable-->
  </div>
</div>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
 

  $(document).ready(function(){
    $('.pagination li a').addClass('btn');
    page = "<?php echo (str_contains($_SERVER['REQUEST_URI'],'='))?explode('=', $_SERVER['REQUEST_URI'])[1] : '1'; ?>";
    $('.pagination li a').each(function(event){
      if($(this).html()==page){
        $(this).addClass('btn-primary');
      }else{
        if(page==""){
          if($(this).html()==1){
            $(this).addClass('btn-primary');
          }
        }
      }
    });

    $('.custom-select').change(function(){
    var limit = $(this).val();
    var search = $('input[name=key]').val();
    var url_self = "<?php echo $this->Url->webroot ?>/users/allUserList";
    if(search!=""){
       url_self = url_self + '/search_key:'+search;
    }
    var url = url_self;

    if((limit).length && ((limit).length > 0)){
        url = url + '/set_limit:'+encodeURIComponent(limit);
    }
    window.open(url, '_self');

  });
  
  });
  function confirm_del(delUrl,tab){
    bootbox.confirm("Are you sure you wish to delete this "+tab, function(result) {
      if(result==true){
         window.location.href = delUrl;
      }
    }); 
  }
  $(document).ready(function(){
  $(".form_search_input").keypress(function(e){
    if(e.which == 13){
       $('#exhibitor_search_index').trigger('click');
    }
  });

  $('#exhibitor_search_index').click(exhibitor_search);

  j$('#clear_search').click(exhib_clear_search);

})


function exhib_clear_search(){
    $('input.form_search_input').each(function(e){
        j$(this).val('');
    });

    exhibitor_search();
}


function exhibitor_search(){
    var entry = $('.custom-select :selected').text();
    var url_self = "<?php echo $this->Url->webroot ?>/users/allUserList/set_limit:"+entry;
    var url = url_self;
    if($('#search_key').length && ($('#search_key').val().length > 0)){
        url = url + '/search_key:'+encodeURIComponent($('#search_key').val());
    }
    window.open(url, '_self');
  }

</script>

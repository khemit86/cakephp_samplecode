<?php use Cake\ORM\TableRegistry; ?>
<style>
    .page-content-row .page-content-col {
    padding-left: 0px !important;
    padding-right: 0px !important;
}

.modal-dialog {
    margin: 156px auto 30px;
    width: 600px;
}
/*thead tr th a{
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
  tbody tr td a{
    color: #3f4254!important;
    
  }
  tbody tr td a:hover{
    color: #8950fc!important;
   
  }*/
</style>

<div class="card card-custom">
  <div class="card-header">
    <div class="card-title">
      <span class="card-icon">
        <i class="flaticon2-layers text-primary"></i>
      </span>
      <h3 class="card-label">Events</h3>
    </div>
    <div class="card-toolbar">
      <!-- <?php echo $this->Html->link(__('New Event', true), array('action' => 'add')); ?> -->
      <?php echo $this->Html->link('<i class="fa fa-plus"></i> Add New Event', array('action' => 'add'),array('class'=>'btn btn-primary font-weight-bolder ','escape'=>false)); ?>
    </div>
  </div>
  <div class="card-body">
    <?php //echo $this->Form->create(null) ?> 
    <div class="row form-group">
      
        <div class="col-md-5">
         <input type="text" name="search" placeholder="Enter event name..." id="search_key" class="form_search_input form-control" value="<?php echo @$keyword ?>">
        </div>
        <div class="col-md-1">
         <button type="submit" class="btn btn-sm btn-primary form-control" id="event_search_index">Search</button>
        </div>
     <?php //echo $this->Form->end(); ?>
    </div>
    <div class="table-responsive">                     
       <table class="table table-separate table-head-custom table-checkable" id="kt_datatable">
        <thead>
          <tr>
              <th><?php echo $this->Paginator->sort('name');?></th>
              <th><?php echo $this->Paginator->sort('description');?></th>
              <th style="color: #3f4254!important;font-weight: bold;">Company</th>
              <th style=""><?php echo $this->Paginator->sort('start_date');?></th>
              <th style=""><?php echo $this->Paginator->sort('end_date');?></th>
              <th style=""><?php echo $this->Paginator->sort('updated');?></th>
              <th style="color: #3f4254!important;font-weight: bold;">Actions</th>
          </tr>
        </thead>
        <?php foreach ($events as $event): 
          $company= TableRegistry::getTableLocator()->get('Companies')->getCompanyByID($event['company_id'])
        ?>

          <tr>
            
            <td><?php echo $event['name']; ?></td>
            <td><?php echo $event['description']; ?></td>
            <td><?php echo @$company['company_name'] ?></td>
            <td><?php echo date('d/m/Y',strtotime($event['start_date'])); ?></td>
            <td><?php echo date('d/m/Y',strtotime($event['end_date'])); ?></td>
            <td><?php echo date('d/m/Y',strtotime($event['updated'])); ?></td>
            <td class="actions">

              <div class="dropdown dropdown-inline">
                  <a href="#" class="btn btn-clean btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ki ki-bold-more-hor"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                    <!--begin::Naviigation-->
                    <ul class="navi">
                      <li class="navi-item">
                        <?php echo $this->Html->link('<span class="navi-icon"><i class="fa fa-edit"></i></span><span class="navi-item"> Edit </span>', array('action' => 'edit', $event['id']),array('class'=>'navi-link','escape'=>false)); ?> 
                      </li>
                      
                      <li class="navi-item">
                        <?php $delUrl= $this->Url->build(array('controller' => 'events', 'action' => 'delete',$event['id'])); ?> 
                        <a href="javascript:void(0)" onclick="confirm_del('<?php echo $delUrl ?>')" class="navi-link"><span class="navi-icon"><i class="icon-trash"></i></span><span class="navi-item"> Delete </span></a>
                      </li>
                      
                    </ul>
                    <!--end::Naviigation-->
                  </div>
              </div> 
              <!-- <?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $event['id']),array('class'=>'btn btn-xs blue')); ?>

              <?php $delUrl= $this->Url->build(array('controller' => 'events', 'action' => 'delete',$event['id'])); ?> 
              <a href="javascript:void(0)" class="btn btn-xs red" onclick="confirm_del('<?php echo $delUrl ?>')">Delete</a> -->

            </td>
          </tr>

        <?php endforeach; ?>
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
              <option value="50" <?php if(@$search['set_limit']=='' || $search['set_limit']=="50"){
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
        <p> <?php echo $this->Paginator->counter('Showing {{start}} to {{end}} of {{count}} entries'); ?>
        </p>
      </div>
      <div class="col-md-6">

        <div class=" pagination-large" style="float: right;">
          <ul class="pagination" style="visibility: visible;">
            <?php echo $this->Paginator->prev(__('<'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled prev','disabledTag' => 'a'));

              echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));

              echo $this->Paginator->next(__('>'), array('tag' => 'li','currentClass' => 'disabled next'), null, array('tag' => 'li','class' => 'disabled next','disabledTag' => 'a'));
            ?>
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
 
 function confirm_del(delUrl){
    bootbox.confirm("Are you sure you want to delete ?", function(result) {
      if(result==true){
         window.location.href = delUrl;
      }
    }); 
 }

</script>
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
    var url_self = "<?php echo $this->Url->webroot ?>/events/index";
    var url = url_self;

    if((limit).length && ((limit).length > 0)){
        url = url + '/set_limit:'+encodeURIComponent(limit);
    }
    window.open(url, '_self');

  });
});

$(document).ready(function(){
  $(".form_search_input").keypress(function(e){
    if(e.which == 13){
       $('#event_search_index').trigger('click');
    }
  });

  $('#event_search_index').click(exhibitor_search);

  $('#clear_search').click(exhib_clear_search);

});

function exhib_clear_search(){
    $('input.form_search_input').each(function(e){
        j$(this).val('');
    });

    exhibitor_search();
}


function exhibitor_search(){
    var entry = $('.custom-select :selected').text();
    var url_self = "<?php echo $this->Url->webroot ?>/events/index/set_limit:"+entry;
    var url = url_self;
    if($('#search_key').length && ($('#search_key').val().length > 0)){
        url = url + '/search_key:'+encodeURIComponent($('#search_key').val());
    }
    window.open(url, '_self');
}
</script>

<?php /*
<div class="page-content-container">
  <div class="page-content-row">
   <!-- BEGIN PAGE SIDEBAR -->
    <div class="page-sidebar">
    <div style="padding-right:10px;">
      <h3>Action </h3>
      <nav class="navbar" role="navigation">
        <ul class="nav navbar-nav margin-bottom-35">
          <li><?php echo $this->Html->link(__('New Event', true), array('action' => 'add')); ?></li>
          <li class="active"><?php echo $this->Html->link(__('List Events', true), array('action' => 'index')); ?></li>
        </ul>
     </nav>
   </div>
  </div> 

  <div class="page-content-col">
    <div class="row">
      <div class="col-md-12">
        <div class="portlet box blue">
          <div class="portlet-title">
             <div class="caption"><i class="fa fa-gift"></i>Events</div> 
             <div class="tools">
               <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
             </div>
          </div>

          <div class="portlet-body">

           <div class="row" style="margin-bottom: 10px">
             <?php echo $this->Form->create(null) ?> 
               <div class="col-md-5">
                 <input type="text" name="search" placeholder="Enter event name..." class="form-control" value="<?php echo @$keyword ?>">
               </div>
               <div class="col-md-6">
                 <button type="submit" class="btn btn-sm blue">Search</button>
               </div>
             <?php echo $this->Form->end(); ?>
           </div>  
           <div class="table-responsive">                     
             <table class='table table-striped table-bordered table-hover'>
               <thead>
                  <tr>
                      <th><?php echo $this->Paginator->sort('id');?></th>
                      <th><?php echo $this->Paginator->sort('name');?></th>
                      <th><?php echo $this->Paginator->sort('description');?></th>
                      <th>Company</th>
                      <th style="min-width: 100px;"><?php echo $this->Paginator->sort('start_date');?></th>
                      <th style="min-width: 100px;"><?php echo $this->Paginator->sort('end_date');?></th>
                      <th style="min-width: 170px;"><?php echo $this->Paginator->sort('updated');?></th>
                      <th style="min-width: 140px;">Actions</th>
                  </tr>
               </thead>
               <?php foreach ($events as $event): 
                 $company= TableRegistry::getTableLocator()->get('Companies')->getCompanyByID($event['company_id'])
               ?>

              <tr>
                <td><?php echo $event['id']; ?></td>
                <td><?php echo $event['name']; ?></td>
                <td><?php echo $event['description']; ?></td>
                <td><?php echo $company['company_name'] ?></td>
                <td><?php echo date('Y-m-d',strtotime($event['start_date'])); ?></td>
                <td><?php echo date('Y-m-d',strtotime($event['end_date'])); ?></td>
                <td><?php echo date('Y-m-d',strtotime($event['updated'])); ?></td>
                <td class="actions">
                  <?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $event['id']),array('class'=>'btn btn-xs blue')); ?>

                  <?php $delUrl= $this->Url->build(array('controller' => 'events', 'action' => 'delete',$event['id'])); ?> 
                  <a href="javascript:void(0)" class="btn btn-xs red" onclick="confirm_del('<?php echo $delUrl ?>')">Delete</a>

                </td>
              </tr>

             <?php endforeach; ?>
           </table>
         </div>
         <p> <?php echo $this->Paginator->counter('showing {{current}} records out of {{count}} total'); ?>
         </p>

         <div class=" pagination-large">
           <ul class="pagination" style="visibility: visible;">
            <?php echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled prev','disabledTag' => 'a'));

              echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));

              echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled next'), null, array('tag' => 'li','class' => 'disabled next','disabledTag' => 'a'));
            ?>
           </ul>
        </div>


      </div>
    </div>
  </div>
 </div>
</div>



  </div>
</div>
*/?>
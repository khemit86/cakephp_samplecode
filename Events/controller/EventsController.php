<?php 
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Event;
use Cake\Http\ServerRequest;

use Cake\View\Helper\HtmlHelper;
use Cake\Routing\Router;
use EntMail;
use EntCustomTheme;
use Cake\Datasource\ConnectionManager;
use App\Controller\UsersController;


class EventsController extends AppController{
  protected $currentSession;
  protected $Event;
  protected $EventExhibitorType;
  protected $EventBoothType;
  protected $ApForm;
  protected $EventPage;
  protected $NavOrder;
  protected $ContentBlock;
  protected $SystemSetting;


  public $paginate = [
      'Events' => [
          'limit' => 50,
            'order' => [
                'Events.id' => 'DESC'
            ]
      ],

    ];

  public function initialize(): void
  {
    parent::initialize();
    
    $this->Event = TableRegistry::getTableLocator()->get('Events');
    $this->EventExhibitorType = TableRegistry::getTableLocator()->get('EventExhibitorTypes');
    $this->EventBoothType = TableRegistry::getTableLocator()->get('EventBoothTypes');
    $this->ApForm = TableRegistry::getTableLocator()->get('ApForms');
    $this->EventPage = TableRegistry::getTableLocator()->get('EventPages');
    $this->NavOrder = TableRegistry::getTableLocator()->get('NavOrders');
    $this->ContentBlock = TableRegistry::getTableLocator()->get('ContentBlocks');
    $this->SystemSetting = TableRegistry::getTableLocator()->get('SystemSettings');
    $this->Companies = TableRegistry::getTableLocator()->get('Companies');
  } 

  public function beforeFilter(\Cake\Event\EventInterface $event){
      parent::beforeFilter($event);
      $this->currentSession=$this->getRequest()->getSession();
      //=========================================================
      
      if(!$this->currentSession->check('user')){
          $this->redirect(array('controller'=>'Users', 'action' => 'login'));
          return;
      }

      $user_type = $this->currentSession->read('user.type');
      $allowed_arr= array('admin');
      if((!in_array($user_type, $allowed_arr))&& (!$this->currentSession->check('user.reseller_user'))){
         $this->redirect(array('controller'=>'home', 'action' => 'index'));
      }
      
  }

//=============================================================

function index(){
  $condition=array();
  if($this->currentSession->check('user.reseller_user')){
    $organiser_company= $this->Companies->v4find('all',array('fields'=>array('id'),'conditions'=>array('Companies.user_id'=>$this->currentSession->read('user.id'))));
    // condition to get event from all company
    $condition =  array(
            'OR' => array(
                array('Events.company_id IN' => $organiser_company),
                array('Events.company_id IN' => $this->getRequest()->getSession()->read('user.company_id')),
            )
        );

  }
  $query = $this->Events->find()->where($condition);
  $search= array();
  $params= $this->request->getAttribute('params')['pass'];
  if(count($params) > 0){
    foreach($params as $param){
       $param_arr= explode(":", $param);
       $search_key= $param_arr[0];
       $search_value= $param_arr[1];

       $search[$search_key]= $search_value;

        if($search_key=='set_limit'){
            $tmp = $search_value;
            if($tmp!=''){
                $this->paginate = ['limit'=>$tmp];
            } else {
               $this->paginate = ['limit'=>50];
            }
        }
        if($search_key=='search_key'){
          $search['key']= $search_value;
          $key = trim($search_value);
          $key=str_replace("'","",$key);
          $conditions[]= "(Events.name LIKE '%".$key."%')";
          if($this->currentSession->check('user.reseller_user')){
          $organiser_company= $this->Companies->v4find('all',array('fields'=>array('id'),'conditions'=>array('Companies.user_id'=>$this->currentSession->read('user.id'))));
            // condition to get event from all company
            $conditions[] =  array(
                    'OR' => array(
                        array('Events.company_id IN' => $organiser_company),
                        array('Events.company_id IN' => $this->getRequest()->getSession()->read('user.company_id')),
                    )
                );
          }
          $query= $this->Events->find()->where($conditions);
          $this->set('keyword',$key);
        }
    }
  }
  $events=$this->paginate($query);
  $this->set('events', $events);
  $this->set('search',$search);

  /*if($this->request->is(['post','put'])){
     if($this->request->getData()){
         $keyword= $this->request->getData('search');
         $conditions[]= "(Events.name LIKE '%".$keyword."%')";
         if($this->currentSession->check('user.reseller_user')){
            $organiser_company= $this->Companies->v4find('all',array('fields'=>array('id'),'conditions'=>array('Companies.user_id'=>$this->currentSession->read('user.id'))));
            // condition to get event from all company
            $conditions[] =  array(
                    'OR' => array(
                        array('Events.company_id IN' => $organiser_company),
                        array('Events.company_id IN' => $this->getRequest()->getSession()->read('user.company_id')),
                    )
                );

          }
         $events= $this->Events->find()->where($conditions);
         $events=$this->paginate($events);
         $this->set('events', $events);
         $this->set('keyword',$keyword);
     }  
  }*/
}

function view($id = null) {
  if (!$id) {
    $this->Session->setFlash(__('Invalid event', true));
    $this->redirect(array('action' => 'index'));
  }
  $this->set('event', $this->Event->read(null, $id));
}

function add_feed_key(){
  $this->Event->recursive=-1;
  $events= $this->Event->find('all');
  foreach($events as $event){
    $key=$this->User->generate_password(5);
          $feed_security_key= $this->User->encode_password($key);
          $data= array();
          $data['id']= $event['Event']['id'];
          $data['feed_security_key']= $feed_security_key;
          echo "<pre>"; print_r($data);
          $this->Event->save($data);

  }
  
  exit;
}

function add() {
  $comp_conditions= array();
  if($this->currentSession->check('user.reseller_user')){
    $comp_conditions=array("OR"=>array('Companies.user_id'=>$this->currentSession->read('user.id'), 'Companies.id'=>$this->currentSession->read('user.company_id')));
  }
   $companies = TableRegistry::getTableLocator()->get('Companies')->find()->where($comp_conditions);
   $this->set('companies', $companies);
   // create feed security key
   $key=TableRegistry::getTableLocator()->get('Users')->generate_password(5);
   $feed_security_key= TableRegistry::getTableLocator()->get('Users')->encode_password($key);
   $this->set('feed_security_key', $feed_security_key);

   // get company id
    //$company_id= $this->Events->field('company_id',array('id'=>$this->currentSession->read('user.event.id')));
    $conditions= array();
    #$conditions['id !=']= $this->currentSession->read('user.event.id'); // should not be current event
    #$conditions['company_id']= $company_id;
    if($this->currentSession->check('user.reseller_user')){
      $conditions = array('Events.company_id IN' => $this->getRequest()->getSession()->read('user.company_id'));
      if($companies){
        $organiser_company= $this->Companies->field('id',array('Companies.user_id'=>$this->currentSession->read('user.id')));
        if(!empty($organiser_company)){
          //$conditions = array('Events.company_id'=>$organiser_company);
          $conditions =  array(
            'OR' => array(
                array('Events.company_id IN' => $organiser_company),
                array('Events.company_id IN' => $this->getRequest()->getSession()->read('user.company_id')),
            )
        );
        }
      }
    }
    
    $comp_events= $this->Events->v4find('all',array('fields'=>array('id','name'),'conditions'=>$conditions));
    $this->set('comp_events',$comp_events);
    //echo "<pre>"; print_r($comp_events->toArray()); exit;

   if(!empty($this->request->is(['post','put']))){
       $exist= $this->Events->find()->where(['Events.name'=>$this->request->getData('name')])->first();

      if(!empty($exist)){
          $this->Flash->error('Event already exist. Please change the event name');   
          return $this->redirect(array('action' => 'add'));
      }

      $event_data= $this->request->getData();
    
      //*************Test****************
      
      //*********************************
      $event_data['email_subject']='%%EVENT_NAME%% : Your login to the Exhibitor Information Website';
      $event_data['reply_email']= $event_data['event_email_address'];
      /* $event_data['start_date']= date('Y-m-d',strtotime($event_data['start_date']));
      $event_data['end_date']= date('Y-m-d',strtotime($event_data['end_date'])); */
	  $event_data['start_date']= date('Y-m-d', strtotime(str_replace('/', '-', $event_data['start_date'])));
      $event_data['end_date']= date('Y-m-d', strtotime(str_replace('/', '-', $event_data['end_date'])));
      //$event_data['theme_layout']='new';

      // ======== manage clone variable ===========
      $clone= array();
      if(isset($event_data['clone_another_event'])){
        $clone['clone_another_event']= $event_data['clone_another_event'];
        $clone['cloned_event_id']= @$event_data['cloned_event_id'];
        $clone['clone_forms']= @$event_data['clone_forms'];
        $clone['clone_pages']= @$event_data['clone_pages'];
        $clone['clone_content_blocks']= @$event_data['clone_content_blocks'];
        $clone['clone_exib_types_and_stand_type']= @$event_data['clone_exib_types_and_stand_type'];
        $clone['clone_branding']= @$event_data['clone_branding'];
        
        unset($event_data['clone_another_event']);
        if(isset($event_data['cloned_event_id'])){
          unset($event_data['cloned_event_id']);
        }

        if(isset($event_data['clone_forms'])){
          unset($event_data['clone_forms']);
        }

        if(isset($event_data['clone_pages'])){
          unset($event_data['clone_pages']);
        }

        if(isset($event_data['clone_content_blocks'])){
          unset($event_data['clone_content_blocks']);
        }

        if(isset($event_data['clone_exib_types_and_stand_type'])){
          unset($event_data['clone_exib_types_and_stand_type']);
        }

        if(isset($event_data['clone_branding'])){
          unset($event_data['clone_branding']);
        }
      }
      //$event_data['unique_identify']=1;
      //============ clone variable end ============
    
      //echo "<pre>"; print_r($event_data); exit;

      $entity_data= $this->Events->newEntity($event_data);
      if($this->Events->save($entity_data)){
          $event_id = $entity_data->id;

          // save default Exhibitor Type
          $extypearr=array();
          //$extypearr['name']= "Default";
          $extypearr['name']= "Exhibitor";
          $extypearr['default_exhib_type']= 1;
          $extypearr['event_id']= $event_id;

          $entity_data= TableRegistry::getTableLocator()->get('EventExhibitorTypes')->newEntity($extypearr);
          TableRegistry::getTableLocator()->get('EventExhibitorTypes')->save($entity_data);

          // save default Stand Types
          $stands= array('Shell Scheme','Space Only');
          foreach($stands as $key=>$stand_type){
               $stand_typearr=array();
               $stand_typearr['name']= $stand_type;
               $stand_typearr['event_id']= $event_id;
               $entity_data= TableRegistry::getTableLocator()->get('EventBoothTypes')->newEntity($stand_typearr);
               TableRegistry::getTableLocator()->get('EventBoothTypes')->save($entity_data);
          }

          // save default email header
          $header= array();
          $header['reply_email']= $event_data['reply_email'];
          $header['from_name']= $event_data['event_email_sender'];
          $header['event_id']= $event_id;
          $entity_data= TableRegistry::getTableLocator()->get('EmailHeaderLists')->newEntity($header);
          TableRegistry::getTableLocator()->get('EmailHeaderLists')->save($entity_data);

          // provoide priv to all super user (within company) to access newly created event
          $super_users= TableRegistry::getTableLocator()->get('Users')->find('all',array('conditions'=>array('event_company_id'=>$event_data['company_id'],'user_type'=>'client','org_admin'=>1)));
          foreach($super_users as $super_user){
              $reg_record = array();
              $reg_record['event_id'] = $event_id;
              $reg_record['user_id'] = $super_user['id'];
              $reg_record['status'] = 'client';
              $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($reg_record);
              TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data);
          }

          // if(!empty($clone)){
          //    $this->cloneAnotherEventData($clone,$event_id);
          // }

          //echo "submited"; exit;

          // end of default entry

          $default_email_folder = EMAIL_TEMPLATE_FOLDER.'default'.DS;
          $email_folder = EMAIL_TEMPLATE_FOLDER.'events'.DS.$event_id.DS;
          if(!is_dir($email_folder)){
            mkdir($email_folder, 0777, true);
            if(file_exists($default_email_folder.'event_welcome.tpl')){
               copy($default_email_folder.'event_welcome.tpl',  $email_folder.'event_welcome.tpl');
            }
            
            if(file_exists($default_email_folder.'event_withdraw.tpl')){
               copy($default_email_folder.'event_welcome.tpl',  $email_folder.'event_withdraw.tpl');
            }
          }
          
          $css_events_folder = WWW_ROOT.'css'.DS.'events';
          $new_event_css_folder = $css_events_folder.DS.$event_id;
          if(!is_dir($new_event_css_folder)){
            mkdir($new_event_css_folder, 0777, true);
            $default_css = $css_events_folder.DS.'default'.DS.'screen.css';
            if(file_exists($default_css)){
              copy($default_css, $new_event_css_folder.DS.'screen.css');
            }
          }
          
          $css_form_folder = WWW_ROOT.'css'.DS.'forms';
          $event_default_form_css = $css_form_folder.DS."view_{$event_id}.css";
          if(!file_exists($event_default_form_css)){
            $default_form_css = $css_form_folder.DS."view.css";
            if(file_exists($default_form_css)){
              copy($default_form_css,  $event_default_form_css);
            }
          }

          //**********create default theme template***************

          $company_id= $event_data['company_id'];
          $theme_name= $event_data['name'];
          $theme_id=$this->Events->createTheme($theme_name,$company_id);
          
          require_once(ROOT . DS . 'vendor' . DS  . 'ent_custom_theme.php');
          $custom_theme = new EntCustomTheme();
          $custom_theme->theme_get_css_content($theme_id);

          //******************************************************
          //******************************************************
        if(!empty($clone)){
          $this->cloneAnotherEventData($clone,$event_id);
        }
        


          $this->Flash->success('The event has been saved');   
          $this->redirect(array('action' => 'index'));
          return;
    } else {
      $this->Flash->error('The event could not be saved. Please, try again.');   
      return;  
      
    }
  }

}

    public function addCompanyAjax(){
      $this->autoRender= false;
      $this->autolayout= false;
      $this->layout='ajax';

      if($this->request->is(['post','put'])){
            if($this->request->getData()){
                $data= $this->request->getData();
                
                if(!isset($data['can_create_event'])){
                   $data['can_create_event']=0;
                   $data['event_credit']=0;

                   $data['can_enable_agent']=0;
                   $data['can_enable_exhibitor_invite']=0;

                }else{
                   $data['can_create_event']=1;

                   // check invoice number
                   $invoice=TableRegistry::getTableLocator()->get('CompanyInvoices')->find()->where(['CompanyInvoices.invoice_number'=>$data['invoice_number']])->first();
                   
                   if(!empty($invoice)){
                      echo 'exist';
                      exit;
                   }

                   $data['can_enable_agent']= (isset($data['can_enable_agent'])) ? 1 : 0;
                   $data['can_enable_exhibitor_invite']= (isset($data['can_enable_exhibitor_invite'])) ? 1 : 0;
                }
               
                $entity_data= TableRegistry::getTableLocator()->get('Companies')->newEntity($data);
                if(TableRegistry::getTableLocator()->get('Companies')->save($entity_data)){
                   $company_id= $entity_data->id;
                      $data['user_id']=$this->currentSession->read('user.id');
                      $this->connection = ConnectionManager::get('default');
                        $id = $company_id;
                        $query="UPDATE companies SET user_id = '".$data['user_id']."' WHERE companies.id=".$id."";
                        $this->connection->execute($query);
                   if(isset($data['can_create_event'])){
                       $inv= array();
                       $inv['company_id']= $company_id;
                       $inv['qty']= $data['event_credit'];
                       $inv['invoice_number']= $data['invoice_number'];

                       $entity_data= TableRegistry::getTableLocator()->get('CompanyInvoices')->newEntity($inv);
                       TableRegistry::getTableLocator()->get('CompanyInvoices')->save($entity_data);
                   }
                   
                }           
                
                echo trim($company_id);exit;
            }
        }
    }
public function cloneAnotherEventData($clone,$new_event_id){
    if($clone['clone_another_event']==1){
      if(!empty($clone['cloned_event_id'])){

        if($clone['clone_exib_types_and_stand_type']==1){
           $this->cloneEventExibTypeAndStandType($clone['cloned_event_id'],$new_event_id);
        }

        // clone forms
        if($clone['clone_forms']==1){
            $this->cloneEventForms($clone['cloned_event_id'],$new_event_id);
        }

        
        if($clone['clone_pages']==1){
            $this->cloneEventPages($clone['cloned_event_id'],$new_event_id);
        }

        
        if($clone['clone_content_blocks']==1){
            $this->cloneEventContentBlock($clone['cloned_event_id'],$new_event_id);
        }

        if($clone['clone_branding']==1){
            $this->cloneEventBranding($clone['cloned_event_id'],$new_event_id);
        }
      }
    }
    
    return true;
  }


  public function cloneEventExibTypeAndStandType($cloned_event_id,$new_event_id){
    // clone exhibitor type
    $exhibitor_type=$this->EventExhibitorType->v4find('all',array('conditions'=>array('event_id'=>$cloned_event_id)));

    foreach($exhibitor_type as $type){
      if($type['name'] =='Default' || $type['name']=='Exhibitor'){
         continue;
      }

      $data= array();
      $data['event_id']= $new_event_id;
      $data['name']= $type['name'];
      $data['description']= $type['description'];
      $data['default_exhib_type']= 0;

      $this->EventExhibitorType->v4save($data);
    }
    
    // clone stand types
    $booth_type=$this->EventBoothType->v4find('all',array('conditions'=>array('event_id'=>$cloned_event_id)));

    foreach($booth_type as $type){
      $stands= array('Shell Scheme','Space Only');
      if(in_array($type['name'], $stands)){
          continue;
      }

      $data= array();
      $data['event_id']= $new_event_id;
      $data['name']= $type['name'];
      $data['description']= $type['description'];
      
      $this->EventBoothType->v4save($data);
    }
    
  }

  public function cloneEventForms($cloned_event_id,$new_event_id){
      $form_conditions= array();
      $form_conditions['ent_event_id']= $cloned_event_id;
      $form_conditions['form_active']= 1;
      // get all form by tag
      $forms=$this->ApForm->v4find('all',array('fields'=>array('form_id','form_name'),'conditions'=>$form_conditions,'order' => array('form_name ASC')));

      foreach($forms as $form){  
          $form_id= $form['form_id'];

          $event_exhibitor_type=$this->EventExhibitorType->v4find('all',array('conditions'=>array('event_id'=>$new_event_id)));
          $Event_booth_type=$this->EventBoothType->v4find('all',array('conditions'=>array('event_id'=>$new_event_id)));

          $exhibitor_types='';
          $booth_Types='';
          foreach($event_exhibitor_type as $exhib_type){
                $exhibitor_types.="[".$exhib_type['id']."]";
          }

          foreach($Event_booth_type as $booth_types){
             $booth_Types.="[".$booth_types['id']."]";
          }

        
          $event= $this->Event->v4find('first',array('conditions'=>array('id'=>$new_event_id)));

          $default_theme= $this->Event->getDefaultTheme($event);
          $default_theme_id = 0;
          if($default_theme){
              $default_theme_id= $default_theme['theme_id'];
          }
          
            
          //Step 1: entry in ap_form
          $new_form_id=$this->Event->copyApForm($form_id,$new_event_id,$default_theme_id,$exhibitor_types,$booth_Types);

          //Step 2: //insert into ap_permissions table, so that this user can add fields
          $this->Event->copyApPermission($form_id,$new_form_id);
        
          //Step 3: create ap_form_{$form_id} table
          $this->Event->createApFormTable($form_id,$new_form_id);

          // Step 3.1 create ap_form_{form_id}_log table
          $this->Event->createApFormLogTable($form_id,$new_form_id);
        
          //Step 4: entry in ap_form_elements table
          $this->Event->copyApFormElement($form_id,$new_form_id);

          //Step 5: entry in ap_element_options table
          $this->Event->copyApFormElementOption($form_id,$new_form_id);

          //Step 6: entry in ap_element_prices table
          $this->Event->copyApFormElementPrice($form_id,$new_form_id);
        
          //Step 7: entry in ap_form_locks table
          $this->Event->copyApFormLocks($form_id,$new_form_id);

          //Step 8: entry in ap_email_logic table
          $this->Event->copyApFormEmailLogic($form_id,$new_form_id);
        
          //Step 9: entry in ap_email_logic_conditions table
          $this->Event->copyApFormEmailLogicCondition($form_id,$new_form_id);

          //Step 10: if blob then create form_{$form_id}_files table
          $this->Event->createApFormFileTable($form_id,$new_form_id);

          //Step 11: create data folder for this form
          $this->Event->createApFormDataFolder($form_id,$new_form_id);

          /************Manage other table*************/
        
          //Step 12: entry in ap_field_logic_elements
          $this->Event->copyApFieldLogicElements($form_id,$new_form_id);

          //Step 13: entry in ap_field_logic_conditions
          $this->Event->copyApFieldLogicConditions($form_id,$new_form_id);

          //entry in ap_page_logic
          $this->Event->copyApPageLogic($form_id,$new_form_id);

          //entry in ap_page_logic_conditions
          $this->Event->copyApPageLogicConditions($form_id,$new_form_id);

          //Step 14: entry in ap_webhook_options
          $this->Event->copyApWebhookOptions($form_id,$new_form_id);

          //Step 15: entry in ap_webhook_parameters
          $this->Event->ApWebhookParameters($form_id,$new_form_id);

          //Step 16: entry in ap_success_logic_options
          $this->Event->ApSuccessLogicOptions($form_id,$new_form_id);

          //Step 16: entry in ap_success_logic_conditions
          $this->Event->ApSuccessLogicConditions($form_id,$new_form_id);

      }

  }

  public function cloneEventPages($cloned_event_id,$new_event_id){
    $user=$this->currentSession->read('user');

    $from_pages= $this->EventPage->v4find('all',array('conditions'=>array('event_id'=>$cloned_event_id)));
    $cloned_menus= $this->NavOrder->v4find('first',array('conditions'=>array('event_id'=>$cloned_event_id)));
    
    $new_menue= @$cloned_menus['nav_order'];
    foreach($from_pages as $from_page){
        //=====================================
          $from_slug= $from_page['slug'];
          // remove spacial char
          $from_slug=ltrim($from_slug,"-");
          $from_slug=preg_replace("/[^ \w]+/", "-", $from_slug);
          $from_slug=rtrim($from_slug,"-");
          $from_slug= str_replace(" ", "-", $from_slug);
          // remove multiple dashes
          $from_slug = preg_replace('/-+/', '-', $from_slug);

          $slug=$this->checkValidSlugRecursive($from_slug,$new_event_id);
          
          //=====================================
          $save_arr= array();
          $save_arr['event_id']= $new_event_id;
          $save_arr['access_type']= $from_page['access_type'];
          $save_arr['show_exhib_info']= $from_page['show_exhib_info'];
          $save_arr['page_name']= $from_page['page_name'];
          $save_arr['text']= $from_page['text'];
          $save_arr['slug']= $slug;
          $save_arr['created']= date('Y-m-d');
          $save_arr['last_edited']=date('Y-m-d');
          $save_arr['last_edited_by']= $user['id'];
          $entity_data= $this->EventPage->v4save($save_arr);
          if($entity_data){
             $new_page_id = $entity_data->id;

             if($cloned_menus['nav_order']){
                $old_page_id= $from_page['id'];
                $new_menue= str_replace($old_page_id, $new_page_id, $new_menue);
             }
          }
    }

    if($new_menue){
       $nv_data= array();
       $nv_data['event_id']= $new_event_id;
       $nv_data['nav_order']= $new_menue;
       $this->NavOrder->v4save($nv_data);
    }
    
  }
    
  public function cloneEventContentBlock($cloned_event_id,$new_event_id){
      $contents= $this->ContentBlock->v4find('all',array('conditions'=>array('event_id'=>$cloned_event_id)));

      foreach($contents as $content){
              $data= array();
              $data['event_id']= $new_event_id;
              $data['block_name']= $content['block_name'];
              $data['content']= $content['content'];
              $data['short_code']= $content['short_code'];
              $data['created']= date('Y-m-d H:i:s');
              //echo "<pre>"; print_r($data); exit;
              $this->ContentBlock->v4save($data);
      }

  }

  public function cloneEventBranding($cloned_event_id,$new_event_id){
    $import_sett= $this->SystemSetting->v4find('first',array('conditions'=>array('event_id'=>$cloned_event_id)));
    if($import_sett){
        $data= array();
        $data['event_id']= $new_event_id;
        $data['color_1']= $import_sett['color_1'];
        $data['color_2']= $import_sett['color_2'];
        $data['color_3']= $import_sett['color_3'];
        $data['color_4']= $import_sett['color_4'];
        $data['color_5']= $import_sett['color_5'];
        $data['color_6']= $import_sett['color_6'];
        $data['color_7']= $import_sett['color_7'];
        $data['color_8']= $import_sett['color_8'];
        $data['color_9']= $import_sett['color_9'];
        $data['color_10']= $import_sett['color_10'];
        $data['color_11']= $import_sett['color_11'];
        $data['color_12']= $import_sett['color_12'];
        $data['color_13']= $import_sett['color_13'];
        $data['color_14']= $import_sett['color_14'];
        $data['color_15']= $import_sett['color_15'];
        $data['color_16']= $import_sett['color_16'];
        $data['color_17']= $import_sett['color_17'];
        $data['color_18']= $import_sett['color_18'];
        $data['color_19']= $import_sett['color_19'];
        $data['color_20']= $import_sett['color_20'];
        $data['logo']= $import_sett['logo'];
        $data['banner_logo']= $import_sett['banner_logo'];
        $data['login_logo']= $import_sett['login_logo'];

       // $this->SystemSetting->v4save($data);
        if($this->SystemSetting->v4save($data)){
        // if setting has banner logo
        if($data['banner_logo']){
          //check event email folder
          $email_folder = EMAIL_TEMPLATE_FOLDER.'events'.DS.$new_event_id;
          if(!file_exists($email_folder) || !is_dir($email_folder)){
                      mkdir($email_folder, 0777, true);
                  }

                  // copy default frame to current event folder
                  $source_file= EMAIL_TEMPLATE_FOLDER.'default'.DS.'event_frame.tpl';
                  $desti_file= EMAIL_TEMPLATE_FOLDER.'events'.DS.$new_event_id.DS.'event_frame.tpl';
                  copy($source_file, $desti_file);

                  if(file_exists(EMAIL_TEMPLATE_FOLDER.'events'.DS.$new_event_id.DS.'event_frame.tpl')){
                     $stored_file_name=EMAIL_TEMPLATE_FOLDER.'events'.DS.$new_event_id.DS.'event_frame.tpl';
                     $stored_file = fopen($stored_file_name, "rb");
                     $content = fread($stored_file, filesize($stored_file_name));
                     fclose($stored_file);

                     $find = "%%LOGO_PATH%%";
                     $base= Router::url('/', true);
                     $replace=  $base."img/logo/".$data['banner_logo'];
                     $email_frame = str_replace($find, $replace, $content);
                     file_put_contents($stored_file_name, $email_frame);
                  }

                  // update theme banner
                  $theme_event= $this->Event->v4find('first',array('conditions'=>array('id'=>$new_event_id)));
                    $ent_id= $theme_event['id'];
                    $ent_name= $theme_event['name'];

              #App::import('Vendor', 'ent_custom_theme');
              require_once(ROOT . DS . 'vendor' . DS  . 'ent_custom_theme.php');
              $custom_theme = new EntCustomTheme();
              list($width, $height) = getimagesize(WWW_ROOT.'img'.DS.'logo/'.$data['banner_logo']);
              $custom_theme->default_theme_update_css_content_clone($replace,$height,$ent_id,$ent_name);

        }
      }
    }
    
  }

  public function checkValidSlugRecursive($page_slug,$new_event_id){
      $slug= $this->EventPage->field('slug',array('slug'=>$page_slug,'event_id'=>$new_event_id));
    
      if(empty($slug)){
         return $page_slug;
      }

      // modify slug
      $slug_last_segment= explode("-",$slug);
      $end_slug = end($slug_last_segment);
      if(is_numeric($end_slug)){
          $inx= (int)$end_slug+1;
          array_pop($slug_last_segment); // remove last element
          $slug_implode= implode("-",$slug_last_segment);
          $new_slug= $slug_implode."-".$inx; // add last element
      }else{
          $new_slug= $slug."-2";
      }

      return $this->checkValidSlugRecursive($new_slug,$new_event_id);

  }



function create_new(){
  if (!empty($this->data)) {
    $this->Event->create();
    if ($this->Event->save($this->data)) {
      
      //$this->Session->write('flash', array('The event has been saved','success')); 
      $this->Session->setFlash('The event has been saved','success');   
      $this->redirect(array('action' => 'index'));
    } else {
      //$this->Session->write('flash', array('The event could not be saved. Please, try again.','failure'));
      $this->Flash->error('The event could not be saved. Please, try again.','failure');   
      
    }
  }
}

function edit($id = null) {
  if(!$id){
    $this->Flash->error(__('Invalid event'));
    $this->redirect(array('action' => 'index'));
  }

  $comp_conditions= array();
  if($this->currentSession->check('user.reseller_user')){
    $comp_conditions=array("OR"=>array('Companies.user_id'=>$this->currentSession->read('user.id'), 'Companies.id'=>$this->currentSession->read('user.company_id')));
  }
  $companies = TableRegistry::getTableLocator()->get('Companies')->find()->where($comp_conditions);
  $this->set('companies', $companies);

  $event = $this->Events->find()->where(['Events.id'=>$id])->first();
  $this->set('event', $event);

  if($this->request->is(['post','put'])){
    $event_data= $this->request->getData(); 
    //echo "<pre>"; print_r($event_data); exit;
    $event_start_date = $event_data['data']['start_date']['year'].'-'.$event_data['data']['start_date']['month'].'-'.$event_data['data']['start_date']['day'];
    $event_end_date = $event_data['data']['end_date']['year'].'-'.$event_data['data']['end_date']['month'].'-'.$event_data['data']['end_date']['day'];
    $event_data['start_date'] = $event_start_date;
    $event_data['end_date'] = $event_end_date;
    $event_data['company_id'] = $event_data['company_id'];
    
    $event_data['enable_cs_cart']= (isset($event_data['enable_cs_cart'])) ? 1 : 0;
    $event_data['enable_agent']= (isset($event_data['enable_agent'])) ? 1 : 0;
    $event_data['enable_exhibitor_invite']= (isset($event_data['enable_exhibitor_invite'])) ? 1 : 0;
    $event_data['enable_form_approval']= (isset($event_data['enable_form_approval'])) ? 1 : 0;
    unset($event_data['data']);
    //echo "<pre>"; print_r($event_data); exit;

    $entity_data= $this->Events->newEntity($event_data);
    if($this->Events->save($entity_data)){
      //===========Update default theme======================
      $old_theme_name= $event_data['old_event_name'];
      $old_company_id=$event_data['old_company_id'];

      $new_theme_name= $event_data['name'];
      $new_company_id= $event_data['company_id'];

      $this->Events->updateThemeName($old_theme_name,$old_company_id,$new_theme_name,$new_company_id);
      //======================================================

      $this->Flash->success('The event has been saved');
      $this->redirect(array('action' => 'index'));
    } else {
      $this->Flash->error(__('The event could not be saved. Please, try again.'));
    }
  }
      
}

function delete($id = null) {
  if(!$id) {
     $this->Flash->error(__('Invalid id for event'));
     $this->redirect(array('action'=>'index'));
  }
  
  //Remove all booth types,
  $booths = TableRegistry::getTableLocator()->get('EventBoothTypes')->findAllByEventId($id);
  
  foreach($booths as $each){
     TableRegistry::getTableLocator()->get('EventBoothTypes')->deleteAll(['id'=>$each['id']]);
  }
  
  //Remove all categories,
  /*$cats = $this->EventExhibitorCategory->findAllByEventId($id);
  foreach($cats as $each){
    $this->EventExhibitorCategory->delete($each['EventExhibitorCategory']['id']);
  }*/
  
  //Remove all EventExhibitorType,
  $exhs = TableRegistry::getTableLocator()->get('EventExhibitorTypes')->findAllByEventId($id);
  foreach($exhs as $each){
     TableRegistry::getTableLocator()->get('EventExhibitorTypes')->deleteAll(['id'=>$each['id']]);
  }
  
  //Remove all EventFiles,
  $evnfiles = TableRegistry::getTableLocator()->get('EventFiles')->findAllByEventId($id);
  foreach($evnfiles as $each){
    TableRegistry::getTableLocator()->get('EventFiles')->deleteAll(['id'=>$each['id']]);
  }
  
  //Remove all registrations,
  $regs = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->findAllByEventId($id);
  foreach($regs as $each){
    TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->deleteAll(['id'=>$each['id']]);
  }
  
  $base_folder = WWW_ROOT.'css'.DS.'forms'.DS;
  $css_file = $base_folder."view_".$id.".css";
  if(file_exists($css_file)){
    unlink($css_file);
  }
  
  //Remove site css folder and contents
  $css_events_folder = WWW_ROOT.'css'.DS.'events';
  $new_event_css_folder = $css_events_folder.DS.$id;
  if(is_dir($new_event_css_folder)){
    $d = dir($new_event_css_folder); 
    while($entry = $d->read()) { 
      if ($entry!= "." && $entry!= "..") { 
        unlink($new_event_css_folder.DS.$entry); 
      } 
    } 
    $d->close(); 
    rmdir($new_event_css_folder); 
  }
  
  if(TableRegistry::getTableLocator()->get('Events')->deleteAll(['id'=>$id])) {
    //$this->cleanups();
    $this->Flash->success('Event deleted');
    return $this->redirect(array('action'=>'index'));
  }

  $this->Flash->error(__('Event was not deleted'));
  $this->redirect(array('action' => 'index'));
}

  
   function cleanups(){
          $this->cleanup_forms();
          $this->cleanup_login_records();
          $this->cleanup_files();
          $this->cleanup_emails();
          if(isset($this->params['url']['show'])){
                  die('finish');
          }
  }

  function cleanup_forms(){
          $query = "SELECT  ap_forms.*
                                  FROM ap_forms
                                  WHERE ap_forms.ent_event_id NOT IN
                                          (SELECT id
                                                  FROM events)";
          $ap_form_entries = $this->ExhibitionRegistration->query($query);
          foreach($ap_form_entries as $each){
                  $form_id = $each['ap_forms']['form_id'];
                  $table_name = 'ap_form_'.$form_id;
                  $drop_table_query = "DROP TABLE `{$table_name}`";
                  $this->ExhibitionRegistration->query($drop_table_query);

                  if($each['ap_forms']['form_review']>0){ //has review table
                          $table_name = 'ap_form_'.$form_id.'_review';
                          $drop_table_query = "DROP TABLE `{$table_name}`";
                          $this->ExhibitionRegistration->query($drop_table_query);
                  }

                  //last, delete this entry
                  $delete_query = "DELETE FROM `ap_forms` WHERE `ap_forms`.`form_id` = '{$form_id}'";
                  $this->ExhibitionRegistration->query($delete_query);
          }
  }

  function cleanup_login_records(){
          $query = "DELETE FROM `user_login_records`
                                  WHERE event_id NOT IN 
                                  (SELECT id FROM events)";
          $login_records = $this->UserLoginRecord->query($query);

  }

  function cleanup_files(){
          $query = "DELETE FROM `event_files`
                                  WHERE event_id NOT IN 
                                  (SELECT id FROM events)";
          $login_records = $this->UserLoginRecord->query($query);

          $query = "DELETE FROM `view_files`
                                  WHERE event_id NOT IN 
                                  (SELECT id FROM events)";
          $login_records = $this->UserLoginRecord->query($query);
  }

  function cleanup_emails(){
          $query = "DELETE FROM `custom_email_records`
                                  WHERE event_id NOT IN 
                                  (SELECT id FROM events)";
          $login_records = $this->UserLoginRecord->query($query);
  }
 

  function changeEventSettings(){
    $status = $this->request->getData('status');
    $event_data['id'] = $this->currentSession->read('user.event.id');
    $event_data['is_event_with_stands'] = $status;
    $entity_data= $this->Events->newEntity($event_data);
    if($this->Events->save($entity_data)){
      echo "saved";exit;
    }
  }
  function uniqueIdentifyUser(){
    $status = $this->request->getData('status');
    $event_data['id'] = $this->currentSession->read('user.event.id');
    $event_data['unique_identify'] = $status;
    $entity_data = $this->Events->newEntity($event_data);
    if($this->Events->save($entity_data)){
      echo "saved";exit;
    }
  }
}
?>
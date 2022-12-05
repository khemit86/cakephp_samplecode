<?php 
namespace App\Controller;
require_once(ROOT . DS . 'vendor' . DS  . 'aws'. DS .'aws-autoloader.php');
use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\View\Helper\SessionHelper;
use Authentication\Controller\Component\AuthenticationComponent;
use Cake\Routing\Router;
use Cake\View\Helper\HtmlHelper;
use Cake\View\Helper\PaginatorHelper;
use Cake\Http\ServerRequest;
use Cake\View\EnthtmlHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Datasource\ConnectionManager;
//use EntMail;
use EntCustomForm;
use Spreadsheet_Excel_Reader;
/* use Aws\Ses\SesClient;
use Aws\Exception\AwsException; */
use SesMail;

class UsersController extends AppController{
	protected $currentSession;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->loadComponent('RequestHandler');
        //$this->loadComponent('Auth');
    }
    
    
    public $paginate = [
      'ExhibitionRegistrations' => [
          'limit' => 10,
          'sortWhitelist' => [
                'id', 'Users.email','Users.firstname','Users.lastname','Users.company_name','ExhibitionRegistrations.booth_no','ExhibitionRegistrations', 'ExhibitionRegistrations.created','UserLoginRecords.created','UserEmailRecords.created'
            ],
            'order' => [
                'Users.firstname' => 'asc'
            ]
      ],

      'Users' => [
          'limit' => 50,
            'order' => [
                'Users.id' => 'asc'
            ]
      ],

    ];


	public function beforeFilter(\Cake\Event\EventInterface $event){
	    parent::beforeFilter($event);
	    // Configure the login action to not require authentication, preventing
	    // the infinite redirect loop issue
	    $this->Authentication->addUnauthenticatedActions(['login','add']);
        $this->currentSession=$this->getRequest()->getSession();

        $action = $this->request->getParam('action');
        if($this->currentSession->check('user')){
          if (!$this->currentSession->check('user.event.id') && !in_array($action, array('login','logout','addAdmin', 'listClients', 'listAdmins','userOrgAdminStatusAjax'))){
              $this->Flash->error(__('Please select an event'));
              $user_type = $this->currentSession->read('user.type');
              if($user_type=='admin'){
                  return $this->redirect(array('controller' => 'Admin', 'action' => 'selectEvent'));
              }else{
                 return $this->redirect(array('controller' => 'ControlPanel', 'action' => 'selectEvent')); 
              }
            }
        }
	}


public function testEmail(){
	
	 require_once(ROOT . DS . 'vendor' . DS  . 'sesmail.php');
	 
	// SesMail::test_ses_email();
	 SesMail::test_ses_email();
	//SesMail::test();
	 die;
	// Create an SesClient. Change the value of the region parameter if you're 
	// using an AWS Region other than US West (Oregon). Change the value of the
	// profile parameter if you want to use a profile in your credentials file
	// other than the default.
	/* $SesClient = new SesClient([
		 'profile' => 'default',
		 'version' => '2010-12-01',
		 'region'  => 'us-west-2',
		'use_aws_shared_config_files '=> false,
		'credentials' => [
			'key'    => 'AKIAIUXTDW2VGMHVUPEQ',
			'secret' => 'ZD0uEB3NRPOwA/bBlT0WNdYI/JcMU9ikAJnTKdg1',
		],
	]); */
	
	
	
	

	// Replace sender@example.com with your "From" address.
	// This address must be verified with Amazon SES.
	//$sender_email = 'sender@example.com';
	/* $sender_email = 'notifications@xpobay.com';

	// Replace these sample addresses with the addresses of your recipients. If
	// your account is still in the sandbox, these addresses must be verified.
	//$recipient_emails = ['recipient1@example.com','recipient2@example.com'];
	$recipient_emails = ['khemit86@gmail.com'];

	// Specify a configuration set. If you do not want to use a configuration
	// set, comment the following variable, and the
	// 'ConfigurationSetName' => $configuration_set argument below.
	$configuration_set = 'ConfigSet';

	$subject = 'Amazon SES test (AWS SDK for PHP)';
	$plaintext_body = 'This email was sent with Amazon SES using the AWS SDK for PHP.' ;
	$html_body =  '<h1>AWS Amazon Simple Email Service Test Email</h1>'.
				  '<p>This email was sent with <a href="https://aws.amazon.com/ses/">'.
				  'Amazon SES</a> using the <a href="https://aws.amazon.com/sdk-for-php/">'.
				  'AWS SDK for PHP</a>.</p>';
	$char_set = 'UTF-8';

	try {
		$result = $SesClient->sendEmail([
			'Destination' => [
				'ToAddresses' => $recipient_emails,
			],
			'ReplyToAddresses' => [$sender_email],
			'Source' => $sender_email,
			'Message' => [
			  'Body' => [
				  'Html' => [
					  'Charset' => $char_set,
					  'Data' => $html_body,
				  ],
				  'Text' => [
					  'Charset' => $char_set,
					  'Data' => $plaintext_body,
				  ],
			  ],
			  'Subject' => [
				  'Charset' => $char_set,
				  'Data' => $subject,
			  ],
			],
			// If you aren't using a configuration set, comment or delete the
			// following line
			'ConfigurationSetName' => $configuration_set,
		]);
		$messageId = $result['MessageId'];
		echo("Email sent! Message ID: $messageId"."\n");
	} catch (AwsException $e) {
		// output error message if fails
		echo $e->getMessage();
		echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
		echo "\n";
	} */
	die;
  
  }
	

//=============================================================

public function login(){
	$this->viewBuilder()->setLayout('auth');
    $this->currentSession->delete('user');
    $this->currentSession->delete('agent');
    //$this->Authentication->logout();

    $this->request->allowMethod(['get', 'post']);
    $result = $this->Authentication->getResult();
    // regardless of POST or GET, redirect if user is logged in
    if ($result->isValid()){
        $url=@$_GET['redirect'];
        if($url=='/users/logout'){
          $url="";
        }
        $user = $this->Authentication->getIdentity();
        $this->currentSession->write('user.id', $user->id);
        $this->currentSession->write('user.email', $user->email);
        $this->currentSession->write('user.name', $user->firstname . ' ' . $user->lastname);
        $this->currentSession->write('user.firstname', $user->firstname);
        $this->currentSession->write('user.login_by', $user->id);
        $this->currentSession->write('user.lastname', $user->lastname);
        //set the timezone of user
        $this->connection = ConnectionManager::get('default');
        $id =$this->currentSession->read('user.id');
        $query="select timezone FROM users WHERE users.id=".$id."";
        $res = $this->connection->execute($query)->fetch('assoc');;
        if($res['timezone']!=""){
          $this->currentSession->write('user.timezone', $res['timezone']);
        }
        //check user is agent also
          $check_type = TableRegistry::getTableLocator()->get('UserRoles')->find()->where(['UserRoles.user_id'=>$user['id']])->first();
            if($check_type){
              //if has any stand
              $stands=TableRegistry::getTableLocator()->get('AgentStands')->find()->where(['AgentStands.user_id'=>$user['id']])->first();
              if($stands){
                $this->currentSession->write('user.multi_user', 'multiple');
                return $this->redirect(array('controller'=>'Home','action'=>'multi_select_event'));
                //return $this->redirect(array('controller'=>'system','action'=>'choose_type'));
              }
            }
        $this->currentSession->write('user.type', $user->user_type);
        

        if($user->user_type=='client'){
            $this->currentSession->write('user.company_id', $user['event_company_id']);
           //==========save organiser login record============
           $this->saveOrganiserLoginRecord($user); 
           //check the users is reseller or white-label user
            $query = "select is_whitelabel, password FROM users WHERE users.id=".$id."";
            $res = $this->connection->execute($query)->fetch('assoc');
            /*if($res['is_reseller']==1){
              $this->currentSession->write('user.reseller_user', 'reseller_user');
            }elseif($res['is_whitelabel']==1){
              $this->currentSession->write('user.whitelabel_user', 'whitelabel_user');
            }*/
            if(!empty($get_company_details)){
                $get_company_details = "select subdomain,can_add_reseller FROM companies WHERE companies.id=".$user['event_company_id']."";
                $company_details = $this->connection->execute($get_company_details)->fetch('assoc');
            }
            /*if($res['is_whitelabel']==1){
              $this->currentSession->write('user.whitelabel_user', 'whitelabel_user');
            }else*/if($company_details['can_add_reseller']==1){
                if($user['org_admin']==1){
                    $this->currentSession->write('user.reseller_user', 'reseller_user');
                }
            }
            if($company_details['subdomain']!=""){
                $subdomain = $company_details['subdomain'];
                $host=$_SERVER['HTTP_HOST'];
                $host = str_replace($subdomain.'.',"",$host);
                /*if($host=='my.xpobay.com'){
                    $host=str_replace('my.', '', $host);
                }
                if($host=='stage.xpobay.com'){
                    $host=str_replace('stage.','',$host);
                }*/
                //$host = 'xpobay.com';
                if($_SERVER['SERVER_NAME']!='localhost'){
                    $host = 'xpobay.com';
                }
                $param_url= "/ControlPanel/index";
                $url='/system/cookie-login/'.$id."/".base64_encode($res['password'])."/".$subdomain;
                $redirect_url=$subdomain.".".$host.$url;
                $http = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
                $final_url= $http.$redirect_url."?url=".$param_url;
                $this->redirect($final_url);   
              
            }
           return $this->redirect(array('controller' => 'ControlPanel', 'action' => 'selectEvent'));
        }else if($user->user_type == 'exhibitor'){
           return $this->redirect(array('controller' => 'Home', 'action' => 'selectEvent'));
        }elseif($user->user_type == 'agent'){
           return $this->redirect(array('controller' => 'Home', 'action' => 'agentSelectEvent'));
        }elseif($user->user_type == 'user'){

        }elseif($user->user_type == 'vendor'){
           return $this->redirect(array('controller' => 'Vendors', 'action' => 'index'));
        }else{
           //$this->Flash->success('Login success');
           /*$redirect = $this->request->getQuery('redirect', [
                'controller' => 'admin',
                'action' => 'index',
           ]); */

           return $this->redirect(array('controller' => 'Admin', 'action' => "selectEvent?redirect=$url"));
        }
        
    }
    // display error if user submitted and authentication failed (Note: If cake2 user login with valid cred. let em login)
    if ($this->request->is('post') && !$result->isValid()){
        $failed_user= $this->Users->findByEmail($this->request->getData('email'))->first();
        if($failed_user){
            if(strcmp($failed_user['password'], md5($this->request->getData('password'))) == 0){
                $this->upgradePasswordHash($failed_user->id,$this->request->getData('password'));
                $this->autoLogin($failed_user);
            }else{
               $this->Flash->error('Invalid email or password'); 
            }
        }else{
           $this->Flash->error('Invalid email or password');
        }

    }
}

public function autoLogin($user){
    // save password in cake4 format
    $this->Authentication->setIdentity($user);
    $this->currentSession->write('user.id', $user->id);
    $this->currentSession->write('user.email', $user->email);
    $this->currentSession->write('user.name', $user->firstname . ' ' . $user->lastname);
    $this->currentSession->write('user.firstname', $user->firstname);
    $this->currentSession->write('user.lastname', $user->lastname);
    $this->currentSession->write('user.type', $user->user_type);
    $this->currentSession->write('user.login_by', $user->id);
    
    if($user->user_type=='client'){
       $this->saveOrganiserLoginRecord($user); 
       return $this->redirect(array('controller' => 'ControlPanel', 'action' => 'selectEvent'));
    }else if($user->user_type == 'exhibitor'){
       return $this->redirect(array('controller' => 'Home', 'action' => 'selectEvent'));
    }elseif($user->user_type == 'agent'){
       return $this->redirect(array('controller' => 'Home', 'action' => 'agentSelectEvent'));
    }elseif($user->user_type == 'vendor'){
       return $this->redirect(array('controller' => 'Vendors', 'action' => 'index'));
    }
    
}


public function upgradePasswordHash($user_id,$password){
   $data= array();
   $data['password']= $password;
   $entity_data=TableRegistry::getTableLocator()->get('Users')->newEntity($data);
   $entity_data->id= $user_id;
   TableRegistry::getTableLocator()->get('Users')->save($entity_data);
}

public function saveOrganiserLoginRecord($user){
   $rec= array();
   $rec['user_id']= $user['id'];
   $rec['created']= date('Y-m-d H:i:s');
   $entity_data= TableRegistry::getTableLocator()->get('OrganiserLoginRecords')->newEntity($rec);
   TableRegistry::getTableLocator()->get('OrganiserLoginRecords')->save($entity_data);
}

public function logout(){
	$result = $this->Authentication->getResult();
	// regardless of POST or GET, redirect if user is logged in
	if($result->isValid()) {
	    $this->Authentication->logout();
        $subdomain = $this->currentSession->read('user.subdomain');
        // delete all session
        $this->currentSession->delete('user');
        $this->currentSession->delete('agent');
        /*if($subdomain!=""){
            $host=$_SERVER['HTTP_HOST'];
            $host = str_replace($subdomain.'.',"",$host);
            $url="/Users/logout";
            $redirect_url=$host.$url;
            $final_url= 'http://' . $redirect_url;
            $this->redirect($final_url);  
        }*/
	    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	}
}


//=======================================================================


    function salesforce_sync($reg_id){
       TableRegistry::getTableLocator()->get('SalesforceSyncQueues')->sync($reg_id);
    }

    // live date: 22-10-18
    function index(){
     //$this->saveMissingCustomMetaForAllUsers();
      if($this->currentSession->read('user.timezone')){
        $this->setTimeZone($this->currentSession->read('user.timezone'));
      }
        $condition= array();
        $condition['ExhibitionRegistrations.event_id']=$this->currentSession->read('user.event.id');
        $condition['Users.user_type']='exhibitor';

        // search conditions
        $search= array();
        $params= $this->request->getAttribute('params')['pass'];
        if(count($params) > 0){
           foreach($params as $param){
               $param_arr= explode(":", $param);
               $search_key= $param_arr[0];
               $search_value= $param_arr[1];

               $search[$search_key]= $search_value;

               if($search_key=='search_email'){
                  $tmp = trim($search_value);
                  $condition['AND'][] = "Users.email LIKE '%".$tmp."%'";

                }

               if($search_key=='search_fname'){
                    $tmp = trim($search_value);
                    $condition['AND'][] = "Users.firstname LIKE '%$tmp%'";
                }

                if($search_key=='search_lname'){
                    $tmp = trim($search_value);
                    $condition['AND'][] = "Users.lastname LIKE '%$tmp%'";
                }
                if($search_key=='search_company'){
                    $tmp = trim($search_value);
                    $condition['AND'][] = "Users.company_name LIKE '%$tmp%'";
                }
                if($search_key=='search_standno'){
                    $tmp = trim($search_value);
                    $condition['AND'][] = "ExhibitionRegistrations.booth_no LIKE '%$tmp%'";
                }
                if($search_key=='search_standname'){
                    $tmp = trim($search_value);
                    $condition['AND'][] = "ExhibitionRegistrations.booth_name LIKE '%$tmp%'";
                }
                 if($search_key=='search_uid'){
                    $tmp = trim($search_value);
                    $condition['AND'][] = "ExhibitionRegistrations.uni_id LIKE '%$tmp%'";
                }

                /*if($search_key=='search_standtype'){
                    $tmp = trim($search_value);
                    $condition['AND'][] = "EventBoothTypes.name LIKE '%$tmp%'";
                }*/
                if($search_key=='search_standtype'){
                    $and_or_ary=array();
                    $tmp = trim($search_value);
                    //$condition['AND'][] = "EventBoothTypes.name LIKE '%$tmp%'";
                    $booth_type_ids = TableRegistry::getTableLocator()->get('EventBoothTypes')->find('list', ['keyField'=>'id','valueField'=>'id'])->where(['event_id'=>$this->currentSession->read('user.event.id'),'EventBoothTypes.name LIKE'=>"%".$tmp."%"])->toArray();
                    if(count($booth_type_ids)>0){
                        foreach($booth_type_ids as $booth_type_id){
                            $pattren = '%'.$booth_type_id.'%';
                            $and_or_ary[] = array('ExhibitionRegistrations.booth_type_id LIKE' => $pattren);
                        }
                    }else{
                        $and_or_ary[] = array('ExhibitionRegistrations.event_exhibitor_types LIKE' => $tmp);
                    }
                    $condition['AND'][]['OR'] = $and_or_ary;
                }
                if($search_key=='search_exhibtype'){
                    $and_or_ary=array();
                    $tmp = trim($search_value);
                    $exhib_type_ids = TableRegistry::getTableLocator()->get('EventExhibitorTypes')->find('list', ['keyField'=>'id','valueField'=>'id'])->where(['event_id'=>$this->currentSession->read('user.event.id'),'EventExhibitorTypes.name LIKE'=>"%".$tmp."%"])->toArray();
                    if(count($exhib_type_ids)>0){
                        foreach($exhib_type_ids as $exhib_type_id){
                            $pattren = '%'.$exhib_type_id.'%';
                            $and_or_ary[] = array('ExhibitionRegistrations.event_exhibitor_types LIKE' => $pattren);
                        }
                    }else{
                        $and_or_ary[] = array('ExhibitionRegistrations.event_exhibitor_types LIKE' => $tmp);
                    }
                    $condition['AND'][]['OR'] = $and_or_ary;
                }

                if($search_key=='search_profileupdated'){
                    $tmp = trim($search_value);
                    $condition['AND'][] = "Users.updated LIKE '%$tmp%'";
                }

                if($search_key=='search_regupdated'){
                    $tmp = trim($search_value);
                    $condition['AND'][] = "ExhibitionRegistrations.updated LIKE '%$tmp%'";
                }

                if($search_key=='search_regcreated'){
                    $tmp = trim($search_value);
                    $data = explode('-', $tmp);
                        $data_len = count($data);
                        if($data_len==2 || $data[$data_len-1]=="" || $data[0]==""){
                          $data_rev = array_reverse($data);
                         $tmp = implode('-', $data_rev);
                         $condition['AND'][] = "ExhibitionRegistrations.created LIKE '%$tmp%'";
                       }else{
                    $tmp = date('Y-m-d', strtotime($tmp));
                    $condition['AND'][] = "ExhibitionRegistrations.created LIKE '%$tmp%'";
                  }
                }

                if($search_key=='search_lastlogin'){
                    $tmp = trim(strtolower($search_value));
                     /*if($tmp=='no record'){*/
                     if(strstr('no record', $tmp )){
                         $condition['AND'][] = "NOT EXISTS  (select user_id from exhibition_registrations where `UserLoginRecords`.`user_id`= `exhibition_registrations`.`user_id`)";
                     } else {
                      $data = explode('-', $tmp);
                        $data_len = count($data);
                        if($data_len==2 || $data[$data_len-1]=="" || $data[0]==""){
                          $data_rev = array_reverse($data);
                         $tmp = implode('-', $data_rev);
                         $condition['AND'][] = "UserLoginRecords.created LIKE '%$tmp%'";
                       }else{
                        $tmp = date('Y-m-d', strtotime($tmp));
                        $condition['AND'][] = "UserLoginRecords.created LIKE '%$tmp%'";
                      }
                    }
                }

                if($search_key=='search_lastwelcome'){
                    $tmp = trim(strtolower($search_value));
                    /*if($tmp=='no record'){*/
                    if(strstr('no record', $tmp )){
                        $condition['AND'][] = "NOT EXISTS  (select user_id from exhibition_registrations where `UserEmailRecords`.`user_id`= `exhibition_registrations`.`user_id`)";
                    } else {
                        $data = explode('-', $tmp);
                        $data_len = count($data);
                        if($data_len==2 || $data[$data_len-1]=="" || $data[0]==""){
                          $data_rev = array_reverse($data);
                         $tmp = implode('-', $data_rev);
                         $condition['AND'][] = "UserEmailRecords.created LIKE '%$tmp%'";
                        }else{
                        $tmp = date('Y-m-d', strtotime($tmp));
                        $condition['AND'][] = "UserEmailRecords.created LIKE '%$tmp%'";
                      }
                    }
                }

                // ===== search filter on custom fields ======
                $custom_arr= explode("_", $param_arr[0]);
                if(isset($custom_arr[1]) && $custom_arr[1]=='customfield'){
                    $custom_field_key= $custom_arr[2];

                     $reg_ids = TableRegistry::getTableLocator()->get('RegistrationMetas')->find('list', ['keyField'=>'id','valueField'=>'reg_id'])->where(['event_id'=>$this->currentSession->read('user.event.id'),'field_key'=>str_replace("-", " ", $custom_field_key),'value LIKE'=>"%".$search_value."%"])->toArray();

                    $reg_ids_arr= array();
                    foreach($reg_ids as $reg_row){
                        $reg_ids_arr[] = $reg_row;
                    }

                    $reg_ids_arr= array_unique($reg_ids_arr);
                    $condition['ExhibitionRegistrations.id IN']= $reg_ids_arr;
                  
                }

           }
        }

        

        $query = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($condition)->contain(['Users','Events','EventDimensions','EventLocations','UserEmailRecords','UserLoginRecords','RegistrationMetas']);
        if(count($params) > 0){
          $limit = 10;
             foreach($params as $param){
               $param_arr= explode(":", $param);
               $search_key= $param_arr[0];
               $search_value= $param_arr[1];
               if(str_contains($search_value,'&')){
                  $str_arr=explode('&', $search_value);
                  $search_value=$str_arr[0];
                  $order = $param_arr[2];
               }
               $search[$search_key]= $search_value;
               if($search_key=='search_regcreated' || $search_key=='search_lastlogin' || $search_key=='search_lastwelcome'){
                  $search[$search_key]= str_replace('-', '/', $search_value);
               }
               
                if($search_key=='set_limit'){
                    $tmp = $search_value;
                    if($tmp!=''){
                      $limit = $tmp;
                        $this->paginate = ['limit'=>$tmp];
                    } else {
                       $this->paginate = ['limit'=>10];
                    }
                }
                if($search_key=='sort_by'){
                    $sort_value = $search_value;
                    if($sort_value!=''){
                        $this->paginate = ['ExhibitionRegistrations' => [
                        'limit' => $limit,
                        'sortWhitelist' => [
                              'id', 'Users.email','Users.firstname','Users.lastname','Users.company_name','ExhibitionRegistrations.booth_no','ExhibitionRegistrations.booth_name','ExhibitionRegistrations.uni_id', 'ExhibitionRegistrations.created','UserLoginRecords.created','UserEmailRecords.created'
                          ],
                          'order' => [
                              "$sort_value" => $order
                          ]
                      ] ];                    
                    } else {
                       $this->paginate = ['ExhibitionRegistrations' => [
                        'limit' => 10,
                        'sortWhitelist' => [
                              'id', 'Users.email','Users.firstname','Users.lastname','Users.company_name','ExhibitionRegistrations.booth_no','ExhibitionRegistrations.booth_name','ExhibitionRegistrations.uni_id', 'ExhibitionRegistrations.created','UserLoginRecords.created','UserEmailRecords.created'
                          ],
                          'order' => [
                              'Users.firstname' => 'asc'
                          ]
                      ] ];
                    }
                }
               

           }

        }
        $users_data=$this->paginate($query);
        //get exhibitor_type of  users
        foreach ($users_data as $user){ 
            $mytypes = $user['event_exhibitor_types'];
            $mytypes = trim($mytypes, '[');
            $mytypes = trim($mytypes, ']');
            $mytypes = explode('][', $mytypes);
             
            $extypes=TableRegistry::getTableLocator()->get('EventExhibitorTypes')->getExhibitorType($mytypes);
             
            $extypestr='';
            foreach($extypes as $type){
                $extypestr.= $type['name']." | ";
            }
            $user['exhibitor_type']=$extypestr;
          }
          foreach ($users_data as $user){ 
            $boothtypes = $user['booth_type_id'];
            $boothtypes = trim($boothtypes, '[');
            $boothtypes = trim($boothtypes, ']');
            $boothtypes = explode('][', $boothtypes);
             
            $bthtypes=TableRegistry::getTableLocator()->get('EventBoothTypes')->find()->where(['EventBoothTypes.id IN' => $boothtypes]);
             
            $boothtypestr='';
            foreach($bthtypes as $type){
                $boothtypestr.= $type['name']." | ";
            }
            $user['event_booth_type']=$boothtypestr;
          }
          
          $users=array();
          $i=0;
          foreach ($users_data as $user) {
            $users[$i]=$user;
            $i++;
          }
          //sort by exhibitor_type
          foreach($params as $param){
            $param_arr= explode(":", $param);
            $search_key= $param_arr[0];
            $search_value= $param_arr[1];
            if(str_contains($search_value,'&')){
                $str_arr=explode('&', $search_value);
                $search_value=$str_arr[0];
                $order = $param_arr[2];
            }
            $search[$search_key]= $search_value;
            if($search_key=='search_regcreated' || $search_key=='search_lastlogin' || $search_key=='search_lastwelcome'){
                $search[$search_key]= str_replace('-', '/', $search_value);
            }
             
              if($search_key=='sort_by' && $search_value=='exhibtype'){
                  if($order=='asc'){
                    array_multisort(array_column($users, 'exhibitor_type'), SORT_ASC, $users);
                  }else{
                    array_multisort(array_column($users, 'exhibitor_type'), SORT_DESC, $users);
                  }
              }
              if($search_key=='sort_by' && $search_value=='booth_type_id'){
                  if($order=='asc'){
                    array_multisort(array_column($users, 'event_booth_type'), SORT_ASC, $users);
                  }else{
                    array_multisort(array_column($users, 'event_booth_type'), SORT_DESC, $users);
                  }
              }
          }
          

        $this->set('users',$users);
        $this->set('search',$search);
        //echo "<pre>"; print_r($search); exit;

        // ===== get custom field =======
        $event_id= $this->currentSession->read('user.event.id');
        $custom_datas = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.event_id' => $event_id])->order(['RegistrationMetas.meta_order' => 'ASC']);
        
        // unique custom fields
        $temp_arr= array();
        $custom_fields= array();
        foreach($custom_datas as $custom){
            if(!in_array($custom['field_key'], $temp_arr)){
               $custom_fields[]= $custom;
            }

            $temp_arr[]=$custom['field_key'];
        }

        $this->set('custom_fields',$custom_fields);

        //echo "<pre>"; print_r($custom_fields); exit;

        $session_data= $this->currentSession->read('user');
        $session_user=TableRegistry::getTableLocator()->get('Users')->find()->where(['Users.id' => $session_data['id']])->first();
        $this->set('session_user', $session_user);
        
        // get email header
        $email_headers=TableRegistry::getTableLocator()->get('EmailHeaderLists')->find()->where(['EmailHeaderLists.event_id'=>$this->currentSession->read('user.event.id')]);
        $this->set('email_headers', $email_headers);
        
        $templates= TableRegistry::getTableLocator()->get('CustomTemplates')->find()->where(['CustomTemplates.event_id'=>$this->currentSession->read('user.event.id')]);
        $this->set('templates', $templates);
        //echo "<pre>"; print_r($templates); exit;

        $event= TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id'=>$event_id])->first();
        $reminder= TableRegistry::getTableLocator()->get('Reminders')->find()->where(['Reminders.event_id'=>$event_id])->first();

        $subjects= array();
        $subjects['Event Welcome'] = $event['email_subject'];
        $subjects['Event Reminder'] = (isset($reminder['subject'])) ? $reminder['subject'] : $event['name'].": Compulsory forms reminder";

        foreach($templates as $template){
            $subjects[$template['id']] = $template['subject'];
        }

        $this->set('subjects',json_encode($subjects));
        $this->set('is_unique_identify',$event['unique_identify']);

    }

    function saveformnameajax(){
      $this->autoRender=false;
      $this->autoLayout=false;
      $this->layout='ajax';

      $save_arr['from_name'] = $this->request->getData('val');
      $save_arr['event_id'] = $this->currentSession->read('user.event.id');
      $entity_data=TableRegistry::getTableLocator()->get('EmailHeaderLists')->newEntity($save_arr);
      TableRegistry::getTableLocator()->get('EmailHeaderLists')->save($entity_data);
      echo "insert";
      exit;
    }
	
    function saveformemailajax(){
      $this->autoRender=false;
      $this->autoLayout=false;
      $this->layout='ajax';

      $save_arr['reply_email'] = $this->request->getData('val');
      $save_arr['event_id'] = $this->currentSession->read('user.event.id');
      $entity_data=TableRegistry::getTableLocator()->get('EmailHeaderLists')->newEntity($save_arr);
      TableRegistry::getTableLocator()->get('EmailHeaderLists')->save($entity_data);
      echo "insert";
      exit;
    }


    function indexTest(){
        $condition= array();
        $condition['ExhibitionRegistrations.event_id']=$this->currentSession->read('user.event.id');
        $condition['Users.user_type']='exhibitor';

        $query = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($condition)->contain(['Users']);

        $users=$this->paginate($query);
        $this->set('users',$users);
        

        // ===== get custom field =======
        $event_id= $this->currentSession->read('user.event.id');
        $custom_datas = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.event_id' => $event_id])->order(['RegistrationMetas.meta_order' => 'ASC']);
        
        // unique custom fields
        $temp_arr= array();
        $custom_fields= array();
        foreach($custom_datas as $custom){
            if(!in_array($custom['field_key'], $temp_arr)){
               $custom_fields[]= $custom;
            }

            $temp_arr[]=$custom['field_key'];
        }

        $this->set('custom_fields',$custom_fields);

        //echo "<pre>"; print_r($custom_fields); exit;

        $session_data= $this->currentSession->read('user');
        $session_user=TableRegistry::getTableLocator()->get('Users')->find()->where(['Users.id' => $session_data['id']])->first();
        $this->set('session_user', $session_user);
        
        // get email header
        $email_headers=TableRegistry::getTableLocator()->get('EmailHeaderLists')->find()->where(['EmailHeaderLists.event_id'=>$this->currentSession->read('user.event.id')]);
        $this->set('email_headers', $email_headers);
        
        $templates= TableRegistry::getTableLocator()->get('CustomTemplates')->find()->where(['CustomTemplates.event_id'=>$this->currentSession->read('user.event.id')]);
        $this->set('templates', $templates);
        //echo "<pre>"; print_r($templates); exit;

        $event= TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id'=>$event_id])->first();
        $reminder= TableRegistry::getTableLocator()->get('Reminders')->find()->where(['Reminders.event_id'=>$event_id])->first();

        $subjects= array();
        $subjects['Event Welcome'] = $event['email_subject'];
        $subjects['Event Reminder'] = (isset($reminder['subject'])) ? $reminder['subject'] : $event['name'].": Compulsory forms reminder";

        foreach($templates as $template){
            $subjects[$template['id']] = $template['subject'];
        }

        $this->set('subjects',json_encode($subjects));

    }


    //List all exhibitor
    function index_old_22_10_18(){
        
        $this->User->recursive = 0;
        $condition = array(
            'AND' => array(
                'User.user_type' => 'exhibitor',
                'ExhibitionRegistration.event_id' => $this->Session->read('user.event.id')),
        );

        if (count($this->params['named']) > 0) {
            //echo "<pre>"; print_r($this->params['named']); exit;
            if (isset($this->params['named']['search_email']) &&
                    strlen(trim($this->params['named']['search_email'])) > 0) {
                $tmp = trim($this->params['named']['search_email']);
                $condition['AND'][] = "User.email LIKE '%".$tmp."%'";
            }
            if (isset($this->params['named']['search_fname']) &&
                    strlen(trim($this->params['named']['search_fname'])) > 0) {
                $tmp = trim($this->params['named']['search_fname']);
                $condition['AND'][] = "User.firstname LIKE '%$tmp%'";
            }
            if (isset($this->params['named']['search_lname']) &&
                    strlen(trim($this->params['named']['search_lname'])) > 0) {
                $tmp = trim($this->params['named']['search_lname']);
                $condition['AND'][] = "User.lastname LIKE '%$tmp%'";
            }
            if (isset($this->params['named']['search_company']) &&
                    strlen(trim($this->params['named']['search_company'])) > 0) {
                $tmp = trim($this->params['named']['search_company']);
                $condition['AND'][] = "User.company_name LIKE '%$tmp%'";
            }
            if (isset($this->params['named']['search_standno']) &&
                    strlen(trim($this->params['named']['search_standno'])) > 0) {
                $tmp = trim($this->params['named']['search_standno']);
                $condition['AND'][] = "ExhibitionRegistration.booth_no LIKE '%$tmp%'";
            }
            if (isset($this->params['named']['search_standname']) &&
                    strlen(trim($this->params['named']['search_standname'])) > 0) {
                $tmp = trim($this->params['named']['search_standname']);
                $condition['AND'][] = "ExhibitionRegistration.booth_name LIKE '%$tmp%'";
            }

            if (isset($this->params['named']['search_standtype']) &&
                    strlen(trim($this->params['named']['search_standtype'])) > 0) {
                $tmp = trim($this->params['named']['search_standtype']);
                $condition['AND'][] = "EventBoothType.name LIKE '%$tmp%'";
            }

            if (isset($this->params['named']['search_profileupdated']) &&
                    strlen(trim($this->params['named']['search_profileupdated'])) > 0) {
                $tmp = trim($this->params['named']['search_profileupdated']);
                $condition['AND'][] = "User.updated LIKE '%$tmp%'";
            }
            if (isset($this->params['named']['search_regupdated']) &&
                    strlen(trim($this->params['named']['search_regupdated'])) > 0) {
                $tmp = trim($this->params['named']['search_regupdated']);
                $condition['AND'][] = "ExhibitionRegistration.updated LIKE '%$tmp%'";
            }
            if (isset($this->params['named']['search_regcreated']) &&
                    strlen(trim($this->params['named']['search_regcreated'])) > 0) {
                $tmp = trim($this->params['named']['search_regcreated']);
                $condition['AND'][] = "ExhibitionRegistration.created LIKE '%$tmp%'";
            }
        }

        $joins = array(
            array('table' => 'exhibition_registrations',
                'alias' => 'ExhibitionRegistration',
                'type' => 'LEFT',
                'foreignKey' => false,
                'conditions' => array('User.id = ExhibitionRegistration.user_id')),
            array('table' => 'user_login_records',
                'alias' => 'UserLogin',
                'type' => 'LEFT',
                'foreignKey' => false,
                'conditions' => array('UserLogin.reg_id = ExhibitionRegistration.id'),
                'order' => array('UserLogin.id' => 'DESC')
            ),
            array('table' => 'event_booth_types',
                'alias' => 'EventBoothType',
                'type' => 'LEFT',
                'foreignKey' => false,
                'conditions' => array('EventBoothType.id = ExhibitionRegistration.  booth_type_id')
                
            )/*,
            array('table' => 'user_email_records',
                'alias' => 'UserEmail',
                'type' => 'LEFT',
                'foreignKey' => false,
                'conditions' => array('UserEmail.reg_id = ExhibitionRegistration.id'),
                'order' => array('UserEmail.id' => 'DESC')
                )
            */
            );
        /* $joinss = array(
          array('table' => 'user_login_records',
          'alias' => 'UserLogin',
          'type' => 'LEFT',
          'foreignKey' => false,
          'conditions' => array( 'UserLogin.id = ExhibitionRegistration.id'))); */
        $this->paginate = array(
            'fields' => array('User.*', 'ExhibitionRegistration.*', 'UserLogin.*','EventBoothType.*'),//, 'UserEmail.*'
            'conditions' => $condition,
            'joins' => $joins,
            'limit' => 10,
            'order' => array('User.firstname' => "ASC")
        );
        $u = $this->paginate();
        //echo "<pre>"; print_r($u);exit;
        $this->set('users', $this->paginate());
        //start <<< change by jinsiu wang
        $this->set('user_email_record', $this->UserEmailRecord->get_datas_last_sent());
        
        // ==================================
        /*
        $users_last_login_time = $this->UserLastLogin->get_user_last_login_date();
        $this->set('users_last_login_time', $users_last_login_time); */
        //end <<< change by jinsiu wang
        //===================================
        
        $this->UserLoginRecord->recursive=-1;
        $login_rec = $this->UserLoginRecord->find('list', array('fields'=>array('UserLoginRecord.user_id','UserLoginRecord.created'),'conditions' => array('UserLoginRecord.event_id'=>$this->Session->read('user.event.id'))));
        $this->set('users_last_login_time', $login_rec);

        //echo "<pre>"; print_r($login_rec); exit;


    }


    public function saveDisplayCustomFieldAjax(){
       $this->autoRender=false;
       $this->autoLayout=false;
       $this->layout='ajax';
       
       
       $custom_fields =$this->request->getData('custom_fields');
       
       $event_id= $this->currentSession->read('user.event.id');
       // first update with 0
       $conditions= array();
       $conditions['RegistrationMetas.event_id']= $event_id;
       TableRegistry::getTableLocator()->get('RegistrationMetas')->updateAll(['field_display' => '0'],$conditions);
       
       foreach($custom_fields as $field_key){
           //get meta key first
           $condi= array();
           $condi['RegistrationMetas.event_id']= $event_id;
           $condi['RegistrationMetas.field_key']= $field_key;
           TableRegistry::getTableLocator()->get('RegistrationMetas')->updateAll(['field_display' => '1'],$condi);
       }

       echo "saved";

       die();
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid user', true));
            return $this->redirect(array('action' => 'index'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    function add() {
        if (!empty($this->request->data)) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                //$this->Session->write('flash', array('The user has been saved', 'success'));
                $this->Session->setFlash('The user has been saved', 'success');
                //$this->Session->setFlash(__('The user has been saved', true));
                return $this->redirect(array('action' => 'index'));
            } else {
                //$this->Session->write('flash', array('The user could not be saved. Please, try again.', 'failure'));
                $this->Session->setFlash('The user could not be saved. Please, try again.', 'failure');

            }
        }
    }

   

    function addClient() {
		
		
        if($this->request->is(['post', 'put']) && !empty($this->request->getData('events'))){    
            
            $userdata= $this->request->getData();
			
            $userdata['contact_tel'] = $userdata['contact_tel_areacode'] . "-" . $userdata['contact_tel_num'];
            $userdata['contact_fax'] = $userdata['contact_fax_areacode'] . "-" . $userdata['contact_fax_num'];
            $userdata['company_addr_country'] = strtoupper($userdata['company_addr_country']);
            $userdata['email'] = trim($userdata['email']);

            $reg_event_ids = $userdata['events'];
            unset($userdata['events']);
            
            $current_user = $this->Users->findByEmail($userdata['email'])->first();
            
            if ($current_user == null) {
                $new_password = $this->Users->generate_password(8);
                $userdata['password'] = $new_password;
				
				
                $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($userdata);
               // if(TableRegistry::getTableLocator()->get('Users')->save($entity_data)){
                if(TableRegistry::getTableLocator()->get('Users')->save($entity_data)){
                    $uid = $entity_data->id;
                    $user = $this->Users->findById($uid)->first();
                    //require_once(ROOT . DS . 'vendor' . DS  . 'entmail.php');
                    require_once(ROOT . DS . 'vendor' . DS  . 'sesmail.php');
                    $mail = array('subject' => 'Your XPOBAY Password',
                        'mails' => array(
                            array('email' => $user['email'],
                                'firstname' => $user['firstname'],
                                'password' => $new_password)
                        )
                    );
					//$mail['sender_address'] = $event['event_email_address'];
                    //EntMail::sendMail($mail, 'password');
					
					############ Company White Labelled code start here #####
					/* $company= TableRegistry::getTableLocator()->get('Companies')->find()->select(['company_white_labelled_email','company_white_labelled_email_verification_status','company_white_labelled_email_dkim_verification_status'])->where(['Companies.id'=>$this->request->getData('event_company_id')])->first();
					if(!empty($company) && !empty($company['company_white_labelled_email']) && $company['company_white_labelled_email_verification_status'] == '1' && $company['company_white_labelled_email_dkim_verification_status'] == '1'){
						 $mail['sender_email'] = $company['company_white_labelled_email'];
					} */
					############ Company White Labelled code end here #####
                    //SesMail::sendMail($mail, 'password');

                    foreach ($reg_event_ids as $each_event_id){
                        $reg_record = array();
                        $reg_record['event_id'] = $each_event_id;
                        $reg_record['user_id'] = $uid;
                        $reg_record['status'] = 'client';

                        $entity_data=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($reg_record);
                        TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data);
                    }
                    
                    $this->Flash->success(__('The user has been saved.'));
                    return $this->redirect(array('action' => 'listClients'));
                } else {
                    $this->Flash->success(__('The user could not be saved. Please, try again.'));
                }
            } else { //exisitng user  
                $user_type= $current_user['user_type'];
                $this->Flash->error(__('User already exist as : '.$user_type));
            }
        }elseif (!empty($this->request->getData()) && empty($this->request->getData('events'))){
            $this->Flash->error(__('Please select at least one event'));
        }

        $events = TableRegistry::getTableLocator()->get('Events')->find('list', ['keyField'=>'id','valueField'=>'name'
        ])->toArray();
        $this->set(compact('events'));

        $companies=TableRegistry::getTableLocator()->get('Companies')->find();
        $this->set('companies', $companies);
    }

    function getEventByCompanyAjax(){
        $this->autoRender=false;
        $this->autoLayout=false;
        $this->layout='ajax';

        $company_id= $this->request->getData('company_id');
        
        $events = TableRegistry::getTableLocator()->get('Events')->find('list',['keyField'=>'id','valueField'=>'name'
        ])->where(['Events.company_id'=>$company_id])->toArray();
        
        if(!empty($events)){
           echo $this->checkboxMultiple('Events', "events", $events);
        }else{
           echo "<span style='color:red'>Event not found</span>"; 
        }
        
       
        die();
    }


    function checkboxMultiple($display_field, $fieldName, $options, $selected = '', $required = false){
        if(!is_array($selected)){
            $selected = trim($selected, '[');
            $selected = trim($selected, ']'); 
            $selected = explode('][', $selected);
        }

        $check_str = '';
        $outstr = '';
        foreach($options as $key=>$value){
                $checked = '';
                if(in_array($key, $selected)){ $checked = 'checked="true"'; }
                
                $check_str .= '<div class="col-md-12 checkbox-inline">
                                <label class="checkbox">
                                  <input type="checkbox" name="'.$fieldName.'[]" value="'.$key.'" '.$checked.'> <span></span> '.$value.'
                                </label>
                              </div>';
                            
        }

        $outstr .= '<div class="row">'.$check_str.'</div>';
        return $outstr;
    }


    function addStandTypeAjax(){
        $this->autoRender=false;
        $this->autoLayout=false;
        $this->layout='ajax';

        $data= $this->request->getData();
        // check stand already exist
        $isStandType=TableRegistry::getTableLocator()->get('EventBoothTypes')->find()->where(['EventBoothTypes.name'=>$data['stand_type'],'EventBoothTypes.event_id'=>$this->currentSession->read('user.event.id')])->first();

        if(!empty($isStandType)){
           echo "exist";
        }else{
           $savedata= array();
           $savedata['name']= $data['stand_type'];
           $savedata['event_id']= $this->currentSession->read('user.event.id');
           $savedata['created']= date('Y-m-d H:i:s');
           $savedata['updated']= date('Y-m-d H:i:s');

           $entity_data= TableRegistry::getTableLocator()->get('EventBoothTypes')->newEntity($savedata);

           if(TableRegistry::getTableLocator()->get('EventBoothTypes')->save($entity_data)){
              echo trim($entity_data->id);
           }
        }

        die();

    }

    function addDimensionAjax(){
        $this->autoRender=false;
        $this->autoLayout=false;
        $this->layout='ajax';

        $data= $this->request->getData();
        // check stand already exist
        $isEventDimension=TableRegistry::getTableLocator()->get('EventDimensions')->find()->where(['EventDimensions.name'=>$data['dimension'],'EventDimensions.event_id'=>$this->currentSession->read('user.event.id')])->first();

        if(!empty($isEventDimension)){
           echo "exist";
        }else{
           $savedata= array();
           $savedata['name']= $data['dimension'];
           $savedata['event_id']= $this->currentSession->read('user.event.id');
           $savedata['created']= date('Y-m-d H:i:s');
           $savedata['updated']= date('Y-m-d H:i:s');

           $entity_data=TableRegistry::getTableLocator()->get('EventDimensions')->newEntity($savedata);
           if(TableRegistry::getTableLocator()->get('EventDimensions')->save($entity_data)){
              echo trim($entity_data->id);
           }
        }

        die();

    }

    function addLocationAjax(){
        $this->autoRender=false;
        $this->autoLayout=false;
        $this->layout='ajax';

        $data= $this->request->getData();
        // check stand already exist
        $isEventLocation=TableRegistry::getTableLocator()->get('EventLocations')->find()->where(['EventLocations.name'=>$data['location'],'EventLocations.event_id'=>$this->currentSession->read('user.event.id')])->first();

        if(!empty($isEventLocation)){
           echo "exist";
        }else{
           $savedata= array();
           $savedata['name']= $data['location'];
           $savedata['event_id']= $this->currentSession->read('user.event.id');
           $savedata['created']= date('Y-m-d H:i:s');
           $savedata['updated']= date('Y-m-d H:i:s');

           $entity_data=TableRegistry::getTableLocator()->get('EventLocations')->newEntity($savedata);
           if(TableRegistry::getTableLocator()->get('EventLocations')->save($entity_data)){
              echo trim($entity_data->id);
           }
        }

        die();

    }
    
    function addExhibitorTypeAjax(){
        $this->autoRender=false;
        $this->autoLayout=false;
        $this->layout='ajax';

        $data= $this->request->getData();
        // check if exhibitor type already exist
        $isExhibitorType= TableRegistry::getTableLocator()->get('EventExhibitorTypes')->find()->where(['EventExhibitorTypes.name'=>$data['exhibitor_type'],'EventExhibitorTypes.event_id'=>$this->currentSession->read('user.event.id')])->first();

        if(!empty($isExhibitorType)){
           echo "exist";
        }else{
           $savedata= array();
           $savedata['name']= $data['exhibitor_type'];
           $savedata['event_id']= $this->currentSession->read('user.event.id');
           $savedata['created']= date('Y-m-d H:i:s');
           $savedata['updated']= date('Y-m-d H:i:s');

           $entity_data= TableRegistry::getTableLocator()->get('EventExhibitorTypes')->newEntity($savedata);

           if(TableRegistry::getTableLocator()->get('EventExhibitorTypes')->save($entity_data)){
               echo trim($entity_data->id);
           }
        }

        die();

    }

    function editClient($id = null) {
        if($this->request->is(['post', 'put']) && !empty($this->request->getData('events'))){
            $userdata= $this->request->getData();
			if(isset($userdata['is_allow_access_api']) && $userdata['is_allow_access_api'] == '1'){
				$userdata['is_allow_access_api'] = '1';
			}else{
				$userdata['is_allow_access_api'] = '0';
			}	
				
            $company_event = TableRegistry::getTableLocator()->get('Companies')->getCompanyByID($userdata['event_company_id']);
            $userdata['company_name'] = $company_event['company_name'];
            $userdata['contact_tel'] = $userdata['contact_tel_areacode'] . "-" . $userdata['contact_tel_num'];
            $userdata['contact_fax'] = $userdata['contact_fax_areacode'] . "-" . $userdata['contact_fax_num'];
            $userdata['company_addr_country'] = strtoupper($userdata['company_addr_country']);
            
            $reg_event_ids = $userdata['events'];
            unset($userdata['events']);

            $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($userdata);
            $entity_data->id= $id;
            //echo "<pre>"; print_r($entity_data); exit;
            if (TableRegistry::getTableLocator()->get('Users')->save($entity_data)){
                $uid = $id;

                $regCurrentEventList = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.user_id' => $uid]);

                $currentEventList = array();
                foreach ($regCurrentEventList as $each) {
                    $currentEventList[] = $each['event_id'];
                }

                foreach ($reg_event_ids as $each_event_id) {
                    if (!in_array($each_event_id, $currentEventList)) { //if not have this entry, then add it
                        $reg_record = array();
                        $reg_record['event_id'] = $each_event_id;
                        $reg_record['user_id'] = $uid;
                        $reg_record['status'] = 'client';

                        $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($reg_record);
                        TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data);
                    }
                }

                // delete extra event of organiser
                $toBeDelEvents=array_diff($currentEventList,$reg_event_ids);
                foreach($toBeDelEvents as $evntid){
                    TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->deleteAll(['ExhibitionRegistrations.event_id'=>$evntid,'ExhibitionRegistrations.user_id'=>$uid]);
                }
                
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(array('action' => 'listClients'));
            }else{
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        } elseif (!empty($this->request->getData()) && empty($this->request->getData('events'))){
            $this->Flash->error(__('Please select at least one event'));
        }

        $client = $this->Users->findById($id)->first();

        if($client['user_type'] != "client"){
            $this->Flash->error(__('Invalid Client'));
            $this->redirect(array('action' => 'list_clients'));
            return;
        }

        $regs = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->findAllByUserId($id);

        $client['events'] = array();
        foreach ($regs as $each) {
            $client['events'][] = $each['event_id'];
        }

        $tmp = explode('-', $client['contact_tel']);
        $client['contact_tel_areacode'] = $tmp[0];
        $client['contact_tel_num'] = $tmp[1];
        
        $tmp = explode('-', $client['contact_fax']);
        $client['contact_fax_areacode'] = $tmp[0];
        $client['contact_fax_num'] = $tmp[1];

        $this->set('client',$client);
        
        $company_id=$client['event_company_id'];
        $events= array();
        if($company_id){
            $events = TableRegistry::getTableLocator()->get('Events')->find('list', ['keyField'=>'id','valueField'=>'name'])->where(['Events.company_id'=>$company_id])->toArray();
        }
        
        $this->set(compact('events')); 
        
        $companies=TableRegistry::getTableLocator()->get('Companies')->find();
        $this->set('companies', $companies);
    }

    function deleteClient($id = null) {
        if(!$id) {
            $this->Flash->error(__('Invalid id for user'));
            return $this->redirect(array('action' => 'listClients'));
        }

        $user = $this->Users->findById($id)->first();
        if ($user['user_type'] != 'client'){
            $this->Flash->error(__('Invalid id for user'));
            return $this->redirect(array('action' => 'listClients'));
        }

        //remove all event relations
        $regs = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->findAllByUserId($id)->toArray();
        foreach($regs as $each){
            TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->deleteAll(['id'=>$each['id']]);
        }

        if(TableRegistry::getTableLocator()->get('Users')->deleteAll(['id'=>$id])){
            $this->Flash->success(__('User deleted'));
            return $this->redirect(array('action' => 'listClients'));
        }else{
           $this->Flash->error(__('User was not deleted'));
           return $this->redirect(array('action' => 'listClients')); 
        }
        
    }

    function listClients() {
        $condition = array('Users.user_type' =>'client');
        /*$condition[] =  array(
            'AND' => array(
                array('Users.is_reseller !=' => 1),
                array('Users.is_whitelabel !=' => 1),
            )
        );*/
        $condition[] = array('Users.is_whitelabel !=' => 1);
        $search= array();
        $params= $this->request->getAttribute('params')['pass'];
        /*if($this->request->is('post')){
           //$this->request->params['named']['page']=1;
           if($this->request->getData() !=''){
              $data= $this->request->getData();
              if(!empty($data['key'])){
                 $key= $data['key'];
                 $key=str_replace("'","",$key);;
                 $condition[]= "(Users.email LIKE '%".$key."%' OR Users.firstname LIKE '%".$key."%' OR Users.lastname LIKE '%".$key."%')";
                 $this->set('key',$data['key']);
              }
           }
        }*/

        if(count($params) > 0){
            foreach($params as $param){
                $param_arr= explode(":", $param);
                $search_key= $param_arr[0];
                $search_value= $param_arr[1];
                if($search_key=='search_key'){
                  $search['key']= $search_value;
                  $key = trim($search_value);
                  $key=str_replace("'","",$key);
                  $condition[]= "(Users.email LIKE '%".$key."%' OR Users.firstname LIKE '%".$key."%' OR Users.lastname LIKE '%".$key."%')";
                  $this->set('key',$key);
                }
            }
        }

        $query = TableRegistry::getTableLocator()->get('Users')->find()->contain(['OrganiserLoginRecords'])->where($condition);

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
            }
        }
        $search_comp = false;

        //searching from company name
        /*if(empty($query->toArray())){
            $condition = array('Users.user_type' =>'client');
            $condition[] = array('Users.is_whitelabel !=' => 1);
            $search= array();
            if($this->request->is('post')){
               //$this->request->params['named']['page']=1;
               if($this->request->getData() !=''){
                  $data= $this->request->getData();
                  if(!empty($data['key'])){
                     $query = TableRegistry::getTableLocator()->get('Users')->find()->contain(['OrganiserLoginRecords'])->where($condition);
                      $search_comp = true;
                      $this->set('search_comp',$search_comp);
                  }
               }
            }
        }*/
        //searching from company name
        if(empty($query->toArray())){
            $condition = array('Users.user_type' =>'client');
            $condition[] = array('Users.is_whitelabel !=' => 1);
            $search= array();
            if(count($params) > 0){
                foreach($params as $param){
                    $param_arr= explode(":", $param);
                    $search_key= $param_arr[0];
                    $search_value= $param_arr[1];
                    $search['key']= $search_value;
                    if($search_key=='search_key'){
                        $query = TableRegistry::getTableLocator()->get('Users')->find()->contain(['OrganiserLoginRecords'])->where($condition);
                        $search_comp = true;
                        $this->set('search_comp',$search_comp);
                    }
               }
            }
        }
        $users_data=$this->paginate($query);

        //get users company name
        foreach ($users_data as $user){ 
            if(!empty($user['event_company_id'])){
                $company_id = $user['event_company_id'];
                $company_data=TableRegistry::getTableLocator()->get('Companies')->getCompanyByID($company_id);
                if(!empty($company_data)){
                    $user['comp_name']=$company_data['company_name'];
                }else{
                    $user['comp_name']="";
                }
            }else{
                $user['comp_name']="";
            }
        }
        $users=array();
        $i=0;
        foreach ($users_data as $user) {
            $users[$i]=$user;
            $i++;
        }
          
        //sort by company name
        foreach($params as $param){
            $param_arr= explode(":", $param);
            $search_key= $param_arr[0];
            $search_value= $param_arr[1];
            if(str_contains($search_value,'&')){
                $str_arr=explode('&', $search_value);
                $search_value=$str_arr[0];
                $order = $param_arr[2];
            }
            $search[$search_key]= $search_value;
            if($search_key=='sort_by' && $search_value=='company'){
                if($order=='asc'){
                    array_multisort(array_column($users, 'comp_name'), SORT_ASC, $users);
                }else{
                    array_multisort(array_column($users, 'comp_name'), SORT_DESC, $users);
                }
            }
        }
       
        $this->set('users',$users);
        $this->set('search',$search);
        //pr($users->toArray()); exit;
     
    }

    public function userOrgAdminStatusAjax(){
        $this->autoLayout=false;
        $this->autoRender=false;
        $this->layout='ajax';

        $data=array();
        $data['org_admin']= $_REQUEST['status'];
        $user_id= $_REQUEST['user_id'];
        $client = $this->Users->findById($user_id)->first();
        $company_events = TableRegistry::getTableLocator()->get('Events')->getCompanyEvents($client['event_company_id']);
        $data['events']=array();
        foreach($company_events as $event){
          array_push($data['events'], $event['id']);
        }
        $reg_event_ids = $data['events'];
        unset($data['events']);
        
        $entity_data=TableRegistry::getTableLocator()->get('Users')->newEntity($data);
        $entity_data->id= $_REQUEST['user_id'];

        if(TableRegistry::getTableLocator()->get('Users')->save($entity_data)){
          $uid = $_REQUEST['user_id'];

          $regCurrentEventList = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.user_id' => $uid]);


          $currentEventList = array();
          foreach ($regCurrentEventList as $each) {
              $currentEventList[] = $each['event_id'];
          }

          foreach ($reg_event_ids as $each_event_id) {
              if (!in_array($each_event_id, $currentEventList)) { //if not have this entry, then add it
                  $reg_record = array();
                  $reg_record['event_id'] = $each_event_id;
                  $reg_record['user_id'] = $uid;
                  $reg_record['status'] = 'client';

                  $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($reg_record);
                  TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data);
              }
          }

          // delete extra event of organiser
          $toBeDelEvents=array_diff($currentEventList,$reg_event_ids);
          foreach($toBeDelEvents as $evntid){
              TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->deleteAll(['ExhibitionRegistrations.event_id'=>$evntid,'ExhibitionRegistrations.user_id'=>$uid]);
          }
           echo "saved";
        }else{
           echo "failed";
        }

        die();

    }

    /* This method is only use to add a fresh exhibitor, who is never exists in the system */

    function addNewExhibitor($email = null) {
        if(@$this->currentSession->read('userdata')['email']!=$email){
            $this->currentSession->delete('userdata');
            $this->currentSession->delete('exhib_data');
        }
		$current_event=TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $this->currentSession->read('user.event.id')])->first();

        $is_event_stand = $current_event['is_event_with_stands'];
        $this->set('is_event_stand',$is_event_stand);
        $user_check = $this->Users->findByEmail($email)->first();

        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            $this->Flash->error(__('Invalid email address. []():;\<,>" characters are not allowed on Email Address.'));
            return $this->redirect(array("controller"=>$this->request->getParam('controller'),'action'=>'addExhibitor'));
        } elseif ($user_check != null) {
            $this->Flash->error(__('The exhibitor is already found in the system!'));
            $this->redirect(array("controller"=>$this->request->getParam('controller'),'action'=>'addExhibitor'));
        }

        if ($this->request->is('post')){
            $html = new HtmlHelper(new \Cake\View\View());
            
            $userdata= $this->request->getData('data.User');
            $exhib_data= $this->request->getData('data.ExhibitionRegistration');
            
            $isErrorFree = true;
            //print_r($this->request->data);die;
            $userdata['contact_tel'] = $userdata['contact_tel_num'];
            $userdata['company_addr_country'] = strtoupper($userdata['company_addr_country']);
            $userdata['email'] = trim($userdata['email']);
            
            
            $tmp_err = array();
            $user_err = array();
            if($userdata['external_username']!=""){
                $check_username = TableRegistry::getTableLocator()->get('Users')->find()->where(array('Users.external_username'=>$userdata['external_username']))->count();
                if($check_username>0){
                    $user_err[] = "External Username is already taken by another User";
                }
            }
            if($is_event_stand!=0){
                if(isset($exhib_data['booth_no']) && strlen(trim($exhib_data['booth_no'])) > 0){
                    $dup_check = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.booth_no' => $exhib_data['booth_no'],'ExhibitionRegistrations.event_id' => $this->currentSession->read('user.event.id')])->count();

                    if($dup_check > 0) {
                       /*$tmp_err[] = "The booth number has been used";*/
                       $tmp_err[] = "The Stand Number you entered has been used. Please enter a unique Stand Number";
                    }
                } else {
                    //if($is_event_stand==1){
                        $tmp_err[] = "An unique Stand number is required";
                    //}
                }
            }

            if (isset($exhib_data['event_exhibitor_types']) && count($exhib_data['event_exhibitor_types']) > 0){

            } else {
                $tmp_err[] = "User Type is required";
            }

            if(empty($exhib_data['booth_type_id'])){
               $tmp_err[] = "User Category is required";
            }

            if($exhib_data['promo_code']){
                // check unique under current event
                $is_exist=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.promo_code'=>$exhib_data['promo_code'],'ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id')])->first();

                if($is_exist){
                    $tmp_err[] = "Promo code already exist for current event";
                }
            }

            $current_user = $this->Users->findByEmail($userdata['email'])->first();

            if (count($tmp_err) > 0) {
                $error_message = implode(",", $tmp_err);
                $this->Flash->error(__($error_message));
                $this->set('userdata',$userdata);
                $this->currentSession->write('userdata',$userdata);
                $this->set('exhib_data',$exhib_data);
                $this->currentSession->write('exhib_data',$exhib_data);
                return $this->redirect(array('action'=>'addNewExhibitor',$userdata['email']."#kt_apps_contacts_view_tab_2"));
                $isErrorFree = false;
            }elseif (count($user_err) > 0) {
                $error_message = implode(",", $user_err);
                $this->Flash->error(__($error_message));
                $this->set('userdata',$userdata);
                $this->currentSession->write('userdata',$userdata);
                $this->set('exhib_data',$exhib_data);
                $this->currentSession->write('exhib_data',$exhib_data);
                return $this->redirect(array('action'=>'addNewExhibitor',$userdata['email']."#kt_apps_contacts_view_tab_1"));
                $isErrorFree = false;
            }else if ($current_user == null) { //New User
                $new_password = $this->Users->generate_password(8);
                $userdata['password'] = $new_password;
                $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($userdata);
                if(TableRegistry::getTableLocator()->get('Users')->save($entity_data)){
                    $uid = $entity_data->id;
                    
                    //===== if logo ===
                    if(isset($_FILES['logo']['name'])){
                        $file= $_FILES['logo'];
        
                        if(!file_exists(WWW_ROOT.'img'.DS.'logo')){
                            mkdir(WWW_ROOT. DS . 'img'.DS.'logo', 0777, true);
                        }

                        $filename= time()."_".$file['name'];
                        $realfilename=str_replace(" ","_",$filename);
                        $pathfilename = WWW_ROOT.'img'.DS.'logo/'.$realfilename;

                        if(move_uploaded_file($file['tmp_name'],$pathfilename)){
                            $logodata= array();
                            $logodata['logo']= $realfilename;
                            $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($logodata);
                            $entity_data->id= $uid;
                            TableRegistry::getTableLocator()->get('Users')->save($entity_data);
                            
                        }
                    }

                    // prepare to save registration data
                    $exhib_data['event_id'] = $this->currentSession->read('user.event.id');
                    $exhib_data['user_id'] = $uid;
                    $exhib_data['status'] = "new";
                    $event_exhibitor_types = "";
                    if (!empty($exhib_data['event_exhibitor_types'])){
                        $event_exhibitor_types = implode('][', $exhib_data['event_exhibitor_types']);
                        $exhib_data['event_exhibitor_types'] = '[' . $event_exhibitor_types . ']';
                    }
                    $event_booth_type = "";
                    if (!empty($exhib_data['booth_type_id'])){
                        $booth_type_id = implode('][', $exhib_data['booth_type_id']);
                        $exhib_data['booth_type_id'] = '[' . $booth_type_id . ']';
                    }
                    $exhib_data['uni_id'] = $this->currentSession->read('user.event.id').$uid;
                    $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($exhib_data);
                    if(TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data)){

                        $last_reg_id= $entity_data->id;

                        $new_user = $this->Users->findById($uid)->first();

                        //=== save custom field for new exhibitor======
                        // get custom field key of event
                        
                        $custom_fields = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['RegistrationMetas.field_key','RegistrationMetas.short_code'])->where(['RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')])->order(['RegistrationMetas.id'=>'ASC'])->toArray();

                        $tmp= array();
                        $metas= array();
                        foreach($custom_fields as $field){
                            if(!in_array($field['field_key'], $tmp)){
                               $metas[]=$field;
                            }

                            $tmp[]= $field['field_key'];
                        }

            
                        foreach($metas as $meta){
                            //get meta order and field display
                            $singlemeta=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id'),'RegistrationMetas.field_key'=>$meta['field_key'],'RegistrationMetas.short_code'=>$meta['short_code']])->first();

                            $reg_meta= array();
                            $reg_meta['reg_id']= $last_reg_id;
                            $reg_meta['event_id']= $this->currentSession->read('user.event.id');
                            $reg_meta['field_key']= $meta['field_key'];
                            $reg_meta['value']= $this->request->getData(str_replace(" ", "_", $meta['field_key']));

                            $reg_meta['short_code']= $meta['short_code'];
                            $reg_meta['meta_order']= $singlemeta['meta_order'];
                            $reg_meta['field_display']= $singlemeta['field_display'];
                            $entity_data= TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($reg_meta);
                            TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
                        }

                        $this->salesforce_sync($last_reg_id);

                        //=============================================

                        if ($this->request->getData('send_email') !='' && $this->request->getData('send_email') == 'yes'){
                           // require_once(ROOT . DS . 'vendor' . DS  . 'entmail.php');
                            require_once(ROOT . DS . 'vendor' . DS  . 'sesmail.php');

                            // choose prefrred template
                            $welcome_template= 'event_welcome';
                            $preferred_lang=$exhib_data['preferred_language'];
                            if(empty($preferred_lang)){
                                $welcome_template= 'event_welcome';
                            }else if($preferred_lang=='english'){
                                $welcome_template= 'event_welcome';
                            }else{
                                $welcome_template= 'event_welcome_'.$preferred_lang;
                            }

                            $welcome_template_content = SesMail::getTemplateContent($welcome_template);
                            if(!$welcome_template_content){
                                $welcome_template_content = SesMail::getTemplateContent('event_welcome'); 
                            }
                            
                            if ($welcome_template_content !== false){
                                
                                $related_event= TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $this->currentSession->read('user.event.id')])->first();

                                $user_auto_login_url =  Router::url(['controller' => 'System','action' => 'login',$new_user['id'], base64_encode($new_user['password']), $this->currentSession->read('user.event.id'), $last_reg_id],true);
                                //$user_auto_login_url= Router::url('/',true).'system/exhib_login?dest='.$this->currentSession->read('user.event.id')."/About-Your-Event";

                                $user_login_link = "<a href=\"{$user_auto_login_url}\" target=\"_blank\">Click here to login</a>";

                                $user_password_reset_url =  Router::url(['controller' => 'System','action' => 'reset_password',$new_user['id'], base64_encode($new_user['password']),$related_event['id']],true);

                                $user_password_reset_link = "<a href=\"{$user_password_reset_url}\" target=\"_blank\">Set Password</a>";

                                $other_lang_email_header= unserialize($related_event['other_lang_email_header']);
								
								if($welcome_template_content['type'] == 'default'){
									$defaultEmailTemplate =TableRegistry::getTableLocator()->get('DefaultTemplates')->find()->where(['DefaultTemplates.template_filename Like'=>'%event_welcome.tpl%'])->first();
									if(!empty($defaultEmailTemplate)){
										$subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$defaultEmailTemplate['subject']);
									}
								}else{	
								
									if($preferred_lang=='' || $preferred_lang=='english'){
										$subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$related_event['email_subject']);
									}else{
										$subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$other_lang_email_header['email_subject_'.$preferred_lang]);
									}
                                }
                                
                                $mail = array('subject' => $subject,
                                    'mails' => array(
                                        array(
                                            //%%EMAIL_CONTENT%% needs to be the first element to be replaced
                                            'EMAIL_CONTENT' => $welcome_template_content['content'],
                                            'email' => $new_user['email'],
                                            'EMAIL' => $new_user['email'],
                                            'FIRST_NAME' => $new_user['firstname'],
                                            'LAST_NAME' => $new_user['lastname'],
                                            'FIRSTNAME' => $new_user['firstname'],
                                            'LASTNAME'  => $new_user['lastname'],
                                            'EVENT_NAME' => $related_event['name'],
                                            'COMPANY_NAME' => $new_user['company_name'],
                                            'LOGIN_URL' => $user_auto_login_url,
                                            'LOGIN_LINK' => $user_login_link,
                                            'PASSWORD_RESET_URL' => $user_password_reset_url,
                                            'PASSWORD_RESET_LINK' => $user_password_reset_link,
                                            'EXTERNAL_USERNAME' =>$new_user['external_username'],
                                            'EXTERNAL_PASSWORD' =>$new_user['external_password'],
											'recipient_id'=>$new_user['id'],
                                        )
                                    )
                                );
                                if (strlen($related_event['event_email_sender']) > 3 && strlen($related_event['event_email_address']) > 6 && strpos($related_event['event_email_address'], '@') !== false) {
                                    $mail['sender_name'] = $related_event['event_email_sender'];
                                    $mail['sender_email'] = $related_event['event_email_address'];
                                    $mail['reply_to_name'] = $related_event['event_email_sender'];
                                    //$mail['reply_to_email'] = $related_event['Event']['event_email_address'];
                                    //Note:reply email changed from email_address to reply email
                                    $mail['reply_to_email'] = ($related_event['reply_email']) ? $related_event['reply_email'] : $related_event['event_email_address'] ;
                                }else if($related_event['reply_email']){
                                     $mail['reply_to_email'] = $related_event['reply_email'];
                                }

                                // ====== overwrite email header according to lang =====
                                if($preferred_lang !='' && $preferred_lang !='english'){
                                   $mail['sender_name'] = $other_lang_email_header['from_name_'.$preferred_lang];
                                   $mail['reply_to_email'] = $other_lang_email_header['reply_email_'.$preferred_lang]; 
                                }

                                //echo "<pre>"; print_r($mail); exit;

                               // if (EntMail::sendMail($mail, 'event_frame') !== false) {
							   
							   ############ Company White Labelled code start here #####
								$mail['sender_email'] = '';
								$event_data = TableRegistry::getTableLocator()->get('Events')->find()->select(['company_id'])->where(['id'=>$this->currentSession->read('user.event.id')])->first(); 
								if(!empty($event_data)){
									$company= TableRegistry::getTableLocator()->get('Companies')->find()->select(['company_white_labelled_email','company_white_labelled_email_verification_status','company_white_labelled_email_dkim_verification_status'])->where(['Companies.id'=>$event_data['company_id']])->first();
									if(!empty($company) && !empty($company['company_white_labelled_email']) && $company['company_white_labelled_email_verification_status'] == '1' && $company['company_white_labelled_email_dkim_verification_status'] == '1'){
										$mail['sender_email'] = $company['company_white_labelled_email'];
									}
								}
								############ Company White Labelled code end here #####	
							   $result = SesMail::sendMail($mail, 'event_frame',null,'welcome');
							   
                                if ($result !== false) {
                                    $result = array_shift($result);
									/* Save welcome email into table 'welcome_email_reports' start */
									$welcomeEmailReportsTable = $this->getTableLocator()->get('WelcomeEmailReports');
									$welcomeEmailReport = $welcomeEmailReportsTable->newEmptyEntity();
									$welcomeEmailReport->user_id = $this->currentSession->read('user.id');;
									//$welcomeEmailReport->recipient_id = $new_user['id'];
									$welcomeEmailReport->recipient_id = $result['recipient_id'];
									$welcomeEmailReport->reg_id = $last_reg_id;
									$welcomeEmailReport->event_id = $this->currentSession->read('user.event.id');
									$welcomeEmailReport->email_content = $welcome_template_content['content'];
									$welcomeEmailReport->email_subject = $subject;
									//$welcomeEmailReport->message_id  = key($result);
									
									$welcomeEmailReport->message_id  = $result['message_id'];
									$welcomeEmailReport->email_to  = $result['email_to'];
									$welcomeEmailReport->sender_name  = $result['sender_name'];
									$welcomeEmailReport->sender_email  = $result['sender_email'];
									$welcomeEmailReport->reply_to_name  = $result['reply_to_name'];
									$welcomeEmailReport->reply_to_email  = $result['reply_to_email'];
									$welcomeEmailReport->mail_content_html  = $result['mail_content_html'];
									
									
									$welcomeEmailReport->status = 'sent';
									$welcomeEmailReport->created = date('Y-m-d H:i:s');
									$welcomeEmailReport->updated = date('Y-m-d H:i:s');
									$welcomeEmailReportsTable->save($welcomeEmailReport);
									/* Save welcome email into table 'welcome_email_reports' end */
									
                                    $emailEntry = array('user_id' => $new_user['id'],'reg_id' => $last_reg_id);
                                    $emailEntry['created'] = date('Y-m-d H:i:s'); 
                                    $test= TableRegistry::getTableLocator()->get('UserEmailRecords')->find()->where(['UserEmailRecords.user_id'=>$new_user['id'],'UserEmailRecords.reg_id'=>$last_reg_id])->first();

                                    $entity_data=TableRegistry::getTableLocator()->get('UserEmailRecords')->newEntity($emailEntry);

                                    if($test){
                                        $entity_data->id = $test['id'];
                                    }

                                    TableRegistry::getTableLocator()->get('UserEmailRecords')->save($entity_data);
                                    $this->Flash->success(__('The user has been saved. A welcome email has been out'));

                                } else {
                                   
                                    $this->Flash->success(__('The user has been saved. Warning: Welcome email is not sent.'));
                                }
                            } else {
                                $this->Flash->success(__('The user has been saved. Warning: Welcome email is not sent. Please check email templates'));
                            }
                        
                           
                            
                        }else{
                            $this->Flash->success(__('The user has been saved'));
                            return $this->redirect(array('action'=>'index'));
                        }
                        
                    } else {
                        $isErrorFree = false;
                        $this->Flash->error(__('The user could not be registered to the event. Please, try again.'));
                    }
                } else {
                    $isErrorFree = false;
                    $this->Flash->error(__('The user could not be saved. Please, try again.'));
                }
            } else {
                //should not get in here!!
                $isErrorFree = false;
                $this->Flash->error(__('The exhibitor is already found in the system!'));
                return $this->redirect(array("controller" => $this->request->getParam('controller'), 'action' => 'addExhibitor'));
            }


            if ($isErrorFree) {
                $redirect_page_to = array('action' => 'index');
                if ($this->currentSession->check('user.event.page_referer')) {
                    $redirect_page_to = $this->currentSession->read('user.event.page_referer');
                    $this->currentSession->delete('user.event.page_referer');
                }
                $this->redirect($redirect_page_to);
            }
        }

        
        $eventBoothTypes = TableRegistry::getTableLocator()->get('EventBoothTypes')->find('list')->where(['EventBoothTypes.event_id' => $this->currentSession->read('user.event.id')])->toArray();


        $eventDimensions = TableRegistry::getTableLocator()->get('EventDimensions')->find('list')->where(['EventDimensions.event_id' => $this->currentSession->read('user.event.id')])->toArray();

        $eventLocations = TableRegistry::getTableLocator()->get('EventLocations')->find('list')->where(['EventLocations.event_id' => $this->currentSession->read('user.event.id')])->toArray();

        $eventExhibitorTypes = TableRegistry::getTableLocator()->get('EventExhibitorTypes')->find('list')->where(['EventExhibitorTypes.event_id' => $this->currentSession->read('user.event.id')])->toArray();

        $this->set(compact('eventExhibitorTypes', 'eventBoothTypes', 'eventDimensions', 'eventLocations'));
        $this->set('input_email', $email);

        // get default exhibitor type
        $default_exhib_type=TableRegistry::getTableLocator()->get('EventExhibitorTypes')->find()->select(['EventExhibitorTypes.id'])->where(['EventExhibitorTypes.default_exhib_type'=>'1','EventExhibitorTypes.event_id'=>$this->currentSession->read('user.event.id')])->first();
        $this->set('default_exhib_type', $default_exhib_type['id']);
        
        $default_booth_type=TableRegistry::getTableLocator()->get('EventBoothTypes')->find()->select(['EventBoothTypes.id'])->where(['EventBoothTypes.default_booth_type'=>'1','EventBoothTypes.event_id'=>$this->currentSession->read('user.event.id')])->first();
        $this->set('default_booth_type', $default_booth_type['id']);
        
        // get custom field
        $custom_fields = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['RegistrationMetas.field_key','RegistrationMetas.short_code'])->where(['RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')])->order(['RegistrationMetas.meta_order'=>'ASC'])->toArray();

        $tmp= array();
        $unique_custom_fields= array();
        foreach($custom_fields as $field){
            if(!in_array($field['field_key'], $tmp)){
               $unique_custom_fields[]=$field;
            }

            $tmp[]= $field['field_key'];
        }

        $this->set('custom_fields', $unique_custom_fields);

        // get available language
        $languages= $this->langArr();
        $this->set('languages', $languages);
        
    }


    public function editExhibitor($reg_id = null){
	
       require_once(ROOT . DS . 'vendor' . DS  . 'ent_custom_form.php');
       $custom_form = new EntCustomForm();
             
       $this->loadModel('ExhibitionRegistrations'); 
       $this->currentSession->delete('editexhibitor');

        $self_location = "/" . $this->request->getParam('controller') . "/" . $this->request->getParam('action');
        $another_referer = "/" . $this->request->getParam('controller') . "/addExhibitor";
        $refer = $this->referer();

        //$this->redirect($refer);
        if((strpos($refer, $self_location) !== 0) && (strpos($refer, $another_referer) !== 0)){
            $this->currentSession->write('user.event.page_referer', $refer);
        }

        if (!$reg_id){
            $this->Flash->error(__('Invalid user'));
            return $this->redirect(array('action' => 'index'));
        }
        $current_event=TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $this->currentSession->read('user.event.id')])->first();
        $is_event_stand = $current_event['is_event_with_stands'];
        $is_unique_identify = $current_event['unique_identify'];
        $this->set('is_event_stand',$is_event_stand);
        $this->set('is_unique_identify',$is_unique_identify);
        //=======================if submit===============================
        
        $fields_check = false;
        if(!empty($this->request->getData())){
            $std_tmp_err = array();
            $user_tmp_err = array();
            $user_detail_err = array();
            
            $exhib_post_data=$this->request->getData('data.ExhibitionRegistration');
            if($this->request->getData('external_username')!=""){
                $check_username = TableRegistry::getTableLocator()->get('Users')->find()->where(array('Users.external_username'=>$this->request->getData('external_username'),'Users.id !=' => $this->request->getData('id')))->count();
                if($check_username>0){
                    $user_detail_err[] = "External Username is already taken by another User";
                }
            }
            if($is_event_stand!=0){
                if(isset($exhib_post_data['booth_no']) && strlen(trim($exhib_post_data['booth_no'])) > 0){
                    
                    $conditions= array('ExhibitionRegistrations.booth_no' => $exhib_post_data['booth_no'],'ExhibitionRegistrations.event_id' => $this->currentSession->read('user.event.id'),'ExhibitionRegistrations.id <>' => $reg_id);

                    $dup_check = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($conditions)->count();

                    if ($dup_check > 0) {
                        /*$tmp_err[] = "The booth number has been used";*/
                        $std_tmp_err[] = "The Stand Number you entered has been used. Please enter a unique Stand Number";
                    }
                }else{
                    //$tmp_err[] = "An unique Stand number is required";
                    //if($is_event_stand==1){
                        $std_tmp_err[] = "An unique Stand number is required";
                    //}
                }
            }

            if (isset($exhib_post_data['event_exhibitor_types']) && count($exhib_post_data['event_exhibitor_types']) > 0) {
                //good
            } else {
                $user_tmp_err[] = "User Type is required";
            }

            if(empty($exhib_post_data['booth_type_id'])){
                $user_tmp_err[] = "User Category is required";
            }

            if($exhib_post_data['promo_code']){
                // check unique under current event
                $conditions= array();
                $conditions['ExhibitionRegistrations.promo_code']= $exhib_post_data['promo_code'];
                $conditions['ExhibitionRegistrations.event_id']= $this->currentSession->read('user.event.id');
                $conditions['ExhibitionRegistrations.id !=']= $reg_id;
                $is_exist=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($conditions)->count();

                if($is_exist>0){
                    $tmp_err[] = "Promo code already exist for current event";
                }
            }


            if(count($std_tmp_err) > 0){
                $error_message = implode("<br/>", $std_tmp_err);
                $this->Flash->error(__($error_message));
                return $this->redirect(array('action'=>'editExhibitor',$reg_id."#kt_apps_contacts_view_tab_2"));
            }elseif(count($user_tmp_err) > 0){
                $error_message = implode("<br/>", $user_tmp_err);
                $this->Flash->error(__($error_message));
                return $this->redirect(array('action'=>'editExhibitor',$reg_id."#kt_apps_contacts_view_tab_4"));
            }elseif(count($user_detail_err) > 0){
                $error_message = implode("<br/>", $user_detail_err);
                $this->Flash->error(__($error_message));
                return $this->redirect(array('action'=>'editExhibitor',$reg_id."#kt_apps_contacts_view_tab_1"));
            }else{
                $fields_check = true;
            }
        }

        
        if(!empty($this->request->getData()) && $fields_check){
            //echo "<pre>"; print_r($this->request->getData('email')); exit;
            // sync to salseforce
            $this->salesforce_sync($reg_id);

            // form email
            $form_email= $this->request->getData('email');
            // get db email
            $db_email=TableRegistry::getTableLocator()->get('Users')->find()->where(['Users.id' => $this->request->getData('id')])->first();
            
            if(strtolower($form_email) != strtolower($db_email['email'])){
                //check if form email exist in system
                $exist_data= TableRegistry::getTableLocator()->get('Users')->find()->where(['Users.email' => $form_email])->first();
                if(!empty($exist_data)){
                    if ($exist_data['user_type'] == 'admin' || $exist_data['user_type'] == 'client') {
                    
                        $this->Flash->error(__('The email address you attempted to add belongs to an Organiser and cannot be used as an Exhibitor email address.  Please use a different email address.'));
                        //return $this->redirect(array('action' => $this->request->getParam('action'), 'step1'));
                        return $this->redirect(array('action'=>'editExhibitor',$reg_id));
                    }
                    $get_booth_no_data = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.id'=>$reg_id])->first();
                    if($get_booth_no_data['user_id']==$this->request->getData('id')) {
                        // inform organiser with exist data
                        $this->currentSession->write('editexhibitor.exist_data', $exist_data);
                        $this->currentSession->write('editexhibitor.form_data', $this->request->getData());
                        $this->currentSession->write('editexhibitor.registration_id', $reg_id);
                        return $this->redirect(array('action'=>'edit_exibitor_step2'));
                    }else{
                        if($this->request->getData('add_new')){
                            return $this->redirect(array('action'=>'addExhibitor'));
                        }
                        return $this->redirect(array('action'=>'index'));
                    }
                }
            }


            $isErrorFree = true;

            $userdata= array();
            $userdata['id']= $this->request->getData('id');
            $userdata['user_type']= $this->request->getData('user_type');
            $userdata['firstname']= $this->request->getData('firstname');
            $userdata['lastname']= $this->request->getData('lastname');
            $userdata['email']= trim($this->request->getData('email'));
            $userdata['company_name']= $this->request->getData('company_name');
            $userdata['contact_mob']= $this->request->getData('contact_mob');
            $userdata['contact_tel']= $this->request->getData('contact_tel_num');
            $userdata['external_username']= $this->request->getData('external_username');
            $userdata['external_password']= $this->request->getData('external_password');
            $userdata['company_addr_st']= $this->request->getData('company_addr_st');
            $userdata['company_addr_city']= $this->request->getData('company_addr_city');
            $userdata['company_addr_state']= $this->request->getData('company_addr_state');
            $userdata['company_addr_postcode']= $this->request->getData('company_addr_postcode');
            $userdata['company_addr_country']= strtoupper($this->request->getData('company_addr_country'));
            $userdata['updated']= date('Y-m-d H:i:s');

            $exhib_data= array();
            $exhib_data= $this->request->getData('data.ExhibitionRegistration');
            
            if(!empty($exhib_data['event_exhibitor_types'])){
                $event_exhibitor_types = implode('][', $exhib_data['event_exhibitor_types']);
                $exhib_data['event_exhibitor_types']= '[' . $event_exhibitor_types . ']';
            }else{
                $exhib_data['event_exhibitor_types'] = '';
            }
            if(!empty($exhib_data['booth_type_id'])){
                $booth_type_id = implode('][', $exhib_data['booth_type_id']);
                $exhib_data['booth_type_id']= '[' . $booth_type_id . ']';
            }else{
                $exhib_data['booth_type_id'] = '';
            }

            //echo "<pre>"; print_r($exhib_data); exit;
            
            //--------- update here ---------
            $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($userdata);
            $entity_data->id= $userdata['id'];
            if(TableRegistry::getTableLocator()->get('Users')->save($entity_data)){
               //get all agent having same booth_no
               $agent_ids = $this->getAgentWithStand($reg_id);

               $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($exhib_data);
               $entity_data->id=$reg_id;
               TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data);

               //update agents booth_no with new booth_no.
               if(!empty($agent_ids)){
                   $this->updateAgentStand($agent_ids,$exhib_data['booth_no']);
               }
                //===========save custom field=============
                 $custom_label= $this->request->getData('custom_label');
                 $custom_value= $this->request->getData('custom_value');

                 if(!empty($custom_label)){
                     $conditions= array();
                     $conditions['Users.user_type']= 'exhibitor';
                     $conditions['ExhibitionRegistrations.event_id']= $this->currentSession->read('user.event.id');

                     $exhibs=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($conditions)->contain(['Users'])->toArray();
                     //echo "<pre>"; print_r($exhibs); exit;

                     foreach($exhibs as $exhib){
                        $i=0;
                        foreach($custom_label as $label){
                            // $getdbcustomlabel=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.field_key'=>$label, 'RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')])->first();
                            // if($getdbcustomlabel==NULL){
                                $custom_arr=array();
                                $custom_arr['reg_id']= $exhib['id'];
                                $custom_arr['event_id']=$this->currentSession->read('user.event.id');
                                $custom_arr['field_key']=$custom_label[$i];
                                $custom_arr['value']= ($exhib['id']==$reg_id) ? $custom_value[$i] : '';
                                $custom_arr['short_code']= "[CUSTOM_".str_replace(" ", "-", $custom_label[$i])."]";
                                $dbfieldkey=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['field_key'])->where(['RegistrationMetas.reg_id'=>$custom_arr['reg_id'],'RegistrationMetas.field_key'=>$custom_arr['field_key']])->first();
                    			if(empty($dbfieldkey)){
                    				$entity_data= TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($custom_arr);
    	                            // only new custom field are going to save for all exhib
    	                            TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
                    			}
                            //}
                            $i++;
                        }
                     }

                 }
                 
                //===================update feed======================
                $condi= array();
                $condi['Feeds.event_id']= $this->currentSession->read('user.event.id');
                $condi['Feeds.reg_id']= $reg_id;
                $condi['Feeds.meta_key']= 'StandNumber';
                
                TableRegistry::getTableLocator()->get('Feeds')->updateAll(['meta_value' => $exhib_data['booth_no']],$condi);
                //==================================================== 
               $this->Flash->success(__('The user has been saved'));
               /*return $this->redirect(array('action'=>'editExhibitor',$reg_id));*/
               if($this->request->getData('add_new')){
                return $this->redirect(array('action'=>'addExhibitor'));
               }
               return $this->redirect(array('action'=>'index'));

            }else{
               $this->Flash->error(__('The user could not be updated'));
            }

            //--------- update end ----------

        } 
        //===============================================================

        //load DB record
        if (empty($this->request->getData())) {
            $formExhib = $this->ExhibitionRegistrations->findById($reg_id)->firstOrFail();
            $formUser= TableRegistry::getTableLocator()->get('Users')->find()->where(['Users.id' => $formExhib['user_id']])->first();
            
            if ($formExhib == null) { //Not found, invalid user?
                $this->Flash->error(__('Invalid User'));
                $this->redirect(array('action' => 'index'));
            }
        }else{
            $formExhib['id'] = $reg_id;
        }

        
        $formUser['contact_tel_areacode'] = "";
        $formUser['contact_tel_num'] = "";
        $tmp = explode('-', $formUser['contact_tel']);
        if(count($tmp) > 1){
            $formUser['contact_tel_areacode'] = $tmp[0];
            $formUser['contact_tel_num'] = $tmp[1];
        }
       
        
        
        $this->set('formExhib',$formExhib);
        $this->set('formUser',$formUser);

        $eventBoothTypes = TableRegistry::getTableLocator()->get('EventBoothTypes')->find('list')->where(['EventBoothTypes.event_id' => $this->currentSession->read('user.event.id')])->toArray();

        $eventDimensions = TableRegistry::getTableLocator()->get('EventDimensions')->find('list')->where(['EventDimensions.event_id' => $this->currentSession->read('user.event.id')])->toArray();

        $eventLocations = TableRegistry::getTableLocator()->get('EventLocations')->find('list')->where(['EventLocations.event_id' => $this->currentSession->read('user.event.id')])->toArray();

        $eventExhibitorTypes = TableRegistry::getTableLocator()->get('EventExhibitorTypes')->find('list')->where(['EventExhibitorTypes.event_id' => $this->currentSession->read('user.event.id')])->toArray();
        
        $this->set(compact('eventExhibitorTypes', 'eventBoothTypes', 'eventDimensions', 'eventLocations'));

        // get custom field
        $custom_fields = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.reg_id'=>$reg_id])->order(['RegistrationMetas.meta_order'=>'ASC'])->toArray();
        $this->set('custom_fields',$custom_fields);

        $custom_val = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')])->order(['RegistrationMetas.id'=>'ASC']);

        $custom_val_str= '';
        $valid_arr= array();
        foreach($custom_val as $row){
            if($row['value']){
               if(!in_array($row['value'], $valid_arr)){
                  $custom_val_str .= '"'.$row['value'].'",';
                  $valid_arr[]= $row['value'];
               } 
            }
        }

        $custom_val_str= rtrim($custom_val_str,",");
        $custom_val_str= "[".$custom_val_str."]";
        $this->set('custom_val_json', $custom_val_str);

        // get company id
        $current_event=TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $this->currentSession->read('user.event.id')])->first();
        $company_id= $current_event['company_id'];
        $is_event_stand = $current_event['is_event_with_stands'];
        $this->set('is_event_stand',$is_event_stand);
        $conditions= array();
        $conditions['Events.id !=']= $this->currentSession->read('user.event.id'); // should not be current event
        $conditions['Events.company_id']= $company_id;
        
        $comp_events=TableRegistry::getTableLocator()->get('Events')->find()->select(['Events.id','Events.name'])->where($conditions)->toArray();
        $this->set('comp_events',$comp_events);
        
        // get total forms for the exhibitor
        $forms=$this->getExhibValidForms($reg_id);
        $this->set('total_forms', count($forms));
        $completed_forms=$this->getExhibCompletedForms($forms,$reg_id);
        $this->set('completed_forms', count($completed_forms));
        
        // get total login exhibitor
        $login_rec=TableRegistry::getTableLocator()->get('ExhibLoginRecords')->find()->select(['ExhibLoginRecords.user_id','ExhibLoginRecords.reg_id'])->where(['ExhibLoginRecords.reg_id'=>$reg_id,'ExhibLoginRecords.event_id'=>$this->currentSession->read('user.event.id')])->toArray();

        $this->set('total_login', count($login_rec));

        // get available language
        $languages= $this->langArr();
        $this->set('languages', $languages);


    }

    public function editExhibitorajax($reg_id = null){
       $this->autoRender = false;


        //=======================if submit===============================
        
        $fields_check = false;
                
        if(!empty($this->request->getData())) {
            //echo "<pre>"; print_r($this->request->getData('email')); exit;
            // sync to salseforce
            $this->salesforce_sync($reg_id);

            // form email
            $form_email= $this->request->getData('email');
            // get db email
            $db_email=TableRegistry::getTableLocator()->get('Users')->find()->where(['Users.id' => $this->request->getData('id')])->first();
            
            if(strtolower($form_email) != strtolower($db_email['email'])){
                //check if form email exist in system
                $exist_data= TableRegistry::getTableLocator()->get('Users')->find()->where(['Users.email' => $form_email])->first();
                if(!empty($exist_data)){
                    // inform organiser with exist data

                  if ($exist_data['user_type'] == 'admin' || $exist_data['user_type'] == 'client') {
                    
                    //return $this->redirect(array('action' => $this->request->getParam('action'), 'step1'));
                    echo "user exist";
                    exit;
                  }
                    /*$this->currentSession->write('editexhibitor.exist_data', $exist_data);
                    $this->currentSession->write('editexhibitor.form_data', $this->request->getData());
                    $this->currentSession->write('editexhibitor.registration_id', $reg_id);
                    return $this->redirect(array('action'=>'edit_exibitor_step2'));*/
                    $this->currentSession->write('editexhibitor.exist_data', $exist_data);
                    $this->currentSession->write('editexhibitor.form_data', $this->request->getData());
                    $this->currentSession->write('editexhibitor.registration_id', $reg_id);
                    echo "edit exibitor step2";
                    exit();
                }else{
                    $userdata['email']= trim($this->request->getData('email'));
                }
            }
            if($this->request->getData('external_username')!=""){
                $check_username = TableRegistry::getTableLocator()->get('Users')->find()->where(array('Users.external_username'=>$this->request->getData('external_username'),'Users.id !=' => $this->request->getData('id')))->count();
                if($check_username>0){
                    echo "External Username exist";
                    exit;
                }
            }


            $isErrorFree = true;

            $userdata= array();
            $userdata['id']= $this->request->getData('id');
            $userdata['user_type']= $this->request->getData('user_type');
            $userdata['firstname']= $this->request->getData('firstname');
            $userdata['lastname']= $this->request->getData('lastname');
            $userdata['email']= trim($this->request->getData('email'));
            $userdata['company_name']= $this->request->getData('company_name');
            $userdata['contact_mob']= $this->request->getData('contact_mob');
            $userdata['contact_tel']= $this->request->getData('contact_tel_num');
            $userdata['external_username']= $this->request->getData('external_username');
            $userdata['external_password']= $this->request->getData('external_password');
            $userdata['company_addr_st']= $this->request->getData('company_addr_st');
            $userdata['company_addr_city']= $this->request->getData('company_addr_city');
            $userdata['company_addr_state']= $this->request->getData('company_addr_state');
            $userdata['company_addr_postcode']= $this->request->getData('company_addr_postcode');
            $userdata['company_addr_country']= strtoupper($this->request->getData('company_addr_country'));
            $userdata['updated']= date('Y-m-d H:i:s');

            $exhib_data= array();
            $exhib_data= $this->request->getData('data.ExhibitionRegistration');
            
            if(!empty($exhib_data['event_exhibitor_types'])){
                $event_exhibitor_types = implode('][', $exhib_data['event_exhibitor_types']);
                $exhib_data['event_exhibitor_types']= '[' . $event_exhibitor_types . ']';
            }else{
                $exhib_data['event_exhibitor_types'] = '';
            }

            //echo "<pre>"; print_r($exhib_data); exit;
            
            //--------- update here ---------
            $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($userdata);
            $entity_data->id= $userdata['id'];
            if(TableRegistry::getTableLocator()->get('Users')->save($entity_data)){

                //get all agent having same booth_no
               $agent_ids = $this->getAgentWithStand($reg_id);

               $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($exhib_data);
               $entity_data->id=$reg_id;
               TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data);
               //update agents booth_no with new booth_no.
               if(!empty($agent_ids)){
                   $this->updateAgentStand($agent_ids,$exhib_data['booth_no']);
               }
               
                //===========save custom field=============
                 $custom_label= $this->request->getData('custom_label');
                 $custom_value= $this->request->getData('custom_value');

                 if(!empty($custom_label)){
                     $conditions= array();
                     $conditions['Users.user_type']= 'exhibitor';
                     $conditions['ExhibitionRegistrations.event_id']= $this->currentSession->read('user.event.id');

                     $exhibs=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($conditions)->contain(['Users'])->toArray();
                     //echo "<pre>"; print_r($exhibs); exit;

                     foreach($exhibs as $exhib){
                        $i=0;
                        foreach($custom_label as $label){
                            $custom_arr=array();
                            $custom_arr['reg_id']= $exhib['id'];
                            $custom_arr['event_id']=$this->currentSession->read('user.event.id');
                            $custom_arr['field_key']=$custom_label[$i];
                            $custom_arr['value']= ($exhib['id']==$reg_id) ? $custom_value[$i] : '';
                            $custom_arr['short_code']= "[CUSTOM_".str_replace(" ", "-", $custom_label[$i])."]";
                            $dbfieldkey=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['field_key'])->where(['RegistrationMetas.reg_id'=>$custom_arr['reg_id'],'RegistrationMetas.field_key'=>$custom_arr['field_key']])->first();
                      if(empty($dbfieldkey)){
                        $entity_data= TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($custom_arr);
                              // only new custom field are going to save for all exhib
                              TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
                      }
                            
                            $i++;
                        }
                     }

                 }
                 
                //===================update feed======================
                $condi= array();
                $condi['Feeds.event_id']= $this->currentSession->read('user.event.id');
                $condi['Feeds.reg_id']= $reg_id;
                $condi['Feeds.meta_key']= 'StandNumber';
                
                TableRegistry::getTableLocator()->get('Feeds')->updateAll(['meta_value' => $exhib_data['booth_no']],$condi);
                //==================================================== 
               //$this->Flash->success(__('The user has been saved'));
               /*return $this->redirect(array('action'=>'editExhibitor',$reg_id));*/
               
              echo "saved";

            }else{
               echo "not saved";
            }

            //--------- update end ----------

        } 
        //===============================================================

    }

    public function getAgentWithStand($reg_id){
        $conditions= array();
        $conditions['ExhibitionRegistrations.event_id']= $this->currentSession->read('user.event.id');
        $conditions['ExhibitionRegistrations.id']= $reg_id;
        $user_exist_data = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($conditions)->first();
        $user_exist_booth_no = $user_exist_data['booth_no'];
        $all_agents = TableRegistry::getTableLocator()->get('AgentStands')->find()->where(['AgentStands.event_id'=>$this->currentSession->read('user.event.id')])->toArray();
        $agent_id = array();
        foreach($all_agents as $agent){
            $agent_stands = json_decode($agent['stands']);
            if(in_array($user_exist_booth_no,$agent_stands)){
                // $agent_id[$i]['id']=$agent['id'];
                // $agent_id[$i]['stands']=$agent['stands'];
                // $agent_id[$i]['old_stand']=$user_exist_data['booth_no'];
                $agent_id[]=array('id'=>$agent['id'], 'stands'=>$agent['stands'], 'old_stand'=>$user_exist_data['booth_no']);
            }
        }
        return $agent_id;
    }

    public function updateAgentStand($agent_ids,$new_booth_no){
        foreach($agent_ids as $agent_id){
            $agent['id']=$agent_id['id'];
            $stands = json_decode($agent_id['stands']);
            $new_stands = array();
            foreach($stands as $stand){
                if($stand == $agent_id['old_stand']){
                    $new_stands[]=$new_booth_no;
                }else{
                    $new_stands[]=$stand;
                }
            }
            $new_stands = json_encode($new_stands);
            $agent['stands']=$new_stands;
            $entity_data= TableRegistry::getTableLocator()->get('AgentStands')->newEntity($agent);
            $entity_data->id= $agent['id'];
            TableRegistry::getTableLocator()->get('AgentStands')->save($entity_data);
        }
        
    }

    public function getExhibCompletedForms($forms,$reg_id){
       require_once(ROOT . DS . 'vendor' . DS  . 'ent_custom_form.php');
       $custom_form = new EntCustomForm();
       $exhibitor=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.id' => $reg_id])->first();
       
       $completed_forms= array();
       foreach($forms as $each_form){
            // check if applicable
            if($custom_form->checkPermission($each_form, $exhibitor)){
                $entry = $custom_form->getFormEntryByRegId($each_form['form_id'],$exhibitor['id']);
                if(!empty($entry)){
                    $completed_forms[]= $each_form;
                }
            }
        }

        return $completed_forms;
    }


    public function getExhibValidForms($reg_id){
        
        $form_conditions= array();
        $form_conditions['ApForms.ent_event_id']= $this->currentSession->read('user.event.id');
        $form_conditions['ApForms.form_active']= 1;
        
        // get all form by tag
        $forms=TableRegistry::getTableLocator()->get('ApForms')->find()->where($form_conditions)->order(['ApForms.form_name'=>'ASC'])->toArray();
        
        $exhibitor=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.id' => $reg_id])->first();
        //echo "<pre>"; print_r($forms); exit;

       //get valid form
       $mybooth = $exhibitor['booth_type_id'];
       $mybooth = trim($mybooth, '[');
       $mybooth = trim($mybooth, ']');
       $mybooth = explode('][', $mybooth);
       $mytypes = $exhibitor['event_exhibitor_types'];
       $mytypes = trim($mytypes, '[');
       $mytypes = trim($mytypes, ']');
       $mytypes = explode('][', $mytypes);
       $validForms=array();
       foreach($forms as $each){
          if(!empty($each)){
             if($each['public_form']>0){
                 $validForms[] = $each;
             }else{
                 $exhibition_types = $each['ent_exhibitor_types'];
                 $booth_types = $each['ent_booth_types'];
                 $isVlidExType = false;
                 foreach($mytypes as $each_type){
                    $key_str = '['.$each_type.']';
                    $pos = strrpos($exhibition_types, $key_str);
                    if ($pos !== false) { // note: found record
                        $isVlidExType = true;
                    }
                 }

                 $isValidBooth = false;
                 //$key_str = '['.$mybooth.']';
                 /*$key_str = $mybooth;
                 $pos = strrpos($booth_types, $key_str);
                 if($pos !== false) { // note: found record
                    $isValidBooth = true;
                 }*/
                 foreach($mybooth as $each_type){
                    $key_str = '['.$each_type.']';
                    $pos = strrpos($booth_types, $key_str);
                    if ($pos !== false) { // note: found record
                        $isValidBooth = true;
                    }
                 }

                 if($isVlidExType && $isValidBooth){   
                    $validForms[] = $each;
                 }
             }

          }
       }

       return $validForms;
    }

    public function uploadExhibLogoAjax(){
       $this->autoRender=false;
       $this->autoLayout=false;
       $this->layout='ajax';

       if($_FILES['exhib_logo']['name']){
          $file= $_FILES['exhib_logo'];
        
          if(!file_exists(WWW_ROOT.'img'.DS.'logo')){
             mkdir(WWW_ROOT. DS . 'img'.DS.'logo', 0777, true);
          }

          $filename= time()."_".$file['name'];
          $realfilename=str_replace(" ","_",$filename);
          $pathfilename = WWW_ROOT.'img'.DS.'logo/'.$realfilename;

          if(move_uploaded_file($file['tmp_name'],$pathfilename)){
             $data= array();
             $data['logo']= $realfilename;
             $entity_data=TableRegistry::getTableLocator()->get('Users')->newEntity($data);
             $entity_data->id= $this->request->getData('user_id');
             TableRegistry::getTableLocator()->get('Users')->save($entity_data);
             echo "uploaded";
          }else{
             echo "not_uploaded";
          }
       }

       exit();

    }


    public function countCustomFieldsAjax(){
        $this->autoLayout=false;
        $this->autoRender=false;
        $this->layout='ajax'; 

        $event_id= $this->request->getData('event_id');
        
        $custom_datas = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.event_id'=>$event_id])->order(['RegistrationMetas.meta_order'=>'ASC']);

        // unique custom fields
        $temp_arr= array();
        $custom_fields= array();
        foreach($custom_datas as $custom){
            if(!in_array($custom['field_key'], $temp_arr)){
               $custom_fields[]= $custom;
            }

            $temp_arr[]=$custom['field_key'];
        }

        echo count($custom_fields);

        die();

    }


    public function importCustomFieldsAjax(){
        $this->autoLayout=false;
        $this->autoRender=false;
        $this->layout='ajax';
        
        $from_event_id= $this->request->getData('event_id');
        
        //$custom_datas= $this->RegistrationMeta->find('all',array('conditions'=>array('RegistrationMeta.event_id'=>$from_event_id),'order' => array('RegistrationMeta.meta_order ASC')));

        $custom_datas= TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.event_id'=>$from_event_id])->order(['RegistrationMetas.meta_order'=>'ASC']);

        // unique custom fields
        $temp_arr= array();
        $custom_fields= array();
        foreach($custom_datas as $custom){
            if(!in_array($custom['field_key'], $temp_arr)){
               $custom_fields[]= $custom;
            }

            $temp_arr[]=$custom['field_key'];
        }

        // get all exhibitor of current event
        $conditions= array();
        $conditions['ExhibitionRegistrations.event_id']=$this->currentSession->read('user.event.id');
        $conditions['Users.user_type']='exhibitor';
        //$exhibs = $this->ExhibitionRegistration->find('all',array('conditions'=>$conditions));
        $exhibs =TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($conditions)->contain(['Users']);
        
        foreach($exhibs as $exhib){
            // last meta order
            $heighest_order=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['meta_order' => 'MAX(RegistrationMetas.meta_order)'])->where(['RegistrationMetas.reg_id' => $exhib['id'], 'RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')])->first();
            
            if(!empty($heighest_order)){
                $heigh_order= $heighest_order['meta_order'];
                $meta_order=$heigh_order+1;
            }else{
                $meta_order=1; 
            }

            //print_r($custom_fields); exit;
            
            foreach($custom_fields as $cs){
                // check if field key already exist
                $dbfieldkey=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['field_key'])->where(['RegistrationMetas.reg_id'=>$exhib['id'],'RegistrationMetas.field_key'=>$cs['field_key']])->first();
                
                if(empty($dbfieldkey)){
                    $custom_arr=array();
                    $custom_arr['reg_id']= $exhib['id'];
                    $custom_arr['event_id']= $this->currentSession->read('user.event.id');
                    $custom_arr['field_key']= $cs['field_key'];
                    $custom_arr['value']= '';
                    $custom_arr['short_code']= $cs['short_code'];
                    $custom_arr['meta_order']= $meta_order;
                    
                    $entity_data=TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($custom_arr);
                    TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
                    $meta_order++; 
                }
                
            }
        }

        echo "saved";
        die();
        
    }

    //get autocomplete data 
 
    public function getAutocompleteAjax(){
        $this->autoLayout=false;
        $this->autoRender=false;
        $this->layout='ajax';
        //$custom_val= $this->RegistrationMeta->find('all',array('conditions'=>array('RegistrationMeta.value LIKE'=>$_REQUEST['term'].'%', 'RegistrationMeta.event_id'=>$this->Session->read('user.event.id'))));

        $conditions= array();
        $conditions= array('RegistrationMetas.value LIKE'=>$_REQUEST['term'].'%', 'RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id'));
        $custom_val = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where($conditions);
        
        $custom_val_str= '';
        $custom_arr= array();
        foreach($custom_val as $row){
            if($row['value']){
               $custom_arr[]= $row['value'];
            }
        }
        $valid_arr=array_unique($custom_arr);
        echo json_encode($valid_arr);
        die(); 
    }
    
    
    public function editExibitorStep2(){

      $form_data= $this->currentSession->read('editexhibitor.form_data');
      $exist_data= $this->currentSession->read('editexhibitor.exist_data');
      $reg_id= $this->currentSession->read('editexhibitor.registration_id');

      $agent_id = $exist_data['id'];
            $stands = TableRegistry::getTableLocator()->get('AgentStands')->find()->where(['AgentStands.event_id'=>$this->currentSession->read('user.event.id'),'AgentStands.user_id'=>$agent_id])->toArray();
            if($stands){
              $stands_name = json_decode($stands['0']['stands']);
              $total_stand = count(json_decode($stands['0']['stands'])); 
              //print_r($total_stand);
              if($total_stand>1){
                $user=array();
                foreach ($stands_name as $stand_name) {
                    $user_data = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.booth_no'=>$stand_name,'ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id')])->toArray();
                    if(!in_array( $user_data['0']['user_id'], $user)){
                        array_push($user, $user_data['0']['user_id']);
                    }
                }

                if(count($user)>1){
                    $this->set('cannot_change','cannot_change');
                }else{
                    $this->set('change_user','change_user');
                }
            }
            $this->set('stands_name', $stands_name); 
            $this->set('total_stand', $total_stand); 
            }

        
        if($this->request->is('post')){
            // $form_data= $this->currentSession->read('editexhibitor.form_data');
            // $exist_data= $this->currentSession->read('editexhibitor.exist_data');
            // $reg_id= $this->currentSession->read('editexhibitor.registration_id');

            //echo "<pre>"; print_r($form_data); exit;
            if($this->request->getData('all_user')){
              foreach ($stands_name as $stand_name) {
                $user_data = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.booth_no'=>$stand_name,'ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id')])->first();
                if(!empty($form_data['data']['ExhibitionRegistration']['event_exhibitor_types'])) {
                    $event_exhibitor_types = implode('][', $form_data['data']['ExhibitionRegistration']['event_exhibitor_types']);
                    $form_data['ExhibitionRegistration']['event_exhibitor_types'] = '[' . $event_exhibitor_types . ']';
                }else{
                    $form_data['ExhibitionRegistration']['event_exhibitor_types'] = '';
                }


                $exhib_data= $form_data['ExhibitionRegistration'];
                // transfer stand ownership to exist user
                $exhib_data['user_id']= $exist_data['id'];
                $exhib_data['id']= $user_data['id'];
                $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($exhib_data);
                if(TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data)){
                  if(isset($form_data['User']['welcome_email']) && $form_data['User']['welcome_email'] == 1){
                    $this->resendWelcome($exist_data['id'].'-'.$user_data['id'],false,true);
                  }
                  if(isset($form_data['custom_label'])){
                      $custom_label= $form_data['custom_label'];
                      $custom_value= $form_data['custom_value'];
                      $i=0;
                      foreach($custom_label as $label){
                         $custom_arr=array();
                         $custom_arr['reg_id']=$user_data['id'];;
                         $custom_arr['event_id']=$this->currentSession->read('user.event.id');
                         $custom_arr['field_key']=$custom_label[$i];
                         $custom_arr['value']=$custom_value[$i];
                         $custom_arr['short_code']= "[CUSTOM_".str_replace(" ", "-", $custom_label[$i])."]";
                         
                         $entity_data= TableRegistry::getTableLocator()->get('RegistrationMeta')->newEntity($custom_arr);;
                         TableRegistry::getTableLocator()->get('RegistrationMeta')->save($entity_data);
                         $i++;
                       }  
                  }


                }
              }
              $exter['user_type']= 'exhibitor';
              $update_data= TableRegistry::getTableLocator()->get('Users')->newEntity($exter);
              $update_data->id= $exist_data['id'];
              TableRegistry::getTableLocator()->get('Users')->save($update_data);
              $agent_id = $exist_data['id'];
              TableRegistry::getTableLocator()->get('AgentStands')->deleteAll(['AgentStands.user_id'=>$agent_id,'AgentStands.event_id'=>$this->currentSession->read('user.event.id')]);
              $reg_data['user_id']=$exist_data['id'];
              $reg_data['event_id']=$this->currentSession->read('user.event.id');
              $reg_data['user_type']='agent';
              $entity_data= TableRegistry::getTableLocator()->get('UserRoles')->newEntity($reg_data);
      
              TableRegistry::getTableLocator()->get('UserRoles')->save($entity_data);
              $this->currentSession->delete('editexhibitor');
              $this->Flash->success('The user has been saved');
              return $this->redirect(array('action'=>'index'));
            }else{


              if(!empty($form_data['data']['ExhibitionRegistration']['event_exhibitor_types'])) {
                  $event_exhibitor_types = implode('][', $form_data['data']['ExhibitionRegistration']['event_exhibitor_types']);
                  $form_data['ExhibitionRegistration']['event_exhibitor_types'] = '[' . $event_exhibitor_types . ']';
              }else{
                  $form_data['ExhibitionRegistration']['event_exhibitor_types'] = '';
              }


              $exhib_data= $form_data['ExhibitionRegistration'];
              // transfer stand ownership to exist user
              $exhib_data['user_id']= $exist_data['id'];
              $exhib_data['id']= $reg_id;
              $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($exhib_data);
              
              if(TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data)){
                $exter['user_type']= 'exhibitor';
                $update_data= TableRegistry::getTableLocator()->get('Users')->newEntity($exter);
                $update_data->id= $exist_data['id'];
                TableRegistry::getTableLocator()->get('Users')->save($update_data);

                 if(isset($form_data['User']['welcome_email']) && $form_data['User']['welcome_email'] == 1){
                    $this->resendWelcome($exhib_data['user_id'].'-'.$reg_id,false,true);
                 }

                 //===========save custom field=============
                  if(isset($form_data['custom_label'])){
                      $custom_label= $form_data['custom_label'];
                      $custom_value= $form_data['custom_value'];
                      $i=0;
                      foreach($custom_label as $label){
                         $custom_arr=array();
                         $custom_arr['reg_id']=$reg_id;
                         $custom_arr['event_id']=$this->currentSession->read('user.event.id');
                         $custom_arr['field_key']=$custom_label[$i];
                         $custom_arr['value']=$custom_value[$i];
                         $custom_arr['short_code']= "[CUSTOM_".str_replace(" ", "-", $custom_label[$i])."]";
                         
                         $entity_data= TableRegistry::getTableLocator()->get('RegistrationMeta')->newEntity($custom_arr);;
                         TableRegistry::getTableLocator()->get('RegistrationMeta')->save($entity_data);
                         $i++;
                       }  
                  }
                 //=========================================
                  //code to add user as exhibitor and remove from additional user
                  if($exist_data['user_type']=='agent'){
                      $agent_id = $exist_data['id'];
                      //$this->AgentStand->deleteAll(array('AgentStand.user_id'=>$agent_id,'AgentStand.event_id'=>$this->Session->read('user.event.id')));
                      TableRegistry::getTableLocator()->get('AgentStands')->deleteAll(['AgentStands.user_id'=>$agent_id,'AgentStands.event_id'=>$this->currentSession->read('user.event.id')]);
                      $reg_data['user_id']=$exist_data['id'];
                      $reg_data['event_id']=$this->currentSession->read('user.event.id');
                      $reg_data['user_type']='agent';
                      $entity_data= TableRegistry::getTableLocator()->get('UserRoles')->newEntity($reg_data);
              
                      TableRegistry::getTableLocator()->get('UserRoles')->save($entity_data);
                  }else{
                      $is_agent=TableRegistry::getTableLocator()->get('UserRoles')->find()->where(['UserRoles.user_id'=>$exist_data['id']])->first();
                      $stands=TableRegistry::getTableLocator()->get('AgentStands')->find()->where(['AgentStands.user_id'=>$agent_id,'AgentStands.event_id'=>$this->currentSession->read('user.event.id')])->first();
                      if(!empty($is_agent) || !empty($stands)){
                          $agent_id = $exist_data['id'];
                          TableRegistry::getTableLocator()->get('AgentStands')->deleteAll(['AgentStands.user_id'=>$agent_id,'AgentStands.event_id'=>$this->currentSession->read('user.event.id')]);
                          $reg_data['user_id']=$exist_data['id'];
                          $reg_data['event_id']=$this->currentSession->read('user.event.id');
                          $reg_data['user_type']='agent';
                          $entity_data= TableRegistry::getTableLocator()->get('UserRoles')->newEntity($reg_data);
              
                      TableRegistry::getTableLocator()->get('UserRoles')->save($entity_data);
                      }
                  }
                  
                 $this->currentSession->delete('editexhibitor');
                 $this->Flash->success('The user has been saved');
                 return $this->redirect(array('action'=>'index'));
              }else{
                 $this->currentSession->delete('editexhibitor');
                 $this->Flash->error('The user could not be saved');
                 return $this->redirect(array('action'=>'editExhibitor',$reg_id));
              }

            
            }
        }
    }


    public function saveCustomFieldAjax(){
        $this->autoLayout=false;
        $this->autoRender=false;
        $this->layout='ajax';

        $custom_arr=array();
        $custom_arr['id']=$this->request->getData('id');
        $custom_arr['value']=$this->request->getData('value');
        
        $entity_data = TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($custom_arr);
        TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
        
        //======== update feeds if exist=================
        
        $meta= TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.id'=>$this->request->getData('id')])->first();

        $condi= array();
        $condi['Feeds.event_id']= $meta['event_id'];
        $condi['Feeds.reg_id']= $meta['reg_id'];
        $condi['Feeds.meta_key']= str_replace(" ", "-", $meta['field_key']);
    
        TableRegistry::getTableLocator()->get('Feeds')->updateAll(['meta_value'=>$meta['value']],$condi);

        echo "updated";
        
        die();
    }

    // function for meta order saving 
    
    public function saveMetaOrderAjax(){
        $field_keys= $_POST['field_key'];

        $order=1;
        foreach($field_keys as $field_key){
            $condi= array();
            $condi['RegistrationMetas.event_id']= $this->currentSession->read('user.event.id');
            $condi['RegistrationMetas.field_key']= $field_key;
            TableRegistry::getTableLocator()->get('RegistrationMetas')->updateAll(['meta_order' => $order],$condi);
            $order++;
        }
        exit;
    }
    
    public function deleteCustomFieldAjax(){
        $this->autoLayout=false;
        $this->autoRender=false;
        $this->layout='ajax';

        $id= $this->request->getData('id');
        $event_id= $this->currentSession->read('user.event.id');
        $field_key= $this->request->getData('field_key');

        if(TableRegistry::getTableLocator()->get('RegistrationMetas')->deleteAll(['RegistrationMetas.event_id'=>$event_id,'RegistrationMetas.field_key'=>$field_key])){
           echo "deleted";
        }
        
        die();
    }

    public function updateLabelAjax(){
        $this->autoLayout=false;
        $this->autoRender=false;
        $this->layout='ajax';

        $field_key=$_REQUEST['value'];
        $event_id= $this->currentSession->read('user.event.id');
        // get exist field key for the pk
        //$db_field_key= $this->RegistrationMeta->field('field_key',array('RegistrationMeta.id'=>$_REQUEST['pk']));
        $shortcode_field_key= TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.id' => $_REQUEST['pk']])->first();
        $db_shortcode = $shortcode_field_key['short_code'];

        $getdbcustomlabel=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.field_key'=>$field_key, 'RegistrationMetas.event_id'=>$event_id,'RegistrationMetas.short_code !='=>$db_shortcode])->first();
        if($getdbcustomlabel==NULL){
            $db_field_key=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['field_key'])->where(['RegistrationMetas.id' => $_REQUEST['pk']])->first();
            
            // update label for all exhib
            $conditions['RegistrationMetas.event_id']= $event_id;
            $conditions['RegistrationMetas.field_key']= $db_field_key['field_key'];
            //$this->RegistrationMeta->updateAll(array('RegistrationMeta.field_key'=>"'".$field_key."'"),$conditions);
            TableRegistry::getTableLocator()->get('RegistrationMetas')->updateAll(['field_key' => $field_key],$conditions);
            echo "updated";
            die();
        }else{
            echo "label already found";
            die();
        }
    }


    function addExhibitor($step = null) {
        $this_event= TableRegistry::getTableLocator()->get('Events')->findById($this->currentSession->read('user.event.id'))->first();
        $is_event_stand = $this_event['is_event_with_stands'];
        $this->set('is_event_stand',$is_event_stand);
        $self_location = "/" . $this->request->getParam('controller') . "/" . $this->request->getParam('action');
        $refer = $this->referer();
        if (strpos($refer, $self_location) !== 0) {
            $this->currentSession->write('user.event.page_referer', $refer);
        }
        //print_r($refer);

        if ($step == null) {
            $this->redirect(array('action' => $this->request->getParam('action'), 'step1'));
        } elseif ($step == "step1") {
            //do nothing
        } elseif ($step == "step2") {
            
            $eventExhibitorTypes = TableRegistry::getTableLocator()->get('EventExhibitorTypes')->find('list')->where(['EventExhibitorTypes.event_id' => $this->currentSession->read('user.event.id')])->toArray();

            $eventBoothTypes = TableRegistry::getTableLocator()->get('EventBoothTypes')->find('list')->where(['EventBoothTypes.event_id' => $this->currentSession->read('user.event.id')])->toArray();
            $default_booth_type=TableRegistry::getTableLocator()->get('EventBoothTypes')->find()->select(['EventBoothTypes.id'])->where(['EventBoothTypes.default_booth_type'=>'1','EventBoothTypes.event_id'=>$this->currentSession->read('user.event.id')])->first();
            $this->set('default_booth_type', @$default_booth_type['id']);
            $eventDimensions = TableRegistry::getTableLocator()->get('EventDimensions')->find('list')->where(['EventDimensions.event_id' => $this->currentSession->read('user.event.id')])->toArray();

            $eventLocations = TableRegistry::getTableLocator()->get('EventLocations')->find('list')->where(['EventLocations.event_id' => $this->currentSession->read('user.event.id')])->toArray();

            $this->set(compact('eventExhibitorTypes', 'eventBoothTypes', 'eventDimensions', 'eventLocations'));

            // get available language
            $languages= $this->langArr();
            $this->set('languages', $languages);
            if($this->request->getData('uid') !==null){
                $user = $this->Users->findById(trim($this->request->getData('uid')))->first();
                if ($user) {

                    //echo "<pre>"; print_r($this->request->getData()); exit;

                    $tmp_err = array();
                    $new_reg = array();
                    $new_reg['user_id'] = $user['id'];
                    $new_reg['event_id'] = $this->currentSession->read('user.event.id');
                    //$new_reg['ExhibitionRegistration']['event_exhibitor_types'] = "";
                    $new_reg['booth_no'] = trim($this->request->getData('booth_no'));
                    $new_reg['booth_name'] = trim($this->request->getData('booth_name'));
                   
                    //$new_reg['booth_type_id'] = $this->request->getData('booth_type_id');
                    $new_reg['event_dimension_id'] = $this->request->getData('event_dimension_id');
                    $new_reg['event_location_id'] = $this->request->getData('event_location_id');

                    $new_reg['preferred_language'] = $this->request->getData('preferred_language');
                    $new_reg['promo_code'] = $this->request->getData('promo_code');

                    $new_reg['status'] = "new";



                    if (!empty($this->request->getData('event_exhibitor_types'))) {
                        $event_exhibitor_types = implode('][', $this->request->getData('event_exhibitor_types'));
                        $new_reg['event_exhibitor_types'] = '[' . $event_exhibitor_types . ']';
                    }

                    if (!empty($this->request->getData('booth_type_id'))) {
                        $booth_type_id = implode('][', $this->request->getData('booth_type_id'));
                        $new_reg['booth_type_id'] = '[' . $booth_type_id . ']';
                    }

                    if (strlen($new_reg['event_exhibitor_types']) < 1) {
                        $tmp_err[] = "User Type is required";
                    }
                    if($this->request->getData('external_username')!=""){
                        $check_username = TableRegistry::getTableLocator()->get('Users')->find()->where(array('Users.external_username'=>$this->request->getData('external_username'),'Users.id !=' => $user['id']))->count();
                        if($check_username>0){
                            $tmp_err[] = "External Username already taken by another User";
                        }
                    }
                    if($is_event_stand!=0){
                        if (strlen($new_reg['booth_no']) > 0) {
                            $dup_check = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.booth_no'=>$new_reg['booth_no'],'ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id')])->count();

                            if ($dup_check > 0) {
                                /*$tmp_err[] = "The Booth Number has been used";*/
                                $tmp_err[] = "The Stand Number you entered has been used. Please enter a unique Stand Number";
                            }
                        } else {
                            //$tmp_err[] = "An Unique Stand Number is required";
                            //if($is_event_stand==1){
                                $tmp_err[] = "An unique Stand number is required";
                            //}
                        }
                    }

                    if(empty($this->request->getData('booth_type_id'))){
                        $tmp_err[] = "User Category is Required";
                    }

                    if($new_reg['promo_code']){
                        $conditions= array();
                        $conditions['ExhibitionRegistrations.promo_code']= $new_reg['promo_code'];
                        $conditions['ExhibitionRegistrations.event_id']= $this->currentSession->read('user.event.id');
                        $is_exist= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($conditions)->first();

                        if(!empty($is_exist)){
                            $tmp_err[] = "Promo code already exist for current event";
                        }
                    }

                    if (count($tmp_err) > 0) {
                        $error_message = implode(", ", $tmp_err);
                        $this->Flash->error(__($error_message));
                        $this->set('user', $user);
                        // get default exhibitor type
                        $default_exhib_type=TableRegistry::getTableLocator()->get('EventExhibitorTypes')->find()->select(['EventExhibitorTypes.id'])->where(['EventExhibitorTypes.default_exhib_type'=>'1','EventExhibitorTypes.event_id'=>$this->currentSession->read('user.event.id')])->first();
                        $this->set('default_exhib_type', $default_exhib_type['id']);

                        $custom_fields = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['RegistrationMetas.field_key','RegistrationMetas.short_code'])->where(['RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')])->order(['RegistrationMetas.meta_order'=>'ASC'])->toArray();
                    
                        $tmp= array();
                        $unique_custom_fields= array();
                        foreach($custom_fields as $field){
                            if(!in_array($field['field_key'], $tmp)){
                               $unique_custom_fields[]=$field;
                            }

                            $tmp[]= $field['field_key'];
                        }

                        $this->set('custom_fields', $unique_custom_fields);

                    } else {

                        //========= update external user and pass, logo==================
                        $exter=array();   
                        $exter['external_username']= trim($this->request->getData('external_username'));
                        $exter['external_password']= trim($this->request->getData('external_password'));
                        $exter['user_type']='exhibitor';
                        $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($exter);
                        $entity_data->id= $user['id'];
                        TableRegistry::getTableLocator()->get('Users')->save($entity_data);
                        //code to add user as exhibitor and remove from additional user
                        if($user['user_type']=='agent'){
                            $agent_id = $user['id'];
                            //$this->AgentStand->deleteAll(array('AgentStand.user_id'=>$agent_id,'AgentStand.event_id'=>$this->Session->read('user.event.id')));
                            TableRegistry::getTableLocator()->get('AgentStands')->deleteAll(['AgentStands.user_id'=>$agent_id,'AgentStands.event_id'=>$this->currentSession->read('user.event.id')]);
                            $reg_data['user_id']=$user['id'];
                            $reg_data['event_id']=$this->currentSession->read('user.event.id');
                            $reg_data['user_type']='agent';
                            $entity_data= TableRegistry::getTableLocator()->get('UserRoles')->newEntity($reg_data);
                    
                            TableRegistry::getTableLocator()->get('UserRoles')->save($entity_data);
                        }else{
                            $is_agent=TableRegistry::getTableLocator()->get('UserRoles')->find()->where(['UserRoles.user_id'=>$user['id']])->first();
                            $stands=TableRegistry::getTableLocator()->get('AgentStands')->find()->where(['AgentStands.user_id'=>$user['id'],'AgentStands.event_id'=>$this->currentSession->read('user.event.id')])->first();
                            if(!empty($is_agent) || !empty($stands)){
                                $agent_id = $user['id'];
                                TableRegistry::getTableLocator()->get('AgentStands')->deleteAll(['AgentStands.user_id'=>$agent_id,'AgentStands.event_id'=>$this->currentSession->read('user.event.id')]);
                                $reg_data['user_id']=$user['id'];
                                $reg_data['event_id']=$this->currentSession->read('user.event.id');
                                $reg_data['user_type']='agent';
                                $entity_data= TableRegistry::getTableLocator()->get('UserRoles')->newEntity($reg_data);
                    
                                TableRegistry::getTableLocator()->get('UserRoles')->save($entity_data);
                            }
                        }
                        
                        
                        //===== if logo ===
                        if(isset($_FILES['logo']['name'])){
                            $file= $_FILES['logo'];
            
                            if(!file_exists(WWW_ROOT.'img'.DS.'logo')){
                                mkdir(WWW_ROOT. DS . 'img'.DS.'logo', 0777, true);
                            }

                            $filename= time()."_".$file['name'];
                            $realfilename=str_replace(" ","_",$filename);
                            $pathfilename = WWW_ROOT.'img'.DS.'logo/'.$realfilename;

                            if(move_uploaded_file($file['tmp_name'],$pathfilename)){
                                $logodata= array();
                                $logodata['logo']= $realfilename;
                                $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($logodata);
                                $entity_data->id= $user['id'];
                                TableRegistry::getTableLocator()->get('Users')->save($entity_data);
                                
                            }
                        }

                        //======================================================
                        $new_reg['created']= date('Y-m-d H:i:s');
                        $new_reg['updated']= date('Y-m-d H:i:s');
                        $new_reg['uni_id'] = $this->currentSession->read('user.event.id').$user['id'];
                        $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($new_reg);
                        TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data);
                        $new_reg_id = $entity_data->id;

                        $save_result= ($new_reg_id) ? true : false;

                        //=== add custom field for exhibitor=======
                        //$metas=$this->RegistrationMeta->find('all',array('fields'=>array('DISTINCT RegistrationMeta.field_key','RegistrationMeta.short_code'),'conditions'=>array('RegistrationMeta.event_id'=>$this->Session->read('user.event.id'))));

                        $custom_fields=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['RegistrationMetas.field_key','RegistrationMetas.short_code'])->where(['RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')])->order(['RegistrationMetas.meta_order'=>'ASC'])->toArray();
                    
                        $tmp= array();
                        $metas= array();
                        foreach($custom_fields as $field){
                            if(!in_array($field['field_key'], $tmp)){
                               $metas[]=$field;
                            }

                            $tmp[]= $field['field_key'];
                        }

                        
                        foreach($metas as $meta){
                            //$singlemeta=$this->RegistrationMeta->find('first',array('conditions'=>array('RegistrationMeta.event_id'=>$this->Session->read('user.event.id'),'RegistrationMeta.field_key'=>$meta['RegistrationMeta']['field_key'],'RegistrationMeta.short_code'=>$meta['RegistrationMeta']['short_code'])));

                            $singlemeta=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id'),'RegistrationMetas.field_key'=>$meta['field_key'],'RegistrationMetas.short_code'=>$meta['short_code']])->first();

                            $reg_meta= array();
                            $reg_meta['reg_id']= $new_reg_id;
                            $reg_meta['event_id']= $this->currentSession->read('user.event.id');
                            $reg_meta['field_key']= $meta['field_key'];
                            $reg_meta['value']= $this->request->getData(str_replace(" ", "_", $meta['field_key']));

                            $reg_meta['short_code']= $meta['short_code'];
                            $reg_meta['meta_order']= $singlemeta['meta_order'];
                            $reg_meta['field_display']= $singlemeta['field_display'];

                            $entity_data= TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($reg_meta);
                            TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
                            
                        }

                        $this->salesforce_sync($new_reg_id);
                        //==== end of add custom field ============


                        if ($save_result) { //success
                            $warning = "";

                            if($this->request->getData('send_email') !==null && $this->request->getData('send_email') == 'yes') {
                                
                                $html = new HtmlHelper(new \Cake\View\View());
                               // require_once(ROOT . DS . 'vendor' . DS  . 'entmail.php');
                                  require_once(ROOT . DS . 'vendor' . DS  . 'sesmail.php');

                                $preferred_lang= $new_reg['preferred_language']; 
                                $welcome_template= 'event_welcome';
                                if(empty($preferred_lang)){
                                    $welcome_template= 'event_welcome';
                                }else if($preferred_lang=='english'){
                                    $welcome_template= 'event_welcome';
                                }else{
                                    $welcome_template= 'event_welcome_'.$preferred_lang;
                                }

                                //$welcome_template_content = EntMail::getTemplateContent($welcome_template);
                                $welcome_template_content = SesMail::getTemplateContent($welcome_template);
                                if(empty($welcome_template_content)){
                                    //$welcome_template_content = EntMail::getTemplateContent('event_welcome'); 
                                    $welcome_template_content = SesMail::getTemplateContent('event_welcome');
                                }
								
                                //var_dump($welcome_template_content); exit;
                                
                                if ($welcome_template_content !== false) {
                                    $user = $this->Users->findById(trim($this->request->getData('uid')))->first();
                                    $related_event= TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $this->currentSession->read('user.event.id')])->first();

                                    $user_auto_login_url =  Router::url(['controller' => 'System','action' => 'login',$user['id'], base64_encode($user['password']), $this->currentSession->read('user.event.id'), $new_reg_id],true);
                                    //$user_auto_login_url= Router::url('/',true).'system/exhib_login?dest='.$this->currentSession->read('user.event.id')."/About-Your-Event";
                                    
                                    $user_login_link = $html->link('Click here to login', $user_auto_login_url);

                                    $user_password_reset_url =  Router::url(['controller' => 'System','action' => 'reset_password',$user['id'], base64_encode($user['password']),$related_event['id']],true);

                                    $user_password_reset_link = "<a href=\"{$user_password_reset_url}\" target=\"_blank\">Set Password</a>";

                                    $other_lang_email_header= unserialize($related_event['other_lang_email_header']);
                                    if($preferred_lang=='' || $preferred_lang=='english'){
                                       $subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$related_event['email_subject']); 
                                   }else{
                                      $subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$other_lang_email_header['email_subject_'.$preferred_lang]); 
                                   }

									if($welcome_template_content['type'] == 'default'){
										$defaultEmailTemplate =TableRegistry::getTableLocator()->get('DefaultTemplates')->find()->where(['DefaultTemplates.template_filename Like'=>'%event_welcome_agent.tpl%'])->first();
										$subject = $defaultEmailTemplate['subject'];
										$subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$defaultEmailTemplate['subject']); 
									}
                                    

                                    $mail = array('subject' => $subject,
                                        'mails' => array(
                                            array(
                                                //%%EMAIL_CONTENT%% needs to be the first element to be replaced
                                                'EMAIL_CONTENT' => $welcome_template_content['content'],
                                                'email' => $user['email'],
                                                'EMAIL' => $user['email'],
                                                'FIRST_NAME' => $user['firstname'],
                                                'LAST_NAME' => $user['lastname'],
                                                'EVENT_NAME' => $related_event['name'],
                                                'COMPANY_NAME' => $user['company_name'],
                                                'LOGIN_URL' => $user_auto_login_url,
                                                'LOGIN_LINK' => $user_login_link,
                                                'PASSWORD_RESET_URL' => $user_password_reset_url,
                                                'PASSWORD_RESET_LINK' => $user_password_reset_link,
                                                'EXTERNAL_USERNAME' =>$user['external_username'],
                                                'EXTERNAL_PASSWORD' =>$user['external_password'],
												'recipient_id'=>$user['id'],
                                            )
                                        )
                                    );
                                    if (strlen($related_event['event_email_sender']) > 3 &&
                                            strlen($related_event['event_email_address']) > 6 &&
                                            strpos($related_event['event_email_address'], '@') !== false) {
                                        $mail['sender_name'] = $related_event['event_email_sender'];
                                        $mail['sender_email'] = $related_event['event_email_address'];
                                        $mail['reply_to_name'] = $related_event['event_email_sender'];
                                        //$mail['reply_to_email'] = $related_event['Event']['event_email_address'];

                                        //Note: reply mail changed from event mail to reply email
                                        $mail['reply_to_email'] = ($related_event['reply_email']) ? $related_event['reply_email'] : $related_event['event_email_address'];
                                    }else if($related_event['reply_email']){
                                         $mail['reply_to_email'] = $related_event['reply_email'];
                                    }

                                    // ====== overwrite email header according to lang =====
                                    if($preferred_lang !='' && $preferred_lang !='english'){
                                        $mail['sender_name'] = $other_lang_email_header['from_name_'.$preferred_lang];
                                        $mail['reply_to_email'] = $other_lang_email_header['reply_email_'.$preferred_lang];
                                    }
									
                                    //echo "<pre>"; print_r($mail); exit;
									
									############ Company White Labelled code start here #####
									$mail['sender_email'] = '';
									$event_data = TableRegistry::getTableLocator()->get('Events')->find()->select(['company_id'])->where(['id'=>$this->currentSession->read('user.event.id')])->first(); 
									if(!empty($event_data)){
										$company= TableRegistry::getTableLocator()->get('Companies')->find()->select(['company_white_labelled_email','company_white_labelled_email_verification_status','company_white_labelled_email_dkim_verification_status'])->where(['Companies.id'=>$event_data['company_id']])->first();
										if(!empty($company) && !empty($company['company_white_labelled_email']) && $company['company_white_labelled_email_verification_status'] == '1' && $company['company_white_labelled_email_dkim_verification_status'] == '1'){
											$mail['sender_email'] = $company['company_white_labelled_email'];
										}
									}
									############ Company White Labelled code end here #####							
									$result = SesMail::sendMail($mail, 'event_frame');
                                    //if (EntMail::sendMail($mail, 'event_frame') !== false) {
                                    if ($result !== false) {
										$result = array_shift($result);
										/* Save welcome email into table 'welcome_email_reports' start */
										$welcomeEmailReportsTable = $this->getTableLocator()->get('WelcomeEmailReports');
										$welcomeEmailReport = $welcomeEmailReportsTable->newEmptyEntity();
										$welcomeEmailReport->user_id = $this->currentSession->read('user.id');;
										$welcomeEmailReport->recipient_id = $result['recipient_id'];
										$welcomeEmailReport->reg_id = $new_reg_id;
										$welcomeEmailReport->event_id = $this->currentSession->read('user.event.id');
										$welcomeEmailReport->email_content = $welcome_template_content['content'];
										$welcomeEmailReport->email_subject = $subject;
										//$welcomeEmailReport->message_id  = key($result);
										$welcomeEmailReport->message_id  = $result['message_id'];
										$welcomeEmailReport->email_to  = $result['email_to'];
										$welcomeEmailReport->sender_name  = $result['sender_name'];
										$welcomeEmailReport->sender_email  = $result['sender_email'];
										$welcomeEmailReport->reply_to_name  = $result['reply_to_name'];
										$welcomeEmailReport->reply_to_email  = $result['reply_to_email'];
										$welcomeEmailReport->mail_content_html  = $result['mail_content_html'];
										$welcomeEmailReport->status = 'sent';
										$welcomeEmailReport->created = date('Y-m-d H:i:s');
										$welcomeEmailReport->updated = date('Y-m-d H:i:s');
										$welcomeEmailReportsTable->save($welcomeEmailReport);
										
										
										
										
										
										/* Save welcome email into table 'welcome_email_reports' end */
										
                                        $emailEntry = array('user_id' => $user['id'],'reg_id' => $new_reg_id);
                                        $emailEntry['created'] = date('Y-m-d H:i:s');

                                        $test= TableRegistry::getTableLocator()->get('UserEmailRecords')->find()->where(['UserEmailRecords.user_id'=>$user['id'],'UserEmailRecords.reg_id' => $new_reg_id])->first();

                                        $entity_data=TableRegistry::getTableLocator()->get('UserEmailRecords')->newEntity($emailEntry);
										
										
                                        if($test){
                                            $entity_data->id = $test['id'];
                                        }
                                        
                                        TableRegistry::getTableLocator()->get('UserEmailRecords')->save($entity_data);

                                        //this is good, email sent
                                    } else {
                                        $warning = '<br/>Warning: Welcome email is not sent. Please check email templates.';
                                    }
                                } else {
                                    $warning = '<br/>Warning: Welcome email is not sent. Please check email templates.';
                                }
                            }

                            if (strlen($warning) > 0) {
                                
                                //$this->Session->setFlash('You successfully added a new exhibitor<br/>' . "<br/>" . $warning,'normal');

                                $this->Flash->success(__('You successfully added a new exhibitor, but welcome email could not be sent'));

                            } else {
                                
                                //$this->Session->setFlash('You successfully added a new exhibitor', 'success');
                                $this->Flash->success(__('You successfully added a new exhibitor'));
                            }
                            return $this->redirect(array('controller' => 'users', 'action' => 'editExhibitor', 'id'=> $new_reg_id));
                        } else { //failure
                            
                            //$this->Session->setFlash('Exhibitor could not be save, please try again', 'failure');
                            $this->Flash->error(__('Exhibitor could not be save, please try again'));

                            return $this->redirect(array('action' => $this->request->getParam('action'), 'step1'));
                        }
                    }
                } else {
                    $this->Flash->error(__('Invalid action'));
                    return $this->redirect(array('action' => $this->request->getParam('action'), 'step1'));
                }

            } elseif ($this->request->getData('email') !==null && strpos($this->request->getData('email'), '@') !== false) {

                $user = $this->Users->findByEmail(trim($this->request->getData('email')))->first();
                if ($user == null) {

                    $email = trim($this->request->getData('email'));
                    $this->redirect(array('controller' => $this->request->getParam('controller'), 'action' => 'addNewExhibitor', 'email'=> $email));
                    /*
                      $user['User']['email'] = $this->params['form']['email'];
                      $user['User']['user_type'] = "exhibitor";
                      $this->User->create();
                      if($this->User->save($user)){
                      $uid = $this->User->getLastInsertId();
                      //done
                      $reg['ExhibitionRegistration']['event_id'] = $this->Session->read('user.event.id');
                      $reg['ExhibitionRegistration']['user_id'] = $uid;
                      $reg['ExhibitionRegistration']['status'] = "new";
                      $this->ExhibitionRegistration->create();
                      $retry_counter = 0;
                      while(!$this->ExhibitionRegistration->save($reg) && $retry_counter<3){
                      $retry_counter++;
                      usleep(100000);   //retry after 0.1 second;
                      }
                      $this->redirect(array('controller'=>'users', 'action'=>'editExhibitor', $uid));
                      }else{
                      $this->Session->write('flash', array('The user could not be saved. Please, try again.','failure'));
                      $this->redirect(array('action'=>$this->params['action'], 'step1'));
                      } */
                } elseif ($user['user_type'] == 'admin' || $user['user_type'] == 'client') {
                    
                    $this->Flash->error(__('The email address you attempted to add belongs to an Organiser and cannot be used as an Exhibitor email address.  Please use a different email address.'));
                    return $this->redirect(array('action' => $this->request->getParam('action'), 'step1'));
                } else {
                    if($is_event_stand==0){
                        //$is_user_exhib = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.booth_no'=>"",'ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id'),'ExhibitionRegistrations.user_id'=>$user['id']])->first();
                        $is_user_exhib = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id'),'ExhibitionRegistrations.user_id'=>$user['id']])->first();
                        if($is_user_exhib){
                            $this->redirect(array('controller' => $this->request->getParam('controller'), 'action' => 'editExhibitor', 'id'=> $is_user_exhib['id']));
                        }
                    }
                    
                    //$events = TableRegistry::getTableLocator()->get('Events')->find('list')->toArray();
                    $compant_name=TableRegistry::getTableLocator()->get('Companies')->getCompanyByID($this_event['company_id']);             

                    $this->set('company_name',$compant_name['company_name']);
                    $this->set('user', $user);
                    
                    
                    // get default exhibitor type
                    $default_exhib_type=TableRegistry::getTableLocator()->get('EventExhibitorTypes')->find()->select(['EventExhibitorTypes.id'])->where(['EventExhibitorTypes.default_exhib_type'=>'1','EventExhibitorTypes.event_id'=>$this->currentSession->read('user.event.id')])->first();
                    $this->set('default_exhib_type', $default_exhib_type['id']);
                    
                    $custom_fields = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['RegistrationMetas.field_key','RegistrationMetas.short_code'])->where(['RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')])->order(['RegistrationMetas.meta_order'=>'ASC'])->toArray();
                    
                    $tmp= array();
                    $unique_custom_fields= array();
                    foreach($custom_fields as $field){
                        if(!in_array($field['field_key'], $tmp)){
                           $unique_custom_fields[]=$field;
                        }

                        $tmp[]= $field['field_key'];
                    }

                    $this->set('custom_fields', $unique_custom_fields);

                }
            } else {
                //$this->Session->write('flash', array('Invalid action', 'failure'));
                $this->Flash->error('Invalid email address. []():;\<,>" characters are not allowed on Email Address.');
                return $this->redirect(array('action' => $this->request->getParam('action'), 'step1'));
            }
        }

        $this->set('step', $step);
    }

    function deleteExhibitorRegistration($reg_id = null) {
	
        //Do not actual remove the user record, just remove the registration
        $reg=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.id' => $reg_id])->first();

        if (!$reg){
            $this->Flash->error(__('Invalid Registration'));
            return $this->redirect(array('action' => 'index'));
        }

        if(TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->deleteAll(['id'=>$reg['id']])){
            //Delete form entries..
            require_once(ROOT . DS . 'vendor' . DS  . 'ent_custom_form.php');
            $custom_form = new EntCustomForm();
            $custom_form->deleteEventRecordsByRegId($this->currentSession->read('user.event.id'),$reg_id);
            // delete feeds
            TableRegistry::getTableLocator()->get('Feeds')->deleteAll(array('Feeds.reg_id' => $reg_id));
            
            $this->Flash->success(__('User deleted'));
            return $this->redirect(array('action' => 'index'));
        }else{
           $this->Flash->error(__('User could not be deleted. Please try again'));
           return $this->redirect(array('action' => 'index'));
        }

        
    }

    function resendPassword($id = null) {
		
        if(!$id){
            $this->Flash->error(__('Invalid id for user'));
            return $this->redirect(array('action' => 'index'));
        }

        $user = $this->Users->findById($id)->first();
        if ($user == null) {
            $this->Flash->error(__('Invalid id for user'));
            return $this->redirect(array('action' => 'index'));
        }

        $newpassword = $this->Users->generate_password(8);
        // save new password
        $data= array();
        $data['password'] = $newpassword;
        $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($data);
        $entity_data->id= $user['id'];
        if(TableRegistry::getTableLocator()->get('Users')->save($entity_data)){
            //require_once(ROOT . DS . 'vendor' . DS  . 'entmail.php');
            require_once(ROOT . DS . 'vendor' . DS  . 'sesmail.php');
            $mail = array('subject' => 'Your XPOBAY Password',
                'mails' => array(
                    array('email' => $user['email'],
                        'firstname' => $user['firstname'],
                        'password' => $newpassword)
                )
            );
			
            //EntMail::sendMail($mail, 'password');
            //SesMail::sendMail($mail, 'password');
            //$this->Flash->success(__('New password has been sent to the user email'));
            return $this->redirect(array('action'=>'listClients'));
        }else{
            $this->Flash->error(__('New password could not be sent. Please try again'));
            return $this->redirect(array('action' => 'listClients'));
        }
    }

    function resendWelcome($id = null, $system = false, $edit = false){
		
        $html = new HtmlHelper(new \Cake\View\View());
        //require_once(ROOT . DS . 'vendor' . DS  . 'entmail.php');
        require_once(ROOT . DS . 'vendor' . DS  . 'sesmail.php');

        if (!$id) {
            $this->Flash->error(__('Invalid id for user'));
            $this->redirect(array('action' => 'index'));
        }

        $idx = explode('-', $id);
        $id = $idx[0];

        $user=TableRegistry::getTableLocator()->get('Users')->find()->where(['Users.id' => $id])->first();

        if(array_key_exists(1, $idx)){
            $reg_id = $idx[1];
        }else{
            $last= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.user_id' => $user['id'], 'ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id')])->first();
            $reg_id = $last['id'];
        }

        if($user == null){
            $this->Flash->error(__('Invalid id for user'));
            return $this->redirect(array('action' => 'index'));
        }

        
        if ($user['user_type'] == 'exhibitor') {
            $emailEntry = array('user_id' => $id,
                'reg_id' => $reg_id);
            
            $test = TableRegistry::getTableLocator()->get('UserEmailRecords')->find()->where(['UserEmailRecords.user_id'=>$id, 'UserEmailRecords.reg_id'=>$reg_id])->first();

            $emailEntry['created'] = date('Y-m-d H:i:s');

            $ent_data=TableRegistry::getTableLocator()->get('UserEmailRecords')->newEntity($emailEntry);
            if($test){
                $ent_data->id = $test['id'];
            }

            TableRegistry::getTableLocator()->get('UserEmailRecords')->save($ent_data);
            //exit;
        }


        $warning = "";
        // get welcome template
        $exhib=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.id' => $reg_id])->first();
        $preferred_lang= $exhib['preferred_language'];
        
        $welcome_template= 'event_welcome';
        if(empty($preferred_lang)){
            $welcome_template= 'event_welcome';
        }else if($preferred_lang=='english'){
            $welcome_template= 'event_welcome';
        }else{
            $welcome_template= 'event_welcome_'.$preferred_lang;
        }
        
        $welcome_template_content = SesMail::getTemplateContent($welcome_template);
		
        if(!$welcome_template_content){
           $welcome_template_content = SesMail::getTemplateContent('event_welcome'); 
        }
        
        if ($welcome_template_content !== false) {
			
            $related_event= TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $this->currentSession->read('user.event.id')])->first();

            $user_auto_login_url =  Router::url(['controller' => 'System','action' => 'login',$user['id'], base64_encode($user['password']), $this->currentSession->read('user.event.id'), $reg_id],true);
            //$user_auto_login_url= Router::url('/',true).'system/exhib_login?dest='.$this->currentSession->read('user.event.id')."/About-Your-Event";
            $user_login_link = $html->link('Click here to login', $user_auto_login_url);

            $user_password_reset_url =  Router::url(['controller' => 'System','action' => 'reset_password',$user['id'], base64_encode($user['password']),$related_event['id']],true);
            
            $user_password_reset_link="<a href=\"{$user_password_reset_url}\" target=\"_blank\">Set Password</a>";
            
            $other_lang_email_header= unserialize($related_event['other_lang_email_header']);
			if($welcome_template_content['type'] == 'default'){
				$defaultEmailTemplate =TableRegistry::getTableLocator()->get('DefaultTemplates')->find()->where(['DefaultTemplates.template_filename Like'=>'%event_welcome.tpl%'])->first();
				if(!empty($defaultEmailTemplate)){
					$subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$defaultEmailTemplate['subject']);
				}
			}else{	
			
				if($preferred_lang=='' || $preferred_lang=='english'){
					$subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$related_event['email_subject']);
				}else{
					$subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$other_lang_email_header['email_subject_'.$preferred_lang]);
				}
			}
            

            $mail = array('subject' => $subject,
                'mails' => array(
                    array(
                        //%%EMAIL_CONTENT%% needs to be the first element to be replaced
                        'EMAIL_CONTENT' => $welcome_template_content['content'],
                        'email' => $user['email'],
                        'EMAIL' => $user['email'],
                        'FIRST_NAME' => $user['firstname'],
                        'LAST_NAME' => $user['lastname'],
                        'FIRSTNAME' => $user['firstname'],
                        'LASTNAME'  => $user['lastname'],
                        'EVENT_NAME' => $related_event['name'],
                        'COMPANY_NAME' => $user['company_name'],
                        'EXTERNAL_USERNAME' =>$user['external_username'],
                        'EXTERNAL_PASSWORD' =>$user['external_password'],
                        'recipient_id'=>$user['id'],
                        'LOGIN_URL' => $user_auto_login_url,
                        'LOGIN_LINK' => $user_login_link,
                        'PASSWORD_RESET_URL' => $user_password_reset_url,
                        'PASSWORD_RESET_LINK' => $user_password_reset_link
                    )
                )
            );
            if (strlen($related_event['event_email_sender']) > 3 &&
                    strlen($related_event['event_email_address']) > 6 &&
                    strpos($related_event['event_email_address'], '@') !== false) {
                $mail['sender_name'] = $related_event['event_email_sender'];
                $mail['sender_email'] = $related_event['event_email_address'];
                $mail['reply_to_name'] = $related_event['event_email_sender'];
                //$mail['reply_to_email'] = $related_event['Event']['event_email_address'];

                //Note: reply mail changed from event mail to reply email
                $mail['reply_to_email'] = ($related_event['reply_email']) ? $related_event['reply_email'] : $related_event['event_email_address'];
            
            }else if($related_event['reply_email']){
                 $mail['reply_to_email'] = $related_event['reply_email'];
            } 

            if($related_event['event_email_sender']){
                $mail['sender_name'] = $related_event['event_email_sender'];
            }

            // ====== overwrite email header according to lang =====
            if($preferred_lang !='' && $preferred_lang !='english'){
                $mail['sender_name'] = $other_lang_email_header['from_name_'.$preferred_lang];
                $mail['reply_to_email'] = $other_lang_email_header['reply_email_'.$preferred_lang];
            }

            //echo "<pre>"; print_r($mail); exit;
            
            //if (EntMail::sendMail($mail, 'event_frame') !== false) {
			############ Company White Labelled code start here #####
			$mail['sender_email'] = '';
			$event_data = TableRegistry::getTableLocator()->get('Events')->find()->select(['company_id'])->where(['id'=>$this->currentSession->read('user.event.id')])->first(); 
			if(!empty($event_data)){
				$company= TableRegistry::getTableLocator()->get('Companies')->find()->select(['company_white_labelled_email','company_white_labelled_email_verification_status','company_white_labelled_email_dkim_verification_status'])->where(['Companies.id'=>$event_data['company_id']])->first();
				if(!empty($company) && !empty($company['company_white_labelled_email']) && $company['company_white_labelled_email_verification_status'] == '1' && $company['company_white_labelled_email_dkim_verification_status'] == '1'){
					$mail['sender_email'] = $company['company_white_labelled_email'];
				}
			}
			
			
			############ Company White Labelled code end here #####	
			$result = SesMail::sendMail($mail, 'event_frame',null,'welcome');
            if ($result !== false) {
				$result = array_shift($result);
				/* Save welcome email into table 'welcome_email_reports' start */
				$welcomeEmailReportsTable = $this->getTableLocator()->get('WelcomeEmailReports');
				$welcomeEmailReport = $welcomeEmailReportsTable->newEmptyEntity();
				$welcomeEmailReport->user_id = $this->currentSession->read('user.id');;
				$welcomeEmailReport->recipient_id = $result['recipient_id'];
				$welcomeEmailReport->reg_id = $reg_id;
				$welcomeEmailReport->event_id = $this->currentSession->read('user.event.id');
				$welcomeEmailReport->email_content = $welcome_template_content['content'];
				$welcomeEmailReport->email_subject = $subject;
				//$welcomeEmailReport->message_id  = key($result);
				$welcomeEmailReport->message_id  = $result['message_id'];
				$welcomeEmailReport->email_to  = $result['email_to'];
				$welcomeEmailReport->sender_name  = $result['sender_name'];
				$welcomeEmailReport->sender_email  = $result['sender_email'];
				$welcomeEmailReport->reply_to_name  = $result['reply_to_name'];
				$welcomeEmailReport->reply_to_email  = $result['reply_to_email'];
				$welcomeEmailReport->mail_content_html  = $result['mail_content_html'];
				$welcomeEmailReport->status = 'sent';
				$welcomeEmailReport->created = date('Y-m-d H:i:s');
				$welcomeEmailReport->updated = date('Y-m-d H:i:s');
				$welcomeEmailReportsTable->save($welcomeEmailReport);
				/* Save welcome email into table 'welcome_email_reports' end */
                //this is good, email sent
                if (!$system) {
                    if (!$edit)
                    $this->Flash->success(__('An Event Welcome email has been sent to the exhibitor'));
                    else
                    $this->Flash->success(__('User details have been updated and Event Welcome email has been resent to the user'));
                    return $this->redirect(array('action' => 'index'));
                }
            }else {
                $warning = 'Welcome email is not sent.';
            }
        } else {
            $warning = 'Welcome email is not sent. Please check email templates.';
        }

        if (!$system){
            //$this->Session->setFlash('Failure<br/>' . $warning);
            $this->Flash->error(__('Failure: ' . $warning));
            return $this->redirect(array('action' => 'index'));
        }
    }


    public function sendTemplateAjax(){
       $this->autoRender=false;
       $this->autoLayout=false;
       $this->layout='ajax';

       $html = new HtmlHelper(new \Cake\View\View());
       //require_once(ROOT . DS . 'vendor' . DS  . 'entmail.php');
       require_once(ROOT . DS . 'vendor' . DS  . 'sesmail.php');
       
       $data =$this->request->getData();
       
       $error= array();
       if(!$data['user_id']){
           $error['isError']= true;
           $error['msg']= 'Invalid user';
           exit;
       }
		
       if($data['email_template']=='Event Welcome'){
           //$welcome_template_content = EntMail::getTemplateContent('event_welcome');
           $welcome_template_content = SesMail::getTemplateContent('event_welcome');
           if($welcome_template_content !== false){
               $isSend=$this->sendEmailTemplate($data,$welcome_template_content,'event_welcome');
               if($isSend){
                  $error['isError']= false;
                  $error['msg']= 'Email template sent.';
               }else{
                  $error['isError']= true;
                  $error['msg']= 'Email configuration error';
               }
           }else{
              $error['isError']= true;
              $error['msg']= 'Email is not sent. Please check email templates';
              
           }
       }else if($data['email_template']=='Event Reminder'){
           $event_id= $this->currentSession->read('user.event.id');
           
           //$welcome_template_content = EntMail::getTemplateContent('event_reminder',$event_id);
           $welcome_template_content = SesMail::getTemplateContent('event_reminder',$event_id);
           if($welcome_template_content !== false){
              $isSend=$this->sendEmailTemplate($data,$welcome_template_content,'event_reminder');
              if($isSend){
                  $error['isError']= false;
                  $error['msg']= 'Email template sent.';
              }else{
                  $error['isError']= true;
                  $error['msg']= 'Email configuration error';
              }
           }else{
              $error['isError']= true;
              $error[]= 'Email is not sent. Please check email templates';
           }
           
       }else{
          $template= $this->CustomTemplate->find('first',array('conditions'=>array('CustomTemplate.id'=>$data['email_template'])));

          $template=TableRegistry::getTableLocator()->get('CustomTemplates')->find()->where(['CustomTemplates.id'=>$data['email_template']])->first();

          $welcome_template_content= $template['template'];
          $custom_subject= ($template['subject']) ? $template['subject'] : $template['template_name'];
          $isSend=$this->sendEmailTemplate($data,$welcome_template_content,'event_custom',$custom_subject);
          if($isSend){
             $error['isError']= false;
             $error['msg']= 'Email template sent.';
          }else{
             $error['isError']= true;
             $error['msg']= 'Email configuration error';
          }
        
       }
       
       echo json_encode($error);
       die();
        
    }

    public function sendEmailTemplate($data,$welcome_template_content,$type,$custom_subject=NULL){
		
		
		
        $html = new HtmlHelper(new \Cake\View\View());
        //require_once(ROOT . DS . 'vendor' . DS  . 'entmail.php');
        require_once(ROOT . DS . 'vendor' . DS  . 'sesmail.php');

        $user_id= $data['user_id'];
        $reg_id= $data['reg_id'];

        $user = TableRegistry::getTableLocator()->get('Users')->find()->where(['Users.id' => $user_id])->first();

        $related_event = TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $this->currentSession->read('user.event.id')])->first();

        $user_auto_login_url =  Router::url(['controller' => 'System','action' => 'login',$user['id'],base64_encode($user['password']), $this->currentSession->read('user.event.id'), $reg_id],true);
        //$user_auto_login_url= Router::url('/',true).'system/exhib_login?dest='.$this->currentSession->read('user.event.id')."/About-Your-Event";

        $user_login_link = $html->link('Click here to login', $user_auto_login_url);

        $user_password_reset_url =  Router::url(['controller' => 'System','action' => 'reset_password',$user['id'], base64_encode($user['password']),$related_event['id']],true);

        
        $user_password_reset_link="<a href=\"{$user_password_reset_url}\" target=\"_blank\">Set Password</a>";
        
        if($type=='event_welcome'){
			if($welcome_template_content['type'] == 'default'){
				$defaultEmailTemplate =TableRegistry::getTableLocator()->get('DefaultTemplates')->find()->where(['DefaultTemplates.template_filename Like'=>'%event_welcome.tpl%'])->first();
				if(!empty($defaultEmailTemplate)){
					$subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$defaultEmailTemplate['subject']); 
				}	
			}else{
				$subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$related_event['email_subject']); 
			}
        }

        $incompleted_forms='';
        if($type=='event_reminder'){
            $event_id= $this->currentSession->read('user.event.id');
            $reminder= TableRegistry::getTableLocator()->get('Reminders')->find()->where(['Reminders.event_id' => $event_id])->first();
			
			if($welcome_template_content['type'] == 'default'){
				$defaultEmailTemplate =TableRegistry::getTableLocator()->get('DefaultTemplates')->find()->where(['DefaultTemplates.template_filename Like'=>'%event_reminder.tpl%'])->first();
				if(!empty($defaultEmailTemplate)){
					$subject = $defaultEmailTemplate['subject'];
				}	
			}else{	
			
				if(empty($reminder)){
					$subject= $related_event['name'].": Compulsory forms reminder";
				}else{
					$subject= $reminder['subject'];
				}
            }
            // incompleted forms
            $forms= $this->getIncompletedForms($reg_id);
            
            foreach($forms as $form){
               $deadline= $form['deadline'];
               $deadline_str= '| Deadline: N/A';
               if($deadline){
                  $deadline_str='| Deadline: '.date('d M Y',strtotime($deadline));
               }

               $incompleted_forms.= '<p>'.$form['form_name'].' '.$deadline_str.'<p>';
            }

        } 

        if($type=='event_custom'){
            $subject= $custom_subject;
        }
        

        //echo $user_password_reset_url;
		
		if($type=='event_reminder' || $type=='event_welcome'){
			$email_content=$welcome_template_content['content'];
		}else{
			$email_content=$welcome_template_content;
		}		
		

        $mail = array('subject' => $subject,
                'mails' => array(
                    array(
                        //%%EMAIL_CONTENT%% needs to be the first element to be replaced
                        //'EMAIL_CONTENT' => $welcome_template_content,
                        'EMAIL_CONTENT' => $email_content,
                        'email' => $user['email'],
                        'EMAIL' => $user['email'],
                        'FIRST_NAME' => $user['firstname'],
                        'LAST_NAME' => $user['lastname'],
                        'FIRSTNAME' => $user['firstname'],
                        'LASTNAME'  => $user['lastname'],
                        'EVENT_NAME' => $related_event['name'],
                        'COMPANY_NAME' => $user['company_name'],
                        'EXTERNAL_USERNAME' =>$user['external_username'],
                        'EXTERNAL_PASSWORD' =>$user['external_password'],
                        'recipient_id'=>$user_id,
                        'LOGIN_URL' => $user_auto_login_url,
                        'LOGIN_LINK' => $user_login_link,
                        'PASSWORD_RESET_URL' => $user_password_reset_url,
                        'PASSWORD_RESET_LINK' => $user_password_reset_link,
                        'INCOMPLETED_FORMS' => $incompleted_forms
                    )
                )
            );
        $mail['sender_name'] = $data['from_name'];
        $mail['sender_email'] = $data['reply_email'];
        $mail['reply_to_name'] = $data['from_name'];
        $mail['reply_to_email'] = $data['reply_email'];
		
		############ Company White Labelled code start here #####
		$mail['sender_email'] = '';
		$event_data = TableRegistry::getTableLocator()->get('Events')->find()->select(['company_id'])->where(['id'=>$this->currentSession->read('user.event.id')])->first(); 
		if(!empty($event_data)){
			$company= TableRegistry::getTableLocator()->get('Companies')->find()->select(['company_white_labelled_email','company_white_labelled_email_verification_status','company_white_labelled_email_dkim_verification_status'])->where(['Companies.id'=>$event_data['company_id']])->first();
			if(!empty($company) && !empty($company['company_white_labelled_email']) && $company['company_white_labelled_email_verification_status'] == '1' && $company['company_white_labelled_email_dkim_verification_status'] == '1'){
				$mail['sender_email'] = $company['company_white_labelled_email'];
			}
		}
		############ Company White Labelled code end here #####
		
		
		
        //echo "<pre>"; print_r($mail); exit;
        //if(EntMail::sendMail($mail, 'event_frame') !== false){
        //if(SesMail::sendMail($mail, 'event_frame') !== false){
			
			
			
			
		$result = SesMail::sendMail($mail, 'event_frame',null,'email_template');
        if($result !== false){
			$result = array_shift($result);
			/* Save email template send report into table 'email_template_reports' start */
			$emailTemplateReportsTable = $this->getTableLocator()->get('EmailTemplateReports');
			$emailTemplateReport = $emailTemplateReportsTable->newEmptyEntity();
			$emailTemplateReport->user_id = $this->currentSession->read('user.id');
			//$emailTemplateReport->recipient_id = $user_id;
			$emailTemplateReport->recipient_id = $result['recipient_id'];
			$emailTemplateReport->reg_id = $reg_id;
			$emailTemplateReport->event_id = $this->currentSession->read('user.event.id');
			$emailTemplateReport->email_content = $email_content;
			$emailTemplateReport->email_subject = $subject;
			//$emailTemplateReport->message_id  = key($result);
			$emailTemplateReport->message_id  = $result['message_id'];
			$emailTemplateReport->email_to  = $result['email_to'];
			$emailTemplateReport->sender_name  = $result['sender_name'];
			$emailTemplateReport->sender_email  = $result['sender_email'];
			$emailTemplateReport->reply_to_name  = $result['reply_to_name'];
			$emailTemplateReport->reply_to_email  = $result['reply_to_email'];
			$emailTemplateReport->mail_content_html  = $result['mail_content_html'];
			$emailTemplateReport->status = 'sent';
			$emailTemplateReport->email_template_name = $data['email_template'];
			$emailTemplateReport->created = date('Y-m-d H:i:s');
			$emailTemplateReport->updated = date('Y-m-d H:i:s');
			$emailTemplateReportsTable->save($emailTemplateReport);
			/* Save email template sent into table 'email_template_reports' end */
           return true;
        }else{
           return false; 
        }
    }


    public function getIncompletedForms($reg_id){
        require_once(ROOT . DS . 'vendor' . DS  . 'ent_custom_form.php');
        $custom_form = new EntCustomForm();

        $event_id= $this->currentSession->read('user.event.id');
         $exhibitor=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.id' => $reg_id])->first();

        $forms=TableRegistry::getTableLocator()->get('ApForms')->find()->where(['ApForms.ent_event_id'=>$event_id,'ApForms.form_active'=>'1'])->order(['ApForms.form_name'=>'asc']);
        
        $incompleted_forms= array();
        foreach($forms as $each_form){
            $tmp = array();
            // check if applicable
            if($custom_form->checkPermission($each_form, $exhibitor)){
                $entry = $custom_form->getFormEntryByRegId($each_form['form_id'],$exhibitor['id']);
                if($entry === false){
                    // check here if event has few checked forms from reminder setting
                    $checked_form_ids=TableRegistry::getTableLocator()->get('Reminders')->find()->select(['checked_reminder_forms_id'])->where(['Reminders.event_id' => $event_id])->first();

                    if($checked_form_ids){
                       $form_id_arr= explode(",", $checked_form_ids['checked_reminder_forms_id']);
                       if(in_array($each_form['form_id'], $form_id_arr)){
                          $tmp['form_name'] = $each_form['form_name'];
                          $tmp['form_description'] = $each_form['form_description'];
                          $tmp['deadline'] = $each_form['ent_deadline'];
                          $tmp['form_entry'] = "0";   //not completed
                          $tmp['form_entry_created'] = null;
                          $tmp['form_entry_updated'] = null;
                       }
                    }else{
                        $tmp['form_name'] = $each_form['form_name'];
                        $tmp['form_description'] = $each_form['form_description'];
                        $tmp['deadline'] = $each_form['ent_deadline'];
                        $tmp['form_entry'] = "0";   //not completed
                        $tmp['form_entry_created'] = null;
                        $tmp['form_entry_updated'] = null;
                    }
                }
            }

            if(!empty($tmp)){
               $incompleted_forms[] = $tmp;
            }
        }

        return $incompleted_forms;
    }


    public function send_organiser_email(){
       if($this->request->is('post')){
          if(!empty($this->request->data)){
             $data= $this->request->data;
             $user = $this->User->findById($data['user_id']);
             if($user == null) {
                $this->Session->setFlash('Invalid id for user', 'failure');
                $this->redirect(array('action'=>'listClients'));
             }

             App::import('Helper', 'Html');
             $html = new HtmlHelper(new View(null));
             //App::import('Vendor', 'entmail');
             App::import('Vendor', 'sesmail');

             $warning = "";
             $template = $this->OrganiserTemplate->find('first',array('conditions'=>array('OrganiserTemplate.id'=>$data['template_id'])));

             $welcome_template_content= $template['OrganiserTemplate']['content'];

             if($welcome_template_content !== false){
                $subject= $template['OrganiserTemplate']['subject'];
                $mail = array('subject' => $subject,
                    'mails' => array(
                        array(
                            'EMAIL_CONTENT' => $welcome_template_content,
                            'email' => $user['User']['email'],
                            'EMAIL' => $user['User']['email'],
                            'FIRST_NAME' => $user['User']['firstname'],
                            'LAST_NAME' => $user['User']['lastname'],
                            'FIRSTNAME' => $user['User']['firstname'],
                            'LASTNAME'  => $user['User']['lastname']
                        )
                    )
                );

                if($template['OrganiserTemplate']['from_name']){
                   $mail['sender_name'] = $template['OrganiserTemplate']['from_name']; 
                   $mail['reply_to_name'] = $template['OrganiserTemplate']['from_name']; 
                }
                
                if($template['OrganiserTemplate']['reply_email']){
                   $mail['reply_to_email'] = $template['OrganiserTemplate']['reply_email']; 
                }
                

                //echo "<pre>"; print_r($mail); exit;
                $mail['sender_email']=SYSTEM_MAIL_SENDER_EMAIL;
				
                //if(EntMail::sendMail($mail, 'orgainser_frame') !== false) {
                if(SesMail::sendMail($mail, 'orgainser_frame') !== false) {
                    $this->Session->setFlash('An email has been sent to the organiser', 'success');
                    return $this->redirect(array('action'=>'listClients'));
                }

             }else{
                $warning = '<br/>Warning: Welcome email is not sent. Please check email templates.';
             }

          }
       }

       return $this->redirect(array('action'=>'listClients'));
    }


    public function resendAllWelcome(){
		
		
		
        $html = new HtmlHelper(new \Cake\View\View());
        //require_once(ROOT . DS . 'vendor' . DS  . 'entmail.php');
        require_once(ROOT . DS . 'vendor' . DS  . 'sesmail.php');
        $welcomeEmailReportsTable = $this->getTableLocator()->get('WelcomeEmailReports');
        $exhibitors =TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id'),'Users.user_type'=>'exhibitor'])->contain(['Users']);
        foreach($exhibitors as $exhib){

            if($exhib['user']['user_type']=='exhibitor'){
                $emailEntry = array();
                $emailEntry['user_id']=$exhib['user']['id'];
                $emailEntry['reg_id']=$exhib['id'];
                
                $test =TableRegistry::getTableLocator()->get('UserEmailRecords')->find()->where(['UserEmailRecords.user_id'=>$exhib['user']['id'],'UserEmailRecords.reg_id'=>$exhib['id']])->first();

                $emailEntry['created'] = date('Y-m-d H:i:s');
                $ent_data=TableRegistry::getTableLocator()->get('UserEmailRecords')->newEntity($emailEntry);
                if($test){
                   $ent_data->id = $test['id'];
                }
                //echo "<pre>"; print_r($ent_data);
                TableRegistry::getTableLocator()->get('UserEmailRecords')->save($ent_data);

            }

            $warning = "";
            // get welcome template
            $welcome_template= 'event_welcome';
            if(empty($exhib['preferred_language'])){
                $welcome_template= 'event_welcome';
            }else if($exhib['preferred_language']=='english'){
                $welcome_template= 'event_welcome';
            }else{
                $welcome_template= 'event_welcome_'.$exhib['preferred_language'];
            }

            //$welcome_template_content = EntMail::getTemplateContent($welcome_template);
            $welcome_template_content = SesMail::getTemplateContent($welcome_template);
            if(!$welcome_template_content){
               //$welcome_template_content = EntMail::getTemplateContent('event_welcome'); 
               $welcome_template_content = SesMail::getTemplateContent('event_welcome'); 
            }

            //var_dump($welcome_template_content); exit;

            if($welcome_template_content !== false){
                
                $related_event= TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $this->currentSession->read('user.event.id')])->first();

                $user_auto_login_url =  Router::url(['controller' => 'System','action' => 'login',$exhib['user']['id'], base64_encode($exhib['user']['password']), $this->currentSession->read('user.event.id'), $exhib['id']],true);
                //$user_auto_login_url= Router::url('/',true).'system/exhib_login?dest='.$this->currentSession->read('user.event.id')."/About-Your-Event";
                //echo $user_auto_login_url;exit;
                $user_login_link = $html->link('Click here to login', $user_auto_login_url);

                $user_password_reset_url =  Router::url(['controller' => 'System','action' => 'reset_password',$exhib['user']['id'], base64_encode($exhib['user']['password']),$related_event['id']],true);

                $user_password_reset_link = "<a href=\"{$user_password_reset_url}\" target=\"_blank\">Set Password</a>";
                
                $other_lang_email_header= unserialize($related_event['other_lang_email_header']);
				
				if($welcome_template_content['type'] == 'default'){
					$defaultEmailTemplate =TableRegistry::getTableLocator()->get('DefaultTemplates')->find()->where(['DefaultTemplates.template_filename Like'=>'%event_welcome.tpl%'])->first();
					if(!empty($defaultEmailTemplate)){
						$subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$defaultEmailTemplate['subject']);
					}
				}else{	
				
					if($exhib['preferred_language']=='' || $exhib['preferred_language']=='english'){
						$subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$related_event['email_subject']);
					}else{
						$subject= str_replace("%%EVENT_NAME%%",$related_event['name'],$other_lang_email_header['email_subject_'.$exhib['preferred_language']]);
					}
				}
                

                $mail = array('subject' => $subject,
                    'mails' => array(
                        array(
                            //%%EMAIL_CONTENT%% needs to be the first element to be replaced
                            'EMAIL_CONTENT' => $welcome_template_content['content'],
                            'email' => $exhib['user']['email'],
                            'EMAIL' => $exhib['user']['email'],
                            'FIRST_NAME' => $exhib['user']['firstname'],
                            'LAST_NAME' => $exhib['user']['lastname'],
                            'FIRSTNAME' => $exhib['user']['firstname'],
                            'LASTNAME'  => $exhib['user']['lastname'],
                            'EVENT_NAME' => $related_event['name'],
                            'COMPANY_NAME' => $exhib['user']['company_name'],
                            'LOGIN_URL' => $user_auto_login_url,
                            'LOGIN_LINK' => $user_login_link,
                            'PASSWORD_RESET_URL' => $user_password_reset_url,
                            'PASSWORD_RESET_LINK' => $user_password_reset_link,
                            'EXTERNAL_USERNAME' =>$exhib['user']['external_username'],
                            'EXTERNAL_PASSWORD' =>$exhib['user']['external_password'],
							'recipient_id'=>$exhib['user']['id'],
                        )
                    )
                );

               //echo "<pre>"; print_r($mail); exit;

               if(strlen($related_event['event_email_sender']) > 3 && strlen($related_event['event_email_address']) > 6 && strpos($related_event['event_email_address'], '@') !== false){
                    
                    $mail['sender_name'] = $related_event['event_email_sender'];
                    $mail['sender_email'] = $related_event['event_email_address'];
                    $mail['reply_to_name'] = $related_event['event_email_sender'];
                    $mail['reply_to_email'] = ($related_event['reply_email']) ? $related_event['reply_email'] : $related_event['event_email_address'];
            
                }else if($related_event['reply_email']){
                    $mail['reply_to_email'] = $related_event['reply_email'];
                } 

                if($related_event['event_email_sender']){
                   $mail['sender_name'] = $related_event['event_email_sender'];
                }

                // ====== overwrite email header according to lang =====
                if($exhib['preferred_language'] !='' && $exhib['preferred_language'] !='english'){
                    
                    $mail['sender_name'] = $other_lang_email_header['from_name_'.$exhib['preferred_language']];
                    $mail['reply_to_email'] = $other_lang_email_header['reply_email_'.$exhib['preferred_language']];

                }

				############ Company White Labelled code start here #####
				$mail['sender_email'] = '';
				$event_data = TableRegistry::getTableLocator()->get('Events')->find()->select(['company_id'])->where(['id'=>$this->currentSession->read('user.event.id')])->first(); 
				if(!empty($event_data)){
					$company= TableRegistry::getTableLocator()->get('Companies')->find()->select(['company_white_labelled_email','company_white_labelled_email_verification_status','company_white_labelled_email_dkim_verification_status'])->where(['Companies.id'=>$event_data['company_id']])->first();
					if(!empty($company) && !empty($company['company_white_labelled_email']) && $company['company_white_labelled_email_verification_status'] == '1' && $company['company_white_labelled_email_dkim_verification_status'] == '1'){
						$mail['sender_email'] = $company['company_white_labelled_email'];
					}
				}
				############ Company White Labelled code end here #####
                // send email
                //EntMail::sendMail($mail, 'event_frame');
                $result = SesMail::sendMail($mail, 'event_frame',null,'welcome');
				$result = array_shift($result);
				/* Save welcome email into table 'welcome_email_reports' start */
				$welcomeEmailReport = $welcomeEmailReportsTable->newEmptyEntity();
				$welcomeEmailReport->user_id = $this->currentSession->read('user.id');;
				//$welcomeEmailReport->recipient_id = $exhib['user']['id'];
				$welcomeEmailReport->recipient_id = $result['recipient_id'];
				$welcomeEmailReport->message_id  = $result['message_id'];
				$welcomeEmailReport->email_to  = $result['email_to'];
				$welcomeEmailReport->sender_name  = $result['sender_name'];
				$welcomeEmailReport->sender_email  = $result['sender_email'];
				$welcomeEmailReport->reply_to_name  = $result['reply_to_name'];
				$welcomeEmailReport->reply_to_email  = $result['reply_to_email'];
				$welcomeEmailReport->mail_content_html  = $result['mail_content_html'];
				$welcomeEmailReport->reg_id = $exhib['id'];
				$welcomeEmailReport->event_id = $this->currentSession->read('user.event.id');
				$welcomeEmailReport->email_content = $welcome_template_content['content'];
				$welcomeEmailReport->email_subject = $subject;
				//$welcomeEmailReport->message_id  = key($result);
				$welcomeEmailReport->status = 'sent';
				$welcomeEmailReport->created = date('Y-m-d H:i:s');
				$welcomeEmailReport->updated = date('Y-m-d H:i:s');
				$welcomeEmailReportsTable->save($welcomeEmailReport);
				/* Save welcome email into table 'welcome_email_reports' end */
                //this is good, email sent
            
            }else{

               $warning = 'Warning: Welcome email is not sent. Please check email templates.';
               $this->Flash->error(__($warning));
               $this->redirect(array('action' => 'index'));
            }
        }

        $this->Flash->success(__('Event Welcome email has been resent to all exhibitors'));
        return $this->redirect(array('action' => 'index'));
     
    }

    function downloadExhibImportTemplate(){	
       $event_id= $this->currentSession->read('user.event.id');

       /*$headers= array('First Name','Last Name','Email','Company','Mobile','Telephone','External Username','External Password','Street','City','State','Post Code','Country','Stand Name','Stand Number','[Update] Stand No','Stand Type','Stand Location','Stand Dimension','Exhibitor Type','Preferred Language');*/
       $headers= array('First Name','Last Name','Email','Company','Mobile','Telephone','External Username','External Password','Street','City','State','Post Code','Country','Stand Name','Stand Number','[Update] Stand No','User Categories','Stand Location','Stand Dimension','User Type','Preferred Language');
       
       $custom_fields = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['RegistrationMetas.field_key','RegistrationMetas.short_code'])->where(['RegistrationMetas.event_id'=>$event_id])->order(['RegistrationMetas.meta_order'=>'ASC']);

       $tmpx= array();
       $metas= array();
       foreach($custom_fields as $field){
           if(!in_array($field['field_key'], $tmpx)){
              $metas[]=$field;
           }

           $tmpx[]= $field['field_key'];
       }

       

       foreach($metas as $meta){
           $headers[]= "Custom_".$meta['field_key'];
       }

       $this->set('headers',$headers);
    }


    /*function downloadExhibImportTemplate(){	
       $event_id= $this->currentSession->read('user.event.id');
       $custom_fields = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['RegistrationMetas.field_key','RegistrationMetas.short_code'])->where(['RegistrationMetas.event_id'=>$event_id])->order(['RegistrationMetas.id'=>'ASC'])->toArray();

       $tmp= array();
       $metas= array();
       foreach($custom_fields as $field){
           if(!in_array($field['field_key'], $tmp)){
              $metas[]=$field;
           }

           $tmp[]= $field['field_key'];
       }

       $headers= array('First Name','Last Name','Email','Company','Mobile','Telephone','External Username','External Password','Street','City','State','Post Code','Country','Stand Name','Stand Number','[Update] Stand No','Stand Type','Stand Location','Stand Dimension','Exhibitor Type','Preferred Language');

       foreach($metas as $meta){
           $headers[]= "Custom_".$meta['field_key'];
       }

       //====================================================
        $pathtofolder= WWW_ROOT.'import_templates';
        if(!file_exists($pathtofolder)){
            mkdir($pathtofolder, 0777, true);
        }

		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->fromArray(
		        $headers,  // The data to set
		        NULL,        // Array values with this value will not be set
		        'A1'         //we want to set these values (default is A1)
		    );

		//$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$alphas = range('A', 'Z');
		foreach($alphas as $alpha){
			$spreadsheet->getActiveSheet()->getColumnDimension($alpha)->setAutoSize(true);
		}
		

		$writer = new Xlsx($spreadsheet);
		$xls_template= $pathtofolder . DS .'template_'.$event_id.'.xlsx';
		$writer->save($xls_template);

		// Force the download
		 $file = $xls_template;
		 if(file_exists($file)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.basename($file));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));
		    ob_clean();
		    flush();
		    readfile($file);
		}else{
			echo "Template not generated";
		}

		exit;
    }*/

    public function fixImportExhibCsvErrorAjax(){
        $this->autoRender=false;
        $this->autoLayout=false;
        $this->layout='ajax';

        
        $form_data= $this->request->getData();
        //print_r($form_data); exit;    
        $fixed_data= array();
        foreach($form_data as $csvkey=>$datas){
           foreach($datas as $data_key=>$data_val){
                if($data_val){
                   $fixed_data[$csvkey][$data_key]= $data_val;
                }
           }
        }

        //print_r($fixed_data); exit;

        $table = $this->currentSession->read('user.event.import.exhibitor');
        $table_content = $table['content']['content'];

        foreach($fixed_data as $key=>$fixed_val){
            $table['content']['content'][$key]=$fixed_val;
        }

        unset($table['processed_data']);
        $this->currentSession->delete('user.event.import.exhibitor');
        $this->currentSession->write('user.event.import.exhibitor', $table);
               
        $this->preprocessSavingData();

    }


    function importExhibitor(){
        ini_set('max_execution_time', '0');
        $step = 0;

        if($this->request->is('post')){
            if($this->request->getData() !==''){
               $step = $this->request->getData('step');
            }
        }

        if ($step == 0) {
            $this->currentSession->delete('user.event.import.exhibitor');
            $this->render("import_step_0");
            //echo "hello"; exit;
        } else if ($step == 1) {
            $this->import_step_1();
        } else if ($step == 2) {
            $this->import_step_2();
        } else if ($step == 3) {
            $this->import_step_3();
        }
    }

    function cancelImportExhibitor() {
        $this->currentSession->delete('user.event.import.exhibitor');
        $this->Flash->success('Exhibitor Import Cancelled');
        $this->redirect(array('action' => 'index'));
    }

    function import_step_3() {

        $current_event=TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $this->currentSession->read('user.event.id')])->first();
        $is_event_stand = $current_event['is_event_with_stands'];

        $is_send_welcome_email = false;
        if($this->request->getData('send_welcome_email')){
            $is_send_welcome_email = true;
        }

        $profile_update_list = array();
        if($this->request->getData('update_profile')){
             $profile_update_list = $this->request->getData('update_profile');

        }

        $table = $this->currentSession->read('user.event.import.exhibitor');
        $record_new_users = $table['processed_data']['new_users'];
        $record_existing_users = $table['processed_data']['existing_users'];
        
        //echo "<pre>"; print_r($record_new_users);
        //echo "<pre>"; print_r($record_existing_users); 
        //exit;
        
        $error_new_users = array();
        $error_exisitng_users = array();
        $emails = array();
        
        // insert new exhibitor
        foreach ($record_new_users as $idx => $each_record) {
            if(isset($each_record['reg']['view_exhibitor_type'])){
               unset($each_record['reg']['view_exhibitor_type']);
            }
            
            $save_user = $each_record['user'];
            $save_reg = $each_record['reg'];
            
            $record_new_users[$idx]['user_is_saved'] = false;
            $record_new_users[$idx]['reg_is_saved'] = false;

            if(in_array($save_user['email'], $emails)){
                // do not save user just do registraion
                $record_new_users[$idx]['user_is_saved'] = true;

                $current_user = $this->Users->findByEmail($save_user['email'])->first();
                $uid = $current_user['id'];

                $save_reg['user_id'] = $uid;
                $save_reg['uni_id'] = $this->currentSession->read('user.event.id').$uid;

                $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($save_reg);

                if(TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data)) {
                    $new_reg_id = $entity_data->id;
                    /*if(!is_numeric($each_record['reg']['booth_type_id'])){
                       // get booth type id
                        $booth=TableRegistry::getTableLocator()->get('EventBoothTypes')->find()->where(['EventBoothTypes.event_id'=>$this->currentSession->read('user.event.id'), 'EventBoothTypes.name'=>$each_record['reg']['booth_type_id']])->first();

                        //if empty booth
                        if(empty($booth)){
                           // insert new booth
                           $booth_arr= array();
                           $booth_arr['event_id']=$this->currentSession->read('user.event.id');
                           $booth_arr['name']=$each_record['reg']['booth_type_id'];
                           $booth_ent= TableRegistry::getTableLocator()->get('EventBoothTypes')->newEntity($booth_arr);
                           if(TableRegistry::getTableLocator()->get('EventBoothTypes')->save($booth_ent)){
                              $booth_id = $booth_ent->id;
                           }
                        }else{
                            $booth_id=$booth['id'];
                        }

                        $update_arr= array();
                        $update_arr['booth_type_id']=$booth_id;
                        $update_ent= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($update_arr);
                        $update_ent->id= $new_reg_id;
                        TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($update_ent);
                    }*/

                    //****** save custom field*********************
                    $this->saveUpdateCustomFields($each_record,$new_reg_id);

                    $this->salesforce_sync($new_reg_id);
                    //************************************************
                   
                    $record_new_users[$idx]['reg_is_saved'] = true;

                    $complex_uid_rid = $uid . '-' . $new_reg_id;

                    if ($is_send_welcome_email) {
                        $this->resendWelcome($complex_uid_rid, true);
                    }
                }
            }else{
                //save user
                $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($save_user);
                if(isset($save_user['id']) && $save_user['id'] !=''){
                    $entity_data->id= $save_user['id'];
                }
                //echo "<pre>"; print_r($entity_data); exit;
                if(TableRegistry::getTableLocator()->get('Users')->save($entity_data)){
                    $emails[] = $save_user['email'];
                    $record_new_users[$idx]['user_is_saved'] = true;
                    
                    $uid ='';
                    $uid=@$save_user['id'];
                    
                    if(empty($uid)){
                       $uid = $entity_data->id;
                    }

                    $save_reg['user_id'] = $uid;
                    $save_reg['uni_id'] = $this->currentSession->read('user.event.id').$uid;
                    $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($save_reg);
                    if(TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data)){
                        $new_reg_id = $entity_data->id;
                        
                        /*if(!is_numeric($each_record['reg']['booth_type_id'])){
                           // get booth type id
                            $booth=TableRegistry::getTableLocator()->get('EventBoothTypes')->find()->where(['EventBoothTypes.event_id'=>$this->currentSession->read('user.event.id'), 'EventBoothTypes.name'=>$each_record['reg']['booth_type_id']])->first();

                            if(empty($booth)){
                               // insert new booth
                               $booth_arr= array();
                               $booth_arr['event_id']=$this->currentSession->read('user.event.id');
                               $booth_arr['name']=$each_record['reg']['booth_type_id'];
                               $booth_ent= TableRegistry::getTableLocator()->get('EventBoothTypes')->newEntity($booth_arr);
                               if(TableRegistry::getTableLocator()->get('EventBoothTypes')->save($booth_ent)){
                                  $booth_id = $booth_ent->id;
                               }
                            }else{
                                $booth_id=$booth['id'];
                            }

                            $update_arr= array();
                            $update_arr['booth_type_id']=$booth_id;
                            $update_ent= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($update_arr);
                            $update_ent->id= $new_reg_id;
                            TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($update_ent);
                        }*/

                        //****** save custom field*********************
                        $this->saveUpdateCustomFields($each_record,$new_reg_id);

                        $this->salesforce_sync($new_reg_id);
                        //************************************************

                        $record_new_users[$idx]['reg_is_saved'] = true;

                        $complex_uid_rid = $uid . '-' . $new_reg_id;

                        if ($is_send_welcome_email) {
                            $this->resendWelcome($complex_uid_rid, true);
                        }
                    }
                }
                
            }

            if (!$record_new_users[$idx]['user_is_saved'] || !$record_new_users[$idx]['reg_is_saved']) {
                $error_new_users[] = $record_new_users[$idx];
            }
        }

        
        // update existing exhibitor
        foreach ($record_existing_users as $idx => $each_record) {
            $record_existing_users[$idx]['user_is_saved'] = true;
            $record_existing_users[$idx]['reg_is_saved'] = false;

            $uid = $each_record['existing_uid'];
            if(in_array($uid, $profile_update_list)) {
                $record_existing_users[$idx]['user_is_saved'] = false;

                $save_user = $each_record['user'];
                $save_user['id'] = $uid;
                $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($save_user);
                $entity_data->id= $uid;
                if(TableRegistry::getTableLocator()->get('Users')->save($entity_data)){
                    $record_existing_users[$idx]['user_is_saved'] = true;
                }
            }

            $save_reg = $each_record['reg'];
            $save_reg['user_id'] = $uid;

            $booth_type=TableRegistry::getTableLocator()->get('EventBoothTypes')->find()->where(['EventBoothTypes.event_id'=>$this->currentSession->read('user.event.id'),'EventBoothTypes.name'=>$save_reg['booth_type_id']])->first();

            //$save_reg['booth_type_id'] = $booth_type['id'];
        
            if (strlen(@$save_reg['update_booth_no']) > 0) {
                $booth_no = $save_reg['booth_no'];
                if($is_event_stand==0){
                    $booth_no = $each_record['existing_reg_data']['booth_no'];
                }
                $new_booth_no = $save_reg['update_booth_no'];

                $new_booth_no_count=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.event_id' => $this->currentSession->read('user.event.id'),'ExhibitionRegistrations.user_id <>' => $uid,'ExhibitionRegistrations.booth_no'=>$new_booth_no])->count();

                if ($new_booth_no_count == 0) {  //check new booth number is not used by others.

                    $existing_reg=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id'),'ExhibitionRegistrations.user_id'=>$uid,'ExhibitionRegistrations.booth_no' => $booth_no])->first();

                    if($existing_reg){
                        $existing_reg= $existing_reg->toArray();
                    }

                    unset($save_reg['update_booth_no']);
                    $save_reg['booth_no'] = $new_booth_no;

                    $save_reg_merge = array_merge($existing_reg, $save_reg);

                    $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($save_reg_merge);
                    if($existing_reg){
                        $entity_data->id=$existing_reg['id'];
                    }

                    if (TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data)){
                        $last_inserted_reg_id= $entity_data->id;
                        $record_existing_users[$idx]['reg_is_saved'] = true;

                        if ($is_send_welcome_email) { // welcome email will sent only new exhibitor
                            //$this->resendWelcome($uid, true);
                        }
                    }
                }
            } else {
                if($is_event_stand==1){
                    //echo "if";exit;
                    $booth_no = $save_reg['booth_no'];

                    $new_booth_no_count=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id'),'ExhibitionRegistrations.user_id <>'=>$uid,'ExhibitionRegistrations.booth_no'=>$booth_no])->count();

                    if ($new_booth_no_count == 0) { //check new booth number is not used by others.
                        
                        $existing_reg=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id'),'ExhibitionRegistrations.user_id'=>$uid,'ExhibitionRegistrations.booth_no' => $booth_no])->first();

                        $entity_data = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($save_reg);

                        if($existing_reg){
                            $save_reg['id'] = $existing_reg['id'];
                            $entity_data->id= $existing_reg['id'];
                        }

                        if(TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data)){
                            $last_inserted_reg_id= $entity_data->id;
                            $record_existing_users[$idx]['reg_is_saved'] = true;

                            if ($is_send_welcome_email) {  // welcome email will sent only new exhibitor
                                //$this->resendWelcome($uid, true);
                            }
                            // 1- check if already custom field for this event---
                            $metas= $this->getEventUniqueMeta();
                            foreach($metas as $meta){
                                  //get meta order and field display
                                  $singlemeta= TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id'),'RegistrationMetas.field_key'=>$meta['field_key'],'RegistrationMetas.short_code'=>$meta['short_code'],'RegistrationMetas.reg_id'=>$last_inserted_reg_id])->first();
                                  if(empty($singlemeta)){
                                      $reg_meta= array();
                                      $reg_meta['reg_id']= $last_inserted_reg_id;
                                      $reg_meta['event_id']= $this->currentSession->read('user.event.id');
                                      $reg_meta['field_key']= $meta['field_key'];
                                      $reg_meta['short_code']= $meta['short_code'];
                                      $reg_meta['meta_order']= $singlemeta['meta_order'];
                                      $reg_meta['field_display']= ($singlemeta['field_display']) ? $singlemeta['field_display'] : '0';
                                      $entity_data= TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($reg_meta);
                                      TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
                                  }
                            }
                        }
                    }
                }else{
                    //echo $this->currentSession->read('user.event.id');exit;
                    $existing_reg=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id'),'ExhibitionRegistrations.user_id'=>$uid,'OR'=>array('ExhibitionRegistrations.booth_no IS' => null,'ExhibitionRegistrations.booth_no ='=>'','ExhibitionRegistrations.booth_no ='=>$each_record['existing_reg_data']['booth_no'])])->first();
                    $save_reg['booth_no']=$save_reg['booth_no'] ? $save_reg['booth_no'] : "";
                        $entity_data = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($save_reg);

                        if($existing_reg){
                            $save_reg['id'] = $existing_reg['id'];
                            $entity_data->id= $existing_reg['id'];
                        }

                        if(TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data)){
                            $last_inserted_reg_id= $entity_data->id;
                            $record_existing_users[$idx]['reg_is_saved'] = true;

                            if ($is_send_welcome_email) {  // welcome email will sent only new exhibitor
                                //$this->resendWelcome($uid, true);
                            }
                        }
                        
                }
            }

            // *******save or update custom field********
            if(!empty($each_record['custom'])){
                foreach($each_record['custom'] as $keyfield=>$keyvalue){
                    $exp_key= explode("_", $keyfield);
                    if($exp_key[1]){
                       // check key field already exist
                       $last_inserted_reg_id_c = ($last_inserted_reg_id) ? $last_inserted_reg_id : $save_reg['id'];
                       
                       $meta_id_arr=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.reg_id'=>$last_inserted_reg_id_c,'RegistrationMetas.field_key'=>$exp_key[1],'RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')])->first();
                       $meta_id=$meta_id_arr['id'];

                       if(!empty($meta_id)){ // only update value
                          $reg_meta= array();
                          $reg_meta['value']=$keyvalue;
                          $entity_data= TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($reg_meta);
                          $entity_data->id=$meta_id;
                          TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
                        }else{
                           $exhibs= $this->getAllExhibEvent();
                           foreach($exhibs as $exhib){
                              //$meta_id= $this->RegistrationMeta->field('id',array('RegistrationMeta.reg_id'=>$last_inserted_reg_id_c,'RegistrationMeta.field_key'=>$exp_key[1],'RegistrationMeta.event_id'=>$this->Session->read('user.event.id')));
                              $meta_id= TableRegistry::getTableLocator()->get('RegistrationMetas')->field('id',array('RegistrationMetas.reg_id'=>$exhib['id'],'RegistrationMetas.field_key'=>$exp_key[1],'RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')));
                              if(empty($meta_id)){
                                $field_key = $exp_key[1];
                                $file_custom_field = $this->currentSession->read('user.event.import.customfield');
                                foreach($file_custom_field as $actualcustomfield){
                                    $field_arr = explode('_', $actualcustomfield);
                                    if(strtolower($field_key)==strtolower($field_arr[1])){
                                        $field_key = $field_arr[1];
                                    }
                                }
                                  $reg_meta=array();
                                  //$reg_meta['reg_id']= $last_inserted_reg_id_c;
                                  $reg_meta['reg_id']= $exhib['id'];
                                  $reg_meta['event_id']=$this->currentSession->read('user.event.id');
                                  //$reg_meta['field_key']=$exp_key[1];
                                  $reg_meta['field_key']=$field_key;
                                  $reg_meta['value']= ($exhib['id']==$last_inserted_reg_id_c) ? $keyvalue : '';
                                  $reg_meta['short_code']= "[CUSTOM_".str_replace(" ", "-", $exp_key[1])."]";
                                  $entity_data= TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($reg_meta);
                                  TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
                              }
                           } 
                        }


                    }
                    
                }
                
            }
            
            
            
            $last_inserted_reg_id = ($last_inserted_reg_id) ? $last_inserted_reg_id : $save_reg['id'];
            $this->salesforce_sync($last_inserted_reg_id);
            //************************************************


            if (!$record_existing_users[$idx]['user_is_saved'] || !$record_existing_users[$idx]['reg_is_saved']) {
                $error_exisitng_users[] = $record_existing_users[$idx];
            }
        }


        //$this->Session->delete('user.event.import.exhibitor');
        $this->set('record_new_users', $record_new_users);
        $this->set('record_existing_users', $record_existing_users);
        $this->set('error_new_users', $error_new_users);
        $this->set('error_exisitng_users', $error_exisitng_users);
        $this->set('is_send_welcome_email', $is_send_welcome_email);
        
        //======== view purpose only =======================
        $view_record_new_users = $table['processed_data']['view_new_users'];
        $view_record_existing_users = $table['processed_data']['view_existing_users'];
        $dupes_warning = $table['processed_data']['dupes_warning'];

        $this->set('view_record_new_users', $view_record_new_users);
        $this->set('view_record_existing_users', $view_record_existing_users);
        //==================================================

        $this->currentSession->delete('user.event.import.exhibitor');
        if($dupes_warning){
            $this->Flash->success($dupes_warning);
        }
        $this->render('import_step_final');
    }

    function getEventUniqueMeta(){
       $custom_fields = TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->select(['RegistrationMetas.field_key','RegistrationMetas.short_code'])->where(['RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')])->order(['RegistrationMetas.id'=>'ASC'])->toArray();

        $tmp= array();
        $metas= array();
        foreach($custom_fields as $field){
            if(!in_array($field['field_key'], $tmp)){
               $metas[]=$field;
            }

            $tmp[]= $field['field_key'];
        }

        return $metas;
    }

    function saveUpdateCustomFields($each_record,$new_reg_id){
        //****** save custom field*********************
        // 1- check if already custom field for this event---
        $metas= $this->getEventUniqueMeta();

        foreach($metas as $meta){
              //get meta order and field display
              $singlemeta= TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id'),'RegistrationMetas.field_key'=>$meta['field_key'],'RegistrationMetas.short_code'=>$meta['short_code']])->first();

              $reg_meta= array();
              $reg_meta['reg_id']= $new_reg_id;
              $reg_meta['event_id']= $this->currentSession->read('user.event.id');
              $reg_meta['field_key']= $meta['field_key'];
              $reg_meta['short_code']= $meta['short_code'];
              $reg_meta['meta_order']= $singlemeta['meta_order'];
              $reg_meta['field_display']= $singlemeta['field_display'];
              $entity_data= TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($reg_meta);
              TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
        }

        // condition 2- if import file have custom field
        if(!empty($each_record['custom'])){
            foreach($each_record['custom'] as $keyfield=>$keyvalue){
                $exp_key= explode("_", $keyfield);
                if($exp_key[1]){
                    // check key field already exist
                    $meta_id_arr=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.reg_id'=>$new_reg_id,'RegistrationMetas.field_key'=>$exp_key[1],'RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')])->first();
                    $meta_id=$meta_id_arr['id'];

                    if(!empty($meta_id)){ // only update value
                        $reg_meta= array();
                        $reg_meta['value']=$keyvalue;
                        $entity_data= TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($reg_meta);
                        $entity_data->id=$meta_id;
                        TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
                    }else{
                        // new custom field need to be saved for all exhib
                        $exhibs= $this->getAllExhibEvent();
                        $field_key = $exp_key[1];
                        $file_custom_field = $this->currentSession->read('user.event.import.customfield');
                        foreach($file_custom_field as $actualcustomfield){
                            $field_arr = explode('_', $actualcustomfield);
                            if(strtolower($field_key)==strtolower($field_arr[1])){
                                $field_key = $field_arr[1];
                            }
                        }
                        foreach($exhibs as $exhib){
                            $reg_meta=array();
                            $reg_meta['reg_id']= $exhib['id'];
                            $reg_meta['event_id']=$this->currentSession->read('user.event.id');
                            //$reg_meta['field_key']=$exp_key[1];
                            $reg_meta['field_key']=$field_key;
                            $reg_meta['value']= ($exhib['id']==$new_reg_id) ? $keyvalue : '';
                            $reg_meta['short_code']= "[CUSTOM_".str_replace(" ", "-", $exp_key[1])."]";
                            $entity_data= TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($reg_meta);
                            TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
                        }
                    }
                }
                
            }
            
        }

    }
    //function to save all missing custom fields
    public function saveMissingCustomMetaForAllUsers(){
        $exhibs= $this->getAllExhibEvent();
        $metas= $this->getEventUniqueMeta();
        $missing_meta_data = array();
        $i=0;
        foreach($exhibs as $exhib){
            
            foreach($metas as $meta){
                $meta_id_arr=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.reg_id'=>$exhib['id'],'RegistrationMetas.field_key'=>$meta['field_key'],'RegistrationMetas.event_id'=>$this->currentSession->read('user.event.id')])->first();
                if(empty($meta_id_arr)){
                    $missing_meta_data[$i]['meta']=$meta['field_key'];
                    $missing_meta_data[$i]['reg_id']=$exhib['id'];
                    $reg_meta=array();
                    $reg_meta['reg_id']= $exhib['id'];
                    $reg_meta['event_id']=$this->currentSession->read('user.event.id');
                    $reg_meta['field_key']=$meta['field_key'];
                    $reg_meta['value']= '';
                    $reg_meta['short_code']= "[CUSTOM_".str_replace(" ", "-", $meta['field_key'])."]";
                    $entity_data= TableRegistry::getTableLocator()->get('RegistrationMetas')->newEntity($reg_meta);
                    TableRegistry::getTableLocator()->get('RegistrationMetas')->save($entity_data);
                
                    $i++;
                }
            }
        }
    }


    function getAllExhibEvent(){
        $condition= array();
        $condition['ExhibitionRegistrations.event_id']=$this->currentSession->read('user.event.id');
        $condition['Users.user_type']='exhibitor';

        $exhibs = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($condition)->contain(['Users'])->toArray();
        return $exhibs;
    }

    function checkDuplicateExhibitor(){
        $condition= array();
        $condition['ExhibitionRegistrations.event_id']=$this->currentSession->read('user.event.id');
        $condition['Users.user_type']='exhibitor';
        /*$condition['group'] = array('user_id');
        $condition['having'] = 'count(*)>=2';*/

        $exhibs = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($condition)->group('ExhibitionRegistrations.user_id')->having(['count(*) >' => 1])->contain(['Users'])->toArray();
        $exhib_data=array();
        $exhib_data['total_count'] = count($exhibs);
        foreach($exhibs as $exhib){
            $exhib_data[$exhib['user_id']]=$exhib['user']['email'];
        }

        echo json_encode($exhib_data);
        die();
    }
    function checkDuplicateStand(){
        $condition= array();
        $condition['ExhibitionRegistrations.event_id']=$this->currentSession->read('user.event.id');
        $condition['Users.user_type']='exhibitor';
        /*$condition['group'] = array('user_id');
        $condition['having'] = 'count(*)>=2';*/

        $exhibs = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($condition)->group('ExhibitionRegistrations.booth_no')->having(['count(*) >' => 1])->contain(['Users'])->toArray();
        $exhib_data=array();
        $exhib_data['total_count'] = count($exhibs);
        foreach($exhibs as $exhib){
            $exhib_data[$exhib['user_id']]=$exhib['user']['email'];
        }
        $condition['OR']=array('ExhibitionRegistrations.booth_no IS' => null,'ExhibitionRegistrations.booth_no ='=>'');
        $empty_stand_exhibs = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($condition)->contain(['Users'])->toArray();
        $exhib_data['total_empty_count'] = count($empty_stand_exhibs);
        echo json_encode($exhib_data);
        die();
    }

    function import_step_1() {
        $support_mimes = array('application/x-msexcel', 'application/vnd.ms-excel', 'application/msexcel',
            'application/x-ms-excel', 'application/x-excel', 'application/xls','application/octet-stream','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        $files = $_FILES;
        //echo $files['upload_file']['type']; exit;
        
        if (!is_uploaded_file($files['upload_file']['tmp_name'])) {
            //$this->Session->write('flash', array('File upload error', 'failure'));

            $files['upload_file']['tmp_name'];            
            $this->Flash->error('File upload error');
            $this->render("import_step_0");
        } else if (!in_array($files['upload_file']['type'], $support_mimes)) {
            $this->Flash->error('File reading error. Supported Format: xls');
            $this->render("import_step_0");
        } else {
            $filename = $files['upload_file']['tmp_name'];
            $table = $this->readImportFile($filename);

            if (!$table) {
                $this->Flash->error('File reading error. Please check file');
                $this->render("import_step_0");
            } else {
                $table_info = $this->tableAnalysis($table);
                $table_data_header = $table['content'][0];
                $table_custom_field_heading = array();
                foreach ($table_data_header as $key => $value) {
                    $exp_col=explode('_', $value);
                    if(strtolower($exp_col[0])=='custom'){
                        $table_custom_field_heading[$key]=$value;
                    }
                }
                $this->currentSession->write('user.event.import.customfield', $table_custom_field_heading);
                //echo "<pre>"; print_r($table_info);exit;
                $this->currentSession->write('user.event.import.exhibitor', $table_info);
                $this->import_render(1);
            }
        }
    }


    function import_render($step){
        $this->set('table_info', $this->currentSession->read('user.event.import.exhibitor'));
        //==== for help hero =========
        if($step==2){
            $table_info= $this->currentSession->read('user.event.import.exhibitor');
            //echo "<pre>"; print_r($table_info); exit;
            $record_errors = $table_info['processed_data']['errors'];
            $record_existing_users = $table_info['processed_data']['existing_users'];
            $record_new_users = $table_info['processed_data']['new_users'];
           
            $this->set('import_error',(count($record_errors)>0) ? 'yes' : 'no');
            $this->set('import_existing_user',(count($record_existing_users)>0) ? 'yes' : 'no');
            $this->set('import_new_user',(count($record_new_users)>0) ? 'yes' : 'no');

            $this->set('import_step',$step);
        }
        
        //=== end help here ==========
        $this->render("import_step_" . $step);
    }

    

    function import_step_2() {
        /** check submit values * */
        $current_event=TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $this->currentSession->read('user.event.id')])->first();
        $is_event_stand = $current_event['is_event_with_stands'];
        $this->set('is_event_stand',$is_event_stand);
        $import_field_err = array();    

        $request_data= $this->request->getData();  

        if (!in_array('email', @$request_data['fields'])) {
            $import_field_err[] = "Email";
        }

        if (!in_array('event_exhibitor_types', @$request_data['fields'])) {
            /*$import_field_err[] = "Exhibitor type";*/
            $import_field_err[] = "User type";
        }

        if (!in_array('booth_type_id', @$request_data['fields'])) {
            /*$import_field_err[] = "Stand Type";*/
            $import_field_err[] = "User Category";
        }

        if($is_event_stand==1){
            if (!in_array('booth_no', @$request_data['fields'])) {
                $import_field_err[] = "Booth No";
            }
        }

        //echo "<pre>"; print_r($request_data['fields']); exit;

        if (count($import_field_err) > 0) {
            $tmp_msg = implode(", ", $import_field_err);
            $this->Flash->error("Missing Required Field: " . $tmp_msg);
            $this->import_render(1);

        } else {

            // check for duplicate header
            $dup_fields = array();
            $field_ct = array();
            foreach ($request_data['fields'] as $key => $value) {
                if (strlen(trim($value)) > 0) {
                    if (isset($field_ct[$value])) {
                        $dup_fields[] = $value;
                        $field_ct[$value]++;
                    } else {
                        $field_ct[$value] = 1;
                    }
                }
            }

            

            if (count($dup_fields) > 0) {
                
                $err_msg = "Found duplicated field. Please review your selection. ";
                $err_msg .= " Dupilicated Field:";
                $err_msg .= implode(", ", $dup_fields);
                $this->Flash->error($err_msg);
                $this->import_render(1);
            } else {
                
                //===== check for duplicate stand no in csv ======
                $csv_headers= $request_data['fields'];
                $csv_data= $this->currentSession->read('user.event.import.exhibitor');
                $csv_content= $csv_data['content']['content'];
                // get header key for (stand no)
                foreach($csv_headers as $boothkey=>$header){
                    if($header=='booth_no'){
                        $booth_no_key=$boothkey;
                    }
                }
                
                $csv_booth_arr=array();
                //if($is_event_stand==1){
                    foreach($csv_content as $content_value){
                        $csv_booth_arr[]=$content_value[$booth_no_key];
                    }
                //}
                
                //print_r($csv_booth_arr);
                $duplicates=array();
                $duplicates=array_unique(array_diff_assoc($csv_booth_arr, array_unique($csv_booth_arr)));
                //echo "<pre>"; print_r($duplicates); exit;
                if(!empty($duplicates)){
                   /*$dup_err_msg = "Your import file contains the following duplicate Stand Number(s): ";
                   $dup_err_msg .= implode(", ", $duplicates);
                   $dup_err_msg .= " Please ensure that your import file does not contain duplicate Stand Numbers and try importing again.";
                   $this->Flash->error($dup_err_msg);
                   $this->redirect(array('action'=>'import_exhibitor'));
                   return;*/
                   $dup_war_msg="";
                   if($is_event_stand==1){
                       $dup_err_msg = "Your import file contains the following duplicate Stand Number(s): ";
                       $dup_err_msg .= implode(", ", $duplicates);
                       $dup_err_msg .= ". Please ensure that your import file does not contain duplicate Stand Numbers and try importing again.";
                       $this->Flash->error($dup_err_msg);
                       $this->redirect(array('action'=>'import_exhibitor'));
                       return;
                    }else{  
                       $dup_war_msg = "You have imported the following duplicate Stand Number(s): ";
                       $dup_war_msg .= implode(", ", $duplicates);
                       $dup_war_msg .= ". <br>Please update these manually if required.";
                    }
                    
                }
                
                //==######### end stand check ######################

                //=========check for duplicate external user and pass=======
                 $external_username_key='';
                 $external_password_key='';
                 $external_email_key='';
                 
                 // get header key for (external user and pass)
                 foreach($csv_headers as $exkey=>$header){
                    if($header=='external_username'){
                        $external_username_key=$exkey;
                    }

                    if($header=='external_password'){
                        $external_password_key=$exkey;
                    }

                    if($header=='email'){
                        $external_email_key=$exkey;
                    }
                 }

                 
                 $externals=array();
                 foreach($csv_content as $content_value){
                    $ext_arr=array();
                    $ext_arr['username']=@$content_value[$external_username_key];
                    $ext_arr['password']=@$content_value[$external_password_key];
                    $ext_arr['email']=@$content_value[$external_email_key];
                    $externals[]=$ext_arr;
                 }
                 

                 //echo "<pre>"; print_r($externals); exit;
                 $duplicate_data = array();
                 foreach ($externals as $key=>$mainarray){
                    
                    foreach($externals as $rowkey=> $row_result){
                        if($key != $rowkey && $mainarray['email'] !=$row_result['email'] && $mainarray['username'] !=''){
                            
                            if($mainarray['username']==$row_result['username'] && $mainarray['password']==$row_result['password']){
                               $duplicate_data[]=$mainarray['email']; 
                            }
                        }
                    } 
                 }

                 $duplicate_data= array_unique($duplicate_data);
                 
                 if(!empty($duplicate_data)){
                    $dup_err_msg = "Your import file contains duplicate external username & password on following Eamil Address(es): ";
                    $dup_err_msg .= implode(", ", $duplicate_data);
                    $dup_err_msg .= ". Please ensure that your import file does not contain duplicate External username,Password and try importing again.";
                    $this->Flash->error($dup_err_msg);

                    $this->redirect(array('action'=>'import_exhibitor'));
                    return;
                 }
                
                // ========== check avaialability of language ==================
                
                foreach($csv_headers as $langkey=>$header){
                    if($header=='preferred_language'){
                        $preferred_lang_key=$langkey;
                    }
                } 

                $avail_langs= $this->langArr();
                $avail_lang_arr= array();
                foreach($avail_langs as $lang){
                   $avail_lang_arr[]= $lang;
                }
                //print_r($avail_lang_arr); exit;
                foreach($csv_content as $content_value){
                    $csv_lang = @$content_value[$preferred_lang_key];
                    if($csv_lang){
                       if(!in_array($csv_lang, $avail_lang_arr)){
                            $lang_err = "System is supporting following language:<br>";
                            $lang_err .= implode(", ", $avail_lang_arr);
                            $lang_err .= "<br>Please ensure that your import file contain available language only and try importing again.";
                            $this->Flash->error($lang_err);
                            $this->redirect(array('action'=>'import_exhibitor'));
                            return;
                            
                        } 
                    }
                    

                }
                // ========== end check avaialability of language ============== 

                /* merge data with confirmed headers */
                $tmp_data = $this->currentSession->read('user.event.import.exhibitor');
                $tmp_data['processed_data']['dupes_warning'] = @$dup_war_msg;
                $tmp_data['content']['header'] = $request_data['fields'];
                $this->currentSession->write('user.event.import.exhibitor', $tmp_data);
                //echo "<pre>"; print_r($this->currentSession->read('user.event.import.exhibitor')); exit;
                $this->preprocessSavingData();
                $this->import_render(2);

            }
        }
    }

    function preprocessSavingData() {
        
        $table = $this->currentSession->read('user.event.import.exhibitor');
        
        $table_column = $table['content']['header'];
        $table_content = $table['content']['content'];
        $dupes_warning = $table['processed_data']['dupes_warning'];

        //echo "<pre>"; print_r($table); print_r($table_content); exit;

        $record_input_errors = array();
        $record_existing_users = array();
        $record_new_users = array();

        $tmp_records = $this->buildSavingRecords();
        $current_event=TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $this->currentSession->read('user.event.id')])->first();
        $is_event_stand = $current_event['is_event_with_stands'];
        //$existing_booth_numbers = $this->ExhibitionRegistration->find('list', array('fields' => array('ExhibitionRegistration.booth_no', 'ExhibitionRegistration.user_id'),'conditions' => array('ExhibitionRegistration.booth_no !='=>'','event_id' => $this->Session->read('user.event.id'))));

        $query = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find('list', ['keyField'=>'booth_no','valueField'=>'user_id'])->where(['booth_no !='=>'','event_id'=>$this->currentSession->read('user.event.id')]);
        $existing_booth_numbers= array();
        if($query){
           $existing_booth_numbers= $query->toArray();
        }

        foreach ($tmp_records as $idx => $each_record) {
            //================check boothno rule start===================
            //$is_update_booth_no = false;
            if($is_event_stand!=0){
                if ($each_record['existing_uid']) {  //for existing users
                    
                    if (isset($each_record['reg']['update_booth_no']) && strlen($each_record['reg']['update_booth_no']) > 0 && strcmp($each_record['reg']['update_booth_no'], $each_record['reg']['booth_no']) != 0) { //if update from an old value to a new value
                        //$is_update_booth_no = true;

                        if (array_key_exists($each_record['reg']['booth_no'], $existing_booth_numbers)
                                && $existing_booth_numbers[$each_record['reg']['booth_no']] != $each_record['existing_uid']) {
                            
                            $tmp_records[$idx]['error_messages'][] = 'The given stand number is belongs to others';
                        } else if (strlen($each_record['reg']['update_booth_no']) > 0
                                && array_key_exists($each_record['reg']['update_booth_no'], $existing_booth_numbers)) {
                            $tmp_records[$idx]['error_messages'][] = 'The update stand number is already taken';
                        }
                    } else if (isset($each_record['reg']['update_booth_no'])
                            && strlen($each_record['reg']['update_booth_no']) > 0
                            && strlen(trim($each_record['reg']['booth_no'])) < 1) { //should never reach this
                        $tmp_records[$idx]['error_messages'][] = 'Can not update stand number without given existing stand number';
                    } else if (array_key_exists($each_record['reg']['booth_no'], $existing_booth_numbers) && $existing_booth_numbers[$each_record['reg']['booth_no']] != $each_record['existing_uid']){
                        
                        $tmp_records[$idx]['error_messages'][] = 'The stand number is already taken';
                    }
                } else if (count($each_record['error_messages']) < 0) { //normal new users
                    if (array_key_exists($each_record['reg']['booth_no'], $existing_booth_numbers)) {

                        $tmp_records[$idx]['error_messages'][] = 'The stand number is already taken';
                    }
                }else{
                    if(array_key_exists($each_record['reg']['booth_no'], $existing_booth_numbers)){
                        $tmp_records[$idx]['error_messages'][] = 'The stand number is already taken';
                    }
                }
            }

            //================check stand type ===================

            if(empty($each_record['reg']['booth_type_id'])){
                $tmp_records[$idx]['error_messages'][] = 'Missing User Category';
            }
        }


        $view_record_exisiting_users = array();
        $view_record_new_users = array();  


        $record_errors = array();
        $record_exisiting_users = array();
        $record_new_users = array();        
        foreach ($tmp_records as $idx => $each_record) {
            if (count($each_record['error_messages']) > 0) {
                $record_errors[] = $each_record;
            } else if ($each_record['existing_uid']) {
                $record_exisiting_users[] = $each_record;

                //===== for view purpose only ==========
                $conditions= array();
                $conditions['ExhibitionRegistrations.user_id']=$each_record['user']['id'];
                $conditions['ExhibitionRegistrations.event_id']=$each_record['reg']['event_id'];
                if($is_event_stand==1){
                    $conditions['ExhibitionRegistrations.booth_no']=$each_record['reg']['booth_no'];
                }
                //$isExistStandNo= $this->ExhibitionRegistration->find('first',array('conditions'=>$conditions));

                $isExistStandNo=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($conditions)->first();

                if(!empty($isExistStandNo)){
                   $view_record_exisiting_users[] = $each_record;
                }else{
                   $view_record_new_users[] = $each_record; 
                }
                //======================================

            } else {
                $record_new_users[] = $each_record;
                $view_record_new_users[] = $each_record; // view purpose only
            }
        }

        //Note: we are using 'view_record_exisiting_users' and 'view_record_new_users' only view purpose in import_step_2. we are not processing both array for further action

        $table['processed_data'] = array('errors' => $record_errors,
            'existing_users' => $record_exisiting_users,
            'new_users' => $record_new_users,

            'view_existing_users'=>$view_record_exisiting_users,
            'view_new_users'=>$view_record_new_users,
            'dupes_warning'=>$dupes_warning
        );

        $this->currentSession->write('user.event.import.exhibitor', $table);
    }


    function searchForIndex($email, $table_content,$table_column){  
       
       //echo "<pre>"; print_r($table_content); exit;
       $index_key='';
       foreach($table_content as $key => $val){
           if($val[1] == $email){
               $index_key= $key;
               break;
           }
       }
       
       if($index_key >= 0){

          $csv_user= array();
          foreach($table_column as $key => $field){
              if($field == 'firstname'){
                 $csv_user[$field] = $table_content[$index_key][$key];
              }

              if($field == 'lastname'){
                 $csv_user[$field] = $table_content[$index_key][$key];
              }

              if($field == 'position'){
                 $csv_user[$field] = $table_content[$index_key][$key];
              }

              if($field == 'company_name'){
                 $csv_user[$field] = $table_content[$index_key][$key];
              }

              if($field == 'company_info'){
                 $csv_user[$field] = $table_content[$index_key][$key];
              }

              if($field == 'company_addr_st'){
                 $csv_user[$field] = $table_content[$index_key][$key];
              }

              if($field == 'company_addr_city'){
                 $csv_user[$field] = $table_content[$index_key][$key];
              }

              if($field == 'company_addr_state'){
                 $csv_user[$field] = $table_content[$index_key][$key];
              }

              if($field == 'company_addr_postcode'){
                 $csv_user[$field] = $table_content[$index_key][$key];
              }

              if($field == 'contact_tel'){
                 $csv_user[$field] = $table_content[$index_key][$key];
              }

              if($field == 'contact_tel'){
                 $tmp_value = $table_content[$index_key][$key];
                 $tel = convert_raw_tel_number($tmp_value);
                 $csv_user[$field] = $tel['region'] . '-' . $tel['number'];
              }

              if($field == 'contact_fax'){
                 $tmp_value = $table_content[$index_key][$key];
                 $tel = convert_raw_tel_number($tmp_value);
                 $csv_user[$field] = $tel['region'] . '-' . $tel['number'];
              }

              if($field == 'contact_mob'){
                 $csv_user[$field] = $table_content[$index_key][$key];
              }

          }

          return $csv_user;
       }else{
          return null;
       }

      
    }

    function buildSavingRecords() {
        $current_event=TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $this->currentSession->read('user.event.id')])->first();
        $is_event_stand = $current_event['is_event_with_stands'];
        $table = $this->currentSession->read('user.event.import.exhibitor');
        $table_column = $table['content']['header'];
        $table_content = $table['content']['content'];
        //echo "<pre>"; print_r($table_column); print_r($table_content); exit;
        
        $results = array();
        foreach ($table_content as $rowNumber => $row) {
            $each_err = array();
            $external_user = null;
            $new_password = $this->Users->generate_password(8);

            $each_user = array(
                'user_type' => 'exhibitor',
                'password' => $new_password
            );



            $each_reg = array('event_id' => $this->currentSession->read('user.event.id'));

            $each_custom = array();

            foreach ($table_column as $key => $field) {
                if (strlen($field) > 1) {
                    if ($field == 'email') {
                        if (filter_var(@$row[$key], FILTER_VALIDATE_EMAIL)) {
                            $each_user[$field] = $row[$key];
                            $check_mail = $this->Users->findByEmail($each_user['email'])->first();
                            if($check_mail){
                                if($check_mail['user_type']=='client'){
                                    $each_user[$field] = $row[$key];
                                    $each_err[] = 'User exists as client';
                                }
                            }
                        } else {
                            $each_user[$field] = $row[$key];
                            $each_err[] = 'Invalid email address. []():;\<,>" characters are not allowed on Email Address.';
                        }
                    } else if ($field == 'firstname') {
                        $each_user[$field] = @mb_detect_encoding($row[$key], ['UTF-8', 'ISO-8859-1'])=='ISO-8859-1' ? utf8_encode($row[$key]) : $row[$key]; 
                    } else if ($field == 'lastname') {
                        //$each_user[$field] = @$row[$key];
                        $each_user[$field] = @mb_detect_encoding($row[$key], ['UTF-8', 'ISO-8859-1'])=='ISO-8859-1' ? utf8_encode($row[$key]) : $row[$key];
                    } else if ($field == 'position') {
                        //$each_user[$field] = @$row[$key];
                        $each_user[$field] = @mb_detect_encoding($row[$key], ['UTF-8', 'ISO-8859-1'])=='ISO-8859-1' ? utf8_encode($row[$key]) : $row[$key];
                    } else if ($field == 'company_name') {
                        //$each_user[$field] = @$row[$key];
                        $each_user[$field] = @mb_detect_encoding($row[$key], ['UTF-8', 'ISO-8859-1'])=='ISO-8859-1' ? utf8_encode($row[$key]) : $row[$key];
                    } else if ($field == 'company_info') {
                        //$each_user[$field] = @$row[$key];
                        $each_user[$field] = @mb_detect_encoding($row[$key], ['UTF-8', 'ISO-8859-1'])=='ISO-8859-1' ? utf8_encode($row[$key]) : $row[$key];
                    } else if ($field == 'company_addr_st') {
                        //$each_user[$field] = @$row[$key];
                        $each_user[$field] = @mb_detect_encoding($row[$key], ['UTF-8', 'ISO-8859-1'])=='ISO-8859-1' ? utf8_encode(@$row[$key]) : @$row[$key];
                    } else if ($field == 'company_addr_city') {
                        $each_user[$field] = @$row[$key];
                    } else if ($field == 'company_addr_state') {
                        $each_user[$field] = @$row[$key];
                    } else if ($field == 'company_addr_country') {
                        $tmp_value = @$row[$key];
                        if (strlen($tmp_value) == 0) {
                            $each_user[$field] = '';
                        } elseif (strlen($row[$key]) == 2) {
                            $each_user[$field] = strtoupper($tmp_value);
                        } else {
                            $each_user[$field] = country_name_to_code(trim($tmp_value));
                            $each_user['country_name'] = trim($tmp_value);
                        }
                    } else if ($field == 'company_addr_postcode') {
                        $each_user[$field] = @$row[$key];
                    } else if ($field == 'contact_tel') {
                        $tmp_value = @$row[$key];
                        $tel = convert_raw_tel_number($tmp_value);
                        $each_user[$field] = $tel['region'] . '-' . $tel['number'];
                    } else if ($field == 'contact_fax') {
                        $tmp_value = @$row[$key];
                        $tel = convert_raw_tel_number($tmp_value);
                        $each_user[$field] = $tel['region'] . '-' . $tel['number'];
                    } else if ($field == 'contact_mob') {
                        $each_user[$field] = @$row[$key];
                    }

                    //Registration info .....
                    else if ($field == 'booth_no') {
                        //if($is_event_stand==1){
                            if (strlen(@$row[$key]) > 0) {
                                $each_reg[$field] = @$row[$key];
                            } else {
                                if($is_event_stand==1){
                                    $each_err[] = 'Stand Number is required';
                                }
                            }
                        //}
                    } else if ($field == 'update_booth_no') {
                        $each_reg[$field] = @$row[$key];
                    } /*else if ($field == 'booth_type_id') {
                      $each_reg[$field] = @$row[$key];
                    }*/else if ($field == 'booth_name') {
                      //$each_reg[$field] = @$row[$key]; 
                      $each_reg[$field] = @mb_detect_encoding($row[$key], ['UTF-8', 'ISO-8859-1'])=='ISO-8859-1' ? utf8_encode($row[$key]) : $row[$key];
                    } else if ($field == 'event_exhibitor_types') {
                        if (strlen(trim(@$row[$key])) > 0) {
                            $each_reg[$field] = $this->import_convert_exhibitor_types(@mb_detect_encoding($row[$key], ['UTF-8', 'ISO-8859-1'])=='ISO-8859-1' ? utf8_encode($row[$key]) : $row[$key]);
                            $each_reg['view_exhibitor_type']= $row[$key];
                        } else {
                            // get default exhibitor type
                            $default_exhib_type= $this->getDefaultExhibType();
                            if(!empty($default_exhib_type)){
                               $each_reg[$field] = "[".$default_exhib_type['id']."]";
                               $each_reg['view_exhibitor_type']= $default_exhib_type['name'];
                            }else{
                               $each_err[] = 'User Type is required'; 
                            }
                            
                        }
                    } else if ($field == 'booth_type_id') {
                        $each_reg[$field] = $this->import_convert_booth_type(@mb_detect_encoding($row[$key], ['UTF-8', 'ISO-8859-1'])=='ISO-8859-1' ? utf8_encode($row[$key]) : $row[$key]);
                        $each_reg['booth_type_name'] = @$row[$key];
                    } else if ($field == 'event_dimension_id') {
                        $each_reg[$field] = $this->import_convert_dimension(@mb_detect_encoding($row[$key], ['UTF-8', 'ISO-8859-1'])=='ISO-8859-1' ? utf8_encode($row[$key]) : $row[$key]);
                        $each_reg['event_dimension_name'] = @$row[$key];
                    } else if ($field == 'event_location_id') {
                        $each_reg[$field] = $this->import_convert_location(@mb_detect_encoding($row[$key], ['UTF-8', 'ISO-8859-1'])=='ISO-8859-1' ? utf8_encode($row[$key]) : $row[$key]);
                        $each_reg['event_location_name'] = @$row[$key];
                    } else if ($field == 'external_username') {
                        $each_user[$field] = @$row[$key];
                    } else if ($field == 'external_password') {
                        $each_user[$field] = @$row[$key];
                    } else if ($field == 'preferred_language'){
                        $each_reg[$field] = @$row[$key];
                    }

                    // custom field
                    $exp_field= explode("_", $field);
                    //if(strtolower($exp_field[0])=='custom'){
                    if($exp_field[0]=='custom'){
                       $each_custom[$field] = @$row[$key];  
                    }


                }
            } // end of foreach $table_column
          
            //echo "<pre>"; print_r($this->currentSession->read('user.event.import.exhibitor')); exit;

            if (@strlen($each_user['external_username']) == 0 || strlen(@$each_user['external_password']) == 0) {
                unset($each_user['external_username']);
                unset($each_user['external_password']);
            } else {

                // check if external username and password already taken by other email id
                $taken_condi=array();
                $taken_condi['Users.external_username']=$each_user['external_username'];
                $taken_condi['Users.external_password']=$each_user['external_password'];
                $taken_condi['Users.email !=']=$each_user['email'];
                
                //$istaken_username= $this->User->find('first',array('conditions'=>$taken_condi));
                $istaken_username = TableRegistry::getTableLocator()->get('Users')->find()->where($taken_condi)->first();

                if(!empty($istaken_username)){
                    $each_err[] = 'External username and password already taken by another exhibitor';
                }

                //==================end=====================

                $each_user['password'] = $this->Users->encode_password($each_user['external_password']);
                
                //$external_user = $this->User->find('first', array('conditions' => array('external_username' => $each_user['external_username'], 'external_password' => $each_user['external_password'])));

                $external_user=TableRegistry::getTableLocator()->get('Users')->find()->where(['external_username' => $each_user['external_username'], 'external_password' => $each_user['external_password']])->first();

            }


            if (count($each_err) > 0) {
                
                $results[] = array(
                    'existing_uid' => false,
                    'csv_row' => $rowNumber,
                    'error_messages' => $each_err,
                    'user' => $each_user,
                    'reg' => $each_reg,
                    'custom' => $each_custom
                );
            } else {

                $uid = '';
                $user = $this->Users->findByEmail($each_user['email'])->first();
                if($user){
                   $user= $user->toArray();
                   $user['country_name']=country_code_to_name($user['company_addr_country']);
                }
                //echo "<pre>"; print_r($user); exit;
                if ($user != null) {
                    
                    foreach ($each_user as $user_key => $user_value) {
                        $user_value = trim($user_value, '-');
                        if (strlen(trim($user_value)) < 1) {
                            unset($each_user[$user_key]);
                        } else {
                            $user_value = str_replace('(', '', $user_value);
                            $user_value = str_replace(')', '', $user_value);
                        }
                    }

                    

                    /********/
                    $csvuser=array();
                    if($each_user['firstname']==''){
                       $csvuser=$this->searchForIndex($each_user['email'],$table_content,$table_column);
                       //echo "<pre>"; print_r($csvuser); exit;
                    }

                    if(!empty($csvuser)){
                       $each_user = array_merge($csvuser, $each_user);  
                    }else{
                        $each_user = array_merge($user, $each_user);
                    }

                    /********/

                    //$each_user = array_merge($user['User'], $each_user);
                    
                    $each_user['id'] = $user['id'];
                    $each_user['updated'] = date('Y-m-d H:i:s');
                    unset($each_user['created']);
                    unset($each_user['password']);

                    //do not update user profile yet,
                    $uid = $each_user['id'];

                    $each_reg['user_id'] = $uid;

                    // check if user involved in current event
                    $condi=array('ExhibitionRegistrations.user_id'=>$uid,'ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id'));
                    //$is_involved= $this->ExhibitionRegistration->field('id',$condi);
                    $is_involved=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($condi)->first();
                    if($is_involved){
                       $results[] = array(
                            'existing_uid' => $uid,
                            'csv_row' => $rowNumber,
                            'error_messages' => $each_err,
                            'user' => $each_user,
                            'exisiting_user' => $user,
                            'reg' => $each_reg,
                            'custom' => $each_custom,
                            'existing_reg_data' => $is_involved
                       );
                    }else{
                       $results[] = array(
                            'existing_uid' => false,
                            'csv_row' => $rowNumber,
                            'error_messages' => $each_err,
                            'user' => $each_user,
                            'reg' => $each_reg,
                            'custom' => $each_custom
                        );  
                    }
                    
                    //echo "<pre>"; print_r($results); exit;
                } else if ($external_user != null) {

                    foreach ($each_user as $user_key => $user_value) {
                        $user_value = trim($user_value, '-');
                        if (strlen(trim($user_value)) < 1) {
                            unset($each_user[$user_key]);
                        } else {
                            $user_value = str_replace('(', '', $user_value);
                            $user_value = str_replace(')', '', $user_value);
                        }
                    }

                    /********/
                    $csvuser=array();
                    if($each_user['firstname']==''){
                       $csvuser=$this->searchForIndex($each_user['email'],$table_content,$table_column);
                    }

                    if(!empty($csvuser)){
                       $each_user = array_merge($csvuser, $each_user);  
                    }else{
                        $each_user = array_merge($external_user, $each_user);
                    }

                    /********/

                    //$each_user = array_merge($external_user['User'], $each_user);
                    $each_user['id'] = $external_user['id'];
                    $each_user['updated'] = date('Y-m-d H:i:s');
                    unset($each_user['created']);
                    unset($each_user['password']);

                    //do not update user profile yet,
                    $uid = $each_user['id'];

                    $each_reg['user_id'] = $uid;
                    // check if user involved in current event
                    $condi=array('ExhibitionRegistrations.user_id'=>$uid,'ExhibitionRegistrations.event_id'=>$this->currentSession->read('user.event.id'));
                    $is_involved=TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where($condi)->first();
                    if($is_involved){
                       $results[] = array(
                            'existing_uid' => $uid,
                            'csv_row' => $rowNumber,
                            'error_messages' => $each_err,
                            'user' => $each_user,
                            'exisiting_user' => $user,
                            'reg' => $each_reg,
                            'custom' => $each_custom
                       );
                    }else{
                       $results[] = array(
                            'existing_uid' => false,
                            'csv_row' => $rowNumber,
                            'error_messages' => $each_err,
                            'user' => $each_user,
                            'reg' => $each_reg,
                            'custom' => $each_custom
                        );  
                    }

                    /*$results[] = array(
                        'existing_uid' => $uid,
                        'error_messages' => $each_err,
                        'user' => $each_user,
                        'exisiting_user' => $external_user,
                        'reg' => $each_reg
                    );*/
                } else {
                    
                    $tmp_user_ary = array('User' => $each_user);
                    $record_new_users[] = array('user' => $each_user, 'reg' => $each_reg);

                    $results[] = array(
                        'existing_uid' => false,
                        'csv_row' => $rowNumber,
                        'error_messages' => $each_err,
                        'user' => $each_user,
                        'reg' => $each_reg,
                        'custom' => $each_custom
                    );
                }

                
            }
        }

       
        //echo "<pre>"; print_r($results); exit;
        return $results;
    }

    function readImportFile($filename) {

        //==================================
        /*$reader = new XlsxReader();
        $spreadsheet = $reader->load($filename);
        foreach($spreadsheet->getWorksheetIterator() as $worksheet) {
		    foreach($worksheet->getRowIterator() as $row){
		        
		        $cellIterator = $row->getCellIterator();
		        $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                //echo "<pre>"; print_r($cellIterator); exit;
		        foreach($cellIterator as $cell) {
		            if($cell !== null) {
		               echo 'Cell - ' . $cell->getCoordinate() . ' - ' . $cell->getCalculatedValue();
		            }
		        }
		    }
	    }

	    exit;*/

    	//==================================
        //App::import('Vendor', 'reader', array('file' => 'Spreadsheet_Excel_Reader' . DS . 'reader.php'));
        require_once(ROOT . DS . 'vendor' . DS  . 'Spreadsheet_Excel_Reader/reader.php');

        $reader = new Spreadsheet_Excel_Reader();
        $reader->setUTFEncoder('iconv');
        $reader->setOutputEncoding('UTF-8');

        if ($reader->read($filename) !== false){
            $table = $reader->sheets[0]; //only read first sheet.

            $table_data = array();
            foreach ($table['cells'] as $row) {
                $table_data[] = $row;
            }          

            return array('numRows' => $table['numRows'], 'numCols' => $table['numCols'], 'content' => $table_data);
        } else {
            return false;
        }
    }

    function tableAnalysis($table) {
        $table_data = $table['content'];
        $table_info = array('header' => array(), 'content' => array());
        // code to add all custom field in import exhibitor dropdown
            $field = array();
            $customfield = $this->get_custom_field();
            foreach ($table_data[0] as $key => $value) {
                if(!in_array($value, $field)){
                    array_push($field, $value);
                }
            }
           
            $index = count($table_data[0]);
            foreach ($customfield as $custom) {
                $attr = "Custom_".$custom['field_key'];
                if((!in_array($attr, $field)) && (!in_array( "custom_".$custom['field_key'], $field))){
                   $table_data[0][$index+1] = $attr; 
                }
                $index=$index+1;
            }
        // code to add all custom field in import exhibitor dropdown
        if (isset($table_data[0])) {

            $header_info = array();
            $content_info = array();

            $first_row = $table_data[0];
            //echo "<pre>"; print_r($first_row); exit;
            
            foreach ($first_row as $idx => $value) {
                $key = $this->fieldHeaderConvert(strtolower($value));
                //$key = $this->fieldHeaderConvert($value);
                if ($key) {
                    $header_info[$idx] = $key;
                }
            }

            


            if (count($header_info) > 0) {
                unset($table_data[0]);
            }

            foreach ($table_data as $row) {
                $tmp_row = array();
                foreach ($row as $idx => $value) {
                    $tmp_row[$idx] = $value;
                }

                $content_info[] = $tmp_row;
            }

            $table_info = array('header' => $header_info,
                'content' => $content_info);
        }

        $table['content'] = $table_info;

        return $table;
    }

    function fieldHeaderConvert($attr) {
        

        $knowledge_column_header = array('email' => array('email', 'e-mail'),
            'firstname' => array('firstname', 'given name'),
            'lastname' => array('lastname', 'family name'),
            'position' => array('position'),
            'company_name' => array('company', 'company name'),
            'company_info' => array('company info', 'company description'),
            'company_addr_st' => array('company_addr_st', 'address 1', 'street address','street'),
            'company_addr_city' => array('company_addr_city', 'city'),
            'company_addr_state' => array('company_addr_state', 'state'),
            'company_addr_country' => array('company_addr_country', 'country'),
            'company_addr_postcode' => array('company_addr_postcode', 'postcode'),
            'contact_tel' => array('contact_tel', 'telephone', 'tel'),
            'contact_fax' => array('contact_fax', 'facsimile', 'fax'),
            'contact_mob' => array('contact_mob', 'mobile', 'mob'),
            'booth_no' => array('booth_no', 'stand no', 'stand number', 'booth number', 'Booth No.'),
            'update_booth_no' => array('update_booth_no', 'update booth no', 'update stand no', 'new stand no'),
            'booth_name' => array('booth_name', 'booth name', 'stand name'),
            'event_exhibitor_types' => array('event_exhibitor_types', 'event exhibitor types', 'exhibitor type','user type'),
            'booth_type_id' => array('booth_type_id', 'booth type', 'stand type', 'Event Booth Type','user categories'),
            'event_dimension_id' => array('event_dimension_id', 'event dimension', 'dimension','stand dimension'),
            'event_location_id' => array('event_location_id', 'event location', 'location','stand location'),
            'preferred_language' => array('preferred_language','Preferred Language','preferred language'),
            'external_username' => array('external_username', 'Username External', 'External Username', 'username external'),
            'external_password' => array('external_password', 'Password External', 'External Password', 'password external'),
            'event' => array('Event'),
            'stands' => array('Stands')


        );


        // check for custom field
        $exp_attr= explode("_", $attr);
        //if(strtolower($exp_attr[0])=='custom'){
        if($exp_attr[0]=='custom'){
           $knowledge_column_header[$attr] = array($attr);
           //echo "<pre>"; print_r($knowledge_column_header); exit;
        }

        //echo "<pre>"; print_r($knowledge_column_header); exit;

        foreach ($knowledge_column_header as $key => $alias) {
            foreach ($alias as $each) {
                $each = strtolower($each);
                similar_text($attr, $each, $percentage);

                if ($percentage > 85) {
                    return $key;
                }
            }
        }

        return false;
    }

     //function to get all custom fields
    function get_custom_field(){
        //$custom_datas=$this->RegistrationMeta->find('all',array('conditions'=>array('RegistrationMeta.event_id'=>$this->Session->read('user.event.id'))));
        $custom_datas=TableRegistry::getTableLocator()->get('RegistrationMetas')->find()->where(['RegistrationMetas.event_id'=>$this->getRequest()->getSession()->read('user.event.id')])->toArray();
        // echo "<pre>";
        // print_r($custom_datas);exit;
        $temp_arr= array();
        $custom_fields= array();
        foreach($custom_datas as $custom){
            if(!in_array($custom['field_key'], $temp_arr)){
               $custom_fields[]= $custom;
            }
            $temp_arr[]=$custom['field_key'];
        }
        return $custom_fields;
    }

    /*function import_exhibitor_old() {
        $step = 1;
        $tmp_data = array();
        if (!empty($this->params['form']) && isset($this->params['form']['step'])) {
            $step = $this->params['form']['step'];
            if ($step == '1') {
                if (is_uploaded_file($this->params['form']['upload_file']['tmp_name'])) {
                    //echo $this->params['form']['upload_file']['type'];
                    //print_r($this->params['form']['upload_file']);
                    if ($this->params['form']['upload_file']['type'] == 'application/x-msexcel' ||
                            $this->params['form']['upload_file']['type'] == 'application/vnd.ms-excel' ||
                            $this->params['form']['upload_file']['type'] == 'application/msexcel' ||
                            $this->params['form']['upload_file']['type'] == 'application/x-ms-excel' ||
                            $this->params['form']['upload_file']['type'] == 'application/x-excel' ||
                            $this->params['form']['upload_file']['type'] == 'application/xls') {

                        $filename = $this->params['form']['upload_file']['tmp_name'];
                        App::import('Vendor', 'reader', array('file' => 'Spreadsheet_Excel_Reader' . DS . 'reader.php'));
                        $reader = new Spreadsheet_Excel_Reader();
                        $reader->setUTFEncoder('iconv');
                        $reader->setOutputEncoding('UTF-8');
                        if ($reader->read($filename) !== false) {
                            $table = $reader->sheets[0]; //only read first sheet.
                            //check is has header;  no idx "0"
                            $header = $table['cells'][1];

                            $header_idx = array();
                            foreach ($header as $k => $value) {
                                $value = trim($value);
                                $value = str_replace(' ', '_', $value);
                                if (strtolower($value) == 'email' ||
                                        strtolower($value) == 'e-mail') {
                                    $header_idx[$k] = 'email';
                                } elseif (strtolower($value) == 'firstname' ||
                                        strtolower($value) == 'first_name' ||
                                        strtolower($value) == 'given_name' ||
                                        strtolower($value) == 'forename') {
                                    $header_idx[$k] = 'firstname';
                                } elseif (strtolower($value) == 'lastname' ||
                                        strtolower($value) == 'last_name' ||
                                        strtolower($value) == 'surname') {
                                    $header_idx[$k] = 'lastname';
                                } elseif (strtolower($value) == 'position') {
                                    $header_idx[$k] = 'position';
                                } elseif (strtolower($value) == 'company_name' ||
                                        strtolower($value) == 'company') {
                                    $header_idx[$k] = 'company_name';
                                } elseif (strtolower($value) == 'company_info') {
                                    $header_idx[$k] = 'company_info';
                                } elseif (strtolower($value) == 'company_addr_st' ||
                                        strtolower($value) == 'address_1') {
                                    $header_idx[$k] = 'company_addr_st';
                                } elseif (strpos(strtolower($value), 'company_addr_city') !== false) {
                                    $header_idx[$k] = 'company_addr_city';
                                } elseif (strpos(strtolower($value), 'state') !== false ||
                                        strpos(strtolower($value), 'province') !== false) {
                                    $header_idx[$k] = 'company_addr_state';
                                } elseif (strpos(strtolower($value), 'country') !== false) {
                                    $header_idx[$k] = 'company_addr_country';
                                } elseif (strpos(strtolower($value), 'postcode') !== false) {
                                    $header_idx[$k] = 'company_addr_postcode';
                                } elseif (strtolower($value) == 'contact_tel' ||
                                        strtolower($value) == 'phone' ||
                                        strtolower($value) == 'telephone') {
                                    $header_idx[$k] = 'contact_tel';
                                } elseif (strtolower($value) == 'contact_fax' ||
                                        strtolower($value) == 'fax' ||
                                        strtolower($value) == 'facsimile') {
                                    $header_idx[$k] = 'contact_fax';
                                } elseif (strtolower($value) == 'contact_mob' ||
                                        strtolower($value) == 'mobile') {
                                    $header_idx[$k] = 'contact_mob';
                                }
                                // registration information
                                elseif (strtolower($value) == 'booth_no' ||
                                        strtolower($value) == 'booth_number' ||
                                        strtolower($value) == 'stand_no' ||
                                        strtolower($value) == 'stand_number') {
                                    $header_idx[$k] = 'booth_no';
                                } elseif (strtolower($value) == 'update_booth_no' ||
                                        strtolower($value) == 'update_stand_no' ||
                                        strtolower($value) == 'update_stand' ||
                                        strtolower($value) == 'update_booth' ||
                                        strtolower($value) == 'booth_update' ||
                                        strtolower($value) == 'stand_update' ||
                                        (strpos(strtolower($value), 'update') !== false &&
                                        strpos(strtolower($value), 'booth') !== false)) {
                                    $header_idx[$k] = 'update_booth_no';
                                } elseif (strtolower($value) == 'booth_name' ||
                                        strtolower($value) == 'stand_name') {
                                    $header_idx[$k] = 'booth_name';
                                } elseif (strpos(strtolower($value), 'exhibitor_type') !== false) {
                                    $header_idx[$k] = 'event_exhibitor_types';
                                } elseif (strpos(strtolower($value), 'booth_type') !== false ||
                                        strpos(strtolower($value), 'stand_type') !== false) {
                                    $header_idx[$k] = 'booth_type_id';
                                } elseif (strpos(strtolower($value), 'dimension') !== false) {
                                    $header_idx[$k] = 'event_dimension_id';
                                } elseif (strpos(strtolower($value), 'location') !== false) {
                                    $header_idx[$k] = 'event_location_id';
                                }
                            }

                            //if the first row is column description, dont include it in our data
                            $data_rows = $table['cells'];
                            if (count($header_idx) > 0) {
                                unset($data_rows[1]);
                            }

                            $new_data_rows = array();
                            foreach ($data_rows as $row) {
                                $new_data_rows[] = $row;
                            }

                            $tmp_data = array(
                                'header' => $header_idx,
                                'data' => $new_data_rows
                            );
                            $this->Session->write('user.event.import.exhibitor', $tmp_data);
                            //echo "<br/>\n";
                            //print_r($data_row);
                            //print_r($header);
                            $step = 2;
                        } else {
                            //$this->Session->write('flash', array('File reading error. Supported Format: xls', 'failure'));
                            $this->Session->setFlash('File reading error. Supported Format: xls');
                        }
                    } else {
                        //$this->Session->write('flash', array('File reading error. Supported Format: xls', 'failure'));
                        $this->Session->setFlash('File reading error. Supported Format: xls');
                    }
                    unlink($this->params['form']['upload_file']['tmp_name']);
                } else {
                    //$this->Session->write('flash', array('File upload error', 'failure'));
                    $this->Session->setFlash('File upload error', 'failure');
                }
            } elseif ($step == 2 && $this->Session->check('user.event.import.exhibitor')) {
                if ($this->Session->check('user.event.import.exhibitor') && count($this->Session->read('user.event.import.exhibitor.data')) > 0) {

                    App::import('Vendor', 'ent_custom_form');
                    $custom_form = new EntCustomForm();

                    $import_field_err = array();
                    if (!in_array('email', @$this->params['form']['fields'])) {
                        $import_field_err[] = "Email";
                    }
                    if (!in_array('firstname', @$this->params['form']['fields'])) {
                        $import_field_err[] = "Firstname";
                    }
                    if (!in_array('event_exhibitor_types', @$this->params['form']['fields'])) {
                        $import_field_err[] = "Exhibitor";
                    }
                    if (!in_array('booth_no', @$this->params['form']['fields'])) {
                        $import_field_err[] = "Booth No";
                    }

                    if (count($import_field_err) === 0) {
                        $dup_fields = array();
                        $field_ct = array();
                        foreach ($this->params['form']['fields'] as $key => $value) {
                            if (strlen(trim($value)) > 0) {
                                if (isset($field_ct[$value])) {
                                    $dup_fields[] = $value;
                                    $field_ct[$value]++;
                                } else {
                                    $field_ct[$value] = 1;
                                }
                            }
                        }

                        //print_r($dup_fields);
                        if (count($dup_fields) == 0) {
                            $tmp_data = $this->Session->read('user.event.import.exhibitor');
                            $tmp_data['header'] = $this->params['form']['fields'];
                            $this->Session->write('user.event.import.exhibitor', $tmp_data);

                            $error = array();
                            $existing_users = array();
                            $new_users = array();

                            $user_data = array();
                            $reg_datas = array();
                            foreach ($tmp_data['data'] as $row) {
                                $each_err = array();
                                $new_password = $this->User->generate_password(8);
                                $each_user = array(
                                    'user_type' => 'exhibitor',
                                    'password' => $this->User->encode_password($new_password)
                                );
                                $each_reg = array('event_id' => $this->Session->read('user.event.id'));
                                foreach ($tmp_data['header'] as $key => $field) {
                                    if (strlen($field) > 1) {
                                        if ($field == 'email') {
                                            if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", @$row[$key])) {
                                                $each_user[$field] = $row[$key];
                                            } else {
                                                $each_err[] = 'Invalid email address';
                                            }
                                        } else if ($field == 'firstname') {
                                            $each_user[$field] = @$row[$key];
                                        } else if ($field == 'lastname') {
                                            $each_user[$field] = @$row[$key];
                                        } else if ($field == 'position') {
                                            $each_user[$field] = @$row[$key];
                                        } else if ($field == 'company_name') {
                                            $each_user[$field] = @$row[$key];
                                        } else if ($field == 'company_info') {
                                            $each_user[$field] = @$row[$key];
                                        } else if ($field == 'company_addr_st') {
                                            $each_user[$field] = @$row[$key];
                                        } else if ($field == 'company_addr_city') {
                                            $each_user[$field] = @$row[$key];
                                        } else if ($field == 'company_addr_state') {
                                            $each_user[$field] = @$row[$key];
                                        } else if ($field == 'company_addr_country') {
                                            $tmp_value = @$row[$key];
                                            if (strlen($tmp_value) == 0) {
                                                $each_user[$field] = '';
                                            } elseif (strlen($row[$key]) == 2) {
                                                $each_user[$field] = strtoupper($tmp_value);
                                            } else {
                                                $each_user[$field] = country_name_to_code(trim($tmp_value));
                                            }
                                        } else if ($field == 'company_addr_postcode') {
                                            $each_user[$field] = @$row[$key];
                                        } else if ($field == 'contact_tel') {
                                            $tmp_value = @$row[$key];
                                            $tel = convert_raw_tel_number($tmp_value);
                                            $each_user[$field] = $tel['region'] . '-' . $tel['number'];
                                        } else if ($field == 'contact_fax') {
                                            $tmp_value = @$row[$key];
                                            $tel = convert_raw_tel_number($tmp_value);
                                            $each_user[$field] = $tel['region'] . '-' . $tel['number'];
                                        } else if ($field == 'contact_mob') {
                                            $each_user[$field] = @$row[$key];
                                        }
                                        //Registration info .....
                                        else if ($field == 'booth_no') {
                                            if (strlen(@$row[$key]) > 0) {
                                                $each_reg[$field] = @$row[$key];
                                            } else {
                                                $each_err[] = 'Booth No is required';
                                            }
                                        } else if ($field == 'update_booth_no') {
                                            $each_reg[$field] = @$row[$key];
                                        } else if ($field == 'booth_name') {
                                            $each_reg[$field] = @$row[$key];
                                        } else if ($field == 'event_exhibitor_types') {
                                            if (strlen(trim(@$row[$key])) > 0) {
                                                $each_reg[$field] = $this->import_convert_exhibitor_types(@$row[$key]);
                                            } else {
                                                $each_err[] = 'User Type is required';
                                            }
                                        } else if ($field == 'booth_type_id') {
                                            $each_reg[$field] = $this->import_convert_booth_type(@$row[$key]);
                                        } else if ($field == 'event_dimension_id') {
                                            $each_reg[$field] = $this->import_convert_dimension(@$row[$key]);
                                        } else if ($field == 'event_location_id') {
                                            $each_reg[$field] = $this->import_convert_location(@$row[$key]);
                                        }
                                    }
                                }

                                if (count($each_err) > 0) {
                                    $error[] = array(
                                        'error_messages' => $each_err,
                                        'user' => $each_user,
                                        'reg' => $each_reg
                                    );
                                } else {
                                    //$import_data[] = array(   'user' => $each_user,
                                    //                      'reg' => $each_reg);
                                    $uid = '';
                                    $user = $this->User->findByEmail($each_user['email']);
                                    if ($user != null) {
                                        foreach ($each_user as $user_key => $user_value) {
                                            $user_value = trim($user_value, '-');
                                            if (strlen(trim($user_value)) < 1) {
                                                unset($each_user[$user_key]);
                                            } else {
                                                $user_value = str_replace('(', '', $user_value);
                                                $user_value = str_replace(')', '', $user_value);
                                            }
                                        }

                                        $each_user = array_merge($user['User'], $each_user);
                                        $each_user['id'] = $user['User']['id'];
                                        $each_user['updated'] = date('Y-m-d H:i:s');
                                        unset($each_user['created']);
                                        unset($each_user['password']);

                                        //do not update user profile yet,
                                        $uid = $each_user['id'];
                                        $existing_users[$uid] = $each_user;
                                    } else {
                                        /*
                                          $tmp_user_ary = array('User' => $each_user);
                                          $this->User->create();
                                          while(!$this->User->save($tmp_user_ary)){
                                          usleep(100000);   //retry again in 0.1 second
                                          }
                                          $uid = $this->User->getLastInsertId();
                                          $each_user['id'] = $uid;
                                          $new_users[$uid] = $each_user;
                                         */
                                        /*$tmp_user_ary = array('User' => $each_user);
                                        $new_users[] = $tmp_user_ary;
                                    }

                                    $each_reg['user_id'] = $uid;
                                    $user_data[$uid] = $each_user;
                                    $reg_datas[$uid][] = $each_reg;
                                }
                            }

                            $reg_errors = array();
                            foreach ($reg_datas as $uid => $user_regs) {
                                foreach ($user_regs as $idx => $each_reg) {
                                    $current_reg_errors = array();
                                    $is_update_booth_no = false;
                                    $reg_to_save = array('ExhibitionRegistration' => $each_reg);
                                    $booth_no = $each_reg['booth_no'];
                                    $existing_reg = $this->ExhibitionRegistration->find('all', array(
                                        'conditions' => array(
                                            'AND' => array(
                                                'ExhibitionRegistration.event_id' => $this->Session->read('user.event.id'),
                                                'ExhibitionRegistration.user_id' => $uid,
                                                'ExhibitionRegistration.booth_no' => $booth_no,
                                            )
                                        )
                                            ));

                                    if (isset($each_reg['update_booth_no']) && strlen($each_reg['update_booth_no']) > 0 && strcmp($each_reg['update_booth_no'], $booth_no) != 0) { //if update from an old value to a new value
                                        $is_update_booth_no = true;
                                        $new_booth_no = $each_reg['update_booth_no'];
                                        $reg_to_save['ExhibitionRegistration']['booth_no'] = $new_booth_no;
                                    }

                                    //check duplicated booth number;
                                    if (!$existing_reg && $is_update_booth_no) {
                                        $current_reg_errors[] = array(
                                            'error_messages' => array('Stand Number Update found no existing record matched'),
                                            'user' => $user_data[$uid],
                                            'reg' => $each_reg
                                        );
                                    } else if ($existing_reg && $is_update_booth_no) {
                                        //$reg_to_save = array_merge_recursive($existing_reg[0], $reg_to_save);
                                        $reg_to_save['ExhibitionRegistration'] = array_merge($existing_reg[0]['ExhibitionRegistration'], $reg_to_save['ExhibitionRegistration']);

                                        if ($is_update_booth_no && $this->ExhibitionRegistration->find('count', array(
                                                    'conditions' => array(
                                                        'ExhibitionRegistration.event_id' => $this->Session->read('user.event.id'),
                                                        'ExhibitionRegistration.booth_no' => $reg_to_save['ExhibitionRegistration']['booth_no'],
                                                        ))) > 0) {
                                            $current_reg_errors[] = array(
                                                'error_messages' => array('Booth Number has been used'),
                                                'user' => $user_data[$uid],
                                                'reg' => $each_reg
                                            );
                                        }
                                    } else if ($existing_reg) { //update existing record; without changing booth no
                                        $reg_to_save['ExhibitionRegistration'] = array_merge($existing_reg[0]['ExhibitionRegistration'], $reg_to_save['ExhibitionRegistration']);
                                    } else { //new record
                                        if ($this->ExhibitionRegistration->find('count', array(
                                                    'conditions' => array(
                                                        'AND' => array(
                                                            'ExhibitionRegistration.event_id' => $this->Session->read('user.event.id'),
                                                            'ExhibitionRegistration.booth_no' => $reg_to_save['ExhibitionRegistration']['booth_no'],
                                                        )
                                                    )
                                                )) > 0) {
                                            $current_reg_errors[] = array(
                                                'error_messages' => array('Booth Number has been used'),
                                                'user' => $user_data[$uid],
                                                'reg' => $each_reg
                                            );
                                        } else {
                                            $this->ExhibitionRegistration->create();
                                        }
                                    }

                                    if (count($current_reg_errors) < 1) {
                                        while (!$this->ExhibitionRegistration->save($reg_to_save)) {
                                            usleep(100000); //retry again in 0.1 second
                                        }
                                        $tmp_registration = false;
                                        if (isset($reg_to_save['ExhibitionRegistration']['id'])) {
                                            $tmp_registration = $this->ExhibitionRegistration->findById($reg_to_save['ExhibitionRegistration']['id']);
                                        }

                                        if ($tmp_registration) {
                                            $custom_form->changeRegOwnershipForEventForms(
                                                    $this->Session->read('user.event.id'), $tmp_registration['ExhibitionRegistration']['id'], $tmp_registration['ExhibitionRegistration']['id'], //same reg_id ...
                                                    $tmp_registration['ExhibitionRegistration']['event_exhibitor_types'], $tmp_registration['ExhibitionRegistration']['booth_type_id']
                                            );
                                        }

                                        if (isset($this->params['form']['send_welcome_email']) &&
                                                $this->params['form']['send_welcome_email'] == 'yes') {
                                            $this->resendWelcome($uid, true);
                                        }
                                    } else {
                                        $reg_errors = array_merge($reg_errors, $current_reg_errors);
                                    }
                                }
                            }

                            $tmp_data['errors'] = $error;
                            $tmp_data['existing_users'] = $existing_users;
                            $tmp_data['new_users'] = $new_users;
                            $tmp_data['reg_errors'] = $reg_errors;
                            $this->Session->write('user.event.import.exhibitor', $tmp_data);

                            $step = 3;
                        } else {
                            $err_msg = "Found duplicated field. Please review your selection.<br/><br/>";
                            $err_msg .= "&nbsp;&nbsp;&nbsp;Dupilicated Field:";
                            $err_msg .= implode(", ", $dup_fields);
                            //$this->Session->write('flash', array($err_msg, 'failure'));
                            $this->Session->setFlash($err_msg, 'failure');
                        }
                    } else {
                        $tmp_msg = '<div style="margin-left:10px">' . implode(", ", $import_field_err) . '</div>';
                       // $this->Session->write('flash', array("Missing Required Field:<br/><br/>" . $tmp_msg, 'failure'));
                        $this->Session->setFlash("Missing Required Field:<br/><br/>" . $tmp_msg, 'failure');
                    }
                }
            } elseif ($step == 3 && $this->Session->check('user.event.import.exhibitor')) {
                $msg = "";
                if (isset($this->params['form']['update_profile']) &&
                        count($this->params['form']['update_profile']) > 0) {

                    $update_ids = $this->params['form']['update_profile'];
                    //  print_r($update_ids);die();
                    $tmp_data = $this->Session->read('user.event.import.exhibitor');
                    $users = $tmp_data['existing_users'];
                    foreach ($users as $each_user) {
                        if (in_array($each_user['id'], $update_ids)) {
                            $tmp_user_ary = array('User' => $each_user);
                            while (!$this->User->save($tmp_user_ary)) {
                                usleep(100000); //retry again in 0.1 second
                            }
                        }
                    }

                    $msg = "All selected profile have been updated";
                }

                $this->Session->delete('user.event.import.exhibitor');
                $msg = "Import Process Ended<br/><br/>" . $msg;
                //$this->Session->write('flash', array($msg, 'success'));
                $this->Session->setFlash($msg, 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $step = 1;
            }
        }

        if ($step == 1) {
            //do nothing..
        } elseif ($step == 2) {
            $tmp_data = $this->Session->read('user.event.import.exhibitor');
            $this->set('tmp_data', $tmp_data);
        } elseif ($step == 3) {
            $tmp_data = $this->Session->read('user.event.import.exhibitor');
            $this->set('tmp_data', $tmp_data);

            $existing_users_to_update = $tmp_data['existing_users'];
            $ids = array();
            foreach ($existing_users_to_update as $each_user) {
                $ids[] = $each_user['id'];
            }

            $existing_profiles = array();
            if (count($ids) > 0) {
                $ids = "'" . implode("','", $ids) . "'";
                $existing_users_profile = $this->User->find('all', array('conditions' => array("User.id IN ($ids)")));

                foreach ($existing_users_profile as $each_profile) {
                    $existing_profiles[$each_profile['User']['id']] = $each_profile['User'];
                }
            }
            $this->set('exisitng_user_profiles', $existing_profiles);
        }

        $this->set('step', $step);
    }*/

    function import_convert_exhibitor_types($raw_str) {
        $raw_str = trim($raw_str);
        $types = array();
        if (count(explode(',', $raw_str)) > 1) {
            $types = explode(',', $raw_str);
        } else {
            $types[] = $raw_str;
        }
        //print_r($types);die();

        $ids = '';
        foreach ($types as $each_type) {
            $each_type = trim($each_type);
            $each_type = htmlentities($each_type, null, 'utf-8');
            $each_type = str_replace("&nbsp;", "", $each_type);
            $each_type = html_entity_decode($each_type);
            if (strlen($each_type) > 0) {
                //$exhibitor_type = $this->EventExhibitorType->find('first', array('conditions' =>array('AND' =>array('EventExhibitorType.event_id' => $this->Session->read('user.event.id'),'EventExhibitorType.name' =>$each_type))));

                $exhibitor_type = TableRegistry::getTableLocator()->get('EventExhibitorTypes')->find()->where(['EventExhibitorTypes.event_id'=>$this->currentSession->read('user.event.id'),'EventExhibitorTypes.name' =>$each_type])->first();

                if ($exhibitor_type){
                    $id = $exhibitor_type['id'];
                    $ids .= "[$id]";
                } else {
                    $new_type_entry = array();
                    $new_type_entry['name'] = $each_type;
                    $new_type_entry['event_id'] = $this->currentSession->read('user.event.id');
                    
                    $entity_data=TableRegistry::getTableLocator()->get('EventExhibitorTypes')->newEntity($new_type_entry);

                    if(TableRegistry::getTableLocator()->get('EventExhibitorTypes')->save($entity_data)){
                        $id = $entity_data->id;
                        $ids .= "[$id]"; 
                    }

                    
                }
            }
        }

        return $ids;
    }

    function getDefaultExhibType(){
         $exhibitor_type='';
         $data=TableRegistry::getTableLocator()->get('EventExhibitorTypes')->find()->where(['EventExhibitorTypes.default_exhib_type'=>'1','EventExhibitorTypes.event_id'=>$this->currentSession->read('user.event.id')])->first();
         if(!empty($data)){
            $exhibitor_type = $data;
         }

         return $exhibitor_type;
                
    }

    /*function import_convert_booth_type($raw_str) {
        $raw_str = trim($raw_str);
        $id = '';
        if (strlen($raw_str) > 0) {
            //$booth_type = $this->EventBoothType->find('first', array('conditions' =>array('AND' =>array('EventBoothType.event_id' => $this->Session->read('user.event.id'),'EventBoothType.name' => $raw_str))));

            $booth_type = TableRegistry::getTableLocator()->get('EventBoothTypes')->find()->where(['EventBoothTypes.event_id' => $this->currentSession->read('user.event.id'),'EventBoothTypes.name'=>$raw_str])->first();

            if ($booth_type) {
                $id = $booth_type['id'];
            } else {
                $new_entry = array();
                $new_entry['name'] = $raw_str;
                $new_entry['event_id'] = $this->currentSession->read('user.event.id');
                
                $entity_data=TableRegistry::getTableLocator()->get('EventBoothTypes')->newEntity($new_entry);

                TableRegistry::getTableLocator()->get('EventBoothTypes')->save($entity_data);
                $id = $entity_data->id;
            }
        }

        return $id;
    }*/
    function import_convert_booth_type($raw_str) {
        $raw_str = trim($raw_str);
        $types = array();
        if (count(explode(',', $raw_str)) > 1) {
            $types = explode(',', $raw_str);
        } else {
            $types[] = $raw_str;
        }
        //print_r($types);die();

        $ids = '';
        foreach ($types as $each_type) {
            $each_type = trim($each_type);
            $each_type = trim($each_type);
            $each_type = htmlentities($each_type, null, 'utf-8');
            $each_type = str_replace("&nbsp;", "", $each_type);
            $each_type = html_entity_decode($each_type);
            if (strlen($each_type) > 0) {
                //$exhibitor_type = $this->EventExhibitorType->find('first', array('conditions' =>array('AND' =>array('EventExhibitorType.event_id' => $this->Session->read('user.event.id'),'EventExhibitorType.name' =>$each_type))));

                $booth_type = TableRegistry::getTableLocator()->get('EventBoothTypes')->find()->where(['EventBoothTypes.event_id' => $this->currentSession->read('user.event.id'),'EventBoothTypes.name'=>$each_type])->first();

                if ($booth_type){
                    $id = $booth_type['id'];
                    $ids .= "[$id]";
                } else {
                    $new_type_entry = array();
                    $new_type_entry['name'] = $each_type;
                    $new_type_entry['event_id'] = $this->currentSession->read('user.event.id');
                    
                    $entity_data=TableRegistry::getTableLocator()->get('EventBoothTypes')->newEntity($new_type_entry);

                    if(TableRegistry::getTableLocator()->get('EventBoothTypes')->save($entity_data)){
                        $id = $entity_data->id;
                        $ids .= "[$id]"; 
                    }

                    
                }
            }
        }

        return $ids;
    }

    function import_convert_dimension($raw_str) {
        $raw_str = trim($raw_str);
        $raw_str = htmlentities($raw_str, null, 'utf-8');
        $raw_str = str_replace("&nbsp;", "", $raw_str);
        $raw_str = html_entity_decode($raw_str);
        $id = '';
        if (strlen($raw_str) > 0) {
            //$dimension = $this->EventDimension->find('first', array('conditions'=>array('AND' =>array('EventDimension.event_id' => $this->Session->read('user.event.id'),'EventDimension.name' => $raw_str))));

            $dimension =TableRegistry::getTableLocator()->get('EventDimensions')->find()->where(['EventDimensions.event_id'=>$this->currentSession->read('user.event.id'),'EventDimensions.name'=>$raw_str])->first();

            if ($dimension){
                $id = $dimension['id'];
            } else {
                $new_entry = array();
                $new_entry['name'] = $raw_str;
                $new_entry['event_id'] = $this->currentSession->read('user.event.id');
                
                $entity_data= TableRegistry::getTableLocator()->get('EventDimensions')->newEntity($new_entry);

                TableRegistry::getTableLocator()->get('EventDimensions')->save($entity_data);
                $id = $entity_data->id;
            }
        }

        return $id;
    }

    function import_convert_location($raw_str) {
        $raw_str = trim($raw_str);
        $raw_str = htmlentities($raw_str, null, 'utf-8');
        $raw_str = str_replace("&nbsp;", "", $raw_str);
        $raw_str = html_entity_decode($raw_str);
        $id = '';
        if (strlen($raw_str) > 0) {
            //$location = $this->EventLocation->find('first', array('conditions' =>array('AND' =>array('EventLocation.event_id' => $this->Session->read('user.event.id'),'EventLocation.name' => $raw_str))));

            $location =TableRegistry::getTableLocator()->get('EventLocations')->find()->where(['EventLocations.event_id'=>$this->currentSession->read('user.event.id'),'EventLocations.name' => $raw_str])->first();

            if ($location){
                $id = $location['id'];
            } else {
                $new_entry = array();
                $new_entry['name'] = $raw_str;
                $new_entry['event_id'] = $this->currentSession->read('user.event.id');

                $entity_data=TableRegistry::getTableLocator()->get('EventLocations')->newEntity($new_entry);

                TableRegistry::getTableLocator()->get('EventLocations')->save($entity_data);
                $id = $entity_data->id;
            }
        }

        return $id;
    }

    function allUserList(){
      $condition = array();
     /* if($this->request->is('post')){
           //$this->request->params['named']['page']=1;
           if($this->request->getData() !=''){
              $data= $this->request->getData();
              if(!empty($data['key'])){
                 $key= $data['key'];
                 $condition[]= "(Users.email LIKE '%".$key."%' OR Users.firstname LIKE '%".$key."%' OR Users.lastname LIKE '%".$key."%' OR Users.user_type LIKE '%".$key."%')";
                 $this->set('key',$data['key']);
              }
           }
        }*/
        $search= array();
        $params= $this->request->getAttribute('params')['pass'];
        if(count($params) > 0){
          foreach($params as $param){
            $param_arr= explode(":", $param);
            $search_key= $param_arr[0];
            $search_value= $param_arr[1];
            if($search_key=='search_key'){
              $search['key']= $search_value;
              $key = trim($search_value);
              $condition[]= "(Users.email LIKE '%".$key."%' OR Users.firstname LIKE '%".$key."%' OR Users.lastname LIKE '%".$key."%' OR Users.user_type LIKE '%".$key."%')";
             }
          }
        }

      $query =  TableRegistry::getTableLocator()->get('Users')->find('all')->where($condition);
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
            }
        }
      $users_data=$this->paginate($query);
      $this->set('users',$users_data);
      $this->set('search',$search);
    }

    public function deleteUsers($id){
      if(!$id){
          $this->Flash->error(__('Invalid id for user'));
          return $this->redirect(array('action' => 'index'));
      }
        $user = $this->Users->findById($id)->first();
        //remove all event relations
        $regs = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->findAllByUserId($id)->toArray();
        foreach($regs as $each){
            TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->deleteAll(['id'=>$each['id']]);
        }

        if(TableRegistry::getTableLocator()->get('Users')->deleteAll(['id'=>$id])){
            $this->Flash->success(__('User deleted'));
            return $this->redirect(array('action' => 'allUserList'));
        }else{
           $this->Flash->error(__('User was not deleted'));
           return $this->redirect(array('action' => 'allUserList')); 
        }
      
    }


    public function multiTenantUser(){
        $condition = array('Users.user_type' =>'client');
        /*$condition[] =  array(
            'OR' => array(
                array('Users.is_reseller' => 1),
                array('Users.is_whitelabel' => 1),
            )
        );*/
        $condition[] =  array(
            
                array('Users.is_whitelabel' => 1),
            
        );

        $search= array();
        $params= $this->request->getAttribute('params')['pass'];
        if($this->request->is('post')){
           //$this->request->params['named']['page']=1;
           if($this->request->getData() !=''){
              $data= $this->request->getData();
              if(!empty($data['key'])){
                 $key= $data['key'];
                 $condition[]= "(Users.email LIKE '%".$key."%' OR Users.firstname LIKE '%".$key."%' OR Users.lastname LIKE '%".$key."%')";
              }
           }
        }

        $query = TableRegistry::getTableLocator()->get('Users')->find()->contain(['OrganiserLoginRecords'])->where($condition);

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
            }
        }
        $users=$this->paginate($query);
        $this->set('users',$users);
        $this->set('search',$search);
        //pr($users->toArray()); exit;
    }

    public function addMultiTenantUser(){
        if($this->request->is(['post', 'put'])){    
            
            $userdata= $this->request->getData();
            $userdata['contact_tel'] = $userdata['contact_tel_areacode'] . "-" . $userdata['contact_tel_num'];
            $userdata['contact_fax'] = $userdata['contact_fax_areacode'] . "-" . $userdata['contact_fax_num'];
            $userdata['company_addr_country'] = strtoupper($userdata['company_addr_country']);
            $userdata['email'] = trim($userdata['email']);
            $userdata['org_admin']=1;
            $userdata['event_company_id']=2;

            
            $current_user = $this->Users->findByEmail($userdata['email'])->first();
            
            if ($current_user == null) {
                $new_password = $this->Users->generate_password(8);
                $userdata['password'] = $new_password;
                $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($userdata);
                if(TableRegistry::getTableLocator()->get('Users')->save($entity_data)){
                    $uid = $entity_data->id;
                    if($userdata['is_multi_tenant']==1){
                      $this->connection = ConnectionManager::get('default');
                      $query="UPDATE users SET is_multi_tenant='1', subdomain = '".$userdata['subdomain']."' WHERE users.id=".$uid."";
                      $this->connection->execute($query);
                    }
                    $user = $this->Users->findById($uid)->first();
                    require_once(ROOT . DS . 'vendor' . DS  . 'entmail.php');
                    
                    $mail = array('subject' => 'Your XPOBAY Password',
                        'mails' => array(
                            array('email' => $user['email'],
                                'firstname' => $user['firstname'],
                                'password' => $new_password)
                        )
                    );
                    //EntMail::sendMail($mail, 'password');
                    $this->Flash->success(__('The user has been saved.'));
                    return $this->redirect(array('action' => 'multiTenantUser'));
                } else {
                    $this->Flash->success(__('The user could not be saved. Please, try again.'));
                }
            } else { //exisitng user  
                $user_type= $current_user['user_type'];
                $this->Flash->error(__('User already exist as : '.$user_type));
            }
        }elseif (!empty($this->request->getData())){
            $this->Flash->error(__('Please Fill the form'));
        }
    }
    public function editMultiTenantUser($id = null) {
        if($this->request->is(['post', 'put'])){
            $userdata= $this->request->getData();
            /*$company_event = TableRegistry::getTableLocator()->get('Companies')->getCompanyByID($userdata['event_company_id']);
            $userdata['company_name'] = $company_event['company_name']*/;
            $userdata['contact_tel'] = $userdata['contact_tel_areacode'] . "-" . $userdata['contact_tel_num'];
            $userdata['contact_fax'] = $userdata['contact_fax_areacode'] . "-" . $userdata['contact_fax_num'];
            $userdata['company_addr_country'] = strtoupper($userdata['company_addr_country']);
            $userdata['event_company_id']=2;
            
           /* $reg_event_ids = $userdata['events'];
            unset($userdata['events']);*/

            $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($userdata);
            $entity_data->id= $id;
            //echo "<pre>"; print_r($entity_data); exit;
            if (TableRegistry::getTableLocator()->get('Users')->save($entity_data)){
                $uid = $id;
                if(!isset($userdata['is_multi_tenant'])){
                    $this->connection = ConnectionManager::get('default');
                    $query="UPDATE users SET is_multi_tenant='0', subdomain = '".$userdata['subdomain']."' WHERE users.id=".$uid."";  
                    $this->connection->execute($query);
                  }else{
                    $this->connection = ConnectionManager::get('default');
                    $query="UPDATE users SET is_multi_tenant='1', subdomain = '".$userdata['subdomain']."' WHERE users.id=".$uid."";  
                    $this->connection->execute($query);
                  }
                $regCurrentEventList = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.user_id' => $uid]);

                $currentEventList = array();
                foreach ($regCurrentEventList as $each) {
                    $currentEventList[] = $each['event_id'];
                }

                /*foreach ($reg_event_ids as $each_event_id) {
                    if (!in_array($each_event_id, $currentEventList)) { //if not have this entry, then add it
                        $reg_record = array();
                        $reg_record['event_id'] = $each_event_id;
                        $reg_record['user_id'] = $uid;
                        $reg_record['status'] = 'client';

                        $entity_data= TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->newEntity($reg_record);
                        TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->save($entity_data);
                    }
                }

                // delete extra event of organiser
                $toBeDelEvents=array_diff($currentEventList,$reg_event_ids);
                foreach($toBeDelEvents as $evntid){
                    TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->deleteAll(['ExhibitionRegistrations.event_id'=>$evntid,'ExhibitionRegistrations.user_id'=>$uid]);
                }*/
                
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(array('action' => 'multiTenantUser'));
            }else{
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        } elseif (!empty($this->request->getData())){
            $this->Flash->error(__('Please Fill Up the required fields'));
        }

        $client = $this->Users->findById($id)->first();

        if($client['user_type'] != "client"){
            $this->Flash->error(__('Invalid Client'));
            $this->redirect(array('action' => 'list_clients'));
            return;
        }

        $regs = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->findAllByUserId($id);

        $client['events'] = array();
        foreach ($regs as $each) {
            $client['events'][] = $each['event_id'];
        }

        $tmp = explode('-', $client['contact_tel']);
        $client['contact_tel_areacode'] = $tmp[0];
        $client['contact_tel_num'] = $tmp[1];
        
        $tmp = explode('-', $client['contact_fax']);
        $client['contact_fax_areacode'] = $tmp[0];
        $client['contact_fax_num'] = $tmp[1];
        $this->connection = ConnectionManager::get('default');
        $query="SELECT is_multi_tenant,subdomain FROM users  WHERE users.id=".$id."";  
        $res = $this->connection->execute($query)->fetch('assoc');
        $client['is_multi_tenant'] = $res['is_multi_tenant'];
        $client['subdomain'] = $res['subdomain'];

        $this->set('client',$client);
        
        $company_id=$client['event_company_id'];
        $events= array();
        // if($company_id){
        //     $events = TableRegistry::getTableLocator()->get('Events')->find('list', ['keyField'=>'id','valueField'=>'name'])->where(['Events.company_id'=>$company_id])->toArray();
        // }
        
        // $this->set(compact('events')); 
        
        $companies=TableRegistry::getTableLocator()->get('Companies')->find();
        $this->set('companies', $companies);
    }

    public function addSuperUserAjax(){
        $this->autoRender=false;
        $this->autoLayout=false;
        $this->layout='ajax';
        $userdata=$this->request->getData();

        $current_user = $this->Users->findByEmail($userdata['email'])->first();
            
            if ($current_user == null) {
                $new_password = $this->Users->generate_password(8);
                $userdata['password'] = $new_password;
                $userdata['org_admin'] = 1;
                $userdata['user_type']='client';
                $entity_data= TableRegistry::getTableLocator()->get('Users')->newEntity($userdata);
                if(TableRegistry::getTableLocator()->get('Users')->save($entity_data)){
                    $uid = $entity_data->id;
                    /*if($userdata['is_multi_tenant']==1){
                      $this->connection = ConnectionManager::get('default');
                      $query="UPDATE users SET is_multi_tenant='1', subdomain = '".$userdata['subdomain']."' WHERE users.id=".$uid."";
                      $this->connection->execute($query);
                    }
                    $user = $this->Users->findById($uid)->first();
                    require_once(ROOT . DS . 'vendor' . DS  . 'entmail.php');
                    
                    $mail = array('subject' => 'Your XPOBAY Password',
                        'mails' => array(
                            array('email' => $user['email'],
                                'firstname' => $user['firstname'],
                                'password' => $new_password)
                        )
                    );
                    EntMail::sendMail($mail, 'password')*/;
                    echo $uid;
                    exit;
                } else {
                    $this->Flash->success(__('The user could not be saved. Please, try again.'));
                }
            } else { //exisitng user  
                $user_type= $current_user['user_type'];
                echo "exist";
                exit();
            }
    }

//=============================================
}

//===== few functions outside of class =======
function array_has_dupes($array) {
    $dupe_array = array();
    foreach ($array as $val) {
        if (in_array($val, $dupe_array)) {
            return true;
        } else {
            $dupe_array[] = $val;
        }
    }
    return false;
}

function convert_raw_tel_number($raw_str) {
    $raw_str = trim($raw_str);
    $raw_str = trim($raw_str, '-');

    $frt_space_pos = strpos($raw_str, ' ');
    $frt_hyphen_pos = strpos($raw_str, '-');
    $frt_left_b_pos = strpos($raw_str, '[');
    $frt_right_b_pos = strpos($raw_str, ']');
    if ($frt_left_b_pos === false && $frt_right_b_pos == false) {
        $frt_left_b_pos = strpos($raw_str, '(');
        $frt_right_b_pos = strpos($raw_str, ')');
    }

    $tel_region = '';
    $tel_number = '';
    if ($frt_hyphen_pos > 0) {
        $tel_region = str_filter_numeric(substr($raw_str, 0, $frt_hyphen_pos));
        $tel_number = str_filter_numeric(substr($raw_str, $frt_hyphen_pos + 1));
    } else if ($frt_space_pos > 0) {
        $tel_region = str_filter_numeric(substr($raw_str, 0, $frt_space_pos));
        $tel_number = str_filter_numeric(substr($raw_str, $frt_space_pos + 1));
    } else if ($frt_right_b_pos !== false && $frt_left_b_pos !== false
            && ($frt_right_b_pos - $frt_left_b_pos) > 1) {
        $tel_region = str_filter_numeric(substr($raw_str, 0, $frt_right_b_pos));
        $tel_number = str_filter_numeric(substr($raw_str, $frt_right_b_pos + 1));
    } else {
        $tel_region = '';
        $tel_number = str_filter_numeric($raw_str);
    }

    $tel_region = str_replace('-', '', $tel_region);
    $tel_region = str_replace(' ', '', $tel_region);
    $tel_region = str_replace('[', '', $tel_region);
    $tel_region = str_replace(']', '', $tel_region);
    $tel_region = str_replace('(', '', $tel_region);
    $tel_region = str_replace(')', '', $tel_region);

    $tel_number = str_replace('-', '', $tel_number);
    $tel_number = str_replace(' ', '', $tel_number);
    $tel_number = str_replace('[', '', $tel_number);
    $tel_number = str_replace(']', '', $tel_number);
    $tel_number = str_replace('(', '', $tel_number);
    $tel_number = str_replace(')', '', $tel_number);
    return array('region' => $tel_region, 'number' => $tel_number);
}

function str_filter_numeric($raw) {
    $str = '';
    for ($i = 0; $i < strlen($raw); $i++) {
        $char = $raw[$i];
        if (is_numeric($char)) {
            $str .= $char;
        }
    }
    return $str;
}

function country() {
    $countries = array(
        'AF' => 'Afganistan',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegowina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, the Democratic Republic of the',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote d\'Ivoire',
        'HR' => 'Croatia (Hrvatska)',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'TP' => 'East Timor',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'FX' => 'France, Metropolitan',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard and Mc Donald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran (Islamic Republic of)',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea, Democratic People\'s Republic of',
        'KR' => 'Korea, Republic of',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macau',
        'MK' => 'Macedonia, The Former Yugoslav Republic of',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States of',
        'MD' => 'Moldova, Republic of',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint LUCIA',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SH' => 'St. Helena',
        'PM' => 'St. Pierre and Miquelon',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen Islands',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan, Province of China',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania, United Republic of',
        'TH' => 'Thailand',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Minor Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands (British)',
        'VI' => 'Virgin Islands (U.S.)',
        'WF' => 'Wallis and Futuna Islands',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'YU' => 'Yugoslavia',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );

    
    return  $countries;
}

function country_code_to_name($code) {
    $countries = array(
        'AF' => 'Afganistan',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegowina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, the Democratic Republic of the',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote d\'Ivoire',
        'HR' => 'Croatia (Hrvatska)',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'TP' => 'East Timor',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'FX' => 'France, Metropolitan',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard and Mc Donald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran (Islamic Republic of)',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea, Democratic People\'s Republic of',
        'KR' => 'Korea, Republic of',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macau',
        'MK' => 'Macedonia, The Former Yugoslav Republic of',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States of',
        'MD' => 'Moldova, Republic of',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint LUCIA',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SH' => 'St. Helena',
        'PM' => 'St. Pierre and Miquelon',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen Islands',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan, Province of China',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania, United Republic of',
        'TH' => 'Thailand',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Minor Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands (British)',
        'VI' => 'Virgin Islands (U.S.)',
        'WF' => 'Wallis and Futuna Islands',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'YU' => 'Yugoslavia',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );

    $code = strtoupper(trim($code));
    if (array_key_exists($code, $countries)) {
        return $countries[$code];
    }
    return false;
}

function country_name_to_code($name) {
    $countries = array(
        'AF' => 'Afganistan',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegowina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, the Democratic Republic of the',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote d\'Ivoire',
        'HR' => 'Croatia (Hrvatska)',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'TP' => 'East Timor',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'FX' => 'France, Metropolitan',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard and Mc Donald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran (Islamic Republic of)',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea, Democratic People\'s Republic of',
        'KR' => 'Korea, Republic of',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macau',
        'MK' => 'Macedonia, The Former Yugoslav Republic of',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States of',
        'MD' => 'Moldova, Republic of',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint LUCIA',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SH' => 'St. Helena',
        'PM' => 'St. Pierre and Miquelon',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen Islands',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan, Province of China',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania, United Republic of',
        'TH' => 'Thailand',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Minor Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands (British)',
        'VI' => 'Virgin Islands (U.S.)',
        'WF' => 'Wallis and Futuna Islands',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'YU' => 'Yugoslavia',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );

    $name = strtoupper(trim($name));
    foreach ($countries as $code => $country) {
        if (strtoupper($country) == $name) {
            return $code;
        }
    }
    return false;
}
?>
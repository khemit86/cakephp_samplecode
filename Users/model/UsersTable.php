<?php 
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\View\Helper\HtmlHelper;
//use EntMail;
use SesMail;
class UsersTable extends Table
{
    public function validationDefault(Validator $validator): Validator
    {
        return $validator
            ->notEmptyString('email', 'An email is required')
            ->email('email')
            ->notEmptyString('password', 'A password is required')
            ->notEmptyString('role', 'A role is required')
            ->add('role', 'inList', [
                'rule' => ['inList', ['admin', 'author']],
                'message' => 'Please enter a valid role'
            ]);
    }
	
	/* Validation for Login Api */
	public function validationLoginApi(Validator $validator): Validator
    {
       
        $validator
			->requirePresence('email')
            ->email('email')
            ->notEmptyString('email');

        $validator
			->requirePresence('password')
            ->scalar('password')
            ->maxLength('password', 255)
            ->notEmptyString('password');

        return $validator;
    }
	
	/* Validation for add exhibitor Api */
	public function validationAddExhibitorApi(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
			 ->requirePresence('firstname')
            ->scalar('firstname')
            ->maxLength('firstname', 255)
            ->notEmptyString('firstname');

        $validator
			->requirePresence('lastname')
            ->scalar('lastname')
            ->maxLength('lastname', 255)
            ->notEmptyString('lastname');

        $validator
			->requirePresence('email')
            ->email('email')
			->add('email', [
				'unique' => [
					'rule' => [
					  'validateUnique'
					],
					'message'=>'Please enter unique email.',
					'provider' => 'table'
				]
			])
            ->notEmptyString('email');
		 $validator
			->requirePresence('event_id')
            ->notEmptyString('event_id');
		$validator
			->requirePresence('user_types')
            ->notEmptyString('user_types');	
		$validator
			->requirePresence('user_category_id')
            ->notEmptyString('user_category_id');			
		$validator->add('preferred_language', 'custom', [
			'rule' => function ($value, $context) {
				// Custom logic that returns true/false
				if(!in_array($value, array('english','chinese','thai'))){
					return false;
				}else{
					return true;
				}
			},
			'message' => 'Language option should be english,chinese,thai'
		]);
        return $validator;
    }
	
	/* Validation for edit exhibitor Api */
	public function validationEditExhibitorApi(Validator $validator): Validator
    {
		
        $validator
			->requirePresence('user_id')
            ->notEmptyString('user_id');
		$validator
			->requirePresence('uid')
            ->notEmptyString('uid');
        $validator
			 ->requirePresence('firstname')
            ->scalar('firstname')
            ->maxLength('firstname', 255)
            ->notEmptyString('firstname');

        $validator
			->requirePresence('lastname')
            ->scalar('lastname')
            ->maxLength('lastname', 255)
            ->notEmptyString('lastname');
		$validator
			->requirePresence('user_types')
            ->notEmptyString('user_types');	
			
		$validator
			->requirePresence('user_category_id')
            ->notEmptyString('user_category_id');	
			
		$validator->add('preferred_language', 'custom', [
			'rule' => function ($value, $context) {
				// Custom logic that returns true/false
				if(!in_array($value, array('english','chinese','thai'))){
					return false;
				}else{
					return true;
				}
			},
			'message' => 'Language option should be english,chinese,thai'
		]);
        return $validator;
    }
	
	/* Validation for update custom field for exhibitor Api */
	public function validationUpdateExhibitorCustomFieldsApi(Validator $validator): Validator
    {
		$validator
			->requirePresence('event_id')
            ->notEmptyString('event_id');
        $validator
			->requirePresence('user_id')
            ->notEmptyString('user_id');
		$validator
			->requirePresence('field_id')
			->notEmptyString('field_id');
		$validator
			->requirePresence('field_value')
			->notEmptyString('field_value');
        
        return $validator;
    }
	
	/* Validation for delete custom field for exhibitor Api */
	public function validationDeleteExhibitorCustomFieldsApi(Validator $validator): Validator
    {
		$validator
			->requirePresence('event_id')
            ->notEmptyString('event_id');
        $validator
			->requirePresence('user_id')
            ->notEmptyString('user_id');
		$validator
			->requirePresence('field_id')
			->notEmptyString('field_id');
        
        return $validator;
    }

    public function initialize(array $config): void
    {
        $this->addBehavior('EnhancedFinder');
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'updated' => 'always'
                ]
            ]
        ]);

        // has many
        $this->hasMany('OrganiserLoginRecords', [
                'className' => 'OrganiserLoginRecords'
            ])
            ->setForeignKey('user_id')
            ->setSort(['OrganiserLoginRecords.id' => 'DESC']);  
        
    }

//=====================================================

public static function encode_password($code){
    return md5($code);
}

public static function generate_password($length = 9){
    $vowels = 'aeuyAEUY';
    $consonants = 'bdghjmnpqrstvzBDGHJLMNPQRSTVWXZ23456789';

    $password = '';
    $alt = time() % 2;
    for ($i = 0; $i < $length; $i++) {
        if ($alt == 1) {
            $password .= $consonants[(rand() % strlen($consonants))];
            $alt = 0;
        } else {
            $password .= $vowels[(rand() % strlen($vowels))];
            $alt = 1;
        }
    }
    return $password;
}

public function userDetails($user_id){
   $data= TableRegistry::getTableLocator()->get('Users')->find()->where(['Users.id' => $user_id])->first();
   return $data;
}


function getUserCompanyById($user_id) {
    return $this->field('event_company_id', array('id' => $user_id));
}

public function isOrgAdmin($user_id){
   $data= TableRegistry::getTableLocator()->get('Users')->find()->where(['Users.id' => $user_id])->first();
   if($data['org_admin']==1){
      return true;
   }else{
      return false;
   }
}


public function sendWelcomeEmailToImportedExhibFromSalesForce($reg_ids){
    //require_once(ROOT . DS . 'vendor' . DS  . 'entmail.php');
    require_once(ROOT . DS . 'vendor' . DS  . 'sesmail.php');
    $html = new HtmlHelper(new \Cake\View\View());
    
    foreach($reg_ids as $reg_id){
       // get exhibitor info
        $exhib = TableRegistry::getTableLocator()->get('ExhibitionRegistrations')->find()->where(['ExhibitionRegistrations.id'=>$reg_id])->contain(['Users','Events','EventDimensions','EventLocations','EventBoothTypes'])->first();
      
       // if organiser allowed - to send welocme email to imported exhibitor from salesforce
       if($exhib['event']['send_selesforce_welcome_email']==1){
          
           // save user email record
           $emailEntry = array('user_id'=>$exhib['user']['id'],'reg_id'=>$reg_id,'created'=>date('Y-m-d H:i:s'));
           
           $email_rec = TableRegistry::getTableLocator()->get('UserEmailRecords')->v4find('first', array("conditions" => array('user_id'=>$exhib['user']['id'], 'reg_id'=>$reg_id)));

           if($email_rec){
              $emailEntry['id'] = $email_rec['id'];
           }

           TableRegistry::getTableLocator()->get('UserEmailRecords')->v4save($emailEntry);

           //-------------Now send welcome email-----------------------
           //$welcome_template_content = EntMail::getTemplateContent('event_welcome');
           $welcome_template_content = SesMail::getTemplateContent('event_welcome');

           if($welcome_template_content !== false){
              $user_auto_login_url = Router::url(array('controller'=>'System','action'=>'login',$exhib['user']['id'],base64_encode($exhib['user']['password']),$exhib['event']['id'], $reg_id), true);

              $user_login_link = $html->link('Click here to login', $user_auto_login_url);
              
              $user_password_reset_url = Router::url(array('controller'=>'System','action'=>'reset_password', $exhib['user']['id'], base64_encode($exhib['user']['password']),$exhib['event']['id']), true);

              $user_password_reset_link="<a href=\"{$user_password_reset_url}\" target=\"_blank\">Set Password</a>";
       
              $subject= str_replace("%%EVENT_NAME%%",$exhib['event']['name'],$exhib['event']['email_subject']);

              $mail = array('subject' => $subject,
                'mails' => array(
                    array(
                        //%%EMAIL_CONTENT%% needs to be the first element to be replaced
                        'EMAIL_CONTENT' => $welcome_template_content,
                        'email' => $exhib['user']['email'],
                        'EMAIL' => $exhib['user']['email'],
                        'FIRST_NAME' => $exhib['user']['firstname'],
                        'LAST_NAME' => $exhib['user']['lastname'],
                        'FIRSTNAME' => $exhib['user']['firstname'],
                        'LASTNAME'  => $exhib['user']['lastname'],
                        'EVENT_NAME' => $exhib['event']['name'],
                        'LOGIN_URL' => $user_auto_login_url,
                        'LOGIN_LINK' => $user_login_link,
                        'PASSWORD_RESET_URL' => $user_password_reset_url,
                        'PASSWORD_RESET_LINK' => $user_password_reset_link
                    )
                )
              );


              if(strlen($exhib['event']['event_email_sender']) > 3 && strlen($exhib['event']['event_email_address']) > 6 && strpos($exhib['event']['event_email_address'], '@') !== false){
                
                    $mail['sender_name'] = $exhib['event']['event_email_sender'];
                    $mail['sender_email'] = $exhib['event']['event_email_address'];
                    $mail['reply_to_name'] = $exhib['event']['event_email_sender'];
                    $mail['reply_to_email'] = ($exhib['event']['reply_email']) ? $exhib['event']['reply_email'] : $exhib['event']['event_email_address'];
            
              }else if($exhib['event']['reply_email']){
                   $mail['reply_to_email'] = $exhib['event']['reply_email'];
              }

              if($exhib['event']['event_email_sender']){
                 $mail['sender_name'] = $exhib['event']['event_email_sender'];
              }

           }

       } 
    
    }
          
}


}
?>
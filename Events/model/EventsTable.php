<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
class EventsTable extends Table
{
	private $connection;
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

        $this->connection = ConnectionManager::get('default');
    }

//==============================================

public function getNameById($event_id) {
	return $this->field('name', array('id' => $event_id));
}

public function getIdByName($event_name) {
	return $this->field('id', array('name' => $event_name));
}

public function getCompanyEvents($event_company_id) {
	$conditions = array('company_id' => $event_company_id);
	$fields = array('id', 'name');
	$events = $this->v4find('all', array('conditions' => $conditions, 'fields' => $fields));
	$result = array();
	foreach ($events as $event) {
		$result[] = array('id' => $event['id'], 'name' => $event['name']);
	}
	return $result;
}


public function getEventDetails($event_id){	
   if($event_id){
   	  $data= TableRegistry::getTableLocator()->get('Events')->find()->where(['Events.id' => $event_id])->first();
   	  return $data;
   }else{
   	  return false;
   }
   
   
}



//* functions regarding
//* get and import form 



public function getFormByEventId($event_id){
   $query="SELECT ap_forms.form_id,ap_forms.form_name,ap_forms.form_description FROM ap_forms WHERE ap_forms.ent_event_id=".$event_id." AND ap_forms.form_active='1' ";
   //$result=$this->query($query);
   $result=$this->connection->execute($query)->fetchAll('assoc');
   return $result;
}

public function getDefaultTheme($event){
	$query="SELECT * FROM ap_form_themes WHERE ap_form_themes.theme_name='".$event['name']."' AND ap_form_themes.company_id='".$event['company_id']."'";
    $result=$this->connection->execute($query)->fetch('assoc');
    return $result;
    
}


public function copyApForm($form_id,$current_event_id,$default_theme_id,$exhibitor_types,$booth_Types){
   // select form to be coppied
   $query="SELECT * FROM ap_forms WHERE ap_forms.form_id=".$form_id."";
   $form=$this->connection->execute($query)->fetch('assoc');
  
   //*copy selected form as a new record

   //generate random form_id number, based on existing value
   $query = "SELECT max(form_id) max_form_id FROM ap_forms";
   $max=$this->connection->execute($query)->fetch('assoc');
   $max_form_id= $max['max_form_id'];
   $new_form_id = $max_form_id + rand(100,1000);

   $this->connection->insert('ap_forms', [
					    'form_id'=>$new_form_id,
			            'form_name'=> $form['form_name'],
			            'form_description' => $form['form_description'],
			            'form_name_hide' => $form['form_name_hide'],
			            'form_tags' => $form['form_tags'],
			            'form_email' => $form['form_email'],
			            'form_redirect' => $form['form_redirect'],
			            'form_redirect_enable' => $form['form_redirect_enable'],
			            'form_success_message' => $form['form_success_message'],
			            'form_disabled_message' => $form['form_disabled_message'],
			            'form_password' => $form['form_password'],
			            'form_unique_ip' => $form['form_unique_ip'],
			            'form_unique_ip_maxcount' => $form['form_unique_ip_maxcount'],
			            'form_unique_ip_period' => $form['form_unique_ip_period'],
			            'form_frame_height' => $form['form_frame_height'],
			            'form_has_css' => '1',
			            'form_captcha' => $form['form_captcha'],
			            'form_captcha_type' => $form['form_captcha_type'],
			            'form_active' => $form['form_active'],
			            'form_theme_id' => $default_theme_id,
			            'form_review' => $form['form_review'],
			            'form_resume_enable' => $form['form_resume_enable'],
			            'form_resume_subject' => $form['form_resume_subject'],
			            'form_resume_content' => $form['form_resume_content'],
			            'form_resume_from_name' => $form['form_resume_from_name'],
			            'form_resume_from_email_address' => $form['form_resume_from_email_address'],
			            'form_custom_script_enable' => $form['form_custom_script_enable'],
			            'form_custom_script_url' => $form['form_custom_script_url'],
			            'form_limit_enable' => $form['form_limit_enable'],
			            'form_limit' => $form['form_limit'],
			            'form_label_alignment' => $form['form_label_alignment'],
			            'form_language' => $form['form_language'],
			            'form_page_total' => $form['form_page_total'],
			            'form_lastpage_title' => $form['form_lastpage_title'],
			            'form_submit_primary_text' => $form['form_submit_primary_text'],
			            'form_submit_secondary_text' => $form['form_submit_secondary_text'],
			            'form_submit_primary_img' => $form['form_submit_primary_img'],
			            'form_submit_secondary_img' => $form['form_submit_secondary_img'],
			            'form_submit_use_image' => $form['form_submit_use_image'],
			            'form_review_primary_text' => $form['form_review_primary_text'],
			            'form_review_secondary_text' => $form['form_review_secondary_text'],
			            'form_review_primary_img' => $form['form_review_primary_img'],
			            'form_review_secondary_img' => $form['form_review_secondary_img'],
			            'form_review_use_image' => $form['form_review_use_image'],
			            'form_review_title' => $form['form_review_title'],
			            'form_review_description' => $form['form_review_title'],
			            'form_pagination_type' => $form['form_pagination_type'],
			            'form_schedule_enable' => $form['form_schedule_enable'],
			            'form_schedule_start_date' => $form['form_schedule_start_date'],
			            'form_schedule_end_date' => $form['form_schedule_end_date'],
			            'form_schedule_start_hour' => $form['form_schedule_start_hour'],
			            'form_schedule_end_hour' => $form['form_schedule_end_hour'],
			            'esl_enable' => $form['esl_enable'],
			            'esl_from_name' => $form['esl_from_name'],
			            'esl_from_email_address' => $form['esl_from_email_address'],
			            'esl_bcc_email_address' => $form['esl_bcc_email_address'],
			            'esl_replyto_email_address' => $form['esl_replyto_email_address'],
			            'esl_subject' => $form['esl_subject'],
			            'esl_content' => $form['esl_content'],
			            'esl_plain_text' => $form['esl_plain_text'],
			            'esl_pdf_enable' => $form['esl_pdf_enable'],
			            'esl_pdf_content' => $form['esl_pdf_content'],
			            'esr_enable' => $form['esr_enable'],
			            'esr_email_address' => $form['esr_email_address'],
			            'esr_from_name' => $form['esr_from_name'],
			            'esr_from_email_address' => $form['esr_from_email_address'],
			            'esr_bcc_email_address' => $form['esr_bcc_email_address'],
			            'esr_replyto_email_address' => $form['esr_replyto_email_address'],
			            'esr_subject' => $form['esr_subject'],
			            'esr_content' => $form['esr_content'],
			            'esr_plain_text' => $form['esr_plain_text'],
			            'esr_pdf_enable' => $form['esr_pdf_enable'],
			            'esr_pdf_content' => $form['esr_pdf_content'],
			            'payment_enable_merchant' => $form['payment_enable_merchant'],
			            'payment_merchant_type' => $form['payment_merchant_type'],
			            'payment_paypal_email' => $form['payment_paypal_email'],
			            'payment_paypal_language' => $form['payment_paypal_language'],
			            'payment_currency' => $form['payment_currency'],
			            'payment_show_total' => $form['payment_show_total'],
			            'payment_total_location' => $form['payment_total_location'],
			            'payment_enable_recurring' => $form['payment_enable_recurring'],
			            'payment_recurring_cycle' => $form['payment_recurring_cycle'],
			            'payment_recurring_unit' => $form['payment_recurring_unit'],
			            'payment_enable_trial' => $form['payment_enable_trial'],
			            'payment_trial_period' => $form['payment_trial_period'],
			            'payment_trial_unit' => $form['payment_trial_unit'],
			            'payment_trial_amount' => $form['payment_trial_amount'],
			            'payment_enable_setupfee' => $form['payment_enable_setupfee'],
			            'payment_setupfee_amount' => $form['payment_setupfee_amount'],
			            'payment_price_type' => $form['payment_price_type'],
			            'payment_price_amount' => $form['payment_price_amount'],
			            'payment_price_name' => $form['payment_price_name'],
			            'payment_stripe_live_secret_key' => $form['payment_stripe_live_secret_key'],
			            'payment_stripe_live_public_key' => $form['payment_stripe_live_public_key'],
			            'payment_stripe_test_secret_key' => $form['payment_stripe_test_secret_key'],
			            'payment_stripe_test_public_key' => $form['payment_stripe_test_public_key'],
			            'payment_stripe_enable_test_mode' => $form['payment_stripe_enable_test_mode'],
			            'payment_stripe_enable_receipt' => $form['payment_stripe_enable_receipt'],
			            'payment_stripe_receipt_element_id' => $form['payment_stripe_receipt_element_id'],
			            'payment_paypal_rest_live_clientid' => $form['payment_paypal_rest_live_clientid'],
			            'payment_paypal_rest_live_secret_key' => $form['payment_paypal_rest_live_secret_key'],
			            'payment_paypal_rest_test_clientid' => $form['payment_paypal_rest_test_clientid'],
			            'payment_paypal_rest_test_secret_key' => $form['payment_paypal_rest_test_secret_key'],
			            'payment_paypal_rest_enable_test_mode' => $form['payment_paypal_rest_enable_test_mode'],
			            'payment_authorizenet_live_apiloginid' => $form['payment_authorizenet_live_apiloginid'],
			            'payment_authorizenet_live_transkey' => $form['payment_authorizenet_live_transkey'],
			            'payment_authorizenet_test_apiloginid' =>$form['payment_authorizenet_test_apiloginid'], 
			            'payment_authorizenet_test_transkey' => $form['payment_authorizenet_test_transkey'], 
			            'payment_authorizenet_enable_test_mode' => $form['payment_authorizenet_enable_test_mode'], 
			            'payment_authorizenet_save_cc_data' => $form['payment_authorizenet_save_cc_data'],
			            'payment_braintree_live_merchant_id' => $form['payment_braintree_live_merchant_id'],
			            'payment_braintree_live_public_key' => $form['payment_braintree_live_public_key'],
			            'payment_braintree_live_private_key' => $form['payment_braintree_live_private_key'],
			            'payment_braintree_live_encryption_key' => $form['payment_braintree_live_encryption_key'],
			            'payment_braintree_test_merchant_id' => $form['payment_braintree_test_merchant_id'],
			            'payment_braintree_test_public_key' => $form['payment_braintree_test_public_key'],
			            'payment_braintree_test_private_key' => $form['payment_braintree_test_private_key'],
			            'payment_braintree_test_encryption_key' => $form['payment_braintree_test_encryption_key'],
			            'payment_braintree_enable_test_mode' => $form['payment_braintree_enable_test_mode'],
			            'payment_paypal_enable_test_mode' => $form['payment_paypal_enable_test_mode'],
			            'payment_enable_invoice' => $form['payment_enable_invoice'],
			            'payment_invoice_email' => $form['payment_invoice_email'],
			            'payment_delay_notifications' => $form['payment_delay_notifications'],
			            'payment_ask_billing' => $form['payment_ask_billing'],
			            'payment_ask_shipping' => $form['payment_ask_shipping'],
			            'payment_enable_tax' => $form['payment_enable_tax'],
			            'payment_tax_rate' => $form['payment_tax_rate'],
			            'payment_enable_discount' => $form['payment_enable_discount'],
			            'payment_discount_type' => $form['payment_discount_type'],
			            'payment_discount_amount' => $form['payment_discount_amount'],
			            'payment_discount_code' => $form['payment_discount_code'],
			            'payment_discount_element_id' => $form['payment_discount_element_id'],
			            'payment_discount_max_usage' => $form['payment_discount_max_usage'],
			            'payment_discount_expiry_date' => $form['payment_discount_expiry_date'],
			            'logic_field_enable' => $form['logic_field_enable'],
			            'logic_page_enable' => $form['logic_page_enable'],
			            'logic_email_enable' => $form['logic_email_enable'],
			            'logic_webhook_enable' => $form['logic_webhook_enable'],
			            'logic_success_enable' => $form['logic_success_enable'],
			            'webhook_enable' => $form['webhook_enable'],
			            'webhook_url' => $form['webhook_url'],
			            'webhook_method' => $form['webhook_method'],
			            
			            'form_exhibitor_types' => $form['form_exhibitor_types'],
			            'form_booth_types' => $form['form_booth_types'],
			            'form_deadline' => $form['form_deadline'],
			            'ent_event_id' => $current_event_id,
			            'ent_exhibitor_types' => $exhibitor_types,
			            'ent_booth_types' => $booth_Types,
			            //'ent_deadline' => '',
			            'ent_deadline' => $form['ent_deadline'],

			            'payment_securepay_merchant_id' => $form['payment_securepay_merchant_id'],
			            'payment_securepay_merchant_password' => $form['payment_securepay_merchant_password'],
			            'payform'=>$form['payform']
					]);
   
   return $new_form_id;

}


public function copyApPermission($form_id,$new_form_id){
	// select ap_permissions to be coppied
    $query="SELECT * FROM ap_permissions WHERE ap_permissions.form_id=".$form_id."";
    $permission=$this->connection->execute($query)->fetch('assoc');
    
	$this->connection->insert('ap_permissions', [
					     'form_id'=>$new_form_id,
					     'user_id'=>$permission['user_id'],
					     'edit_form'=>$permission['edit_form'],
					     'edit_entries'=>$permission['edit_entries'],
			             'view_entries'=>$permission['view_entries']
					]);

}


public function createApFormTable($form_id,$new_form_id){
    //copy table structure ap_form_{$form_id}
    $query="create TABLE ap_form_{$new_form_id} like ap_form_{$form_id}";
    $this->connection->execute($query);
}

public function createApFormLogTable($form_id,$new_form_id){
    //copy table structure ap_form_{$form_id}_log
    $exist = $this->connection->execute("show tables like 'ap_form_{$form_id}_log'")->fetch('assoc');
    if($exist){
    	$query="create TABLE ap_form_{$new_form_id}_log like ap_form_{$form_id}_log";
        $this->connection->execute($query);
    }
    
}

public function createApFormFileTable($form_id,$new_form_id){
    //check table exist
    $exist = $this->connection->execute("show tables like 'form_{$form_id}_files'")->fetch('assoc');
    if($exist){
       //copy table structure form_{$form_id}_files
       $query="create TABLE form_{$new_form_id}_files like form_{$form_id}_files";
       $this->connection->execute($query);
    }
    
}

public function copyApFormElement($form_id,$new_form_id){
	// select ap_form_elements to be coppied
    $query="SELECT * FROM ap_form_elements WHERE ap_form_elements.form_id=".$form_id."";
    $result = $this->connection->execute($query)->fetch('assoc');

    if(!empty($result)){

       $tno= time();

       $query="CREATE TEMPORARY TABLE tmptable_{$tno} SELECT * FROM ap_form_elements WHERE form_id = ".$form_id." ";
	   //$this->query($query);
	   $this->connection->execute($query);
	   // update form id with new form id
	   $query="UPDATE tmptable_{$tno} SET form_id = ".$new_form_id."";
	   $this->connection->execute($query);

	   $query="INSERT INTO ap_form_elements SELECT * FROM tmptable_{$tno}";
	   $this->connection->execute($query);
	   // drop temp table
	   $query="DROP TEMPORARY TABLE IF EXISTS tmptable_{$tno}";
	   $this->connection->execute($query);

    }

}


public function copyApFormElementOption($form_id,$new_form_id){
	// select ap_element_options to be coppied
    $query="SELECT * FROM ap_element_options WHERE ap_element_options.form_id=".$form_id."";
    $result = $this->connection->execute($query)->fetchAll('assoc');
    
    if(!empty($result)){
    	foreach($result as $option){

    		$sql="INSERT ap_element_options SET 
			             form_id='".$new_form_id."',
			             element_id='".$option['element_id']."',
			             option_id='".$option['option_id']."',
			             position='".$option['position']."',
			             `option`='".addslashes($option['option'])."',
			             option_is_default='".$option['option_is_default']."',
			             live='".$option['live']."'";

			$this->connection->execute($sql); 

    	}

    }

          
}


public function copyApFormElementPrice($form_id,$new_form_id){
	// select ap_element_prices to be coppied
    $query="SELECT * FROM ap_element_prices WHERE ap_element_prices.form_id=".$form_id."";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){
       foreach($result as $price){

       	  $sql="INSERT ap_element_prices SET 
			             form_id='".$new_form_id."',
			             element_id='".$price['element_id']."',
			             option_id='".$price['option_id']."',
			             price='".$price['price']."'";
          
          $this->connection->execute($sql); 
       
       }
       

    }

	        
}


public function copyApFormLocks($form_id,$new_form_id){
	$tno= time();
	// if row exist
	$query="SELECT * FROM ap_form_locks WHERE ap_form_locks.form_id=".$form_id."";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){
    	$query="CREATE TEMPORARY TABLE tmptable_lock_{$tno} SELECT * FROM ap_form_locks WHERE form_id = ".$form_id."";
	    $this->connection->execute($query);

	    // update form id with new form id
	    $query="UPDATE tmptable_lock_{$tno} SET form_id = ".$new_form_id." ";
	    $this->connection->execute($query);

	    $query="INSERT INTO ap_form_locks SELECT * FROM tmptable_lock_{$tno} ";
	    $this->connection->execute($query);
        // drop temp table
        $query="DROP TEMPORARY TABLE IF EXISTS tmptable_lock_{$tno} ";
	    $this->connection->execute($query);

    }

}


public function copyApFormEmailLogic($form_id,$new_form_id){
	$tno= time();
	// if row exist
	$query="SELECT * FROM ap_email_logic WHERE ap_email_logic.form_id=".$form_id."";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){

    	$query="CREATE TEMPORARY TABLE tmptable_logic_{$tno} SELECT * FROM ap_email_logic WHERE form_id=".$form_id."";
	    $this->connection->execute($query);

	    // update form id with new form id
	    $query="UPDATE tmptable_logic_{$tno} SET form_id = ".$new_form_id." ";
	    $this->connection->execute($query);

	    $query="INSERT INTO ap_email_logic SELECT * FROM tmptable_logic_{$tno} ";
	    $this->connection->execute($query);
        // drop temp table
        $query="DROP TEMPORARY TABLE IF EXISTS tmptable_logic_{$tno} ";
	    $this->connection->execute($query);

    }

}


public function copyApFormEmailLogicCondition($form_id,$new_form_id){
	// if row exist in ap_email_logic_conditions
	$query="SELECT * FROM ap_email_logic_conditions WHERE ap_email_logic_conditions.form_id=".$form_id."";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){

    	foreach($result as $lc){

       	  $sql="INSERT ap_email_logic_conditions SET 
			             form_id='".$new_form_id."',
			             target_rule_id='".$lc['target_rule_id']."',
			             element_name='".$lc['element_name']."',
			             rule_condition='".$lc['rule_condition']."',
			             rule_keyword='".$lc['rule_keyword']."'";
          
          $this->connection->execute($sql);
       
       }
    }

}


public function copyApFormPermission($form_id,$new_form_id){
	$tno= time();
	// if row exist
	
	$query="SELECT * FROM ap_permissions WHERE ap_permissions.form_id=".$form_id."";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){
    	
    	$query="CREATE TEMPORARY TABLE tmptable_permission_{$tno} SELECT * FROM ap_permissions WHERE form_id = ".$form_id." ";
	    $this->connection->execute($query);

	    // update form id with new form id
	    $query="UPDATE tmptable_permission_{$tno} SET form_id = ".$new_form_id." ";
	    $this->connection->execute($query);

	    $query="INSERT INTO ap_permissions SELECT * FROM tmptable_permission_{$tno} ";
	    $this->connection->execute($query);
        // drop temp table
        $query="DROP TEMPORARY TABLE IF EXISTS tmptable_permission_{$tno} ";
	    $this->connection->execute($query);

    }

}



public function copyApFieldLogicElements($form_id,$new_form_id){
	$tno= time();
	// if row exist
	$query="SELECT * FROM ap_field_logic_elements WHERE ap_field_logic_elements.form_id=".$form_id."  ";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){

    	$query="CREATE TEMPORARY TABLE tmptable_logic_el{$tno} SELECT * FROM ap_field_logic_elements WHERE form_id = ".$form_id." ";
	    $this->connection->execute($query);
	    // update form id with new form id
	    $query="UPDATE tmptable_logic_el{$tno} SET form_id = ".$new_form_id." ";
	    $this->connection->execute($query);

	    $query="INSERT INTO ap_field_logic_elements SELECT * FROM tmptable_logic_el{$tno} ";
	    $this->connection->execute($query);
        // drop temp table
        $query="DROP TEMPORARY TABLE IF EXISTS tmptable_logic_el{$tno} ";
	    $this->connection->execute($query);

    }

}


public function copyApFieldLogicConditions($form_id,$new_form_id){
	// if row exist in ap_field_logic_conditions
	$query="SELECT * FROM ap_field_logic_conditions WHERE ap_field_logic_conditions.form_id=".$form_id."  ";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){

    	foreach($result as $lc){

       	  $sql="INSERT ap_field_logic_conditions SET 
			             form_id='".$new_form_id."',
			             target_element_id='".$lc['target_element_id']."',
			             element_name='".$lc['element_name']."',
			             rule_condition='".$lc['rule_condition']."',
			             rule_keyword='".$lc['rule_keyword']."'";
          
          $this->connection->execute($sql);
       
       }
    }

}

public function copyApPageLogic($form_id,$new_form_id){
	$tno= time();
	// if row exist
	$query="SELECT * FROM ap_page_logic WHERE ap_page_logic.form_id=".$form_id."  ";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){

    	$query="CREATE TEMPORARY TABLE tmptable_logic_el{$tno} SELECT * FROM ap_page_logic WHERE form_id = ".$form_id." ";
	    $this->connection->execute($query);
	    // update form id with new form id
	    $query="UPDATE tmptable_logic_el{$tno} SET form_id = ".$new_form_id." ";
	    $this->connection->execute($query);

	    $query="INSERT INTO ap_page_logic SELECT * FROM tmptable_logic_el{$tno} ";
	    $this->connection->execute($query);
        // drop temp table
        $query="DROP TEMPORARY TABLE IF EXISTS tmptable_logic_el{$tno} ";
	    $this->connection->execute($query);

    }

}

public function copyApPageLogicConditions($form_id,$new_form_id){
	// if row exist in ap_field_logic_conditions
	$query="SELECT * FROM ap_page_logic_conditions WHERE ap_page_logic_conditions.form_id=".$form_id."  ";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){

    	foreach($result as $lc){

       	  $sql="INSERT ap_page_logic_conditions SET 
			             form_id='".$new_form_id."',
			             target_page_id='".$lc['target_page_id']."',
			             element_name='".$lc['element_name']."',
			             rule_condition='".$lc['rule_condition']."',
			             rule_keyword='".$lc['rule_keyword']."'";
          
          $this->connection->execute($sql);
       
       }
    }

}


public function copyApWebhookOptions($form_id,$new_form_id){
	// if row exist in ap_webhook_options
	$query="SELECT * FROM ap_webhook_options WHERE ap_webhook_options.form_id=".$form_id."  ";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){

    	foreach($result as $lc){

       	  $sql="INSERT ap_webhook_options SET 
			             form_id='".$new_form_id."',
			             rule_id='".$lc['rule_id']."',
			             rule_all_any='".$lc['rule_all_any']."',
			             webhook_url='".$lc['webhook_url']."',
			             webhook_method='".$lc['webhook_method']."',
			             webhook_format='".$lc['webhook_format']."',
			             webhook_raw_data='".$lc['webhook_raw_data']."',
			             enable_http_auth='".$lc['enable_http_auth']."',
			             http_username='".$lc['http_username']."',
			             http_password='".$lc['http_password']."',
			             enable_custom_http_headers='".$lc['enable_custom_http_headers']."',
			             custom_http_headers='".$lc['custom_http_headers']."',
			             delay_notification_until_paid='".$lc['delay_notification_until_paid']."'";
          
         $this->connection->execute($sql);
       
       }
    }

}


public function ApWebhookParameters($form_id,$new_form_id){
	// if row exist in ap_webhook_parameters
	$query="SELECT * FROM ap_webhook_parameters WHERE ap_webhook_parameters.form_id=".$form_id."  ";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){

    	foreach($result as $lc){

       	  $sql="INSERT ap_webhook_parameters SET 
			             form_id='".$new_form_id."',
			             rule_id='".$lc['rule_id']."',
			             param_name='".$lc['param_name']."',
			             param_value='".$lc['param_value']."'";
          
          $this->connection->execute($sql);
       
       }
    }

}


public function ApSuccessLogicOptions($form_id,$new_form_id){
	// if row exist in ap_success_logic_options
	$query="SELECT * FROM ap_success_logic_options WHERE ap_success_logic_options.form_id=".$form_id."";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){

    	foreach($result as $lc){

       	  $sql="INSERT ap_success_logic_options SET 
			             form_id='".$new_form_id."',
			             rule_id='".$lc['rule_id']."',
			             rule_all_any='".$lc['rule_all_any']."',
			             success_type='".$lc['success_type']."',
			             success_message='".$lc['success_message']."',
			             redirect_url='".$lc['redirect_url']."'";
          
          $this->connection->execute($sql);
       
       }
    }

}


public function ApSuccessLogicConditions($form_id,$new_form_id){
	// if row exist in ap_success_logic_conditions
	$query="SELECT * FROM ap_success_logic_conditions WHERE ap_success_logic_conditions.form_id=".$form_id."";
    $result = $this->connection->execute($query)->fetchAll('assoc');

    if(!empty($result)){

    	foreach($result as $lc){

       	  $sql="INSERT ap_success_logic_conditions SET 
			             form_id='".$new_form_id."',
			             target_rule_id='".$lc['target_rule_id']."',
			             element_name='".$lc['element_name']."',
			             rule_condition='".$lc['rule_condition']."',
			             rule_keyword='".$lc['rule_keyword']."'";
          
          $this->connection->execute($sql);
       
       }
    }

}


public function createApFormDataFolder($form_id,$new_form_id){
	$source_path = WWW_ROOT.'form'.DS.'data'.DS.'form_'.$form_id;
	$dest_path = WWW_ROOT.'form'.DS.'data'.DS.'form_'.$new_form_id;

	if(file_exists($source_path)){
	   //echo "Folder found";
       $this->recurse_copy($source_path,$dest_path);

	}
    
}


public function recurse_copy($src,$dst){ 

    $dir = opendir($src); 
    @mkdir($dst,0777); 
    
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                $this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 


public function createTheme($theme_name,$company_id){
  $this->connection->insert('ap_form_themes', [
					     'user_id'=>'1',
			             'status'=>'1',
			             'theme_has_css'=>'1',
			             'theme_name'=>$theme_name,
			             'theme_built_in'=>'0',
			             'theme_is_private'=>'1',
			             'logo_type'=>'custom',
			             'logo_custom_image'=>'https://my.xpobay.com/app/webroot/form/data/themes/images/default-banner.png',
			             'logo_custom_height'=>'160',
			             'logo_default_image'=>'machform.png',
			             'logo_default_repeat'=>'0',
			             'wallpaper_bg_type'=>'pattern',
			             'wallpaper_bg_color'=>'#ffffff',
			             'wallpaper_bg_pattern'=>'',
			             'wallpaper_bg_custom'=>'',
			             'header_bg_type'=>'color',
			             'header_bg_color'=>'#ffffff',
			             'form_bg_type'=>'',
			             'form_bg_color'=>'#ffffff',
			             'highlight_bg_type'=>'color',
			             'highlight_bg_color'=>'#FFF7C0',
			             'guidelines_bg_type'=>'color',
			             'guidelines_bg_color'=>'#F5F5F5',
			             'field_bg_type'=>'color',
			             'field_bg_color'=>'#FBFBFB',
			             'form_title_font_type'=>'Lucida Grande',
			             'form_title_font_weight'=>'400',
			             'form_title_font_style'=>'normal',
			             'form_title_font_size'=>'160%',
			             'form_title_font_color'=>'#444444',
			             'form_desc_font_type'=>'Lucida Grande',
			             'form_desc_font_weight'=>'400',
			             'form_desc_font_style'=>'normal',
			             'form_desc_font_size'=>'95%',
			             'form_desc_font_color'=>'#444444',
			             'field_title_font_type'=>'Lucida Grande',
			             'field_title_font_weight'=>'700',
			             'field_title_font_style'=>'normal',
			             'field_title_font_size'=>'95%',
			             'field_title_font_color'=>'#444444',
			             'guidelines_font_type'=>'Lucida Grande',
			             'guidelines_font_weight'=>'400',
			             'guidelines_font_style'=>'normal',
			             'guidelines_font_size'=>'80%',
			             'guidelines_font_color'=>'#444444',
			             'section_title_font_type'=>'Lucida Grande',
			             'section_title_font_weight'=>'400',
			             'section_title_font_style'=>'normal',
			             'section_title_font_size'=>'110%',
			             'section_title_font_color'=>'#444444',
			             'section_desc_font_type'=>'Lucida Grande',
			             'section_desc_font_weight'=>'400',
			             'section_desc_font_style'=>'normal',
			             'section_desc_font_size'=>'85%',
			             'section_desc_font_color'=>'#444444',
			             'field_text_font_type'=>'Lucida Grande',
			             'field_text_font_weight'=>'400',
			             'field_text_font_style'=>'normal',
			             'field_text_font_size'=>'100%',
			             'field_text_font_color'=>'#666666',
			             'border_form_width'=>'1',
			             'border_form_style'=>'solid',
			             'border_form_color'=>'#CCCCCC',
			             'border_guidelines_width'=>'1',
			             'border_guidelines_style'=>'solid',
			             'border_guidelines_color'=>'#CCCCCC',
			             'border_section_width'=>'1',
			             'border_section_style'=>'dotted',
			             'border_section_color'=>'#CCCCCC',
			             'form_shadow_style'=>'disabled',
			             'form_shadow_size'=>'large',
			             'form_shadow_brightness'=>'normal',
			             'form_button_type'=>'text',
			             'form_button_text'=>'Submit',
			             'form_button_image'=>'http://',
			             'company_id'=>$company_id
					]);

  $query="SELECT theme_id FROM ap_form_themes ORDER BY theme_id DESC LIMIT 1 ";
  $result= $this->connection->execute($query)->fetch('assoc');
  return $result['theme_id'];
}


public function updateThemeName($old_theme_name,$old_company_id,$new_theme_name,$new_company_id){
   
   $this->connection->update('ap_form_themes', [
   	                         'theme_name' => $new_theme_name,
   	                         'company_id' => $new_company_id
   	                    ], ['theme_name' => $old_theme_name,'company_id'=>$old_company_id]);
   
}


	public function isCompletedForm($form_id= -1, $uid= 0, $reg_id= 0){
		$record_id = false;
		$form_name = "ap_form_".$form_id;
		$query= "select id record_exist from {$form_name} where user_id='{$uid}'";
		if($reg_id > 0){
			$query= "select id record_exist from {$form_name} where reg_id='{$reg_id}'";
		}

		$row= $this->connection->execute($query)->fetch('assoc');

		if(isset($row['record_exist'])){
		   $record_id = $row['record_exist'];
		   $query= "select * from ap_forms where form_id='{$form_id}'";
		   $rowa= $this->connection->execute($query)->fetch('assoc');

		   if($rowa['payform']==1){
			  $record_id="payform";
			}
		}

		return $record_id;

	}
	
	//* functions regarding
	//* get the reuse form
	public function getReuseFormByEventId($event_id){
	   $query="SELECT ap_forms.form_id,ap_forms.form_name,ap_forms.form_description FROM ap_forms WHERE ap_forms.ent_event_id=".$event_id." AND ap_forms.form_active='1' AND ap_forms.form_reuse='1' ";
	   //$result=$this->query($query);
	   $result=$this->connection->execute($query)->fetchAll('assoc');
	   return $result;
	}
	
	public function copyApReuseForm($form_id,$current_event_id,$default_theme_id,$exhibitor_types,$booth_Types){
	   // select form to be coppied
	   $query="SELECT * FROM ap_forms WHERE ap_forms.form_id=".$form_id."";
	   $form=$this->connection->execute($query)->fetch('assoc');
	  
	   //*copy selected form as a new record

	   //generate random form_id number, based on existing value
	   $query = "SELECT max(form_id) max_form_id FROM ap_forms";
	   $max=$this->connection->execute($query)->fetch('assoc');
	   $max_form_id= $max['max_form_id'];
	   $new_form_id = $max_form_id + rand(100,1000);

	   $this->connection->insert('ap_forms', [
							'form_id'=>$new_form_id,
							'parent_form_id'=>$form_id,
							'form_reuse'=>'1',
							'form_name'=> $form['form_name'],
							'form_description' => $form['form_description'],
							'form_name_hide' => $form['form_name_hide'],
							'form_tags' => $form['form_tags'],
							'form_email' => $form['form_email'],
							'form_redirect' => $form['form_redirect'],
							'form_redirect_enable' => $form['form_redirect_enable'],
							'form_success_message' => $form['form_success_message'],
							'form_disabled_message' => $form['form_disabled_message'],
							'form_password' => $form['form_password'],
							'form_unique_ip' => $form['form_unique_ip'],
							'form_unique_ip_maxcount' => $form['form_unique_ip_maxcount'],
							'form_unique_ip_period' => $form['form_unique_ip_period'],
							'form_frame_height' => $form['form_frame_height'],
							'form_has_css' => '1',
							'form_captcha' => $form['form_captcha'],
							'form_captcha_type' => $form['form_captcha_type'],
							'form_active' => $form['form_active'],
							'form_theme_id' => $default_theme_id,
							'form_review' => $form['form_review'],
							'form_resume_enable' => $form['form_resume_enable'],
							'form_resume_subject' => $form['form_resume_subject'],
							'form_resume_content' => $form['form_resume_content'],
							'form_resume_from_name' => $form['form_resume_from_name'],
							'form_resume_from_email_address' => $form['form_resume_from_email_address'],
							'form_custom_script_enable' => $form['form_custom_script_enable'],
							'form_custom_script_url' => $form['form_custom_script_url'],
							'form_limit_enable' => $form['form_limit_enable'],
							'form_limit' => $form['form_limit'],
							'form_label_alignment' => $form['form_label_alignment'],
							'form_language' => $form['form_language'],
							'form_page_total' => $form['form_page_total'],
							'form_lastpage_title' => $form['form_lastpage_title'],
							'form_submit_primary_text' => $form['form_submit_primary_text'],
							'form_submit_secondary_text' => $form['form_submit_secondary_text'],
							'form_submit_primary_img' => $form['form_submit_primary_img'],
							'form_submit_secondary_img' => $form['form_submit_secondary_img'],
							'form_submit_use_image' => $form['form_submit_use_image'],
							'form_review_primary_text' => $form['form_review_primary_text'],
							'form_review_secondary_text' => $form['form_review_secondary_text'],
							'form_review_primary_img' => $form['form_review_primary_img'],
							'form_review_secondary_img' => $form['form_review_secondary_img'],
							'form_review_use_image' => $form['form_review_use_image'],
							'form_review_title' => $form['form_review_title'],
							'form_review_description' => $form['form_review_title'],
							'form_pagination_type' => $form['form_pagination_type'],
							'form_schedule_enable' => $form['form_schedule_enable'],
							'form_schedule_start_date' => $form['form_schedule_start_date'],
							'form_schedule_end_date' => $form['form_schedule_end_date'],
							'form_schedule_start_hour' => $form['form_schedule_start_hour'],
							'form_schedule_end_hour' => $form['form_schedule_end_hour'],
							'esl_enable' => $form['esl_enable'],
							'esl_from_name' => $form['esl_from_name'],
							'esl_from_email_address' => $form['esl_from_email_address'],
							'esl_bcc_email_address' => $form['esl_bcc_email_address'],
							'esl_replyto_email_address' => $form['esl_replyto_email_address'],
							'esl_subject' => $form['esl_subject'],
							'esl_content' => $form['esl_content'],
							'esl_plain_text' => $form['esl_plain_text'],
							'esl_pdf_enable' => $form['esl_pdf_enable'],
							'esl_pdf_content' => $form['esl_pdf_content'],
							'esr_enable' => $form['esr_enable'],
							'esr_email_address' => $form['esr_email_address'],
							'esr_from_name' => $form['esr_from_name'],
							'esr_from_email_address' => $form['esr_from_email_address'],
							'esr_bcc_email_address' => $form['esr_bcc_email_address'],
							'esr_replyto_email_address' => $form['esr_replyto_email_address'],
							'esr_subject' => $form['esr_subject'],
							'esr_content' => $form['esr_content'],
							'esr_plain_text' => $form['esr_plain_text'],
							'esr_pdf_enable' => $form['esr_pdf_enable'],
							'esr_pdf_content' => $form['esr_pdf_content'],
							'payment_enable_merchant' => $form['payment_enable_merchant'],
							'payment_merchant_type' => $form['payment_merchant_type'],
							'payment_paypal_email' => $form['payment_paypal_email'],
							'payment_paypal_language' => $form['payment_paypal_language'],
							'payment_currency' => $form['payment_currency'],
							'payment_show_total' => $form['payment_show_total'],
							'payment_total_location' => $form['payment_total_location'],
							'payment_enable_recurring' => $form['payment_enable_recurring'],
							'payment_recurring_cycle' => $form['payment_recurring_cycle'],
							'payment_recurring_unit' => $form['payment_recurring_unit'],
							'payment_enable_trial' => $form['payment_enable_trial'],
							'payment_trial_period' => $form['payment_trial_period'],
							'payment_trial_unit' => $form['payment_trial_unit'],
							'payment_trial_amount' => $form['payment_trial_amount'],
							'payment_enable_setupfee' => $form['payment_enable_setupfee'],
							'payment_setupfee_amount' => $form['payment_setupfee_amount'],
							'payment_price_type' => $form['payment_price_type'],
							'payment_price_amount' => $form['payment_price_amount'],
							'payment_price_name' => $form['payment_price_name'],
							'payment_stripe_live_secret_key' => $form['payment_stripe_live_secret_key'],
							'payment_stripe_live_public_key' => $form['payment_stripe_live_public_key'],
							'payment_stripe_test_secret_key' => $form['payment_stripe_test_secret_key'],
							'payment_stripe_test_public_key' => $form['payment_stripe_test_public_key'],
							'payment_stripe_enable_test_mode' => $form['payment_stripe_enable_test_mode'],
							'payment_stripe_enable_receipt' => $form['payment_stripe_enable_receipt'],
							'payment_stripe_receipt_element_id' => $form['payment_stripe_receipt_element_id'],
							'payment_paypal_rest_live_clientid' => $form['payment_paypal_rest_live_clientid'],
							'payment_paypal_rest_live_secret_key' => $form['payment_paypal_rest_live_secret_key'],
							'payment_paypal_rest_test_clientid' => $form['payment_paypal_rest_test_clientid'],
							'payment_paypal_rest_test_secret_key' => $form['payment_paypal_rest_test_secret_key'],
							'payment_paypal_rest_enable_test_mode' => $form['payment_paypal_rest_enable_test_mode'],
							'payment_authorizenet_live_apiloginid' => $form['payment_authorizenet_live_apiloginid'],
							'payment_authorizenet_live_transkey' => $form['payment_authorizenet_live_transkey'],
							'payment_authorizenet_test_apiloginid' =>$form['payment_authorizenet_test_apiloginid'], 
							'payment_authorizenet_test_transkey' => $form['payment_authorizenet_test_transkey'], 
							'payment_authorizenet_enable_test_mode' => $form['payment_authorizenet_enable_test_mode'], 
							'payment_authorizenet_save_cc_data' => $form['payment_authorizenet_save_cc_data'],
							'payment_braintree_live_merchant_id' => $form['payment_braintree_live_merchant_id'],
							'payment_braintree_live_public_key' => $form['payment_braintree_live_public_key'],
							'payment_braintree_live_private_key' => $form['payment_braintree_live_private_key'],
							'payment_braintree_live_encryption_key' => $form['payment_braintree_live_encryption_key'],
							'payment_braintree_test_merchant_id' => $form['payment_braintree_test_merchant_id'],
							'payment_braintree_test_public_key' => $form['payment_braintree_test_public_key'],
							'payment_braintree_test_private_key' => $form['payment_braintree_test_private_key'],
							'payment_braintree_test_encryption_key' => $form['payment_braintree_test_encryption_key'],
							'payment_braintree_enable_test_mode' => $form['payment_braintree_enable_test_mode'],
							'payment_paypal_enable_test_mode' => $form['payment_paypal_enable_test_mode'],
							'payment_enable_invoice' => $form['payment_enable_invoice'],
							'payment_invoice_email' => $form['payment_invoice_email'],
							'payment_delay_notifications' => $form['payment_delay_notifications'],
							'payment_ask_billing' => $form['payment_ask_billing'],
							'payment_ask_shipping' => $form['payment_ask_shipping'],
							'payment_enable_tax' => $form['payment_enable_tax'],
							'payment_tax_rate' => $form['payment_tax_rate'],
							'payment_enable_discount' => $form['payment_enable_discount'],
							'payment_discount_type' => $form['payment_discount_type'],
							'payment_discount_amount' => $form['payment_discount_amount'],
							'payment_discount_code' => $form['payment_discount_code'],
							'payment_discount_element_id' => $form['payment_discount_element_id'],
							'payment_discount_max_usage' => $form['payment_discount_max_usage'],
							'payment_discount_expiry_date' => $form['payment_discount_expiry_date'],
							'logic_field_enable' => $form['logic_field_enable'],
							'logic_page_enable' => $form['logic_page_enable'],
							'logic_email_enable' => $form['logic_email_enable'],
							'logic_webhook_enable' => $form['logic_webhook_enable'],
							'logic_success_enable' => $form['logic_success_enable'],
							'webhook_enable' => $form['webhook_enable'],
							'webhook_url' => $form['webhook_url'],
							'webhook_method' => $form['webhook_method'],
							
							'form_exhibitor_types' => $form['form_exhibitor_types'],
							'form_booth_types' => $form['form_booth_types'],
							'form_deadline' => $form['form_deadline'],
							'ent_event_id' => $current_event_id,
							'ent_exhibitor_types' => $exhibitor_types,
							'ent_booth_types' => $booth_Types,
							//'ent_deadline' => '',
							'ent_deadline' => $form['ent_deadline'],

							'payment_securepay_merchant_id' => $form['payment_securepay_merchant_id'],
							'payment_securepay_merchant_password' => $form['payment_securepay_merchant_password'],
							'payform'=>$form['payform']
						]);
	   
	   return $new_form_id;

	}
}


/*
class Event extends AppModel {
	var $name = 'Event';
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'EventBoothType' => array(
			'className' => 'EventBoothType',
			'foreignKey' => 'event_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'EventExhibitorCategory' => array(
			'className' => 'EventExhibitorCategory',
			'foreignKey' => 'event_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'EventFile' => array(
			'className' => 'EventFile',
			'foreignKey' => 'event_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ExhibitionRegistration' => array(
			'className' => 'ExhibitionRegistration',
			'foreignKey' => 'event_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	var $hasAndBelongsToMany = array(
		'Vendor' => array(
			'className' => 'Vendor',
			'joinTable' => 'cscart_expo_companies',
			'foreignKey' => 'expo_id',
			'associationForeignKey' => 'company_id'
		)
	);





}*/
?>
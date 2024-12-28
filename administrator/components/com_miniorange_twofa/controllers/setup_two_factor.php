<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_twofa
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');

// import common utilities from library
jimport('miniorangetfa.utility.commonUtilitiesTfa');
jimport('miniorangetfa.utility.miniOrangeUser');

class miniorange_twofaControllersetup_two_factor  extends JControllerForm
{
	public  function configure(){
		$c_time =date("Y-m-d",time());
		$isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
		if(!$isCustomerRegistered){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_CONFIG_REGISTER'),'error');
				return;
		}
		$post = JFactory::getApplication()->input->post->getArray();
		$current_user =  JFactory::getUser();
		$username='';
		$email='';
		$phone='';
		
		$authType=$post['authType'];
		if(isset($post['authType'])&&$post['authType']=='OOSE'){
			$username=isset($post['Email'])?$post['Email']:'';
		    $email=isset($post['Email'])?$post['Email']:'';
		    $phone=$post['Phone'];
		}
		else if(isset($post['authType'])&&$post['authType']=='OOS'){
			$authType='OOS';
			$username=$current_user->email;
			$phone=$post['Phone'];
			$email="";
		}
		else{
			$authType='OOE';
			$username=isset($post['Email'])?$post['Email']:'';
		    $email   =isset($post['Email'])?$post['Email']:'';
			$phone="";
		}
		$user = new miniOrangeUser();	
		$response=json_decode($user->challengeTest($authType,true,$email,$phone));
		setcookie("step_two_txID",$response->txId);
		
		if($response->status=='SUCCESS'){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&configure='.$authType.'&step=2',JText::_('COM_MINIORANGE_SETUP2FA_OTP_MSG'));
			return;
		}
		else{
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&configure='.$authType,$response->message,'error');
			return;
		}
	}
	public function configure_step_two()
	{
		$c_time =date("Y-m-d",time());
		$isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
		if(!$isCustomerRegistered){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor',JText::_('COM_MINIORANGE_SETUP2FA_CONFIG_REGISTER'),'error');
				return;
		}
		$post = JFactory::getApplication()->input->post->getArray();
		$user = new miniOrangeUser();
		$current_user = JFactory::getUser();
		$secret = JFactory::getApplication()->input->cookie->get('step_two_txID');
		setcookie("step_two_txID", "", time()-3600);
		$response= $user->validateTest($post['Otp_token'],$post['configuring'],NULL,true,$secret);
		$response=json_decode($response);
		
		if($response->status=='SUCCESS'){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor', JText::_('COM_MINIORANGE_SETUP2FA_TESTED'),'success');
			return;
		}
		else{
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&configure='.$post['configuring'].'&step=2',$response->message,'error');
				return;
		}
	}

	public function validateAppPasscode(){
		$c_time =date("Y-m-d",time());
		$post = JFactory::getApplication()->input->post->getArray();
		$get = JFactory::getApplication()->input->get->getArray();
		$name=$get['AppName'];
		$appName=array(
			"google"=>"Google",
			"AA"=>"Authy",
			"MA"=>"Microsoft",
			"LPA"=>"LastPass", 
			"DUO"=>"DUO",
		);
		$passcode=$post['google_passcode'];
		$user = new miniOrangeUser();
		
		$details=commonUtilitiesTfa::getCustomerDetails();

		$response=json_decode($user->validateGoogleToken($details['email'],$post['txID'],$passcode,$get['AppName']));
		if($response->status=='SUCCESS')
		{
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor', JText::_('COM_MINIORANGE_SETUP2FA_TESTED').$appName[$name].JText::_(' COM_MINIORANGE_SETUP2FA_AUTH_METHOD'));
			return;
		}
		else if($response->status=='FAILED'){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&configure='.$get['AppName'].'&step=3',JText::_('COM_MINIORANGE_SETUP2FA_INVALID'),'error');
			return;
		} 
 
	}
	
	public  function support()
	{
		$c_time =date("Y-m-d",time());
		$post = JFactory::getApplication()->input->post->getArray();
		$obj = new Mo_tfa_Customer();
        $response = $obj->submit_contact_us($post['email'],$post['phone'],$post['query']);
	    if ( $response!= 'Query submitted.' ) 
		{
			   $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', JText::_('COM_MINIORANGE_SETUP2FA_QUERY_MSG'),'error');
            	return;
	    } 
	    else 
		{
			   $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', JText::_('COM_MINIORANGE_SETUP2FA_QUERY_MSG1'));
			   return;
	    }
	}
	public  function trialsupport()
	{
		$post = JFactory::getApplication()->input->post->getArray();
		$c_time =date("Y-m-d",time());
		$obj = new Mo_tfa_Customer();
        $response = $obj->submit_trial_request($post['trial_or_demo'],$post['trial_email_id'],$post['trial_mobile_number'],$post['trial_plan'],$post['trial_description']);
	    if(json_last_error() == JSON_ERROR_NONE){
			if(is_array($response) && array_key_exists('status', $response) && $response['status'] == 'ERROR'){
				$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', $response['message'], 'error');
				return;
			}else{
				if ( $response == false ) {
					$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', JText::_('COM_MINIORANGE_SETUP2FA_QUERY_MSG'),'error');
					return;
				} else {
					$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', JText::_('COM_MINIORANGE_SETUP2FA_QUERY_MSG1'));
					return;
				}
			}
		}
		$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', JText::_('COM_MINIORANGE_SETUP2FA_QUERY_MSG2'),'error');
		return;
	}

	public function callSupport() {
		$c_time =date("Y-m-d",time());
	    $post = JFactory::getApplication()->input->post->getArray();
	    if(count($post) == 0) {
	        $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support',JText::_('COM_MINIORANGE_SETUP2FA_FILL_DETAILS'),'error');
	        return;
        }
        $query_email = $post['mo_sp_setup_call_email'];
	    $query = $post['mo_sp_setup_call_issue'];
	    $description = $post['mo_sp_setup_call_desc'];
	    $callDate = $post['mo_sp_setup_call_date'];
        $timeZone    =$post['mo_sp_setup_call_timezone'];

        if(empty($query_email) || empty($query) || empty($description) || empty($callDate) || empty($timeZone)) {
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support',JText::_('COM_MINIORANGE_SETUP2FA_FIELDS_MANDATORY'),'error');
            return;
        } else {
            $contact = new Mo_tfa_Customer();
            $submitted = json_decode($contact->request_setup_call($query_email, $query, $description, $callDate, $timeZone), true);
		
            if(json_last_error() == JSON_ERROR_NONE){
                if(is_array($submitted) && array_key_exists('status', $submitted) && $submitted['status'] == 'ERROR'){
                    $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', $submitted['message'], 'error');
                    return;
                }else{
                    if ( $submitted == false ) {
                        $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', JText::_('COM_MINIORANGE_SETUP2FA_QUERY_MSG'),'error');
                        return;
                    } else {
                        $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', JText::_('COM_MINIORANGE_SETUP2FA_QUERY_MSG1'));
                        return;
                    }
                }
            }
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', JText::_('COM_MINIORANGE_SETUP2FA_QUERY_MSG2'),'error');
            return;
        }

    }

    public function requestQuote() {
		$c_time =date("Y-m-d",time());
        $array=JFactory::getApplication()->input->post->getArray();
        $type_service = isset($array['type_service'])?$array['type_service']:"";

        $email = isset($array['email'])?$array['email']:"";
        $user_count = isset($array['no_of_users'])?$array['no_of_users']:0;
        $number_otp = isset($array['no_of_otp'])?$array['no_of_otp']:0;
        if($type_service=='SMS'||$type_service=='OOSE')
        {

            $select_country=$array['select_country'];
            if($select_country=="singlecountry")
            {
                $which_country=$array['select_country'];
            }
        }
        $query = isset($array['user_extra_requirement'])?$array['user_extra_requirement']:"";
        if(empty($email)){
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support',JText::_('COM_MINIORANGE_SETUP2FA_EMAIL'),'error');
            return;
        }else{
            $select_country=isset($array['select_country'])?$array['select_country']:"";
            $which_country=isset($array['which_country'])?$array['which_country']:"";
            $quote_Request=new Mo_tfa_Customer();
            $submitted = json_decode($quote_Request->submit_request_quote($type_service, $email,$user_count, $number_otp,$select_country,$which_country,$query), true);
        
			if (json_last_error() == JSON_ERROR_NONE){
                if (is_array($submitted) && array_key_exists('status', $submitted) && $submitted['status'] == 'ERROR') {
                    $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', $submitted['message'], 'error');
                    return;
                } else {
                    if($submitted == false){
                        $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support',JText::_('COM_MINIORANGE_SETUP2FA_QUERY_MSG'),'error');
                        return;
                    }else {
                        $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', JText::_('COM_MINIORANGE_SETUP2FA_QUERY_MSG1'));
                        return;
                    }
                }
            }
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=support', JText::_('COM_MINIORANGE_SETUP2FA_QUERY_MSG2'),'error');
            return;
        }

	}
	public  function register_login_customer(){
		$c_time =date("Y-m-d",time());
		// extract all posted data
		$post = JFactory::getApplication()->input->post->getArray();

		// decide if it is registartion or login
		$isRegistering = isset($post['register_or_login'])&&$post['register_or_login']=='Register'?TRUE:FALSE;
		if($isRegistering){
			// check if password and confirm password matches
			if(isset($post['password']) && isset($post['confirm_password']))
			{
				if($post['password']!=$post['confirm_password'])
				{
					$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup', JText::_('COM_MINIORANGE_SETUP2FA_PASSWORD_MSG') ,'error');
            		return;
				}
				else if(strlen($post['password'])<6)
				{
					$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup', JText::_('COM_MINIORANGE_SETUP2FA_PASSWORD_MSG1') ,'error');
            		return;
				}
			}
			//check if customer already exist
			$customer = new Mo_tfa_Customer();
        	$check_customer_response  = json_decode($customer->check_customer(trim($post['email'])));
        	if(!is_object( $check_customer_response ) || !isset( $check_customer_response->status ) || empty($check_customer_response->status))
			{
				$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_ERROR_MSG'), 'error');
                return;
       		}
        	elseif ($check_customer_response->status == 'TRANSACTION_LIMIT_EXCEEDED') 
			{
				$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_TESTING'), 'error');
            	return;
        	}
			elseif ($check_customer_response->status == 'CURL_ERROR') {
				$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_CURL_MSG'), 'error');
				return;
        	}
        	elseif ($check_customer_response->status == 'CUSTOMER_NOT_FOUND') 
			{
            	$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',"User Not Found", 'error');
				return;
        	} 
        	else {
        		// already have an acount
        		$this->customerLogin(trim($post['email']),$post['password']);
        	}
		}
		else{
			// start login process
			$this->customerLogin(trim($post['email']),$post['password']);
		}
	}
	
	public  function customerLogin($email,$password){
		$c_time =date("Y-m-d",time());
        $customer= new Mo_tfa_Customer();
		$customer_keys_response = json_decode($customer->getCustomerKeys($email,$password));
		
        if (json_last_error() == JSON_ERROR_NONE) {
            if(!is_object( $customer_keys_response ) || !isset( $customer_keys_response->id ) || empty($customer_keys_response->id)){
                $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup&tab=login',JText::_('COM_MINIORANGE_SETUP2FA_ERROR_MSG'), 'error');
                return;
            }
            else{
            	// save customer details
            	if($customer_keys_response->status=='SUCCESS')
            	{
            		$current_user = JFactory::getUser();
            		commonUtilitiesTfa::saveCustomerDetailsAfterLogin($email,$password,$customer_keys_response->phone,$customer_keys_response->id,$customer_keys_response->apiKey,$customer_keys_response->token,$customer_keys_response->appSecret,$current_user->id);
            		 
            		$moUser = new miniOrangeUser();
            		//$userApiResponse = json_decode($moUser->mo2f_update_userinfo($email,'OOE'));
            		$customer = new Mo_tfa_Customer();
					$response = json_decode($customer->fetchLicense());
					
					commonUtilitiesTfa::updateLicenseDetails($response,$email); 
            		$erMsg = JText::_('COM_MINIORANGE_SETUP2FA_ACCOUNT_RETRIEVED');
					$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',$erMsg);
            	}
            	else{
            		$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup&tab=login',JText::_('COM_MINIORANGE_SETUP2FA_ERROR_MSG'), 'error');
                	return;
            	}
            }
        }
        else {
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup&tab=login',JText::_('COM_MINIORANGE_SETUP2FA_ERROR_MSG1'), 'error');
            return;
        }

	}

	public  function resendOtp(){
		$c_time =date("Y-m-d",time());
		$this->sendOtp('resent');
	}
	public function sendOtp($sentOrResent){
		$c_time =date("Y-m-d",time());
		$details  = commonUtilitiesTfa::getCustomerDetails();
		$customer = new Mo_tfa_Customer();
		$response = $customer->send_otp_token('EMAIL',$details['email'],'',true);
		$response = json_decode($response);

		if($response->status == 'SUCCESS' ){
			$this->updateTransactionId($response->txId);
 			$application = JFactory::getApplication();
			$msg_otp=JText::_('COM_MINIORANGE_SETUP2FA_OTP_SENT') .$sentOrResent. JText::_('COM_MINIORANGE_SETUP2FA_OTP_TO') .$details['email'].JText::_('COM_MINIORANGE_SETUP2FA_ENTER_OTP');
             $application->enqueueMessage($msg_otp,'success');
             $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup');

			return;
		}
		else{
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_OTP_ERROR').$details['email'],'error');
			return;
		}
	}
	static function  updateTransactionId($transactionId) 
	{
		$c_time =date("Y-m-d",time());
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		 // Fields to update.
		$fields = array(
			$db->quoteName('registration_status') .' = '.$db->quote('OTP'),
			$db->quoteName('transaction_id') . ' = '.$db->quote($transactionId),
			
		);
		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('id') . ' = 1'
		);
		$query->update($db->quoteName('#__miniorange_tfa_customer_details'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();
		
	}

	public function saveTfaSettings()
	{
		$c_time =date("Y-m-d",time());
		$isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
		if(!$isCustomerRegistered){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_CONFIG_REGISTER'),'error');
				return;
		}
		$app=JFactory::getApplication();
		$post=$app->input->post->getArray();
 
		if(count($post)==0){
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup');
            return;
        }
		$active2FAMethods=array();
		$allTfaMethods = commonUtilitiesTfa::getAllTfaMethods();
		foreach ($allTfaMethods as $key=>$value){
		    if(isset($post['tfa_method_allowed_'.$key]) && $post['tfa_method_allowed_'.$key]=='on' )
		        array_push($active2FAMethods,$key);
        }
        $validator = function ($var) {
            if($var == 1 || $var == TRUE || $var == 'on'){
                return TRUE;
            }
            return FALSE;
		};
		
		$kbasetofques1             = JText::_('COM_MINIORANGE_SETUP2FA_KBA1');
		$kbasetofques2             = JText::_('COM_MINIORANGE_SETUP2FA_KBA2');
		$enabled_tfa               = isset($post['enable_mo_tfa']) ? $validator($post['enable_mo_tfa']) : 0;
		$enable_2fa_user_type      = isset($post['enable_2fa_user_type']) ? $post['enable_2fa_user_type'] : 'none';
		$inline                    = isset($post['enable_mo_tfa_inline']) ? $validator($post['enable_mo_tfa_inline']) : 0;
        $skip_tfa_for_users        = isset($post['skip_tfa_for_users']) ? $post['skip_tfa_for_users'] : 0;
		$enable_otp_login          = isset($post['enable_tfa_passwordless_login']) ? $validator($post['enable_tfa_passwordless_login']) : 0;
		$enable_change_2fa_method  = isset($post['enable_change_2fa_method']) ? $validator($post['enable_change_2fa_method']) : 0;
		$enable_remember_device    = isset($post['enable_remember_device']) ? $validator($post['enable_remember_device']) : 0;
		$enable_2fa_backup_method  = isset($post['enable_2fa_backup_method']) ? $validator($post['enable_2fa_backup_method']) : 0;
		$enable_2fa_backup_type    = isset($post['enable_2fa_backup_type']) ? $post['enable_2fa_backup_type'] : 'none'; 
		$KBA_set1                  = isset($post['KBA_set_ques1']) ? trim($post['KBA_set_ques1']) : $kbasetofques1;
		$KBA_set2                  = isset($post['KBA_set_ques2']) ? trim($post['KBA_set_ques2']) : $kbasetofques2;
	
		$details= commonUtilitiesTfa::getCustomerDetails();
        $inlineDisabled='';
	
        if( $inline==true &&(is_null($details['license_type']) || empty($details['license_type']))){
            $inline=FALSE;
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=login_settings',JText::_('COM_MINIORANGE_SETUP2FA_ERROR_MSG2'),'error');
		   return;
        }
        if((is_null($details['license_type']) || empty($details['license_type']))){
            $active2FAMethods = array('ALL');
        }
        if(count($active2FAMethods)==0 && $enabled_tfa==TRUE){
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=login_settings',JText::_('COM_MINIORANGE_SETUP2FA_DISABLE_ONSITE'),'warning:');
			return;
        }
        if(count($active2FAMethods)==1 && $enable_change_2fa_method==true){
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=login_settings','You cannot enable <strong>Allow users to change TFA method</strong> if only one TFA method is selected.','warning:');
			return;
        }

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		 // Fields to update.
		$fields = array(
			$db->quoteName('tfa_enabled') .' = '.$db->quote($enabled_tfa),
			$db->quoteName('tfa_enabled_type') .' = '.$db->quote($enable_2fa_user_type),
			$db->quoteName('tfa_halt') .' = '.$db->quote($inline),
            $db->quoteName('skip_tfa_for_users') . '=' . $db->quote($skip_tfa_for_users),
            $db->quoteName('enable_tfa_passwordless_login') . '=' . $db->quote($enable_otp_login),
			$db->quoteName('enable_change_2fa_method') . '=' . $db->quote($enable_change_2fa_method),
			$db->quoteName('remember_device') . '=' . $db->quote($enable_remember_device),
            $db->quoteName('enable_backup_method') . '=' . $db->quote($enable_2fa_backup_method),
			$db->quoteName('enable_backup_method_type') . '=' . $db->quote($enable_2fa_backup_type),
			$db->quoteName('tfa_kba_set1').' = '.$db->quote($KBA_set1),
			$db->quoteName('tfa_kba_set2').' = '.$db->quote($KBA_set2),
			$db->quoteName('activeMethods').' = '.$db->quote(json_encode($active2FAMethods)),
		);

		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('id') . ' = 1'
		);
		$query->update($db->quoteName('#__miniorange_tfa_settings'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$db->execute();

		$msg= JText::_('COM_MINIORANGE_SETUP2FA_SUCCESSFULLY');
        $msgType = 'success';
		$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=login_settings',$msg,$msgType);
		return;
	} 

	public function saveTfaAdvanceSettings()
	{
		$c_time =date("Y-m-d",time());
		$isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
		if(!$isCustomerRegistered){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_CONFIG_REGISTER'),'error');
				return;
		}

		$app=JFactory::getApplication();
		$post=$app->input->post->getArray();
		
		if(count($post)==0){
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup');
            return;
        }

		$tfa_enabled_for_roles = array();
		$groups = commonUtilitiesTfa::loadGroups();
		
		foreach ($groups as $key => $value) {
			if(array_key_exists('role_based_tfa_'.str_replace(' ', '_', $value['title']),$post))
			{
				$en = $post['role_based_tfa_'.str_replace(' ', '_', $value['title'])];
				if($en==1 || $en=='on' || $en=TRUE){
					array_push($tfa_enabled_for_roles,$value['title'] );
				}
			}
		}
		if(count($tfa_enabled_for_roles)==0){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=advance_settings',JText::_('COM_MINIORANGE_SETUP2FA_ROLEBASED_MSG'),'warning:');
			return;
		}

        $validator = function ($var) {
            if($var == 1 || $var == TRUE || $var == 'on'){
                return TRUE;
            }
            return FALSE;
		};

		
        $ipWhiteList = isset($post['enableIpWhiteListing'])
            ? $validator($post['enableIpWhiteListing'])
            : 0;
        $ipBlackList    = isset($post['enableIpBlackListing'])
            ? $validator($post['enableIpBlackListing'])
            : 0;
		$redirectUrl    = isset($post['mo_tfa_user_after_login'])
            ? $post['mo_tfa_user_after_login']
            : '';
		$googleAppName  = isset($post['mo_tfa_google_app_name']) || empty($post['mo_tfa_google_app_name'])
            ? $post['mo_tfa_google_app_name']
            : 'miniOrangeAuth';
		$brandingName   = isset($post['branding_name']) && !empty($post['branding_name'])
            ? $post['branding_name']
            : 'login';
		
        $login_with_second_factor_only = isset($post['login_with_second_factor_only'])
            ? $validator($post['login_with_second_factor_only'])
            : 0;

		list($validIps, $invalidIps) = isset($post['mo_tfa_whitelist_ips'])
            ? commonUtilitiesTfa::validateIpsInput($post['mo_tfa_whitelist_ips'])
            : commonUtilitiesTfa::validateIpsInput(array());

		list($blackListIPs, $invalidIps) = isset($post['mo_tfa_blacklist_ips'])
            ? commonUtilitiesTfa::validateIpsInput($post['mo_tfa_blacklist_ips'])
            : commonUtilitiesTfa::validateIpsInput(array());

		$details= commonUtilitiesTfa::getCustomerDetails();
        $inlineDisabled='';

        if((is_null($details['license_type']) || empty($details['license_type']))){
            $tfa_enabled_for_roles = array('ALL');
        }

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		 // Fields to update.
		$fields = array(
			$db->quoteName('mo_tfa_for_roles').' = '.$db->quote(json_encode($tfa_enabled_for_roles)),
            $db->quoteName('enableIpWhiteList').' = '.$db->quote($ipWhiteList),
            $db->quoteName('enableIpBlackList').' = '.$db->quote($ipBlackList),
            $db->quoteName('whiteListedIps').' = '.$db->quote(json_encode($validIps)),
            $db->quoteName('blackListedIPs').' = '.$db->quote(json_encode($blackListIPs)),
            $db->quoteName('afterLoginRedirectUrl').' = '.$db->quote($redirectUrl),
            $db->quoteName('googleAuthAppName').' = '.$db->quote(urlencode($googleAppName)),
			$db->quoteName('branding_name').' = '.$db->quote($brandingName),
			$db->quoteName('login_with_second_factor_only').' = '.$db->quote($login_with_second_factor_only),
			
		);

		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('id') . ' = 1'
		);
		$query->update($db->quoteName('#__miniorange_tfa_settings'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$db->execute();

		$msg= JText::_('COM_MINIORANGE_SETUP2FA_SUCCESSFULLY');
        $msgType = 'success';
		if(count($invalidIps)>0){
		    $msg=JText::_('COM_MINIORANGE_SETUP2FA_INVALID_IPS');
		    foreach ($invalidIps as $value)
		        $msg=$msg.'<li>'.$value.'</li>';
		    $msg=$msg.'</ul>';
		    $msgType = 'error';
        }
		$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=advance_settings',$msg,$msgType);
		return;
	}

	public function configureKBADetails()
	{
		$c_time =date("Y-m-d",time());
		$isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
		if(!$isCustomerRegistered){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_CONFIG_REGISTER'),'error');
				return;
		}
		$app  = JFactory::getApplication();
		$post = $app->input->post->getArray();
		$question1 = trim($post['mo_tfa_ques_1']);
		$answer1   = trim($post['mo_tfa_ans_1']);
		$question2 = trim($post['mo_tfa_ques_2']);
		$answer2   = trim($post['mo_tfa_ans_2']);
		$question3 = trim($post['mo_tfa_ques_3']);
		$answer3   = trim($post['mo_tfa_ans_3']);
		if($question1==$question2 || $question1==$question3 || $question2==$question3){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor',JText::_('COM_MINIORANGE_SETUP2FA_KBA_MSG'),'error');
		    return;
		}
		else
		{
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor',JText::_('COM_MINIORANGE_SETUP2FA_KBA_MSG1'),'success');
		    return;
		}
	}

	public function testKBADetails(){
		$c_time =date("Y-m-d",time());
		$isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
		if(!$isCustomerRegistered){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_CONFIG_REGISTER'),'error');
				return;
		}
		$app  = JFactory::getApplication();
		$post = $app->input->post->getArray();
		$current_user = JFactory::getUser(); 
		$user      = new miniOrangeUser();
		$row       = commonUtilitiesTfa::getCustomerDetails();
		$answers   = array();
		for ($i=1;$i<3;$i++) {
    		$temp_arr=array("question" =>$post['question'.$i],
                            "answer" => $post['answer'.$i],
                            );
    		array_push($answers, $temp_arr);
    	}
    	
    	$response=json_decode($user->validate($current_user->id,NULL,'KBA', $answers));

    	if($response->status=='SUCCESS'){
    		$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor',JText::_('COM_MINIORANGE_SETUP2FA_TEST_SUCCESS'));
    		return;
    	}
    	else{
    	    $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor',$response->message,'error');
    		return;
    	}
	}
 
 
	public function setTfaMethod($tfaMethod){
		$c_time =date("Y-m-d",time());
		$isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
		if(!$isCustomerRegistered)
		{
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_CONFIG_REGISTER'),'error');
				return;
		}
		$app=JFactory::getApplication();
		$post=$app->input->post->getArray();
		$current_user = JFactory::getUser();
		$user = new miniOrangeUser();
		$details = commonUtilitiesTfa::getCustomerDetails();
		$response = json_decode($user->mo2f_update_userinfo($details['email'],$tfaMethod));
		$tfaArray = Mo_tfa_utilities::tfaMethodArray();
		if( $response->status=='SUCCESS'){ 
			commonUtilitiesTfa::updateOptionOfUser($current_user->id,'active_method',$tfaMethod);
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor',$tfaArray[$tfaMethod]['name'].JText::_('COM_MINIORANGE_SETUP2FA_ACTIVE_METHOD'));
    		return;
		} 
		else{
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor',$response->message,'error');
    		return;
		}
		
	}

	public function testTfaMethod(){
		$c_time =date("Y-m-d",time());
		$isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
		if(!$isCustomerRegistered){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_CONFIG_REGISTER'),'error');
				return;
		}
		$app=JFactory::getApplication();
		$post=$app->input->post->getArray();
		$current_user = JFactory::getUser();
		$tfaMethod=str_replace('mo_tfa_test', '', $post['tfaMethodToTest']);
		$user = new miniOrangeUser();
		

		$authCodes   = array('OOE'=>'EMAIL','OOS'=>'SMS','OOSE'=>'SMS AND EMAIL','KBA'=>'KBA','google'=>'GOOGLE AUTHENTICATOR','MA'=>'MICROSOFT AUTHENTICATOR','AA'=>'AUTHY AUTHENTICATOR','LPA'=>'LASTPASS AUTHENTICATOR');
		$response = json_decode($user->challenge($current_user->id,$authCodes[$tfaMethod]));
		if($response->status=='SUCCESS'){
			commonUtilitiesTfa::updateOptionOfUser($current_user->id,'transactionId',$response->txId);
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&test='.$tfaMethod);
    		return;

		}
		else{
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&configuring',$response->message,'error');
    		return;
		}
	}
	public function resendOtpWhileTest(){
		$c_time =date("Y-m-d",time());
		$isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
		if(!$isCustomerRegistered){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_CONFIG_REGISTER'),'error');
				return;
		}
		$user = new miniOrangeUser();
		$post = JFactory::getApplication()->input->post->getArray();
		$current_user = JFactory::getUser();
		$user = new miniOrangeUser();
		$authCodes   = array('OOE'=>'EMAIL','OOS'=>'SMS','OOSE'=>'SMS AND EMAIL','KBA'=>'KBA');
		$response=json_decode($user->challenge($current_user->id,$authCodes[$post['testing']]));
		if($response->status=='SUCCESS'){
			commonUtilitiesTfa::updateOptionOfUser($current_user->id,'transactionId',$response->txId);
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&test='.$post['testing'],JText::_('COM_MINIORANGE_SETUP2FA_OTP_RESEND'));
    		return;
		}
		else{
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&test='.$post['testing'],$response->message,'error');
			return;
		}
	}
	public function testTfaMethodValidate(){
		$c_time =date("Y-m-d",time());
		$isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
		if(!$isCustomerRegistered){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_CONFIG_REGISTER'),'error');
				return;
		}
		$user = new miniOrangeUser();
		$post = JFactory::getApplication()->input->post->getArray();
		$current_user = JFactory::getUser();
		$response=json_decode($user->validate($current_user->id,$post['Otp_token'],$post['testing']));
		if($response->status=='SUCCESS'){
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor',JText::_('COM_MINIORANGE_SETUP2FA_TEST_SUCCESS'));
    		return;
		}
		else{
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&test='.$post['testing'],$response->message,'error');
			return;
		}
	}

	public function checkLicense(){

		$c_time =date("Y-m-d",time());
		$customer = new Mo_tfa_Customer();
		$details  = commonUtilitiesTfa::getCustomerDetails();

		$response = json_decode($customer->fetchLicense());
		$miniorange_lexp_notification_sent = isset($details['miniorange_lexp_notification_sent'])
            ? $details['miniorange_lexp_notification_sent']
            : 0;
	
		$licenseExpiryDate = strtotime($details['licenseExpiry']);
		$licenseExpiryFromServer = strtotime($response->licenseExpiry);
		
		if($response->status=='SUCCESS'){
			commonUtilitiesTfa::updateLicenseDetails($response,$details['email']);

			if($licenseExpiryDate < $licenseExpiryFromServer)
			{
				if($miniorange_lexp_notification_sent)
				{
						$db_table = '#__miniorange_tfa_customer_details';
						$db_coloums = array('miniorange_fifteen_days_before_lexp'   => 0,
											'miniorange_five_days_before_lexp'      => 0,
											'miniorange_after_lexp'                 => 0,
											'miniorange_after_five_days_lexp'       => 0,
											'miniorange_lexp_notification_sent'     => 0,
						);
						commonUtilitiesTfa::__genDBUpdate($db_table, $db_coloums);
				}
			}
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_LICENSE_UPDATE'),'success');
			return;
		}
		else{
			$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',$response->message,'error');
			return;
		}


	}

    public function resetUser2FA()
    {
		$post = JFactory::getApplication()->input->post->getArray();
		
		$c_time =date("Y-m-d",time());
        $isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
        if(!$isCustomerRegistered){
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_CONFIG_REGISTER'),'error');
            return;
        }
        $post=JFactory::getApplication()->input->post->getArray();
        $email = $post['reset_email'];
        $username =  $post['reset_username'];
        $id = JUserHelper::getUserId($username);

        if($id != null)
        {
            $db = JFactory::getDbo();
            $query = $db
                ->getQuery(true)
                ->select('email')
                ->from($db->quoteName('#__miniorange_tfa_users'))
                ->where($db->quoteName('id') . " = " . $id);
            $db->setQuery($query);
            $email = $db->loadResult();
        }
        else{
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=user_management',JText::_('COM_MINIORANGE_SETUP2FA_USER').$username.JText::_('COM_MINIORANGE_SETUP2FA_NOT_EXIST'), 'error');
            return;
        }
		$get_user= json_decode(commonUtilitiesTfa::get_user_on_server($email),true);

		if($get_user['status'] == 'SUCCESS')
		{
			// Delete the user from server if reset the 2FA successfully
			$response = json_decode(commonUtilitiesTfa::delete_user_from_server($email),true);
			if($response['status'] == 'SUCCESS')
			{
				// Delete the user from '#__miniorange_tfa_users' tables.
				commonUtilitiesTfa::delete_user_from_joomla_database($email);
				commonUtilitiesTfa::delete_rba_settings_from_database($id);
				$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=user_management',JText::_('COM_MINIORANGE_SETUP2FA_METHOD').$username.JText::_('COM_MINIORANGE_SETUP2FA_RESET_SUCCESS'));
				return;
			}
			else
			{
				$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=user_management',JText::_('COM_MINIORANGE_SETUP2FA_RESET_ERROR').$username.'</strong>.','error');
				return;
			}
		}
        else if($get_user['status'] == 'FAILED' && $get_user['message'] == 'Invalid username/email or password.')
		{
			$row = commonUtilitiesTfa::getMoTfaUserDetails($id);
			if(!empty($row))
			{
				commonUtilitiesTfa::delete_user_from_joomla_database($email);
				commonUtilitiesTfa::delete_rba_settings_from_database($id);
				$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=user_management','2FA reset for <strong>'.$username.'</strong> is successful.');
				return;
			}
	
			else
			{
				$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=user_management','There occured some error resetting the 2FA for <strong>'.$username.'</strong>.','error');
				return;
			}
			
		}
        
    }

	public function removeAccount()
	{
		$c_time =date("Y-m-d",time());
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		 // Fields to update.
		$fields = array(
			$db->quoteName('email') . ' = '.$db->quote(""),
			$db->quoteName('password') . ' = '.$db->quote(""),
			$db->quoteName('admin_phone') . ' = '.$db->quote(""),
			$db->quoteName('customer_key') . ' = '.$db->quote(""),
			$db->quoteName('customer_token') . ' = '.$db->quote(""),
			$db->quoteName('api_key') . ' = '.$db->quote(""),
			$db->quoteName('app_secret') . ' = '.$db->quote(""),
			$db->quoteName('login_status') . ' = '.$db->quote(0),
			$db->quoteName('registration_status') . ' = '.$db->quote("not-started"),
			$db->quoteName('new_registration') . ' = '.$db->quote(0),
			$db->quoteName('transaction_id') . ' = '.$db->quote(""),
			$db->quoteName('license_type') . ' = '.$db->quote(""),
			$db->quoteName('license_plan').' ='.$db->quote(""),
			$db->quoteName('no_of_users').' ='.$db->quote(0),
			$db->quoteName('jid') . ' = '.$db->quote(0),
			$db->quoteName('smsRemaining') . ' = '.$db->quote(0),
			$db->quoteName('emailRemaining') . ' = '.$db->quote(0),
			$db->quoteName('supportExpiry') . ' = '.$db->quote('0000-00-00 00:00:00'),
			$db->quoteName('licenseExpiry') . ' = '.$db->quote('0000-00-00 00:00:00'),
			$db->quoteName('fid') . ' = '.$db->quote(0),

		);
		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('id') . ' = 1'
		);
		$query->update($db->quoteName('#__miniorange_tfa_customer_details'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$db->execute();

		$db1 = JFactory::getDbo();
		$query1 = $db1->getQuery(true);
		 // Fields to update.
		$query1->delete($db1->quoteName('#__miniorange_tfa_users'));
		$db1->setQuery($query1);
		$db1->execute();
		
		$this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=account_setup',JText::_('COM_MINIORANGE_SETUP2FA_ACCOUNT_REMOVE'),'success');
	} 

	function updateCssConfig(){
        
        $post=	JFactory::getApplication()->input->post->getArray();
		if(count($post)==0){
            $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=popup_design');
            return;
        } 
		$Newcss ="";
		$radius= isset($post['radius'])?$post['radius']:8;
        $margin = (isset($post['margin'])&&$post['margin']!='0')?$post['margin']:5;
        $bgcolor =isset($post['bgcolor'])?$post['bgcolor']:"#FFFFFF";
        $bordertop = isset($post['bordertop'])?$post['bordertop']:"#20b2aa";
        $borderbottom = isset($post['borderbottom'])?$post['borderbottom']:"#20b2aa";
        $primarybtn = isset($post['primarybtn'])?$post['primarybtn']:"#fb9a9a";
        $height = isset($post['height'])?$post['height']:"200px";

		$Newcss .="border-radius:".$radius."px;background-color:".$bgcolor.";border-top:".$margin."px "."solid ".$bordertop.";border-bottom:".$margin."px "."solid ".$borderbottom.";min-height:".$height."px;";

		

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		 // Fields to update.

		
		$fields = array(
			$db->quoteName('customFormCss').' = '.$db->quote($Newcss),
            $db->quoteName('primarybtnCss').' = '.$db->quote($primarybtn),
           
		);
		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('id') . ' = 1'
		);
		
		$query->update($db->quoteName('#__miniorange_tfa_settings'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$db->execute();

		$message =  JText::_('COM_MINIORANGE_SETUP2FA_CONFIG_MSG');
        $this->setRedirect('index.php?option=com_miniorange_twofa&tab-panel=popup_design',$message ,'success');
	}

	public function joomlapagination()
    {
		
		$post       = JFactory::getApplication()->input->post->getArray();
    
        $total_entries = commonUtilitiesTfa::_get_all_login_attempts_count();
        $start      = isset($post['page']) ? $post['page'] : '';
        $order      = isset($post['orderBY']) ? $post['orderBY'] : 'down';
        $no_of_entry= isset($post['no_of_entry']) ? $post['no_of_entry'] : 10;

        $first_val  = (int)$start * $no_of_entry;
        $first_val  = ($total_entries == 0) ? -1 : $first_val;
        $last_val   = $no_of_entry + $first_val;

        if($last_val >= $total_entries)
        {
            $last_val = $total_entries;
        }

        $low_id    = (int)$start * $no_of_entry;
        $upper_id  =  $no_of_entry;
        $first_val = $first_val + 1;

        $data = commonUtilitiesTfa::_get_login_transaction_reports();

        if($last_val == $total_entries)
        {
            echo '<script>
                document.getElementById("next_btn").style.display = "none";              
            </script>';
        }

        $list_of_login_trans = commonUtilitiesTfa::_get_login_attempts_count($low_id, $upper_id,$order);

        $icnt = count($list_of_login_trans[0]);
		$active2FA = commonUtilitiesTfa::getAllTfaMethods();

        $result = '';
        $result .= '<div class="mo_boot_row" ><div class="mo_boot_col-sm-12" style="overflow:auto;">
            <table class="table" id="Tfa_table">
            <thead class="table-light table-hover">
                    <tr>
                    <th>'. JText::_("COM_MINIORANGE_EMAIL_USERNAME").'</th>
                    <th>'. JText::_("COM_MINIORANGE_USER_EMAIL").'</th>
                    <th class="mo_boot_text-center">'. JText::_("COM_MINIORANGE_USER_PHONE").'</th>
                    <th class="mo_boot_text-center">'. JText::_("COM_MINIORANGE_USER_ROLE").'</th>
                    <th class="mo_boot_text-center">'. JText::_("COM_MINIORANGE_USER_METHOD").'</th>
                    <th class="mo_boot_text-center">'. JText::_("COM_MINIORANGE_USER_STATUS").'</th>
                    <th class="mo_boot_text-center">'. JText::_("COM_MINIORANGE_USER_ACTION").'</th>
                </tr>
            </thead>
                <tbody style="font-size: 13px;color:black;">';
        foreach ($list_of_login_trans as $list2)
        {
            foreach ($list2 as $key=>$list)
            {
                if (!empty($list['username']) && (!empty($list['email'])|| !empty($list['status_of_motfa'])))
                {
					$serialNum = (int)($key) + 1;
					
					$result .='<tr style="line-height: 25px;"><td>'.$list['jUsername'].'</td>'.'<td>'.$list['email'].'</td>'.'<td class="mo_boot_text-center">'.$list['phone'].'</td>';

                    $result .= '<td class="mo_boot_text-center" >'.$list['user_group'].'</td>';
					if($list['active_method'] != 'NONE' && !empty($list['active_method']))
					{
						$result .='<td class="mo_boot_text-center"><strong>'.$active2FA[$list['active_method']]['name'].'</strong></td>';
					}
					else
					{
						$result .='<td class="mo_boot_text-center"><strong>None</strong></td>';
					}

					if($list['status_of_motfa']=='active' || $list['status_of_motfa']=='five')
					{
						$result .= '<td class="mo_boot_text-center"><label class=" mo_btn-status mo_btn-tfa-enabled">'.JText::_("COM_MINIORANGE_USER_ACTIVE").'</label></td>';
					}
					else
					{
						$result .= '<td class="mo_boot_text-center"><label class="mo_btn-status mo_btn-tfa-disabled">'.JText::_("COM_MINIORANGE_USER_INACTIVE").'</label></td>';
					}
                    $result.='<td>
					<form class="mo_boot_text-center" id="form_user'.$serialNum.'" method="post" action="index.php?option=com_miniorange_twofa&task=setup_two_factor.resetUser2FA">
					<input type="button" name="reset_user" value="Reset" id="reset_user" onclick="getValue('.$serialNum.')" class="mo_boot_btn mo_boot_btn-dark" style="height: 30px;padding: 2px;width:66px;">
					<input type="hidden" id="reset_username'.$serialNum.'" name="reset_username" value="'.$list['jUsername'].'">
					<input type="hidden" id="reset_email'.$serialNum.'" name="reset_email" value="'.$list['email'].'">
					</form></td></tr>';

                }			
            }
	
        }

        $result .= '</tr>
                    </tbody>
                    </table>
                    </div></div></div><br>
                    <div class="mo_boot_col-sm-6" id="tfa_entries">Showing '.$first_val .' - '. $last_val .' of '. "<span id='total_entries'>$total_entries".'</span> entries</div>
					<script>
					
					jQuery(document).ready(function(){
                        var table = document.getElementById("Tfa_table");
                        var tbody = table ? table.getElementsByTagName("tbody")[0] : null;
                        var rowCount = Array.from(document.getElementById("Tfa_table").children[1].children).filter(child => child.style.display !== "none").length;
                        
                    });
					</script>';
        $entries = commonUtilitiesTfa::_get_all_login_attempts_count();
        if ($entries == 0){
            $result .= '<br><br><br><br><br><br><br><br>';
        }
        else if ($entries == 1){
            $result .= '<br><br><br><br><br><br>';
        }
        else if ($entries == 2){
            $result .= '<br><br><br><br>';
        }
        else if ($entries == 3){
            $result .= '<br><br><br>';
        }
        else if ($entries == 4){
            $result .= '<br>';
        }
        echo $result;
        exit;
	}

}
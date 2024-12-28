<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_miniorange_twofa
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
jimport('miniorangetfa.utility.commonUtilitiesTfa');
jimport('miniorangetfa.utility.miniOrangeUser');


class miniorange_twofaControllerminiorange_inline extends JControllerForm
{
    public function skipTwoFactor()
    {
        $session = JFactory::getSession();
        $info    = $session->get('motfa');
        $current_user=$info['inline']['whoStarted'];
        $details=commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
        $activeMethod = is_null($details)?'NONE':$details['active_method'];
        $row = commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
        if ( $activeMethod != "NONE") {
            commonUtilitiesTfa::updateOptionOfUser($current_user->id,'active_method', 'NONE');
            commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'status_of_motfa', 'inactive');
            $this->performLogin(true);
        } else {
            commonUtilitiesTfa::insertOptionOfUser($current_user->username,$current_user->id, 'NONE', 'active', $current_user->email, $current_user->email );
            commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'status_of_motfa', 'inactive');
            $this->performLogin(true);
        }
    }


    function testing()
    {

        $post = JFactory::getApplication()->input->post->getArray();
        $email = $post['miniorange_registered_email'];
        $session = JFactory::getSession();
        $info = $session->get('motfa');
        $current_user = $info['inline']['whoStarted'];

        $row = commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
         
        if (is_array($row) && isset($row['id']) && $row['id'] == $current_user->id) {
            commonUtilitiesTfa::updateMoTfaUser($current_user->id, $email, $email, '');
        } else {
            commonUtilitiesTfa::insertMoTfaUser($current_user->username,$current_user->id, $email, $email, '');
        }
         $user = new miniOrangeUser();
                $response = json_decode($user->challenge($current_user->id, 'OOE', true));

                if ($response->status == 'SUCCESS') 
                {

                    commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'transactionId', $response->txId);
                    commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'status_of_motfa', 'one');

                    $mo_2fa_user_details = commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);

                    $session->set('steps', 'two');
                    $session->set('mo_tfa_message', JText::_('COM_MINIORANGE_OTP_SUCCESS_MSG') . $email . JText::_('COM_MINIORANGE_MSG_ENTER_OTP'));
                    $session->set('mo_tfa_message_type', 'mo2f-message-status');
                    $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                    return;
                } else {
                    $session->set('mo_tfa_message', $response->message);
                    $session->set('mo_tfa_message_type', 'mo2f-message-error');
                    $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                    return;
                }
    }

    public function pageTwoSubmit(){
		$post = JFactory::getApplication()->input->post->getArray();
		$user = new miniOrangeUser();
		$session=JFactory::getSession();
		$info=$session->get('motfa');
		$current_user=$info['inline']['whoStarted'];
		$row = commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
        
		$response= $user->validate($current_user->id,$post['Passcode'],NULL,NULL,true);
		$response=json_decode($response);
        
        
		if($response->status=='SUCCESS') 
        {
            $customer_details = commonUtilitiesTfa::getCustomerDetails();
            $email = $current_user->email;
            
            if($customer_details['email']!=$email)
            {
                $user_create_response = json_decode($user->mo_create_user($current_user->id,$current_user->name));
            }
            
            if($user_create_response->status=='SUCCESS'){
				$mo_2fa_user_details=commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
			    $user_tfamethod_update_reponse=json_decode($user->mo2f_update_userinfo($mo_2fa_user_details['email'],'OOE'));
			    
                if($user_tfamethod_update_reponse->status=='SUCCESS'){
                    $session->set('steps','three');
                    $session->set('mo_tfa_message','Your 2FA account is created successfully. Please complete the setup.');
                    $session->set('mo_tfa_message_type','mo2f-message-status');
                    commonUtilitiesTfa::updateOptionOfUser($current_user->id,'status_of_motfa','three');
                    $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                    return;
                }
			    else{
                    $session->set('steps','three');
                    $session->set('mo_tfa_message',$user_tfamethod_update_reponse->message);
                    $session->set('mo_tfa_message_type','mo2f-message-error');
                    $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                    return;
                }
			}

		}
		else
        {
			$session->set('mo_tfa_message',$response->message);
            $session->set('mo_tfa_message_type','mo2f-message-error');
			$this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
			return;
		}
	} 

    public function thirdStepSubmit()
    { 
        $post = JFactory::getApplication()->input->post->getArray();
        $user = new miniOrangeUser();
        $session = JFactory::getSession();
        $info = $session->get('motfa');
        $current_user = isset($info['inline']['whoStarted']) ? $info['inline']['whoStarted'] : '';
        $method = $post['miniorangetfa_method'];
        $get_response=commonUtilitiesTfa::get_user_on_server($current_user->email);

        if(empty($post))
        {
            $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
            return;
        }
        $tfaSettings = commonUtilitiesTfa::getMoTfaSettings();
        $enable_backup_method = isset($tfaSettings['enable_backup_method']) ? $tfaSettings['enable_backup_method'] : 0;
        $backup_method_type = isset($tfaSettings['enable_backup_method_type']) ? $tfaSettings['enable_backup_method_type'] : '';
        $change2FAMethod = isset($tfaSettings['enable_change_2fa_method']) && $tfaSettings['enable_change_2fa_method'] == 1;
        $isChange2FAEnabled = $session->get('change2FAEnabled');

            $row = commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
            $email = $current_user->email;
            $username = $current_user->username;
            
            if ((!is_array($row)) || (!isset($row['id'])) || ($row['id'] != $current_user->id)) 
            {
                commonUtilitiesTfa::insertMoTfaUser($username,$current_user->id, $email, $email, '');
            }

            if($isChange2FAEnabled && $change2FAMethod)
            {
                if(isset($method) && !empty($method))
                {
                    $info['stepThreeMethod']=$post['miniorangetfa_method'];
                    $session->set('motfa',$info);
                    commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'backup_method', $backup_method_type);
                    commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'active_method', $method);
                    commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'status_of_motfa', 'three');
                    commonUtilitiesTfa::delete_rba_settings_from_database($current_user->id);
                    if($method=='OOE'){

                            if(!commonUtilitiesTfa::isValidUid($current_user->id)){
                                $session->set('steps','invalid');
                            }
                            else
                            {                    
                                $user = new miniOrangeUser();
                                $response = json_decode($user->challenge($current_user->id, 'OOE', true));
                                if ($response->status == 'SUCCESS'){
                                    commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'transactionId', $response->txId);
                                    commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'status_of_motfa', 'three');
                                    $mo_2fa_user_details = commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
                                    json_decode($user->mo2f_update_userinfo($mo_2fa_user_details['email'], 'OOE'));
                                    $email=commonUtilitiesTfa::_getMaskedEmail($email);
                           
                                    $session->set('steps','validateEmail');
                                    $session->set('mo_tfa_message', JText::_('COM_MINIORANGE_OTP_SUCCESS_MSG') . $hiddenEmail . JText::_('COM_MINIORANGE_MSG_ENTER_OTP'));
                                    $session->set('mo_tfa_message_type', 'mo2f-message-status');
                                    $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                                    return;
                                }
                            }
                    }
                    else
                    {
                        $session->set('mo_tfa_message','');
                        $session->set('steps','four');
                        $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                        return;	
                    }	 
                }
            }
            else
            { 
                $response=commonUtilitiesTfa::delete_user_from_server($current_user->email);
                
                $row = commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);

                $customer_details = commonUtilitiesTfa::getCustomerDetails();
                $email = $current_user->email;
                
                if($customer_details['email']!=$email)
                {
                    $user_create_response = json_decode($user->mo_create_user($current_user->id,$current_user->name));
                    
                    if(strtolower($user_create_response->status)=='error' && $user_create_response->message=='An error occurred. Please contact miniOrange administrator.')
                    {
                        commonUtilitiesTfa::delete_user_from_joomla_database($email);
                        $session->set('steps','three');
                        $session->set('mo_tfa_message',$user_create_response->message);
                        $session->set('mo_tfa_message_type','mo2f-message-error');
                        $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                        return;
                    }
                }
                
                $mo_2fa_user_details=commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
                $user_tfamethod_update_reponse=json_decode($user->mo2f_update_userinfo($mo_2fa_user_details['email'],'OOE'));
                
                if($user_tfamethod_update_reponse->status=='SUCCESS')
                {
                    if(isset($method) && !empty($method))
                    {
                        $info['stepThreeMethod']=$post['miniorangetfa_method'];
                        $session->set('motfa',$info);
                        commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'backup_method', $backup_method_type);
                        commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'active_method', $method);
                        commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'status_of_motfa', 'three');
                        if($method=='OOE')
                        {
                            if(!commonUtilitiesTfa::isValidUid($current_user->id)){
                                $session->set('steps','invalid');
                            }
                            else
                            {                   
                                $user = new miniOrangeUser();
                                $response = json_decode($user->challenge($current_user->id, 'OOE', true));
                                
                                if ($response->status == 'SUCCESS'){
                                    commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'transactionId', $response->txId);
                                    commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'status_of_motfa', 'three');
                                    $mo_2fa_user_details = commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
                                    json_decode($user->mo2f_update_userinfo($mo_2fa_user_details['email'], 'OOE'));

                                    $email=commonUtilitiesTfa::_getMaskedEmail($email);

                                    $session->set('steps','validateEmail');
                                    $session->set('mo_tfa_message', JText::_('COM_MINIORANGE_OTP_SUCCESS_MSG') . $email . JText::_('COM_MINIORANGE_MSG_ENTER_OTP'));
                                    $session->set('mo_tfa_message_type', 'mo2f-message-status');
                                    $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                                    return;
                                }
                            }
                        }
                        else
                        {
                            $session->set('steps','four');
                            $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                            return;	
                        }	
                    }
                }
                else
                {
                    
                    $session->set('steps','three');
                    $session->set('mo_tfa_message',$user_tfamethod_update_reponse->message);
                    $session->set('mo_tfa_message_type','mo2f-message-error');
                    $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                    return;
                }
               
            }   
        
    }

    function pageFourAndHAlf()
    {
        $post = JFactory::getApplication()->input->post->getArray();
        $user = new miniOrangeUser();
        $session = JFactory::getSession();
        $info = $session->get('motfa');
        $current_user = isset($info['inline']['whoStarted']) ? $info['inline']['whoStarted'] : '';
        $current_user_id = commonUtilitiesTfa::getCurrentUserID($current_user);
        $method = $info['stepThreeMethod'];
        $phone = $post['phone'];
        commonUtilitiesTfa::updateOptionOfUser($current_user_id, 'phone', $phone);
        $tfaSettings = commonUtilitiesTfa::getMoTfaSettings();
        $enable_backup_method = isset($tfaSettings['enable_backup_method']) ? $tfaSettings['enable_backup_method'] : 0;
        
        $mo_user = new miniOrangeUser();
   
         
        $send_otp_response = json_decode($mo_user->challenge($current_user_id, $method, true));

        if ($send_otp_response->status == 'SUCCESS') {
            
            commonUtilitiesTfa::updateOptionOfUser($current_user_id, 'transactionId', $send_otp_response->txId);
            $session->set('mo_tfa_message',$send_otp_response->message);
            $session->set('mo_tfa_message_type', 'mo2f-message-status');
            $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
            return;
        
        } else {
            $session->set('mo_tfa_message', $send_otp_response->message);
            $session->set('mo_tfa_message_type', 'mo2f-message-error');
            $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
            return;
        }

    } 

    public function pageFourValidatePasscode()
    {
        
        $post = JFactory::getApplication()->input->post->getArray();
        $user = new miniOrangeUser(); 
        $session = JFactory::getSession();
        $info = $session->get('motfa'); 
        $current_user = isset($info['inline']['whoStarted']) ? $info['inline']['whoStarted'] : '';
        $current_user_id = commonUtilitiesTfa::getCurrentUserID($current_user);
        $method = $info['stepThreeMethod'];
        $email = $current_user->email;

        $tfaSettings = commonUtilitiesTfa::getMoTfaSettings();
        $enable_backup_method = isset($tfaSettings['enable_backup_method']) ? $tfaSettings['enable_backup_method'] : 0;
        $backup_method = isset($tfaSettings['enable_backup_method_type']) ? $tfaSettings['enable_backup_method_type'] : "";

        if ($method == 'google' || $method == 'MA' || $method == 'AA' || $method == 'LPA' || $method == 'DUO') {
            
            $row = commonUtilitiesTfa::getMoTfaUserDetails($current_user_id);
            $response = json_decode($user->validateGoogleToken($row['username'], $row['transactionId'], trim($post['Passcode']), $method));
        } 
        elseif($method == 'YK')
        {
            $row = commonUtilitiesTfa::getMoTfaUserDetails($current_user_id);
            $response = $user->validateGoogleToken($row['username'], $row['transactionId'], $post['mo_auth_token_textfield'], $method);
            $response = json_decode($response);
        }
        else {
            $response = $user->validate($current_user_id, trim($post['Passcode']), $method, NULL, true);
            $response = json_decode($response);
        }
        

        if ($response->status == 'SUCCESS') {
            if($enable_backup_method==1){

                if ($method != 'google' && $method != 'MA' && $method != 'AA' && $method != 'LPA' && $method != 'DUO') {
                    $row = commonUtilitiesTfa::getMoTfaUserDetails($current_user_id);
                    $user->mo2f_update_userinfo($row['email'], $method, '');
                }
                commonUtilitiesTfa::updateOptionOfUser($current_user_id, 'status_of_motfa', 'five');
                commonUtilitiesTfa::updateOptionOfUser($current_user_id, 'active_method', $method);
                commonUtilitiesTfa::updateOptionOfUser($current_user_id, 'backup_method', $backup_method);
                $session->set('mo_tfa_message', '');
                $session->set('steps', 'five'); 
                $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA&step=five');
                return;
            } 
            else{
                if ($method != 'google' && $method != 'MA' && $method != 'AA' && $method != 'LPA' && $method != 'DUO') {
                    $row = commonUtilitiesTfa::getMoTfaUserDetails($current_user_id);
                    $user->mo2f_update_userinfo($row['email'], $method,'');
                }
                commonUtilitiesTfa::updateOptionOfUser($current_user_id, 'active_method', $method);
                commonUtilitiesTfa::updateOptionOfUser($current_user_id, 'status_of_motfa', 'active');
                commonUtilitiesTfa::updateOptionOfUser($current_user_id, 'backup_method', $backup_method);
                $session->set('mo_tfa_message', '');
                $this->performLogin(true);
            }
        } 
        else {
            $session->set('mo_tfa_message', $response->message);
            $session->set('mo_tfa_message_type', 'mo2f-message-error');
            $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
            return;
        }
    }

    public function stepFiveSubmit()
    {
        $post = JFactory::getApplication()->input->post->getArray();
        $session = JFactory::getSession();
        $question1 = $post['mo_tfa_ques_1'];
        $answer1 = $post['mo_tfa_ans_1'];
        $question2 = $post['mo_tfa_ques_2'];
        $answer2 = $post['mo_tfa_ans_2'];
        $question3 = $post['mo_tfa_ques_3'];
        $answer3 = $post['mo_tfa_ans_3'];
        if ($question1 == $question2 || $question1 == $question3 || $question2 == $question3) {
            $session->set('mo_tfa_message', JText::_('COM_MINIORANGE_MSG_KBA'));
            $session->set('mo_tfa_message_type', 'mo2f-message-error');
            $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
            return;
        }
        $user = new miniOrangeUser();

        $info = $session->get('motfa');
        $current_user = isset($info['inline']['whoStarted']) ? $info['inline']['whoStarted'] : '';
        $current_user_id = commonUtilitiesTfa::getCurrentUserID($current_user);
        $row = commonUtilitiesTfa::getMoTfaUserDetails($current_user_id);
        $user = new miniOrangeUser();
        $kba_response = json_decode($user->register_kba_details($row['email'], $question1, $answer1, $question2, $answer2, $question3, $answer3));
    
        if ($kba_response->status == 'SUCCESS') {
            commonUtilitiesTfa::updateOptionOfUser($current_user_id, 'status_of_motfa', 'active');
            $this->performLogin(true);
        } else {
            $this->performLogin(true);
        }
    }

    public function stepFiveSubmitForBackupCode()
    {
        $post = JFactory::getApplication()->input->post->getArray();
        $session = JFactory::getSession();
        $info = $session->get('motfa');
        $current_user = isset($info['inline']['whoStarted']) ? $info['inline']['whoStarted'] : '';
        $current_user_id = commonUtilitiesTfa::getCurrentUserID($current_user);
        $backup_codes = isset($post['backup_codes_values']) ? $post['backup_codes_values'] : '';
        $encoded_backup_codes = base64_encode($backup_codes);
        $tfaSettings   = commonUtilitiesTfa::getTfaSettings();
        $backup_method_type = isset($tfaSettings['enable_backup_method_type'])?$tfaSettings['enable_backup_method_type'] : '';
        commonUtilitiesTfa::updateOptionOfUser($current_user_id, 'mo_backup_codes', $encoded_backup_codes);
        commonUtilitiesTfa::updateOptionOfUser($current_user_id, 'status_of_motfa', 'active');
        commonUtilitiesTfa::updateOptionOfUser($current_user_id, 'backup_method', $backup_method_type);
        $this->performLogin(true);
    }

    public function handleBackOfInline()
    {
        $session = JFactory::getSession();
        $info = $session->get('motfa');
        $current_user = isset($info['inline']['whoStarted']) ? $info['inline']['whoStarted'] : '';
        $row = commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
        if(!empty($row))
        {
            commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'status_of_motfa', '');
        }
        
        if ($session->get('started_at_admin') == 'yes') {
            $this->setRedirect(JURI::base() . 'administrator/index.php');
        } else {
            $this->setRedirect('index.php');

        }
        return;
    }

    public function backValidateBackup(){
        $session = JFactory::getSession();
        $session->set('steps', 'active');
        $session->set('mo_tfa_message', '');
        $session->set('mo_tfa_message_type', '');
        $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
        return;
    }

    public function gotoPreviousStep(){
        $session = JFactory::getSession();
        $session->set('steps', 'three');
        $session->set('mo_tfa_message', '');
        $session->set('mo_tfa_message_type', '');
        $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
        return;
    }

    public function navigateToBack()
    {
        $session = JFactory::getSession();
        $session->set('steps', 'invokeOOE');
        $session->set('mo_tfa_message', '');
        $session->set('mo_tfa_message_type', '');
        $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
        return;
    }

    public function handleBackOfInlineTwo()
    {
        $session = JFactory::getSession();
        $session->set('steps', 'one');
        $session->set('mo_tfa_message', '');
        $session->set('mo_tfa_message_type', '');
        $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
        return;
    }

    public function handleBackOfInlineThree()
    {
        $session = JFactory::getSession();
        $info = $session->get('motfa');
        $current_user = $info['inline']['whoStarted'];
        commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'status_of_motfa', 'one');
        $session->set('steps', 'one');
        $session->set('mo_tfa_message', '');
        $session->set('mo_tfa_message_type', '');
        $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
        return;
    }

    public function handleBackOnChange2FA(){
        $session = JFactory::getSession();
        $session->set('steps', 'active');
        $session->set('mo_tfa_message', '');
        $session->set('mo_tfa_message_type', '');
        $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
        return;
    }

    public function handleBackOfInlineFour()
    {
        $session = JFactory::getSession();
        $session->set('mo_tfa_message', '');
        $session->set('mo_tfa_message_type', '');
        $session->set('steps', 'three');
        $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
        return;
    }

    public function handleBackOfInlineFive()
    {
        $session = JFactory::getSession();
        $tfaSettings = commonUtilitiesTfa::getMoTfaSettings();
        $isChange2FAEnabled = $session->get('change2FAEnabled');
        $change2FAMethod = isset($tfaSettings['enable_change_2fa_method']) && $tfaSettings['enable_change_2fa_method'] == 1;

        if($isChange2FAEnabled && $change2FAMethod) {
            $session->set('steps', 'invokeOOE');
        }
        else{
            $session->set('steps', 'four');
        }
        $session->set('mo_tfa_message', '');
        $session->set('mo_tfa_message_type', '');
        $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
        return;
    }

    public function validateTfaChallange()
    {
        $post = JFactory::getApplication()->input->post->getArray();
        $user = new miniOrangeUser();
        $session = JFactory::getSession();
        $userId = $session->get('current_user_id');
        $challenge_response = $session->get('challenge_response');
        $response = $user->validate($userId, $post['passcode'], $challenge_response->authType, NULL);
        $response = json_decode($response);
        $username = $post['username'];
        $attributes="";

        
        $reconfigure2FA = isset($post['reconfigure_2fa']) ? $post['reconfigure_2fa'] : '';
        $remember_device = isset($post['remember_device']) ? $post['remember_device'] : '';
        
        if (isset($post['miniorange_rba_attributes'])) {
            $attributes = $post['miniorange_rba_attributes'];
          }
    
        if($reconfigure2FA == 'on'){

            if ($response->status == 'SUCCESS') {
                $session = JFactory::getSession();
                $session->set('steps', 'three');
                $moTfa=array('inline'=>array('whoStarted'=>JFactory::getUser($userId),'status'=>'attempted'));
                $session->set('motfa',$moTfa);
                $session->set('mo_tfa_message','');
                $session->set('change2FAEnabled', 'TRUE');
                $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                return;
            } else {
               $this->showInvalidMessage($response);
            }
        }
    
        elseif($remember_device == 'on'){
            
            if($response->status == 'SUCCESS'){
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);

                $columns = array('user_id', 'mo_rba_device');
                
                $values = array($db->quote($userId), $db->quote($attributes));
                
                $query
                ->insert($db->quoteName('#__miniorange_rba_device'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
                
                $db->setQuery($query);
                $db->execute();

                $cookie_value    =  self::getRandomString(8);
                $cookie_name = "rba";
                
                setcookie($cookie_name, $cookie_value, time() + (86400 * 1), "/");
                
                $this->performLogin();
            }
            else {
                $this->showInvalidMessage($response);
            }
        }
        else{
            if ($response->status == 'SUCCESS') {
                $this->performLogin();
            } else {
                $this->showInvalidMessage($response);
            }
        }
    }

    function getRandomString($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
      
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
      
        return $randomString;
    }

    public function showInvalidMessage($response){
        $session = JFactory::getSession();
        $session->set('mo_tfa_message', $response->message);
        $session->set('mo_tfa_message_type', 'mo2f-message-error');
        $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
        return;
    }

    public function handleForgotForm()
    {
        $user = new miniOrangeUser();
        $session = JFactory::getSession();
        $userId = $session->get('current_user_id');
        $get_user_details = commonUtilitiesTfa::getMoTfaUserDetails($userId);
        if($get_user_details['backup_method']=='backupCodes')
        {
            $session->set('steps', 'backup');
            $session->set('mo_tfa_message', JText::_('COM_MINIORANGE_MSG'));
            $session->set('mo_tfa_message_type', 'mo2f-message-status');
            $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
            return;
        }
        else if($get_user_details['backup_method']=='securityQuestion')
        
        {
            $response = json_decode($user->challenge($userId, 'KBA'));
            
            if ($response->status == 'SUCCESS') {
                $session->set('kba_response', $response);
                commonUtilitiesTfa::updateOptionOfUser($userId, 'transactionId', $response->txId);
                $session->set('mo_tfa_message', JText::_('COM_MINIORANGE_MSG_KBA1'));
                $session->set('mo_tfa_message_type', 'mo2f-message-status');
                $session->set('steps', 'KBA');
                $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                return;
            } 
            else {
                $session->set('mo_tfa_message', JText::_('COM_MINIORANGE_MSG_KBA2'));
                $session->set('mo_tfa_message_type', 'mo2f-message-error');
                $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
                return;
            }
        }
        
    }

    public function validateBackupCodes()
    {
        $post = JFactory::getApplication()->input->post->getArray();

        $session = JFactory::getSession();
        $current_userid = $session->get('current_userid');

        $entered_backup_code_value = trim(isset($post['backup_code_value']) ? $post['backup_code_value'] : '');


        $get_user_details = commonUtilitiesTfa::getMoTfaUserDetails($current_userid);
        $stored_backup_codes = isset($get_user_details['mo_backup_codes']) ? $get_user_details['mo_backup_codes'] : '';
        $stored_backup_codes = base64_decode($stored_backup_codes);
        $stored_backup_codes = explode(',', $stored_backup_codes);

        if(in_array($entered_backup_code_value, $stored_backup_codes)){


            foreach (array_keys($stored_backup_codes, $entered_backup_code_value) as $key) {
                unset($stored_backup_codes[$key]);
            }

            $stored_backup_codes = implode(',', $stored_backup_codes);
            $altered_backup_codes = base64_encode($stored_backup_codes);
            commonUtilitiesTfa::updateOptionOfUser($current_userid, 'mo_backup_codes', $altered_backup_codes);

            self::performLogin();
        }
        else{
            $session->set('mo_tfa_message', JText::_('COM_MINIORANGE_MSG_INVALID_CODE'));
            $session->set('mo_tfa_message_type', 'mo2f-message-error');
            $this->setRedirect(JURI::root().'index.php?option=com_miniorange_twofa&view=miniorange_twoFA&taskbcode=generatebackupcode');
        }

    }

    public function SubmitKBAForm()
    {
        $post = JFactory::getApplication()->input->post->getArray();

        $user = new miniOrangeUser();
        $session = JFactory::getSession();
        $userId = $session->get('current_user_id');

        $challangeInfo = $session->get('kba_response');
        $response = json_decode($user->challenge($userId, 'KBA'));

        $questions = $challangeInfo->questions;
        
        $answers = array();
        foreach ($questions as $key => $value) {
            $temp_arr = array("question" => $questions[$key]->question,
                "answer" => $post['answer' . $key],
            );
            array_push($answers, $temp_arr);
        }

        $response = json_decode($user->validate($userId, NULL, 'KBA', $answers));

        if ($response->status == 'SUCCESS') {
            $this->performLogin();

        } else {
            $session->set('mo_tfa_message', $response->message);
            $session->set('mo_tfa_message_type', 'mo2f-message-error');
            $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
            return;
        }
    }

    public function performLogin($inline = false)
    {
        $session = JFactory::getSession();

        if ($inline) {
            $info = $session->get('motfa');
            $userId = isset($info['inline']['whoStarted']->id) && !empty($info['inline']['whoStarted']->id) ? $info['inline']['whoStarted']->id : '';
            if(empty($userId) || $userId == '') {
                $userId = $session->get('juserId');
            }
        } else {
            $userId = $session->get('current_user_id');
        }
        $current_user = JFactory::getUser($userId);
        $session->set('tfa_verified', 'yes');

        $app = JFactory::getApplication();
        $isroot = $current_user->authorise('core.login.admin');

        $isChange2FAEnabled = $session->get('change2FAEnabled');
        
        if ($isroot && $session->get('started_at_admin') == 'yes') {
            $session->clear('tfa_verified');
            $session->clear('steps');
            $session->clear('motfa');
            $session->clear('current_user_id');
            $session->clear('challenge_response');
            $session->clear('mo_tfa_message');
            $session->clear('mo_tfa_message_type');
            $session->clear('kba_response');
            $salt = bin2hex(random_bytes(random_int(10, 50)));
            commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'transactionId', $salt);
            $this->setRedirect(JURI::base() . 'administrator/index.php?tfa_login=' . $userId . '&user=' . $salt);
            return;
        } else {
            
            JPluginHelper::importPlugin('user');
            $options = array();
            $options['action'] = 'core.login.site';
            $response = new stdClass();
            $response->username = $current_user->username;
            $response->language = '';
            $response->email = $current_user->email;
            $response->password_clear = '';
            $response->fullname = '';
            
            $result = $app->triggerEvent('onUserLogin', array((array)$response, $options));

            if ($isChange2FAEnabled) {
                $this->setRedirect('index.php',"TFA reset successful");
    
            }
            else if(in_array(false, $result)) {
                $app->logout($current_user->id);
                $this->setRedirect('index.php');
                return;
            }

            $is_idp_installed = commonUtilitiesTfa::isIdPInstalled();

            if(isset($is_idp_installed['enabled']) && $is_idp_installed['enabled'] == 1)
            {
                setcookie('2faInvokedSuccessfully', TRUE, time() + 3600, '/');
                $saml_request = isset($_COOKIE['SAMLRequest']) ? $_COOKIE['SAMLRequest'] : '';
                if(!empty($saml_request)){
                    $this->setRedirect($saml_request);
                    setcookie('SAMLRequest', '', time() - 3600, '/'); 
                    return;
                }
            }

            $redirectUrl = commonUtilitiesTfa::getTfaSettings()['afterLoginRedirectUrl'];
            $redirectUrl = empty($redirectUrl) ? 'index.php' : $redirectUrl;
            $this->setRedirect($redirectUrl, JText::_('COM_MINIORANGE_MSG_SUCCESS'));
            return;
        }
    }

    public function validateEmail()
    {

        $post = JFactory::getApplication()->input->post->getArray();
        $email = $post['miniorange_registered_email'];
        $session = JFactory::getSession();
        $info = $session->get('motfa');
        $session->set('ooe_for_change_2fa', 'TRUE');
        $current_user = $info['inline']['whoStarted'];

        $row = commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
        if (is_array($row) && isset($row['id']) && $row['id'] == $current_user->id) 
        {
            commonUtilitiesTfa::updateMoTfaUser($current_user->id, $email, $email, '');
        } 
        else 
        {
            commonUtilitiesTfa::insertMoTfaUser($current_user->$username,$current_user->id, $email, $email, '');
        }

        $user = new miniOrangeUser();

        $response = json_decode($user->challenge($current_user->id, 'OOE', true));
        if ($response->status == 'SUCCESS') {
            commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'transactionId', $response->txId);
            commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'status_of_motfa', 'one');
            $mo_2fa_user_details = commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
            json_decode($user->mo2f_update_userinfo($mo_2fa_user_details['email'], 'OOE'));

            $session->set('steps', 'validateEmail');
            $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
            return;
        } else {
            $session->set('mo_tfa_message', $response->message);
            $session->set('mo_tfa_message_type', 'mo2f-message-error');
            $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
            return;
        }
    }

    public function validateOOE()
    {
        $post = JFactory::getApplication()->input->post->getArray();
        $user = new miniOrangeUser();
        $session = JFactory::getSession();
        $info = $session->get('motfa');
        $current_user = $info['inline']['whoStarted'];
        $tfaSettings = commonUtilitiesTfa::getTfaSettings();
        $backup_method_type = isset($tfaSettings['enable_backup_method_type'])?$tfaSettings['enable_backup_method_type'] : '';
        $enable_backup_method = isset($tfaSettings['enable_backup_method']) ? $tfaSettings['enable_backup_method'] : 0;
        $response = $user->validate($current_user->id, $post['Passcode'], NULL, NULL, true);
        $response = json_decode($response);
    
        if ($response->status == 'SUCCESS') {

            if($enable_backup_method==1){
        
            commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'status_of_motfa', 'five');
            commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'backup_method', $backup_method_type);
            commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'active_method', 'OOE');
            $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
            $session->set('steps', 'five');
            return;
            }
            else{
                commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'active_method', 'OOE');
                commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'status_of_motfa', 'active');
                $this->performLogin(true);
                return;
            }
        }
        else {
            $session->set('mo_tfa_message', $response->message);
            $session->set('mo_tfa_message_type', 'mo2f-message-error');
            $this->setRedirect('index.php?option=com_miniorange_twofa&view=miniorange_twoFA');
            return;
        }
    }
}
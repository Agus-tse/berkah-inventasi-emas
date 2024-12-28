<?php
/**
 * @package     Joomla.User
 * @subpackage  User.miniorangetfa
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access

jimport('joomla.user.helper');

defined('_JEXEC') or die('Restricted access');
jimport('miniorangetfa.utility.commonUtilitiesTfa');
jimport('miniorangetfa.utility.miniOrangeUser');

/**
 * miniOrange 2FA Plugin plugin
 */
class PlgUserMiniorangetfa extends JPlugin
{
    /*2FA verification or Inline registration During Login */

    public function onUserAfterSave($user, $isnew, $success, $msg): void
    {

        $isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
        $app = JFactory::getApplication();
        
        $post = $app->input->post->getArray();
        $user_id=$user['id'];
        
        $updated_email    = $user['email'];
        $details_of_motfa = commonUtilitiesTfa::getMoTfaUserDetails($user_id);

        if($isCustomerRegistered && !empty($details_of_motfa) && !empty($details_of_motfa['email']) && $updated_email !==  $details_of_motfa['email'])
        {
            $user = new miniOrangeUser();
            $response = json_decode($user->updateEmail($details_of_motfa['email'],$updated_email));    

            if( $response->status=='SUCCESS'){ 
                commonUtilitiesTfa::updateOptionOfUser($user_id,'email',$updated_email);
                commonUtilitiesTfa::updateOptionOfUser($user_id,'username',$updated_email);
            }
        }
        
        
    }

    public function onUserLogin($user, $options = array())
    {

        $app = JFactory::getApplication();
        $settings = commonUtilitiesTfa::getTfaSettings();
        $isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
        $tfa_uid = JUserHelper::getUserId($user['username']);
        $c_user = JFactory::getUser($tfa_uid);
        $details = commonUtilitiesTfa::getCustomerDetails();
        
        $details_of_motfa = commonUtilitiesTfa::getMoTfaUserDetails($tfa_uid);
        $post = JFactory::getApplication()->input->post->getArray();
        $device_details=isset($post['miniorange_rba_attributes'])?($post['miniorange_rba_attributes']):'';
        
        $rba_var='';
        $session = JFactory::getSession();
        $tfaInfo = $session->get('tfa_verified');


        if(isset($post['Submit']) && ($post['Submit']=='password_login') && !empty($post['password']))
        {
            return TRUE;
        }


        $inputCookie  = JFactory::getApplication()->input->cookie;
        $rba_Verifier = $inputCookie->get($name = 'rba', $defaultValue = null);
        
        if(!is_null($details_of_motfa))
        {
            $db = JFactory::getDbo();
            $db->setQuery($db->getQuery(true)
                ->select('mo_rba_device')
                ->from($db->quoteName('#__miniorange_rba_device'))
                ->where($db->quoteName('user_id') . ' = '.$db->quote($tfa_uid))
            );
            $result= $db->loadColumn();
            
            if(!empty($result) && isset($post['Submit']) && ($post['Submit']!='passless_login') && $post['password']!='password')
            {
                foreach($result as $key=>$val)
                    {
    
                        if($device_details == $val)
                        {
                            if($rba_Verifier !== NULL)
                            {
                                setcookie('rba', $rba_Verifier, time() + (86400 * 30), "/"); 
                                $rba_var=true;
                                break;
                            }
                           else
                           {
                                $db = JFactory::getDbo();
                                $query = $db->getQuery(true);
                                $conditions = array(
                                    $db->quoteName('user_id') . ' = ' . $db->quote($tfa_uid)
                                );
                                $query->delete($db->quoteName('#__miniorange_rba_device'));
                                $query->where($conditions);
                                $db->setQuery($query);
                                $db->execute();
                                break;
                           }
                        }
                    }
            }
        }

        $session = JFactory::getSession();
        $tfaInfo = $session->get('tfa_verified');

        $userId = JUserHelper::getUserId($user['username']);
        $skip = commonUtilitiesTfa::getMoTfaUserDetails($userId);
        $no_of_users=commonUtilitiesTfa::checkMoTfaUsers();

        $jLanguage = $app->getLanguage();
        $jLanguage->load('plg_user_miniorangetfa', JPATH_ADMINISTRATOR, 'en-GB', true, true);
        $jLanguage->load('plg_system_updatenotification', JPATH_ADMINISTRATOR, null, true, false);
        
        if(is_null($details_of_motfa) && $no_of_users!=0 && $no_of_users >= $details['no_of_users'])
        {
            
            $app->enqueueMessage(JText::_('PLG_USER_MINIORANGETFA_MSG'), 'warning');
            return TRUE;
        }
        $session->set('current_userid', $tfa_uid);
        
        $isroot = $c_user->authorise('core.login.admin');

        $c_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $isAdminSite = strpos($c_url, 'administrator') == true ? 'Yes' : 'No';

        $bypass_tfa_for_admin = isset($settings['tfa_enabled_type']) && ($settings['tfa_enabled_type'] == 'site' );
        $bypass_tfa_for_users = isset($settings['tfa_enabled_type']) && ($settings['tfa_enabled_type'] == 'admin');


        if(is_null($details_of_motfa) && $settings['tfa_halt']==1 )
        {
            return TRUE;
        }


        if (($bypass_tfa_for_admin && $isroot && $isAdminSite == 'Yes') || ($bypass_tfa_for_admin && (isset($c_user->groups['7']) || isset($c_user->groups['8'])))) {
            return TRUE;
        }

        if ($bypass_tfa_for_users && !$isroot && $isAdminSite == 'No') {
            return TRUE;
        }

        if (!$isCustomerRegistered || !isset($settings['tfa_enabled']) || $settings['tfa_enabled'] == 0 || $this->roleBaseTfaDisabled($c_user->groups) || commonUtilitiesTfa::doWeHaveAwhiteIp(commonUtilitiesTfa::get_client_ip(), $settings))
        { 
            // dont invoke 2FA for this case
            return TRUE;
        }
        
        
        if( $details['license_type'] =='' && $details['license_plan']==''){

            return TRUE;
        }
        
        if(isset($rba_var) && $rba_var==TRUE)
        {
            return TRUE;
        }
 
        if($skip=='null'||is_array($skip)?($skip['active_method']!= 'NONE'):TRUE)
        {   
            
            if ($tfaInfo == 'yes') 
            {  
                $session->clear('tfa_verified');
                $session->clear('steps');
                $session->clear('motfa');
                $session->clear('current_user_id');
                $session->clear('challenge_response');
                $session->clear('mo_tfa_message');
                $session->clear('mo_tfa_message_type');
                $session->clear('kba_response');
                $tfa_ = $session->get('motfa_initiated');
                if (!isset($tfa_) || is_null($tfa_) || !in_array($tfa_uid, $tfa_)) {
                    return FALSE;
                }
                $session->clear('motfa_initiated');
                return TRUE;
            } 
            else 
            {  
                
                $post = JFactory::getApplication()->input->post->getArray();
                
                $userId = JUserHelper::getUserId($post['username']);
                
                if (isset($settings['tfa_enabled']) && $settings['tfa_enabled'] == 0) {
                    $row = commonUtilitiesTfa::getMoTfaUserDetails($userId);
                 
                    if (is_null($row) || count($row) == 0 || !isset($row['status_of_motfa']) || $row['status_of_motfa'] != 'active') {
                        $session->clear('motfa_initiated');
                        return TRUE;
                    }
                }
                
                $session->clear('tfa_verified');
                $session->clear('steps');
                $session->clear('motfa');
                $session->clear('current_user_id');
                $session->clear('challenge_response');
                $session->clear('mo_tfa_message');
                $session->clear('mo_tfa_message_type');
                $session->clear('kba_response');
                $tfa_arr = $session->get('motfa_initiated');
                
                if (is_null($tfa_arr) || !isset($tfa_arr)) {
                    $tfa_arr = array();
                }
                array_push($tfa_arr, $userId);
                $session->set('motfa_initiated', $tfa_arr);

                if ($app->isClient('site')) { 

                    header("Location:" . JURI::root() . "index.php?option=com_miniorange_twofa&view=miniorange_twofa&motfausers=" . commonUtilitiesTfa::encrypt($userId));
                   
                } else {
                    header("Location:" . JURI::root() . "index.php?option=com_miniorange_twofa&view=miniorange_twofa&motfausers=" . commonUtilitiesTfa::encrypt($userId) . '&admin=1');
                }
              exit();
            }
        }
    }


    public function roleBaseTfaDisabled($userRolesId)
    {
        $userRoles = array();
        $groups = commonUtilitiesTfa::loadGroups();
        $settings = commonUtilitiesTfa::getTfaSettings();
        $tfa_for_roles = isset($settings['mo_tfa_for_roles']) && !empty($settings['mo_tfa_for_roles']) ? json_decode($settings['mo_tfa_for_roles']) : array();
        foreach ($groups as $key => $value) {
            if (in_array($value['id'], $userRolesId)) {
                array_push($userRoles, $value['title']);
            }
        }

        if (count($tfa_for_roles) == 0)
        {
            return TRUE;
        }
        else if (in_array('ALL', $tfa_for_roles)) 
        {
            return FALSE;
        }

        foreach ($userRoles as $key => $value) 
        {
            if (in_array($value, $tfa_for_roles)) {
                return FALSE;
            }
        }
        return TRUE;
    }
}
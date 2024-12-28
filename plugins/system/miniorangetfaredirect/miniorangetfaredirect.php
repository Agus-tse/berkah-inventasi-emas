<?php
/**
 * @package     Joomla.Plugins
 * @subpackage  System.miniorangetfaredirect
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * Plugin class for miniorangetfa redirect handling.
 *
 * @since  1.6
 */
jimport('miniorangetfa.utility.commonUtilitiesTfa');
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>	
<?php                                                                                                   
class PlgSystemMiniorangetfaredirect extends JPlugin
{
    public function onAfterRender()
    {
        $app = JFactory::getApplication();
        $body = $app->getBody();
        $user = JFactory::getUser();  

        $get = JFactory::getApplication()->input->get->getArray();
        $post = $app->input->post->getArray();
    

        if(!isset($post['mojsp_feedback']))
        {
            $settings = commonUtilitiesTfa::getTfaSettings();
            $change2FAMethod = isset($settings['enable_change_2fa_method']) && $settings['enable_change_2fa_method'] == 1;
            $enabled_tfa     = isset($settings['tfa_enabled']) && $settings['tfa_enabled'] == 1;
            
            $details = commonUtilitiesTfa::getCustomerDetails();
            $no_of_users=commonUtilitiesTfa::checkMoTfaUsers();
            $isLicenseExpired = commonUtilitiesTfa::checkIsLicenseExpired();
            $licenseExp = strtotime($details['licenseExpiry']);
            $licenseExp = $licenseExp === FALSE || $licenseExp <= -62169987208 ? "-" : date("F j, Y, g:i a", $licenseExp);
            $config = JFactory::getConfig();
            $site_name = $config->get('sitename');

            if($user->id!=0 && ($isLicenseExpired['LicenseExpired'] || $isLicenseExpired['LicenseExpiry']))
            {
                commonUtilitiesTfa::_cuc();

                $plan_name = "Joomla 2FA";
                if($isLicenseExpired['LicenseExpired'])
                {
                    $msg="Your license for <b>".$plan_name."</b> plan has expired on ".$licenseExp.". If you want to renew your license please reach out to us at ";
                }
                if($isLicenseExpired['LicenseExpiry'])
                {
                    $msg="Your license for <b>".$plan_name."</b> plan is going to expire on ".$licenseExp.". If you want to renew your license please reach out to us at ";
                }
                
                $message='<div class="background_color_update_message ms-auto" style=" display:block;color:red;background-color:rgba(251, 232, 0, 0.15); border:solid 1px rgba(255, 0, 9, 0.36);padding: 10px ;margin: 10px ;">
                '.$msg.' <a style="color:red;" href="mailto:joomlasupport@xecirify.com"><strong>joomlasupport@xecurify.com</strong></a>
                </div>';
                $upgrade_message='<div class="container-fluid container-main">'.$message;
                $body=str_replace('<div class="container-fluid container-main">',$upgrade_message,$body);
                $app->setBody($body);
            }
            
            $jLanguage = $app->getLanguage();
            $jLanguage->load('plg_system_miniorangetfaredirect', JPATH_ADMINISTRATOR, 'en-GB', true, true);
            $jLanguage->load('plg_system_updatenotification', JPATH_ADMINISTRATOR, null, true, false);
         
            if($user->id!=0 && $no_of_users!='0' && $no_of_users >= $details['no_of_users'] )
            {
                if(stristr($body,'content') && !isset($get['option']))
                {
                    $msg=JText::_('PLG_SYSTEM_MINIORANGETFAREDIRECT_USERLIMIT_MSG');
                    
                    $message='<div class="background_color_update_message ms-auto" style=" display:block;color:red;background-color:rgba(251, 232, 0, 0.15); border:solid 1px rgba(255, 0, 9, 0.36);padding: 10px ;margin: 10px ;">
                    '.$msg.' <a style="color:red;" href="mailto:joomlasupport@xecirify.com"><strong>joomlasupport@xecurify.com</strong></a>
                    </div>';
                    $upgrade_message='<div class="container-fluid container-main">'.$message;
                    $body=str_replace('<div class="container-fluid container-main">',$upgrade_message,$body);
                    $app->setBody($body);
                }
            }
            
            if($change2FAMethod && $enabled_tfa){
                if (stristr($body, "com-users-profile__edit profile-edit")) {

                        $label = "Reset TFA";
     
                        $linkPosition='
                        <div class="com-users-methods-list-method com-users-methods-list-method-name-totp mx-1 my-3 card ">
                            <div class="com-users-methods-list-method-header card-header  d-flex flex-wrap align-items-center gap-2">
                                <div class="com-users-methods-list-method-title flex-grow-1 d-flex flex-column">
                                    <h2 class="h4 p-0 m-0 d-flex gap-3 align-items-center">
                                        <span class="me-1 flex-grow-1">Second-factor Authentication</span>
                                    </h2>
                                </div>
                            </div>
                            <div class="com-users-methods-list-method-records-container card-body">
                                <div class="com-users-methods-list-method-info my-1 pb-1 small text-muted">
                                        Re-configure or change your TFA method              
                                </div>
                                <div class="com-users-methods-list-method-addnew-container border-top pt-2">
                                <form name="f" method="post" id="usertfa_resetform" action="#"> 
                                    <button type=submit name="user_tfa_reset" value="method.reset"  class="btn btn-dark" style="padding:0.4rem 1rem" id="moresetbtn">'.$label.'</button>
                                    <input type="hidden" name="tfauser_id" value="'.$user->id.'">
                                    <input type="hidden" name="task" value="tfa_reset" />        
                                </form>
                                </div>
                            </div>
                        
                        </div>';

                        $body = str_replace('<div class="com-users-profile__edit-submit control-group">', 
                        $linkPosition.'<div class="com-users-profile__edit-submit control-group">', $body);
                       
						$app->setBody($body);
                }
            }
            
            if (stristr($body, "com-users-profile__edit profile-edit") && $enabled_tfa) {
                if (stristr($body, "com-users-profile__multifactor")) {
                    $foobar=$foobar='<script>

                    document.addEventListener("DOMContentLoaded", function(){
                        jQuery(".com-users-methods-list").css("display", "none");
                        jQuery(".com-users-profile__multifactor").css("display", "none");
                    });
                    </script>';
                    $body = $app->getBody();
                    $body = str_replace('</body>', $foobar . '</body>', $body);
                    $app->setBody($body);
                }
            }
                $linkPosition ='<script src = "' . JURI::root() . 'administrator\components\com_miniorange_twofa\assets\js\remember_me\js\jquery-1.9.1.js"></script>
                <script src = "' . JURI::root() . 'administrator\components\com_miniorange_twofa\assets\js\remember_me\js\jquery.flash.js"></script>
                <p><input type="hidden" id="miniorange_rba_attributes" name="miniorange_rba_attributes" value=""/></p>

                <script type="application/javascript" src="' . JURI::root() . 'administrator\components\com_miniorange_twofa\assets\js\remember_me/js/ua-parser.js" ></script>
                <script type="application/javascript" src="' . JURI::root() . 'administrator\components\com_miniorange_twofa\assets\js\remember_me/js/client.js " ></script>
                <script type="application/javascript" src="' . JURI::root() . 'administrator\components\com_miniorange_twofa\assets\js\remember_me/js/device_attributes.js" ></script>
                <script type="application/javascript" src="' . JURI::root() . 'administrator\components\com_miniorange_twofa\assets\js\remember_me/js/swfobject.js" ></script>
                <script type="application/javascript" src="' . JURI::root() . 'administrator\components\com_miniorange_twofa\assets\js\remember_me/js/fontdetect.js" ></script>
                <script type="application/javascript" src="' . JURI::root() . 'administrator\components\com_miniorange_twofa\assets\js\remember_me/js/murmurhash3.js" ></script>
                <script type="application/javascript" src="' . JURI::root() . 'administrator\components\com_miniorange_twofa\assets\js\remember_me/js/miniorange-fp.js" ></script>
                ';
                $body = $app->getBody();
                $body = str_replace('Log in</button>', 'Log in</button>'.$linkPosition, $body);
                $app->setBody($body);
            
            $login_with_second_factor_only = isset($settings['login_with_second_factor_only']) ? $settings['login_with_second_factor_only'] : 0;
              
            if($login_with_second_factor_only){
                $app = JFactory::getApplication();
                $foobar = ' <script>
                document.addEventListener("DOMContentLoaded", function(){
                    jQuery("#modlgn-passwd").value = "laskf";
                    jQuery("#form-login-password").css("display", "none");
                    jQuery("#password").attr("disabled", "disabled").css("display", "none");              
                    jQuery("#password-lbl").css("display", "none");
                });
                </script>';
                $body = $app->getBody();
                $body = str_replace('</body>', $foobar . '</body>', $body);
                $app->setBody($body);
            }
        }
        
    }

    public function onAfterRoute()
    {
        $app = JFactory::getApplication();
        $name = $app->getName();
        $get = $app->input->get->getArray();
        $post = $app->input->post->getArray();
        

        if (array_key_exists('tfa_login', $get)) {
            $current_user = JFactory::getUser($get['tfa_login']);
            $row = commonUtilitiesTfa::getMoTfaUserDetails($current_user->id);
            $salt = random_bytes(random_int(10, 50));

            $salt = bin2hex($salt);
            commonUtilitiesTfa::updateOptionOfUser($current_user->id, 'transactionId', $salt);

            if (!array_key_exists('user', $get) || $get['user'] != $row['transactionId']) {
                return;
            }
            JPluginHelper::importPlugin('user');
            $options = array();
            $options['action'] = 'core.login.admin';
            $options['group'] = 'Public Backend';
            $options['autoregister'] = false;
            $options['entry_url'] = '/administrator/index.php';
            $response = new stdClass();
            $response->username = $current_user->username;
            $response->language = '';
            $response->email = $current_user->email;
            $response->password_clear = '';
            $response->fullname = '';
            $session = JFactory::getSession();
            $session->set('tfa_verified', 'yes');
            $result = $app->triggerEvent('onUserLogin', array((array)$response, $options));
            

            if (in_array(false, $result)) {
                $app->logout($current_user->id);
            }
            $app->redirect(JURI::base() . 'index.php');
        }
        
        
        if (isset($get['motfausers'])) {
            $session = JFactory::getSession();
            if (isset($get['admin']) && $get['admin'] == 1) {
                $session->clear('steps');
                $app->redirect(JURI::root() . "index.php?option=com_miniorange_twofa&view=miniorange_twofa&userId=" . $get['motfausers'] . '&admin=1');
            }
            $app->redirect(JURI::root() . "index.php?option=com_miniorange_twofa&view=miniorange_twofa&userId=" . $get['motfausers']);

        }
        
    }

    function onAfterInitialise()
    {  
    
        $path =  JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_miniorange_twofa' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'Mo_tfa_customer_setup.php';
        
        if(JFile::exists($path))
        {
            require_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_miniorange_twofa' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'Mo_tfa_customer_setup.php';
        }

        $app = JFactory::getApplication();
        $get = $app->input->get->getArray();
        $post = $app->input->post->getArray();
        $user = JFactory::getUser();

        if(isset($post['task']) && $post['task'] =='tfa_reset' && isset($post['user_tfa_reset']) && $post['user_tfa_reset']=='method.reset')
        {
            $user_id=$post['tfauser_id'];
            $session = JFactory::getSession();
            $session->set('steps', 'three');
            $moTfa=array('inline'=>array('whoStarted'=>JFactory::getUser($user_id),'status'=>'attempted'));
            $session->set('motfa',$moTfa);
            $session->set('mo_tfa_message','');
            $session->set('change2FAEnabled', 'TRUE');
            if ($app->isClient('site')) { 

                header("Location:" . JURI::root() . "index.php?option=com_miniorange_twofa&view=miniorange_twofa&motfausers=" . commonUtilitiesTfa::encrypt($user_id));
            
            } else {
                header("Location:" . JURI::root() . "index.php?option=com_miniorange_twofa&view=miniorange_twofa&motfausers=" . commonUtilitiesTfa::encrypt($user_id) . '&admin=1');
            }
            exit();
        }
        if (isset($post['mojsp_feedback'])) {

            require_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_miniorange_twofa' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'Mo_tfa_utility.php';

            $radio = $post['deactivate_plugin'];
            $data = $post['query_feedback'];
            $feedback_email = $post['feedback_email'];
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            // Fields to update.
            $fields = array(
                $db->quoteName('fid') . ' = ' . $db->quote(1)
            );

            // Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('id') . ' = 1'
            );

            $query->update($db->quoteName('#__miniorange_tfa_customer_details'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();


            $current_user = JFactory::getUser();

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select(array('*'));
            $query->from($db->quoteName('#__miniorange_tfa_customer_details'));
            $query->where($db->quoteName('id') . " = 1");
            $db->setQuery($query);
            $customerResult = $db->loadAssoc();

            $admin_email = $customerResult['email'];
            $admin_phone = $customerResult['admin_phone'];

            $data1 = $radio . ' : ' . $data . '  <br><br> Email :  ' . $feedback_email;

            $customer = new Mo_tfa_Customer();
            $customer->submit_feedback_form($admin_email, $admin_phone, $data1);

            require_once JPATH_SITE . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Installer' . DIRECTORY_SEPARATOR . 'Installer.php';

            foreach ($post['result'] as $fbkey) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('type');
                $query->from('#__extensions');
                $query->where($db->quoteName('extension_id') . " = " . $db->quote($fbkey));
                $db->setQuery($query);
                $result = $db->loadColumn();
                $identifier = $fbkey;

                $type = 0;
                foreach ($result as $results) {
                    $type = $results;
                }

                if ($type) {
                    $cid = 0;
                    $installer = new JInstaller();
                    $installer->uninstall($type, $identifier, $cid);
                }
            }
        }

        $download_backup_code = isset($get['download_backup_code']) ? $get['download_backup_code'] : 'No';
        $generate_backup_codes = isset($get['generate_backup_codes']) ? $get['generate_backup_codes'] : 'No';
        $is_user_deleted_from_joomla = isset($post['task']) ? $post['task'] : '';
        $current_uid = isset($post['cid']) ? $post['cid'] : '';

        /*
         *  Delete the user from server and from joomla database '#__miniorange_tfa_users' if user has been deleted from the Joomla '#__users' table
         */
        if($is_user_deleted_from_joomla == 'users.delete') 
        {
            if(array($current_uid))
            {
                $current_uid = $current_uid[0];
            }

            // get user email using current_user_id
            $email = commonUtilitiesTfa::get_user_details($current_uid[0]);
            $email = isset($email['email']) ? $email['email'] : '';

            if(!empty($email))
            {
                /*
                 * Delete user from joomla database and from server iff status of tfa method is active
                 */
                $check_active_tfa_method = commonUtilitiesTfa::check_active_tfa_method($email);
                $check_active_tfa_method = isset($check_active_tfa_method['status_of_motfa']) ? $check_active_tfa_method['status_of_motfa'] : '';
                if($check_active_tfa_method == 'active'){
                    // Delete the user from server if user has deleted from the Joomla
                    commonUtilitiesTfa::delete_user_from_server($email);

                    // delete user from joomla database - '#__miniorange_tfa_users'
                    commonUtilitiesTfa::delete_data_from_joomla_database($email);
                }
            }
        }


        if($download_backup_code == 'downloadbkpcode')
        {
            commonUtilitiesTfa::downloadTxtFile();
        }

        // Generate the backup codes.
        if($generate_backup_codes == 'generateBackupCodes')
        {
            $random_string = commonUtilitiesTfa::generateBackupCodes();
            $backup_codes = implode(',', $random_string);
            commonUtilitiesTfa::saveInFile($backup_codes);
            $app->enqueueMessage(JText::_('PLG_SYSTEM_MINIORANGETFAREDIRECT_BACKUP'), 'success');
            $app->redirect('index.php?option=com_miniorange_twofa&tab-panel=login_settings');
        }

       
    }
}

<?php
/**
 * @package     Joomla.miniorangetfa
 * @subpackage  Application
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class commonUtilitiesTfa
{

    static function mo_logs($log_msg)
    {
        $filePath = $_SERVER['DOCUMENT_ROOT']."/log/log.log";
        $sizeInBytes = filesize($filePath);

        // Convert byte to kb upto 2 decimal
        $sizeInKb = number_format($sizeInBytes / 1024, 2);


        if($sizeInKb >= 256)
        {
            //Clean the file if the size is greater than or equal to 256kb
            file_put_contents($filePath, "");
        }

        $log_filename = $_SERVER['DOCUMENT_ROOT']."/log";
        if (!file_exists($log_filename))
        {
            // create directory/folder uploads.
            mkdir($log_filename, 0777, true);
        }
        // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
        file_put_contents($filePath, var_export($log_msg, true). "\n", FILE_APPEND);
    }

    public static function __getDBValuesUsingColumns($col1_Name, $tableName,$condition=TRUE,$method="loadResult")
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($col1_Name);
        $query->from($db->quoteName($tableName));
        if ($condition !== TRUE){
            foreach ($condition as $key=>$value)
            {
                $query->where($db->quoteName($key) . " = " . $db->quote($value));
            }
        }
        $db->setQuery($query);
        switch ($method) {
            case 'loadColumn':
                return $db->loadColumn();
              break;
            case 'loadAssocList':
                return $db->loadAssocList();
                break;
            case 'loadObjectList':
                return $db->loadObjectList();
                break;
            case 'loadAssoc':
                return $db->loadAssoc();
                break;
            case 'loadObject':
                return $db->loadObject();
                break;
            case 'loadRow':
                return $db->loadRow();
                break;
            case 'loadRowList':
                return $db->loadRowList();
                break;
            default:
                return $db->loadResult();
            }
    }

    public static function __getDBProfileValues($uid,$profile_key)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $conditions = array(
            $db->quoteName('profile_key') . ' = ' . $db->quote($profile_key),
            $db->quoteName('user_id') . ' = ' . $db->quote($uid)
        );
        

        $query->select('profile_value')->from($db->quoteName('#__user_profiles'))->where($conditions);
        $db->setQuery($query);

        $results = $db->loadResult();
        return $results;
    }

    public static function _getMaskedEmail($email)
    {
        $parts = explode('@', $email);
        $username = $parts[0];
        $domain = $parts[1];
        
        $maskedUsername = substr($username, 0, 3) . '*****';
        $maskedDomain = substr($domain, strpos($domain, '.'));
        
        $email = $maskedUsername . $maskedDomain;
        return $email;
    }

    public static function checkIsLicenseExpired()
    {
        $content = self::getCustomerDetails();
        $licenseExpiry = isset($content['licenseExpiry']) ? $content['licenseExpiry'] : '0000-00-00 00:00:00';
        $days = intval((strtotime($licenseExpiry) - time()) / (60 * 60 * 24));
        
        $isLicenseExpired = array();
        $isLicenseExpired['LicenseExpiry'] = $days > 0 && $days < 31 ? TRUE : FALSE;
        $isLicenseExpired['LicenseExpired'] = $days > -365 && $days < 0 ? TRUE : FALSE;
        
        return $isLicenseExpired;
    }

    public static function _genericGetDBValues($table)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(array('*'));
        $query->from($db->quoteName($table));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();
        return $result;
    }

    public static function licenseExpiryDay()
    {
        $content = self::getCustomerDetails();
        $days = intval((strtotime($content['licenseExpiry']) - time()) / (60 * 60 * 24));
        return $days;
    }

    public static function licensevalidity($expire)
    {
        require_once JPATH_SITE . '/administrator/components/com_miniorange_twofa/helpers/Mo_tfa_customer_setup.php';

        $customer = new Mo_tfa_Customer();
        $licenseContent = json_decode($customer->fetchLicense(), true);
        $license_exp = $licenseContent['licenseExpiry'];
    

        if ($license_exp > $expire) {
            $db_table = '#__miniorange_tfa_customer_details';
            $db_coloums = array('licenseExpiry' => $license_exp);
            self::__genDBUpdate($db_table, $db_coloums);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function __genDBUpdate($db_table, $db_coloums)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        foreach ($db_coloums as $key => $value) {
            $database_values[] = $db->quoteName($key) . ' = ' . $db->quote($value);
        }

        $query->update($db->quoteName($db_table))->set($database_values)->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $db->execute();
    }

    public static function _update_lid($val)
    {

        $db = jFactory::getDbo();
        $query = $db->getQuery(true);

        $fields = array(
            $db->quoteName($val) . ' = ' . $db->quote(1)
        );

        $conditions = array(
            $db->quoteName('id') . ' = '. $db->quote(1)
        );

        $query->update($db->quoteName('#__miniorange_tfa_customer_details'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->execute();
    }


    public static function _cuc()
    {
        $content = self::_genericGetDBValues('#__miniorange_tfa_customer_details');

        $licenseExp = strtotime($content['licenseExpiry']);
        $licenseExp = $licenseExp === FALSE || $licenseExp <= -62169987208 ? "-" : date("F j, Y, g:i a", $licenseExp);

        //difference between expiry date and current time in days

        $day_diff = self::licenseExpiryDay();
        
        /*
         * Deactivate the plugin and remove the license key after 5 days of grace period
         */
        
        $config = JFactory::getConfig();
        $site_name = $config->get('sitename');
        $plan_name = "Joomla 2FA";
        $subject = "License Expire of Joomla 2FA Plugin |" . $site_name ;

        $message_before_lexp = "Hello,<br /><br />Your license for <b>".$plan_name."</b> plan is going to expire on ".$licenseExp." for your website: <b>". $site_name ."</b>.<br /> <br /> Please renew your license as soon as possible to receive plugin updates providing security patches, bug fixes, new features, or even compatibility adjustments. If you want to renew your license please reach out to us at <b>joomlasupport@xecurify.com</b><br /><br />Thanks,<br />miniOrange Team";
        $message_after_lexp = "Hello,<br /><br />Your license for <b>".$plan_name."</b> plan has expired on ".$licenseExp." for your website: <b>". $site_name ."</b>.<br /> <br /> Please renew your license as soon as possible to receive plugin updates providing security patches, bug fixes, new features, or even compatibility adjustments. If you want to renew your license please reach out to us at <b>joomlasupport@xecurify.com</b><br /><br />Thanks,<br />miniOrange Team";

        if ($day_diff <=15  && $day_diff  > 5 &&  !$content['miniorange_fifteen_days_before_lexp'])            //15 days remaining    1296000: 15 days in seconds
        {
            if (!self::licensevalidity($licenseExp)) {
                self::_update_lid('miniorange_fifteen_days_before_lexp');
                json_decode(self::send_email_alert($subject, $message_before_lexp), true);
            
            }
        } else if ($day_diff <=5  && $day_diff  > 0 && !$content['miniorange_five_days_before_lexp'])            //5 days remaining    432000: 5 days in seconds
        {
            if (!self::licensevalidity($licenseExp)) {
                self::_update_lid('miniorange_five_days_before_lexp');
                json_decode(self::send_email_alert($subject, $message_before_lexp), true);
            }
        } else if ($day_diff <=0  && $day_diff  > -5 && !$content['miniorange_after_lexp'])            //on or after license expiry
        { 
            if (!self::licensevalidity($licenseExp)) {
                self::_update_lid('miniorange_after_lexp');
                json_decode(self::send_email_alert($subject, $message_after_lexp), true);
            }
        } else if ( $day_diff  == -5  && !$content['miniorange_after_five_days_lexp'])          // 5 days after expiry
        {
            if (!self::licensevalidity($licenseExp)) {
                self::_update_lid('miniorange_after_five_days_lexp');
                json_decode(self::send_email_alert($subject, $message_after_lexp), true);
            }
        }
     
    }

    public static function send_email_alert($subject, $message_content)
    {
        $hostname = self::getHostname();
        $url = $hostname . '/moas/api/notify/send';
        $ch = curl_init($url);
        $customer_details = self::_genericGetDBValues('#__miniorange_tfa_customer_details');

        $customerKey = $customer_details['customer_key'];
        $apiKey = $customer_details['api_key'];

        $currentTimeInMillis = round(microtime(true) * 1000);
        $stringToHash = $customerKey . number_format($currentTimeInMillis, 0, '', '') . $apiKey;
        $hashValue = hash("sha512", $stringToHash);
        $customerKeyHeader = "Customer-Key: " . $customerKey;
        $timestampHeader = "Timestamp: " . number_format($currentTimeInMillis, 0, '', '');
        $authorizationHeader = "Authorization: " . $hashValue;

        $toEmail = $customer_details['email'];
        $fromEmail = 'joomlasupport@xecurify.com';

        $fields = array
        (
            'customerKey'   => $customerKey,
            'sendEmail'     => true,
            'email'         => array
            (
                'customerKey'   => $customerKey,
                'fromEmail'     => $fromEmail,
                'fromName'      => 'miniOrange',
                'toEmail'       => $toEmail,
                'toName'        => $toEmail,
                'bccEmail'      => $fromEmail,
                'subject'       => $subject,
                'content'       => $message_content
            ),
        );

        $field_string = json_encode($fields);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls

        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader, $timestampHeader, $authorizationHeader));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
        $content = json_decode(curl_exec($ch));

        if($content->status == 'SUCCESS'){
            self::_update_lid('miniorange_lexp_notification_sent');
        }

        if (curl_errno($ch)) {
            return json_encode(array("status" => 'ERROR', 'statusMessage' => curl_error($ch)));
        }
        curl_close($ch);
        
        return json_encode(array("status" => 'SUCCESS', 'statusMessage' => 'SUCCESS'));

    }

    public static function update_users_on_server($username,$new_user_email)
    {
        $hostname = self::getHostname();
        $url = $hostname . '/moas/api/admin/users/update';
        
        $customer_details = self::_genericGetDBValues('#__miniorange_tfa_customer_details');

        $customerKey = $customer_details['customer_key'];
        $apiKey = $customer_details['api_key'];

        $fields = array(
            'customerKey' => $customerKey,
            'username' => $username,
            'email' => $new_user_email,
            'transactionName' => 'Joomla 2FA Plugin'
        );
        $json_fields = json_encode($fields);

        $api = new MoTfa_api();
        $header= $api->get_http_header_array();
        return $api->make_curl_call($url, $json_fields, $header);

    }

    public static function get_user_on_server($username)
    {
        $hostname = self::getHostname();
        $url = $hostname . '/moas/api/admin/users/get';
        
        $customer_details = self::_genericGetDBValues('#__miniorange_tfa_customer_details');

        $customerKey = $customer_details['customer_key'];
        $apiKey = $customer_details['api_key'];

        $fields = array(
            'customerKey' => $customerKey,
            'username' => $username,
            'email' => $username,
            'transactionName' => 'Joomla 2FA Plugin'
        );
        $json_fields = json_encode($fields);

        $api = new MoTfa_api();
        $header= $api->get_http_header_array();
        return $api->make_curl_call($url, $json_fields, $header);

    }
    public static function check_active_tfa_method($email)
    {
        $db = JFactory::getDbo();
        $query = $db
            ->getQuery(true)
            ->select('status_of_motfa')
            ->from($db->quoteName('#__miniorange_tfa_users'))
            ->where($db->quoteName('email') . " = " . $db->quote($email));
        $db->setQuery($query);
        $row=$db->loadAssoc();
        return $row;
    }

    public static function get_user_details($id){
        $db = JFactory::getDbo();
        $query = $db
            ->getQuery(true)
            ->select('email')
            ->from($db->quoteName('#__users'))
            ->where($db->quoteName('id') . " = " . $db->quote($id));
        $db->setQuery($query);
        $row=$db->loadAssoc();
        return $row;
    }

    public static function delete_user_from_server($email)
    {
        $customerKeys = commonUtilitiesTfa::getCustomerKeys();
        $customerKey  = $customerKeys['customer_key'];
        
        $fields       = array
        (
            'customerKey' => $customerKey,
            'username'    => $email,
        );

        $api_urls = commonUtilitiesTfa::getApiUrls();
        $mo2fApi= new MoTfa_api();
        $http_header_array = $mo2fApi->get_http_header_array();
        return $mo2fApi->make_curl_call($api_urls['deleteUser'], $fields, $http_header_array);
    }

    public static function delete_user_from_joomla_database($email){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $conditions = array(
            $db->quoteName('email') . ' = ' . $db->quote($email)
        );
        $query->delete($db->quoteName('#__miniorange_tfa_users'));
        $query->where($conditions);
        $db->setQuery($query);
        return $db->execute();
    }
    public static function delete_rba_settings_from_database($user_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $conditions = array(
            $db->quoteName('user_id') . ' = ' . $db->quote($user_id)
        );
        $query->delete($db->quoteName('#__miniorange_rba_device'));
        $query->where($conditions);
        $db->setQuery($query);
        return $db->execute();
    }


    public static function is_curl_installed() 
    {
        if  (in_array  ('curl', get_loaded_extensions())) {
            return 1;
        } else
            return 0;
    }

    public static function getJoomlaCmsVersion()
    {
        $jVersion   = new JVersion;
        return($jVersion->getShortVersion());
    }

    public static function GetPluginVersion()
    {
        $db = JFactory::getDbo();
        $dbQuery = $db->getQuery(true)
            ->select('manifest_cache')
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('element') . " = " . $db->quote('com_miniorange_twofa'));
        $db->setQuery($dbQuery);
        $manifest = json_decode($db->loadResult());
        return ($manifest->version);
    }

    public static function checkIsCurlInstalled()
    {
        if (!self::is_curl_installed()) { ?>
            <div id="help_curl_warning_title">
                <p><a target="_blank" style="cursor: pointer;"><font color="#FF0000"><?php JText::_('LIB_MINIORANGETFA_MSG_CURL');?>
                             <span style="color:blue"><?php JText::_('LIB_MINIORANGETFA_MSG_CLICK');?></span> <?php JText::_('LIB_MINIORANGETFA_MSG_CURL1');?>
                            </font></a></p>
            </div>
            <div hidden="" id="help_curl_warning_desc" class="mo_help_desc">
            <?php JText::_('LIB_MINIORANGETFA_MSG_BACKUP_DESC3');?>
            <?php JText::_('LIB_MINIORANGETFA_MSG_QUERY');?> <a href="mailto:joomlasupport@xecurify.com"><?php JText::_('LIB_MINIORANGETFA_MSG_CONTACT');?></a>.
            </div>
            <style>
                .mo_help_desc {
                    font-size:13px;
                    border-left:solid 2px rgba(128, 128, 128, 0.65);
                    margin-top:10px;
                    padding-left:10px;
                }
            </style>
            <?php
        }
    }
    public static function getCurrentUserID($current_user)
    { 
        
        $session = JFactory::getSession();
        if(empty($current_user) || $current_user == '') {

            return $current_user_id = $session->get('juserId');
        } else{
            return $current_user_id = $current_user->id;
        }
        
    }

    public static function isIdPInstalled()
    {
        $arr = array('miniorangejoomlaidp', 'joomlaidplogin');

        foreach ($arr as $key)
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('enabled');
            $query->from('#__extensions');
            $query->where($db->quoteName('element') . " = " . $db->quote($key));
            $query->where($db->quoteName('type') . " = " . $db->quote('plugin'));
            $db->setQuery($query);
            return($db->loadAssoc());
        }
    }

    public static function saveInFile($backup_codes)
    {
        $content_backup_code ='Two Factor Backup Codes:
        These are the codes that can be used in case you lose your phone or cannot access your email. Please reconfigure your authentication method after login.
        Please use this carefully as each code can only be used once. Please do not share these codes with anyone..'.PHP_EOL;
        $content_backup_code = $content_backup_code.PHP_EOL;
        $content_backup_code = $content_backup_code.$backup_codes.PHP_EOL;
        $filename = self::getFilePath('moBackupCode.txt');
        $file     = fopen($filename, 'w');
        fwrite($file, $content_backup_code);
        fclose($file);
    }
 

    public static function readBackupCodesFromFile()
    {
        $byteOffset = 303;
        $readLength = 256;

        $filename = self::getFilePath('moBackupCode.txt');
        if(file_exists($filename)){
            $fp = fopen($filename, "r");        
            fseek($fp, $byteOffset);
            $bytes = fread($fp, $readLength);
            return $bytes;
        }
        return '';
    }

    public static function getFilePath($bkpCodeFile){
        $fpath = JPATH_ADMINISTRATOR;
        $filep = substr($fpath, 0, strrpos($fpath, 'administrator'));
        $filepath = $filep.'libraries'.DIRECTORY_SEPARATOR.'miniorangetfa'.DIRECTORY_SEPARATOR.'utility'.DIRECTORY_SEPARATOR.$bkpCodeFile;       
        return 'Backup_Codes';
    }

    public static function downloadTxtFile()
    {
        $file_path = self::getFilePath('moBackupCode.txt');

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header("Expires: 0");
        header('Content-Disposition: attachment; filename='.basename($file_path));
        header('Content-Length: ' . (filesize($file_path)));
        header('Pragma: public');

        flush();
        ob_clean();
        readfile($file_path);
        unlink($file_path);
        die();
    }

    public static function generateBackupCodes()
    {
        $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $stringLength = strlen($string);
        $randomStringArr = array();

        for($j = 1; $j <= 10; $j++){
            $randomString = '';
            for ($i = 0; $i < 10; $i++) {
                $randomString .= $string[rand(0, $stringLength - 1)];
            }
            array_push($randomStringArr, $randomString);
        }

        return $randomStringArr;
    }

    public static function getMoTfaSettings()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__miniorange_tfa_settings'));
        $db->setQuery($query);
        return $db->loadAssoc();
    }


    public static function get_user_from_joomla($username)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
                    ->select('id')
                    ->from('#__users')
                    ->where('username=' . $db->quote($username));

        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;

    }

	public static function  is_extension_installed($extension_name){
		return in_array($extension_name, get_loaded_extensions());
	}

	public static function loadGroups(){
		$db = JFactory::getDbo();
        $db->setQuery($db->getQuery(true)
            ->select('*')
            ->from("#__usergroups")
        );
        return  $db->loadAssocList();
	}

	public static function encrypt($str) {
		if(!self::is_extension_installed('openssl')) {
			return self::base64url_encode($str);
		}
		$key= self::getEncryptKey();
		$string= openssl_encrypt($str, 'aes-128-ecb', $key, OPENSSL_RAW_DATA);
		return self::base64url_encode($string);
	}

	public static function isValidUid($id){
		$db = JFactory::getDbo();
		$query = $db
    		->getQuery(true)
    		->select('*')
    		->from($db->quoteName('#__users'))
    		->where($db->quoteName('id') . " = " . $db->quote($id));
		$db->setQuery($query);
		$row=$db->loadAssoc();

		if(is_null($row)){
			return FALSE;
		}
		return TRUE;
	}

	static function base64url_encode($data) {
  		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	static function base64url_decode($data) {
  		return base64_decode(strtr( $data, '-_', '+/') . str_repeat('=', 3 - ( 3 + strlen( $data )) % 4 ));
	}

	public static function decrypt($value)
	{
		$value=self::base64url_decode($value);
		if(!self::is_extension_installed('openssl')) {
			return self::base64url_decode($value);
		}
		$key= self::getEncryptKey();
		$string = rtrim( openssl_decrypt($value, 'aes-128-ecb', $key, OPENSSL_RAW_DATA), "\0");
		return trim($string,"\0..\32");
	}

	public static function getEncryptKey(){
		$details = self::getCustomerDetails();
		$apiKey = empty($details['api_key'])?'j2faplufjebyu':$details['api_key'];
		$customerId = empty($details['customer_key'])?'j2faplufjebyu':$details['customer_key'];
		return $apiKey.$customerId;
	}

	public static function isCustomerRegistered(){
		$details = self::getCustomerDetails();
       
		return !(!isset($details['email']) || !isset($details['customer_key']) || !isset($details['api_key']) || !isset($details['customer_token']) || empty($details['email']) || empty($details['customer_key']) || empty($details['api_key']) || empty($details['customer_token']));

	}

	public static function isFirstUser($id){
		$details = self::getCustomerDetails();

		return $details['jid']==$id;
	} 

	public static function getCustomerDetails(){
		$db = JFactory::getDbo();
		$query = $db
    		->getQuery(true)
    		->select('*')
    		->from($db->quoteName('#__miniorange_tfa_customer_details'))
    		->where($db->quoteName('id') . " = " . $db->quote(1));
		$db->setQuery($query);
		$row=$db->loadAssoc();
		return $row;
	}

	public static function updateLicenseDetails($response,$email=NULL){
  
        if(!isset($response->licensePlan))
            return;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
        if($email!=NULL && $email == "demo2fauser1@xecurify.com")
        {
            $fields = array(
                $db->quoteName('license_type') . ' = '.$db->quote(isset($response->licenseType) ? $response->licenseType : ''),
                $db->quoteName('license_plan').' ='.$db->quote(isset($response->licensePlan) ? $response->licensePlan : ''),
                $db->quoteName('no_of_users').' ='.$db->quote(isset($response->noOfUsers) ? 5 : ''),
                $db->quoteName('smsRemaining') . ' = '.$db->quote(isset($response->smsRemaining) ? $response->smsRemaining : 0),
                $db->quoteName('emailRemaining') . ' = '.$db->quote(isset($response->emailRemaining) ? $response->emailRemaining : 0),
                $db->quoteName('supportExpiry') . ' = '.$db->quote(isset($response->supportExpiry) ? date('Y-M-d H:i:s', strtotime($response->supportExpiry)) : ''),
                $db->quoteName('licenseExpiry') . ' = '.$db->quote(isset($response->licenseExpiry) ? date('Y-M-d H:i:s', strtotime($response->licenseExpiry)) : ''),
            );
        }
        else
        {
            $fields = array(
                $db->quoteName('license_type') . ' = '.$db->quote(isset($response->licenseType) ? $response->licenseType : ''),
                $db->quoteName('license_plan').' ='.$db->quote(isset($response->licensePlan) ? $response->licensePlan : ''),
                $db->quoteName('no_of_users').' ='.$db->quote(isset($response->noOfUsers) ? $response->noOfUsers : ''),
                $db->quoteName('smsRemaining') . ' = '.$db->quote(isset($response->smsRemaining) ? $response->smsRemaining : 0),
                $db->quoteName('emailRemaining') . ' = '.$db->quote(isset($response->emailRemaining) ? $response->emailRemaining : 0),
                $db->quoteName('supportExpiry') . ' = '.$db->quote(isset($response->supportExpiry) ? date('Y-M-d H:i:s', strtotime($response->supportExpiry)) : '<span style="color:#FF0000;">Upgrade to licensed version</span>'),
                $db->quoteName('licenseExpiry') . ' = '.$db->quote(isset($response->licenseExpiry) ? date('Y-M-d H:i:s', strtotime($response->licenseExpiry)) : '<span style="color:#FF0000;">Upgrade to licensed version</span>'),
            );
        }

		$conditions = array(
			$db->quoteName('id') . ' = 1'
		);

		$query->update($db->quoteName('#__miniorange_tfa_customer_details'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$db->execute();
	}

	public static function customerTfaDetails(){
		$details = self::getCustomerDetails();
		return self::getMoTfaUserDetails($details['jid']);
	}
 
	public static function getMoTfaUserDetails($id){
		$db = JFactory::getDbo();
		$query = $db
    		->getQuery(true)
    		->select('*')
    		->from($db->quoteName('#__miniorange_tfa_users'))
    		->where($db->quoteName('id') . " = " . $db->quote($id));
		$db->setQuery($query);
		$row=$db->loadAssoc();
		return $row;
	}
    public static function checkMoTfaUserDetails(){
		$db = JFactory::getDbo();
		$query = $db
    		->getQuery(true)
    		->select('*')
    		->from($db->quoteName('#__miniorange_tfa_users'));
		$db->setQuery($query);
		$row=$db->loadAssoc();
		return $row;
	}

    public static function checkMoTfaUsers(){
        $db = JFactory::getDbo();
		$query = $db
    		->getQuery(true)
    		->select($db->quoteName('id'))
    		->from($db->quoteName('#__miniorange_tfa_users'));
		$db->setQuery($query);
        $db->execute();
        $num_rows = $db->getNumRows();
		return $num_rows;
    }


    public static function insertMoTfaUser($jUsername,$id,$username,$email='',$phone=''){

        $c_user = JFactory::getUser($id);
        $usergroup_id = $c_user->groups;
        $user_id = 1;
        foreach($usergroup_id as $key=>$value)
        {
            $user_id=$value;
        }
        
        $user_role = self::__getDBValuesUsingColumns('title', '#__usergroups',array('id' => $user_id,),$method="loadResult");
     
        $groups= self::loadGroups();
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Insert columns.
		$columns = array('id','jUsername','username', 'email', 'phone','user_group');

		// Insert values.
		$values = array($id, $db->quote($jUsername),$db->quote($username), $db->quote($email), $db->quote($phone),$db->quote($user_role));

		// Prepare the insert query.
		$query
    		->insert($db->quoteName('#__miniorange_tfa_users'))
    		->columns($db->quoteName($columns))
    		->values(implode(',', $values));

		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		$db->execute();
	}

	public static function updateMoTfaUser($id,$username,$email,$phone=''){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		 // Fields to update.
		$fields = array(
			$db->quoteName('username') . ' = '.$db->quote($username),
			$db->quoteName('email') . ' = '.$db->quote($email),
			$db->quoteName('phone') . ' = '.$db->quote($phone),
		);
		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('id') . ' ='.$id
		);

		$query->update($db->quoteName('#__miniorange_tfa_users'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();
	}

	public static function getCustomerKeys($isMiniorange=false){
		$keys=array();
		if($isMiniorange){
			$keys['customer_key']= "16555";
    		$keys['apiKey']      = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
		}
		else{
			$details=self::getCustomerDetails();
			$keys['customer_key']= $details['customer_key'];
    		$keys['apiKey']      = $details['api_key'];
		}
		return $keys;
	}

	static function saveCustomerDetailsAfterLogin($email,$password,$phone,$id, $apiKey, $token,$appSecret,$jid) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		 // Fields to update.
		$fields = array(
			$db->quoteName('email') . ' = '.$db->quote($email),
			$db->quoteName('password').' ='.$db->quote(base64_encode($password)),
			$db->quoteName('admin_phone').' ='.$db->quote($phone),
			$db->quoteName('customer_key') . ' = '.$db->quote($id),
			$db->quoteName('api_key') . ' = '.$db->quote($apiKey),
			$db->quoteName('customer_token') . ' = '.$db->quote($token),
			$db->quoteName('app_secret') . ' = '.$db->quote($appSecret),
			$db->quoteName('login_status') . ' = '.$db->quote(1),
			$db->quoteName('new_registration') .' = '.$db->quote(0),
			$db->quoteName('jid') .' = '.$db->quote($jid),
		);
		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('id') . ' = 1'
		);
		$query->update($db->quoteName('#__miniorange_tfa_customer_details'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();
	}



	static function getTransactionName(){
		return 'Joomla 2FA Plugin';
	}

    public static function insertOptionOfUser($jUsername,$id,$ActiveMethod,$StatusMotfa, $username, $email){

        $c_user = JFactory::getUser($id);
        $usergroup_id = $c_user->groups;
        $user_id = 1;
        foreach($usergroup_id as $key=>$value)
        {
            $user_id=$value;
        }
        
        $user_role = self::__getDBValuesUsingColumns('title', '#__usergroups',array('id' => $user_id,),$method="loadResult");

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $fields = array(
            $db->quoteName('id')                . ' = ' . $db->quote($id),
            $db->quoteName('active_method')     . ' = ' . $db->quote($ActiveMethod),
            $db->quoteName('status_of_motfa')   . ' = ' . $db->quote($StatusMotfa),
            $db->quoteName('username')          . ' = ' . $db->quote($username),
            $db->quoteName('jUsername')          . ' = ' . $db->quote($jUsername),
            $db->quoteName('email')             . ' = ' . $db->quote($email),
            $db->quoteName('user_group'). ' = ' . $db->quote($user_role),
        );
        $query->insert($db->quoteName('#__miniorange_tfa_users'))->set($fields);

        $db->setQuery($query);
        $db->execute();
    }

	public static function updateOptionOfUser($id,$columnName,$value){
		$db = JFactory::getDbo(); 
		$query = $db->getQuery(true);
		 // Fields to update.
		$fields = array(
			$db->quoteName($columnName) . ' = '.$db->quote($value),
		);

		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('id') . ' ='.$id
		);
		$query->update($db->quoteName('#__miniorange_tfa_users'))->set($fields)->where($conditions);
		$db->setQuery($query);

		$result = $db->execute();
	}

	static function getHostName(){
		$brandingName=self::getTfaSettings()['branding_name'];
		$brandingName=empty(trim($brandingName))?'login':$brandingName;
		return 'https://'.$brandingName.'.xecurify.com';
    }

    static function getApiUrls()
    {
	    $hostName = self::getHostName();
		return array(
			'challange'         =>  $hostName.'/moas/api/auth/challenge',
			'update'            =>  $hostName.'/moas/api/admin/users/update',
			'validate'          =>  $hostName.'/moas/api/auth/validate',
			'googleAuthService' =>  $hostName.'/moas/api/auth/google-auth-secret',
			'googlevalidate'    =>  $hostName.'/moas/api/auth/validate-google-auth-secret',
			'createUser'        =>  $hostName.'/moas/api/admin/users/create',
			'kbaRegister'       =>  $hostName.'/moas/api/auth/register',
			'getUserInfo'       =>  $hostName.'/moas/api/admin/users/get',
            'feedback'          =>  $hostName.'/moas/api/notify/send',
            'deleteUser'        =>  $hostName.'/moas/api/admin/users/delete'
	    );
	}

	public static function addToConfiguredMethod($id,$method)
    {
		$row=self::getMoTfaUserDetails($id);
		$methods=$row['configured_methods'];
		if(is_null($methods) || empty($methods)){
			$methods.=$method;
		}
		else{

			if(array_search($method, explode(';',$methods))===FALSE)
			$methods=$methods.';'.$method;
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		 // Fields to update.
		$fields = array(
			$db->quoteName('configured_methods') . ' = '.$db->quote($methods),

		);
		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('id') . ' ='.$id
		);
		$query->update($db->quoteName('#__miniorange_tfa_users'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$db->execute();
	}

	public static function getKbaQuestions(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('tfa_kba_set1', 'tfa_kba_set2')));
		$query->from($db->quoteName('#__miniorange_tfa_settings'));
		$db->setQuery($query);
		$results = $db->loadRow();

		$options_one = explode('?',$results[0]);
		$options_two = explode('?',$results[1]);

        $arr = array();
        $option_string_1 = '';
        foreach ($options_one as $key => $value) {
            if(!empty($options_one[$key]))
            {
                $option_string_1 = $option_string_1.'<option name=question1 value="'.$value.'" >'.$value.'</option>';
            }
        }
        $option_string_2='';
        foreach ($options_two as $key => $value) {
            if(!empty($options_two[$key])){
                $option_string_2 = $option_string_2.'<option name=question1 value="'.$value.'" >'.$value.'</option>';
            }
        }

        $arr['0'] = $option_string_1;
        $arr['1'] = $option_string_2;
        return $arr;
	}

	public static function getTfaSettings(){
		$db = JFactory::getDbo();
		$query = $db
    		->getQuery(true)
    		->select('*')
    		->from($db->quoteName('#__miniorange_tfa_settings'))
    		->where($db->quoteName('id') . " = " . $db->quote(1));

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$row=$db->loadAssoc();
		return $row;
	}

	public static function  getActive2FAMethods(){
	    $tfaSetting = self::getTfaSettings();
	    $tfaSetting = json_decode($tfaSetting['activeMethods'],true);
        $configuration = self::getAllTfaMethods();

        if(in_array('ALL', $tfaSetting)){
            $tfaSetting = array_keys($configuration);
        }
        
        foreach ($configuration as $key => $value){
            if(!in_array($key, $tfaSetting))
                $configuration[$key]['active'] = false;
            else
                $configuration[$key]['active'] = true;
        }

        return $configuration;
    }

    public static function getAllTfaMethods(){
        return array(
            'OOS'=>array('name'=>'OTP over SMS'),
            'OOE'=>array('name'=>'OTP over Email'),
            'OOSE'=>array('name'=>'OTP over SMS and Email'),
            'OOC'=>array('name'=>'OTP over Phone Call'),
            'YK'=>array('name'=>'Yubikey Hardware Token'),
            'google'=>array('name'=>'Google Authenticator'),
            'MA'=>array('name'=>'Microsoft Authenticator'),
            'AA'=>array('name'=>'Authy Authenticator'),
			'LPA'=>array('name'=>'LastPass Authenticator'),
			'DUO'=>array('name'=>'Duo Authenticator'),
			'DUON'=>array('name'=>'Duo Push Notification')
        );
    }

    public static function validateIpsInput($IPs){
	    // explode with ;
        if(!array($IPs)){
            $IPs = trim($IPs);
        }
        $invalidIps = array();
        $validIps   = array();
        if(!empty($IPs)){
            $ipArray = explode(";", $IPs);
            foreach ($ipArray as $key => $value){
                // check if we have a range here
                $value = str_replace(" ","", $value);
                if(empty($value))
                    continue;
                $ipOrRangeArr = explode('-', $value);
                // validate ip
                $invalid  = count($ipOrRangeArr) > 2 ||
                    (count($ipOrRangeArr) == 2 && (ip2long($ipOrRangeArr[0]) === FALSE || ip2long($ipOrRangeArr[1]) === FALSE))
                    || (count($ipOrRangeArr) == 1 && ip2long($ipOrRangeArr[0]) === FALSE);

                $invalid ? array_push($invalidIps, $value) : array_push($validIps, $value);
            }
        }

        return array($validIps,$invalidIps);
    }

    static function get_client_ip() {
        $ipaddress = 'UNKNOWN';
	    $environments = array('HTTP_CLIENT_IP','REMOTE_ADDR','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_FORWARDED_FOR','HTTP_FORWARDED');
	    foreach ($environments as $key=>$value)
        {
            if(getenv($value))
            {
                $ipaddress = getenv($value);
                break;
            }
        }
	    return $ipaddress;
    }

    

    static function doWeHaveAwhiteIp($current_IP_address,$settings=false){
	    $settings = $settings== false ? self::getTfaSettings():$settings;
        $mo_ip_found = FALSE;
        if($settings['enableIpWhiteList']==0)
            return false;
        $whitelisted_IP_array=json_decode($settings['whiteListedIps'],true);
        foreach( $whitelisted_IP_array as $key => $value ) {
            if( stristr( $value, '-' ) )
            {
                /** Search in range of IP address **/
                list($lower, $upper) = explode('-', $value, 2);
                $lower_range = ip2long( $lower );
                $upper_range = ip2long( $upper );
                $current_IP  = ip2long( $current_IP_address );
                if( $lower_range !== FALSE && $upper_range !== FALSE && $current_IP !== FALSE && ( ( $current_IP >= $lower_range ) && ( $current_IP <= $upper_range ) ) ){
                    $mo_ip_found = TRUE;
                    break;
                }
            }
            else 
            {
                /** Compare with single IP address **/
                if( $current_IP_address == $value )
                {
                    $mo_ip_found = TRUE;
                    break;
                }
            }
        }

        return $mo_ip_found;
    }

    static function isCurrentIPBlackListed($current_IP_address, $settings = false)
    {
        $settings = $settings == false ? self::getTfaSettings():$settings;
        $mo_ip_found = FALSE;
        if($settings['enableIpBlackList']==0)
            return false;
        $blackListed_IP_array = json_decode($settings['blackListedIPs'],true);
        foreach( $blackListed_IP_array as $key => $value ) {
            if( stristr( $value, '-' ) ){
                /** Search in range of IP address **/
                list($lower, $upper) = explode('-', $value, 2);
                $lower_range = ip2long( $lower );
                $upper_range = ip2long( $upper );
                $current_IP  = ip2long( $current_IP_address );
                if( $lower_range !== FALSE && $upper_range !== FALSE && $current_IP !== FALSE && ( ( $current_IP >= $lower_range ) && ( $current_IP <= $upper_range ) ) ){
                    $mo_ip_found = TRUE;
                    break;
                }
            }else {
                /** Compare with single IP address **/
                if( $current_IP_address == $value ){
                    $mo_ip_found = TRUE;
                    break;
                }
            }
        }

        return $mo_ip_found;
    }

    public static function plugin_efficiency_check($email)
	{
        $c_time =date("Y-m-d",time());
        $base_url = JURI::root();
        $url =  'https://login.xecurify.com/moas/api/notify/send';
        $ch = curl_init($url);

        
        $customerKey = base64_decode("MTY1NTU="); 
		$apiKey = base64_decode("ZkZkMlhjdlRHRGVtWnZidzFiY1Vlc05KV0VxS2JiVXE=");
	
        $currentTimeInMillis= round(microtime(true) * 1000);
        $stringToHash 		= $customerKey .  number_format($currentTimeInMillis, 0, '', '') . $apiKey;
        $hashValue 			= hash("sha512", $stringToHash);
        $customerKeyHeader 	= "Customer-Key: " . $customerKey;
        $timestampHeader 	= "Timestamp: " .  number_format($currentTimeInMillis, 0, '', '');
        $authorizationHeader= "Authorization: " . $hashValue;
        $fromEmail 			= $email;
        $subject            = "Two Factor Authentication [Free] efficiency Check ";
        


        $query1 =" MiniOrange joomla [Free] 2FA Plugin to improve efficiency ";
        $content='<div >Hello, <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br><b>Email :<a href="mailto:'.$fromEmail.'" target="_blank">'.$fromEmail.'</a></b><br><br><b>Plugin Efficency Check: '.$query1. '</b><br><br><b>Website: ' .$base_url. '</b><br>Creation Date:'.$c_time.'</div>';

        $fields = array(
            'customerKey'	=> $customerKey,
            'sendEmail' 	=> true,
            'email' 		=> array(
                'customerKey' 	=> $customerKey,
                'fromEmail' 	=> $fromEmail,                
                'fromName' 		=> 'miniOrange',
                'toEmail' 		=> 'shubham.pokharna@xecurify.com',
                'toName' 		=> 'shubham.pokharna@xecurify.com',
                'subject' 		=> $subject,
                'content' 		=> $content
            ),
        );
        $field_string = json_encode($fields);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
            $timestampHeader, $authorizationHeader));
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
        $content = curl_exec($ch);
        if(curl_errno($ch)){
			
            return;
        }
        curl_close($ch);
        return;
	}

    public static function license_efficiency_check()
    {
        $userDetail = self::getCustomerDetails();
        $licenseAttributes = array();
        $currentUsers = self::checkMoTfaUsers();
        if( ("JOOMLA_2FA_PLUGIN" == $userDetail['license_type']) && ("joomla_2fa_premium_plan" == $userDetail['license_plan']))
        {
            $licenseAttributes['plan_fetched'] = 1;
            if((TRUE == self::check_status_time($userDetail['licenseExpiry'])) && (TRUE == self::check_status_time($userDetail['supportExpiry'])))
            {
                $licenseAttributes['license_expiry'] = 0;
                $licenseAttributes['support_expiry'] = 0;
            }
            else if((TRUE == self::check_status_time($userDetail['licenseExpiry'])) && (FALSE == self::check_status_time($userDetail['supportExpiry'])))
            {
                $licenseAttributes['license_expiry'] = 0;
                $licenseAttributes['support_expiry'] = 1;
            }
            else if((FALSE == self::check_status_time($userDetail['licenseExpiry'])) && (TRUE == self::check_status_time($userDetail['supportExpiry'])))
            {
                $licenseAttributes['license_expiry'] = 1;
                $licenseAttributes['support_expiry'] = 0;
            }
            else
            {
                $licenseAttributes['license_expiry'] = 1;
                $licenseAttributes['support_expiry'] = 1;
            }

            if($userDetail['email']!="demo2fauser1@xecurify.com" && ($userDetail['no_of_users']!=0) && ($userDetail['no_of_users']>$currentUsers))
            {
                $licenseAttributes['users'] = 0;
            }
            else if($userDetail['email']=="demo2fauser1@xecurify.com" && ($userDetail['no_of_users']!=0) && (5>=$currentUsers))
            {
                $licenseAttributes['users'] = 0;
            }
            else
            {
                $licenseAttributes['users'] = 1;
            }
            
        }
        else if(("JOOMLA_2FA_PLUGIN" != $userDetail['license_type']) ||("joomla_2fa_premium_plan" != $userDetail['license_plan']))
        {
            $licenseAttributes['plan_fetched'] = 0;
        }
        return $licenseAttributes;
    }

    static function check_status_time($timestamp)
    {
        $convertedTime = new DateTime($timestamp);
        $currentTime = new DateTime();

        $differenceSeconds = $convertedTime->format('U') - $currentTime->format('U');
        if($differenceSeconds>0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public static function _get_all_login_attempts_count()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from($db->quoteName('#__miniorange_tfa_users'));
        $db->setQuery($query);
        return $db->loadResult();;
    }

    public static function _get_login_transaction_reports()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__miniorange_tfa_users'));
        $db->setQuery($query);
        return $db->loadAssoc();
    }

    public static function _get_login_attempts_count($low_id, $upper_id,$order="down")
    {
        $db = JFactory::getDbo();
        $temp = array();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__miniorange_tfa_users'));
        
        $query->order('jUsername ASC');
        $db->setQuery($query,$low_id,$upper_id);
        $temp[] = $db->loadAssocList();
        return $temp;
    }
}
<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_twofa
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
JHtml::_('jquery.framework');
jimport('miniorangetfa.utility.commonUtilitiesTfa');
JHtml::_('stylesheet', JUri::base() . 'components/com_miniorange_twofa/assets/css/miniorange_boot.css');
JHtml::_('stylesheet', JUri::base() . 'components/com_miniorange_twofa/assets/css/mo_two_fa_style_sheet.css');
JHtml::_('stylesheet', JUri::base() . 'components/com_miniorange_twofa/assets/css/mo_tfa_phone.css');
JHtml::_('stylesheet', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css');
JHtml::_('script', JURI::base() . 'components/com_miniorange_twofa/assets/js/mo_tfa_phone.js');
JHtml::_('stylesheet', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
JHtml::_('script', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js');
/*
 * Check is curl installed or not, if not show the instructions for installation.
*/
commonUtilitiesTfa::checkIsCurlInstalled();
$app = JFactory::getApplication();
$get = $app->input->get->getArray();
$checkFree = commonUtilitiesTfa::getCustomerDetails();
if($checkFree['license_type'] != 'JOOMLA_2FA_PLUGIN')
{
    ?>
        <div class="mo_boot_row">
            <div class="mo_boot_col-sm-12">
                <p class="alert alert-danger">Note : The Free Version of the Joomla 2FA Plugin allows configuration for up to 5 users only. To extend this feature to more users, kindly consider purchasing our license, which is tailored to user-based pricing.</p>
            </div>
        </div>
    <?php
}
if(isset($get['tab-panel']))
{
    $mfa_active_tab = $get['tab-panel'];
}
?>  
<script>
    function mo_show_tab(tab_id)
    {

        if(tab_id=='2fa_tab_1')
        {
            jQuery("#mo_navbar").show(); 
            jQuery(".mo_nav_tab_active ").removeClass("mo_nav_tab_active").removeClass("active");
            jQuery("#nav_item2").addClass("mo_nav_tab_active");

        }
        else
        {
            jQuery("#mo_navbar").hide();
        }
        jQuery(".mini_2fa_tab").css("background",'none');
        jQuery(".mini_2fa_tab").css("color",'white');
        jQuery(".mo_2fa_tab").css('display','none');
        jQuery("#"+tab_id).css('display','block');
        jQuery("#mo_"+tab_id).css("background",'white');
        jQuery("#mo_"+tab_id).css("color",'black');
        
    }
</script>
<div class="mo_boot_container-fluid mo_boot_2fa-container">
    <div class="mo_boot_row mo_boot_p-2 " style="background:#001b4c;border-bottom:2px solid white;">
        <div class="mo_boot_col-sm-6 mo_boot_text-light mo_boot_text-left">
            <h4><?php echo JText::_('COM_MINIORANGE_TWO_FACTOR_AUTHENTICATION');?></h4>
        </div>
        <div class="mo_boot_col-sm-6" style="font-family:sans-serif;text-align:right">
            <a  href="<?php echo JURI::base().'index.php?option=com_miniorange_twofa&view=account_setup&tab-panel=support'?>" class="mo_boot_btn mo_boot_bg-light mo_hover-btn"  style=""><strong><?php echo JText::_('COM_MINIORANGE_SUPPORT_FEATURE');?></strong></a>
            &emsp;<a  href="<?php echo JURI::base().'index.php?option=com_miniorange_twofa&view=Licensing'?>" class="mo_boot_btn mo_boot_bg-light mo_hover-btn"  style=""><strong><?php echo JText::_('COM_MINIORANGE_UPGRADE_PLAN');?></strong></a>
        </div>
    </div>
    <div class="mo_boot_row">
        <div class="mo_boot_col-sm-2 mo_boot_2fa-row">
            <div class="mo_boot_row">
                <div onclick="mo_show_tab('2fa_tab_1')" style="<?php echo ($mfa_active_tab=='account_setup')?'background:white;color:black':'background:none;color:white;'?>" id="mo_2fa_tab_1" class="mini_2fa_tab mo_boot_col-sm-12 mo_boot_p-3 mo_boot_border-1 mo_boot_2fa-tab">
                    <strong><?php echo JText::_('COM_MINIORANGE_TFA_ACCOUNT');?></strong>
                </div>
                <div onclick="mo_show_tab('2fa_tab_2')" style="<?php echo ($mfa_active_tab=='login_settings')?'background:white;color:black':'background:none;color:white;'?>" id="mo_2fa_tab_2" class="mini_2fa_tab mo_boot_col-sm-12 mo_boot_p-3 mo_boot_border-1 mo_boot_2fa-tab">
                    <strong><?php echo JText::_('COM_MINIORANGE_LOGIN_SETTING');?></strong>
                </div>
                <div onclick="mo_show_tab('2fa_tab_3')" style="<?php echo ($mfa_active_tab=='advance_settings')?'background:white;color:black':'background:none;color:white;'?>" id="mo_2fa_tab_3" class="mini_2fa_tab mo_boot_col-sm-12 mo_boot_p-3 mo_boot_border-1 mo_boot_2fa-tab">
                    <strong><?php echo JText::_('COM_MINIORANGE_ADVANCE_SETTINGS');?></strong>
                </div>
                <div onclick="mo_show_tab('2fa_tab_4')" style="<?php echo ($mfa_active_tab=='user_management')?'background:white;color:black':'background:none;color:white;'?>" id="mo_2fa_tab_4" class="mini_2fa_tab mo_boot_col-sm-12 mo_boot_p-3 mo_boot_border-1 mo_boot_2fa-tab">
                    <strong><?php echo JText::_('COM_MINIORANGE_USER_MANAGEMENT');?></strong>
                </div>
                <div onclick="mo_show_tab('2fa_tab_5')" style="<?php echo ($mfa_active_tab=='login_forms')?'background:white;color:black':'background:none;color:white;'?>" id="mo_2fa_tab_5" class="mini_2fa_tab mo_boot_col-sm-12 mo_boot_p-3 mo_boot_border-1 mo_boot_2fa-tab">
                    <strong><?php echo JText::_('COM_MINIORANGE_LOGIN_FORMS');?></strong>
                </div>
                <div onclick="mo_show_tab('2fa_tab_10')" style="<?php echo ($mfa_active_tab=='popup_design')?'background:white;color:black':'background:none;color:white;'?>" id="mo_2fa_tab_10" class="mini_2fa_tab mo_boot_col-sm-12 mo_boot_p-3 mo_boot_border-1 mo_boot_2fa-tab">
                    <strong><?php echo JText::_('COM_MINIORANGE_POPUPS');?></strong>
                </div>
            </div>
        </div>
        <?php
            $isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
            $details = commonUtilitiesTfa::getCustomerDetails();
        ?>
        <div class="mo_boot_col-sm-10">
            <?php
                if($isCustomerRegistered  && $details['email'] == "demo2fauser1@xecurify.com" )
                {
                    ?>
                    <div class="alert alert-danger">
                        <span><strong><?php echo JText::_('COM_MINIORANGE_NOTE');?></strong> <?php echo JText::_('COM_MINIORANGE_DEMO_LOGIN_DESC');?> <a href="https://portal.miniorange.com/initializepayment?requestOrigin=joomla_2fa_premium_plan" target="_blank"> <?php echo JText::_('COM_MINIORANGE_HERE');?></a> <?php echo JText::_('COM_MINIORANGE_UPGRADE_ACC');?></span>
                    </div>
                    <?php
                }
            ?>
            <div class="mo_boot_col-sm-12 mo_2fa_tab" id="2fa_tab_1" style="<?php echo (($mfa_active_tab=='account_setup')?'display:block;':'display:none;');?>">
                <?php
                    $details = commonUtilitiesTfa::getCustomerDetails();
                    $app = JFactory::getApplication();
                    $get = $app->input->get->getArray();
                    if($details['login_status']==1)
                    {
                        welcomeCustomer($details);
                    }
                    else
                    {
                        if(array_key_exists('tab',$get))
                        {
                            loginTab($get['tab']);
                        }
                        else
                        {
                            loginTab();
                        }
                    }
                ?>
            </div> 
            <div class="mo_boot_col-sm-12 mo_2fa_tab" id="2fa_tab_2" style="<?php echo (($mfa_active_tab=='login_settings')?'display:block;':'display:none;');?>">
                <?php echo loginSettingsTab();?>
            </div>
            <div class="mo_boot_col-sm-12 mo_2fa_tab" id="2fa_tab_3" style="<?php echo (($mfa_active_tab=='advance_settings')?'display:block;':'display:none;');?>">
                <?php echo advanceSettingsTab();?>
            </div>
            <div class="mo_boot_col-sm-12 mo_2fa_tab" id="2fa_tab_4" style="<?php echo (($mfa_active_tab=='user_management')?'display:block;':'display:none;');?>">
                <?php
                    UserManagement();
                ?>
            </div>
            <div class="mo_boot_col-sm-12 mo_2fa_tab" id="2fa_tab_5" style="<?php echo (($mfa_active_tab=='login_forms')?'display:block;':'display:none;');?>">
                <?php
                    CustomLoginForms();
                ?>
            </div>
            <div class="mo_boot_col-sm-12 mo_2fa_tab" id="2fa_tab_8" style="<?php echo (($mfa_active_tab=='support')?'display:block;':'display:none;');?>">
                <?php
                    support();
                ?>
            </div>
            <div class="mo_boot_col-sm-12 mo_2fa_tab" id="2fa_tab_10" style="<?php echo (($mfa_active_tab=='popup_design')?'display:block;':'display:none;');?>">
                <?php
                    popup();
                ?>
            </div>
        </div>
    </div>
</div>
<?php
function loginTab($tab='register'){
    $user = JFactory::getUser();
    ?>
    <div class="mo_boot_row mo_boot_m-1">
        <div class="mo_boot_container-fluid mo_boot_py-4">
            <div class="card no_boot_my-2" style="box-shadow: -2 3px 7px 2px hsl(214deg 17.95% 5.21%);">
                <div class="card-body">
                    <div class="mo_boot_col-sm-11 mo_boot_mt-3 mo_boot_text-center">
                        <h3 id="mo_tfa_register_login"><?php echo JText::_('COM_MINIORANGE_LOGIN_MINI');?><span class="icon-mo_tfa_icon mo_tfa_mini_logo" aria-hidden="true"></span>range</h3><hr style="1px solid rgb(116 107 107 / 72%)">
                    </div>
                    <div class="mo_boot_col-sm-12 mo_boot_offset-2 mo_tfa_Login_panel" style="width:62%">
                        <form name="f" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&task=setup_two_factor.register_login_customer'); ?>"> 
                            <div class="mo_boot_row mo_boot_mt-3 mo_boot_mx-4">
                                <div class="mo_boot_col-sm-12" style="font-weight:500;">
                                    <?php echo JText::_('COM_MINIORANGE_EMAIL');?>
                                </div>
                                <div class="mo_boot_col-sm-12 mo_boot_mt-1">
                                    <input class="mo_boot_form-control"  type="email" name="email" required placeholder="<?php echo JText::_('COM_MINIORANGE_EXAMPLE_EMAIL');?>"  />
                                </div>
                            </div>
                            <div class="mo_boot_row mo_boot_mt-3 mo_boot_mx-4" style="font-weight:500;">
                                <div class="mo_boot_col-sm-12 ">
                                    <?php echo JText::_('COM_MINIORANGE_PASSWORD');?>  
                                </div>
                                <div class="mo_boot_col-sm-12 mo_boot_mt-1">
                                    <input class="mo_boot_form-control"  required type="password" name="password" placeholder="<?php echo JText::_('COM_MINIORANGE_PASSWORD_MSG');?>" />
                                    <a class="mo_boot_forgot_phn mo_boot_mt-1" style="color:#737e7ce3;font-family: ui-sans-serif;float:left;cursor:pointer;" href="<?php echo commonUtilitiesTfa::getHostName(); ?>/moas/idp/resetpassword" target="_blank"><u><?php echo JText::_('COM_MINIORANGE_FORGOT_PASSWORD');?></u></a>
                                </div>
                            </div>
                            <div class="mo_boot_row mo_boot_my-4">
                                <div class="mo_boot_col-sm-12 mo_boot_text-center">
                                    <input type="submit" name="register_or_login" class="mo_boot_btn" style="background-color:#036682;color:#fff;width: 66%;border-radius:21px" value="<?php echo JText::_('COM_MINIORANGE_VALLOGIN1');?>" />
                                </div>
                                <div class="mo_boot_col-sm-12 mo_boot_text-center mo_boot_mt-2">
                                    <span class="mo_boot_forgot_phn " style="color:#403b3b;font-family: ui-sans-serif;" ><?php echo JText::_('COM_MINIORANGE_REGISTER_MINI');?></span><br>
                                    <a class="mo_boot_forgot_phn " style="color:#403b3b;font-family: ui-sans-serif;cursor:pointer;" href="https://www.miniorange.com/businessfreetrial" target='_blank' ><u> <?php echo JText::_('COM_MINIORANGE_CREATE_ACCOUNT');?></u></a>     
                                </div>
                            </div> 
                        </form>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
function welcomeCustomer($details)
{
    $tfaDetails=commonUtilitiesTfa::customerTfaDetails();
    $tfaMethodArray = Mo_tfa_utilities::tfaMethodArray();
    $transctionUrl = "<a href='".Mo_tfa_utilities::getHostname()."/moas/login?username=".$details['email']."&redirectUrl=".Mo_tfa_utilities::getHostname()."/moas/viewtransactions' target='_blank'>".JText::_("COM_MINIORANGE_VAL_CHECK")."</a>";
    $licenseExpiry = strtotime($details['licenseExpiry']);
    $supportExpiry = strtotime($details['supportExpiry']);
    $customCSS = '';
    $joomla_version = commonUtilitiesTfa::getJoomlaCmsVersion();
    $phpVersion = phpversion();
    $PluginVersion = commonUtilitiesTfa::GetPluginVersion();

    $isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
    $current_user     = JFactory::getUser();
    $isFirstUser          = commonUtilitiesTfa::isFirstUser($current_user->id);
    $c_time =date("Y-m-d",time());
    $licenseType = "";
    $demoAccount = false;
    if(isset($details['email'])&&$details['email'] == 'demo2fauser1@xecurify.com')
    {
        $licenseType = 'Demo Account';
        $demoAccount = true;
    }
    else
        $licenseType = !empty($details['license_type'])?$details['license_type']:'Demo';

    $email=commonUtilitiesTfa::_getMaskedEmail($details['email']);
    ?>
    <div class="mo_boot_row mo_boot_m-1" style="background:white;">
        <div class="mo_boot_col-sm-12" style="padding-top:10px;">
            <h5 class="text-center " style="background:rgba(0, 255, 0, 0.15);color:rgba(0, 128, 0, 0.80);padding:2px;padding: 10px;33" ><?php echo ($licenseType== 'Demo Account')?'You have successfully logged in with Demo Account': JText::_('COM_MINIORANGE_THANKS');?></h5><hr>
            <details class="mo_details">
                <summary style="background:#e1e5e8a3;">
                    <b style="font-size:15px;color:#0d0c0d;"><?php echo JText::_('COM_MINIORANGE_ACCINFO');?></b>
                </summary>
                <div class="mo_boot_row mo_boot_m-2">
                    <div class="mo_boot_col-sm-12 mo_boot_table-responsive">
                        <table class="mo_boot_table mo_boot_table-bordered mo_boot_table-striped">
                            <tr>
                                <td><?php echo JText::_('COM_MINIORANGE_2FA_REGISTERED');?></td>
                                <td><?php echo $email;?></td>
                            </tr> 
                            <tr>
                                <td><?php echo JText::_('COM_MINIORANGE_XECURIFY_REG');?></td>
                                <td><?php echo $email;?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_MINIORANGE_CUSTOMER_ID');?></td>
                                <td><?php echo $details['customer_key'];?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_MINIORANGE_JOOMLA_VERSION');?></td>
                                <td><?php echo $joomla_version;?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_MINIORANGE_PHP_VERSION');?></td>
                                <td><?php echo $phpVersion ?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_MINIORANGE_PLUGIN_VERSION');?></td>
                                <td><?php echo $PluginVersion;?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </details>
            <details class="mo_details" open>
                <summary style="background:#e1e5e8a3">
                    <strong style="font-size:15px;color:#0d0c0d;"><?php echo JText::_('COM_MINIORANGE_LIC_INFO');?></strong>
                </summary>
                <div class="mo_boot_row mo_boot_m-2">
                    <div class="mo_boot_col-sm-12 mo_boot_table-responsive">
                        <table class="mo_boot_table mo_boot_table-bordered mo_boot_table-striped">
                            <tr>
                                <td><?php echo JText::_('COM_MINIORANGE_LIC_TYPE');?></td>
                                <td><?php echo $licenseType;?></td>
                            </tr>
                            <tr>
                                <td><?php echo JText::_('COM_MINIORANGE_USERS_NO');?></td>
                                <td><?php echo empty($details['license_plan'])?1:$details['no_of_users'];?></td>
                            </tr>
                            <?php 
                                if(!$demoAccount)
                                {
                                    ?>
                                        <tr>
                                            <td><?php echo JText::_('COM_MINIORANGE_EMAIL_REMAIN');?></td>
                                            <td><?php echo empty($details['license_plan'])?$transctionUrl:$details['emailRemaining'];?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo JText::_('COM_MINIORANGE_SMS_REMAIN');?></td>
                                            <td><?php echo empty($details['license_plan'])?$transctionUrl:$details['smsRemaining'];?></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                            <tr <?php echo $customCSS; ?> >
                                <td><?php echo JText::_('COM_MINIORANGE_LIC_EXP');?></td>
                                <td><?php if(isset($details['licenseExpiry']) && strtotime($c_time)>($licenseExpiry)){
                                ?>
                               <span style="color:#FF0000;"><?php echo $details['licenseExpiry']; ?></span>
                               <?php }
                                else { 
                                echo empty($details['licenseExpiry'])?$transctionUrl:$details['licenseExpiry'];
                                }?>
                            </td>
                            </tr>
                            <tr <?php echo $customCSS; ?>>
                                <td><?php echo JText::_('COM_MINIORANGE_SUPPORT_EXP');?></td>
                                <td>
                                    <?php if(isset($details['supportExpiry']) && strtotime($c_time)>($supportExpiry)){
                                    ?>
                                        <span style="color:#FF0000;"> <?php echo $details['supportExpiry'];?></span>
                                    <?php }
                                    else {
                                        echo empty($details['supportExpiry'])?$transctionUrl:$details['supportExpiry'];
                                     }?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </details>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_my-3 mo_boot_text-center" style="color:white">
            <a onclick="document.getElementById('mo_tfa_fetch_ccl').submit();" class="mo_boot_btn  mo_boot_btn-primary" style="margin-right: 10px;" ><?php echo JText::_('COM_MINIORANGE_REFETCH_LIC');?></a>
            <?php
                if(!$demoAccount)
                {
                    ?>
                        <a class="mo_boot_btn mo_boot_btn-primary_two" style="margin-right: 10px;"  target="_blank" href = "https://portal.miniorange.com/initializepayment?requestOrigin=joomla_2fa_premium_plan"><?php echo JText::_('COM_MINIORANGE_UPGRADE');?></a>
                    <?php
                }
            ?>
            <?php
                if($isCustomerRegistered && $isFirstUser )
                {
                    ?>
                    <a onclick="document.getElementById('mo_remove_account').submit();" class="mo_boot_btn  mo_boot_btn-danger" ><?php echo JText::_('COM_MINIORANGE_REMOVE_ACC');?></a>
                    <?php
                }
            ?>
        </div>
    </div>
    <form name="f"  method="post" id="mo_tfa_fetch_ccl" action="<?php echo JRoute::_('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&task=setup_two_factor.checkLicense'); ?>"></form>
    <form name="f"  method="post" id="mo_remove_account" action="<?php echo JRoute::_('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&task=setup_two_factor.removeAccount'); ?>"></form>
    <?php
}
function loginSettingsTab()
{
    $settings   = commonUtilitiesTfa::getTfaSettings();
    $kbasetofques1             = "What is your first company name?What was your childhood nickname?In what city did you meet your spouse/significant other?What is the name of your favorite childhood friend?What school did you attend for sixth grade?";
	$kbasetofques2             = "What was the name of the city or town where you first worked? Which sport is your favourite? Who is your favourite athlete? What is the maiden name of your grandmother? What was the registration number of your first car?";
	$enabled_tfa               = isset($settings['tfa_enabled']) && $settings['tfa_enabled'] == 1 ? 'checked' :'';
	$enable_2fa_user_type      = isset($settings['tfa_enabled_type'])? $settings['tfa_enabled_type'] : 'none';
	$inline                    = isset($settings['tfa_halt']) && $settings['tfa_halt'] == 1? 'checked': '';
    $skip_tfa_for_users        = isset($settings['skip_tfa_for_users']) && $settings['skip_tfa_for_users'] == 1? 'checked': '';
    $enable_otp_login          = isset($settings['enable_tfa_passwordless_login']) && $settings['enable_tfa_passwordless_login'] == 1? 'checked': '';
    $enable_change_2fa_method  = isset($settings['enable_change_2fa_method']) && $settings['enable_change_2fa_method']==1? 'checked': '';
    $enable_remember_device    = isset($settings['remember_device']) &&  $settings['remember_device']==1?'checked' : '';
    $enable_2fa_backup_method  = isset($settings['enable_backup_method']) && $settings['enable_backup_method']==1? 'checked': '';
	$enable_2fa_backup_type    = isset($settings['enable_backup_method_type'])? $settings['enable_backup_method_type'] : 'none'; 
	$kbaSet1                   = isset($settings['tfa_kba_set1'])? $settings['tfa_kba_set1'] : $kbasetofques1;
	$kbaSet2                   = isset($settings['tfa_kba_set2'])? $settings['tfa_kba_set2'] : $kbasetofques2;
    $kbaSet1 = $settings['tfa_kba_set1'] ? $settings['tfa_kba_set1'] : '';
    $kbaSet2 = $settings['tfa_kba_set2'] ? $settings['tfa_kba_set2'] : '';
    $isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
    $details= commonUtilitiesTfa::getCustomerDetails();
    $customerEmail = $details['email'];
    $inlineDisabled='';
    if((($details['license_plan']=='') && ($details['license_type'])=='')){
        $inlineDisabled='disabled';
    }
    $featureDisable='';
    if( !$isCustomerRegistered ){
        $featureDisable='disabled';
    }
    $disabled='disabled';
    $active2FA = commonUtilitiesTfa::getActive2FAMethods();
    $current_user     = JFactory::getUser();
    $isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
    $isFirstUser          = commonUtilitiesTfa::isFirstUser($current_user->id);
    ?>
    <script>
        jQuery(document).on('change','#enable_2fa_backup_method', function enable_backup_change()
        {
            if(!jQuery("#enable_2fa_backup_method").is(':checked'))
            {
                jQuery("#setup_kba_questions").css("display","none");
            }
            else
            {
                if(jQuery("#enable_2fa_backup_type").val()=='securityQuestion')
                {
                    jQuery("#setup_kba_questions").css("display","block");
                }
                else
                {
                    jQuery("#setup_kba_questions").css("display","none");
                }
            }
        });
        jQuery(document).ready(function()
        {
            enable_tfa_change();
            var methods_checkbox = document.getElementsByClassName('methods_checkbox');
            var enable_2fa_users = document.getElementById('enable_2fa_users');
            var checkboxes = document.querySelectorAll('input.methods_checkbox');
            for (var i = 0; i < methods_checkbox.length; i++) {
                methods_checkbox[i].disabled = !enable_2fa_users.checked;
                
            }
            if(!jQuery("#enable_2fa_users").attr('disabled'))
            {
                if(jQuery("#enable_2fa_users").is(':checked'))
                {
                    checkboxes.forEach(function(checkbox) {
                    checkbox.disabled = false;
                    });
                    jQuery("#enable_2fa_user_type").removeAttr("disabled");
                    jQuery("#select_methods").prop('disabled', 'false');  
                }
                else
                {
                    jQuery("#enable_2fa_user_type").attr("disabled","true");
                    jQuery("#select_methods").prop('disabled', 'true');  
                }
            }
            else
            { 
                    checkboxes.forEach(function(checkbox) {
                    checkbox.disabled = true;
                    });
            }
            if(!jQuery("#enable_2fa_backup_method").attr('disabled'))
            {
                if(jQuery("#enable_2fa_backup_method").is(':checked'))
                {
                    jQuery("#enable_2fa_backup_type").removeAttr("disabled");
                    if(jQuery("#enable_2fa_backup_type").val()=='securityQuestion')
                    {
                        jQuery("#setup_kba_questions").css("display","block");
                    }
                    else
                    {
                        jQuery("#setup_kba_questions").css("display","none");
                    }
                }
                else
                {
                    jQuery("#enable_2fa_backup_type").attr("disabled","true");
                    if(jQuery("#enable_2fa_backup_type").val()=='securityQuestion')
                    {
                       jQuery("#setup_kba_questions").css("display","none");
                    }
                }
                
            }
            else
            {
                jQuery("#setup_kba_questions").css("display","none");
                jQuery("#enable_2fa_backup_type").attr("disabled","true");
            }
        });
        
        function change_skipTfa()
        {
            if(jQuery("#enable_mo_tfa_inline").is(':checked'))
            {
                jQuery('#skip_tfa_for_users').prop('checked', false);
                alert("If TFA is disabled for new users, you cannot enable the skip TFA option during inline setup.")
            }
            
        }
        function change_tfaInline()
        {
            if(jQuery("#skip_tfa_for_users").is(':checked'))
            {
                jQuery('#skip_tfa_for_users').prop('checked', false);
                alert("If TFA is disabled for new users, you cannot enable the skip TFA option during inline setup.")
            }
            
        }
        function enable_tfa_change(){
            var checkboxes = document.querySelectorAll('input.methods_checkbox');
                
            if(jQuery("#enable_2fa_users").is(':checked') )
            {
                if(!jQuery("#enable_2fa_users").attr('disabled'))
                {
                    jQuery("#enable_2fa_user_type").removeAttr("disabled");
                    jQuery("#skip_tfa_for_users").removeAttr("disabled");
                    jQuery("#change_tfa_method").removeAttr("disabled");
                    jQuery("#enable_remember_device").removeAttr("disabled");
                    jQuery("#enable_2fa_backup_method").removeAttr("disabled");
                    checkboxes.forEach(function(checkbox) {
                    checkbox.disabled = false;
                    });
                }
    
            }
            else
            {
                jQuery("#enable_2fa_user_type").attr("disabled","true");
                jQuery("#skip_tfa_for_users").attr("disabled","true");
                jQuery("#change_tfa_method").attr("disabled","true");
                jQuery("#select_methods").prop('disabled', 'true');  
                jQuery("#enable_remember_device").prop('disabled', 'true');  
                jQuery("#enable_2fa_backup_method").prop('disabled', 'true'); 
                checkboxes.forEach(function(checkbox) {
                    checkbox.disabled = true;
                    }); 
            }
        }
        
        function enable_backup_change(){
            if(jQuery("#enable_2fa_backup_method").is(':checked'))
            {
                jQuery("#enable_2fa_backup_type").removeAttr("disabled");
            }
            else
            { 
                jQuery("#enable_2fa_backup_type").attr("disabled","true");
            }
        }
        function show_kba_question()
        {
            if(jQuery("#enable_2fa_backup_type").val()=='securityQuestion')
            {
                jQuery("#setup_kba_questions").css("display","block");
            }
            else
            {
                jQuery("#setup_kba_questions").css("display","none");
            }
        }
    </script>
    <div class="mo_boot_row mo_boot_m-1">
        <div class="mo_boot_col-sm-12"><?php
            if( !commonUtilitiesTfa::isCustomerRegistered() ){
                echo  '<div class="mo_register_message">'.JText::_("COM_MINIORANGE_SETUP_TFA_MSG").' <a href="'. JRoute::_('index.php?option=com_miniorange_twofa&tab-panel=account_setup').'" >'.JText::_("COM_MINIORANGE_REGISTER_MSG").'</a> '.JText::_("COM_MINIORANGE_SETUP_TFA_MSG1").'</div>';
            } 
            ?>
        </div>
        <div class="mo_boot_col-sm-12">
            <div class="mo_boot_row mo_boot_text-center">
                <div class="mo_boot_col-sm-12"><br>
                    <h3><?php echo JText::_('COM_MINIORANGE_LOGIN_SETTINGS');?></h3><hr>
                </div>
            </div>
            <div class="mo_boot_col-sm-12 mo_boot_mt-2" >
                <form name="f" class="miniorange_tfa_settings_form" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&task=setup_two_factor.saveTfaSettings');?>">
                    <div class="mo_boot_row">
                        <div class="mo_boot_col-sm-12">
                            <input type="checkbox" id="enable_2fa_users" onchange="enable_tfa_change()" name="enable_mo_tfa" <?php echo $enabled_tfa;?> <?php echo $featureDisable ?> <?php echo $inlineDisabled ?>/>&emsp;<strong><?php echo JText::_('COM_MINIORANGE_ENABLE2FA');?></strong>
                            <?php echo $inlineDisabled=='disabled'?'<a href='.JRoute::_('index.php?option=com_miniorange_twofa&view=Licensing').'><strong>&nbsp;&nbsp;['.JText::_("COM_MINIORANGE_PREMIUM").']</strong></a>':''; ?> 
                            <br>&emsp;&emsp;<strong><?php echo JText::_('COM_MINIORANGE_NOTE');?>&nbsp;</strong><?php echo JText::_('COM_MINIORANGE_ENABLE2FA_NOTE');?></span><br>
                        </div>
                        </div>
                        <div class="mo_boot_row">
                            <div class="mo_boot_col-sm-2 mo_boot_offset-1 mo_boot_mt-2">
                                <strong><?php echo JText::_('COM_MINIORANGE_ENABLE2FA_FOR');?> </strong>
                            </div>
                        <div class="mo_boot_col-sm-4 mo_boot_mt-2">
                            <select class="mo_boot_form-control" name="enable_2fa_user_type" id="enable_2fa_user_type" <?php echo $featureDisable ?> <?php echo $inlineDisabled ?> >
                                <option value="both" <?php echo $enable_2fa_user_type=='both'?"selected":"";?>> <?php echo JText::_('COM_MINIORANGE_2FA_BOTH');?> </option>
                                <option value="admin"  <?php echo $enable_2fa_user_type=='admin'?"selected":"";?>> <?php echo JText::_('COM_MINIORANGE_2FA_ADMIN_SUPERUSERS');?> </option>
                                <option value="site" <?php echo $enable_2fa_user_type=='site'?"selected":"";?> > <?php echo JText::_('COM_MINIORANGE_2FA_ENDUSERS');?> </option>
                            </select>
                        </div>
                    </div>
                    <p class="mo_boot_my-4">
                        <input type="checkbox" name="enable_mo_tfa_inline" id="enable_mo_tfa_inline" onchange="change_tfaInline()"<?php echo $inline.' '.$inlineDisabled;?> />
                        &emsp;<strong><?php echo JText::_('COM_MINIORANGE_DISABLE2FA');?></strong><?php echo $inlineDisabled=='disabled'?'<a href='.JRoute::_('index.php?option=com_miniorange_twofa&view=Licensing').'><strong>&nbsp;&nbsp;['.JText::_("COM_MINIORANGE_PREMIUM").']</strong></a>':''; ?>
                        <br><strong>&emsp;&emsp;<?php echo JText::_('COM_MINIORANGE_NOTE');?>&nbsp;</strong> <?php echo JText::_('COM_MINIORANGE_DISABLE2FA_DESC');?><br>
                    </p>
                    <p class="mo_boot_my-4">
                        <input type="checkbox" name="skip_tfa_for_users" id="skip_tfa_for_users" onchange="change_skipTfa()" value="1" <?php  echo $skip_tfa_for_users;?> <?php echo $inlineDisabled ?>/>
                        &emsp;<strong><?php echo JText::_('COM_MINIORANGE_SKIP2FA');?></strong><?php echo $inlineDisabled=='disabled'?'<a href='.JRoute::_('index.php?option=com_miniorange_twofa&view=Licensing').'><strong>&nbsp;&nbsp;['.JText::_("COM_MINIORANGE_PREMIUM").']</strong></a>':''; ?>
                        <br><strong>&emsp;&emsp;<?php echo JText::_('COM_MINIORANGE_NOTE');?>&nbsp;</strong><?php echo JText::_('COM_MINIORANGE_SKIP2FA_DESC');?><br>
                    </p>
                    <p class="mo_boot_my-4 ">
                        <input type="checkbox" name="enable_change_2fa_method" id="change_tfa_method" onchange="change_2fa_method()" <?php echo $inlineDisabled; ?> <?php echo $enable_change_2fa_method;?> <?php echo " ".$featureDisable ?>  />&emsp;<strong><?php echo JText::_('COM_MINIORANGE_CHANGE2FA');?></strong><?php echo $inlineDisabled=='disabled'?'<a href='.JRoute::_('index.php?option=com_miniorange_twofa&view=Licensing').'><strong>&nbsp;&nbsp;['.JText::_("COM_MINIORANGE_PREMIUM").']</strong></a>':''; ?> <br>
                        <strong>&emsp;&emsp;<?php echo JText::_('COM_MINIORANGE_NOTE');?> </strong> <?php echo JText::_('COM_MINIORANGE_CHANGE2FA_DESC');?><br>
                        &emsp;&emsp;<span style="color:red;"><?php echo JText::_('COM_MINIORANGE_CHANGE2FA_NOTE');?>
                        </span>
                    </p>
                    <p class="mo_boot_my-4 ">
                        <input type="checkbox" name="enable_remember_device" id="enable_remember_device"  <?php echo $featureDisable ?> <?php echo $inlineDisabled ?><?php echo $enable_remember_device ?> />&emsp;<strong><?php echo JText::_('COM_MINIORANGE_REMEMBER_DEVICE_ENABLE');?></strong><?php echo $inlineDisabled=='disabled'?'<a href='.JRoute::_('index.php?option=com_miniorange_twofa&view=Licensing').'><strong>&nbsp;&nbsp;['.JText::_("COM_MINIORANGE_PREMIUM").']</strong></a>':''; ?> 
                        <br><strong>&emsp;&emsp;<?php echo JText::_('COM_MINIORANGE_NOTE');?> </strong> <?php echo JText::_('COM_MINIORANGE_REMEMBER_DEVICE_DESC');?><br>
                    </p>
                    <hr><br>
                    <div class="mo_boot_row mo_boot_my-3">
                        <div class="mo_boot_col-sm-6">
                            <input type="checkbox" id="enable_2fa_backup_method" onchange="enable_backup_change()" name="enable_2fa_backup_method" <?php echo $enable_2fa_backup_method;?> <?php echo $featureDisable ?> <?php echo $inlineDisabled ?>/>&emsp;<strong><?php echo JText::_('COM_MINIORANGE_BACKUP_METHODS');?></strong><?php echo $inlineDisabled=='disabled'?'<a href='.JRoute::_('index.php?option=com_miniorange_twofa&view=Licensing').'><strong>&nbsp;&nbsp;['.JText::_("COM_MINIORANGE_PREMIUM").']</strong></a>':''; ?> 
                        </div>
                        <div class="mo_boot_col-sm-6">
                            <select class="mo_boot_form-control" <?php echo $featureDisable ?> <?php echo $inlineDisabled ?> onchange="show_kba_question()" name="enable_2fa_backup_type" id="enable_2fa_backup_type">
                                <option value="securityQuestion" <?php echo $enable_2fa_backup_type=='securityQuestion'?"selected":"";?> ><?php echo JText::_('COM_MINIORANGE_SECURITYQUES');?></option>
                                <option value="backupCodes" <?php echo $enable_2fa_backup_type=='backupCodes'?"selected":"";?> ><?php echo JText::_('COM_MINIORANGE_BACKUP_CODES');?></option>
                            </select>
                        </div>
                    </div>
                    <div class="mo_boot_row mo_boot_my-3" id="setup_kba_questions">
                        <div class="mo_boot_col-sm-12 mo_boot_py-4" style="border:1px solid lightgray;">
                            <ul style="list-style:none">
                                <i><p><strong><?php echo JText::_('COM_MINIORANGE_NOTE');?> </strong><?php echo JText::_('COM_MINIORANGE_KBAQ');?></p></i>
                                <li>
                                    <label for="KBA_set_ques1"><strong><?php echo JText::_('COM_MINIORANGE_KBAQ1');?></strong></label>
                                    <textarea name="KBA_set_ques1" id="KBA_set_ques1" <?php echo $featureDisable ?> cols="30" rows="5" style="width:100%; border: 1px solid;"><?php echo $kbaSet1; ?> </textarea>
                                </li><hr>
                                <li>
                                    <label for="KBA_set_ques2"><strong><?php echo JText::_('COM_MINIORANGE_KBAQ2');?></strong></label>
                                    <textarea name="KBA_set_ques2" id="KBA_set_ques2" <?php echo $featureDisable ?> cols="30" rows="5" style="width:100%;border: 1px solid;"><?php echo $kbaSet2; ?></textarea>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <br> 
                    <div class="mo_boot_row mo_boot_my-3">
                        <div class="mo_boot_col-sm-12 mo_boot_py-4" style="border:1px solid lightgray;">
                            <h3 style="display: inline;"><?php echo JText::_('COM_MINIORANGE_PASSLESS_LOGIN');?></h3> <span style="color:red;display: inline;">(<?php echo JText::_('COM_MINIORANGE_OTP_LOGIN_NOTE');?>)</span>
                            <hr>
                            <p class="mo_boot_alert-secondary">
                                <strong><?php echo JText::_('COM_MINIORANGE_NOTE');?></strong> <?php echo JText::_('COM_MINIORANGE_OTP_LOGIN_DESC');?></br>
                            </p>
                            <p>
                                <input type="checkbox" name="enable_tfa_passwordless_login" id="enable_otp_login" disabled <?php echo $featureDisable ?> <?php echo $inlineDisabled ?><?php echo $enable_otp_login ?> />&emsp;<strong><?php echo JText::_('COM_MINIORANGE_OTP_LOGIN');?></strong><?php echo $inlineDisabled=='disabled'?'<a href='.JRoute::_('index.php?option=com_miniorange_twofa&view=Licensing').'><strong>&nbsp;&nbsp;['.JText::_("COM_MINIORANGE_PREMIUM").']</strong></a>':''; ?> 
                            </p>
                        </div>
                    </div>
                    <div class="mo_boot_row mo_boot_my-3">
                        <div class="mo_boot_col-sm-12 mo_boot_py-4" style="border:1px solid lightgray;">
                            <h3><?php echo JText::_('COM_MINIORANGE_2FA_METHODS');?></h3>
                            <hr>
                            <p>
                              <i><?php echo JText::_('COM_MINIORANGE_2FA_METHODS_DESC');?></i></br>
                            </p>
                            <p>
                                <strong><?php echo JText::_('COM_MINIORANGE_NOTE');?></strong> <?php echo JText::_('COM_MINIORANGE_2FA_METHODS_DESC_NOTE');?>
                            </p>
                            <table class="table table-hover ">
                                <thead class="table-active">
                                    <tr>
                                        <th scope="col"><?php echo JText::_('COM_MINIORANGE_TOTP_METHODS');?></th>
                                        <th scope="col"><?php echo JText::_('COM_MINIORANGE_OTP_METHODS');?></th>
                                        <th scope="col"><?php echo JText::_('COM_MINIORANGE_OTHER_METHODS');?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input class="methods_checkbox" type="checkbox" name="tfa_method_allowed_google" <?php echo ($active2FA['google']['active'])? 'checked=true' :'' ?>  <?php echo $inlineDisabled ?>/>&emsp;<?php echo JText::_('COM_MINIORANGE_GA');?></td>
                                        <td><input class="methods_checkbox" type="checkbox" name="tfa_method_allowed_OOS" <?php echo ($active2FA['OOS']['active'])? 'checked=true' :'' ?>  <?php echo $inlineDisabled ?>/>&emsp;<?php echo JText::_('COM_MINIORANGE_OOS');?></td>
                                        <td><input class="methods_checkbox" type="checkbox" name="tfa_method_allowed_YK" <?php echo ($active2FA['YK']['active'])? 'checked=true' :'' ?>  <?php echo $inlineDisabled ?>/>&emsp;<?php echo JText::_('COM_MINIORANGE_TFA_METHODS_YUBIKEY');?></td>
                                    </tr>
                                    <tr>
                                        <td><input class="methods_checkbox" type="checkbox" name="tfa_method_allowed_MA" <?php echo ($active2FA['MA']['active'])? 'checked=true' :'' ?>  <?php echo $inlineDisabled ?>/>&emsp;<?php echo JText::_('COM_MINIORANGE_MA');?></td>
                                        <td><input class="methods_checkbox" type="checkbox" name="tfa_method_allowed_OOE" <?php echo ($active2FA['OOE']['active'])? 'checked=true' :'' ?>  <?php echo $inlineDisabled ?> />&emsp;<?php echo JText::_('COM_MINIORANGE_OOE');?></td>
                                        <td><input  type="checkbox" name="tfa_method_allowed_DUON" disabled  />&emsp;<?php echo JText::_('COM_MINIORANGE_DUO_PUSH_NOTIFY');?></td>
                                    </tr>
                                    <tr>
                                        <td><input class="methods_checkbox" type="checkbox" name="tfa_method_allowed_AA" <?php echo ($active2FA['AA']['active'])? 'checked=true' :'' ?>  <?php echo $inlineDisabled ?>/>&emsp;<?php echo JText::_('COM_MINIORANGE_AA');?></td>
                                        <td><input class="methods_checkbox" type="checkbox" name="tfa_method_allowed_OOSE" <?php echo ($active2FA['OOSE']['active'])? 'checked=true' :'' ?>  <?php echo $inlineDisabled ?>/>&emsp;<?php echo JText::_('COM_MINIORANGE_OOSE');?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><input class="methods_checkbox" type="checkbox" name="tfa_method_allowed_LPA" <?php echo ($active2FA['LPA']['active'])? 'checked=true' :'' ?>  <?php echo $inlineDisabled ?>/>&emsp;<?php echo JText::_('COM_MINIORANGE_LA');?></td>
                                        <td><input class="methods_checkbox" type="checkbox" name="tfa_method_allowed_OOC" <?php echo ($active2FA['OOC']['active'])? 'checked=true' :'' ?>  <?php echo $inlineDisabled ?>/>&emsp;<?php echo JText::_('COM_MINIORANGE_OOC');?></td>
                                        <td></td>                        
                                    </tr>
                                    <tr>
                                        <td><input class="methods_checkbox " type="checkbox" name="tfa_method_allowed_DUO"  <?php echo ($active2FA['DUO']['active'])? 'checked=true' :'' ?> />&emsp;<?php echo JText::_('COM_MINIORANGE_DA');?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mo_boot_col-sm-12 mo_boot_my-3 mo_boot_text-center;" style="padding-right:80px;">
                        <?php
                        if( $isCustomerRegistered && $isFirstUser ){ ?>
                            <input type="submit" name="submit_login_settings" value="<?php echo JText::_('COM_MINIORANGE_VAL_SAVE');?>" class="mo_tfa_input_submit mo_boot_btn mo_boot_btn-primary" style="background-color:#074583" <?php echo $featureDisable ?>>
                        <?php }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
 }

function UserManagement()
{
    $isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
    $featureDisable='';
    if( !$isCustomerRegistered ){
        $featureDisable='disabled';
    }
    $details= commonUtilitiesTfa::getCustomerDetails();
    $groups = commonUtilitiesTfa::loadGroups();
    $inlineDisabled='';
    if((($details['license_type']=='') && ($details['license_type'])=='')){
        $inlineDisabled='disabled';
    }
    $base_url = JUri::base().'index.php?option=com_miniorange_twofa&task=setup_two_factor.joomlapagination';
    ?>
    <div class="mo_boot_row mo_boot_m-1">
        <div class="mo_boot_col-sm-12"><?php
            if( !commonUtilitiesTfa::isCustomerRegistered() ){
                echo  '<div class="mo_register_message">'.JText::_("COM_MINIORANGE_SETUP_TFA_MSG").' <a href="'. JRoute::_('index.php?option=com_miniorange_twofa&tab-panel=account_setup').'" >'.JText::_("COM_MINIORANGE_REGISTER_MSG").'</a> '.JText::_("COM_MINIORANGE_SETUP_TFA_MSG1").'</div>';
            } 
            ?>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-12  mo_boot_text-center"><br>
                    <h3><?php echo JText::_('COM_MINIORANGE_USER_MANAGE');?></h3>
                    <hr>
                </div>
                <div class="mo_boot_col-sm-12 ">
                    <div class="alert alert-secondary " style="padding:3px;">    
                        <p style="padding-left: 10px;">
                            <strong><?php echo JText::_('COM_MINIORANGE_NOTE');?></strong><?php echo JText::_('COM_MINIORANGE_RESET2FA');?><strong><?php echo JText::_('COM_MINIORANGE_RESET_DESC');?></strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="card w-100" style="0 -2px 10px -7px">
                <div class="card-body" style="padding:0rem 1rem">
                    <div class="mo_boot_row mo_boot_mt-2" style="padding-left:2px">
                        <div class=" mo_boot_px-2">
                            <label><strong><?php echo JText::_('COM_MINIORANGE_SHOW_PAGES');?><br></strong>
                            <select id="select_number" class=" mo_boot_filter-input" style="width:fit-content !important" min="10" onchange="list_of_entry()" <?php echo $inlineDisabled ?> >
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            </label>
                        </div>
                        <div class="mo_boot_col-sm-3 mo_boot_px-2">
                            <label><strong><?php echo JText::_('COM_MINIORANGE_FILTER_USERNAME_EMAIL');?></strong>
                            <input type="text" id="search_name" name="search_name" class=" mo_boot_filter-input" size="30" placeholder="<?php echo JText::_('COM_MINIORANGE_SEARCH');?>" <?php echo $inlineDisabled ?>>
                            </label>
                        </div>
                        <div class="mo_boot_col-sm-2 mo_boot_px-2">
                            <label><strong><?php echo JText::_('COM_MINIORANGE_FILTER_ROLES');?></strong>
                            <select type="text" id="search_role" name="search_role" class=" mo_boot_filter-input" placeholder="Search" <?php echo $inlineDisabled ?>>
                                <option value="any" selected><?php echo JText::_('COM_MINIORANGE_VAL_ANY');?></option>
                                <?php
                                foreach($groups as $key=>$value)
                                {
                                    echo '<option value="'.$value['title'].'">'.$value['title'].'</option>';
                                }
                                ?>
                            </select>
                            </label>
                        </div>
                        <div class="mo_boot_col-sm-2 mo_boot_px-2">
                            <label><strong><?php echo JText::_('COM_MINIORANGE_FILTER_STATUS');?></strong>
                            <select type="text" id="search_status" name="search_status" class="mo_boot_w-100 mo_boot_filter-input" placeholder="Search" <?php echo $inlineDisabled ?>>
                                <option value="any" selected><?php echo JText::_('COM_MINIORANGE_VAL_ANY');?></option>
                                <option value="active" >Active</option>
                                <option value="inactive" >Inactive</option>  
                            </select>
                            </label>
                        </div>
                        <div class="mo_boot_filter mo_boot_px-2" style="">
                            <input type="button" name="filter_search"  class="mo_boot_btn mo_boot_btn-reset" value="<?php echo JText::_('COM_MINIORANGE_VAL_FILTER');?>" onclick="searchFilter()" <?php echo $inlineDisabled ?> >
                        </div>
                        <div class=" mo_boot_filter mo_boot_px-2" style="">
                            <input type="button" name="reset_filters" class="mo_boot_btn mo_boot_btn-reset"  value="<?php echo JText::_('COM_MINIORANGE_RESET_FILTER');?>" onclick="resetFilters()" <?php echo $inlineDisabled ?> >
                        </div>
                        <div class="mo_boot_filter mo_boot_px-2" style="">
                            <button name="refresh_page" class="mo_boot_btn mo_boot_btn-reset" value="" onclick="refreshFilters()" <?php echo $inlineDisabled ?> ><i class="fa fa-refresh" aria-hidden="true"></i></button>
                            </div>
                    </div><br>
                </div>
            </div>
            <div id="show_paginations" class="mo_boot_mt-2">   
            </div>
            <input class ="pager" type="hidden" id="next_page" value="0"><br>
            <div class="mo_boot_col-sm-12" style="margin-bottom:3rem">
                <div id="next_btn">
                    <a href="#" onclick="next_or_prev_page('next', 'preserve');" aria-label="Next" style="float: right;color:darkslategrey;">
                        <span aria-hidden="true"><u><?php echo JText::_('COM_MINIORANGE_NEXT_PAGE');?></u>&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                    </a>
                </div>
                <div id="pre_btn">
                    <a href="#" onclick="next_or_prev_page('pre', 'preserve');" aria-label="Next" style="color:darkslategrey;">
                        <span aria-hidden="true"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;<u><?php echo JText::_('COM_MINIORANGE_PREVIOUS_PAGE');?></u></span>
                    </a>
                </div>
            </div>
        </div><br>
        <script>
            var no_of_entry="10";
            jQuery(document).ready(function (){
                next_or_prev_page('next');
            });
            function getValue(num)
            {
                let text = "The user must go through the inline process if TFA is reset. Do you really wish to revert this user's TFA?";
                var value_email =jQuery("#reset_email"+num).val();
                var value_name =jQuery("#reset_username"+num).val();
                if (confirm(text) == true) 
                {
                    jQuery("#form_user"+num).submit();
                } 
                else 
                {
                    return false;
                }
            }
            function refreshFilters() 
            {
                location.reload();
            }
            function refreshFilters() 
            {
                $.ajax({
                    url: '<?php echo $base_url; ?>',
                    method: 'GET',
                    success: function(response) {
                        // Update the content on the page with the new data
                        $('#show_paginations').html(response);
                    },
                    error: function() {
                        console.log('An error occurred during AJAX request.');
                    }
                });
            }
            function list_of_entry()
            {
                no_of_entry=jQuery("#select_number").val();
                next_or_prev_page('on');
            }
            function sort(button)
            {
                var order ="";
                if(clock)
                {
                    clock = 0;
                    order = 'up';
                }
                else
                {
                    clock = 1;
                    order = 'down';
                }
                next_or_prev_page(button,order,clock);
            }
            function searchFilter() {
                var name_flag=0;
                var value_name =jQuery("#search_name").val().toLowerCase();
                var role_flag =0;
                var value_role =jQuery("#search_role").val().toLowerCase();
                var status_flag=0;
                var value_status =jQuery("#search_status").val().toLowerCase().trim();
                jQuery("#Tfa_table tbody tr").filter(function() 
                {        
                    jQuery(this).toggle(
                        (value_name == '' || $(this).text().toLowerCase().indexOf(value_name) > -1) && 
                        (value_role== 'any' || $(this).text().toLowerCase().indexOf(value_role) > -1) &&
                        (value_status=='any' || $(this).find('.mo_btn-status').text().toLowerCase().trim() === value_status))
                        document.getElementById("tfa_entries").style.display = "none";
                });
            }
            function resetFilters()
            {
                document.querySelector('input[name="search_name"]').value = '';
                document.querySelector('select[name="search_role"]').value = 'any';
                document.querySelector('select[name="search_status"]').value = 'any';
                var value_name =jQuery("#search_name").val().toLowerCase();
                var value_role =jQuery("#search_role").val().toLowerCase();
                var value_status =jQuery("#search_status").val().toLowerCase();
                jQuery("#Tfa_table tbody tr").filter(function() {
                    jQuery(this).toggle(
                        (value_name == '' || jQuery(this).text().toLowerCase().indexOf(value_name) > -1) && 
                        (value_role== 'any' || jQuery(this).text().toLowerCase().indexOf(value_role) > -1) &&
                        (value_status=='any' || jQuery(this).text().toLowerCase().indexOf(value_status) > -1)
                    )
                });
                document.getElementById("tfa_entries").style.display = "block";
            }            
            function next_or_prev_page(button, order='down',clock=0) 
            {
                var page = document.getElementById('next_page').value;
                var orderBY='down';
                no_of_entry=jQuery("#select_number").val();
                if(button =='on')
                    page=0;
                if(order == 'up')
                    orderBY='up';
                if(order == 'preserve')
                    orderBY ='down';
                page = parseInt(page);
                if (button == 'pre' && page != 0) {
                    page -= 2;
                    document.getElementById('next_page').value = page;
                    document.getElementById('next_btn').style.display = "inline";
                }
                if (page == 0) {
                    document.getElementById('pre_btn').style.display = "none";
                    document.getElementById('next_btn').style.display = "inline";
                }
                else
                    document.getElementById('pre_btn').style.display = "inline";
                
                jQuery.ajax({
                    url: '<?php echo $base_url; ?>',
                    dataType: "text",
                    method: "POST",
                    data: {'page': page, 'orderBY':orderBY, 'no_of_entry':no_of_entry},
                    success: function (data) {    
                        if(data.search('form-login')!== -1 || data.search('com_login')!== -1)
                        {
                            window.location.reload();
                        }
                        var arr = data.split("separator_for_count");
                        jQuery("#show_paginations").html(arr[0]);
                        if (arr[1] == 0) 
                        {
                            document.getElementById('next_page').value = 0;
                            next_or_prev_page('next','preserve');
                        }
                    }
                });
                page += 1;
                document.getElementById('next_page').value = page;
            }
        </script>
    </div>
<?php
}
function popup()
{
    $settings = commonUtilitiesTfa::getTfaSettings();
    $current_user     = JFactory::getUser();
    $isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
    $isFirstUser          = commonUtilitiesTfa::isFirstUser($current_user->id);
    $details= commonUtilitiesTfa::getCustomerDetails();

    $inlineDisabled='';
    if((($details['license_type']=='') && ($details['license_type'])=='')){
        $inlineDisabled='disabled';
    }
    $featureDisable='';
    if( !$isCustomerRegistered ){
        $featureDisable='disabled';
    }
    $CustomCssSaved  = isset($settings['customFormCss']) && !empty($settings['customFormCss'])
    ? $settings['customFormCss']
    : "";
    
    $CustomButtonSaved = isset($settings['primarybtnCss']) && !empty($settings['primarybtnCss'])
    ? $settings['primarybtnCss']
    : array();

    $customCssSaved =explode(";",$CustomCssSaved);
    $fields = array();
    foreach($customCssSaved as $key=>$value)
    {
        $breakCss =explode(":",$value);
        $fields[$breakCss[0]] = isset($breakCss[1])?$breakCss[1]:"";
    }

    $border = isset($fields['border-top'])?explode(" ",$fields['border-top']):"";
    
    if(is_array($border))
    {
        $border0= $border['0'];
        $border1= $border['2'];
    }
    else{
        $border0 = "";
        $border1 = "";
    }

    ?>
    <div class="mo_boot_row mo_boot_m-1">
        <div class="mo_boot_col-sm-12">
            <?php
                if( !commonUtilitiesTfa::isCustomerRegistered() ){
                    echo  '<div class="mo_register_message">'.JText::_("COM_MINIORANGE_SETUP_TFA_MSG").' <a href="'. JRoute::_('index.php?option=com_miniorange_twofa&tab-panel=account_setup').'" >'.JText::_("COM_MINIORANGE_REGISTER_MSG").'</a> '.JText::_("COM_MINIORANGE_SETUP_TFA_MSG1").'</div>';
                } 
            ?>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-3 mo_boot_text-center ">
            <h3><?php echo JText::_('COM_MINIORANGE_POPUPS');?></h3>
            <hr>
        </div>                
        <div class="mo_boot_col-sm-6 mo_boot_mb-3">
            <form name="" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&task=setup_two_factor.updateCssConfig');?>" id="previewCSS">
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-4">
                        <strong><?php echo JText::_('COM_MINIORANGE_POPUP_MARGIN');?></strong>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input type="number" <?php echo $featureDisable ?> <?php echo $inlineDisabled ?> class="mo_boot_form-control" name="margin" id="margin" min="0" value="<?php echo !empty($border0)?(int)$border0:5; ?>">
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-4">
                        <strong><?php echo JText::_('COM_MINIORANGE_POPUP_RADIUS');?></strong>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input type="number" <?php echo $featureDisable ?> <?php echo $inlineDisabled ?> class="mo_boot_form-control" name="radius" id="radius" min="0" value="<?php echo isset($fields['border-radius'])?(int)$fields['border-radius']:8;?>">
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-4">
                        <strong><?php echo JText::_('COM_MINIORANGE_POPUP_BGCOLOR');?></strong>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input type="color" <?php echo $featureDisable ?> <?php echo $inlineDisabled ?> class="mo_boot_form-control" name="bgcolor" id="bgcolor" value="<?php echo isset($fields['background-color'])?strtok($fields['background-color'],'!'):"#FFFFFF";?>">
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-4">
                        <strong> <?php echo JText::_('COM_MINIORANGE_POPUP_BORDERCOLOR');?></strong>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input type="color" <?php echo $featureDisable ?> <?php echo $inlineDisabled ?> class="mo_boot_form-control" name="bordertop" id="bordertop" value="<?php echo !empty($border1)?(strtok($border1,'!')):"#20b2aa"; ?>">
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-4">
                        <strong><?php echo JText::_('COM_MINIORANGE_POPUP_BUTTONCOLOR');?></strong>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input type="color" <?php echo $featureDisable ?> <?php echo $inlineDisabled ?> class="mo_boot_form-control" name="primarybtn" id="primarybtn" value="<?php echo !empty($CustomButtonSaved)?strtok($CustomButtonSaved,'!'):"#2384d3"; ?>">
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-4">
                        <strong><?php echo JText::_('COM_MINIORANGE_POPUP_HEIGHT');?></strong>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input type="number" <?php echo $featureDisable ?> <?php echo $inlineDisabled ?> class="mo_boot_form-control" name="height" id="height" value= "<?php echo isset($fields['min-height'])?(int)$fields['min-height']:"200";?>">
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2 mo_boot_text-center">
                    <div class="mo_boot_col-sm-12" style="border-left-width: 5px;margin-left: 10px;margin-right: 10px;">
                        <?php  
                            if( $isCustomerRegistered && $isFirstUser )
                            {
                                ?>
                                <input id="css_submit" <?php echo $inlineDisabled ?> type="submit" class="mo_boot_btn mo_boot_btn-primary_two" style="margin-right: 5px;" value="<?php echo JText::_('COM_MINIORANGE_VAL_SUBMIT');?>">
                                <input type="button" id="css_reset" <?php echo $inlineDisabled ?>  class="mo_boot_btn mo_boot_btn-danger" value="<?php echo JText::_('COM_MINIORANGE_VAL_RESET_CSS');?>">
                                <?php 
                            }
                        ?>
                    </div>
                </div>
            </form>
        </div>
        <div class="mo_boot_col-sm-6 mo_boot_mb-3">
            <div class="mo_boot_container-fluid mo_boot_text-center" style="background-color:#f8f8f8;min-height:100%!important;">
                <div class="mo_boot_row">
	                <div class="mo_boot_col-sm-12 mo_boot_offset-sm-12">
                        <?php 
                            if($featureDisable=='disabled')
                            {
                                ?>
                                <p><?php echo JText::_('COM_MINIORANGE_PREVIEW');?></p>
                                <?php
                            }
                            elseif($featureDisable != 'disabled')
                            {
                                ?>
				                <div class="mo_boot_row mo_tfa_main" id="previewform" >
                                    <div class="mo_boot_col-sm-12 mo_tfa_title" style="border-bottom:0px">
						                <span><?php echo JText::_('COM_MINIORANGE_AUTHENTICATE');?></span>
					                </div>    
                                    <div class="mo_boot_col-sm-12 mo_boot_mt-3">
						                <form name="f" method="post" action="">
                                            <div class="mo_boot_row mo_boot_mb-3">
									            <div class="mo_boot_col-sm-10 mo_boot_mx-4">
                                                    <div class="mo_boot_p-1 mo_boot_alert-secondary mo_tfa_text"><span><?php echo JText::_('COM_MINIORANGE_AUTHENTICATE_PASSCODE');?> <strong>Google <?php echo JText::_('COM_MINIORANGE_AUTHENTICATOR_APP');?></span></div>
                                                </div>
                                            </div>
                                            <div class="mo_boot_row">
                                                <div class="mo_boot_col-sm-10 mo_boot_mx-4">
                                                    <input type="text" class="input mo_boot_form-control mo_tfa_text" name="passcode" placeholder="<?php echo JText::_('COM_MINIORANGE_VAL_PASSCODE');?>" disabled/>
                                                </div>
                                            </div>
                                            <div class="mo_boot_row mo_boot_mx-2">
                                                <div class="mo_boot_col-sm-12 mo_boot_text-left mo_boot_mt-1" >
                                                    <input style=" cursor: pointer;" type="checkbox" name="reconfigure_2fa" />&nbsp;<span  style="font-weight:500;color:dimgrey;font-family: ui-sans-serif;"><?php echo JText::_('COM_MINIORANGE_REMEMBER_DEVICE');?></span><br><br>
                                                </div>
                                            </div>
                                            <div class="mo_boot_row mo_boot_m-2 ">
                                                <div class="mo_boot_col-sm-6">
                                                    <a class="mo_boot_forgot_phn" style="color:#377eff;font-family: ui-sans-serif;float:left;" ><?php echo JText::_('COM_MINIORANGE_FORGOT_PHONE');?></a>
                                                </div>
                                                <div class="mo_boot_col_sm-5 mo_boot_mx-2">
                                                    <input type="button" id="previewbutton1"  name="validate_passcode" class="mo_boot_btn mo_boot_btn-primary" style="margin-right: 3px;font-family: ui-sans-serif;" value="<?php echo JText::_('COM_MINIORANGE_VALIDATE');?>"  />
                                                    <input type="button"  style="margin-right: 3px;font-family: ui-sans-serif;" name="Start_registration" class="mo_boot_btn mo_boot_btn-reset" value="<?php echo JText::_('COM_MINIORANGE_CANCEL');?>" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            jQuery(document).change(function()
            {
                CustomCss();
            });
            jQuery(document).ready(function()
            {
                CustomCss();
            });
            function CustomCss()
            {
                var radius=jQuery('#radius').val();
                var margin = jQuery('#margin').val();
                var bgcolor = jQuery('#bgcolor').val();
                var bordertop = jQuery('#bordertop').val();
                var borderbottom = jQuery('#borderbottom').val();
                var primarybtn = jQuery('#primarybtn').val();
                var height = jQuery('#height').val();
                var customcss="";
                var custombtn="";
                custombtn +="background-color:"+primarybtn;
                customcss += "border-radius:"+radius+"px;background-color:"+bgcolor+";border-top:"+margin+"px solid "+bordertop+";border-bottom:"+margin+"px "+"solid "+bordertop+";min-height:"+height+"px;"+"width:90% !important;";
                jQuery("#previewform").attr("style",customcss);
                jQuery("#previewbutton").attr("style",custombtn);
                jQuery("#previewbutton1").attr("style",custombtn);
            }
            jQuery(document).ready(function()
            {
                jQuery('#css_reset').click(function()
                {
                    document.getElementById("margin").value = 5;
                    document.getElementById("radius").value = 8;
                    document.getElementById("bgcolor").value = "#FFFFFF";
                    document.getElementById("bordertop").value = "#20b2aa";
                    document.getElementById("primarybtn").value = "#2384d3";
                    document.getElementById("height").value = 200;
                    jQuery("#previewCSS").submit();
                });    
            });
        </script>
    </div>
    <?php
}


function support()
{
    $user = JFactory::getUser();
    $admin_email = $user->email;
    ?>
    <div class="mo_boot_row mo_boot_m-1">
        <div class="mo_boot_col-sm-12 mo_boot_mt-3 mo_boot_text-center">
            <h3>
                <?php echo JText::_('COM_MINIORANGE_SUPPORT_FEATURE');?>
                <span style="float:right;" id="mini-icons">
                    <a href="https://faq.miniorange.com/" target="_blank" class="mo_boot_btn mo_boot_btn-success mo_boot_py-1">FAQ's</a>
                </span>
            </h3>
            <hr>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <details class="mo_details" open>
                <summary><?php echo JText::_('COM_MINIORANGE_SUPPORT');?></summary>
                <br>
                <div class="mo_boot_row mo_boot_m-2">
                    <?php
                        $arrContextOptions = array(
                            "ssl" => array(
                                "verify_peer" => false,
                                "verify_peer_name" => false,
                            )
                        );  
                        $context = stream_context_create($arrContextOptions);
                        $details=commonUtilitiesTfa::getCustomerDetails();
                        $strJsonTime = file_get_contents(JURI::root()."/administrator/components/com_miniorange_twofa/assets/json/timezones.json",false,$context);
                        $timezoneJsonArray = json_decode($strJsonTime, true);
                    ?>
                    <div class="mo_boot_col-sm-12">
                        <form name="f" class="mo_tfa_SupportForm" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&task=setup_two_factor.support'); ?>">
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-12 mo_boot_text-center mo_boot_mb-2">
                                    <p><?php echo JText::_('COM_MINIORANGE_NEED_HELP');?></p>
                                </div>
                            </div>
                            <div class="mo_boot_row" style="padding-bottom:15px;">
                                <div class="mo_boot_col-sm-2">
                                    <strong><?php echo JText::_('COM_MINIORANGE_EMAIL');?><span style="color:#FF0000;">*</span>&nbsp;</strong>
                                </div>
                                <div class="mo_boot_col-sm-8">
                                    <input type="email" name="email" placeholder="<?php echo JText::_('COM_MINIORANGE_VAL_ENTER_EMAIL');?>" class=" mo_boot_form-control " value="<?php echo $admin_email ?>" required="true"/>
                                </div>
                            </div>
                            <div class="mo_boot_row" style="margin-bottom:15px;">
                                <div class="mo_boot_col-sm-2">
                                    <strong><?php echo JText::_('COM_MINIORANGE_PHONE');?>&nbsp;</strong>
                                </div>
                                <div class="mo_boot_col-sm-8">
                                    <input type="tel" name="phone" pattern="[\+]\d{11,14}|[\+]\d{1,4}([\s]{0,1})(\d{0}|\d{9,10})" class="mo_tfa_query_phone mo_boot_form-control " id="mo_tfa_query_phone" placeholder="<?php echo JText::_('COM_MINIORANGE_PHONE_MSG');?>" value="" />
                                </div>
                            </div>
                            <div class="mo_boot_row" style="margin-bottom:15px;">
                                <div class="mo_boot_col-sm-2">
                                    <strong><?php echo JText::_('COM_MINIORANGE_QUERY');?><span style="color:#FF0000;">*</span>&nbsp;</strong>
                                </div>
                                <div class="mo_boot_col-sm-8">
                                    <textarea cols="52" rows="4" name="query" placeholder="<?php echo JText::_('COM_MINIORANGE_VAL_QUERY');?>" required="true" class="mo_support_input mo_boot_p-1" style="width: 100%!important;border-radius: 4px !important;"></textarea>
                                </div>
                            </div>
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-12 mo_boot_text-center">
                                    <input type="submit" value="<?php echo JText::_('COM_MINIORANGE_VAL_SUBMIT_QUERY');?>" name="submit_query" class="mo_boot_btn" style="background-color:#2b4774;color:white">
                                </div>
                            </div>
                        </form>    
                    </div>
                </div>
            </details>
            <details class="mo_details">
                <summary><?php echo JText::_('COM_MINIORANGE_QUOTE');?></summary>
                <br>
                <div class="mo_boot_row mo_boot_m-2">
                    <?php
                        $arrContextOptions = array(
                            "ssl" => array(
                                "verify_peer" => false,
                                "verify_peer_name" => false,
                            )
                        );  
                        $context = stream_context_create($arrContextOptions);
                        $details=commonUtilitiesTfa::getCustomerDetails();
                        $strJsonTime = file_get_contents(JURI::root()."/administrator/components/com_miniorange_twofa/assets/json/timezones.json",false,$context);
                        $timezoneJsonArray = json_decode($strJsonTime, true);
                    ?>
                    <div class="mo_boot_col-sm-12">
                        <form name="f" class="mo_tfa_SupportForm" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&task=setup_two_factor.requestQuote'); ?>">
                            <div class="mo_boot_row" style="margin-bottom:15px;">
                                <div class="mo_boot_col-sm-3">
                                    <strong><?php echo JText::_('COM_MINIORANGE_METHODS');?></strong><span style="color:#FF0000;">*</span>
                                </div>
                                <div class="mo_boot_col-sm-8">
                                    <select id="type_service" name="type_service" class="mo_boot_form-control mo_boot_text-center" required>
                                        <option disabled selected>--------<?php echo JText::_('COM_MINIORANGE_METHODSELECT');?>--------</option>
                                        <option id="google_auth" value="google_auth" ><?php echo JText::_('COM_MINIORANGE_GA');?></option>
                                        <option id="microsoft_auth" value="microsoft_auth"><?php echo JText::_('COM_MINIORANGE_MA');?></option>
                                        <option id="LPA" value="LPA"><?php echo JText::_('COM_MINIORANGE_LA');?></option>
                                        <option id="AA" value="AA"><?php echo JText::_('COM_MINIORANGE_AA');?></option>
                                        <option id="duo_auth" value="duo_auth"><?php echo JText::_('COM_MINIORANGE_DA');?></option>
                                        <option id="sms" value="SMS"><?php echo JText::_('COM_MINIORANGE_OOS');?></option>
                                        <option id="email" value="Email"><?php echo JText::_('COM_MINIORANGE_OOE');?>l</option>
                                        <option id="OOSE" value="OOSE"><?php echo JText::_('COM_MINIORANGE_OOSE');?></option>
                                        <option id="kba" value="kba"><?php echo JText::_('COM_MINIORANGE_SECURITY_QUES');?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="mo_boot_row" style="margin-bottom:15px;">
                                <div class="mo_boot_col-sm-3">
                                    <strong><?php echo JText::_('COM_MINIORANGE_EMAIL');?></strong><span style="color:#FF0000;">*</span>
                                </div>
                                <div class="mo_boot_col-sm-8" >
                                    <input type="email" name="email" class="mo_boot_form-control" value="<?php echo $admin_email; ?>" placeholder="<?php echo JText::_('COM_MINIORANGE_VAL_ENTER_EMAIL');?>">
                                </div>
                            </div>
                            <div class="mo_boot_row" style="margin-bottom:15px;">
                                <div class="mo_boot_col-sm-3">
                                    <strong><?php echo JText::_('COM_MINIORANGE_USERS_NO');?></strong><span style="color:#FF0000;">*</span>
                                </div>
                                <div class="mo_boot_col-sm-8" >
                                    <input type="number" name="no_of_users" class="mo_boot_form-control" value="" min="10" placeholder="10">
                                </div>
                            </div>
                            <div class="mo_boot_row" style="display: none" id="no_of_otp">
                                <div class="mo_boot_col-sm-3">
                                    <strong><?php echo JText::_('COM_MINIORANGE_OTP_NO');?></strong><span style="color:#FF0000;">*</span>
                                </div>
                                <div class="mo_boot_col-sm-8" style="padding-bottom:10px;">
                                    <input type="number" name="no_of_otp" class="mo_boot_form-control" pattern="^[1-9][0-9]*$" placeholder="<?php echo JText::_('COM_MINIORANGE_VAL_OTP_NO');?>">
                                </div>
                            </div>
                            <div class="mo_boot_row" id="type_country" style="display: none">
                                <div class="mo_boot_col-sm-3">
                                    <strong><?php echo JText::_('COM_MINIORANGE_COUNTRY');?></strong><span style="color:#FF0000;">*</span>
                                </div>
                                <div class="mo_boot_col-sm-8" style="padding-bottom:10px;">
                                    <select  name="select_country" id="select_country" class="mo_boot_form-control">
                                        <option disabled selected>--------<?php echo JText::_('COM_MINIORANGE_TYPESELECT');?>--------</option>
                                        <option value="allcountry"><?php echo JText::_('COM_MINIORANGE_ALLCOUNTRIES');?></option>
                                        <option value="singlecountry"><?php echo JText::_('COM_MINIORANGE_SINGLECOUNTRIES');?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="mo_boot_row" id="select_type_country" style="display: none">
                                <div class="mo_boot_col-sm-3">
                                    <strong><?php echo JText::_('COM_MINIORANGE_SELECT_COUNTRY');?></strong><span style="color:#FF0000;">*</span>
                                </div>
                                <div class="mo_boot_col-sm-8" style= "margin-bottom:10px;">
                                    <select  class="mo_boot_form-control" data-size="8" name="which_country" id="which_country" data-live-search="true">
                                        <option style="" value="default" disabled selected><?php echo JText::_('COM_MINIORANGE_SELECT_COUNTRY1');?></option>
                                        <?php
                                            $countries= countryList();
                                            foreach($countries as $data)
                                            {
                                                if($data['name']!="All Countries")
                                                    echo "<option value='".$data['name']."'>".$data['name']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-3">
                                    <strong><?php echo JText::_('COM_MINIORANGE_QUERY');?></strong> <span style="color:#FF0000;">*</span>
                                </div>
                                <div class="mo_boot_col-sm-8">
                                    <textarea name="user_extra_requirement" cols="30" rows="5" style="width:100%;border-color:#333333;" placeholder="--<?php echo JText::_('COM_MINIORANGE_VAL_REQUIREMENT');?>--"></textarea>
                                </div>
                            </div>
                            <div class="mo_boot_row mo_boot_text-center">
                                <div class="mo_boot_col-sm-12">
                                    <input type="submit" value="<?php echo JText::_('COM_MINIORANGE_VAL_SUBMIT');?>" class="mo_boot_btn " style="background-color:#2b4774;color:white" style="margin-bottom: 10px;">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </details>
        </div>
    </div>
    <script>
        jQuery(document).ready(function(){
            var dtToday = new Date();
            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();
            
            var maxDate = year + '-' + month + '-' + day;
            jQuery('#setTodaysDate').attr('min', maxDate);
        });
    
        function show_setup_call() 
        {
            jQuery("#support_form").hide();
            jQuery("#request_quote_form").hide();
            jQuery("#setup_call_form").show();
        }
        
        function hide_setup_call() 
        {
            jQuery("#support_form").show();
            jQuery("#setup_call_form").hide();
            jQuery("#request_quote_form").hide();
        }
        function show_quote()
        {
            jQuery("#support_form").hide();
            jQuery("#setup_call_form").hide();
            jQuery("#request_quote_form").show();
        }
        jQuery('#type_service').change(function()
        {
            jQuery('#select_type_country').css('display','none');
            if(jQuery(this).val()==="SMS")
            {
                jQuery('#type_country').css('display','');
                jQuery('#no_of_otp').css('display','');
            }
            else if(jQuery(this).val()==="Email")
            {
                jQuery('#type_country').css('display','none');
                jQuery('#no_of_otp').css('display','');
            }
            else if(jQuery(this).val()==="OOSE")
            {
                jQuery('#type_country').css('display','');
                jQuery('#no_of_otp').css('display','');
            }
            else {
                jQuery('#type_country').css('display','none');
                jQuery('#no_of_otp').css('display','none');
                jQuery('#singlecountry').prop('selected',false);
            }
        });
        jQuery('select').change(function(){
            if(jQuery(this).val()==="singlecountry")
            {
                jQuery('#select_type_country').css('display','');
            }
        });
    </script>
    <?php
}

function countryList()
{
        $countries = array(
            array('name' => 'All Countries',
                'alphacode' => '',
                'countryCode' => ''
            ),
            array(
                'name' => 'Afghanistan (‫افغانستان‬‎)',
                'alphacode' => 'af',
                'countryCode' => '+93'
            ),
            array(
                'name' => 'Albania (Shqipëri)',
                'alphacode' => 'al',
                'countryCode' => '+355'
            ),
            array(
                'name' => 'Algeria (‫الجزائر‬‎)',
                'alphacode' => 'dz',
                'countryCode' => '+213'
            ),
            array(
                'name' => 'American Samoa',
                'alphacode' => 'as',
                'countryCode' => '+1684'
            ),
            array(
                'name' => 'Andorra',
                'alphacode' => 'ad',
                'countryCode' => '+376'
            ),
            array(
                'name' => 'Angola',
                'alphacode' => 'ao',
                'countryCode' => '+244'
            ),
            array(
                'name' => 'Anguilla',
                'alphacode' => 'ai',
                'countryCode' => '+1264'
            ),
            array(
                'name' => 'Antigua and Barbuda',
                'alphacode' => 'ag',
                'countryCode' => '+1268'
            ),
            array(
                'name' => 'Argentina',
                'alphacode' => 'ar',
                'countryCode' => '+54'
            ),
            array(
                'name' => 'Armenia (Հայաստան)',
                'alphacode' => 'am',
                'countryCode' => '+374'
            ),
            array(
                'name' => 'Aruba',
                'alphacode' => 'aw',
                'countryCode' => '+297'
            ),
            array(
                'name' => 'Australia',
                'alphacode' => 'au',
                'countryCode' => '+61'
            ),
            array(
                'name' => 'Austria (Österreich)',
                'alphacode' => 'at',
                'countryCode' => '+43'
            ),
            array(
                'name' => 'Azerbaijan (Azərbaycan)',
                'alphacode' => 'az',
                'countryCode' => '+994'
            ),
            array(
                'name' => 'Bahamas',
                'alphacode' => 'bs',
                'countryCode' => '+1242'
            ),
            array(
                'name' => 'Bahrain (‫البحرين‬‎)',
                'alphacode' => 'bh',
                'countryCode' => '+973'
            ),
            array(
                'name' => 'Bangladesh (বাংলাদেশ)',
                'alphacode' => 'bd',
                'countryCode' => '+880'
            ),
            array(
                'name' => 'Barbados',
                'alphacode' => 'bb',
                'countryCode' => '+1246'
            ),
            array(
                'name' => 'Belarus (Беларусь)',
                'alphacode' => 'by',
                'countryCode' => '+375'
            ),
            array(
                'name' => 'Belgium (België)',
                'alphacode' => 'be',
                'countryCode' => '+32'
            ),
            array(
                'name' => 'Belize',
                'alphacode' => 'bz',
                'countryCode' => '+501'
            ),
            array(
                'name' => 'Benin (Bénin)',
                'alphacode' => 'bj',
                'countryCode' => '+229'
            ),
            array(
                'name' => 'Bermuda',
                'alphacode' => 'bm',
                'countryCode' => '+1441'
            ),
            array(
                'name' => 'Bhutan (འབྲུག)',
                'alphacode' => 'bt',
                'countryCode' => '+975'
            ),
            array(
                'name' => 'Bolivia',
                'alphacode' => 'bo',
                'countryCode' => '+591'
            ),
            array(
                'name' => 'Bosnia and Herzegovina (Босна и Херцеговина)',
                'alphacode' => 'ba',
                'countryCode' => '+387'
            ),
            array(
                'name' => 'Botswana',
                'alphacode' => 'bw',
                'countryCode' => '+267'
            ),
            array(
                'name' => 'Brazil (Brasil)',
                'alphacode' => 'br',
                'countryCode' => '+55'
            ),
            array(
                'name' => 'British Indian Ocean Territory',
                'alphacode' => 'io',
                'countryCode' => '+246'
            ),
            array(
                'name' => 'British Virgin Islands',
                'alphacode' => 'vg',
                'countryCode' => '+1284'
            ),
            array(
                'name' => 'Brunei',
                'alphacode' => 'bn',
                'countryCode' => '+673'
            ),
            array(
                'name' => 'Bulgaria (България)',
                'alphacode' => 'bg',
                'countryCode' => '+359'
            ),
            array(
                'name' => 'Burkina Faso',
                'alphacode' => 'bf',
                'countryCode' => '+226'
            ),
            array(
                'name' => 'Burundi (Uburundi)',
                'alphacode' => 'bi',
                'countryCode' => '+257'
            ),
            array(
                'name' => 'Cambodia (កម្ពុជា)',
                'alphacode' => 'kh',
                'countryCode' => '+855'
            ),
            array(
                'name' => 'Cameroon (Cameroun)',
                'alphacode' => 'cm',
                'countryCode' => '+237'
            ),
            array(
                'name' => 'Canada',
                'alphacode' => 'ca',
                'countryCode' => '+1'
            ),
            array(
                'name' => 'Cape Verde (Kabu Verdi)',
                'alphacode' => 'cv',
                'countryCode' => '+238'
            ),
            array(
                'name' => 'Caribbean Netherlands',
                'alphacode' => 'bq',
                'countryCode' => '+599'
            ),
            array(
                'name' => 'Cayman Islands',
                'alphacode' => 'ky',
                'countryCode' => '+1345'
            ),
            array(
                'name' => 'Central African Republic (République centrafricaine)',
                'alphacode' => 'cf',
                'countryCode' => '+236'
            ),
            array(
                'name' => 'Chad (Tchad)',
                'alphacode' => 'td',
                'countryCode' => '+235'
            ),
            array(
                'name' => 'Chile',
                'alphacode' => 'cl',
                'countryCode' => '+56'
            ),
            array(
                'name' => 'China (中国)',
                'alphacode' => 'cn',
                'countryCode' => '+86'
            ),
            array(
                'name' => 'Christmas Island',
                'alphacode' => 'cx',
                'countryCode' => '+61'
            ),
            array(
                'name' => 'Cocos (Keeling) Islands',
                'alphacode' => 'cc',
                'countryCode' => '+61'
            ),
            array(
                'name' => 'Colombia',
                'alphacode' => 'co',
                'countryCode' => '+57'
            ),
            array(
                'name' => 'Comoros (‫جزر القمر‬‎)',
                'alphacode' => 'km',
                'countryCode' => '+269'
            ),
            array(
                'name' => 'Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)',
                'alphacode' => 'cd',
                'countryCode' => '+243'
            ),
            array(
                'name' => 'Congo (Republic) (Congo-Brazzaville)',
                'alphacode' => 'cg',
                'countryCode' => '+242'
            ),
            array(
                'name' => 'Cook Islands',
                'alphacode' => 'ck',
                'countryCode' => '+682'
            ),
            array(
                'name' => 'Costa Rica',
                'alphacode' => 'cr',
                'countryCode' => '+506'
            ),
            array(
                'name' => 'Côte d’Ivoire',
                'alphacode' => 'ci',
                'countryCode' => '+225'
            ),
            array(
                'name' => 'Croatia (Hrvatska)',
                'alphacode' => 'hr',
                'countryCode' => '+385'
            ),
            array(
                'name' => 'Cuba',
                'alphacode' => 'cu',
                'countryCode' => '+53'
            ),
            array(
                'name' => 'Curaçao',
                'alphacode' => 'cw',
                'countryCode' => '+599'
            ),
            array(
                'name' => 'Cyprus (Κύπρος)',
                'alphacode' => 'cy',
                'countryCode' => '+357'
            ),
            array(
                'name' => 'Czech Republic (Česká republika)',
                'alphacode' => 'cz',
                'countryCode' => '+420'
            ),
            array(
                'name' => 'Denmark (Danmark)',
                'alphacode' => 'dk',
                'countryCode' => '+45'
            ),
            array(
                'name' => 'Djibouti',
                'alphacode' => 'dj',
                'countryCode' => '+253'
            ),
            array(
                'name' => 'Dominica',
                'alphacode' => 'dm',
                'countryCode' => '+1767'
            ),
            array(
                'name' => 'Dominican Republic (República Dominicana)',
                'alphacode' => 'do',
                'countryCode' => '+1'
            ),
            array(
                'name' => 'Ecuador',
                'alphacode' => 'ec',
                'countryCode' => '+593'
            ),
            array(
                'name' => 'Egypt (‫مصر‬‎)',
                'alphacode' => 'eg',
                'countryCode' => '+20'
            ),
            array(
                'name' => 'El Salvador',
                'alphacode' => 'sv',
                'countryCode' => '+503'
            ),
            array(
                'name' => 'Equatorial Guinea (Guinea Ecuatorial)',
                'alphacode' => 'gq',
                'countryCode' => '+240'
            ),
            array(
                'name' => 'Eritrea',
                'alphacode' => 'er',
                'countryCode' => '+291'
            ),
            array(
                'name' => 'Estonia (Eesti)',
                'alphacode' => 'ee',
                'countryCode' => '+372'
            ),
            array(
                'name' => 'Ethiopia',
                'alphacode' => 'et',
                'countryCode' => '+251'
            ),
            array(
                'name' => 'Falkland Islands (Islas Malvinas)',
                'alphacode' => 'fk',
                'countryCode' => '+500'
            ),
            array(
                'name' => 'Faroe Islands (Føroyar)',
                'alphacode' => 'fo',
                'countryCode' => '+298'
            ),
            array(
                'name' => 'Fiji',
                'alphacode' => 'fj',
                'countryCode' => '+679'
            ),
            array(
                'name' => 'Finland (Suomi)',
                'alphacode' => 'fi',
                'countryCode' => '+358'
            ),
            array(
                'name' => 'France',
                'alphacode' => 'fr',
                'countryCode' => '+33'
            ),
            array(
                'name' => 'French Guiana (Guyane française)',
                'alphacode' => 'gf',
                'countryCode' => '+594'
            ),
            array(
                'name' => 'French Polynesia (Polynésie française)',
                'alphacode' => 'pf',
                'countryCode' => '+689'
            ),
            array(
                'name' => 'Gabon',
                'alphacode' => 'ga',
                'countryCode' => '+241'
            ),
            array(
                'name' => 'Gambia',
                'alphacode' => 'gm',
                'countryCode' => '+220'
            ),
            array(
                'name' => 'Georgia (საქართველო)',
                'alphacode' => 'ge',
                'countryCode' => '+995'
            ),
            array(
                'name' => 'Germany (Deutschland)',
                'alphacode' => 'de',
                'countryCode' => '+49'
            ),
            array(
                'name' => 'Ghana (Gaana)',
                'alphacode' => 'gh',
                'countryCode' => '+233'
            ),
            array(
                'name' => 'Gibraltar',
                'alphacode' => 'gi',
                'countryCode' => '+350'
            ),
            array(
                'name' => 'Greece (Ελλάδα)',
                'alphacode' => 'gr',
                'countryCode' => '+30'
            ),
            array(
                'name' => 'Greenland (Kalaallit Nunaat)',
                'alphacode' => 'gl',
                'countryCode' => '+299'
            ),
            array(
                'name' => 'Grenada',
                'alphacode' => 'gd',
                'countryCode' => '+1473'
            ),
            array(
                'name' => 'Guadeloupe',
                'alphacode' => 'gp',
                'countryCode' => '+590'
            ),
            array(
                'name' => 'Guam',
                'alphacode' => 'gu',
                'countryCode' => '+1671'
            ),
            array(
                'name' => 'Guatemala',
                'alphacode' => 'gt',
                'countryCode' => '+502'
            ),
            array(
                'name' => 'Guernsey',
                'alphacode' => 'gg',
                'countryCode' => '+44'
            ),
            array(
                'name' => 'Guinea (Guinée)',
                'alphacode' => 'gn',
                'countryCode' => '+224'
            ),
            array(
                'name' => 'Guinea-Bissau (Guiné Bissau)',
                'alphacode' => 'gw',
                'countryCode' => '+245'
            ),
            array(
                'name' => 'Guyana',
                'alphacode' => 'gy',
                'countryCode' => '+592'
            ),
            array(
                'name' => 'Haiti',
                'alphacode' => 'ht',
                'countryCode' => '+509'
            ),
            array(
                'name' => 'Honduras',
                'alphacode' => 'hn',
                'countryCode' => '+504'
            ),
            array(
                'name' => 'Hong Kong (香港)',
                'alphacode' => 'hk',
                'countryCode' => '+852'
            ),
            array(
                'name' => 'Hungary (Magyarország)',
                'alphacode' => 'hu',
                'countryCode' => '+36'
            ),
            array(
                'name' => 'Iceland (Ísland)',
                'alphacode' => 'is',
                'countryCode' => '+354'
            ),
            array(
                'name' => 'India (भारत)',
                'alphacode' => 'in',
                'countryCode' => '+91'
            ),
            array(
                'name' => 'Indonesia',
                'alphacode' => 'id',
                'countryCode' => '+62'
            ),
            array(
                'name' => 'Iran (‫ایران‬‎)',
                'alphacode' => 'ir',
                'countryCode' => '+98'
            ),
            array(
                'name' => 'Iraq (‫العراق‬‎)',
                'alphacode' => 'iq',
                'countryCode' => '+964'
            ),
            array(
                'name' => 'Ireland',
                'alphacode' => 'ie',
                'countryCode' => '+353'
            ),
            array(
                'name' => 'Isle of Man',
                'alphacode' => 'im',
                'countryCode' => '+44'
            ),
            array(
                'name' => 'Israel (‫ישראל‬‎)',
                'alphacode' => 'il',
                'countryCode' => '+972'
            ),
            array(
                'name' => 'Italy (Italia)',
                'alphacode' => 'it',
                'countryCode' => '+39'
            ),
            array(
                'name' => 'Jamaica',
                'alphacode' => 'jm',
                'countryCode' => '+1876'
            ),
            array(
                'name' => 'Japan (日本)',
                'alphacode' => 'jp',
                'countryCode' => '+81'
            ),
            array(
                'name' => 'Jersey',
                'alphacode' => 'je',
                'countryCode' => '+44'
            ),
            array(
                'name' => 'Jordan (‫الأردن‬‎)',
                'alphacode' => 'jo',
                'countryCode' => '+962'
            ),
            array(
                'name' => 'Kazakhstan (Казахстан)',
                'alphacode' => 'kz',
                'countryCode' => '+7'
            ),
            array(
                'name' => 'Kenya',
                'alphacode' => 'ke',
                'countryCode' => '+254'
            ),
            array(
                'name' => 'Kiribati',
                'alphacode' => 'ki',
                'countryCode' => '+686'
            ),
            array(
                'name' => 'Kosovo',
                'alphacode' => 'xk',
                'countryCode' => '+383'
            ),
            array(
                'name' => 'Kuwait (‫الكويت‬‎)',
                'alphacode' => 'kw',
                'countryCode' => '+965'
            ),
            array(
                'name' => 'Kyrgyzstan (Кыргызстан)',
                'alphacode' => 'kg',
                'countryCode' => '+996'
            ),
            array(
                'name' => 'Laos (ລາວ)',
                'alphacode' => 'la',
                'countryCode' => '+856'
            ),
            array(
                'name' => 'Latvia (Latvija)',
                'alphacode' => 'lv',
                'countryCode' => '+371'
            ),
            array(
                'name' => 'Lebanon (‫لبنان‬‎)',
                'alphacode' => 'lb',
                'countryCode' => '+961'
            ),
            array(
                'name' => 'Lesotho',
                'alphacode' => 'ls',
                'countryCode' => '+266'
            ),
            array(
                'name' => 'Liberia',
                'alphacode' => 'lr',
                'countryCode' => '+231'
            ),
            array(
                'name' => 'Libya (‫ليبيا‬‎)',
                'alphacode' => 'ly',
                'countryCode' => '+218'
            ),
            array(
                'name' => 'Liechtenstein',
                'alphacode' => 'li',
                'countryCode' => '+423'
            ),
            array(
                'name' => 'Lithuania (Lietuva)',
                'alphacode' => 'lt',
                'countryCode' => '+370'
            ),
            array(
                'name' => 'Luxembourg',
                'alphacode' => 'lu',
                'countryCode' => '+352'
            ),
            array(
                'name' => 'Macau (澳門)',
                'alphacode' => 'mo',
                'countryCode' => '+853'
            ),
            array(
                'name' => 'Macedonia (FYROM) (Македонија)',
                'alphacode' => 'mk',
                'countryCode' => '+389'
            ),
            array(
                'name' => 'Madagascar (Madagasikara)',
                'alphacode' => 'mg',
                'countryCode' => '+261'
            ),
            array(
                'name' => 'Malawi',
                'alphacode' => 'mw',
                'countryCode' => '+265'
            ),
            array(
                'name' => 'Malaysia',
                'alphacode' => 'my',
                'countryCode' => '+60'
            ),
            array(
                'name' => 'Maldives',
                'alphacode' => 'mv',
                'countryCode' => '+960'
            ),
            array(
                'name' => 'Mali',
                'alphacode' => 'ml',
                'countryCode' => '+223'
            ),
            array(
                'name' => 'Malta',
                'alphacode' => 'mt',
                'countryCode' => '+356'
            ),
            array(
                'name' => 'Marshall Islands',
                'alphacode' => 'mh',
                'countryCode' => '+692'
            ),
            array(
                'name' => 'Martinique',
                'alphacode' => 'mq',
                'countryCode' => '+596'
            ),
            array(
                'name' => 'Mauritania (‫موريتانيا‬‎)',
                'alphacode' => 'mr',
                'countryCode' => '+222'
            ),
            array(
                'name' => 'Mauritius (Moris)',
                'alphacode' => 'mu',
                'countryCode' => '+230'
            ),
            array(
                'name' => 'Mayotte',
                'alphacode' => 'yt',
                'countryCode' => '+262'
            ),
            array(
                'name' => 'Mexico (México)',
                'alphacode' => 'mx',
                'countryCode' => '+52'
            ),
            array(
                'name' => 'Micronesia',
                'alphacode' => 'fm',
                'countryCode' => '+691'
            ),
            array(
                'name' => 'Moldova (Republica Moldova)',
                'alphacode' => 'md',
                'countryCode' => '+373'
            ),
            array(
                'name' => 'Monaco',
                'alphacode' => 'mc',
                'countryCode' => '+377'
            ),
            array(
                'name' => 'Mongolia (Монгол)',
                'alphacode' => 'mn',
                'countryCode' => '+976'
            ),
            array(
                'name' => 'Montenegro (Crna Gora)',
                'alphacode' => 'me',
                'countryCode' => '+382'
            ),
            array(
                'name' => 'Montserrat',
                'alphacode' => 'ms',
                'countryCode' => '+1664'
            ),
            array(
                'name' => 'Morocco (‫المغرب‬‎)',
                'alphacode' => 'ma',
                'countryCode' => '+212'
            ),
            array(
                'name' => 'Mozambique (Moçambique)',
                'alphacode' => 'mz',
                'countryCode' => '+258'
            ),
            array(
                'name' => 'Myanmar (Burma) (မြန်မာ)',
                'alphacode' => 'mm',
                'countryCode' => '+95'
            ),
            array(
                'name' => 'Namibia (Namibië)',
                'alphacode' => 'na',
                'countryCode' => '+264'
            ),
            array(
                'name' => 'Nauru',
                'alphacode' => 'nr',
                'countryCode' => '+674'
            ),
            array(
                'name' => 'Nepal (नेपाल)',
                'alphacode' => 'np',
                'countryCode' => '+977'
            ),
            array(
                'name' => 'Netherlands (Nederland)',
                'alphacode' => 'nl',
                'countryCode' => '+31'
            ),
            array(
                'name' => 'New Caledonia (Nouvelle-Calédonie)',
                'alphacode' => 'nc',
                'countryCode' => '+687'
            ),
            array(
                'name' => 'New Zealand',
                'alphacode' => 'nz',
                'countryCode' => '+64'
            ),
            array(
                'name' => 'Nicaragua',
                'alphacode' => 'ni',
                'countryCode' => '+505'
            ),
            array(
                'name' => 'Niger (Nijar)',
                'alphacode' => 'ne',
                'countryCode' => '+227'
            ),
            array(
                'name' => 'Nigeria',
                'alphacode' => 'ng',
                'countryCode' => '+234'
            ),
            array(
                'name' => 'Niue',
                'alphacode' => 'nu',
                'countryCode' => '+683'
            ),
            array(
                'name' => 'Norfolk Island',
                'alphacode' => 'nf',
                'countryCode' => '+672'
            ),
            array(
                'name' => 'North Korea (조선 민주주의 인민 공화국)',
                'alphacode' => 'kp',
                'countryCode' => '+850'
            ),
            array(
                'name' => 'Northern Mariana Islands',
                'alphacode' => 'mp',
                'countryCode' => '+1670'
            ),
            array(
                'name' => 'Norway (Norge)',
                'alphacode' => 'no',
                'countryCode' => '+47'
            ),
            array(
                'name' => 'Oman (‫عُمان‬‎)',
                'alphacode' => 'om',
                'countryCode' => '+968'
            ),
            array(
                'name' => 'Pakistan (‫پاکستان‬‎)',
                'alphacode' => 'pk',
                'countryCode' => '+92'
            ),
            array(
                'name' => 'Palau',
                'alphacode' => 'pw',
                'countryCode' => '+680'
            ),
            array(
                'name' => 'Palestine (‫فلسطين‬‎)',
                'alphacode' => 'ps',
                'countryCode' => '+970'
            ),
            array(
                'name' => 'Panama (Panamá)',
                'alphacode' => 'pa',
                'countryCode' => '+507'
            ),
            array(
                'name' => 'Papua New Guinea',
                'alphacode' => 'pg',
                'countryCode' => '+675'
            ),
            array(
                'name' => 'Paraguay',
                'alphacode' => 'py',
                'countryCode' => '+595'
            ),
            array(
                'name' => 'Peru (Perú)',
                'alphacode' => 'pe',
                'countryCode' => '+51'
            ),
            array(
                'name' => 'Philippines',
                'alphacode' => 'ph',
                'countryCode' => '+63'
            ),
            array(
                'name' => 'Poland (Polska)',
                'alphacode' => 'pl',
                'countryCode' => '+48'
            ),
            array(
                'name' => 'Portugal',
                'alphacode' => 'pt',
                'countryCode' => '+351'
            ),
            array(
                'name' => 'Puerto Rico',
                'alphacode' => 'pr',
                'countryCode' => '+1'
            ),
            array(
                'name' => 'Qatar (‫قطر‬‎)',
                'alphacode' => 'qa',
                'countryCode' => '+974'
            ),
            array(
                'name' => 'Réunion (La Réunion)',
                'alphacode' => 're',
                'countryCode' => '+262'
            ),
            array(
                'name' => 'Romania (România)',
                'alphacode' => 'ro',
                'countryCode' => '+40'
            ),
            array(
                'name' => 'Russia (Россия)',
                'alphacode' => 'ru',
                'countryCode' => '+7'
            ),
            array(
                'name' => 'Rwanda',
                'alphacode' => 'rw',
                'countryCode' => '+250'
            ),
            array(
                'name' => 'Saint Barthélemy',
                'alphacode' => 'bl',
                'countryCode' => '+590'
            ),
            array(
                'name' => 'Saint Helena',
                'alphacode' => 'sh',
                'countryCode' => '+290'
            ),
            array(
                'name' => 'Saint Kitts and Nevis',
                'alphacode' => 'kn',
                'countryCode' => '+1869'
            ),
            array(
                'name' => 'Saint Lucia',
                'alphacode' => 'lc',
                'countryCode' => '+1758'
            ),
            array(
                'name' => 'Saint Martin (Saint-Martin (partie française))',
                'alphacode' => 'mf',
                'countryCode' => '+590'
            ),
            array(
                'name' => 'Saint Pierre and Miquelon (Saint-Pierre-et-Miquelon)',
                'alphacode' => 'pm',
                'countryCode' => '+508'
            ),
            array(
                'name' => 'Saint Vincent and the Grenadines',
                'alphacode' => 'vc',
                'countryCode' => '+1784'
            ),
            array(
                'name' => 'Samoa',
                'alphacode' => 'ws',
                'countryCode' => '+685'
            ),
            array(
                'name' => 'San Marino',
                'alphacode' => 'sm',
                'countryCode' => '+378'
            ),
            array(
                'name' => 'São Tomé and Príncipe (São Tomé e Príncipe)',
                'alphacode' => 'st',
                'countryCode' => '+239'
            ),
            array(
                'name' => 'Saudi Arabia (‫المملكة العربية السعودية‬‎)',
                'alphacode' => 'sa',
                'countryCode' => '+966'
            ),
            array(
                'name' => 'Senegal (Sénégal)',
                'alphacode' => 'sn',
                'countryCode' => '+221'
            ),
            array(
                'name' => 'Serbia (Србија)',
                'alphacode' => 'rs',
                'countryCode' => '+381'
            ),
            array(
                'name' => 'Seychelles',
                'alphacode' => 'sc',
                'countryCode' => '+248'
            ),
            array(
                'name' => 'Sierra Leone',
                'alphacode' => 'sl',
                'countryCode' => '+232'
            ),
            array(
                'name' => 'Singapore',
                'alphacode' => 'sg',
                'countryCode' => '+65'
            ),
            array(
                'name' => 'Sint Maarten',
                'alphacode' => 'sx',
                'countryCode' => '+1721'
            ),
            array(
                'name' => 'Slovakia (Slovensko)',
                'alphacode' => 'sk',
                'countryCode' => '+421'
            ),
            array(
                'name' => 'Slovenia (Slovenija)',
                'alphacode' => 'si',
                'countryCode' => '+386'
            ),
            array(
                'name' => 'Solomon Islands',
                'alphacode' => 'sb',
                'countryCode' => '+677'
            ),
            array(
                'name' => 'Somalia (Soomaaliya)',
                'alphacode' => 'so',
                'countryCode' => '+252'
            ),
            array(
                'name' => 'South Africa',
                'alphacode' => 'za',
                'countryCode' => '+27'
            ),
            array(
                'name' => 'South Korea (대한민국)',
                'alphacode' => 'kr',
                'countryCode' => '+82'
            ),
            array(
                'name' => 'South Sudan (‫جنوب السودان‬‎)',
                'alphacode' => 'ss',
                'countryCode' => '+211'
            ),
            array(
                'name' => 'Spain (España)',
                'alphacode' => 'es',
                'countryCode' => '+34'
            ),
            array(
                'name' => 'Sri Lanka (ශ්‍රී ලංකාව)',
                'alphacode' => 'lk',
                'countryCode' => '+94'
            ),
            array(
                'name' => 'Sudan (‫السودان‬‎)',
                'alphacode' => 'sd',
                'countryCode' => '+249'
            ),
            array(
                'name' => 'Suriname',
                'alphacode' => 'sr',
                'countryCode' => '+597'
            ),
            array(
                'name' => 'Svalbard and Jan Mayen',
                'alphacode' => 'sj',
                'countryCode' => '+47'
            ),
            array(
                'name' => 'Swaziland',
                'alphacode' => 'sz',
                'countryCode' => '+268'
            ),
            array(
                'name' => 'Sweden (Sverige)',
                'alphacode' => 'se',
                'countryCode' => '+46'
            ),
            array(
                'name' => 'Switzerland (Schweiz)',
                'alphacode' => 'ch',
                'countryCode' => '+41'
            ),
            array(
                'name' => 'Syria (‫سوريا‬‎)',
                'alphacode' => 'sy',
                'countryCode' => '+963'
            ),
            array(
                'name' => 'Taiwan (台灣)',
                'alphacode' => 'tw',
                'countryCode' => '+886'
            ),
            array(
                'name' => 'Tajikistan',
                'alphacode' => 'tj',
                'countryCode' => '+992'
            ),
            array(
                'name' => 'Tanzania',
                'alphacode' => 'tz',
                'countryCode' => '+255'
            ),
            array(
                'name' => 'Thailand (ไทย)',
                'alphacode' => 'th',
                'countryCode' => '+66'
            ),
            array(
                'name' => 'Timor-Leste',
                'alphacode' => 'tl',
                'countryCode' => '+670'
            ),
            array(
                'name' => 'Togo',
                'alphacode' => 'tg',
                'countryCode' => '+228'
            ),
            array(
                'name' => 'Tokelau',
                'alphacode' => 'tk',
                'countryCode' => '+690'
            ),
            array(
                'name' => 'Tonga',
                'alphacode' => 'to',
                'countryCode' => '+676'
            ),
            array(
                'name' => 'Trinidad and Tobago',
                'alphacode' => 'tt',
                'countryCode' => '+1868'
            ),
            array(
                'name' => 'Tunisia (‫تونس‬‎)',
                'alphacode' => 'tn',
                'countryCode' => '+216'
            ),
            array(
                'name' => 'Turkey (Türkiye)',
                'alphacode' => 'tr',
                'countryCode' => '+90'
            ),
            array(
                'name' => 'Turkmenistan',
                'alphacode' => 'tm',
                'countryCode' => '+993'
            ),
            array(
                'name' => 'Turks and Caicos Islands',
                'alphacode' => 'tc',
                'countryCode' => '+1649'
            ),
            array(
                'name' => 'Tuvalu',
                'alphacode' => 'tv',
                'countryCode' => '+688'
            ),
            array(
                'name' => 'U.S. Virgin Islands',
                'alphacode' => 'vi',
                'countryCode' => '+1340'
            ),
            array(
                'name' => 'Uganda',
                'alphacode' => 'ug',
                'countryCode' => '+256'
            ),
            array(
                'name' => 'Ukraine (Україна)',
                'alphacode' => 'ua',
                'countryCode' => '+380'
            ),
            array(
                'name' => 'United Arab Emirates (‫الإمارات العربية المتحدة‬‎)',
                'alphacode' => 'ae',
                'countryCode' => '+971'
            ),
            array(
                'name' => 'United Kingdom',
                'alphacode' => 'gb',
                'countryCode' => '+44'
            ),
            array(
                'name' => 'United States',
                'alphacode' => 'us',
                'countryCode' => '+1'
            ),
            array(
                'name' => 'Uruguay',
                'alphacode' => 'uy',
                'countryCode' => '+598'
            ),
            array(
                'name' => 'Uzbekistan (Oʻzbekiston)',
                'alphacode' => 'uz',
                'countryCode' => '+998'
            ),
            array(
                'name' => 'Vanuatu',
                'alphacode' => 'vu',
                'countryCode' => '+678'
            ),
            array(
                'name' => 'Vatican City (Città del Vaticano)',
                'alphacode' => 'va',
                'countryCode' => '+39'
            ),
            array(
                'name' => 'Venezuela',
                'alphacode' => 've',
                'countryCode' => '+58'
            ),
            array(
                'name' => 'Vietnam (Việt Nam)',
                'alphacode' => 'vn',
                'countryCode' => '+84'
            ),
            array(
                'name' => 'Wallis and Futuna (Wallis-et-Futuna)',
                'alphacode' => 'wf',
                'countryCode' => '+681'
            ),
            array(
                'name' => 'Western Sahara (‫الصحراء الغربية‬‎)',
                'alphacode' => 'eh',
                'countryCode' => '+212'
            ),
            array(
                'name' => 'Yemen (‫اليمن‬‎)',
                'alphacode' => 'ye',
                'countryCode' => '+967'
            ),
            array(
                'name' => 'Zambia',
                'alphacode' => 'zm',
                'countryCode' => '+260'
            ),
            array(
                'name' => 'Zimbabwe',
                'alphacode' => 'zw',
                'countryCode' => '+263'
            ),
            array(
                'name' => 'Åland Islands',
                'alphacode' => 'ax',
                'countryCode' => '+358'
            ),
        );
        return $countries;
}

function advanceSettingsTab()
{
    $settings   = commonUtilitiesTfa::getTfaSettings();
    $kbaSet1 = $settings['tfa_kba_set1']
       ? $settings['tfa_kba_set1']
       : '';
    $kbaSet2 = $settings['tfa_kba_set2']
       ? $settings['tfa_kba_set2']
       : '';
    $tfa_enabled = isset($settings['tfa_enabled']) && $settings['tfa_enabled'] == 1
       ? 'checked'
       :'';
    $inline  = isset($settings['inline_enabled']) && $settings['inline_enabled'] == 1
       ? 'checked'
       : '';
    $enableIpWhiteList = isset($settings['enableIpWhiteList']) && $settings['enableIpWhiteList'] == 1
       ? 'checked'
       : '';
    $enableIpBlackList = isset($settings['enableIpBlackList']) && $settings['enableIpBlackList'] == 1
       ? 'checked'
       : '';
    $whiteListedIps = isset($settings['whiteListedIps'])
       ? implode(";",json_decode($settings['whiteListedIps']))
       : '';
    $blackListedIps = isset($settings['blackListedIPs'])
       ? implode(";", json_decode($settings['blackListedIPs']))
       : '';
    $redirectUrl = empty($settings['afterLoginRedirectUrl'])
       ? JUri::root()
       : $settings['afterLoginRedirectUrl'];
    $brandingName   = isset($settings['branding_name']) && !empty($settings['branding_name'])
       ? ($settings['branding_name'])
       : 'login';
    $tfa_bypass_for_admin = isset($settings['bypass_tfa_for_admin']) && $settings['bypass_tfa_for_admin'] == 1
       ? 'checked'
       : '';
    $tfa_bypass_for_users = isset($settings['skip_tfa_for_users']) && $settings['skip_tfa_for_users'] == 1
       ? 'checked'
       : '';
    $tfa_for_roles = isset($settings['mo_tfa_for_roles']) && !empty($settings['mo_tfa_for_roles'])
       ? json_decode($settings['mo_tfa_for_roles'])
       : array();
    $login_with_second_factor_only = isset($settings['login_with_second_factor_only']) && $settings['login_with_second_factor_only'] == 1
       ? 'checked'
       : '';
    $resend_otp_control = isset($settings['resend_otp_control']) && $settings['resend_otp_control'] == 1
       ? 'checked'
       : '';
    $resend_otp_count = isset($settings['resend_otp_count'])
       ? $settings['resend_otp_count']
       : 3;
    $blocking_resend_otp_type = isset($settings['blocking_resend_otp_type'])
       ? $settings['blocking_resend_otp_type']
       : "days";
    $enable_backup_codes = isset($settings['enable_backup_codes']) && $settings['enable_backup_codes'] == 1
       ? 'checked'
       : '';

    $googleAuthAppName = urldecode($settings['googleAuthAppName']);
    $isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
    $details= commonUtilitiesTfa::getCustomerDetails();
    $customerEmail = $details['email'];
    $inlineDisabled='';
    if( is_null($details['license_type']) || empty($details['license_type'])){
       $inlineDisabled='disabled';
    }
    $featureDisable='';
    if( !$isCustomerRegistered ){
       $featureDisable='disabled';
    }
    $groups = commonUtilitiesTfa::loadGroups();
    $active2FA = commonUtilitiesTfa::getActive2FAMethods();
    $hostName = commonUtilitiesTfa::getHostName();
    // Read backup codes from file
    $random_string = trim(commonUtilitiesTfa::readBackupCodesFromFile());
    $backup_code_array = explode(',', $random_string);
    $current_user     = JFactory::getUser();
    $isCustomerRegistered = commonUtilitiesTfa::isCustomerRegistered();
    $isFirstUser          = commonUtilitiesTfa::isFirstUser($current_user->id);
    $enableChange2FA = isset($settings['enable_change_2fa_method']) && $settings['enable_change_2fa_method'] == 1
       ? 'checked'
       : '';
    ?>
    <div class="mo_boot_row mo_boot_m-1">
        <div class="mo_boot_col-sm-12">
            <?php
                if( !commonUtilitiesTfa::isCustomerRegistered() ){
                    echo  '<div class="mo_register_message">'.JText::_("COM_MINIORANGE_SETUP_TFA_MSG").' <a href="'. JRoute::_('index.php?option=com_miniorange_twofa&tab-panel=account_setup').'" >'.JText::_("COM_MINIORANGE_REGISTER_MSG").'</a> '.JText::_("COM_MINIORANGE_SETUP_TFA_MSG1").'</div>';
                }
            ?>
        </div>
        <div class="mo_boot_col-sm-12">
            <div class="mo_boot_row mo_boot_text-center">
               <div class="mo_boot_col-sm-12">
                    <br>
                    <h3><?php echo JText::_('COM_MINIORANGE_ADVANCE_SETTINGS');?></h3>
                    <hr>
               </div>
            </div>
            <div class="mo_boot_col-sm-12 mo_boot_mt-2" >
                <form name="f" class="miniorange_tfa_settings_form" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_twofa&tab-panel=setup_two_factor&task=setup_two_factor.saveTfaAdvanceSettings');?>">
                    <details class="mo_details">
                        <summary class="mo_tfa_summary"><?php echo JText::_('COM_MINIORANGE_ROLE2FA');?> <sup><?php echo $inlineDisabled=='disabled'?'<a href='.JRoute::_('index.php?option=com_miniorange_twofa&view=Licensing').'><strong>&nbsp;&nbsp;['.JText::_("COM_MINIORANGE_PREMIUM").']</strong></a>':''; ?></sup></summary><hr>
                        <div class="mo_boot_row mo_boot_m-2">
                            <div class="mo_boot_col-sm-12">
                                <strong><?php echo JText::_('COM_MINIORANGE_NOTE');?></strong> <i><?php echo JText::_('COM_MINIORANGE_ROLE_DESC');?></i></br>
                                <?php
                                    foreach ($groups as $key => $value)
                                    {
                                        if($value['title'] != 'Public' && $value['title'] != 'Guest') 
                                        {
                                            if (in_array('ALL', $tfa_for_roles) || in_array($value['title'], $tfa_for_roles))
                                                echo '<br><input type="checkbox" name="role_based_tfa_' . str_replace(' ', '_', $value['title']) . '" checked="true" ' . $inlineDisabled . ' /> &emsp;' . $value['title'];
                                            else
                                                echo '<br><input type="checkbox" name="role_based_tfa_' . str_replace(' ', '_', $value['title']) . '"' . $inlineDisabled . '  />&emsp;' . $value['title'];
                                        }
                                    } 
                                ?>
                            </div>
                        </div>
                    </details>
                    <details class="mo_details">
                        <summary  class="mo_tfa_summary"><?php echo JText::_('COM_MINIORANGE_IP_BASED2FA');?><sup><?php echo $inlineDisabled=='disabled'?'<a href='.JRoute::_('index.php?option=com_miniorange_twofa&view=Licensing').'><strong>&nbsp;&nbsp;['.JText::_("COM_MINIORANGE_PREMIUM").']</strong></a>':''; ?></sup></summary>
                        <div class="mo_boot_row mo_boot_m-2">
                            <div class="mo_boot_col-sm-12">
                                <div class="mo_tfa_details_content" >
                                    <input type="checkbox" id="enable_ip_whitelist" onclick="enable_ip_whitelist_field();" name="enableIpWhiteListing" <?php echo $inlineDisabled; ?> <?php echo $enableIpWhiteList;?> <?php echo " ".$featureDisable ?> />&emsp;<strong><?php echo JText::_('COM_MINIORANGE_WHITELIST');?></strong><br>
                                    &emsp;&emsp;<strong><?php echo JText::_('COM_MINIORANGE_NOTE');?> </strong><?php echo JText::_('COM_MINIORANGE_WHITELIST_DESC');?><br>
                                    <textarea class="ip_whitelist_field" name="mo_tfa_whitelist_ips" style="margin-top:10px;margin-left:24px;width:85%;font-size:14px;border: 1px solid;" rows="5"  placeholder="<?php echo JText::_('COM_MINIORANGE_WHITELIST_IP');?>" <?php echo $inlineDisabled; ?> <?php echo $featureDisable ?>><?php echo $whiteListedIps;?></textarea>
                                </div> 
                            </div>
                        </div>
                    </details>
                    <script>
                       jQuery(document).ready(function (){
                           enable_ip_whitelist_field();
                       });
                       function enable_ip_whitelist_field() {
                           var enable_ip_whitelist = document.getElementById('enable_ip_whitelist');
                           var ip_whitelist_field = document.getElementsByClassName('ip_whitelist_field')[0];
                           ip_whitelist_field.disabled = enable_ip_whitelist.checked !== true;
                       }
                    </script>
                    <details class="mo_details">
                        <summary class="mo_tfa_summary"><?php echo JText::_('COM_MINIORANGE_EXTRA_MODIFICATIONS');?><sup><?php echo $featureDisable=='disabled'?'<a href='.JRoute::_('index.php?option=com_miniorange_twofa&view=Licensing').'><strong>&nbsp;&nbsp;['.JText::_("COM_MINIORANGE_PREMIUM").']</strong></a>':''; ?> </summary>
                        <div class="mo_boot_row mo_boot_m-2">
                            <div class="mo_boot_col-sm-12">
                                <div class="mo_tfa_details_content">
                                    <label>
                                        <strong><?php echo JText::_('COM_MINIORANGE_REDIRECT');?></strong>
                                    </label>
                                    <input type="url" name="mo_tfa_user_after_login" <?php echo $featureDisable ?> class="mo_boot_form-control" value="<?php echo $redirectUrl;?>" placeholder="Enter the redirect URL" />
                                    <label><strong><?php echo JText::_('COM_MINIORANGE_NOTE');?></strong> <?php echo JText::_('COM_MINIORANGE_REDIRECT_URL');?> <i><?php echo JText::_('COM_MINIORANGE_URL');?></i> <?php echo JText::_('COM_MINIORANGE_REDIRECT_AFTER_AUTH');?></label><hr>
                                    <label>
                                        <strong><?php echo JText::_('COM_MINIORANGE_DOMAIN');?></strong>
                                    </label>
                                    <input type="text" name="branding_name" <?php echo $featureDisable ?> class="mo_boot_form-control" value="<?php echo $brandingName; ?>"/>
                                    <label><strong><?php echo JText::_('COM_MINIORANGE_NOTE');?></strong><?php echo JText::_('COM_MINIORANGE_BRANDING');?> <strong><?php echo JText::_('COM_MINIORANGE_LOGIN');?></strong></label><hr>
                                    <label>
                                        <strong><?php echo JText::_('COM_MINIORANGE_CHANGE_ACCOUNT');?></strong>
                                    </label>
                                    <input type="text" name="mo_tfa_google_app_name" <?php echo $featureDisable ?> class="mo_boot_form-control" value="<?php echo $googleAuthAppName; ?>" />
                                    <label><strong><?php echo JText::_('COM_MINIORANGE_NOTE');?></strong><?php echo JText::_('COM_MINIORANGE_CHANGE_DESC');?></label>
                                </div>
                            </div>   
                        </div>
                    </details>
                    <details class="mo_details">
                        <summary class="mo_tfa_summary"><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_TMPL');?></summary>
                        <div class="mo_boot_row mo_boot_m-2">
                            <div class="mo_boot_col-sm-12">
                                <div class="mo_tfa_details_content"><hr>
                                    <ul>
                                        <li>
                                            <strong><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEPS');?></strong>
                                            <ol>
                                                <li><?php echo JText::_('COM_MINIORANGE_CLICK');?> <a href="<?php echo $hostName; ?>/moas/login?username=<?php echo $customerEmail;?>&redirectUrl=<?php echo $hostName; ?>/moas/admin/customer/emailtemplateconfiguration" target="_blank" rel="noopener noreferrer"><strong><?php echo JText::_('COM_MINIORANGE_HERE');?></strong></a> <?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP6');?></li>
                                                <li><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP1');?></li>
                                                <li><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP2');?> <u><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP3');?></u> <?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP4');?></li>
                                            </ol>
                                        </li><hr>
                                        <li>
                                            <strong><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP5');?></strong>
                                            <ol>
                                                <li><?php echo JText::_('COM_MINIORANGE_CLICK');?> <a href="<?php echo $hostName; ?>/moas/login?username=<?php echo $customerEmail;?>&redirectUrl=<?php echo $hostName; ?>/moas/admin/customer/customerrebrandingconfig" target="_blank" rel="noopener noreferrer"><strong><?php echo JText::_('COM_MINIORANGE_HERE');?></strong></a> <?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP6');?></li>
                                                <li><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP7');?> <u><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP8');?></u> <?php echo JText::_('COM_MINIORANGE_CUSTOMISE_TAB');?></li>
                                                <li><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP9');?></li>
                                            </ol>
                                        </li><hr>
                                        <li>
                                            <strong><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_SMSTMPL');?></strong>
                                            <ol>
                                                <li><?php echo JText::_('COM_MINIORANGE_CLICK');?> <a href="<?php echo $hostName; ?>/moas/login?username=<?php echo $customerEmail;?>&redirectUrl=<?php echo $hostName; ?>/moas/admin/customer/showsmstemplate" target="_blank" rel="noopener noreferrer"><strong><?php echo JText::_('COM_MINIORANGE_HERE');?></strong></a> <?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP6');?></li>
                                                <li><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_SMS1');?></li>
                                                <li><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_SMS2');?> <u><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_SMS3');?></u> <?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP4');?></li>
                                            </ol>
                                        </li><hr>
                                        <li>
                                            <strong><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_SMS4');?></strong>
                                            <ol>
                                                <li><?php echo JText::_('COM_MINIORANGE_CLICK');?> <a href="<?php echo $hostName; ?>/moas/login?username=<?php echo $customerEmail;?>&redirectUrl=<?php echo $hostName; ?>/moas/admin/customer/customerpreferences" target="_blank" rel="noopener noreferrer"><strong><?php echo JText::_('COM_MINIORANGE_HERE');?></strong></a> <?php echo JText::_('COM_MINIORANGE_CUSTOMISE_STEP6');?></li>
                                                <li><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_SMS5');?> <u><?php echo JText::_('COM_MINIORANGE_CUSTOMISE_SMS6');?></u> <?php echo JText::_('COM_MINIORANGE_OPTION');?></li>
                                            </ol>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </details>
                    <details class="mo_details">
                        <summary class="mo_tfa_summary" ><?php echo JText::_('COM_MINIORANGE_DOMAIN_BASED2FA');?><sup style="color: red;"><?php echo JText::_('COM_MINIORANGE_COMINGSOON');?></sup></summary><hr>
                        <div class="mo_boot_row mo_boot_m-2">
                            <div class="mo_boot_boot_col-sm-12">
                                <div class="mo_tfa_details_content" >
                                    <input type="checkbox" disabled/>&emsp;<?php echo JText::_('COM_MINIORANGE_DOMAIN_ENABLE');?><br>
                                    &ensp;&ensp;&emsp;<strong><?php echo JText::_('COM_MINIORANGE_NOTE');?></strong> <?php echo JText::_('COM_MINIORANGE_DOMAIN_DESC');?><br>
                                    <textarea style="margin-top:10px;margin-left:24px;width:100%;font-size:14px;border: 1px solid;" rows="5" disabled placeholder="<?php echo JText::_('COM_MINIORANGE_VAL_DOMAIN');?>" <?php echo $featureDisable ?>></textarea>
                                </div>
                            </div>
                        </div>
                    </details>
                    <details class="mo_details">
                        <summary class="mo_tfa_summary"><?php echo JText::_('COM_MINIORANGE_EMAIL_NOTIFY');?><sup style="color: red;"><?php echo JText::_('COM_MINIORANGE_COMINGSOON');?></sup></summary>
                        <div class="mo_boot_row mo_boot_m-2">
                            <div class="mo_boot_col-sm-12">
                                <div class="mo_tfa_details_content"><hr>
                                    <p style="background:#e0e0e0;"><strong><?php echo JText::_('COM_MINIORANGE_NOTE');?> </strong><?php echo JText::_('COM_MINIORANGE_EMAIL_NOTIFY1');?></p><br>
                                </div>
                            </div>
                        </div> 
                    </details>
                    <div class="mo_boot_col-sm-12 mo_boot_my-3 mo_boot_text-center;" style="padding-right:80px;">
                        <?php
                            if( $isCustomerRegistered && $isFirstUser )
                            { 
                                ?>
                                <input type="submit" name="submit_login_settings" value="<?php echo JText::_('COM_MINIORANGE_VAL_SAVE');?>" style="background-color:#074583" class="mo_tfa_input_submit mo_boot_btn mo_boot_btn-primary" <?php echo $featureDisable ?>>
                                <?php 
                            }
                       ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}

function CustomLoginForms()
{
    ?>
        <div class="mo_boot_row mo_boot_my-3">
            <div class="mo_boot_col-sm-12 mo_boot_text-center mo_boot_mt-3">
                <h3><?php echo JText::_('COM_MINIORANGE_LOGINFORMS');?><sup><span style="color:red;"><?php echo JText::_('COM_MINIORANGE_COMINGSOON');?></span></sup></h3> 
                <hr>
                <p>
                    <strong><?php echo JText::_('COM_MINIORANGE_NOTE');?></strong> <?php echo JText::_('COM_MINIORANGE_FORMS_DESC1');?> <?php echo JText::_('COM_MINIORANGE_FORMS_DESC2');?> <a href="mailto:joomlasupport@xecurify.com">joomlasupport@xecurify.com</a>
                </p>
            </div>
            <div class="mo_boot_col-sm-12 mo_boot_my-3">
                <div class="mo_boot_row mo_boot_m-2">
                    <div class="mo_boot_col-sm-12">
                        <details class="mo_details">
                            <summary><?php echo JText::_('COM_MINIORANGE_CONVERTFORMS');?></summary>
                            <form action="#">
                                <div class="mo_boot_row mo_boot_p-3">
                                    <div class="mo_boot_col-sm-12">
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_FORMID');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" class="mo_boot_form-control" disabled name="convert_form_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_EMAIL_ATTRIBUTE');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" class="mo_boot_form-control" disabled name="convert_email_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-4 mo_boot_text-center">
                                            <div class="mo_boot_col-sm-12">
                                                <input type="submit" class="mo_boot_btn mo_boot_btn-primary_two" disabled value="<?php echo JText::_('COM_MINIORANGE_VAL_SUBMIT');?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </form>
                        </details>
                        <details class="mo_details">
                            <summary><?php echo JText::_('COM_MINIORANGE_BREZZINGFORMS');?></summary>
                            <form action="#">
                                <div class="mo_boot_row mo_boot_p-3">
                                    <div class="mo_boot_col-sm-12">
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_FORMID');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled  class="mo_boot_form-control" name="brazzing_form_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_EMAIL_ATTRIBUTE');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="brazzing_email_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-4 mo_boot_text-center">
                                            <div class="mo_boot_col-sm-12">
                                                <input type="submit" disabled class="mo_boot_btn mo_boot_btn-primary_two" value="<?php echo JText::_('COM_MINIORANGE_VAL_SUBMIT');?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </details>
                        <details class="mo_details">
                            <summary><?php echo JText::_('COM_MINIORANGE_VIRTUEFORMS');?></summary>
                            <form action="#">
                                <div class="mo_boot_row mo_boot_p-3">
                                    <div class="mo_boot_col-sm-12">
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_FORMID');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="virtuemart_form_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_EMAIL_ATTRIBUTE');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="virtue_email_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-4 mo_boot_text-center">
                                            <div class="mo_boot_col-sm-12">
                                                <input type="submit" disabled class="mo_boot_btn mo_boot_btn-primary_two" value="<?php echo JText::_('COM_MINIORANGE_VAL_SUBMIT');?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </details>
                        <details class="mo_details">
                            <summary><?php echo JText::_('COM_MINIORANGE_AJAX');?></summary>
                            <form action="#">
                                <div class="mo_boot_row mo_boot_p-3">
                                    <div class="mo_boot_col-sm-12">
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_FORMID');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="ajax_form_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_EMAIL_ATTRIBUTE');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="ajax_email_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-4 mo_boot_text-center">
                                            <div class="mo_boot_col-sm-12">
                                                <input type="submit" disabled class="mo_boot_btn mo_boot_btn-primary_two" value="<?php echo JText::_('COM_MINIORANGE_VAL_SUBMIT');?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </details>
                        <details class="mo_details">
                            <summary><?php echo JText::_('COM_MINIORANGE_PROFORMS');?></summary>
                            <form action="#">
                                <div class="mo_boot_row mo_boot_p-3">
                                    <div class="mo_boot_col-sm-12">
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_FORMID');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="pro_form_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_EMAIL_ATTRIBUTE');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="pro_email_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-4 mo_boot_text-center">
                                            <div class="mo_boot_col-sm-12">
                                                <input type="submit" disabled class="mo_boot_btn mo_boot_btn-primary_two" value="<?php echo JText::_('COM_MINIORANGE_VAL_SUBMIT');?>">
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                                
                            </form>
                        </details>
                        <details class="mo_details">
                            <summary><?php echo JText::_('COM_MINIORANGE_RSFORMS');?></summary>
                            <form action="#">
                                <div class="mo_boot_row mo_boot_p-3">
                                    <div class="mo_boot_col-sm-12">
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_FORMID');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="rs_form_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_EMAIL_ATTRIBUTE');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="rs_email_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-4 mo_boot_text-center">
                                            <div class="mo_boot_col-sm-12">
                                                <input type="submit" disabled class="mo_boot_btn mo_boot_btn-primary_two" value="<?php echo JText::_('COM_MINIORANGE_VAL_SUBMIT');?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>
                        </details>
                        <details class="mo_details">
                            <summary><?php echo JText::_('COM_MINIORANGE_CHRONOFORMS');?></summary>
                            <form action="#">
                                <div class="mo_boot_row mo_boot_p-3">
                                    <div class="mo_boot_col-sm-12">
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_FORMID');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="chrono_form_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_EMAIL_ATTRIBUTE');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="chrono_email_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-4 mo_boot_text-center">
                                            <div class="mo_boot_col-sm-12">
                                                <input type="submit" disabled class="mo_boot_btn mo_boot_btn-primary_two" value="<?php echo JText::_('COM_MINIORANGE_VAL_SUBMIT');?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>
                        </details>
                        <details class="mo_details">
                            <summary><?php echo JText::_('COM_MINIORANGE_SHACKFORMS');?></summary>
                            <form action="#">
                                <div class="mo_boot_row mo_boot_p-3">
                                    <div class="mo_boot_col-sm-12">
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            <?php echo JText::_('COM_MINIORANGE_FORMID');?>
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="shack_form_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-2">
                                            <div class="mo_boot_col-sm-4">
                                            Email Attribute:
                                            </div>
                                            <div class="mo_boot_col-sm-6">
                                                <input type="text" disabled class="mo_boot_form-control" name="shack_email_id">
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-4 mo_boot_text-center">
                                            <div class="mo_boot_col-sm-12">
                                                <input type="submit" disabled class="mo_boot_btn mo_boot_btn-primary_two" value="<?php echo JText::_('COM_MINIORANGE_VAL_SUBMIT');?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>
                        </details>
                    </div>
                </div>
            </div>
        </div>
    <?php
}


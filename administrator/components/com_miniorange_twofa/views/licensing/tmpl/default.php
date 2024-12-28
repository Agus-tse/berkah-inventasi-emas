<?php
   /**
    * @package     Joomla.Administrator
    * @subpackage  com_miniorange_twofa
    *
    * @license     GNU General Public License version 2 or later; see LICENSE.txt
    */
   defined('_JEXEC') or die('Restricted access');
   JHtml::_('jquery.framework',false);
   jimport('miniorangetfa.utility.commonUtilitiesTfa');
   JHtml::_('stylesheet', JUri::base() . 'components/com_miniorange_twofa/assets/css/miniorange_boot.css');
   JHtml::_('stylesheet','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
   $mfa_active_tab="license";
    /*
    * Check is curl installed or not, if not show the instructions for installation.
    */
    commonUtilitiesTfa::checkIsCurlInstalled();
?>
<style>
    
    .tfa_plans_container:hover{
        transform:scale(1.01);
    }
</style>
<div id="account" class="mo_boot_container-fluid" style="background:white;">
    <div class="mo_boot_row">
        <div class="mo_boot_col-sm-12">
            <?php licensingtab(); ?>
        </div>
    </div>
</div>
<?php
   function licensingtab()
    {
        $details=commonUtilitiesTfa::getCustomerDetails();
        $user_email = isset($details['email'])?$details['email']:'';
        $hostName = commonUtilitiesTfa::getHostName();
        ?>
        <style>
						
						.mo_pricing-table-3
                        {
							background-color: #125be026;
							border: 1px solid black;
							margin:15px auto;
							box-shadow:0px 0px 25px rgba(0,0,0,0.1);
							max-width:300px;
							border-radius:0px 10px 0px 10px;
							overflow:hidden;
							position:relative;
							min-height:250px;
							transition:all ease-in-out 0.25s;	
						}
						
						.mo_pricing-table-3 .mo_pricing-table-header{
							text-align:center;
							color:black;
							font-size: 1em;
                            padding:10px;
						}
                        .mo_pricing-table-header p{
                            font-size:2em;
                        }
                        
						.mo_pricing-table-3 .pricing-body{
							padding:20px;	
							background-color: white;
						}
						.mo_pricing-table-3 .mo_pricing-table-ul li{
							color:rgba(0,0,0,0.7);
							font-size:13px;
							padding:10px;
							border-bottom:1px solid rgba(0,0,0,0.1);
							font-size: 1em;
							list-style-type:none;
						}
						
						.mo_works-step{
							margin-bottom: 50px;
						}
						.mo_works-step div{
							color:#0a3273;
							border: 2px solid #0a3273;
							border-radius: 50%;
							width: 50px;
							height: 50px;
							text-align: center;
							padding: 8px;
							float: left;
							font-size: 20px;
							margin-right: 25px;
							margin-bottom: 27px;
						}
						.mo_works-step p{
							font-size:15px;
							text-align: left;
						}
						.mo_plan-box{
							border: 1px solid #6e727a;
							min-height:250px;
							background-color: white;;
							border-radius: 10px;
							margin: 0 10px;
							box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
							transition: 0.3s;
                            color:black;
						}
						.mo_plan-box:hover{
							color:black;
							border-color: #093553;
							box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
						}
						.mo_plan-box div{
							padding:20px;
							font-size: 14px;
							line-height: normal;
							font-weight:400;    
						}
						.mo_plan-box div:first-child{
							height: 90px;
							text-align: center;
							border-bottom: 4px solid #b9babd;
						}
						.payment-images{
							width:140px;
						}
						.mo_plan-box div:last-child{
							font-size: 15px;
							line-height: 23px;
						}					
						.payment-methods{
							background-color: #f9f9f9;
							padding-left: 10%;
							padding-right: 10%;
							margin-top: 0px;
							margin-bottom: 0%
						}
					</style>

        <div class="mo_boot_row" style="background:white;border-radius:5px;  box-shadow: 0px 0px 10px grey;">
            <div class="mo_boot_col-sm-12">
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-12 mo_boot_mt-4 lead mo_boot_text-center">
                        <h2 style="display:inline-block;color:black;"><?php echo JText::_('COM_MINIORANGE_LICENSING');?></h2>
                        <a href="<?php echo JURI::base().'index.php?option=com_miniorange_twofa&tab-panel=account_setup';?>" style="display:inline-block;float:right;" class="mo_boot_btn mo_boot_btn-danger"><?php echo JText::_('COM_MINIORANGE_BACK');?></a>
                        <hr>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-4  mo_boot_text-center">
                    <div class="mo_boot_col-sm-4 mo_boot_offset-sm-2 tfa_plans_container">
                        <div class="mo_boot_row mo_boot_m-1" style="border:3px solid #768fa4;background:#ededed; border-radius:10px;height:100%">
                            <div class="mo_boot_col-sm-12 mo_boot_mt-4">
                                <h2><?php echo JText::_('COM_MINIORANGE_FREE');?></h2>
                                <hr>
                                
                                <br>
                                <h3>
                                    $0
                                </h3>
                                <br><br>
                    
                                <?php
                                if($details['license_plan']=='Demo'){
                                    ?>
                                    <a  class="mo_boot_btn  mo_boot_text-light" style="background:#3d618f;"><?php echo JText::_('COM_MINIORANGE_ACTIVE_PLAN');?></a>
                                <br><br><br>
                                <?php
                                }
                                else{
                                    ?>
                                    <a  class="mo_boot_btn  mo_boot_text-light" style="background:#3d618f;"><?php echo JText::_('COM_MINIORANGE_FREE_PLAN');?></a>
                                <br><br><br>
                                <?php
                                }?>
                                
                            </div>
                            <div class="mo_boot_col-sm-12">
                                <div class="mo_boot_row">
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white;border-top:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_ALL_2FA_METHODS');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_EMAIL_REMEMBER_DEVICE');?> </span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_LOGIN_PHONE');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_TFA_ON_REGISTER');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_KBA');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_KBA_QUESTIONS');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_KBA_NUMBER');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_BACKUP_CODES');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_CHANGE_APP_NAME');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_CUSTOM_TEMPLATES');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white;">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_CUSTOM_OTP_LENGTH');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_BACKDOOR_URL');?></span>
                                    </div>
                        
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mo_boot_col-sm-4 tfa_plans_container">
                        <div class="mo_boot_row mo_boot_m-1 mo_boot_text-light" style="border:3px solid #3b4156;background:#264367; border-radius:10px;height:100%">
                            <div class="mo_boot_col-sm-12 mo_boot_mt-4">
                                <h2><?php echo JText::_('COM_MINIORANGE_PREMIUM');?></h2>
                                <hr>
                                <p><?php echo JText::_('COM_MINIORANGE_ACTIVATE_PLAN');?></p>
                                <br>
                                <div class="mo_boot_row">
                                    <div class="mo_boot_col-sm-12 mo_boot_px-0" style="border-bottom-color:white;">
                                        <p style="font-size: 12px; text-align: center;">
                                        <?php echo JText::_('COM_MINIORANGE_YEARLY_FEES');?>
                                        </p>
                                        <span><strong><?php echo JText::_('COM_MINIORANGE_USERS_NO');?></strong></span><br>
                                        <select required style="background:#ffffff; border: 1px solid white;color:black; margin-left: 7px;height:25px; width:65%;border-radius: 3px; margin-top: 5px">
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 10 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $10 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 20 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $20 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 30 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $30 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 40 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $40 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 50 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $50 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 60 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $60 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 70 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $70 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 80 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $80 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 90 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $90 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 100 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $100 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 150 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $150 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 200 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $200 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 250 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $250 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 300 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $275 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 350 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $300 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 400 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $325 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 450 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $348 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 500 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $370 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 600 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $395 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 700 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $420 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 800 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $445 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_UPTO');?> 900 <?php echo JText::_('COM_MINIORANGE_USERS');?> - $470 / year</option>
                                            <option><?php echo JText::_('COM_MINIORANGE_MORE_THAN');?> 1000 <?php echo JText::_('COM_MINIORANGE_USERS');?> - <?php echo JText::_('COM_MINIORANGE_CONTACTUS');?> </option>
                                        </select>
                                    </div>
                                </div>
                                <br><br>
                                <?php
                                if((($details['license_plan']=='') && ($details['license_type'])=='')){
                                    ?>
                                    <a href="https://portal.miniorange.com/initializepayment?requestOrigin=joomla_2fa_premium_plan" class="mo_boot_btn mo_boot_btn-light" target="_blank"><?php echo JText::_('COM_MINIORANGE_UPGRADE');?></a>
                            
                                <?php
                                }
                                else{
                                    ?>
                                    <a href="" class="mo_boot_btn" style="background:#e0e6ef;"><span style="color:black"><?php echo JText::_('COM_MINIORANGE_ACTIVE_PLAN');?></span></a>
                    
                                <?php
                                }?>
                                
                                <div class="mo_boot_row">
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" >
                                        <h2><?php echo JText::_('COM_MINIORANGE_PREMIUM_FEATURES_DESC');?></h2>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" >
                                        <h1>+</h1>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white;border-top:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_ROLE_BASED_TFA');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_IP_SPECIFIC_TFA');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_SPECIFIC_TFA_METHOD');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_PASSWORD_RESET');?></span>
                                    </div>
                                    <div class="mo_boot_col-sm-12 mo_boot_py-3" style="border-bottom:1px solid white">
                                        <span><?php echo JText::_('COM_MINIORANGE_2FA_PROFILE_UPDATE');?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                </div>
            </div>
            <div class="mo_boot_col-sm-12 mo_boot_my-2">
                <div class="mo_boot_my-4" style="border:3px solid #3d618f;background:white; border-radius:10px;" id="upgrade-steps">
                    <div  style="padding-top:25px;">
                        <h2 style="text-align:center"><?php echo JText::_('COM_MINIORANGE_UPRAGDE_LICENSE');?></h2>
                    </div> <hr>
            		<section   id="section-steps" >
                        <div class="mo_boot_col-sm-12 mo_boot_row ">
                            <div class=" mo_boot_col-sm-6 mo_works-step">
                                <div><strong>1</strong></div>
                                <p> <?php echo JText::_('COM_MINIORANGE_CLICKON');?> <strong><em><?php echo JText::_('COM_MINIORANGE_UPGRADE');?></em></strong> <?php echo JText::_('COM_MINIORANGE_UPGRADE_BUTTON');?>  <strong><em><?php echo JText::_('COM_MINIORANGE_UPGRADE');?></em></strong> <?php echo JText::_('COM_MINIORANGE_REDIRECT_TO');?><span stye="margin-left:10px"></span> </p>
                            </div>
                            <div class="mo_boot_col-sm-6 mo_works-step">
                                <div><strong>4</strong></div>
                                <p>
                                <?php echo JText::_('COM_MINIORANGE_LICENSE_PLUGIN_DESC1');?> <strong><em><?php echo JText::_('COM_MINIORANGE_LICENSE_PLUGIN_DESC2');?></em></strong> <?php echo JText::_('COM_MINIORANGE_LICENSE_PLUGIN_DESC3');?>
                                </p>
                            </div>            
                        </div>
                        <div class="mo_boot_col-sm-12 mo_boot_row ">
                            <div class=" mo_boot_col-sm-6 mo_works-step">
                                <div><strong>2</strong></div>
                                <p>
                                <?php echo JText::_('COM_MINIORANGE_LICENSE_PLUGIN_DESC4');?> <em></em> <?php echo JText::_('COM_MINIORANGE_LICENSE_PLUGIN_DESC5');?>
                                </p>
                            </div>
                            <div class="mo_boot_col-sm-6 mo_works-step">
                                <div><strong>5</strong></div>
                                <p>
                                <?php echo JText::_('COM_MINIORANGE_LICENSE_PLUGIN_DESC6');?>
                                </p>
                            </div>     
                        </div>
                        <div class="mo_boot_col-sm-12 mo_boot_row ">
                           <div class="mo_boot_col-sm-6 mo_works-step">
                                <div><strong>3</strong></div>
                                <p>
                                <?php echo JText::_('COM_MINIORANGE_LICENSE_PLUGIN_DESC7');?>
                                </p>
                            </div>
                            <div class=" mo_boot_col-sm-6 mo_works-step">
                                <div><strong>6</strong></div>                
                                <p >
                                <?php echo JText::_('COM_MINIORANGE_LICENSE_PLUGIN_DESC8');?>
                                <?php echo JText::_('COM_MINIORANGE_LICENSE_PLUGIN_DESC9');?><br> <br>
                                </p>
                            </div>       
                        </div> 
                    </section>
                </div>
                <div class="mo_boot_my-4 mo_boot_pt-4"  id="payment-method"  style="border:3px solid white;color:white;background:#3d618f; border-radius:10px;" >
                    <h2 style="text-align:center"><?php echo JText::_('COM_MINIORANGE_PAYMENT_METHODS');?></h2><hr>
                    <section style="height: 400px;" >
                        <br>
                        <div class="mo_boot_col-sm-12 mo_boot_row">  
                            <div class="mo_boot_col-sm-4 mo_boot_offset-sm-2">
                                <div class="mo_plan-box">
                                    <div style="background-color:white; border-radius:10px; ">
                                        <em style="font-size:30px;" class="fa fa-cc-amex" aria-hidden="true"></em>
                                        <em style="font-size:30px;" class="fa fa-cc-visa" aria-hidden="true"></em>
                                        <em style="font-size:30px;" class="fa fa-cc-mastercard" aria-hidden="true"></em>
									</div>
                                    <div>
                                    <?php echo JText::_('COM_MINIORANGE_PAYMENT_DESC');?>
                                    </div>
                                </div>
                            </div>
                            <div class="mo_boot_col-sm-4">
                                <div class="mo_plan-box">
                                    <div style="background-color:white; border-radius:10px; ">
                                        <em style="font-size:30px;" class="fa fa-university" aria-hidden="true"><span style="font-size: 20px;font-weight:500;">&nbsp;&nbsp;<?php echo JText::_('COM_MINIORANGE_BANK_TRANSFER');?></span></em>          
                                    </div>
                                    <div> 
                                    <?php echo JText::_('COM_MINIORANGE_BANK_DESC1');?> <strong><em><span>info@xecurify.com</span></em></strong> <?php echo JText::_('COM_MINIORANGE_BANK_DESC2');?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <p style="margin-top:20px;font-size:16px;text-align:center">
                                <span style="font-weight:500;"> <?php echo JText::_('COM_MINIORANGE_NOTE');?></span> <?php echo JText::_('COM_MINIORANGE_BANK_DESC3');?>
                            </p>
                        </div>
                    </section>
                </div>
                
            </div>
        </div>
    <?php
}
?>
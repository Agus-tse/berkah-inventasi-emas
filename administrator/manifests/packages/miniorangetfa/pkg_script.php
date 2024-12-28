<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of miniorange_dirsync_system_plugin.
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
class pkg_MINIORANGETFAInstallerScript
{
    /**
     * This method is called after a component is installed.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function install($parent) 
    {

            
    }

    /**
     * This method is called after a component is uninstalled.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function uninstall($parent) 
    {
        //echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
    }

    /**
     * This method is called after a component is updated.
     *
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function update($parent) 
    {
        //echo '<p>' . JText::sprintf('COM_HELLOWORLD_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
    }

    /**
     * Runs just before any installation action is performed on the component.
     * Verifications and pre-requisites should run in this function.
     *
     * @param  string    $type   - Type of PreFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function preflight($type, $parent) 
    {
        //echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
    }

    /**
     * Runs right after any installation action is performed on the component.
     *
     * @param  string    $type   - Type of PostFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    function postflight($type, $parent) 
    {
       // echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
       if ($type == 'uninstall') {
        return true;
        }
       $this->showInstallMessage('');
    }

    protected function showInstallMessage($messages=array()) {
        ?>
        <style>
        
	.mo-row {
		width: 100%;
		display: block;
		margin-bottom: 2%;
	}

	.mo-row:after {
		clear: both;
		display: block;
		content: "";
	}

	.mo-column-2 {
		width: 19%;
		margin-right: 1%;
		float: left;
	}

	.mo-column-10 {
		width: 80%;
		float: left;
	}
    </style>
    	<div class="mo-row">
            <p>Plugin package for <strong>Two Factor Authentication 2FA</strong> for Joomla</p>
            <h3>What does this plugin do?</h3>
            <p>miniOrange Joomla Two Factor Authentication (TFA/MFA) is a security feature that requires users to provide further proof of identity when accessing a Joomla website.</p>
            <h3>Steps to use the Joomla Two Factor Authentication plugin.</h3>
            <ul>
            <li>Go to <b>Components</b> menu.</li>
            <li>Click on <b>miniOrange Two Factor Authentication</b> and select <b>Account Setup </b>tab.</li>
            <li>You can login with miniOrange account credentials to activate the plugin.</li>
            <li>Now you can start configuring.</li>
            </ul>
            <a class="btn btn-secondary" style="background-color: #46a546; color : white"  href="index.php?option=com_miniorange_twofa&view=account_setup&tab-panel=account_setup">Start Using miniOrange 2FA plugin</a>
            <a class="btn btn-secondary" style="background-color: #46a546; color : white" href="https://plugins.miniorange.com/joomla-two-factor-authentication-2fa" target="_blank">Read the miniOrange documents</a>
		    <a class="btn btn-secondary" style="background-color: #46a546; color : white" href="https://www.miniorange.com/contact" target="_blank">Get Support!</a>
        </div>
        <?php
    }
  
}
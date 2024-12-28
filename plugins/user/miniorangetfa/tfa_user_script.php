<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
/**
 * @package     Joomla.User
 * @subpackage  User.miniorangetfa
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no
class plgUserMiniorangetfainstallerScript
{
    /**
     * This method is called after a component is installed.
     *
     * @param \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function install($parent)
    {

        $db  = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update('#__extensions');
        $query->set($db->quoteName('enabled') . ' = 1');
        $query->where($db->quoteName('element') . ' = ' . $db->quote('miniorangetfa'));
        $query->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
        $db->setQuery($query);
        $db->execute();
    }
    public function postflight($type, $parent) 
    {
       // echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
       if ($type == 'uninstall') {
        return true;
        }
       $this->showInstallMessage('');
    }

    protected function showInstallMessage($messages=array()) 
    {
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
    
        <h3>Steps to use the Joomla 2FA plugin.</h3>
        <ul>
        <li>Click on <b>Components</b></li>
        <li>Click on <b>miniOrange Two Factor Authentication<b> and select <b>Account Setup</b>tab</li>
        <li>You can now login into your miniOrange account to activate the plugin</li>
        <li>Now you can start configuring.</li>
        </ul>
            <div class="mo-row">
                <a class="btn btn-secondary" style="background-color: #46a546; color : white"  href="index.php?option=com_miniorange_twofa&tab-panel=demo_account">Start Using miniOrange 2FA plugin</a>
                <a class="btn btn-secondary" style="background-color: #46a546; color : white" href="https://plugins.miniorange.com/2fa-joomla-setup-guide-page" target="_blank">Read the miniOrange documents</a>
                <a class="btn btn-secondary" style="background-color: #46a546; color : white" href="https://www.miniorange.com/contact" target="_blank">Get Support!</a>
            </div>
            <?php
    }
}
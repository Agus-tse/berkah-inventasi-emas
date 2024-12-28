<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_twofa
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Class Miniorange_Controller
 *
 * @since  1.6
 */
class Miniorange_twofaController extends JControllerLegacy
{
    /**
     * Method to display a view.
     *
     * @param boolean $cachable If true, the view output will be cached
     * @param mixed $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return   JController This object to support chaining.
     *
     * @since    1.5
     */
    // public function display($cachable = false, $urlparams = false)
    // {
    //     $view = JFactory::getApplication()->input->getCmd('view', 'miniorange_twofa');
    //     JFactory::getApplication()->input->set('view', $view);

    //     parent::display($cachable, $urlparams);

    //     return $this;
    // }
    protected $default_view = 'account_setup';
}

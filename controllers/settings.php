<?php

/**
 * Apc controller.
 *
 * @category   apps
 * @package    apcups
 * @subpackage controllers
 * @author     eGloo <hello@egloo.ca>
 * @copyright  2016 eGloo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/apcups/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\apps\apcups\Apc as Apc;
use \clearos\apps\base\Engine_Exception as Engine_Exception;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Apc general settings controller.
 *
 * @category   apps
 * @package    apcups
 * @subpackage controllers
 * @author     eGloo <hello@egloo.ca>
 * @copyright  2016 eGloo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/apcups/
 */

class Settings extends ClearOS_Controller
{

    /**
     * Apc default controller
     *
     * @return view
     */

    function index()
    {
        $this->_view_edit();
    }

    /**
     * Apc edit controller
     *
     * @return view
     */

    function edit()
    {
        $this->_view_edit('edit');
    }

    /**
     * Apc view/edit controller
     *
     * @param string $mode mode
     *
     * @return view
     */
    function _view_edit($mode = 'view')
    {
        // Load dependencies
        //------------------

        $this->load->library('apcups/Apc');
        $this->lang->load('apcups');


        // Set validation rules
        //---------------------

        $this->form_validation->set_policy('powerdown', 'apcups/Apc', 'validate_powerdown', TRUE);
        $this->form_validation->set_policy('control_email', 'apcups/Apc', 'validate_email', TRUE);
        $form_ok = $this->form_validation->run();

        // Handle form submit
        //-------------------

        if (($this->input->post('submit') && $form_ok)) {
            try {
                $this->apc->set_powerdown($this->input->post('powerdown'));
                $this->apc->set_email($this->input->post('control_email'));
                if ($this->apc->is_reset_required())
                    $this->apc->reset();
                $this->page->set_status_updated();
                redirect('/apcups');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }

        // Load view data
        //---------------

        try {
            $data = $this->apc->get_status();
            $data['is_found'] = TRUE;
        } catch (Engine_Exception $e) {
            $data['error'] = clearos_exception_message($e);
            $data['is_found'] = FALSE;
            if ($mode == 'edit')
                redirect('/apcups');
        }
        $data['powerdown_options'] = $this->apc->get_powerdown_options();
        $data['control_email'] = $this->apc->get_email();
        $data['mode'] = $mode;

        // Load views
        //-----------

        $this->page->view_form('overview', $data, lang('apcups_app_name'));
    }
}

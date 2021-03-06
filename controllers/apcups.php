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
// C L A S S
///////////////////////////////////////////////////////////////////////////////

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

class ApcUPS extends ClearOS_Controller
{
    /**
     * ApcUPS default controller
     *
     * @return view
     */

    function index()
    {
        // Load libraries
        //---------------

        $this->lang->load('apcups');

        // Load views
        //-----------

        $views = array(
            'apcups/server',
            'apcups/settings'
        );

        $this->page->view_forms($views, lang('apcups_app_name'));
    }

    /**
     * APC UPS ajax status controller
     *
     * @return view
     */

    function status()
    {
        // Load libraries
        //---------------

        $this->lang->load('apcups');
        $this->load->library('apcups/Apc');

        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');
        try {
            echo json_encode(
                Array(
                    'code' => 0,
                    'status' => $this->apc->get_status()
                )
            );
        } catch (Exception $e) {
            echo json_encode(Array('code' => clearos_exception_code($e), 'errmsg' => clearos_exception_message($e)));
        }
    }
}

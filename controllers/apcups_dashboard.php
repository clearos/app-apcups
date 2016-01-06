<?php

/**
 * Apc dashboard controller.
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

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Apc UPS dashboard controller.
 *
 * @category   apps
 * @package    apcups
 * @subpackage controllers
 * @author     eGloo <hello@egloo.ca>
 * @copyright  2016 eGloo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/apcups/
 */

class Apcups_Dashboard extends ClearOS_Controller
{

    /**
     * Apc default controller
     *
     * @return view
     */

    function index()
    {
        clearos_profile(__METHOD__, __LINE__);

        $this->lang->load('apcups');
        $this->load->library('apcups/Apc');

        $data = $this->apc->get_status();

        // Load views
        //-----------

        $this->page->view_form('apcups/dashboard/status', $data, lang('apcups_app_name'));
    }
}

<?php

/**
 * Apc overview.
 *
 * @category   apps
 * @package    apcups
 * @subpackage views
 * @author     eGloo <hello@egloo.ca>
 * @copyright  2016 eGloo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearcenter.com/support/documentation/clearos/apcups/
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
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('base');
$this->lang->load('apcups');

if (isset($error))
    echo infobox_warning(lang('base_warning'), $error);

///////////////////////////////////////////////////////////////////////////////
// Form open
///////////////////////////////////////////////////////////////////////////////

echo form_open('apcups/settings/edit');
echo form_header(lang('base_summary'));

///////////////////////////////////////////////////////////////////////////////
// Form fields and buttons
///////////////////////////////////////////////////////////////////////////////

if ($mode === 'edit') {
    $read_only = FALSE;
    $buttons = array(
        form_submit_update('submit'),
        anchor_cancel('/app/apcups')
    );
} else {
    $read_only = TRUE;
    // Only display edit button if RAID is detected/supported
    if ($is_found)
        $buttons = array(anchor_edit('/app/apcups/settings/edit'));
}

echo field_input('model', $model, lang('apcups_model'), TRUE);
echo field_input('status', $status, lang('apcups_status'), TRUE);
echo field_input('load_percent', $loadpct, lang('apcups_load_percent'), TRUE);
echo field_input('battery_charge', $bcharge, lang('apcups_battery_charge'), TRUE);
echo field_input('battery_time_remaining', $timeleft, lang('apcups_battery_time_remaining'), TRUE);
echo field_input('battery_age', $battage, lang('apcups_battery_age'), TRUE);
echo field_dropdown('powerdown', $powerdown_options, $mbattchg, lang('apcups_powerdown'), $read_only);
echo field_input('control_email', $control_email, lang('apcups_admin_email_address'), $read_only);
echo field_button_set($buttons);

///////////////////////////////////////////////////////////////////////////////
// Form close
///////////////////////////////////////////////////////////////////////////////

echo form_footer();
echo form_close();

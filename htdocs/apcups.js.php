<?php

/**
 * Javascript helper for APC UPS.
 *
 * @category   apps
 * @package    apcups
 * @subpackage javascript
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
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('apcups');
clearos_load_language('base');

header('Content-Type: application/x-javascript');

?>

var lang_error = '<?php echo lang('base_error'); ?>';
var lang_start = '<?php echo lang('base_start'); ?>';
var lang_battery_remaining = '<?php echo lang('apcups_battery_remaining'); ?>';
$(document).ready(function() {
    if (!$('#submit_update').length)
        get_status();
    $('#clearos_daemon_action').on('click', function(e) {
        if ($('#clearos_daemon_action').html() == lang_start)
            window.setTimeout(get_status, 3000);
    });
});
function get_status() {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '/app/apcups/status',
        data: '',
        success: function(data) {
            if (data != undefined && data.code == 0) {
                $('#model_text').html(data.status.model);
                $('#status_text').html(data.status.status);
                if (data.status.status != 'ONLINE')
                    $('#status_text').addClass('theme-text-alert');
                else
                    $('#status_text').removeClass('theme-text-alert');
                $('#load_percent_text').html(data.status.loadpct);
                $('#battery_charge_text').html(data.status.bcharge);
                $('#battery_time_remaining_text').html(data.status.timeleft);
                $('#battery_age_text').html(data.status.battage);
                $('#powerdown_text').html(data.status.mbattchg + ' % ' + lang_battery_remaining);
                $('#connection_error').hide();
                window.setTimeout(get_status, 3000);
            } else {
                /* Causes pop-up on dashboard */
                /* clearos_dialog_box('error', lang_error, data.errmsg); */
            }
        }
    });
}

// vim: syntax=php ts=4

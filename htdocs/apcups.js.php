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

header('Content-Type: application/x-javascript');

echo "
  $(document).ready(function() {
    get_state();
  });
  function get_state() {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '/app/apcups/get_state',
        data: '',
        success: function(data) {
            if (data != undefined && data.code == undefined) {
                $.each(data, function(id, info) { 
                    $('#state-' + id).html(info.status);
                    if (info.degraded)
                        $('#state-' + id).addClass('theme-text-alert');
                })
            }
            window.setTimeout(get_state, 1000);
        }
    });
  }
";

// vim: syntax=php ts=4

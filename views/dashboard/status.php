<?php

/**
 * APC dashboard status view.
 *
 * @category   apps
 * @package    apcups
 * @subpackage views
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
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('base');
$this->lang->load('apcups');

$this->lang->load('base');

echo box_open($model, array('class' => 'apcups_status'));
echo box_content_open();
echo row_open();

echo column_open(6);
echo "<div class='theme-center-text'>";
echo "<div class='theme-biggest-text'>" . $status . "</div>";
echo "<div class='theme-smaller-text'>" . lang('apcups_status') . "</div>";
echo "</div>";
echo column_close();

echo column_open(6);
echo "<div class='theme-center-text'>";
echo "<div class='theme-biggest-text'>" . $loadpct . "</div>";
echo "<div class='theme-smaller-text'>" . lang('apcups_load_percent') . "</div>";
echo "</div>";
echo column_close();

echo row_close();
echo "<BR>";
echo row_open();

echo column_open(6);
echo "<div class='theme-center-text'>";
echo "<div class='theme-biggest-text'>" . $bcharge . "</div>";
echo "<div class='theme-smaller-text'>" . lang('apcups_battery_charge') . "</div>";
echo "</div>";
echo column_close();

echo column_open(6);
echo "<div class='theme-center-text'>";
echo "<div class='theme-biggest-text'>" . $timeleft_brief . "</div>";
echo "<div class='theme-smaller-text'>" . lang('apcups_battery_time_remaining_dashboard') . "</div>";
echo "</div>";
echo column_close();

echo row_close();
echo box_content_close();
echo box_close();


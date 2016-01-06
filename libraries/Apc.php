<?php

/**
 * APC class.
 *
 * @category   apps
 * @package    apcups
 * @subpackage libraries
 * @author     eGloo <hello@egloo.ca>
 * @copyright  2016 eGloo
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/apcups/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\apps\apcups;

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

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

// Classes
//--------

use \clearos\apps\base\Daemon as Daemon;
use \clearos\apps\base\Engine as Engine;
use \clearos\apps\base\File as File;
use \clearos\apps\base\Shell as Shell;

clearos_load_library('base/Daemon');
clearos_load_library('base/Engine');
clearos_load_library('base/File');
clearos_load_library('base/Shell');

// Exceptions
//-----------

use \Exception as Exception;
use \clearos\apps\base\Engine_Exception as Engine_Exception;
use \clearos\apps\base\Validation_Exception as Validation_Exception;

clearos_load_library('base/Engine_Exception');
clearos_load_library('base/Validation_Exception');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Apc class.
 *
 * @category   apps
 * @package    apcups
 * @subpackage libraries
 * @author     eGloo <hello@egloo.ca>
 * @copyright  2016 eGloo
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/apcups/
 */

class Apc extends Daemon
{
    ///////////////////////////////////////////////////////////////////////////////
    // C O N S T A N T S
    ///////////////////////////////////////////////////////////////////////////////

    const FILE_APC_CONFIG = '/etc/apcupsd/apcupsd.conf';
    const FILE_APC_CONTROL = '/etc/apcupsd/apccontrol';
    const CMD_APC_ACCESS = '/usr/sbin/apcaccess';

    ///////////////////////////////////////////////////////////////////////////////
    // V A R I A B L E S
    ///////////////////////////////////////////////////////////////////////////////

    protected $reset_required = FALSE;

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Apc constructor.
     */

    function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);
        parent::__construct('apcupsd');
    }

    /**
     * Get the notification email.
     *
     * @return String notification email
     * @throws Engine_Exception
     */

    function get_email()
    {
        clearos_profile(__METHOD__, __LINE__);

        $file = new File(self::FILE_APC_CONTROL, TRUE);
        
        try {
            return $file->lookup_value('/^export SYSADMIN=/');
        } catch (Exception $e) {
            throw new Engine_Exception(clearos_exception_message($e), CLEAROS_ERROR);
        }
    }

    /**
     * Set the APC powerdown battery percent.
     *
     * @param string $percent a valid integer
     *
     * @return void
     * @throws Engine_Exception Validation_Exception
     */

    function set_powerdown($powerdown)
    {
        clearos_profile(__METHOD__, __LINE__);

        $status = $this->get_status();
        if ($status['mbattchg'] == $powerdown)
            return;

        // Validation
        // ----------

        Validation_Exception::is_valid($this->validate_powerdown($powerdown));

        $file = new File(self::FILE_APC_CONFIG, TRUE);
        
        try {
            $file->replace_lines('/^BATTERYLEVEL\s+.*/', 'BATTERYLEVEL ' . $powerdown . "\n"); 
            $this->reset_required = TRUE;
        } catch (Exception $e) {
            throw new Engine_Exception(clearos_exception_message($e), CLEAROS_ERROR);
        }
    }

    /**
     * Set the APC notification email.
     *
     * @param string $email a valid email
     *
     * @return void
     * @throws Engine_Exception Validation_Exception
     */

    function set_email($email)
    {
        clearos_profile(__METHOD__, __LINE__);

        // Validation
        // ----------

        Validation_Exception::is_valid($this->validate_email($email));

        $file = new File(self::FILE_APC_CONTROL, TRUE);
        
        try {
            $file->replace_lines('/^export SYSADMIN=.*/', 'export SYSADMIN=' . $email . "\n"); 
        } catch (Exception $e) {
            throw new Engine_Exception(clearos_exception_message($e), CLEAROS_ERROR);
        }
    }

    /**
     * Get status.
     *
     * @return void
     * @throws Engine_Exception
     */

    function get_status()
    {
        clearos_profile(__METHOD__, __LINE__);

        $shell = new Shell();
        $args = '-u ';
        $options['env'] = "LANG=en_US";
        $retval = $shell->execute(self::CMD_APC_ACCESS, $args, FALSE, $options);
        if ($retval != 0) {
            $errstr = $shell->get_last_output_line();
            throw new Engine_Exception($errstr, COMMON_WARNING);
        }
        $lines = $shell->get_output();
        $data = array();
        foreach ($lines as $line) {
            if (preg_match("/(.*?):(.*)$/", $line, $match))
                $data[trim(strtolower($match[1]))] = trim($match[2]);
            if (preg_match('/loadpct|bcharge/', trim(strtolower($match[1])))) {
                $data[trim(strtolower($match[1]))] = trim($match[2]) . ' %';
            } else if (trim(strtolower($match[1])) == 'timeleft') {
                $data[trim(strtolower($match[1]))] = trim($match[2]) . ' ' . strtolower(lang('base_minutes'));
                $data['timeleft_brief'] = trim($match[2]);
            }
            if (trim(strtolower($match[1])) == 'battdate')
                $data['battage'] = floor(((time() - strtotime(trim($match[2]))) / (60*60*24))) . ' ' . strtolower(lang('base_days'));
        }
        return $data;
    }

    /**
     * Get powerdown options.
     *
     * @return array
     */

    function get_powerdown_options()
    {
        clearos_profile(__METHOD__, __LINE__);
        $options = array();
        for ($int = 5; $int <= 75; $int+=5)
            $options[$int] = $int . " % " . lang('apcups_battery_remaining');
        return $options;
    }

    /**
     * Is a daemon restart required.
     *
     * @return boolean
     */

    function is_reset_required()
    {
        clearos_profile(__METHOD__, __LINE__);
        return $this->reset_required;
    }

    ///////////////////////////////////////////////////////////////////////////////
    // P R I V A T E   M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////
    // V A L I D A T I O N   R O U T I N E S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Validation routine for email
     *
     * @param string $email email
     *
     * @return mixed string containing error if invalid
     */

    public function validate_email($email)
    {
        clearos_profile(__METHOD__, __LINE__);

        if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\\+\\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\\+\\._-]+)+$/", $email))
            return lang('base_email_address_invalid') . $email;
    }

    /**
     * Validation routine for powerdown
     *
     * @param string $percent percent to powerdown on
     *
     * @return mixed string containing error if invalid
     */

    public function validate_powerdown($powerdown)
    {
        clearos_profile(__METHOD__, __LINE__);

        if ($powerdown <= 0 || $powerdown >= 100)
            return lang('apcups_invalid_powerdown');
    }
}

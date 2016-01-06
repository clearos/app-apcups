<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'apcups';
$app['version'] = '2.0.0';
$app['release'] = '1';
$app['vendor'] = 'eGloo';
$app['packager'] = 'eGloo';
$app['license'] = 'GPLv3';
$app['license_core'] = 'LGPLv3';
$app['description'] = lang('apcups_app_description');

/////////////////////////////////////////////////////////////////////////////
// App name and categories
/////////////////////////////////////////////////////////////////////////////

$app['name'] = lang('apcups_app_name');
$app['category'] = lang('base_category_system');
$app['subcategory'] = lang('base_subcategory_backup');

/////////////////////////////////////////////////////////////////////////////
// Tooltips
/////////////////////////////////////////////////////////////////////////////

$app['tooltip'] = array(
    lang('apcups_tooltip_usb'),
    lang('apcups_tooltip_logo')
);

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['core_requires'] = array(
    'apcupsd'
);

$app['core_file_manifest'] = array(
    'apcupsd.php'=> array('target' => '/var/clearos/base/daemon/apcupsd.php'),
);

/////////////////////////////////////////////////////////////////////////////
// Dashboard Widgets
/////////////////////////////////////////////////////////////////////////////

$app['dashboard_widgets'] = array(
    $app['category'] => array(
        'apcups/apcups_dashboard' => array(
            'title' => lang('apcups_summary'),
            'restricted' => FALSE,
        )
    )
);

/////////////////////////////////////////////////////////////////////////////
// Delete Dependencies
/////////////////////////////////////////////////////////////////////////////

$app['delete_dependency'] = array(
    'app-apcups-core',
    'apcupsd'
);

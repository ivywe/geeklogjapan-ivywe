<?php

// +---------------------------------------------------------------------------+
// | reCAPTCHA Plugin for Geeklog - The Ultimate Weblog                        |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/recaptcha/autoinstall.php                                 |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2014-2017 mystral-kk - geeklog AT mystral-kk DOT net        |
// |                                                                           |
// | Based on the CAPTCHA Plugin by Ben                                        |
// |                                                - ben AT geeklog DOT fr    |
// | Based on the original CAPTCHA Plugin by Mark R. Evans                     |
// |                                                - mark AT glfusion DOT org |
// | Constructed with the Universal Plugin                                     |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+

if (stripos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false) {
	die('This file cannot be used on its own!');
}

/**
* Plugin autoinstall function
*
* @param    string  $pi_name    Plugin name
* @return   array               Plugin information
*/
function plugin_autoinstall_recaptcha($pi_name) {
	global $_RECAPTCHA_CONF;

	require_once dirname(__FILE__) . '/config.php';

	return array(
		'info'      => array(
			'pi_name'         => 'recaptcha',
			'pi_display_name' => 'reCAPTCHA',
			'pi_version'      => $_RECAPTCHA_CONF['pi_version'],
			'pi_gl_version'   => $_RECAPTCHA_CONF['gl_version'],
			'pi_homepage'     => $_RECAPTCHA_CONF['pi_url'],
		),
		'groups'    => $_RECAPTCHA_CONF['GROUPS'],
		'features'  => $_RECAPTCHA_CONF['FEATURES'],
		'mappings'  => $_RECAPTCHA_CONF['MAPPINGS'],
		'tables'    => array(),
	);
}

/**
* Load plugin configuration from database
*
* @param    string  $pi_name    Plugin name
* @return   boolean             true on success, otherwise false
* @see      plugin_initconfig_recaptcha
*/
function plugin_load_configuration_recaptcha($pi_name) {
    global $_CONF;

    $base_path = $_CONF['path'] . 'plugins/' . $pi_name . '/';

    require_once $_CONF['path_system'] . 'classes/config.class.php';
    require_once $base_path . 'install_defaults.php';

    return plugin_initconfig_recaptcha();
}

/**
* Checks if the plugin is compatible with this Geeklog version
*
* @param    string  $pi_name    Plugin name
* @return   boolean             true: plugin compatible; false: not compatible
*/
function plugin_compatible_with_this_version_recaptcha($pi_name) {
	global $_CONF;

	return (version_compare(PHP_VERSION, '5.3.0') >= 0);
}

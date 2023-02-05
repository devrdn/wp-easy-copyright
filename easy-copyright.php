<?php

/**
 * Plugin Name: Easy Copyright
 * Description: A plugin to manage copyright information
 * Version: 1.0.0
 * Author: rdndev
 * License: GPLv2 or later
 * 
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 * Copyright 2023rdndev.
 * 
 * 
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Copyright: rdndev
 * Text Domain: easy-copy
 * Domain Path: /lang
 */


// Die if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
   _e( 'Hello, i\'m just plugin, and i\'m called when wordpress call me!', 'easy-copy');
   die();
}

// Constants
define( 'EASYCOPYRIGHT__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'EASYCOPYRIGHT__ASSETS_DIR', plugins_url( 'assets', __FILE__) );
define( 'EASYCOPYRIGHT__LANGUAGE_DIR', dirname( plugin_basename( __FILE__ ) ) . '/lang' );

// Require includes files for the plugin.
require_once( EASYCOPYRIGHT__PLUGIN_DIR . 'inc/class-easycopyright-custompost.php' );

require_once( EASYCOPYRIGHT__PLUGIN_DIR . 'inc/class-easycopyright-shortcode.php' );

require_once( EASYCOPYRIGHT__PLUGIN_DIR . 'inc/class-easycopyright.php' );

require_once( EASYCOPYRIGHT__PLUGIN_DIR . 'inc/class-easycopyright-widget.php' );

// Initialize the base class.
new EasyCopyright();

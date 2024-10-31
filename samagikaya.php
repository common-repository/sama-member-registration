<?php
/**
 * Plugin Name: SAMA member registration
 * Plugin URI:
 * Description: SAMA member is a simple member registration system.
 * Version: 1.2.1
 * Author: WAP Nishantha <wapnishantha@gmail.com>
 * Author URI: https://bitbucket.org/wapnishantha/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Repo: https://bitbucket.org/wapnishantha/samagikaya/src/master/
 *
 * @package           SAMA
 **/

define( 'SAMA_PATH', dirname( __FILE__ ) );
define( 'SAMA_URL_PATH', plugin_dir_url( __FILE__ ) );

require SAMA_PATH . '/activate.php';
require SAMA_PATH . '/deactivate.php';

/**
 * Activation and Deactivation hooks
 */
register_activation_hook( __FILE__, 'sama_activate' );
register_deactivation_hook( __FILE__, 'sama_deactivate' );

require SAMA_PATH . '/controllers/class-sama-dashboards.php';

add_action( 'init', array( 'Sama_Dashboards', 'init' ) );
add_action( 'admin_menu', array( 'Sama_Dashboards', 'menu' ) );


require SAMA_PATH . '/models/class-sama-member.php';
require SAMA_PATH . '/controllers/class-sama-members.php';

require SAMA_PATH . '/models/class-sama-register.php';
require SAMA_PATH . '/controllers/class-sama-registers.php';

require SAMA_PATH . '/models/class-sama-subscription.php';
require SAMA_PATH . '/models/class-sama-cron.php';
require SAMA_PATH . '/models/class-sama-email.php';
require SAMA_PATH . '/controllers/class-sama-subscriptions.php';
require SAMA_PATH . '/controllers/class-sama-paypals.php';


/*Front end short code*/
add_shortcode( 'sama-registration', array( 'Sama_Registrations', 'sama_registration_form' ) );


add_shortcode( 'sama-paypal', array( 'sama_Subscriptions', 'sama_paypal_form' ) );

add_action( 'show_user_profile', array( 'Sama_Registrations', 'sama_show_extra_profile_fields' ) );

add_action( 'edit_user_profile', array( 'Sama_Registrations', 'sama_show_extra_profile_fields' ) );
add_action( 'edit_user_profile_update', array( 'Sama_Registrations', 'sama_update_profile_fields' ) );
add_action( 'personal_options_update', array( 'Sama_Registrations', 'sama_update_profile_fields' ) );


add_filter( 'plugin_action_links', 'sama_plugin_action_links', 10, 2 );

function sama_plugin_action_links( $links, $file ) {
    $plugin_file = basename( __FILE__ );
    if ( basename( $file ) == $plugin_file ) {
        $settings_link = '<a href="' . admin_url( 'admin.php?page=sama_settings' ) . '">Settings</a>';
        array_unshift( $links, $settings_link );
    }

    return $links;
}





<?php
/**
 * Plugin Name: WooCommerce Redsys
 * Plugin URI: http://soportewordpress.net/woocommerce-redsys
 * Description: Integration Gateway Redsys.
 * Version: 1.0.0
 * Author: David Táboas
 * Author URI: http://davidtaboas.es
 * Developer: Your Name
 * Developer URI: http://davidtaboas.es
 * Text Domain: woocommerce-redsys
 * Domain Path: /languages
 *
 * Copyright: © 2015 David Táboas.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}



if ( ! class_exists( 'WC_Redsys_Gateway' ) ) :

class WC_Redsys_Gateway {


  /**
   * Instance of this class.
   *
   * @var object
   */
  protected static $instance = null;


  /**
  * Construct the plugin.
  */
  public function __construct() {


    // Load Translation for default options
    $plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    $locale = apply_filters( 'plugin_locale', get_locale() );
    $domain = 'woocommerce-redsys';
    $mofile = $plugin_path . '/languages/'.$domain.'.mo';

    if ( file_exists( $plugin_path . '/languages/'.$domain.'-' . $locale . '.mo' ) )
      $mofile = $plugin_path . '/languages/'.$domain.'-' . $locale . '.mo';

    load_textdomain( 'woocommerce-redsys', $mofile );


    // Checks if WooCommerce is installed.
    if ( class_exists( 'WC_Payment_Gateway' ) && defined( 'WOOCOMMERCE_VERSION' ) && version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
      // Include our integration class.
      include_once 'inc/class-wc-redsys.php';


    }
    else {
      add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
    }

  }


  /**
   * Return an instance of this class.
   *
   * @return object A single instance of this class.
   */
  public static function get_instance() {
    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;
  }


  /**
   * WooCommerce fallback notice.
   *
   * @return string
   */
  public function woocommerce_missing_notice() {
    echo '<div class="error"><p>' . sprintf( __( 'WooCommerce RedSys depends on the last version of %s to work!', 'woocommerce-redsys' ), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">' . __( 'WooCommerce', 'woocommerce-redsys' ) . '</a>' ) . '</p></div>';
  }

  /**
   * Get the plugin path.
   *
   * @return string
   */
  public function plugin_path() {
    return untrailingslashit( plugin_dir_path( __FILE__ ) );
  }





}//end


/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
  add_action( 'plugins_loaded', array( 'WC_Redsys_Gateway', 'get_instance' ), 0 );
}


endif;

<?php
/**
 * Plugin Name: Extends woocommerce coupon
 * Plugin URI: 
 * Description: Extends woocommerce coupon.
 * Version: 1.0.0
 * Author: GnnSAlexander
 * Author URI: 
 * Text Domain: woocommerce
 * Domain Path: 
 *
 * 
 */

defined( 'ABSPATH' ) || exit;

// Include the dependencies needed to instantiate the plugin.
foreach ( glob( plugin_dir_path( __FILE__ ) . 'admin/*.php' ) as $file ) {
    include_once $file;
  }
  
  function app_output_buffer() {
      ob_start();
  } // soi_output_buffer
  add_action('init', 'app_output_buffer');
  
  add_action( 'plugins_loaded', 'admin_settings' );
  /**
  * Starts the plugin.
  *
  * @since 1.0.0
  */
  function admin_settings() {

    
    $db = new ModelCoupon();
    $db->init();
    $plugin = new ExtendsCouponAdmin( new ExtendsCoupon( $db ) );
    $plugin->init();

  }


function db_create() {
 
    global $wpdb;
    $coupon = $wpdb->prefix . "ec_coupon";
    $charset_collate = $wpdb->get_charset_collate();
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  
    $created = dbDelta(  
      "CREATE TABLE IF NOT EXISTS $coupon (
          `id` INT NOT NULL AUTO_INCREMENT,
          `post_id` INT NOT NULL,
          `post_title` VARCHAR(45) NULL,
          PRIMARY KEY (`id`)
      ) $charset_collate;"
    );
  
    $coupon_product = $wpdb->prefix . "ec_coupon_to_product";
  
    $created = dbDelta(
        "CREATE TABLE IF NOT EXISTS $coupon_product(
            `coupon_id` INT NOT NULL,
            `product_id` INT NOT NULL,
            `discount_type` VARCHAR(200),
            `coupon_amount` INT NOT NULL,
            FOREIGN KEY (`coupon_id`) REFERENCES $coupon(`id`)
        ) $charset_collate;"
    );
  
  
  } 
  register_activation_hook( __FILE__, 'db_create' );
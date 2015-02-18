<?php
/*
Plugin Name: Pojo News Ticker
Plugin URI: http://pojo.me/
Description: 
Author: Pojo Team
Author URI: http://pojo.me/
Version: 1.0.0
Text Domain: pojo-news-ticker
Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'POJO_NEWS_TICKER__FILE__', __FILE__ );
define( 'POJO_NEWS_TICKER_BASE', plugin_basename( POJO_NEWS_TICKER__FILE__ ) );
define( 'POJO_NEWS_TICKER_URL', plugins_url( '/', POJO_NEWS_TICKER__FILE__ ) );
define( 'POJO_NEWS_TICKER_ASSETS_PATH', plugin_dir_path( POJO_NEWS_TICKER__FILE__ ) . 'assets/' );
define( 'POJO_NEWS_TICKER_ASSETS_URL', POJO_NEWS_TICKER_URL . 'assets/' );

final class Pojo_News_Ticker {

	/**
	 * @var Pojo_News_Ticker The one true Pojo_News_Ticker
	 * @since 1.0.0
	 */
	private static $_instance = null;

	public function load_textdomain() {
		load_plugin_textdomain( 'pojo-news-ticker', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'pojo-news-ticker' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'pojo-news-ticker' ), '1.0.0' );
	}

	/**cd
	 * @return Pojo_News_Ticker
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new Pojo_News_Ticker();

		return self::$_instance;
	}

	public function bootstrap() {
		// This plugin for Pojo Themes..
		// TODO: Add notice for non-pojo theme
		if ( ! class_exists( 'Pojo_Core' ) )
			return;
		
	}

	private function __construct() {
		add_action( 'init', array( &$this, 'bootstrap' ), 100 );
		add_action( 'plugins_loaded', array( &$this, 'load_textdomain' ) );
	}

}

Pojo_News_Ticker::instance();
// EOF
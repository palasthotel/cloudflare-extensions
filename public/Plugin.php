<?php

/**
 * Plugin Name: Cloudflare Extensions
 * Description: Boost and extend Cloudflare plugin.
 * Version: 1.0.1
 * Author: PALASTHOTEL (by Edward Bock)
 * Author URI: https://palasthotel.de
 */

namespace Palasthotel\CloudflareExtensions;


class Plugin {

    public Database $database;
    public CloudflareHooks $cloudflareHooks;
    public Notice $notice;

	const SCHEDULE_PURGE_QUEUE = "cloudflare_ext_purge_queue";

	const OPTION_PURGE_QUEUE_FINISHED = "_cloudflare_ext_purge_queue_finished";

	private function __construct() {

		require_once dirname(__FILE__)."/vendor/autoload.php";

		$this->database = new Database();
		$this->cloudflareHooks = new CloudflareHooks($this);
		$this->notice = new Notice($this);

		register_activation_hook( __FILE__, array( $this, "activation" ) );
		if(WP_DEBUG) $this->database->createTable();

		require_once dirname(__FILE__)."/classes/CLI.php";
	}

	/**
	 * on plugin activation
	 */
	function activation() {
		$this->database->createTable();
	}

	/**
	 * @return Plugin
	 */
	public static function instance(){
		if(static::$instance == null){
			static::$instance = new Plugin();
		}
		return static::$instance;
	}
	private static $instance;

}

Plugin::instance();

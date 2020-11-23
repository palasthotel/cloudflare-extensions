<?php


namespace Palasthotel\CloudflareExtensions;


use wpdb;

/**
 * @property  wpdb $wpdb
 * @property string table
 */
class Database {

	/**
	 * Database constructor.
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table = $wpdb->prefix . "cloudflare_purge_queue";
	}

	/**
	 * @param $post_id
	 *
	 * @return bool|int
	 */
	public function addToQueue($post_id){
		return $this->wpdb->replace(
			$this->table,
			[
				"post_id" => $post_id,
			],
			[ "%d"]
		);
	}

	/**
	 * @param $post_id
	 *
	 * @return bool|int
	 */
	public function removeFromQueue($post_id){
		return $this->wpdb->delete(
			$this->table,
			[
				"post_id" => $post_id,
			],
			[ "%d" ]
		);
	}

	/**
	 * @return array
	 */
	public function getQueue(){
		return $this->wpdb->get_col("SELECT post_id FROM $this->table");
	}

	/**
	 * create tables if they do not exist
	 */
	function createTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		\dbDelta( "CREATE TABLE IF NOT EXISTS $this->table
			(
			 post_id bigint(20) unsigned not null,
			 primary key (post_id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );
	}



}
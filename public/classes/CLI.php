<?php
namespace Palasthotel\CloudflareExtensions;

use CF\WordPress\Hooks;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

class CLI {

	/**
	 * Run purge queue
	 *
	 *
	 * ## EXAMPLES
	 *
	 *     wp cloudflare-ext purgeQueue
	 *
	 * @when after_wp_load
	 */
	public function purgeQueue($args, $assoc_args){


		$plugin = Plugin::instance();

		$post_ids = $plugin->database->getQueue();
		if(!is_array($post_ids)){
			\WP_CLI::error("could not get post ids");
			exit;
		}
		if(count($post_ids) == 0){
			\WP_CLI::success("Queue is clean");
			$plugin->notice->purgeQueueFinished();
			exit;
		}


		$cloudflareHooks = new Hooks();

		foreach ($post_ids as $post_id){
			$cloudflareHooks->purgeCacheByRelevantURLs($post_id);
			$plugin->database->removeFromQueue($post_id);
			\WP_CLI::log("purged $post_id");
		}

		$plugin->notice->purgeQueueFinished();

		\WP_CLI::success("Purged!");
	}

}

\WP_CLI::add_command( 'cloudflare-ext', __NAMESPACE__.'\CLI', array(
	'shortdesc' => 'extension function for cloudflare',
));
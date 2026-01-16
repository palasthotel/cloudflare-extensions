<?php


namespace Palasthotel\CloudflareExtensions;


class CloudflareHooks extends _Component {

    public Plugin $plugin;

	public function onCreate() {
		parent::onCreate();
		add_filter('cloudflare_purge_by_url', [$this, 'cloudflare_purge_by_url'], 10, 2);
	}

	public function cloudflare_purge_by_url($urls, $post_id){
		$this->plugin->database->addToQueue($post_id);
		return [ get_permalink($post_id) ];
	}

}

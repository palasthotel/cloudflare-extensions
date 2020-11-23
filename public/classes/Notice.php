<?php


namespace Palasthotel\CloudflareExtensions;


class Notice extends _Component {

	public function onCreate() {
		parent::onCreate();
		if( $this->getPurgeQueueFinished() + MINUTE_IN_SECONDS * 6 < time() ){
			add_action('admin_notices', [$this, 'purge_notice']);
		}
	}

	public function getPurgeQueueFinished(){
		return intval(get_option(Plugin::OPTION_PURGE_QUEUE_FINISHED, 0));
	}

	public function purgeQueueFinished(){
		update_option(Plugin::OPTION_PURGE_QUEUE_FINISHED, time());
	}

	public function purge_notice(){
		$time = new \DateTime();
		$time->setTimestamp($this->getPurgeQueueFinished());
		$time->setTimezone(wp_timezone());
		printf(
			'<div class="notice %1$s"><h2>Cloudflare Extensions</h2><div><p>%2$s</p></div></div>',
			'notice-warning',
			'Purge queue has not been run since <strong>'.$time->format("Y/m/d h:i")."</strong>. Please add a cronjob that runs at least every 5 minutes that executes <code>wp cloudflare-ext purgeQueue</code>."
		);
	}
}
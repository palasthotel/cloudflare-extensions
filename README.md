# Cloudflare Extensions

This plugin extends and performance optimizes the [cloudflare plugin](https://wordpress.org/plugins/cloudflare/) for WordPress.

## Purge queue

We use the filter 'cloudflare_purge_by_url' to remove purge requests from save_post actions. Theirfore you need to run a cronjob that executes `wp cloudflare-ext purgeQueue` at least every 5 minutes better every minute. 
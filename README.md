# Cloudflare Extensions

This plugin extends and performance optimizes the [cloudflare plugin](https://wordpress.org/plugins/cloudflare/) for WordPress.

## Maintenance Status

⚠️ This project is no longer actively maintained.

The repository will remain available for reference, but no new features, bug fixes, or support should be expected.

## Purge queue

We use the filter 'cloudflare_purge_by_url' to remove purge requests from save_post actions. Theirfore you need to run a cronjob that executes `wp cloudflare-ext purgeQueue` at least every 5 minutes better every minute. 

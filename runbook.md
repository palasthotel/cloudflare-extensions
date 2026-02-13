# Setup
resource/ and .wp-env.json are part of the development environment.

Start wordpress with `wp-env start`

setup source:
https://flickerleap.com/setting-up-wp-env-for-local-development/


# Cloudflare Extensions – Technical Summary

## Overview

**Cloudflare Extensions** is a WordPress plugin that extends the official Cloudflare WordPress plugin.

Its main purpose is to:

- Limit Cloudflare cache purges to **only the post permalink**
- Store post IDs in a **custom purge queue table**
- Provide a **WP-CLI command** to manually process that purge queue

It does **not** directly call the Cloudflare API itself.  
Instead, it reuses the official Cloudflare plugin’s purge logic.

---

## How It Works

### 1. Hooks into Cloudflare’s Purge Process

The plugin attaches to this filter provided by the official Cloudflare plugin:

```php
apply_filters('cloudflare_purge_by_url', $urls, $postId);
```

When this filter runs, the extension:

- Adds the `$postId` to a custom database table (`wp_cloudflare_purge_queue`)
- Replaces the purge URL list with:

```php
[get_permalink($postId)]
```

### Effect

Instead of purging:
- Post URL
- Homepage
- Category archives
- Tag archives
- Feeds
- etc.

It only purges:

```
https://example.com/my-post/
```

This reduces the number of URLs purged per content update.

---

## Database Queue

The plugin creates a custom table:

```
wp_cloudflare_purge_queue
```

### Table structure

| Column   | Type     | Notes        |
|----------|----------|-------------|
| post_id  | bigint   | Primary key |

### Behavior

- `addToQueue($post_id)` uses `REPLACE` → no duplicates
- `getQueue()` returns all queued post IDs
- `removeFromQueue($post_id)` deletes an entry
- Table contains no timestamps or retry logic

The queue simply stores unique post IDs waiting to be processed.

---

## WP-CLI Command

The plugin registers:

```bash
wp cloudflare-ext purgeQueue
```

### What it does

1. Reads all post IDs from the queue
2. Adds hard-coded post ID `439861`
3. For each post ID:
   - Calls the official Cloudflare plugin’s:
     ```php
     purgeCacheByRelevantURLs($post_id)
     ```
   - Removes it from the queue
4. Marks the queue as finished (via Notice class)


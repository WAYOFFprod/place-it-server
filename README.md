# setup

## Create TOken

`php artisan app:create-token`

# tips

## Migrations

run migration:fresh with `--drop-views` to drop views

```php
--drop-views
```

## nginx in prod cookie size

In nginx proxy manager add these values

```
large_client_header_buffers 4 32k;
client_header_buffer_size 32k;
proxy_buffer_size 32k;
proxy_buffers 4 32k;
proxy_busy_buffers_size 32k;
```

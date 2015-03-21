#StayClassy API Wordpress Plugin
===

## Setup 
Add your Classy Token and Classy Charity ID to your Wordpress `wp-config.php` file.

```php
define('CLASSY_TOKEN', 'xxxxxxxxxxxxxxxxxxxxx');
define('CLASSY_CHARITY_ID', '00000');
```

Activate the plugin in your Wordpress Dashboard and you should be ready to start using the API.

[StayClassy API documentation](http://go.stayclassy.org/hs-fs/hub/190333/file-1586506388-pdf/StayClassy_API_v1.1_FINAL_%281%29.pdf)

## Examples

```php
Classy_API::get_campaigns(array('eid' => 000000));
Classy_API::get_campaign(000000);
Classy_API::get_campaign_tickets(000000);
```

## Debugging
In `wp-config.php` add the following: 

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

Run this in a terminal to follow the debug logs
```sh
tail -f /wp-content/debug.log
```
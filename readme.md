# WordPress Rest API APP

Sample WordPress Rest API application with PHP

More about WordPress Rest API, visit: https://developer.wordpress.org/rest-api/

## Requirement on WordPress site

`#1` WordPress version 4.7 or later

`#2` Installed plugin **JWT Authentication for WP-API**

`#3` Installed plugin **ACF to REST API**

`#4` Installed plugin **Advanced Custom Fields**

### Enable auth header

Add below lines to `.htaccess` line inside tag `<IfModule>`

```
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule ^(.*) - [E=HTTP_AUTHORIZATION:%1]
SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1
```

### JWT Authentication secret key

Add below lines to `wp-config.php`

```
/** JWT_AUTH */
define('JWT_AUTH_SECRET_KEY', 'replace me with wordpress's salt string');
```
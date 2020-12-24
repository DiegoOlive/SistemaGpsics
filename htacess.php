RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
php_value output_buffering on

php_value display_errors 1

# HTID:14364318: DO NOT REMOVE OR MODIFY THIS LINE AND THE LINES BELOW
Redirect 302 /https:/gpsics.000webhostapp.com http://gpsics.000webhostapp.com/gpsics_index
# DO NOT REMOVE OR MODIFY THIS LINE AND THE LINES ABOVE HTID:14364318:



# HTID:14389764: DO NOT REMOVE OR MODIFY THIS LINE AND THE LINES BELOW
allow from 177.37.131.222
# DO NOT REMOVE OR MODIFY THIS LINE AND THE LINES ABOVE HTID:14389764:

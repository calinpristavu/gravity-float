upstream app {
    server 127.0.0.1:9000;
}

server {
    listen  80;

    server_name {{ nginx_servername }};
    root {{ nginx_docroot }};

    error_log /var/log/nginx/gravity-float_error.log;
    access_log /var/log/nginx/gravity-float_access.log;

    gzip            on;
    gzip_min_length 1000;
    gzip_comp_level 9;
    gzip_proxied    any;
    gzip_types      application/javascript application/x-javascript application/json text/css;

    client_max_body_size {{ upload_max_filesize }};

    location / {
        # try to serve file directly, fallback to app.php
        try_files $uri /{{ nginx_index }}$is_args$args;
    }

    # DEV
    # This rule should only be placed on your development environment
    # In production, don't include this and don't deploy app_dev.php or config.php
    location ~ ^/(app_dev|config)\.php(/|$) {
        fastcgi_pass app;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        # When you are using symlinks to link the document root to the
        # current version of your application, you should pass the real
        # application path instead of the path to the symlink to PHP
        # FPM.
        # Otherwise, PHP's OPcache may not properly detect changes to
        # your PHP files (see https://github.com/zendtech/ZendOptimizerPlus/issues/126
        # for more information).
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;

        # Added to avoid 502 Bad Gateway errors
        fastcgi_buffer_size 512k;
        fastcgi_buffers 16 512k;
    }

    # PROD
    location ~ ^/app\.php(/|$) {
        fastcgi_pass app;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        # When you are using symlinks to link the document root to the
        # current version of your application, you should pass the real
        # application path instead of the path to the symlink to PHP
        # FPM.
        # Otherwise, PHP's OPcache may not properly detect changes to
        # your PHP files (see https://github.com/zendtech/ZendOptimizerPlus/issues/126
        # for more information).
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        # Prevents URIs that include the front controller. This will 404:
        # http://domain.tld/app.php/some-path
        # Remove the internal directive to allow URIs like this
        internal;
    }

    # return 404 for all other php files not matching the front controller
    # this prevents access to other php files you don't want to be accessible.
    location ~ \.php$ {
        return 404;
    }

    location ~* \.(otf|eot|svg|ttf|woff|woff2) {
        add_header 'Access-Control-Allow-Origin' '*';
    }

    location ~* \.(css|txt|xml|js|ico)$ {
        expires 1y;
        log_not_found off;
    }

    location ~* \.(gif|jpe?g|png)$ {
        try_files $uri /{{ nginx_index }}$is_args$args;
        expires 1y;
        log_not_found off;
    }
}

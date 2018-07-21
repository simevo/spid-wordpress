# spid-wordpress

**Plugin WordPress** per l'autenticazione attraverso un Identity Provider **SPID** (Sistema Pubblico di Identità Digitale).

Compatibile con:
- Wordpress 4.7.5 - 4.9.7 (TODO)
- PHP 7.0 e 7.2 (TODO)
- single-site o multi-site (TODO)
- installazione WordPress manuale, [composer](https://packagist.org/packages/johnpbloch/wordpress) oppure pacchetto nativo.

## Getting Started

In questa guida si è scelto di installare WordPress per mezzo del pacchetto nativo.

Testato su: Debian 9.4 (stretch) amd64

### Prerequisiti

```
sudo apt install wordpress nginx
```

### Configuring and Installing

Riferimenti per la configurazione WordPress generica:
- http://codex.wordpress.org/Editing_wp-config.php

Riferimenti per la configurazione del pacchetto WordPress nativo di Debian:
- https://wiki.debian.org/WordPress
- https://salsa.debian.org/debian/wordpress/blob/master/debian/README.debian

Configurare WordPress nel file `/etc/wordpress/config-default.php` a partire da `/usr/share/wordpress/wp-config-sample.php`
Questo file dovrebbe essere leggibile solo da www-data e protetto in scrittura.

Configurazione nginx:
```
server_name: "{{ wordpress_hostname }}"
root: "/usr/share/wordpress"
index: "index.php"
  gzip_static on;
  add_header Cache-Control "no-cache";
  etag on;
  rewrite_log on;
  location ~ ^(?!\/api\/).+\.php$ {
    include snippets/fastcgi-php.conf;
    fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
  }
  location ~ ^/wp-content/(.*)$ {
    /srv/www/wp-content/%{HTTP_HOST}/$1
  }
  Alias /wp-content /var/lib/wordpress/wp-content
```

Assegnare www-data come owner delle subdirectories "uploads" e "blogs.dir" 

Clonare questo repo in /var/lib/wordpress/wp-content/plugins/spid-wordpress quindi lanciare `composer install` da quella directory

### Demo

TODO

## Testing

### Tests manuali

TODO

### Linting

Questo progetto segue le linee guida [PSR-2: Coding Style Guide](https://www.php-fig.org/psr/psr-2/).

Verificare che il codice sia pulito con:
```
./vendor/bin/phpcs --standard=PSR2 xxx.php
```

## Contributing

Per contribuire a questo repo si prega di usare il [git-flow workflow](https://danielkummer.github.io/git-flow-cheatsheet/).

## Authors

TODO

## License

Copyright (c) 2018, Paolo Greppi simevo s.r.l.
Licenza: AGPL 3, vedi [LICENSE](LICENSE).

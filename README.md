# spid-wordpress

**Plugin WordPress** per l'autenticazione attraverso un Identity Provider **SPID** (Sistema Pubblico di Identità Digitale) basato sulla libreria SPID PHP [italia/spid-php-lib](https://github.com/italia/spid-php-lib).

Compatibile con:
- Wordpress 4.7.5 - 4.9.7 (TODO)
- PHP 7.0 e 7.2
- single-site o multi-site (TODO)
- installazione WordPress manuale, [composer](https://packagist.org/packages/johnpbloch/wordpress) oppure pacchetto nativo.

## Getting Started

Testato su: amd64 Debian 9.5 (stretch, current stable) con PHP 7.0.

### Prerequisiti

```sh
sudo apt install composer make openssl php-curl php-zip php-xml
```

### Installazione e configurazione

### Manuale

Installare e configurare wordpress nel modo preferito.

Clonare questo repo in `/var/lib/wordpress/wp-content/plugins/spid-wordpress` quindi lanciare `composer install` da quella directory

### Ansible

Alternativamente alla procedura di installazione manuale riportata sopra, è possible installare un sito WordPress di test con questo plugin installato, tramite lo strumento di configuration management [ansible](https://www.ansible.com/). Tutte le informazioni sono nella directory [ansible/](ansible/).

### Demo

TODO

## Troubleshooting

Installazione e uso della WordPress cli:
```sh
cd /srv/spid-wordpress
composer require wp-cli/wp-cli
/srv/spid-wordpress/vendor/bin/wp --path=/usr/share/wordpress/ plugin list
```

Disattivazione di tutti i plugin via database:
```sql
mysql -u wp wp -p
SHOW tables;
SHOW columns FROM wp_options;
SELECT option_name FROM wp_options;
SELECT * FROM wp_options WHERE option_name='active_plugins';
UPDATE wp_options SET option_value = '' WHERE option_name = 'active_plugins';
```

## Testing

### Tests manuali

TODO

### Linting

Questo progetto segue le linee guida [PSR-2: Coding Style Guide](https://www.php-fig.org/psr/psr-2/).

Verificare che il codice sia pulito con:
```sh
./vendor/bin/phpcs --standard=PSR2 xxx.php
```

## Contributing

Per contribuire a questo repo si prega di usare il [git-flow workflow](https://danielkummer.github.io/git-flow-cheatsheet/).

## Authors

Paolo Greppi

## License

Copyright (c) 2018 simevo s.r.l.
Licenza: AGPL 3, vedi [LICENSE](LICENSE).

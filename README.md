# spid-wordpress

**Plugin WordPress** per l'autenticazione attraverso un Identity Provider **SPID** (Sistema Pubblico di Identità Digitale) basato sulla libreria SPID PHP [italia/spid-php-lib](https://github.com/italia/spid-php-lib).

Compatibile con:
- Wordpress 4.7.5 - 4.9.7 (TODO)
- PHP 7.0 e 7.2 (TODO)
- single-site o multi-site (TODO)
- installazione WordPress manuale, [composer](https://packagist.org/packages/johnpbloch/wordpress) oppure pacchetto nativo.

## Getting Started

Testato su: amd64 Debian 9.5 (stretch, current stable) con PHP 7.0.

### Installazione e configurazione

Prima di usare questo pacchetto, è necessario:

1. Installare i prerequisiti:

```sh
sudo apt install composer make openssl php-curl php-zip php-xml
```

2. Installare le dipendenze con `composer install`

3. Generare chiave e certificato del Service Provider (SP)

4. Scaricare e verificare i metadata degli Identity Provider (IdP) nella directory [idp_metadata/](idp_metadata/); un tool per automatizzare questa operazione per gli IdP in produzione è incluso in spid-php-lib, esempio di utilizzo:

```sh
./vendor/italia/spid-php-lib/bin/download_idp_metadata.php /srv/spid-wordpress/idp_metadata
```

5. Scaricare il metadata del SP (Service Provider) da https://wp.example.com/wp-login.php?sso=spid&metadata e registrarlo coll'IdP

**NOTA**: durante il test, si raccomanda l'uso dell'Identity Provider di test [spid-testenv2](https://github.com/italia/spid-testenv2).

### Installazione manuale

Installare e configurare wordpress nel modo preferito.

Clonare questo repo in `/var/lib/wordpress/wp-content/plugins/spid-wordpress` quindi completare i passi 1-5.

### Installazione con ansible

Alternativamente alla procedura di installazione manuale riportata sopra, è possible installare un sito WordPress di test con questo plugin installato, tramite lo strumento di configuration management [ansible](https://www.ansible.com/). Tutte le informazioni sono nella directory [ansible/](ansible/).

Il ruolo ansible effettua i passi 1-3, restano a carico dell'utente i passi 4-5 (registrazione metadata IdP con SP e registrazione metadata SP con IdP).

### Installazione con docker-compose

Based on the [official docker wordpress image](https://docs.docker.com/compose/wordpress).

To start up:
```
composer install --no-dev
cd docker
cp .env.example .env
make
docker-compose up --build
```

Then in a separate shell:
```sh
make post
```

To remove the containers and default network, but preserve the database: `docker-compose down`

To remove all: `docker-compose down --volumes`

### Uso

Visitare: https://wp.example.com/wp-login.php e cliccare sul bottone SPID (TODO: al momento, sul bottone `Accedi con SPID usando testenv2 come IdP`)

Questo screencast mostra cosa dovrebbe succedere se tutto funziona:

![img](images/screencast.gif)

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

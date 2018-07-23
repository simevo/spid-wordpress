# spid-wordpress

**Plugin WordPress** per l'autenticazione attraverso un Identity Provider **SPID** (Sistema Pubblico di Identità Digitale).

Compatibile con:
- Wordpress 4.7.5 - 4.9.7 (TODO)
- PHP 7.0 e 7.2 (TODO)
- single-site o multi-site (TODO)
- installazione WordPress manuale, [composer](https://packagist.org/packages/johnpbloch/wordpress) oppure pacchetto nativo.

## Getting Started

Testato su: Debian 9.4 (stretch) amd64

### Prerequisiti

```
sudo apt install composer make openssl php-curl php-zip php-xml
```
con PHP <= 7.1 (es. Debian 9.4 stretch o precedenti), anche:
```
apt install php-mcrypt
```

### Installazione e configurazione

### Manuale

Installare e configurare wordpress nel modo preferito.

Clonare questo repo in `/var/lib/wordpress/wp-content/plugins/spid-wordpress` quindi lanciare `composer install` da quella directory

### Ansible

Alternativamente alla procedura di installazione manuale riportata sopra, è possible installare un sito WordPress di test con questo plugin installato, tramite lo strumento di configuration management [ansible](https://www.ansible.com/). Tutte le informazioni sono nella directory [ansible/](ansible/).

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

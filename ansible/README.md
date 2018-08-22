# Installazione via ansible

È possible installare un sito WordPress di test con il plugin installato, tramite lo strumento di configuration management [ansible](https://www.ansible.com/).

Questo ruolo installa WordPress per mezzo del pacchetto nativo Debian.

Riferimenti per la configurazione WordPress generica:
- http://codex.wordpress.org/Editing_wp-config.php

Riferimenti per la configurazione del pacchetto WordPress nativo di Debian:
- https://wiki.debian.org/WordPress
- https://salsa.debian.org/debian/wordpress/blob/master/debian/README.debian

Il file di configurazione WordPress sarà `/etc/wordpress/config-default.php`.

Prima di iniziare:

- effettuare il provisioning dell'[host](https://docs.ansible.com/ansible/devel/reference_appendices/glossary.html#term-host) su cui si vuole installare il testenv (che può essere una macchina fisica, una macchina virtuale o un container), con il sistema operativo **Debian 9.4 (stretch)**

- assicurarsi che sull'host:
  - sudo, python, python-mysqldb e apt-utils siano installati (sono richiesti da ansible)

- assicurarsi che il controller (il computer da cui si intende controllare l'host):
  - abbia ansible 2.2 o posteriori installato
  - possa raggiungere l'host con un FQDN (valore di difetto `wp.simevo.com`)
  - possa effettuare l'accesso ssh con chiave crittografica come utente root

Configurare le variabili nel file `ansible/spid-wordpress_vars.yml` e il nome dell'host in `ansible/hosts` avviare l'installazione con il comando:
```
ansible-playbook -i ansible/hosts ansible/site.yml
```

Verifica dell'installazione: visitare [https://wp.simevo.com](https://wp.simevo.com).

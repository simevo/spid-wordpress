include .env

all:
	# Configure SP
	openssl req -x509 -nodes -sha256 -days 365 -newkey rsa:2048 -subj "/C=IT/ST=Italy/L=Rome/O=myservice/CN=localhost" -keyout wp.key -out wp.crt 
	chmod u+r wp.key
	envsubst < config.php.tpl > config.php
	# Configure test IdP
	envsubst < spid-testenv2/config.yaml.tpl > spid-testenv2/config.yaml
	openssl req -x509 -nodes -sha256 -days 365 -newkey rsa:2048 -subj "/C=IT/ST=Italy/L=Rome/O=myservice/CN=localhost" -keyout spid-testenv2/idp.key -out spid-testenv2/idp.crt
	# Needed only to interact with the production IdPs:
	# ../vendor/italia/spid-php-lib/bin/download_idp_metadata.php idp_metadata

post: complete refresh

complete:
	wget --spider --timeout=15 --retry-connrefused "${SP_ENTITYID}"
	curl "${SP_ENTITYID}/wp-admin/install.php?step=1" -H 'Content-Type: application/x-www-form-urlencoded' --data 'language=it_IT'
	curl "${SP_ENTITYID}/wp-admin/install.php?step=2" -H 'Content-Type: application/x-www-form-urlencoded' --data 'weblog_title=test1&user_name=test2&admin_password=test3&pass1-text=test3&admin_password2=test3&pw_weak=on&admin_email=test%40example.com&blog_public=0&language=it_IT'
	docker exec $(shell docker ps -f "ancestor=docker_wp" --format "{{.ID}}") php wp-content/plugins/spid-wordpress/bin/toggle.php

refresh:
	curl -o spid-testenv2/sp_metadata.xml "${SP_ENTITYID}/wp-login.php?sso=spid&metadata"
	wget --spider --timeout=15 --retry-connrefused "${IDP_ENTITYID}"
	curl -o idp_metadata/testenv2.xml "${IDP_ENTITYID}/metadata"
	docker restart $(shell docker ps -f "ancestor=italia/spid-testenv2" --format "{{.ID}}")

clean:
	rm -f spid-testenv2/users.json
	rm -f spid-testenv2/config.yaml
	rm -f spid-testenv2/idp.key
	rm -f spid-testenv2/idp.crt
	rm -f .env
	rm -f wp.key
	rm -f wp.crt
	rm -f config.php
	rm -f idp_metadata/*.xml

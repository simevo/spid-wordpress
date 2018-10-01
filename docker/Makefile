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

post:
	curl 'http://localhost:8099/wp-admin/install.php?step=1' -H 'Content-Type: application/x-www-form-urlencoded' --data 'language=it_IT'
	curl 'http://localhost:8099/wp-admin/install.php?step=2' -H 'Content-Type: application/x-www-form-urlencoded' --data 'weblog_title=test1&user_name=test2&admin_password=test3&pass1-text=test3&admin_password2=test3&pw_weak=on&admin_email=test%40example.com&blog_public=0&language=it_IT'
	docker exec `docker ps -f "ancestor=docker_wp" --format "{{.ID}}"` php wp-content/plugins/spid-wordpress/bin/toggle.php
	curl -o spid-testenv2/sp_metadata.xml "http://localhost:8099/wp-login.php?sso=spid&metadata"
	curl -o idp_metadata/testenv2.xml http://localhost:8088/metadata

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

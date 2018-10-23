<div id='spid-button' aria-live="polite">
	<noscript>
		<?php _e( 'To use the SPID Smart Button, please enable javascript.', 'spid-wordpress' ); ?>
	</noscript>
</div>

<script>
var spid = SPID.init({
	lang: 'it',
	selector: '#spid-button',
	method: 'GET',
	url: 'wp-login.php?sso=spid&idp={{idp}}', // to perform login with POST use: '/login-post?idp={{idp}}'
	mapping: {                    
		<?php
		foreach ( $mapping as $key => $value ) {
			echo "'" . $value . "': '" . $key . "',";
		}
		?>
	},
	supported: [
		<?php
		foreach ( $mapping as $key => $value ) {
			echo "'" . $value . "',";
		}
		?>
	],
	extraProviders: [           
		{
			"protocols": ["SAML"],
			"entityName": "Test",
			"logo": "spid-idp-dummy.svg",
			"entityID": "http://localhost:8088",
			"active": true
		},
	],
	protocol: "SAML",
	size: "large"
});
</script>

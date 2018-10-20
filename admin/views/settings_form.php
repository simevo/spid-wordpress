<?php

defined('WPINC') or die;

$roles = get_editable_roles();
unset($roles['administrator']); // we don't want to create new admins automatically

if (array_key_exists('config', $_POST)) {
	echo "<div id='setting-error-settings-updated' class='updated settings-error notice is-dismissable'><strong>Plugin configurato !</strong></div>";
	SPID_Core::getInstance()->configure('IT', $_POST['sp_stateName'], $_POST['sp_localityName'], $_POST['commonName'], $_POST['sp_emailAddress']);
	update_option('sp_stateName', $_POST['sp_stateName']);
	update_option('sp_localityName', $_POST['sp_localityName']);
	update_option('sp_emailAddress', $_POST['sp_emailAddress']);
}

if (array_key_exists('save_options', $_POST)) {
	echo "<div id='setting-error-settings-updated' class='updated settings-error notice is-dismissable'><strong>Opzioni salvate</strong></div>";
	update_option('sp_org_name', $_POST['sp_org_name']);
	update_option('sp_org_display_name', $_POST['sp_org_display_name']);
	update_option('sp_sso', $_POST['sp_sso']);
	update_option('sp_idp', $_POST['sp_idp']);
	update_option('sp_livello', $_POST['sp_livello']);
	update_option('sp_name', $_POST['sp_name']);
	update_option('sp_familyName', $_POST['sp_familyName']);
	update_option('sp_placeOfBirth', $_POST['sp_placeOfBirth']);
	update_option('sp_countyOfBirth', $_POST['sp_countyOfBirth']);
	update_option('sp_dateOfBirth', $_POST['sp_dateOfBirth']);
	update_option('sp_gender', $_POST['sp_gender']);
	update_option('sp_companyName', $_POST['sp_companyName']);
	update_option('sp_registeredOffice', $_POST['sp_registeredOffice']);
	update_option('sp_fiscalNumber', $_POST['sp_fiscalNumber']);
	update_option('sp_ivaCode', $_POST['sp_ivaCode']);
	update_option('sp_idCard', $_POST['sp_idCard']);
	update_option('sp_mobilePhone', $_POST['sp_mobilePhone']);
	update_option('sp_address', $_POST['sp_address']);
	update_option('sp_expirationDate', $_POST['sp_expirationDate']);
	update_option('sp_digitalAddress', $_POST['sp_digitalAddress']);
	update_option('sp_role', $_POST['sp_role']);
}

$sp_org_name = get_option('sp_org_name', 'nessuno');
$sp_org_display_name = get_option('sp_org_display_name', 'nessuno');
$sp_sso = get_option('sp_sso', 'spid');
$sp_idp = get_option('sp_idp', 'testenv2');
$sp_livello = get_option('sp_livello', '1');
$sp_name = get_option('sp_name', '');
$sp_familyName = get_option('sp_familyName', '');
$sp_placeOfBirth = get_option('sp_placeOfBirth', '');
$sp_countyOfBirth = get_option('sp_countyOfBirth', '');
$sp_dateOfBirth = get_option('sp_dateOfBirth', '');
$sp_gender = get_option('sp_gender', '');
$sp_companyName = get_option('sp_companyName', '');
$sp_registeredOffice = get_option('sp_registeredOffice', '');
$sp_fiscalNumber = get_option('sp_fiscalNumber', '');
$sp_ivaCode = get_option('sp_ivaCode', '');
$sp_idCard = get_option('sp_idCard', '');
$sp_mobilePhone = get_option('sp_mobilePhone', '');
$sp_address = get_option('sp_address', '');
$sp_expirationDate = get_option('sp_expirationDate', '');
$sp_digitalAddress = get_option('sp_digitalAddress', '');
$sp_role = get_option('sp_role', 'subscriber');
$sp_stateName = get_option('sp_stateName', 'Roma');
$sp_localityName = get_option('sp_localityName', 'Ostia');
$sp_emailAddress = get_option('sp_emailAddress', 'test@example.com');

?>
<div class="wrap">

	<img src="<?php echo plugins_url('public/images/spid-logo-b-lb.png', dirname(__DIR__)); ?>"  width="500" height="98" border="0" />

	<h2>SPID configurazione</h2>

	<form action="" method="post">
		<table class="form-table">
			<tr>
				<th><label>Metadata del servizio</label></th>
				<td><a href="/wp-login.php?sso=spid&metadata" target="_blank">SP metadata</a></td>
			</tr>
			<tr>
				<th><label for="sp_stateName">Provincia (nome per esteso)</label></th>
				<td><input name="sp_stateName" class="large-text" value="<?php echo $sp_stateName; ?>"></td>
			</tr>
			<tr>
				<th><label for="sp_localityName">Città</label></th>
				<td><input name="sp_localityName" class="large-text" value="<?php echo $sp_localityName; ?>"></td>
			</tr>
			</tr>
			<tr>
				<th><label for="sp_emailAddress">Indirizzo email dell'ente o del referente tecnico</label></th>
				<td><input name="sp_emailAddress" class="large-text" type="email" value="<?php echo $sp_emailAddress; ?>"></td>
			</tr>
		</table>
    <input hidden name="commonName" class="large-text" value="<?php echo parse_url(get_home_url(), PHP_URL_HOST); ?>">
		
		<p class="submit">
			<input type="submit" value="Configura" name="config" class="button button-primary"/>
		</p>
	</form>

	<hr/>
	
	<h2>SPID opzioni</h2>

	<form action="" method="post">
		<table class="form-table">
			<tr>
				<th><label for="sp_org_name">Nome breve del Service Provider</label></th>
				<td><input name="sp_org_name" class="large-text" value="<?php echo $sp_org_name; ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th><label for="sp_org_display_name">Descrizione del Service Provider</label></th>
				<td><input name="sp_org_display_name" class="large-text" value="<?php echo $sp_org_display_name; ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th><label for="sp_sso">SSO</label></th>
				<td><input name="sp_sso" class="large-text" value="<?php echo $sp_sso; ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th><label for="sp_idp">Name of the test Identity Provider</label></th>
				<td><input name="sp_idp" class="large-text" value="<?php echo $sp_idp; ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th><label for="sp_livello">Livello SPID</label></th>
				<td>
					<select name="sp_livello">
					  <option value="1" <?php if ($sp_livello == 1) echo "selected"; ?>>1</option>
					  <option value="2" <?php if ($sp_livello == 2) echo "selected"; ?>>2</option>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="sp_livello">Ruolo da assegnare a utente</label></th>
				<td>
					<select name="sp_role">
					  <?php
						foreach ($roles as $key => $value) {
							echo "<option value=" . $key;
							if ($sp_role == $key) echo " selected ";
							echo ">" . $key . "</option>";
						}
						?>
					  
					  
					</select>
				</td>
			</tr>
			<tr>
				<th>Attributi</th>
				<td>
					<fieldset>
				    	<legend class="screen-reader-text">
				    		<span>Attributi</span>
				    	</legend>
				    	<table>
				    		<tr>
				    			<td>
									<label for="sp_name">
										<input name="sp_name" type="checkbox" <?php if ($sp_name) echo "checked"; ?>  id="sp_name">
										Nome
									</label>
								</td>
								<td>
									<label for="sp_familyName">
										<input name="sp_familyName" type="checkbox" <?php if ($sp_familyName) echo "checked"; ?>  id="sp_familyName">
										Cognome
									</label>
								</td>
							</tr>
							<tr>
				    			<td>
									<label for="sp_expirationDate">
										<input name="sp_expirationDate" type="checkbox" <?php if ($sp_expirationDate) echo "checked"; ?>  id="sp_expirationDate">
										Data di scadenza identità
									</label>
								</td>
								<td>
									<label for="sp_placeOfBirth">
										<input name="sp_placeOfBirth" type="checkbox" <?php if ($sp_placeOfBirth) echo "checked"; ?>  id="sp_placeOfBirth">
										Luogo di nascita
									</label>
								</td>
							</tr>
							<tr>
				    			<td>
									<label for="sp_countyOfBirth">
										<input name="sp_countyOfBirth" type="checkbox" <?php if ($sp_countyOfBirth) echo "checked"; ?>  id="sp_countyOfBirth">
										Provincia di nascita
									</label>
								</td>
								<td>
									<label for="sp_dateOfBirth">
										<input name="sp_dateOfBirth" type="checkbox" <?php if ($sp_dateOfBirth) echo "checked"; ?>  id="sp_dateOfBirth">
										Data di nascita
									</label>
								</td>
							</tr>
							<tr>
				    			<td>
									<label for="sp_gender">
										<input name="sp_gender" type="checkbox" <?php if ($sp_gender) echo "checked"; ?>  id="sp_gender">
										Sesso
									</label>
								</td>
								<td>
									<label for="sp_companyName">
										<input name="sp_companyName" type="checkbox" <?php if ($sp_companyName) echo "checked"; ?>  id="sp_companyName">
										Ragione o denominazione sociale 
									</label>
								</td>
							</tr>
							<tr>
				    			<td>
									<label for="sp_registeredOffice">
										<input name="sp_registeredOffice" type="checkbox" <?php if ($sp_registeredOffice) echo "checked"; ?>  id="sp_registeredOffice">
										Sede legale
									</label>
								</td>
								<td>
									<label for="sp_fiscalNumber">
										<input name="sp_fiscalNumber" type="checkbox" <?php if ($sp_fiscalNumber) echo "checked"; ?>  id="sp_fiscalNumber">
										Codice fiscale
									</label>
								</td>
							</tr>
							<tr>
				    			<td>
									<label for="sp_ivaCode">
										<input name="sp_ivaCode" type="checkbox" <?php if ($sp_ivaCode) echo "checked"; ?>  id="sp_ivaCode">
										Partita IVA
									</label>
								</td>
								<td>
									<label for="sp_idCard">
										<input name="sp_idCard" type="checkbox" <?php if ($sp_idCard) echo "checked"; ?>  id="sp_idCard">
										Documento d'identità
									</label>
								</td>
							</tr>
							<tr>
				    			<td>
									<label for="sp_mobilePhone">
										<input name="sp_mobilePhone" type="checkbox" <?php if ($sp_mobilePhone) echo "checked"; ?>  id="sp_mobilePhone">
										Numero di telefono mobile 
									</label>
								</td>
								<td>
									<label for="sp_digitalAddress">
										<input name="sp_digitalAddress" type="checkbox" <?php if ($sp_digitalAddress) echo "checked"; ?>  id="sp_digitalAddress">
										Domicilio digitale
									</label>
								</td>
							</tr>
							<tr>
				    			<td>
									<label for="sp_address">
										<input name="sp_address" type="checkbox" <?php if ($sp_address) echo "checked"; ?>  id="sp_address">
										Domicilio fisico
									</label>
								</td>
								<td>
									
								</td>
							</tr>

						</table>
						<p class="description">(Legenda)</p>
					</fieldset> 
				</td>
			</tr>
		</table>
		
		<p class="submit">
			<input type="submit" value="Salva le opzioni" name="save_options" class="button button-primary"/>
		</p>
		
	</form>
</div>

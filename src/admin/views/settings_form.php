<?php

defined('WPINC') or die;

if (array_key_exists('save_options', $_POST)) {
	echo "<div id='setting-error-settings-updated' class='updated settings-error notice is-dismissable'><strong>Opzioni salvate</strong></div>";
    update_option('sp_org_name', $_POST['sp_org_name']);
    update_option('sp_sso', $_POST['sp_sso']);
    update_option('sp_idp', $_POST['sp_idp']);
    update_option('sp_livello', $_POST['sp_livello']);
}

$sp_org_name = get_option('sp_org_name', 'nessuno');
$sp_sso = get_option('sp_sso', 'spid');
$sp_idp = get_option('sp_idp', 'testenv2');
$sp_livello = get_option('sp_livello', '1');

?>
<div class="wrap">

	<img src="<?php echo plugins_url( 'img/spid-logo-b-lb.png', __FILE__ ); ?>"  width="500" height="98" border="0" />

	<h2>SPID opzioni</h2>
	
	<form action="" method="post">
		<table class="form-table">
			<tr>
				<th><label for="sp_org_name">Nome del Service Provider</label></th>
				<td><input name="sp_org_name" class="large-text" value="<?php echo $sp_org_name;?>" class="regular-text"></td>
			</tr>
			<tr>
				<th><label for="sp_sso">SSO</label></th>
				<td><input name="sp_sso" class="large-text" value="<?php echo $sp_sso;?>" class="regular-text"></td>
			</tr>
			<tr>
				<th><label for="sp_idp">Identity Provider</label></th>
				<td><input name="sp_idp" class="large-text" value="<?php echo $sp_idp;?>" class="regular-text"></td>
			</tr>
			<tr>
				<th><label for="sp_livello">Livello SPID</label></th>
				<td>
					<select name="sp_livello">
					  <option value="1" <?php if($sp_livello==1)echo "selected";?>>1</option>
					  <option value="2" <?php if($sp_livello==2)echo "selected";?>>2</option>
					</select>
				</td>
			</tr>
		</table>
		
		<p class="submit">
			<input type="submit" value="Salva le opzioni" name="save_options" class="button button-primary"/>
		</p>
		
	</form>
</div>
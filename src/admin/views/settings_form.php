<?php

defined('WPINC') or die;

if (array_key_exists('save_options', $_POST)) {
	echo "<div id='setting-error-settings-updated' class='updated settings-error notice is-dismissable'><strong>Opzioni salvate</strong></div>";
    update_option('sp_org_name', $_POST['sp_org_name']);
}

$sp_org_name = get_option('sp_org_name', 'nessuno');

?>
<div class="wrap">
	<h2>SPID opzioni</h2>
	

	<form action="" method="post">
		<table class="form-table">
			<tr>
				<th><label for="sp_org_name">Nome del Service Provider</label></th>
				<td><input name="sp_org_name" class="large-text" value="<?php echo $sp_org_name;?>" class="regular-text"></td>
			</tr>
		</table>
		
		<p class="submit">
			<input type="submit" value="Salva le opzioni" name="save_options" class="button button-primary"/>
		</p>
		
	</form>
</div>
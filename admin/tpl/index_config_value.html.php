	<?php if(!defined('_APP')) exit;?>
	
<div class="oper">
	<a href="index_config.php#content" title="<?php _t('config_mgmt'); ?>"><img src="img/icon_config_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('config_mgmt'); ?></a>
</div>	
	
<div class="history">
	<img src="img/icon_config_value.gif" width="64" height="64" border="0" alt="" /> 
	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
	<?php _t('config_value_mgmt'); ?>
</div>

<div class="content_block">

<?php
if(isset($Message) && $Message!='') {
	?>
	<div class="message">
		<?php echo $Message; ?>
	</div>
	<?php
}
?>




<?php

if(count($TabConf)>0) {
	//wypisanie zmiennych głównych
	//ikonka - jeśli jest albo domyslna
	//nazwa zmiennej
	
	// jesli wiele wartosci - link do dodaj, inaczej - Zmien
	//wartisci zmiennych:
	// jesli tablica:
	// jesli wiele wartosci - to tabela - naglowki + edytuj obok
	// jesli jedna wartosc - to tabela: zmienne w osobnych wierszach
	// jesli nie tablica:
	// jesli wiele wartosci - to tabelka???
	// jesli jedna wartosc - to wypisana
	?>
	<table class="cdata">
	<?php
	foreach($TabConf as $k=>$v) {
		?>
		<tr>
			<th width="5%"><img src="<?php echo $v['config_icon']!=''?htmlspecialchars($v['config_icon']):ADMIN_DEF_CONFIG_ICON; ?>" border="0" alt="<?php echo htmlspecialchars($v['config_name']); ?>" /></th>
			<th><?php echo htmlspecialchars($v['config_name']); ?><br /><span class="info"><?php echo nl2br(htmlspecialchars($v['info'])); ?></span></th>
			<th><a name="i_<?php echo intval($k); ?>" href="edit_config_value.php?config_id=<?php echo intval($k); ?>&value_id=0#content"><img src="img/icon_config_value_<?php echo $v['multiple']>0?'add':'edit'; ?>_m.gif" border="0" width="20" height="20" alt="" /> <?php $v['multiple']>0?_t('config_value_add'):_t('config_value_edit'); ?></a></th>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">
			<?php
			if($v['is_group']>0) {
				if($v['multiple']) {
					if(is_array($Tab[$v['config_code']]) && count($Tab[$v['config_code']])>0) {
						?>
						<table class="data">
						<tr>
							<th><?php echo htmlspecialchars($v['config_name']); ?></th>
							<?php
							foreach($v['subconfig'] as $sub_k=>$sub_v) {
								?>
								<th><?php echo htmlspecialchars($sub_v['config_name']); ?></th>
								<?php
								
							}
							?>
							
							<th>&nbsp;</th>
						</tr>
						<?php
							$x=0;
							foreach($Tab[$v['config_code']] as $id=>$tabb) {
								?>
								<tr <?php echo $x%2==0?'class="data_row2"':''; ?>>
									<td><?php echo htmlspecialchars($tabb[$v['config_code']]); ?></td>
									<?php								
									foreach($v['subconfig'] as $sub_k=>$sub_v) {
										?>
										<td><?php echo htmlspecialchars($tabb[$sub_v['config_code']]); ?></td>
										<?php
									}
									?>
									
									<td><a href="edit_config_value.php?config_id=<?php echo intval($k); ?>&value_id=<?php echo intval($id); ?>#content" title="<?php _t('config_value_edit'); ?>"><img src="img/icon_config_value_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('config_value_edit'); ?></a></td>
								</tr>
								<?php
								++$x;
							}
						?>
						</table>
						<?php
					} else {
						?>
						<p class="message">
						<?php _t('no_config_values_msg'); ?>
						</p>
						<?php
					}
				} else {
					?>
					<table class="data" >
					<?php
					$x=0;
					foreach($v['subconfig'] as $sub_k=>$sub_v) {
						?>
						<tr <?php echo $x%2==0?'class="data_row2"':''; ?>>
							<td><?php echo htmlspecialchars($sub_v['config_name']); ?>:</td>
							<td><?php echo $Tab[$v['config_code']][$sub_v['config_code']]!=''?htmlspecialchars($Tab[$v['config_code']][$sub_v['config_code']]):'<span class="info">'.htmlspecialchars($T['config_value_empty']).'</span>'; ?></td>
						</tr>
						<?php
						++$x;
					}
					?>
					</table>
					<?php
				}
			} else {
				if($v['multiple']) {
					if(is_array($Tab[$v['config_code']]) && count($Tab[$v['config_code']])>0) {
						?>
						<table class="data">
						<?php
						$x=0;
						foreach($Tab[$v['config_code']] as $id=>$val) {
							?>
							<tr <?php echo $x%2==0?'class="data_row2"':''; ?>>
								<td><?php echo $val!=''?htmlspecialchars($val):'<span class="info">'.htmlspecialchars($T['config_value_empty']).'</span>'; ?></td>
								<td><a href="edit_config_value.php?config_id=<?php echo intval($k); ?>&value_id=<?php echo intval($id); ?>#content" title="<?php _t('config_value_edit'); ?>"><img src="img/icon_config_value_edit_m.gif" border="0" width="20" height="20" alt="" /><?php _t('config_value_edit'); ?></a></td>
							</tr>
							<?php
							++$x;
						}
						?>
						</table>
						<?php
					} else {
						?>
						<p class="message">
						<?php _t('no_config_values_msg'); ?>
						</p>
						<?php
					}
				} else {
					?>
					<?php echo $Tab[$v['config_code']]!=''?htmlspecialchars($Tab[$v['config_code']]):'<span class="info">'.htmlspecialchars($T['config_value_empty']).'</span>'; ?>
					<?php
				}
			}
			?>
			</td>
		</tr>
		<?php
	}
	?>
	</table>
	<div class="space"></div><div id="global_btn">
	<?php _gui_button($T['ok'], 'location.href=\'index.php\''); ?>
</div>
</div>
	<?php
} else {
	?>
	<p class="message">
	<?php _t('no_configs_msg'); ?>
	</p>
	<?php
}
?>
<br />


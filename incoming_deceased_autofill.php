<?php
// This script and data application were generated by AppGini 5.71
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir = dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");

	handle_maintenance();

	header('Content-type: text/javascript; charset=' . datalist_db_encoding);

	$table_perms = getTablePermissions('incoming_deceased');
	if(!$table_perms[0]){ die('// Access denied!'); }

	$mfk = $_GET['mfk'];
	$id = makeSafe($_GET['id']);
	$rnd1 = intval($_GET['rnd1']); if(!$rnd1) $rnd1 = '';

	if(!$mfk){
		die('// No js code available!');
	}

	switch($mfk){

		case 'relative_name':
			if(!$id){
				?>
				$j('#relative_number<?php echo $rnd1; ?>').html('&nbsp;');
				<?php
				break;
			}
			$res = sql("SELECT `relatives_info`.`id` as 'id', `relatives_info`.`first_relative_full_name` as 'first_relative_full_name', `relatives_info`.`home_address` as 'home_address', `relatives_info`.`home_town` as 'home_town', `relatives_info`.`occupation` as 'occupation', `relatives_info`.`phone_number` as 'phone_number', `relatives_info`.`second_relative_full_name` as 'second_relative_full_name', `relatives_info`.`second_relative_home_address` as 'second_relative_home_address', `relatives_info`.`second_relative_home_town` as 'second_relative_home_town', `relatives_info`.`second_relative_occupation` as 'second_relative_occupation', `relatives_info`.`second_relative_phone_number` as 'second_relative_phone_number' FROM `relatives_info`  WHERE `relatives_info`.`id`='{$id}' limit 1", $eo);
			$row = db_fetch_assoc($res);
			?>
			$j('#relative_number<?php echo $rnd1; ?>').html('<?php echo addslashes(str_replace(array("\r", "\n"), '', nl2br($row['phone_number']))); ?>&nbsp;');
			<?php
			break;


	}

?>
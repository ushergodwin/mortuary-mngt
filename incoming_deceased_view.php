<?php
// This script and data application were generated by AppGini 5.71
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/incoming_deceased.php");
	include("$currDir/incoming_deceased_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('incoming_deceased');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "incoming_deceased";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(   
		"`incoming_deceased`.`id`" => "id",
		"`incoming_deceased`.`fullname`" => "fullname",
		"`incoming_deceased`.`gender`" => "gender",
		"`incoming_deceased`.`tag_number`" => "tag_number",
		"`incoming_deceased`.`serial_number`" => "serial_number",
		"IF(    CHAR_LENGTH(`relatives_info1`.`first_relative_full_name`), CONCAT_WS('',   `relatives_info1`.`first_relative_full_name`), '') /* Relative name */" => "relative_name",
		"IF(    CHAR_LENGTH(`relatives_info1`.`phone_number`), CONCAT_WS('',   `relatives_info1`.`phone_number`), '') /* Relative phone number */" => "relative_number",
		"IF(    CHAR_LENGTH(`rooms1`.`name`), CONCAT_WS('',   `rooms1`.`name`), '') /* Room */" => "room",
		"IF(    CHAR_LENGTH(`beds1`.`number`) || CHAR_LENGTH(`rooms2`.`name`), CONCAT_WS('',   `beds1`.`number`, ' Room: ', `rooms2`.`name`), '') /* Bed */" => "bed",
		"if(`incoming_deceased`.`date`,date_format(`incoming_deceased`.`date`,'%m/%d/%Y'),'')" => "date"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(   
		1 => '`incoming_deceased`.`id`',
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5,
		6 => '`relatives_info1`.`first_relative_full_name`',
		7 => '`relatives_info1`.`phone_number`',
		8 => '`rooms1`.`name`',
		9 => 9,
		10 => '`incoming_deceased`.`date`'
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(   
		"`incoming_deceased`.`id`" => "id",
		"`incoming_deceased`.`fullname`" => "fullname",
		"`incoming_deceased`.`gender`" => "gender",
		"`incoming_deceased`.`tag_number`" => "tag_number",
		"`incoming_deceased`.`serial_number`" => "serial_number",
		"IF(    CHAR_LENGTH(`relatives_info1`.`first_relative_full_name`), CONCAT_WS('',   `relatives_info1`.`first_relative_full_name`), '') /* Relative name */" => "relative_name",
		"IF(    CHAR_LENGTH(`relatives_info1`.`phone_number`), CONCAT_WS('',   `relatives_info1`.`phone_number`), '') /* Relative phone number */" => "relative_number",
		"IF(    CHAR_LENGTH(`rooms1`.`name`), CONCAT_WS('',   `rooms1`.`name`), '') /* Room */" => "room",
		"IF(    CHAR_LENGTH(`beds1`.`number`) || CHAR_LENGTH(`rooms2`.`name`), CONCAT_WS('',   `beds1`.`number`, ' Room: ', `rooms2`.`name`), '') /* Bed */" => "bed",
		"if(`incoming_deceased`.`date`,date_format(`incoming_deceased`.`date`,'%m/%d/%Y'),'')" => "date"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(   
		"`incoming_deceased`.`id`" => "ID",
		"`incoming_deceased`.`fullname`" => "Fullname",
		"`incoming_deceased`.`gender`" => "Gender",
		"`incoming_deceased`.`tag_number`" => "Tag number",
		"`incoming_deceased`.`serial_number`" => "Serial number",
		"IF(    CHAR_LENGTH(`relatives_info1`.`first_relative_full_name`), CONCAT_WS('',   `relatives_info1`.`first_relative_full_name`), '') /* Relative name */" => "Relative name",
		"IF(    CHAR_LENGTH(`relatives_info1`.`phone_number`), CONCAT_WS('',   `relatives_info1`.`phone_number`), '') /* Relative phone number */" => "Relative phone number",
		"IF(    CHAR_LENGTH(`rooms1`.`name`), CONCAT_WS('',   `rooms1`.`name`), '') /* Room */" => "Room",
		"IF(    CHAR_LENGTH(`beds1`.`number`) || CHAR_LENGTH(`rooms2`.`name`), CONCAT_WS('',   `beds1`.`number`, ' Room: ', `rooms2`.`name`), '') /* Bed */" => "Bed",
		"`incoming_deceased`.`date`" => "Date"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(   
		"`incoming_deceased`.`id`" => "id",
		"`incoming_deceased`.`fullname`" => "fullname",
		"`incoming_deceased`.`gender`" => "gender",
		"`incoming_deceased`.`tag_number`" => "tag_number",
		"`incoming_deceased`.`serial_number`" => "serial_number",
		"IF(    CHAR_LENGTH(`relatives_info1`.`first_relative_full_name`), CONCAT_WS('',   `relatives_info1`.`first_relative_full_name`), '') /* Relative name */" => "relative_name",
		"IF(    CHAR_LENGTH(`relatives_info1`.`phone_number`), CONCAT_WS('',   `relatives_info1`.`phone_number`), '') /* Relative phone number */" => "relative_number",
		"IF(    CHAR_LENGTH(`rooms1`.`name`), CONCAT_WS('',   `rooms1`.`name`), '') /* Room */" => "room",
		"IF(    CHAR_LENGTH(`beds1`.`number`) || CHAR_LENGTH(`rooms2`.`name`), CONCAT_WS('',   `beds1`.`number`, ' Room: ', `rooms2`.`name`), '') /* Bed */" => "bed",
		"if(`incoming_deceased`.`date`,date_format(`incoming_deceased`.`date`,'%m/%d/%Y'),'')" => "date"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array(  'relative_name' => 'Relative name', 'room' => 'Room', 'bed' => 'Bed');

	$x->QueryFrom = "`incoming_deceased` LEFT JOIN `relatives_info` as relatives_info1 ON `relatives_info1`.`id`=`incoming_deceased`.`relative_name` LEFT JOIN `rooms` as rooms1 ON `rooms1`.`id`=`incoming_deceased`.`room` LEFT JOIN `beds` as beds1 ON `beds1`.`id`=`incoming_deceased`.`bed` LEFT JOIN `rooms` as rooms2 ON `rooms2`.`id`=`beds1`.`room` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm[2]==0 ? 1 : 0);
	$x->AllowDelete = $perm[4];
	$x->AllowMassDelete = true;
	$x->AllowInsert = $perm[1];
	$x->AllowUpdate = $perm[3];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = 1;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "incoming_deceased_view.php";
	$x->RedirectAfterInsert = "incoming_deceased_view.php?SelectedID=#ID#";
	$x->TableTitle = "Incoming deceased";
	$x->TableIcon = "resources/table_icons/application_side_expand.png";
	$x->PrimaryKey = "`incoming_deceased`.`id`";

	$x->ColWidth   = array(  150, 150, 150, 150, 150, 150, 150, 150, 150);
	$x->ColCaption = array("Fullname", "Gender", "Tag number", "Serial number", "Relative name", "Relative phone number", "Room", "Bed", "Date");
	$x->ColFieldName = array('fullname', 'gender', 'tag_number', 'serial_number', 'relative_name', 'relative_number', 'room', 'bed', 'date');
	$x->ColNumber  = array(2, 3, 4, 5, 6, 7, 8, 9, 10);

	// template paths below are based on the app main directory
	$x->Template = 'templates/incoming_deceased_templateTV.html';
	$x->SelectedTemplate = 'templates/incoming_deceased_templateTVS.html';
	$x->TemplateDV = 'templates/incoming_deceased_templateDV.html';
	$x->TemplateDVP = 'templates/incoming_deceased_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `incoming_deceased`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='incoming_deceased' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `incoming_deceased`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='incoming_deceased' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`incoming_deceased`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: incoming_deceased_init
	$render=TRUE;
	if(function_exists('incoming_deceased_init')){
		$args=array();
		$render=incoming_deceased_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: incoming_deceased_header
	$headerCode='';
	if(function_exists('incoming_deceased_header')){
		$args=array();
		$headerCode=incoming_deceased_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode){
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: incoming_deceased_footer
	$footerCode='';
	if(function_exists('incoming_deceased_footer')){
		$args=array();
		$footerCode=incoming_deceased_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode){
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>
<?php
	require_once './config/common.php';

/*
レギュラー	343	215
ミニ		274	215
*/

	$width  = 215;
	$height = 343;

	if(isset($_GET["type"]) && $_GET["type"]=="mini"){
		$height = 274;
	}

	$smarty = createSmarty();
	$smarty->assign(array(
		"width" => $width,
		"height" => $height,
	));
	$smarty->display("edit.tmpl");
?>

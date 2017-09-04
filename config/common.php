<?php

require_once './lib/smarty/Smarty.class.php';

function createSmarty()
{
	$smarty = new Smarty;
	$smarty->left_delimiter = "<{";
	$smarty->right_delimiter = "}>";
	$smarty->assign("session_id",session_id());
	return $smarty;
}


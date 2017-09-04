<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {mixi_check} function plugin
 *
 * Type:     function<br>
 * Name:     mixi_check<br>
 * Date:     Februry 8, 2011<br>
 * Purpose:  mixi check button.
 * @author   Hiroshi Kawazoe
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_mixi_check($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('shared','make_safe_url');
	$url = smarty_function_make_safe_url($params);

	$button = "button-1";

	$data_key = "2a17df6a145c2723ef30457b551412c9787ef00f";
	if(isset($params['data_key'])){
		$data_key = $params['data_key'];
	}

	if(isset($params['button'])){
		if(
		    $params['button'] == "button-1"
		 || $params['button'] == "button-2"
		 || $params['button'] == "button-3"
		 || $params['button'] == "button-4"
		 || $params['button'] == "button-5"
		){
			$button = $params['button'];
		}
	}

    return '<a href="http://mixi.jp/share.pl" class="mixi-check-button" data-key="'.$data_key.'" data-url="'.$url.'" data-button="'.$button.'">Check</a><script type="text/javascript" src="http://static.mixi.jp/js/share.js"></script>';
}

?>

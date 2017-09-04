<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {facebook_like} function plugin
 *
 * Type:     function<br>
 * Name:     facebook_like<br>
 * Date:     Februry 8, 2011<br>
 * Purpose:  facebook like button.
 * @author   Hiroshi Kawazoe
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_facebook_like($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('shared','make_safe_url');
	$url = smarty_function_make_safe_url($params);

	//default
	$layout      = "standard";
	$height      = 80;
	$width       = 450;
	$show_faces  = "true";
	$action      = "like";
	$colorscheme = "light";

	//param proc
	if(isset($params['width'])){
		$width = $params['width'];
	}

	if(isset($params['layout'])){
		if($params['layout'] == "box_count"){
			$layout = "box_count";
			$height = 65;
		}else if($params['layout'] == "button_count"){
			$layout = "button_count";
			$height = 21;
		}
	}

	if(isset($params['show_faces'])){
		if($params['show_faces'] == "false"){
			$show_faces = $params['show_faces'];
		}
	}

	if(isset($params['action'])){
		if($params['action'] == "recommend"){
			$action = $params['action'];
		}
	}

	if(isset($params['colorscheme'])){
		if($params['colorscheme'] == "dark"){
			$colorscheme = $params['colorscheme'];
		}
	}

	if($params["code"]=="javascript"){
		if(!defined("FACEBOOK_JS_DEFINE")){
			$js_def =  '<script src="http://connect.facebook.net/ja_JP/all.js#xfbml=1"></script>';
			$js_def .= "\n<script>";
			$js_def .= "\nFB.init({appId: '186995278001199', status: true, cookie: true, xfbml: true});";
			$js_def .= "\n</script>\n";

		}
		return $js_def.'<fb:like href="'.$url.'" layout="'.$layout.'" show_faces="'.$show_faces.'" width="'.$width.'" action="'.$action.'" colorscheme="'.$colorscheme.'"></fb:like>';
	}else{
    	return '<iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode($url).'&amp;layout='.$layout.'&amp;show_faces='.$show_faces.'&amp;width='.$width.'&amp;action='.$action.'&amp;colorscheme='.$colorscheme.'&amp;height='.$height.'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$width.'px; height:'.$height.'px;" allowTransparency="true"></iframe>';
	}
}

?>

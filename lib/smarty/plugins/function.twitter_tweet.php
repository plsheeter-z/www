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
function smarty_function_twitter_tweet($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('shared','make_safe_url');
	$url = smarty_function_make_safe_url($params);

	//data-text="これをチェック" 
	if($params['text']){
		$text = str_replace('"','&quot;',$params['text']);
	}

	//data-count="vertical" 
	$count = "horizontal";
	if($params["count"]){
		if($params["count"] == "vertical" 
		 || $params["count"] == "none"
		){
			$count = $params["count"];
		}
	}

	//data-lang="ja"
	$lang = "ja";
	if($params["lang"]){
		$lang = $params["lang"];
	}

	//data-via="souseiji" 
	if($params["via"]){
		$other .= ' data-via="'.$params["via"].'"';
	}

	//data-related="colorme"
	if($params["related"]){
		$other .= ' data-related="'.$params["related"].'"';
	}

	return '<a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$url.'" data-text="'.$text.'" data-count="'.$count.'" data-lang="'.$lang.'"'.$other.'>Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js" charset="utf-8"></script>';
}

?>

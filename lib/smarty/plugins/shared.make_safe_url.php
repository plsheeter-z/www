<?php
/**
 * Smarty shared plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * make_safe_url common function
 *
 * Function: smarty_function_make_safe_url<br>
 * Purpose:  
 * @param string
 * @return string
 */
function smarty_function_make_safe_url(&$params)
{
	if(isset($params['url'])){
		$url = $params['url'];
		if(strpos($url,"http://")!==0){
			$url = "http://" . $_SERVER['HTTP_HOST'] ."/". $url;
		}
	}else{
		$uri = $_SERVER['REQUEST_URI'];
		if(strpos($uri,"PHPSESSID=")!==false){
			list($path,$qstr) = explode("?",$_SERVER['REQUEST_URI']);
			$array_qstr = explode("&",$qstr);
			$buf = "";
			foreach($array_qstr as $row){
				if($buf) $buf .= "&";
				if(strpos($row,"PHPSESSID=")===false){
					$buf .= $row;
				}
			}
			$uri = $path.'?'.$buf;
		}

		$url = "http://" . $_SERVER['HTTP_HOST'] . $uri;
	}

    return $url;
}

?>

<?php
require_once('Cache/Lite.php');
//require_once('HTTP/Request.php');

define('RSS_CACHE_DIR', PROSMARTY_DIR."rss_cache/");

function smarty_function_parse_rss($params, &$smarty) {
        //parse params
        $url = $params['url'];
        if(empty($url)){
                return;
        }

        $assign_name = $params['assign_name'];
        if(empty($assign_name)){
                return;
        }

        $date_format = "Y-m-d";
        if(isset($params['date_format'])){
                $date_format = $params['date_format'];
        }

        $time_format = "H:i:s";
        if(isset($params['time_format'])){
                $time_format = $params['time_format'];
        }

        $limit = 3;
        if(isset($params['limit'])){
                $limit = intval($params['limit']);
        }

		$wUserDir = substr(ACCOUNT_ID, 0, 7) . "/" . substr(ACCOUNT_ID, 7, 9);
        $option = array(
                "cacheDir" => RSS_CACHE_DIR.$wUserDir, 
                "lifeTime" => 3600, 
                "automaticSerialization" => true, 
        );
        $cache = new Cache_Lite($option); 
       
        if ($data = $cache->get(serialize($params), __FUNCTION__)) {
                // キャッシュを利用
                $smarty->assign($assign_name,$data);
                return;
        } else {
                // feedを取得
                $api_key = "ABQIAAAAGMBkjiQ_2GKM3_hobDHBRxQ6rxtuQE2WSCIgGecjeRsRpgN_CRROKF-iRFYFxgO3XSQQsUd6WrkHdQ";
                $feed_api = 'http://ajax.googleapis.com/ajax/services/feed/load?v=%1$s&key=%2$s&q=%3$s&num=%4$d';
               
                $feed_api = sprintf($feed_api, "1.0", $api_key, $params['url'], $limit);
               
                //$req = new HTTP_Request($feed_api);
                //$req->setMethod(HTTP_REQUEST_METHOD_GET);
               
                //API呼び出し
                //$req->sendRequest();
       
                //結果の取得
                //$result = json_decode($req->getResponseBody(), true);
                $result = json_decode(file_get_contents($feed_api), true);
                
                if ($result['responseStatus'] != 200) {
                        exit;
                }
               
                $items = array();
               
                foreach ($result['responseData']['feed']['entries'] as $entry) {
                        $publish_date = strtotime($entry['publishedDate']);
                        $entry_date = strftime("%Y-%m-%d", $publish_date);
                        $entry_time = strftime("%H:%M", $publish_date);
                        $title = mb_convert_encoding($entry['title'],"EUC-JP","utf8");
                        $subject = mb_convert_encoding($entry['categories'],"EUC-JP","utf8");
                        $description = mb_convert_encoding($entry['content'],"EUC-JP","utf8");

                        $items[] = array(
                                "datetime"    => $publish_date,
                                "date"        => $entry_date,
                                "time"        => $entry_time,
                                "url"         => $entry['link'],
                                "title"       => $title,
                                "subject"     => $subject,
                                "description" => $description,
                        );
                }
                $smarty->assign($assign_name,$items);
               
                // ローカルキャッシュに保存
                if (!is_dir(RSS_CACHE_DIR)) {
                        mkdir(RSS_CACHE_DIR, 0755, true);
                }
                $cache->save($items, serialize($params), __FUNCTION__);
        }
        return;
}

?>

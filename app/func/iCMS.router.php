<?php
/**
 * @package iCMS
 * @copyright 2007-2015, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 * @$Id: iCMS.push.php 148 2013-03-14 16:15:12Z coolmoo $
 */
function iCMS_router($vars){
	if(empty($vars['url'])){
		echo 'javascript:;';
		return;
	}
	$router = $vars['url'];
	unset($vars['url'],$vars['app']);
	$url = iPHP::router($router);
	$vars['query'] && $url = buildurl($url,$vars['query']);

	if($url && stripos($url, 'http://')===false && $vars['host']){
		$url = rtrim(iCMS_URL,'/').'/'.ltrim($url, '/');;
	}
	echo $url?$url:'javascript:;';
}

<?php
/**
 * iPHP - i PHP Framework
 * Copyright (c) 2012 iiiphp.com. All rights reserved.
 *
 * @author coolmoo <iiiphp@qq.com>
 * @website http://www.iiiphp.com
 * @license http://www.iiiphp.com/license
 * @version 2.0.0
 */
defined('iPHP') OR exit('What are you doing?');
defined('iPHP_LIB') OR exit('iPHP vendor need define iPHP_LIB');
iPHP::import(iPHP_LIB . '/phpqrcode.php');

function QRcode($content) {
	$expires = 86400;
	header("Cache-Control: maxage=" . $expires);
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
	header('Content-type: image/png');
	$filepath = false;
	if (isset($_GET['cache'])) {
		$name = substr(md5($content), 8, 16);
		$filepath = iPHP_APP_CACHE . '/QRcode.' . $name . '.png';
	}
	is_file($filepath) OR QRcode::png($content, $filepath, 'L', 4, 2);
	if ($filepath) {
		$png = readfile($filepath);
		exit($png);
	}
}

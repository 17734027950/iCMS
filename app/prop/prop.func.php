<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 * @$Id: prop.func.php 179 2013-03-29 03:21:28Z coolmoo $
 */
function prop_array($vars){
	$type	= $vars['type'];
	$field	= $vars['field'];
	$cid	= $vars['cid'];
	$pkey	= $type.'.'.$field;
	$cid &&	$pkey	= 'c'.$cid.'.'.$type.'.'.$field;
	$propArray 	= iCache::get("iCMS/prop/{$pkey}");
	$propArray && sort($propArray);
	$offset		= $vars['start']?$vars['start']:0;
	$vars['row'] && $propArray = array_slice($propArray, 0, $vars['row']);
	return $propArray;
}

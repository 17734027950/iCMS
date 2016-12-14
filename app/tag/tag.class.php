<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
defined('TAG_APPID') OR define('TAG_APPID',0);

class tag {
    public static $remove = true;
    public static $addStatus = '1';

	public static function data($fv=0,$field='name',$limit=0){
		$sql      = $fv ? "where `$field`='$fv'":'';
		$limitSQL = $limit ? "LIMIT $limit ":'';
	    return iDB::all("SELECT * FROM `#iCMS@__tags` {$sql} order by id DESC {$limitSQL}");
	}
	public static function cache($value=0,$field='id'){
		$rs     = self::data($value,$field);
		$_count = count($rs);
	    for($i=0;$i<$_count;$i++) {
			$C              = iCache::get('iCMS/category/'.$rs[$i]['cid']);
			$TC             = iCache::get('iCMS/category/'.$rs[$i]['tcid']);
			$rs[$i]['iurl'] = iURL::get('tag',array($rs[$i],$C,$TC));
			$rs[$i]['url']  = $rs[$i]['iurl']->href;
			$tkey           = self::tkey($rs[$i]['cid']);
	        iCache::set($tkey,$rs[$i],0);
	    }
	}
    public static function tkey($cid){
		$ncid = abs(intval($cid));
		$ncid = sprintf("%08d", $ncid);
		$dir1 = substr($ncid, 0, 2);
		$dir2 = substr($ncid, 2, 3);
		$tkey = $dir1.'/'.$dir2.'/'.$cid;
        return 'iCMS/tags/'.$tkey;
    }
    public static function getag($key='tags',&$array,$C,$TC){
    	if(empty($array[$key])) return;

		$strLink	= '';
        $strArray	= explode(',',$array[$key]);

        foreach($strArray AS $k=>$name){
        	$name				= trim($name);
        	$_cache				= self::getCache($name,$C['cid'],$TC['cid']);
			$strA[$k]['name']	= $name;
			$strA[$k]['url']	= $_cache['url']?$_cache['url']:iCMS_PUBLIC_URL.'/search.php?q='.$name;
			$strLink.='<a href="'.$strA[$k]['url'].'" target="_self">'.$strA[$k]['name'].'</a> ';
        }
        $search	= $C['name'];

        $sstrA	= $strArray;
        count($strArray)>3 && $sstrA = array_slice($strArray,0,3);
        $sstr	= implode(',',$sstrA);
        $sstr && $search = $sstr;

        $array[$key.'link']		= $strLink;
        $array[$key.'_array']	= $strA;
        $array['search'][$key]	= $search;

        return array(
        	$key.'link'		=> $strLink,
        	$key.'_array'	=> $strA,
        	'search'		=> array($key=>$search)
        );
    }

	public static function getCache($tid){
		$tkey	= self::tkey($tid);
		return iCache::get($tkey);
	}

    public static function delCache($tid) {
		$ids = implode(',',(array)$tid);
		iDB::query("DELETE FROM `#iCMS@__tags` WHERE `id` in ($ids) ");
		$c   = count($tid);
        for($i=0;$i<$c;$i++) {
			$tkey = self::tkey($tid[$i]);
			iCache::delete($tkey);
        }
    }
    public static function map_iid($var=array(),$iid=0){
    	foreach ((array)$var as $key => $t) {
    		iDB::query("UPDATE `#iCMS@__tags_map` SET `iid` = '$iid' WHERE `id` = '".$t[4]."'");
    	}
    }

	public static function add($tags,$uid="0",$iid="0",$cid='0',$tcid='0') {
		$a        = explode(',',$tags);
		$c        = count($a);
		$tagArray = array();
	    for($i=0;$i<$c;$i++) {
	        $tagArray[$i] = self::update($a[$i],$uid,$iid,$cid,$tcid);
	    }
	    return implode(',', (array)$tagArray);
	}
	public static function update($tag,$uid="0",$iid="0",$cid='0',$tcid='0') {
	    if(empty($tag)) return;

	    $tag = htmlspecialchars_decode($tag);
	    $tag = preg_replace('/<[\/\!]*?[^<>]*?>/is','',$tag);
	    $tid = iDB::value("SELECT `id` FROM `#iCMS@__tags` WHERE `name`='$tag'");
	    if($tid) {
	        $tlid = iDB::value("
                SELECT `id` FROM `#iCMS@__tags_map`
                WHERE `iid`='$iid' and `node`='$tid' and `appid`='".TAG_APPID."'");
	        if(empty($tlid)) {
	            iDB::query("
                    INSERT INTO `#iCMS@__tags_map` (`node`, `iid`, `appid`)
                    VALUES ('$tid', '$iid', '".TAG_APPID."')");
	            iDB::query("
                    UPDATE `#iCMS@__tags`
                    SET  `count`=count+1,`pubdate`='".time()."'
                    WHERE `id`='$tid'");
	        }
	    }else {
			$tkey   = pinyin($tag,iCMS::$config['other']['py_split']);
			// $fields = array('uid', 'cid', 'tcid', 'pid', 'tkey', 'name', 'seotitle', 'subtitle', 'keywords', 'description', 'haspic', 'pic', 'url', 'related', 'count', 'weight', 'tpl', 'ordernum', 'pubdate', 'status');
			// $data   = compact ($fields);
			// $data['pid'] = '0';
			// iDB::insert('tags',$data);
	        iDB::query("INSERT INTO `#iCMS@__tags`
(`uid`, `cid`, `tcid`, `pid`, `tkey`, `name`,
    `seotitle`, `subtitle`, `keywords`, `description`, `haspic`, `pic`, `url`,
    `related`, `count`, `weight`, `tpl`, `ordernum`,
    `pubdate`,`postime`, `status`)
VALUES ('$uid', '$cid', '$tcid', '0', '$tkey', '$tag',
    '', '', '', '', '', '', '', '', '1', '0', '', '0',
    '".time()."', '".time()."', '".self::$addStatus."');");
	        $tid = iDB::$insert_id;
	        self::cache($tag);
	        iDB::query("
                INSERT INTO `#iCMS@__tags_map` (`node`, `iid`, `appid`)
                VALUES ('$tid', '$iid', '".TAG_APPID."')");
	    	$tmid = iDB::$insert_id;
	    }
	    return $tag;
	}
	public static function diff($Ntags,$Otags,$uid="0",$iid="0",$cid='0',$tcid='0') {
		$N        = explode(',', $Ntags);
		$O        = explode(',', $Otags);
		$diff     = array_diff_values($N,$O);
		$tagArray = array();
	    foreach((array)$N AS $i=>$tag) {//新增
            $tagArray[$i] = self::update($tag,$uid,$iid,$cid,$tcid);
		}
	    foreach((array)$diff['-'] AS $tag) {//减少
	        $tA	= iDB::row("
                SELECT `id`,`count`
                FROM `#iCMS@__tags`
                WHERE `name`='$tag' LIMIT 1;");
	        if($tA->count<=1) {
	        	//$iid && $sql="AND `iid`='$iid'";
	            iDB::query("DELETE FROM `#iCMS@__tags`  WHERE `name`='$tag'");
	            iDB::query("DELETE FROM `#iCMS@__tags_map` WHERE `node`='$tA->id'");
	        }else {
	            iDB::query("
                    UPDATE `#iCMS@__tags`
                    SET  `count`=count-1,`pubdate`='".time()."'
                    WHERE `name`='$tag' and `count`>0");
	            iDB::query("
                    DELETE FROM `#iCMS@__tags_map`
                    WHERE `iid`='$iid'
                    AND `node`='$tA->id'
                    AND `appid`='".TAG_APPID."'");
	        }
	   }
	   return implode(',', (array)$tagArray);
	}
	public static function del($tags,$field='name',$iid=0){
	    $tagArray	= explode(",",$tags);
	    $iid && $sql="AND `iid`='$iid'";
	    foreach($tagArray AS $k=>$v) {
	    	$tag	= iDB::row("SELECT * FROM `#iCMS@__tags` WHERE `$field`='$v' LIMIT 1;");
	    	$tRS	= iDB::all("SELECT `iid` FROM `#iCMS@__tags_map` WHERE `node`='$tag->id' AND `appid`='".TAG_APPID."' {$sql}");
	    	foreach((array)$tRS AS $TL) {
	    		$idA[]=$TL['iid'];
	    	}
	    	if($idA){
	    		$ids = iPHP::values($idA,null);
                if($ids){
                    iPHP::app('apps.class','static');
                    $table = APPS::table(TAG_APPID);
                    iDB::query("
                        UPDATE `#iCMS@__$table`
                        SET `tags`= REPLACE(tags, '$tag->name,',''),
                        `tags`= REPLACE(tags, ',$tag->name','')
                        WHERE id IN($ids)
                    ");
                }
	    	}
            self::$remove && iDB::query("DELETE FROM `#iCMS@__tags`  WHERE `$field`='$v'");
            iDB::query("
                DELETE FROM
                `#iCMS@__tags_map`
                WHERE `node`='$tag->id'
                AND `appid`='".TAG_APPID."' {$sql}");
            $ckey = self::tkey($tag->cid);
            iCache::delete($ckey);
	    }
	}
}

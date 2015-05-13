<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 * @$Id: public.app.php 1392 2013-05-20 12:28:08Z coolmoo $
 */
class publicApp {
	public $methods	= array('weixin','sitemapindex','sitemap','seccode','agreement','crontab','time','qrcode');
    public function API_agreement(){
    	iPHP::view('iCMS://agreement.htm');
    }
    public function API_sitemapindex(){
        header("Content-type:text/xml");
        iPHP::view('iCMS://sitemap.index.htm');
    }
    public function API_sitemap(){
        header("Content-type:text/xml");
        iPHP::assign('cid',(int)$_GET['cid']);
        iPHP::view('iCMS://sitemap.baidu.htm');
    }
    /**
     * [API_weixin 向下兼容]
     */
    public function API_weixin(){
        $weixinApp = iPHP::app("weixin");
        $weixinApp->API_interface();
    }
    public function API_crontab(){
        $timeline = iCMS::timeline();
        //var_dump($timeline);
        $pieces = array();
        foreach ($timeline as $key => $bool) {
            $field = "hits_{$key}";
            if($key=='yday'){
                if($bool==1){
                    $pieces[]="`hits_yday` = hits_today";
                }elseif ($bool>1) {
                    $pieces[]="`hits_yday` = 0";
                }
            }else{
                $bool OR $pieces[]="`{$field}` = 0";
            }
        }
        $pieces && $sql = implode(',', $pieces);
        if($sql){
        	//点击初始化
        	iDB::query("UPDATE `#iCMS@__article` SET {$sql}");
        	iDB::query("UPDATE `#iCMS@__user` SET {$sql}");
        }
    }
    public function API_seccode(){
        @header("Expires: -1");
        @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
        @header("Pragma: no-cache");
        iPHP::loadClass("Seccode");
        $_GET['pre'] && $pre = iS::escapeStr($_GET['pre']);
        iSeccode::run($pre);
    }

    public function API_qrcode(){
        $url = iS::escapeStr($_GET['url']);
        iPHP::QRcode($url);
    }
}

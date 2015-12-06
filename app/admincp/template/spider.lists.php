<?php
/**
 * @package iCMS
 * @copyright 2007-2015, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 * @$Id: files.manage.php 179 2013-03-29 03:21:28Z coolmoo $
 */
defined('iPHP') OR exit('What are you doing?');
iACP::head(false);
?>
<script type="text/javascript">
$(function() {
})
</script>
<style>
.widget-title span.icon { width: 24px; }
</style>
<div class="widget-box widget-plain" id="spider-list">
  <div class="widget-title"> <span class="icon">
    <input type="checkbox" class="checkAll" data-target=".spider-list" />
    </span>
    <h5 class="brs">采集列表</h5>
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=mpublish" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
      <table class="table table-bordered table-condensed table-hover">
        <thead>
          <tr>
            <th><i class="fa fa-arrows-v"></i></th>
            <th>标题</th>
            <th>网址</th>
            <th>操作</th>
          </tr>
        </thead>
  <?php foreach ($listsArray AS $furl => $lists) {?>
        <thead>
          <tr>
            <th><input type="checkbox" class="checkAll" data-target="#spider-list-<?php echo md5($furl); ?>" /></th>
            <th colspan="3"><?php echo $furl; ?></th>
          </tr>
        </thead>
        <tbody class="spider-list" id="spider-list-<?php echo md5($furl); ?>">
    <?php
	  	foreach ($lists AS $lkey => $row) {
        list(spider::$title,spider::$url) = spiderTools::title_url($row,$rule,$furl);
        if(spider::$url===false){
            continue;
        }
				$hash = md5(spider::$url);
				if(spider::checker($work)===true){
		?>
          <tr id="<?php echo $hash; ?>">
            <td><input type="checkbox" name="pub[]" value="<?php echo $cid; ?>|<?php echo $pid; ?>|<?php echo $rid; ?>|<?php echo spider::$url; ?>|<?php echo spider::$title; ?>|<?php echo $hash; ?>" /></td>
            <td><?php echo spider::$title; ?></td>
            <td><?php echo spider::$url; ?></td>
            <td>
              <a href="<?php echo APP_FURI; ?>&do=publish&cid=<?php echo $cid; ?>&pid=<?php echo $pid; ?>&rid=<?php echo $rid; ?>&hash=<?php echo $hash; ?>&url=<?php echo urlencode(spider::$url); ?>&title=<?php echo  urlencode(spider::$title); ?>" class="btn btn-small" target="iPHP_FRAME"><i class="fa fa-check"></i> 发布</a>
              <a href="<?php echo APP_URI;  ?>&do=testdata&cid=<?php echo $cid; ?>&pid=<?php echo $pid; ?>&rid=<?php echo $rid; ?>&url=<?php echo urlencode(spider::$url); ?>&title=<?php echo  urlencode(spider::$title); ?>" class="btn btn-small" target="_blank"><i class="fa fa-keyboard-o"></i> 测试</a>
              <a href="<?php echo APP_FURI; ?>&do=markurl&cid=<?php echo $cid; ?>&pid=<?php echo $pid; ?>&rid=<?php echo $rid; ?>&url=<?php echo urlencode(spider::$url); ?>&title=<?php echo  urlencode(spider::$title); ?>" class="btn btn-small" target="iPHP_FRAME"><i class="fa fa-trash-o"></i> 移除</a>
            </td>
          </tr>
        <?php }?>
      <?php }?>
  <?php } ?>
        </tbody>
      </table>
      <div class="form-actions mt0">
        <div class="input-prepend input-append mt20"> <span class="add-on">全选
          <input type="checkbox" class="checkAll" data-target=".spider-list" />
          </span>
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> 开始采集</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php iACP::foot(); ?>

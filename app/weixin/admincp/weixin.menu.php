<?php /**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<link rel="stylesheet" href="./app/admincp/ui/jquery/treeview-0.1.0.css" type="text/css" />
<script type="text/javascript" src="./app/admincp/ui/jquery/treeview-0.1.0.js"></script>
<script type="text/javascript">
$(function(){
    $("#tree").treeview({collapsed: false});
    $(".addsub").click(function(){
      var that = this;
      $(this).prev('.button_key').hide();
      var subkey = $(this).attr("subkey");
      var ul   = $('.sub_button_'+subkey);
      var length = $("li",ul).size();
      if(length>4){
        iCMS.alert("每个一级菜单最多包含5个二级菜单");
        return false;
      }
      var clone  = '<?php echo html2js($this->menu_button_li());?>';
      ul.append(clone);

      $('li',ul).each(function(i){
        $('[name^=wx_button]',this).each(function(ii){
          this.name = this.name.replace('~KEY~',subkey);
          this.name = this.name.replace('~i~',i);
        });
      });
      return false;
    });
    var doc = $('ul.wx_button_tree');
    doc.on("click",'.wx_del_sub_button',function() {
      var li = $(this).parent().parent(),ul=li.parent();
      li.remove();
      var length = $("li",ul).size();
      if(length<1){
        ul.siblings('div').find('.button_key').show();
      }
    });

    doc.on("change",'select',function() {
        var button_key = $(this).siblings('.button_key');
        var name = $('input',button_key).attr('name');
        name = name.replace('[url]','[key]');
        name = name.replace('[media_id]','[key]');
        var text = 'KEY';
        switch (this.value) {
          case 'view':
            text = 'URL';
            name = name.replace('[key]','[url]');
          break;
          case 'media_id':
          case 'view_limited':
            text = 'media_id';
            name = name.replace('[key]','[media_id]');
          break;

        }
        $('.add-on',button_key).text(text);
        $('input',button_key).attr('name',name);
    });
});
</script>
<style>
.weixin-menu .add-on{width: 36px;}
.weixin-menu select{width: 120px;}
</style>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
      <h5>微信自定义菜单管理</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save_menu" method="post" class="form-inline" id="iCMS-setting" target="iPHP_FRAME">
        <div id="wxmenu-add" class="tab-content">
          <div class="weixin-menu">
            <ul id="tree" class="treeview wx_button_tree">
              <?php
                $menuArray = $this->config['menu'];
                for ($i=0; $i <3 ; $i++) {
                  $type = $menuArray[$i]['type'];
                  $keyname = $this->menu_get_type($type);
              ?>
              <li>
                <div class="input-prepend input-append">
                    <span class="add-on">类型</span>
                    <select name="wx_button[<?php echo $i;?>][type]">
                      <?php echo $this->menu_get_type($type,'opt');?>
                    </select>
                  <span class="add-on">名称</span><input type="text" name="wx_button[<?php echo $i;?>][name]" value="<?php echo $menuArray[$i]['name'];?>">
                  <span class="button_key <?php if($menuArray[$i]['sub_button']){ echo 'hide'; }?>">
                    <span class="add-on"><?php echo strtoupper($keyname);?></span><input type="text" name="wx_button[<?php echo $i;?>][<?php echo $keyname;?>]" value="<?php echo $menuArray[$i][$keyname];?>">
                  </span>

                  <a href="javascript:void(0);" subkey="<?php echo $i;?>" class="btn addsub"/><i class="fa fa-plus"></i> 子菜单</a>
                </div>
                <ul class="sub_button sub_button_<?php echo $i;?>">
                  <?php
                  foreach ((array)$menuArray[$i]['sub_button'] as $key => $value) {
                    echo $this->menu_button_li($i,$key,$value);
                  }
                  ?>
                </ul>
              </li>
              <?php } ?>
            </ul>
          </div>
          <div class="mt20"></div>
          <div class="alert alert-block">
            <h4>注意事项</h4>
            <h3>请先提交后，才能同步菜单到微信</h3>
1、自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。<br />
2、一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。<br />
3、创建自定义菜单后，由于微信客户端缓存，需要5分钟微信客户端才会展现出来。测试时可以尝试取消关注公众账号后再次关注，则可以看到创建后的效果。
<hr />
更多说明请自行查看<a href="https://mp.weixin.qq.com/wiki/" target='_blank'>微信公众平台手册</a>  ( https://mp.weixin.qq.com/wiki/ ) 自定义菜单
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> 提交</button>
          <a class="btn btn-success" href="<?php echo APP_FURI; ?>&do=rsync_menu" target="iPHP_FRAME"><i class="fa fa-upload"></i> 同步菜单到微信公众平台</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>

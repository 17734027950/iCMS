#欢迎使用 iCMS 内容管理系统

iCMS 是一套采用 PHP 和 MySQL 构建的高效简洁的内容管理系统,为您的网站提供一个完美的开源解决方案

##iCMS 可以用来干什么
> * 博客
> * 资讯/新闻站点
> * 公司产品展示
> * 图片展示
> * 快速二次开发

#开始安装
**注意检查你的主机/服务器是否支持 iCMS 完整运行**
> * [PHP 5.3][1] 以上版本 **推荐使用PHP5.6以上版本**
> * [Mysql][2] 数据库支持，并在PHP中安装了相关扩展
> * CURL扩展支持
> * mbstring或者iconv扩展支持

## 下载最新版

> * 请访问 `http://www.idreamsoft.com/download` 获得最新的稳定版本，并下载

## 解压缩安装包
解压缩后你会看到如下的目录结构
```
./iCMS
├─app
│  ├─admincp
│  ├─apps
│  ├─article
│  ├─cache
│  ├─category
│  ├─comment
│  ├─config
│  ├─content
│  ├─database
│  ├─editor
│  ├─favorite
│  ├─files
│  ├─filter
│  ├─former
│  ├─forms
│  ├─func
│  ├─hook
│  ├─html
│  ├─index
│  ├─keywords
│  ├─links
│  ├─members
│  ├─menu
│  ├─message
│  ├─patch
│  ├─plugin
│  ├─prop
│  ├─public
│  ├─search
│  ├─spider
│  ├─tag
│  ├─user
│  └─weixin
├─cache
│  ├─backup
│  ├─conf
│  ├─iCMS
│  └─template
├─core
├─install
├─iPHP
├─public
├─res
├─template
│  admincp.php
│  article.php
│  category.php
│  comment.php
│  config.php
│  crossdomain.xml
│  favicon.ico
│  favorite.php
│  gulpfile.js
│  iCMS.php
│  index.php
│  LICENSE
│  package.json
│  README.md
│  robots.txt
│  tag.php
│  TODO.md
│  UPDATE.md
└─user.php

```

## 上传至服务器WEB目录

将上面列出的所有文件和目录上传到服务器上的指定目录，

如DocumentRoot目录或者任何你希望安装iCMS的目录。

## 安装
上传完毕后使用浏览器直接访问安装目录(install)即可看到iCMS的安装程序。

恭喜，你的服务器可以完美支持iCMS，

点击**开始安装**进入下一步。

打开浏览器，在地址栏中输入http://您的网站域名/install/index.php

![安装界面][5]

## 填写配置信息

按照程序安装向导的要求填写相关服务器参数和初始化设置信息，

完成后点击下一步。

## 完成安装

在安装成功界面中会显示自动生成的初始登录密码，

请务必牢记或马上进入后台按提示更改。

已经大功告成，祝您iCMS使用愉快！:)


[1]: http://www.php.net/
[2]: http://www.mysql.com/
[3]: http://www.postgresql.org/
[4]: http://sqlite.org/
[5]: http://www.idreamsoft.com/static/install7.jpg
[6]: http://git-scm.com/book/zh/v1/%E8%B5%B7%E6%AD%A5-%E5%AE%89%E8%A3%85-Git

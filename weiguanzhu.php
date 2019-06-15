<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title> 鸿宇购物广场-51红包领取页 </title>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
<link rel="stylesheet" type="text/css" href="http://cy.hongyujituan.com/css/login.css" />


</head>
<body >
<div class="top animated fadeInDown"><img src="/hb/images/qrcode.png" /></div>

<div class="place-date">

  </div>
<div class="bottom"><img src="/hb/images/bottom.png" /></div>
<div><a id='clickMe' href='javascript:closeWx();'><span style='visibility:hidden'>我的</span></a></div>
<script data-main="js/app/login.js" src="lib/require.js"></script>
  <script type="text/javascript">
    // 对浏览器的UserAgent进行正则匹配，不含有微信独有标识的则为其他浏览器
    var useragent = navigator.userAgent;
    if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
        // 这里警告框会阻塞当前页面继续加载
        alert('已禁止本次访问：您必须使用微信内置浏览器访问本页面！');
        // 以下代码是用javascript强行关闭当前页面
        var opened = window.open('about:blank', '_self');
        opened.opener = null;
        opened.close();
    }
</script>
</body>
</html>
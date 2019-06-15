# 简单版红包发送API
用于发送红包，完整API

# 使用条件
使用前提：需要微信认证号，已申请微信支付，已备案域名

# 需要设置
微信公众号后台设置--》接口权限--》网页获取用户基本信息中设置 微信API所使用的的域名如open.chi-na.cn

微信支付后台设置调用IP地址即绑定域名的IP地址

设置公众号 个性菜单地址

https://open.weixin.qq.com/
connect/oauth2/authorize?appid=自己的APPID&redirect_uri=调用页面的绝对地址&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect


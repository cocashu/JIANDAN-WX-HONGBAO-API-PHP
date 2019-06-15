<?php
header("Content-type:text/html;charset=utf-8");
 $a=$_GET['0o0o00oo']; //显示"ok"

 $b=$_GET['o0o0']; //显示"ok"

 $c=$_GET['oo00oo']; //显示"ok"
$d=strtoupper(md5($a.$b.'自定义密钥'));
//与生成二维码的客户端匹配
//echo $c;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title> 鸿宇购物广场-51红包领取页 </title>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
<link rel="stylesheet" type="text/css" href="/css/login.css" />

  <!--关闭分享按钮-->
<script>
noShare()
        function noShare(){
            document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
                  // 通过下面这个API隐藏右上角按钮
                WeixinJSBridge.call('hideOptionMenu');
            });
        }
function closeWx(){
        window.close();
        WeixinJSBridge.call('closeWindow');
}
</script>
<SCRIPT   LANGUAGE="JavaScript">  

// 1秒后模拟点击
setTimeout(function() {
    // IE
    if(document.all) {
        document.getElementById("clickMe").click();
    }
    // 其它浏览器
    else {
        var e = document.createEvent("MouseEvents");
        e.initEvent("click", true, true);
        document.getElementById("clickMe").dispatchEvent(e);
    }
}, 10000);
</SCRIPT>

</head>
<body >
<div class="top animated fadeInDown"><img src="/hb/images/3.jpg" /></div>

<div class="place-date">

<?php
/header('Content-type:text/html; Charset=utf-8');
$mchid = '136*******';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
$appid = 'wx3d4a3******';  //微信支付申请对应的公众号的APPID
$appKey = '54b99*******************';   //微信支付申请对应的公众号的APP Key
$apiKey = 'YCfH*************';   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
//填写证书所在位置，证书在https://pay.weixin.qq.com 账户中心->账户设置->API安全->下载证书，下载后将apiclient_cert.pem和apiclient_key.pem上传到服务器。
$apiclient_cert = getcwd().'/cert/apiclient_cert.pem';
$apiclient_key = getcwd().'/cert/apiclient_key.pem';
//①、获取当前访问页面的用户openid（如果给指定用户发送红包，则填写指定用户的openid)
$wxPay = new WxpayService($mchid,$appid,$appKey,$apiKey,$apiclient_cert,$apiclient_key);
$openId = $wxPay->GetOpenid();      //获取openid
if(!$openId) exit(header("Location:weiguanzhu.php"));

//②、发送红包
$outTradeNo = uniqid();     //你自己的商品订单号
$payAmount = mt_rand(150, 250);          //红包金额，单位:元
$sendName = '鸿宇购物广场';    //红包发送者名称
$wishing = '祝您五一快乐';      //红包祝福语
$act_name='5动全城,1触即发';           //活动名称

$uptime =date('Y-m-d H:i:s');	
$con = mysql_connect("127.0.0.1","canyinka","rCW5LWKfBpMD3ZZL");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("canyinka", $con);
mysql_query("set names 'utf8'"); //使用GBK中文编码; 
  
  //验证签名-
  if ($d=$c){
  $con = mysql_connect("127.0.0.1","canyinka","rCW5LWKfBpMD3ZZL");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("canyinka", $con);
$rows=mysql_query("select * from `hongbao` where `sign`='$c';");
if (mysql_num_rows($rows) < 1){//验证是否被使用
  //没被使用过
//开始一个事务   
mysql_query("BEGIN"); //或者mysql_query("START TRANSACTION");   
$sql="INSERT INTO hongbao (openid,jine,uptime,sysl,shijian,sign)VALUES('$openId','$payAmount','$uptime','$b','$a','$c')";
$sql2 = "UPDATE hongbaosl  SET hbsl = hbsl-1 WHERE id = '1' and hbsl>'0'"; 
$res = mysql_query($sql);   
$res1 = mysql_query($sql2);   
$result = mysql_affected_rows(); 
if($res && $result){   
mysql_query("COMMIT"); 
echo '您的红包已经发出，祝您51劳动节快乐！';
echo "<span style='visibility:hidden'>";
$result1=$wxPay->createJsBizPackage($openId,$payAmount,$outTradeNo,$sendName,$wishing,$act_name);
echo '</span>';
}else{   
 mysql_query("ROLLBACK");   
echo '当日660个随机红包已经全部发放完毕';   
}   
mysql_query("END"); 
}
  else
   //被使用过   
  {  echo '已经领过这个红包了';}
}
else
{
  echo '验证签名错误';
 // header("Location:weiguanzhu.php");
}
 
//echo 'success';
  ?>
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
</html><?
class WxpayService
{
    protected $mchid;
    protected $appid;
    protected $appKey;
    protected $apiKey;
    protected $apiclient_cert;
    protected $apiclient_key;
    public $data = null;
    public function __construct($mchid, $appid, $appKey,$key,$apiclient_cert,$apiclient_key)
    {
        $this->mchid = $mchid;
        $this->appid = $appid;
        $this->appKey = $appKey;
        $this->apiKey = $key;
        $this->apiclient_cert = $apiclient_cert;
        $this->apiclient_key = $apiclient_key;
    }
    /**
     * 通过跳转获取用户的openid，跳转流程如下：
     * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
     * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
     * @return 用户的openid
     */
    public function GetOpenid()
    {
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $scheme = $_SERVER['HTTPS']=='on' ? 'https://' : 'http://';
            $baseUrl = urlencode($scheme.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
            $url = $this->__CreateOauthUrlForCode($baseUrl);
            Header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $openid = $this->getOpenidFromMp($code);
            return $openid;
        }
    }
    /**
     * 通过code从工作平台获取openid机器access_token
     * @param string $code 微信跳转回来带上的code
     * @return openid
     */
    public function GetOpenidFromMp($code)
    {
        $url = $this->__CreateOauthUrlForOpenid($code);
        $res = self::curlGet($url);
        //取出openid
        $data = json_decode($res,true);
        $this->data = $data;
        $openid = $data['openid'];
       // return $openid;
        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appKey;
	    $access_msg = json_decode(file_get_contents($access_token));
  		$token = $access_msg->access_token;
    	$subscribe_msg = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';
    	$subscribe = json_decode(file_get_contents($subscribe_msg));
	    $gzxx = $subscribe->subscribe;
	    if($gzxx === 1){
 	    return $openid;
 	   }else{
		//return $openid;
		}
    }
    /**
     * 构造获取open和access_toke的url地址
     * @param string $code，微信跳转带回的code
     * @return 请求的url
     */
    private function __CreateOauthUrlForOpenid($code)
    {
        $urlObj["appid"] = $this->appid;
        $urlObj["secret"] = $this->appKey;
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }
    /**
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     * @return 返回构造好的url
     */
    private function __CreateOauthUrlForCode($redirectUrl)
    {
        $urlObj["appid"] = $this->appid;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE"."#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }
    /**
     * 拼接签名字符串
     * @param array $urlObj
     * @return 返回已经拼接好的字符串
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign") $buff .= $k . "=" . $v . "&";
        }
        $buff = trim($buff, "&");
        return $buff;
    }
    /**
     * 统一下单
     * @param string $openid 调用【网页授权获取用户信息】接口获取到用户在该公众号下的Openid
     * @param float $totalFee 收款总费用 单位元
     * @param string $outTradeNo 唯一的订单号
     * @param string $orderName 订单名称
     * @param string $notifyUrl 支付结果通知url 不要有问号
     * @param string $timestamp 支付时间
     * @return string
     */
    public function createJsBizPackage($openid, $totalFee, $outTradeNo, $sendName,$wishing,$actName)
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        $unified = array(
            'wxappid' => $config['appid'],
            'send_name' => $sendName,
            'mch_id' => $config['mch_id'],
            'nonce_str' => self::createNonceStr(),
            're_openid' => $openid,
            'mch_billno' => $outTradeNo,
            'client_ip' => '127.0.0.1',
            'total_amount' => intval($totalFee),       //单位 转为分
            'total_num'=>1,     //红包发放总人数
            'wishing'=>$wishing,      //红包祝福语
            'act_name'=>$actName,           //活动名称
            'remark'=>'5动全城,1触即发',               //备注信息，如为中文注意转为UTF8编码
            'scene_id'=>'PRODUCT_2',      //发放红包使用场景，红包金额大于200时必传。https://pay.weixin.qq.com/wiki/doc/api/tools/cash_coupon.php?chapter=13_4&index=3
        );
        $unified['sign'] = self::getSign($unified, $config['key']);
        $responseXml = $this->curlPost('https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack', self::arrayToXml($unified));
        file_put_contents('1.txt',print_r($responseXml,true));
//        print_r($responseXml,true);die;
        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($unifiedOrder === false) {
            die('parse xml error');
        }
        if ($unifiedOrder->return_code != 'SUCCESS') {
            die($unifiedOrder->return_msg);
        }
        if ($unifiedOrder->result_code != 'SUCCESS') {
            die($unifiedOrder->err_code);
        }
        return true;
    }
    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //第一种方法，cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/cert/apiclient_cert.pem');
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,getcwd().'/cert/apiclient_key.pem');
        //第二种方式，两个文件合成一个.pem文件
//        curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
        $data = curl_exec($ch);
        var_dump($data);die;
        curl_close($ch);
        return $data;
    }
    public static function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        file_put_contents('1.txt',$xml);
        return $xml;
    }
    public static function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }
    protected static function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}
?>

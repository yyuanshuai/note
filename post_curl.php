
function post_curl($url,$post_data)
{
    $headers = ["Content-type: application/json;charset='utf-8'","Accept: application/json", "Cache-Control: no-cache", "Pragma: no-cache"];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);         // 要访问的地址
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);                         // 显示返回的Header区域内容
    curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // 对认证证书来源的检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);    // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');      // 模拟用户使用的浏览器
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);     // 使用自动跳转
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);             // 自动设置Referer
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);                   // Post提交的数据包
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                   // Post提交的数据包
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);                        // 设置超时限制防止死循环
    //$curl_error = curl_error($ch);
    $json = curl_exec($ch);

    curl_close($ch);
    return $json;
}
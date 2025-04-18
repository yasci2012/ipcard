<?php
header("Content-type: image/JPEG");
use UAParser\Parser;
require_once 'vendor/autoload.php';
$im = imagecreatefromjpeg("xhxh.jpg"); 
$ip = $_SERVER["REMOTE_ADDR"];
$ua = $_SERVER['HTTP_USER_AGENT'];
$get = $_GET["s"];
$get = base64_decode(str_replace(" ","+",$get));
$weekarray = array("日","一","二","三","四","五","六"); 

// 自定义系统和浏览器识别函数
function getOSInfo($ua) {
    // Windows 11 识别（更精确的版本检测）
    if (preg_match('/Windows NT 10\.0.*[Bb]uild[\/\s](\d+)/', $ua, $matches)) {
        if (isset($matches[1]) && intval($matches[1]) >= 22000) {
            return 'Windows 11';
        }
    }
    
    // 常规操作系统识别（更新了更多系统版本）
    $os_array = array(
        '/windows nt 10\.0/i'     => 'Windows 10',
        '/windows nt 6\.3/i'      => 'Windows 8.1',
        '/windows nt 6\.2/i'      => 'Windows 8',
        '/windows nt 6\.1/i'      => 'Windows 7',
        '/windows nt 6\.0/i'      => 'Windows Vista',
        '/windows nt 5\.2/i'      => 'Windows Server 2003/XP x64',
        '/windows nt 5\.1/i'      => 'Windows XP',
        '/windows xp/i'           => 'Windows XP',
        '/windows nt 5\.0/i'      => 'Windows 2000',
        '/windows me/i'           => 'Windows ME',
        '/win98/i'                => 'Windows 98',
        '/win95/i'                => 'Windows 95',
        '/win16/i'                => 'Windows 3.11',
        '/macintosh|mac os x/i'   => 'Mac OS X',
        '/mac_powerpc/i'          => 'Mac OS 9',
        '/linux/i'                => 'Linux',
        '/ubuntu/i'               => 'Ubuntu',
        '/debian/i'               => 'Debian',
        '/fedora/i'               => 'Fedora',
        '/centos/i'               => 'CentOS',
        '/redhat/i'               => 'RedHat',
        '/android/i'              => 'Android',
        '/iphone/i'               => 'iPhone',
        '/ipod/i'                 => 'iPod',
        '/ipad/i'                 => 'iPad',
        '/blackberry/i'           => 'BlackBerry',
        '/webos/i'                => 'Mobile',
        '/chromeos/i'             => 'Chrome OS'
    );

    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $ua)) {
            return $value;
        }
    }
    
    // 使用UA Parser作为备选
    $parser = Parser::create();
    $result = $parser->parse($ua);
    return $result->os->toString();
}

function getBrowserInfo($ua) {
    // 获取浏览器详细信息（更新了更多浏览器类型）
    $browser_array = array(
        '/msie/i'       => 'Internet Explorer',
        '/firefox/i'    => 'Firefox',
        '/safari/i'     => 'Safari',
        '/chrome/i'     => 'Chrome',
        '/edge/i'       => 'Edge',
        '/edg/i'        => 'Edge',
        '/opera/i'      => 'Opera',
        '/opr/i'        => 'Opera',
        '/netscape/i'   => 'Netscape',
        '/maxthon/i'    => 'Maxthon',
        '/konqueror/i'  => 'Konqueror',
        '/mobile/i'     => 'Mobile Browser',
        '/MicroMessenger/i' => 'WeChat',
        '/QQ/i'         => 'QQ Browser',
        '/UCBrowser/i'  => 'UC Browser',
        '/Vivaldi/i'    => 'Vivaldi',
        '/brave/i'      => 'Brave',
        '/samsungbrowser/i' => 'Samsung Browser',
        '/yandex/i'     => 'Yandex Browser',
        '/duckduckgo/i' => 'DuckDuckGo Browser',
        '/sogou/i'      => 'Sogou Explorer',
        '/360se/i'      => '360 Secure Browser',
        '/2345explorer/i' => '2345 Explorer',
        '/liebao/i'     => 'Liebao Browser'
    );

    $browser_name = "未知浏览器";
    $version = "";
    
    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $ua, $match)) {
            $browser_name = $value;
            break;
        }
    }
    
    // 获取浏览器版本号（优化版本号提取）
    $known = array('Version', $browser_name, 'other', 'rv', 'MSIE', 'Chrome', 'Firefox', 'Safari', 'Edge', 'Opera');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    
    if (preg_match_all($pattern, $ua, $matches)) {
        if (count($matches['browser']) > 0 && count($matches['version']) > 0) {
            $i = 0;
            if ($matches['browser'][$i] == 'other') { $i = 1; }
            $version = $matches['version'][$i];
        }
    }
    
    // 特殊处理某些浏览器的版本号
    if ($browser_name == 'Chrome' && preg_match('/Edg(?:e)?\/([0-9.]+)/', $ua, $matches)) {
        $browser_name = 'Edge';
        $version = $matches[1];
    }
    
    // 使用UA Parser获取设备信息
    $parser = Parser::create();
    $result = $parser->parse($ua);
    $device = $result->device->family !== 'Other' ? $result->device->family : '';
    
    // 获取浏览器引擎（更新了更多引擎类型）
    $engine = "";
    if (preg_match('/Trident/i', $ua)) {
        $engine = "Trident";
    } else if (preg_match('/Gecko/i', $ua)) {
        $engine = "Gecko";
    } else if (preg_match('/Presto/i', $ua)) {
        $engine = "Presto";
    } else if (preg_match('/Blink/i', $ua)) {
        $engine = "Blink";
    } else if (preg_match('/WebKit/i', $ua)) {
        $engine = "WebKit";
    } else if (preg_match('/KHTML/i', $ua)) {
        $engine = "KHTML";
    } else if (preg_match('/EdgeHTML/i', $ua)) {
        $engine = "EdgeHTML";
    }
    
    $browser_info = $browser_name;
    if (!empty($version)) {
        $browser_info .= ' ' . $version;
    }
    if (!empty($engine)) {
        $browser_info .= ' (' . $engine . ')';
    }
    if (!empty($device)) {
        $browser_info = $device . ' - ' . $browser_info;
    }
    
    return $browser_info;
}

// 获取系统和浏览器信息
$os = getOSInfo($ua);
$browser = getBrowserInfo($ua);

// API配置：腾讯地图用于位置查询，高德地图用于天气查询
$qq_key = "替换为您的腾讯地图API密钥"; // 替换为您的腾讯地图API密钥
$amap_key = "替换为您的高德地图API密钥"; // 替换为您的高德地图API密钥

// 地址信息 - 使用腾讯地图API
// 获取IP地理位置信息
$ip_url = "https://apis.map.qq.com/ws/location/v1/ip?key={$qq_key}&ip={$ip}";
$ip_data = json_decode(curl_get($ip_url), true);

if($ip_data && $ip_data['status'] == 0) {
    $country = isset($ip_data['result']['ad_info']['nation']) ? $ip_data['result']['ad_info']['nation'] : "未知";
    $region = isset($ip_data['result']['ad_info']['province']) ? $ip_data['result']['ad_info']['province'] : "未知";
    $city = isset($ip_data['result']['ad_info']['city']) ? $ip_data['result']['ad_info']['city'] : "未知";
    $district = isset($ip_data['result']['ad_info']['district']) ? $ip_data['result']['ad_info']['district'] : "";
    
    // 天气信息 - 使用高德地图API
    // 首先尝试通过城市名获取高德地图城市编码
    $geo_url = "https://restapi.amap.com/v3/geocode/geo?key={$amap_key}&address={$city}&city={$region}";
    $geo_data = json_decode(curl_get($geo_url), true);
    
    if($geo_data && $geo_data['status'] == '1' && !empty($geo_data['geocodes'])) {
        $adcode = $geo_data['geocodes'][0]['adcode'];
        
        // 获取天气信息
        $weather_url = "https://restapi.amap.com/v3/weather/weatherInfo?key={$amap_key}&city={$adcode}&extensions=base";
        $weather_data = json_decode(curl_get($weather_url), true);
        
        if($weather_data && $weather_data['status'] == '1' && !empty($weather_data['lives'])) {
            $weather = $weather_data['lives'][0]['weather'];
            $temperature = $weather_data['lives'][0]['temperature'];
        } else {
            $weather = "未知";
            $temperature = "未知";
        }
    } else {
        $weather = "未知";
        $temperature = "未知";
    }
} else {
    // 如果腾讯地图API失败，尝试使用高德地图API作为备选
    $ip_url = "https://restapi.amap.com/v3/ip?key={$amap_key}&ip={$ip}";
    $ip_data = json_decode(curl_get($ip_url), true);

    if($ip_data && $ip_data['status'] == '1') {
        $country = "中国"; // 高德API仅支持中国
        $region = $ip_data['province']; 
        $city = $ip_data['city'];
        $adcode = $ip_data['adcode'];
        
        // 获取天气信息
        $weather_url = "https://restapi.amap.com/v3/weather/weatherInfo?key={$amap_key}&city={$adcode}&extensions=base";
        $weather_data = json_decode(curl_get($weather_url), true);
        
        if($weather_data && $weather_data['status'] == '1' && !empty($weather_data['lives'])) {
            $weather = $weather_data['lives'][0]['weather'];
            $temperature = $weather_data['lives'][0]['temperature'];
        } else {
            $weather = "未知";
            $temperature = "未知";
        }
    } else {
        $country = "未知";
        $region = "未知";
        $city = "未知";
        $district = "";
        $weather = "未知";
        $temperature = "未知";
    }
}

//历史上今天
//$data = json_decode(get_curl('https://xhboke.com/mz/today.php'), true);
//$today = $data['cover']['title']; 
//定义颜色
$black = ImageColorAllocate($im, 0,0,0);//定义黑色的值
$red = ImageColorAllocate($im, 255,0,0);//红色
$font = 'msyh.ttf';//加载字体
//输出
$location = $region;
if(!empty($city) && $city != $region) {
    $location .= '-'.$city;
}
if(!empty($district)) {
    $location .= '-'.$district;
}
imagettftext($im, 16, 0, 10, 40, $red, $font,'欢迎您来自'.$country.'-'.$location.'的朋友');
imagettftext($im, 16, 0, 10, 72, $red, $font, '今天是'.date('Y年n月j日').' 星期'.$weekarray[date("w")]);//当前时间添加到图片
imagettftext($im, 16, 0, 10, 104, $red, $font,'您的IP是:'.$ip.'  '.$weather." ".$temperature.'℃');//ip
imagettftext($im, 16, 0, 10, 140, $red, $font,'您使用的是'.$os.'操作系统');
imagettftext($im, 16, 0, 10, 175, $red, $font,'您使用的是'.$browser);
imagettftext($im, 13, 0, 10, 200, $black, $font,$get); 
ImageGif($im);
ImageDestroy($im);


function curl_get($url, array $params = array(), $timeout = 6){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}
?>



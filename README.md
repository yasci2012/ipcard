原作者已经早已停更，原api更是无法使用：https://github.com/xhboke/IP?tab=readme-ov-file


现在我改写了一下，更适合小白


<h2>API更新说明 (2025)</h2>
<p>现已优化API调用结构，结合腾讯地图和高德地图API的优势：腾讯地图用于IP定位，高德地图用于天气查询</p>

<h3>使用方法</h3>
<p>本项目现在同时使用两个地图API，需要分别申请：</p>
<ol>
  <li>腾讯地图API：用于获取访问者的地理位置（国家、省份、城市、区县）</li>
  <li>高德地图API：用于获取天气信息</li>
</ol>

<h3>配置步骤</h3>
<ol>
  <li>注册腾讯地图开发者账号：<a href="https://lbs.qq.com/" target="_blank">https://lbs.qq.com/</a></li>
  <li>在腾讯地图开发者平台创建应用并获取API Key</li>
  <li>注册高德地图开发者账号：<a href="https://lbs.amap.com/" target="_blank">https://lbs.amap.com/</a></li>
  <li>在高德地图开发者平台创建应用并获取API Key</li>
  <li>在index.php文件中设置两个API Key：</li>
</ol>

<pre>
$qq_key = "您的腾讯地图API Key"; // 替换为您申请的腾讯地图Key
$amap_key = "您的高德地图API Key"; // 替换为您申请的高德地图Key
</pre>

<h3>工作流程</h3>
<ol>
  <li>首先使用腾讯地图API获取访问者IP的地理位置信息</li>
  <li>然后使用高德地图的地理编码API将城市名转换为高德地图的城市编码</li>
  <li>最后使用高德地图的天气API获取对应城市的天气信息</li>
  <li>如果腾讯地图API调用失败，会自动降级使用高德地图API进行IP定位</li>
</ol>

<h3>功能优势</h3>
<ul>
  <li>腾讯地图API提供更详细的地理位置信息，包括区县级别</li>
  <li>高德地图提供准确的天气信息</li>
  <li>双重保障机制，一个API失败时可以使用另一个作为备选</li>
</ul>

<h3>API使用限制</h3>
<p>请注意各平台的API使用限制和计费策略：</p>
<ul>
  <li>腾讯地图API：免费用户日限额，详情请参考<a href="https://lbs.qq.com/service/webService/webServiceGuide/webServicePrice" target="_blank">腾讯位置服务文档</a></li>
  <li>高德地图API：免费用户日限额，详情请参考<a href="https://lbs.amap.com/api/webservice/summary" target="_blank">高德开放平台文档</a></li>
</ul>


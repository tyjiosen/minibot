<?php 

namespace Tyjiosen\Minibot;

/**
 * 基于QQMini机器HTTPAPIv2插件
 */
class Main
{
	public $config;

	public function __construct($config)
	{
		$this->config = $config;
	}

	public function __call($name,$arguments)
	{
		
		/**
		 * 所有请求方式都一致 就懒得在拆开了
		 */
		return  $this->getRes('/' . $name,$arguments[0]?$arguments[0]:[]);
		
	}

	/**
	 * @param  string $url 请求接口
	 * @param  array $data 请求数据
	 * @param  string $method 请求方法
	 * @return array
	 */
	private function getRes($url,$data=[],$method='post')
	{
		if($method=='post'){
			$res =  $this->curlPost($url,$data);
		}else{
			//后续有get请求在修改
		}

		return json_decode($res,true);
	}

	/**
	 * 获取配置
	 * @param  string $key 配置key
	 * @return mixed
	 */
	private function getConfig($key='')
	{
		if($key)
		{
			return $this->config[$key]?$this->config[$key]:'';
		}

		return $this->config;
	}

	/**
	 * post 请求
	 * @param  string $url 请求地址
	 * @param  array $post_data 提交数据
	 * @param  string $type 提交类型
	 * @param  array $header header
	 * @param  integer $timeout 超时时间
	 * @return string
	 */
	private function curlPost($url,$post_data=[],$type='json',$header=[],$timeout=5) {

	    if($type=='json'){

	    	$post_data = json_encode($post_data);

			if(empty($header))
			{
				$header = [
					'Content-type : application/json; charset=utf-8',
					'Content-Length:' . strlen($post_data)
				];

				if($this->getConfig('password')!='')
				{
					$header[] = "Authorization: " . $this->getConfig('password');
				}
			}
	    }
	    
	    //经过检验 Content-Length超过1024就会失败 后续在拆分

	    // var_dump($post_data);
	    // echo json_encode($header);die;
	    $ch = curl_init();    // 启动一个CURL会话
	    curl_setopt($ch, CURLOPT_URL, $this->getUrl($url));     // 要访问的地址
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // https 不验证证书和hosts
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
	    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
	    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	    //curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
	    curl_setopt($ch, CURLOPT_POST, true); // 发送一个常规的Post请求
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);     // Post提交的数据包
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);     // 设置超时限制防止死循环
	    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	    //curl_setopt($ch, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     // 获取的信息以文件流的形式返回 
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //模拟的header头
	    $result = curl_exec($ch);

	    // echo json_encode(curl_error($ch));
	    // echo  curl_getinfo($ch, CURLINFO_HTTP_CODE); 

	    // 打印请求的header信息
	    // echo json_encode(curl_getinfo($ch));
	    curl_close($ch);
	    return $result;
	}

	/**
	 * 获取服务器地址
	 * @return string
	 */
	private function getBaseUrl()
	{
		return $this->getConfig('url');
	}

	/**
	 * 整理url
	 * @param  string 请求地址
	 * @return [type]
	 */
	private function getUrl($url='')
	{

	    if (strpos($url, "http") === 0) {

	        return $url;

	    } else if (strpos($url, "/") === 0) {

	        return $this->getBaseUrl() . $url;

	    }else{ 

	    	return $this->getBaseUrl() . $url;
	    }
	}


}
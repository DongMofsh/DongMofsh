<?php
header('Access-Control-Allow-Origin: https://www.fengleader.cn');

set_time_limit(0);
// ChatGPT API endpoint
// $url = 'https://api.openai.com/v1/engines/davinci-codex/completions';
$url = 'https://api.openai.com/v1/chat/completions'; //聊天接口
// $url = 'https://api.openai.com/v1/completions';

// 你的 API key
$api_key = '';

// 请求头 headers
$headers = array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key, // 注意有空格
);
 $message =  $_GET['message'];  // Get  无验证
// 参数 Request data
$data = array(
    // 'model' => 'text-davinci-003',
    'model' => 'gpt-3.5-turbo', //聊天模型
    // 'model' => 'text-curie-001',
    'temperature' => 0.8,
    // 'prompt' => '如何用php使用chatgpt的聊天接口', //聊天不用
    'max_tokens' => 3000,
    'messages' => [
        ["role" => "user", "content" => $message],
    ]

);


// Send request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$response = curl_exec($ch);
curl_close($ch);

echo json_encode($response);


// ---------------------------------  单例 Static  --------------------------------------
class ChatGPT {
    private  static  $instance = null;
    private $url = 'https://api.openai.com/v1/chat/completions';
    private $api_key = '';

    // 私有 构造方法 防止类被实例化
    private function __construct() {}

    // 实例化自身
    public  static  function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ChatGPT();
        }
        return self::$instance;
    }

    public function chat($message) {
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->api_key,
        );
        $data = array(
            'model' => 'gpt-3.5-turbo', // 版本
            'temperature' => 0.8,  // 0 - 1  文本丰富度
            'max_tokens' => 3000, // 控制生成的文本长度
            'messages' => [
                ["role" => "user", "content" => $message],  // 信息传参
            ]
        );
      
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}



// 使用示例 验证数据格式 再从单例模式下静态调用
$message = $_GET['message'];


$chatgpt = ChatGPT::getInstance();
$response = $chatgpt->chat($message);
echo json_encode($response);

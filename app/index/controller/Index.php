<?php
namespace app\index\controller;

use app\index\model\Message;

/**
 *
 */
class Index
{
    public function __construct()
    {
        $this->msg = new Message();
    }
    public function index()
    {
        $timestamp = $_GET['timestamp'];
        $nonce     = $_GET['nonce'];
        $tokenn    = "syjs10test";
        $echostr   = isset($_GET['echostr']) ? $_GET['echostr'] : 0;
        $signature = $_GET['signature'];
        $array     = array($timestamp, $nonce, $tokenn);
        sort($array);
        $tmpstr = implode('', $array);
        $tmpstr = sha1($tmpstr);
        if ($tmpstr == $signature && $echostr) {
            //第一次接入微信API
            echo $_GET['echostr'];
            exit;
        } else {
            $this->responseMsg();
        }
    }
    public function getSource($term = '1')
    {
        $output = shell_exec(STATIC_PATH . "python/test.py 150402305 21103X {$term} " . RUNTIME_PATH);
        // passthru(STATIC_PATH . "python/test.py 150402305 21103X {$term} " . RUNTIME_PATH);
        // return implode("|", json_decode($output, true));
        var_dump($output);
    }
    public function test()
    {
        return $this->msg->test();
    }
    public function responseMsg()
    {
        $postObj = $this->msg->getPostMessage();

        // <xml>
        //     <ToUserName>< ![CDATA[toUser] ]></ToUserName>
        //     <FromUserName>< ![CDATA[fromUser] ]></FromUserName>
        //     <CreateTime>1348831860</CreateTime>
        //     <MsgType>< ![CDATA[text] ]></MsgType>
        //     <Content>< ![CDATA[this is a test] ]></Content>
        //     <MsgId>1234567890123456</MsgId>
        // </xml>

        //关注时返回消息
        if (strtolower($postObj->MsgType) == 'event') {
            if (strtolower($postObj->Event) == 'subscribe') {
                echo $this->msg->returnTextMessage('hello');
            }
        }
        //返回消息
        if (strtolower($postObj->MsgType) == 'text') {
            $str    = trim($postObj->Content);
            $strArr = preg_split('/-/', $str);
            switch ($strArr[0]) {
                case 'chengji':
                    echo $this->getSource();
                    break;
                default:
                    echo $this->msg->returnTextMessage('test');
                    break;
            }

            // //返回文字消息
            // if (trim($postObj->Content) == 'test') {
            //     echo $this->msg->returnTextMessage('hello');
            // }

            // //返回图文消息

            // if (trim($postObj->Content) == '1') {
            //     $arr = array(
            //         array(
            //             'title'       => '百度',
            //             'description' => '这是百度页面',
            //             'picUrl'      => 'https://www.baidu.com/img/bd_logo1.png',
            //             'url'         => 'https://www.baidu.com',
            //         ),
            //         array(
            //             'title'       => '腾讯',
            //             'description' => '这是腾讯页面',
            //             'picUrl'      => 'http://mat1.gtimg.com/www/images/qq2012/qqlogofilter1_5.png',
            //             'url'         => 'https://www.qq.com',
            //         ),
            //     );
            //     $toUser   = $postObj->FromUserName;
            //     $fromUser = $postObj->ToUserName;
            //     $time     = time();
            //     $msgType  = 'news';
            //     $count    = count($arr);
            //     $template = "<xml>
            //                     <ToUserName><![CDATA[%s]]></ToUserName>
            //                     <FromUserName><![CDATA[%s]]></FromUserName>
            //                     <CreateTime>%s</CreateTime>
            //                     <MsgType><![CDATA[%s]]></MsgType>
            //                     <ArticleCount>{$count}</ArticleCount>
            //                     <Articles>";
            //     foreach ($arr as $key => $value) {
            //         $template .= "<item>
            //                         <Title><![CDATA[{$value['title']}]]></Title>
            //                         <Description><![CDATA[{$value['description']}]]></Description>
            //                         <PicUrl><![CDATA[{$value['picUrl']}]]></PicUrl>
            //                         <Url><![CDATA[{$value['url']}]]></Url>
            //                       </item>";
            //     }
            //     $template .= "</Articles>
            //                   </xml>";
            //     $info = sprintf($template, $toUser, $fromUser, $time, $msgType);
            //     echo $info;
            // }

        }
    }
}

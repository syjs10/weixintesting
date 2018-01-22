<?php
namespace app\index\controller;

/**
 *
 */
class Index
{

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
    public function responseMsg()
    {
        // 获取Xml格式消息
        $postArr = file_get_contents('php://input');
        //Xml转化成对象
        $postObj = simplexml_load_string($postArr);

        // <xml>
        //     <ToUserName>< ![CDATA[toUser] ]></ToUserName>
        //     <FromUserName>< ![CDATA[fromUser] ]></FromUserName>
        //     <CreateTime>1348831860</CreateTime>
        //     <MsgType>< ![CDATA[text] ]></MsgType>
        //     <Content>< ![CDATA[this is a test] ]></Content>
        //     <MsgId>1234567890123456</MsgId>
        // </xml>
        if (strtolower($postObj->MsgType) == 'event') {
            if (strtolower($postObj->Event) == 'subscribe') {
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  = 'text';
                $content  = '关注测试,登录用户';
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                            </xml>";
                $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;

            }
        }
    }
}

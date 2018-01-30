<?php
namespace app\index\model;

use think\Model;

class Message extends Model
{
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function getPostMessage()
    {
        // 获取Xml格式消息
        $postArr = file_get_contents('php://input');
        //Xml转化成对象
        $this->postObj = simplexml_load_string($postArr);
        return $this->postObj;
    }
    public function returnTextMessage($content = "关注测试")
    {
        $toUser   = $this->postObj->FromUserName;
        $fromUser = $this->postObj->ToUserName;
        $time     = time();
        $msgType  = 'text';
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
    public function test()
    {
        echo 'hello';
    }
}

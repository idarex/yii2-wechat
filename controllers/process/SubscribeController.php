<?php
/**
 * Created by PhpStorm.
 * User: Rocsni
 * Date: 16/3/22
 * Time: 14:10
 */
namespace callmez\wechat\controllers\process;
use Yii;
use callmez\wechat\components\ProcessController;
use common\models\WechatAutoReply;
class SubscribeController extends ProcessController
{
    public function actionIndex()
    {
        $wechatCallmezSubscribe = (new \common\components\wechat\WechatCallmezSubscribe());
        $wechatCallmezSubscribe->message = $this->message;
        $wechatCallmezSubscribe->run();
        $wechatAutoReply = new WechatAutoReply();
        $text = $text = $wechatAutoReply->find()->where(['key_words'=>$this->message['Content']])->one();
        if($text = $wechatAutoReply->find()->where(['key_words'=>$this->message['Content']])->one()){
            return $this->responseText($text['comment']);
        }else{
            return $this->responseText('你打的是啥?');
        }
    }
}
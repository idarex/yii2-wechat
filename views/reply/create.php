<?php

use yii\helpers\Html;
use callmez\wechat\widgets\PagePanel;

$arr = [
    'onSubscribe' => '关注自动回复设置',
    'invalid' => '无效信息自动回复设置',
    'text' => '文本消息设置',
    'image' => '图片回复设置->图片大小不得超过100KB',
    'news' => '图文回复设置->图片大小不得超过100KB',
];

$this->title = $arr[$type];
?>

<?php PagePanel::begin(['options' => ['class' => 'reply-create']]) ?>
<?= $this->render('_form', [
    'model' => $model,
    'type' => $type,
]) ?>

<?php PagePanel::end() ?>


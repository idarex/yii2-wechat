<?php

use yii\helpers\Html;
use callmez\wechat\widgets\PagePanel;

$this->title = '添加回复规则';
?>

<?php PagePanel::begin(['options' => ['class' => 'reply-create']]) ?>
<p>
    <?= Html::a('关注自动回复', ['create', 'reply_type' => 'onSubscribe'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('无效信息自动回复', ['create', 'reply_type' => 'invalid'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('文本回复', ['create', 'reply_type' => 'text'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('图片回复', ['create', 'reply_type' => 'image'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('图文回复', ['create', 'reply_type' => 'news'], ['class' => 'btn btn-success']) ?>
</p>
<?php PagePanel::end() ?>


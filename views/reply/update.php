<?php

use yii\helpers\Html;
use callmez\wechat\widgets\PagePanel;

$this->title = '修改回复规则->图片大小不得超过100kb: ' . $model->key_words;
//$this->params['breadcrumbs'][] = ['label' => $model->key_words, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<?php PagePanel::begin(['options' => ['class' => 'reply-update']]) ?>
    <?= $this->render('_form', [
        'model' => $model,
        'type'=>$type,
    ]) ?>

<?php PagePanel::end() ?>

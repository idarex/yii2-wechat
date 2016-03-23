<?php

use yii\helpers\Html;
use callmez\wechat\widgets\PagePanel;

$this->title = '修改回复规则: ' . $model->key_words;
$this->params['breadcrumbs'][] = ['label' => $model->key_words, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?php PagePanel::begin(['options' => ['class' => 'reply-update']]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

<?php PagePanel::end() ?>

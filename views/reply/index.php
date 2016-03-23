<?php
//use Yii;
use yii\helpers\Html;
use callmez\wechat\models\ReplyRule;
use callmez\wechat\widgets\GridView;
use callmez\wechat\widgets\PagePanel;

$this->title = '回复规则';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php PagePanel::begin(['options' => ['class' => 'reply-index']]) ?>

    <p>
        <?= Html::a('添加回复规则', ['create',], ['class' => 'btn btn-success']) ?>
    </p>

<?= GridView::widget([
    'tableOptions' => ['class' => 'table table-hover'],
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'id',
            'options' => [
                'width' => 75
            ]
        ],
        'key_words',
        'comment',
        'created_at:datetime',
        'updated_at:datetime',
        [
            'class' => 'callmez\wechat\widgets\ActionColumn',
            'template' => '{update} {delete}',
            'options' => [
                'width' => 80
            ]
        ],
    ],
]); ?>

<?php PagePanel::end() ?>
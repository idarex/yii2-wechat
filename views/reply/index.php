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
        <?= Html::a('添加回复规则', ['select',], ['class' => 'btn btn-success']) ?>
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
        [
            'format' => raw,
            'header' => '<a>回复类型</a>',
            'value' => function ($data) {
                if ($data->reply_type == 'text') {
                    $result = '文本回复';
                }
                if ($data->reply_type == 'image') {
                    $result = '图片回复';
                }
                if ($data->reply_type == 'news') {
                    $result = '图文回复';
                }
                if ($data->reply_type == 'invalid') {
                    $result = '无效消息回复';
                }
                if ($data->reply_type == 'onSubscribe') {
                    $result = '关注自动回复';
                }
                return $result;
            }
        ],
        'created_at:datetime',
        'updated_at:datetime',
        [
            'class' => 'callmez\wechat\widgets\ActionColumn',
            'template' => '{update} {delete}',
            'urlCreator' => function ($action, $data, $key, $index) {
                switch ($action) {
                    case 'update':
                        return '/wechat/reply/update?id=' . $data->id . '&reply_type=' . $data->reply_type;
                        break;
                    case 'delete':
                        return '/wechat/reply/delete?id=' . $data->id . '&reply_type=' . $data->reply_type;
                        break;
                }
            },
            'options' => [
                'width' => 80
            ]
        ],
    ],
]); ?>

<?php PagePanel::end() ?>
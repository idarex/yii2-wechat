<?php

use yii\helpers\Html;
use callmez\wechat\widgets\ActiveForm;

?>

<div class="reply-rule-form">

    <?php
    if ($type == 'news') {
        echo Html::button('添加图文信息', ['class' => 'btn btn-success', 'onclick' => 'addform()']);
    }
    ?>
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <?= Html::submitButton($model->isNewRecord ? '创建回复规则' : '修改回复规则', [
                'class' => 'btn btn-block ' . ($model->isNewRecord ? 'btn-success' : 'btn-primary')
            ]) ?>
        </div>
    </div>
    <?php
    if ($type == 'image' || $type == 'news' || $type == 'text') {
        echo $form->field($model, 'key_words')->textInput(['maxlength' => true, 'name' => 'key_words[]']);
    } ?>
    <?= Html::hiddenInput('reply_type', $type) ?>
    <?php

    if ($type == 'image') {
        if (Yii::$app->controller->action->id == 'update') {
            echo $form->field($model, 'image')->fileInput(['name' => 'pic[]']);
            echo $form->field($model, 'image')->textInput(['value' => $model->comment, 'disabled' => true]);
            echo Html::hiddenInput('pic_default[]', $model->comment);
        } else {
            echo $form->field($model, 'image')->fileInput(['name' => 'pic[]']);
        }
    }
    if ($type == 'news') {
        if (Yii::$app->controller->action->id == 'update') {
            foreach (\Qiniu\json_decode($model->comment, true) as $key => $value) {
                echo $form->field($model, 'title')->textInput([
                    'maxlength' => true,
                    'name' => 'title[]',
                    'value' => $value['title']
                ]);
                echo $form->field($model, 'link')->textInput([
                    'maxlength' => true,
                    'name' => 'link[]',
                    'value' => $value['link']
                ]);
                echo $form->field($model, 'image')->fileInput(['name' => 'pic[]']);
                echo $form->field($model, 'image')->textInput(['value' => $value['url'], 'disabled' => true]);
                echo Html::hiddenInput('pic_default[]', $value['url']);
                echo $form->field($model, 'comment')->textarea([
                    'maxlength' => true,
                    'name' => 'data[]',
                    'value' => $value['comment']
                ]);
            }
        } else {
            echo $form->field($model, 'title')->textInput(['maxlength' => true, 'name' => 'title[]']);
            echo $form->field($model, 'link')->textInput(['maxlength' => true, 'name' => 'link[]']);
            echo $form->field($model, 'image')->fileInput(['name' => 'pic[]']);
            echo $form->field($model, 'comment')->textarea(['maxlength' => true, 'name' => 'data[]']);
        }
    }
    if ($type == 'text' || $type == 'invalid' || $type == 'onSubscribe') {
        echo $form->field($model, 'comment')->textarea(['maxlength' => true, 'name' => 'data[]']);
    }
    ?>

    <?php ActiveForm::end(); ?>
    <?php
    $html1 = str_replace(PHP_EOL, '', $form->field($model, 'title')->textInput(['name' => 'pic[]']));
    $html2 = str_replace(PHP_EOL, '', $form->field($model, 'link')->textInput(['name' => 'pic[]']));
    $html3 = str_replace(PHP_EOL, '', $form->field($model, 'image')->fileInput(['name' => 'pic[]']));
    $html4 = str_replace(PHP_EOL, '',
        $form->field($model, 'comment')->textarea(['maxlength' => true, 'name' => 'data[]']));
    $html = $html1 . $html2 . $html3 . $html4;
    ?>
    <script>
        function addform() {
            var form = $('.form-horizontal');
            form.append('<?php echo $html;?>');
        }
    </script>
</div>
<?php
$this->registerJs(<<<EOF
var ruleKeywordNum = 100; // 新建的规则从第100个递增,和已有的规则不冲突(前提是已有的规则不能超过100个)
$(document)
    .on('click', '#addRuleKeyword', function(){
        $(this).before($('#ruleKeywordTemplate').html().replace(/\[\]\[/g, '[' + ruleKeywordNum + ']['));
        ruleKeywordNum++;
    })
    .on('click', '.panel .close', function() {
        if (confirm('确认删除这条关键字么')) {
            $(this).closest('.panel').remove();
        }
    });
EOF
);
?>

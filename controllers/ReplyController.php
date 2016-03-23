<?php

namespace callmez\wechat\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use callmez\wechat\models\ReplyRule;
use callmez\wechat\models\ReplyRuleSearch;
use callmez\wechat\models\ReplyRuleKeyword;
use callmez\wechat\components\AdminController;
use common\models\WechatAutoReply;

/**
 * 模块回复规则控制
 * @package callmez\wechat\controllers
 */
class ReplyController extends AdminController
{
    /**
     * 扩展模块回复列表
     * @return mixed
     * 改前actionIndex($mid)
     */
    public function actionIndex()
    {

        $serchModel = new WechatAutoReply();
        $dataProvider = $serchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['wid'=>$this->getWechat()->id]);
        return $this->render('index',['searchModel'=>$serchModel,'dataProvider'=>$dataProvider]);
    }

    /**
     * Creates a new ReplyRule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model= new WechatAutoReply();
        if ($model->load(Yii::$app->request->post())) {
            $model->wid = $this->getWechat()->id;
            if($model->save()){
                return $this->redirect('index');
            }
        }
        return $this->render('create', [
            'model' => $model,
            'dropDownList'=>$this->dropDownList,
        ]);
    }

    /**
     * Updates an existing ReplyRule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model= new WechatAutoReply();
        $model = $model->findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->wid = $this->getWechat()->id;
            if($model->save()){
                return $this->redirect('index');
            }
        }
        return $this->render('update',['model'=>$model]);
    }

    /**
     * Deletes an existing ReplyRule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = new WechatAutoReply();
        $model = $model->findOne($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * 保存内容
     * @param $rule
     * @param $keyword
     * @param array $keywords
     * @return bool
     */
    protected function save($rule, $keyword, $keywords = [])
    {
        if (!$rule->save()) {
            return false;
        }
        $_keywords = ArrayHelper::index($keywords, 'id');
        $keywords = [];
        $valid = true;
        foreach (Yii::$app->request->post($keyword->formName(), []) as $k => $data) {
            if (!empty($data['id']) && $_keywords[$data['id']]) {
                $_keyword = $_keywords[$data['id']];
                unset($_keywords[$data['id']]);
            } else {
                $_keyword = clone $keyword;
            }
            unset($data['id']);
            $keywords[] = $_keyword;
            $_keyword->setAttributes(array_merge($data, [
                'rid' => $rule->id
            ]));
            $valid = $valid && $_keyword->save();
        }
        !empty($_keywords) && ReplyRuleKeyword::deleteAll(['id' => array_keys($_keywords)]); // 无更新的则删除
        return $valid;
    }

    /**
     * Finds the ReplyRule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReplyRule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReplyRule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

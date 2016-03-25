<?php

namespace callmez\wechat\controllers;

use common\components\Qiniu;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use callmez\wechat\models\ReplyRule;
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
        $dataProvider->query->andWhere(['wid' => $this->getWechat()->id]);
        return $this->render('index', ['searchModel' => $serchModel, 'dataProvider' => $dataProvider]);
    }

    /**
     *选择
     *
     */
    public function actionSelect()
    {
        return $this->render('select');
    }

    /**
     * Creates a new ReplyRule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $type = Yii::$app->request->get('reply_type');
        $model = new WechatAutoReply();
        if ($request = Yii::$app->request->post()) {
            $model->wid = $this->getWechat()->id;
            if ($type == 'invalid') {
                $model->key_words = 'invalid';
            } elseif ($type == 'onSubscribe') {
                $model->key_words = 'onSubscribe';
            } else {
                $model->key_words = $request['key_words'][0];
            }

            $model->reply_type = $request['reply_type'];
            if ($request['reply_type'] == 'image') {
                $url = $this->fileupload($_FILES);
                $model->comment = json_encode($url);
            } elseif ($request['reply_type'] == 'news') {
                $url = $this->fileUpload($_FILES);
                $result = [];
                foreach ($request['data'] as $key => $data) {
                    $result[$key]['comment'] = $data;
                    $result[$key]['url'] = $url[$key];
                    $result[$key]['title'] = $request['title'][$key];
                    $result[$key]['link'] = $request['link'][$key];
                    //$result[]['link']= $link[$key];
                }
                $model->comment = json_encode($result);
            } else {
                $model->comment = json_encode($request['data'][0]);
            }
            if ($model->save()) {
                return $this->redirect('index');
            }
        }
        return $this->render('create', [
            'model' => $model,
            'type' => $type,
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
        $type = Yii::$app->request->get('reply_type');
        $model = new WechatAutoReply();
        $model = $model->findOne($id);
        $data = \Qiniu\json_decode($model->comment, true);
        if ($type == 'image') {
            $model->comment = $data[0];
        }
        if ($request = Yii::$app->request->post()) {
            $model->wid = $this->getWechat()->id;
            $model->key_words = $request['key_words'][0];
            $model->reply_type = $request['reply_type'];
            if ($request['reply_type'] == 'image') {
                $url = $this->fileUpload($_FILES);
                if (!$url) {
                    $url = $request['pic_default'];
                }
                $model->comment = json_encode($url);
            } elseif ($request['reply_type'] == 'news') {
                $url = $this->fileUpload($_FILES);
                if (!$url) {
                    $url = $request['pic_default'];
                }
                $result = [];
                foreach ($request['data'] as $key => $data) {
                    $result[$key]['comment'] = $data;
                    $result[$key]['url'] = $url[$key];
                    $result[$key]['title'] = $request['title'][$key];
                    $result[$key]['link'] = $request['link'][$key];
                }
                $model->comment = json_encode($result);
            } else {
                $model->comment = json_encode($request['data'][0]);
            }
            if ($model->save()) {
                return $this->redirect('index');
            }
        }
        return $this->render('update', ['model' => $model, 'type' => $type]);
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

    protected function fileUpload($file)
    {
        if (is_uploaded_file($file['pic']['tmp_name'][0])) {
            $result = [];
            foreach ($file['pic']['tmp_name'] as $key => $value) {
                $name = $file['pic']['name'][$key];
                $type = $file['pic']['type'][$key];
                $size = $file['pic']['size'][$key];
                $tmp_name = $file['pic']['tmp_name'][$key];
                $okType = false;
                switch ($type) {
                    case 'image/pjpeg':
                        $okType = true;
                        break;
                    case 'image/jpeg':
                        $okType = true;
                        break;
                    case 'image/gif':
                        $okType = true;
                        break;
                    case 'image/png':
                        $okType = true;
                        break;
                }
                if ($okType) {
                    $error = $file['pic']['error'][$key];
                    $data = Yii::$app->qiniu->uploadFile($tmp_name);
                    $result[] = Qiniu::ACCESS_DOMAIN . '/' . $data['key'];
                }
            }
            return $result;
        } else {
            return false;
        }
    }
}

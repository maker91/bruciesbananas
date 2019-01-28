<?php

namespace app\controllers;

use Yii;
use app\models\Pack;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\filters\VerbFilter;

/**
 * PackController implements the CRUD actions for Pack model.
 */
class PackController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Pack models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Pack();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $query = Pack::find();
        $data_provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'size' => SORT_DESC
                ]
            ]
        ]);

        return $this->render('index', [
            'data_provider' => $data_provider,
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Pack model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ServerErrorHttpException
     */
    public function actionDelete($id)
    {
        $model = Pack::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException('Pack Size does not exist');
        }

        try {
            $model->delete();
        } catch (\Throwable $e) {
            throw new ServerErrorHttpException('Failed to delete Pack Size');
        }

        return $this->redirect(['index']);
    }

}

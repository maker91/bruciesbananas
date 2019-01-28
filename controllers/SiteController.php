<?php

namespace app\controllers;

use app\models\Order;
use app\models\Pack;
use Yii;
use yii\web\Controller;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $order = new Order();

        if ($order->load(Yii::$app->request->post()) && $order->validate()) {

            // get all available packs and calculate how many of each pack fulfills the order quantity
            $available_packs = Pack::find()->orderBy('size desc')->all();
            $packs = $order->calculatePackSizes($available_packs);

            return $this->render('index', [
                'model' => $order,
                'packs' => $packs,
            ]);
        }

        return $this->render('index', ['model' => $order]);
    }
}

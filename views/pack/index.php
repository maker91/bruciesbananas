<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
?>

<div class="container-fluid">
    <div class="row top-buffer-s">
        <div class="col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-4 text-center">
            <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'size') ?>
                <div class="form-group">
                    <?= Html::submitButton('Add New Pack', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="row top-buffer-l">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2">
            <?= GridView::widget([
                'dataProvider' => $data_provider,
                'columns' => [
                    'size:integer:Pack Size',
                    [
                        'class' => yii\grid\ActionColumn::className(),
                        'visibleButtons' => [
                            'update' => false,
                            'view' => false
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Banana Packer';
?>

<div class="container-fluid">
    <div class="row top-buffer-s">
        <div class="col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-4 text-center">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'quantity') ?>

            <div class="form-group">
                <?= Html::submitButton('Calculate Pack Sizes', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php
    if (isset($packs)) {
        $order_quantity = $model->quantity;
        $total_packs = $packs['total_packs'];
        $total_bananas = $packs['total_bananas'];
        $surplus = $total_bananas - $order_quantity;
        $pack_sizes = $packs["packs"];
    } else {
        $order_quantity = '-';
        $total_packs = '-';
        $total_bananas = '-';
        $surplus = '-';
        $pack_sizes = [
            '-' => '-',
        ];
    }
    ?>

    <div class="row top-buffer-l">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2"">
            <div class="stat-grid">
                <div class="stat-box">
                    Number of bananas ordered
                    <span class="stat-value"><?= Html::encode($order_quantity) ?></span>
                </div>
                <div class="stat-box">
                    Number of packs to send
                    <span class="stat-value"><?= Html::encode($total_packs) ?></span>
                </div>
                <div class="stat-box">
                    Number of bananas to send
                    <span class="stat-value"><?= Html::encode($total_bananas) ?></span>
                </div>
                <div class="stat-box">
                    Number of bananas surplus
                    <span class="stat-value"><?= Html::encode($surplus) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row top-buffer-s">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2">
            <p>Packs required to fulfill order: </p>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Pack Size</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pack_sizes as $pack_size => $quantity) {?>
                        <tr>
                            <td><?= Html::encode($pack_size) ?></td>
                            <td><?= Html::encode($quantity) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

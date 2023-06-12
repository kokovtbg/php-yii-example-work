<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Order $order */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\OrderProduct[] $orderProducts */
/** @var integer $productCount */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(['id' => 'form-order-update']); ?>

    <?= $form->field($order, 'name')->textInput(['maxlength' => true]) ?>

    <input id="productCount" value="<?= $productCount?>" disabled/>

    <input id="order-id" value="<?= $order->id?>" disabled/>

    <select style="width: 30%" id="select-product" >

    </select>

    <div id="product-orders">
        <?php foreach ($orderProducts as $index => $value) {
            echo $this->renderAjax('add-product', [
                'model' => $value,
                'productCount' => $index + 1
            ]);
        } ?>
    </div>

    <div id="products-to-buy">

    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id' => 'submit-order']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
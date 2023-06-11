<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Order $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(['id' => 'form-order']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <input id="productCount" value="1" hidden />
    <select style="width: 30%" id="select-product" >

    </select>

    <div id="products-to-buy">

    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id' => 'submit-order']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
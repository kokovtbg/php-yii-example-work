<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrderProduct $model */
/** @var string $productCount */
?>

<div>
    <?= Html::activeTextInput($model, '[' . $productCount . ']product_id', ['class' => 'form-control', 'hidden' => 'true']) ?>
    <?= Html::activeTextInput($model, '[' . $productCount . ']name_product', ['class' => 'form-control', 'disabled' => 'true']) ?>
    <?= Html::activeTextInput($model, '[' . $productCount . ']price', ['class' => 'form-control']) ?>
    <?= Html::button('Delete', ['class' => 'btn btn-danger btn-product-delete']) ?>
</div>
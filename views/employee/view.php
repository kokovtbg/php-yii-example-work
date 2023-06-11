<?php

use app\models\Department;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Employee $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="employee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="container-image" >
        <?= join('', array_map(function ($e) {
            return "<div class='div-image'><button class='btn btn-danger btn-img' type='submit' >Delete</button><img src=$e->path /></div>";
        }, $model->images)) ?>

    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'first_name',
            'last_name',
            'id_number',
            [
                'label' => 'Image',
                'attribute' => 'image_path',
                'value' => $model->image_path,
                'format' => ['image', ['width' => '100', 'height' => '100']]
            ],
            [
                'label' => 'Department Name',
                'value' => Department::findOne($model->department_id)->getAttribute('name')
            ],
            [
                'label' => 'Foods',
                'value' => join(',', ArrayHelper::map($model->foods, 'id', 'name')) 
            ]
        ],
    ]) ?>

</div>

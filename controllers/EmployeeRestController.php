<?php

namespace app\controllers;
use app\models\Employee;
use Yii;
use yii\rest\ActiveController;

class EmployeeRestController extends ActiveController {
    public $modelClass = 'app\models\Employee';

    public function actions(){
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }

    protected function verbs(){
        return [
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH','POST'],
            'delete' => ['delete'],
            'view' => ['GET'],
            'index'=>['GET'],
        ];
    }

    public function actionIndex() {
        return Employee::find()->all();
    }

    public function actionView($id) {
        return Employee::find()->where(['id' => $id])->one();
    }

    public function actionCreate() {
        $model = new Employee();

        return $model->setImageAndSaveRest();
    }

    public function actionUpdate($id) {
        $model = Employee::find()->where(['id' => $id])->one();

        return $model->setImageAndSaveRest();
    }

    public function actionDelete($id) {
        $model = Employee::find()->where(['id' => $id])->one();
        $modelView = $model;
        $model->delete();
        return $modelView;
    }

    public function actionSearch() {
        $firstName = Yii::$app->request->getBodyParam('firstName');
        $lastName = Yii::$app->request->getBodyParam('lastName');
        $idNumber = Yii::$app->request->getBodyParam('idNumber');
        $departmentName = Yii::$app->request->getBodyParam('departmentName');
        $foods = Yii::$app->request->getBodyParam('foods');

        return Employee::search($firstName, $lastName, $idNumber, $departmentName, $foods);
        
    }

}
<?php

namespace app\controllers;

use app\models\Department;
use app\models\Employee;
use app\models\EmployeeSearch;
use app\models\Food;
use app\models\QueryJob;
use app\models\EmployeeTest;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;


/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Employee models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'departments' => Department::getAllDepartments()
        ]);
    }

    /**
     * Displays a single Employee model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Employee();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->setImageAndSave();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'departments' => Department::getAllDepartments(),
            'foods' => Food::getAllFoods()
        ]);
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $selected_foods = array(); 
        $foods = ArrayHelper::map($model->foods, 'id', 'name');
        foreach ($foods as $key => $value) {
            array_push($selected_foods, $key);
        }
        $model->selected_foods = $selected_foods;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->setImageAndSave();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'departments' => Department::getAllDepartments(),
            'foods' => Food::getAllFoods()
        ]);
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetAll() {
        $employees = Employee::find()->all();
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $response = new \stdClass();
        // $employee1 = new EmployeeTest();
        // $employee1->id = 1;
        // $employee1->text = 'Ivan';
        // $employee2 = new EmployeeTest();
        // $employee2->id = 2;
        // $employee2->text = 'Petar';
        $arr = array_map(function($e) {
            $employeeTest = new EmployeeTest();
            $employeeTest->id = $e->id;
            $employeeTest->text = $e->first_name . ' ' . $e->last_name;
            $employeeTest->idNumber = $e->id_number;
            return $employeeTest;
        }, $employees);
        $response->results = $arr;
        return $response;
    }

    public function actionTest() {
        return $this->render('test');
    }
}

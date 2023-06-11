<?php

namespace app\controllers;

use app\models\Order;
use app\models\OrderProduct;
use app\models\Product;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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
     * Lists all Order models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Order model.
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
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreateOrder($productCount = null) {
        $model = new Order();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->validate()) {
                    if ($productCount > 1) {

                        $ordersValid = true;
                        $ordersProducts = array();

                        for ($i=1; $i < $productCount; $i++) { 
                            $productOrder = new OrderProduct();
                            $productOrder->attributes = $_POST['OrderProduct'][$i];

                            if (!$productOrder->validate()) {
                                $ordersValid = false;
                                break;
                            }
                            array_push($ordersProducts, $productOrder);
                        }

                        if ($ordersValid) {
                            $model->save();
                            foreach ($ordersProducts as $orderProduct) {
                                $orderProduct->order_id = $model->id;
                                $orderProduct->save();
                            }
                            $ordersProducts = array_map(function ($e) {
                                return "Product $e->name_product with price $e->price";
                            }, $ordersProducts);
                            return $this->asJson("Order with name $model->name created! " . join(',', $ordersProducts));
                        }

                        throw new BadRequestHttpException("Products must be valid");
                    }
                    throw new BadRequestHttpException("You must select at least one product");
                }  

                throw new BadRequestHttpException('Must select name');
            }
            
        }

        return $this->render('order-create', [
            'model' => $model
        ]);
    }

    public function actionAddProduct($id, $productsCount) {
        $product = Product::find()->where(['id' => $id])->one();
        $model = new OrderProduct();
        $model->product_id = $product->id;
        $model->name_product = $product->name;
        $model->price = $product->price;
        return $this->renderAjax('add-product', [
            'model' => $model,
            'productCount' => $productsCount
        ]);
    }
}

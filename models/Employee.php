<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "employee".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $id_number
 * @property string|null $image_path
 * @property int $department_id
 *
 * @property Department $department
 * @property EmployeeFood[] $employeeFoods
 * @property Food[] $foods
 * @property Image[] $images
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;
    public $selected_foods;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'department_id', 'id_number'], 'required'],
            [['department_id'], 'integer'],
            [['first_name', 'last_name', 'image_path'], 'string', 'max' => 255],
            [['id_number'], 'string', 'length' => 10],
            [['id_number'], 'integer'],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['department_id' => 'id']],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 0],
            [['selected_foods'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'id_number' => 'Id Number',
            'image_path' => 'Image Path',
            'department_id' => 'Department ID',
        ];
    }

    /**
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'department_id']);
    }

    /**
     * Gets query for [[EmployeeFoods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeFoods()
    {
        return $this->hasMany(EmployeeFood::class, ['employee_id' => 'id']);
    }

    /**
     * Gets query for [[Foods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFoods()
    {
        return $this->hasMany(Food::class, ['id' => 'food_id'])->viaTable('employee_food', ['employee_id' => 'id']);
    }

    /**
     * Gets query for [[Images]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::class, ['employee_id' => 'id']);
    }

    public function setImageAndSave()
    {
        $this->imageFiles = UploadedFile::getInstances($this, 'imageFiles');
        // $this->image_path = 'uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;

        $this->save();

        foreach ($this->imageFiles as $file) {
            
            $image = new Image();
            $image->path = 'uploads/' . $file->baseName . '.' . $file->extension;
            $image->employee_id = $this->id;
            $image->save();

            $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
        }
        // $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);

        foreach ($this->foods as $value) {
            $employee_food = EmployeeFood::find()
                ->where(['food_id' => $value->id])
                ->andWhere(['employee_id' => $this->id])
                ->one();
            $employee_food->delete();
        }
        if ($this->selected_foods) {
            foreach ($this->selected_foods as $value) {
                $employee_food = new EmployeeFood();
                $employee_food->employee_id = $this->id;
                $employee_food->food_id = $value;
                $employee_food->save();
            }
        }
    }

    // public function setImageAndSaveRest()
    // {
    //     $this->imageFile = UploadedFile::getInstanceByName('imageFile');
    //     $this->image_path = 'uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;

    //     $this->first_name = Yii::$app->request->getBodyParam('firstName');
    //     $this->last_name = Yii::$app->request->getBodyParam('lastName');
    //     $this->id_number = Yii::$app->request->getBodyParam('idNumber');
    //     $this->department_id = Yii::$app->request->getBodyParam('departmentId');
    //     $this->save();

    //     $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);

    //     foreach ($this->foods as $value) {
    //         $employee_food = EmployeeFood::find()
    //             ->where(['food_id' => $value->id])
    //             ->andWhere(['employee_id' => $this->id])
    //             ->one();
    //         $employee_food->delete();
    //     }
    //     $this->selected_foods = Yii::$app->request->getBodyParam('selectedFoods');
    //     if ($this->selected_foods) {
    //         foreach ($this->selected_foods as $value) {
    //             $employee_food = new EmployeeFood();
    //             $employee_food->employee_id = $this->id;
    //             $employee_food->food_id = $value;
    //             $employee_food->save();
    //         }
    //     }
    //     return $this;
    // }

    // public static function search($firstName, $lastName, $idNumber, $departmentName, $foods)
    // {
    //     return Employee::find()->joinWith('department')->joinWith('foods')
    //         ->where($firstName ? ['first_name' => $firstName] : [])
    //         ->andWhere($lastName ? ['last_name' => $lastName] : [])
    //         ->andWhere($idNumber ? ['id_number' => $idNumber] : [])
    //         ->andWhere($departmentName ? ['department.name' => $departmentName] : [])
    //         ->andWhere($foods ? ['food.name' => $foods] : [])
    //         ->all();
        
    // }
}
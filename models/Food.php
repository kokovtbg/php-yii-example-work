<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "food".
 *
 * @property int $id
 * @property string $name
 *
 * @property EmployeeFood[] $employeeFoods
 * @property Employee[] $employees
 */
class Food extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'food';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[EmployeeFoods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeFoods()
    {
        return $this->hasMany(EmployeeFood::class, ['food_id' => 'id']);
    }

    /**
     * Gets query for [[Employees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::class, ['id' => 'employee_id'])->viaTable('employee_food', ['food_id' => 'id']);
    }

    public static function getAllFoods() {
        return ArrayHelper::map(Food::find()->all(), 'id', 'name');
    }
}

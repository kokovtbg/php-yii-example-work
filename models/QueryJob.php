<?php

namespace app\models;

use Yii;
use yii\base\BaseObject;

class QueryJob extends BaseObject implements \yii\queue\JobInterface
{
    public function execute($queue)
    {
        return Yii::$app->db->createCommand()
            ->update('employee', ['first_name' => 'Petar'], 'id = 1')
            ->execute();
    }
}
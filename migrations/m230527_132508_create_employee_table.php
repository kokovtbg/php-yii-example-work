<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee}}`.
 */
class m230527_132508_create_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'id_number' => $this->string(10),
            'image_path' => $this->string(),
            'department_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey(
            'fk-employee-department',
            'employee',
            'department_id',
            'department',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-employee-department', 'employee');
        $this->dropTable('{{%employee}}');
    }
}
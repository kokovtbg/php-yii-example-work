<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%image}}`.
 */
class m230605_162707_create_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'path' => $this->string()->notNull(),
            'employee_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('fk-image-employee', 'image', 'employee_id', 'employee', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-image-employee', 'image');
        $this->dropTable('{{%image}}');
    }
}

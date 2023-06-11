<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_food}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%employee}}`
 * - `{{%food}}`
 */
class m230527_134049_create_junction_table_for_employee_and_food_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_food}}', [
            'employee_id' => $this->integer(),
            'food_id' => $this->integer(),
            'PRIMARY KEY(employee_id, food_id)',
        ]);

        // creates index for column `employee_id`
        $this->createIndex(
            '{{%idx-employee_food-employee_id}}',
            '{{%employee_food}}',
            'employee_id'
        );

        // add foreign key for table `{{%employee}}`
        $this->addForeignKey(
            '{{%fk-employee_food-employee_id}}',
            '{{%employee_food}}',
            'employee_id',
            '{{%employee}}',
            'id',
            'CASCADE'
        );

        // creates index for column `food_id`
        $this->createIndex(
            '{{%idx-employee_food-food_id}}',
            '{{%employee_food}}',
            'food_id'
        );

        // add foreign key for table `{{%food}}`
        $this->addForeignKey(
            '{{%fk-employee_food-food_id}}',
            '{{%employee_food}}',
            'food_id',
            '{{%food}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%employee}}`
        $this->dropForeignKey(
            '{{%fk-employee_food-employee_id}}',
            '{{%employee_food}}'
        );

        // drops index for column `employee_id`
        $this->dropIndex(
            '{{%idx-employee_food-employee_id}}',
            '{{%employee_food}}'
        );

        // drops foreign key for table `{{%food}}`
        $this->dropForeignKey(
            '{{%fk-employee_food-food_id}}',
            '{{%employee_food}}'
        );

        // drops index for column `food_id`
        $this->dropIndex(
            '{{%idx-employee_food-food_id}}',
            '{{%employee_food}}'
        );

        $this->dropTable('{{%employee_food}}');
    }
}

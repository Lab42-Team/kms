<?php

use yii\db\Migration;

/**
 * Class m210907_143230_transition_property
 */
class m210907_143230_transition_property extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%transition_property}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'operator' => $this->smallInteger()->notNull()->defaultValue(0),
            'value' => $this->text(),
            'transition' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("transition_property_state_fk", "{{%transition_property}}",
            "transition", "{{%transition}}", "id", 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%transition_property}}');
    }
}
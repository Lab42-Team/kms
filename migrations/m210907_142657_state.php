<?php

use yii\db\Migration;

/**
 * Class m210907_142657_state
 */
class m210907_142657_state extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%state}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'type' => $this->smallInteger()->notNull()->defaultValue(0),
            'description' => $this->text(),
            'indent_x' => $this->integer(),
            'indent_y' => $this->integer(),
            'diagram' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("state_diagram_fk", "{{%state}}", "diagram",
            "{{%diagram}}", "id", 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%state}}');
    }
}
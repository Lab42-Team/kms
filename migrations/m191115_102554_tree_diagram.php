<?php

use yii\db\Migration;

/**
 * Class m191115_102554_tree_diagram
 */
class m191115_102554_tree_diagram extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%tree_diagram}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'mode' => $this->smallInteger()->notNull()->defaultValue(0),
            'tree_view' => $this->smallInteger()->notNull()->defaultValue(0),
            'diagram' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("tree_diagram_diagram_fk", "{{%tree_diagram}}", "diagram",
            "{{%diagram}}", "id", 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%tree_diagram}}');
    }
}
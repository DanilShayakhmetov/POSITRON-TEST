<?php

use yii\db\Migration;

/**
 * Class m201105_231627_book
 */
class m201105_231627_create_book extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->unique(),
            'isbn' => $this->smallInteger()->notNull()->unique(),
            'pageCount' => $this->smallInteger(),
            'publishedDate' => $this->dateTime(),
            'thumbnailUrl' => $this->string(),
            'shortDescription' => $this->string(),
            'longDescription' => $this->text(),
            'status' => $this->string(),
            'authors' => $this->string(),
            'categories' => $this->string(),

        ], $tableOptions);
    }


    public function safeDown()
    {
        $this->dropTable('{{%book}}');
    }
}

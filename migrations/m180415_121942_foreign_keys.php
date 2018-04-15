<?php

use yii\db\Migration;

/**
 * Class m180415_121942_foreign_keys
 */
class m180415_121942_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        \Yii::$app->db->createCommand()->addForeignKey( 'fk_note_user', 'note', 'creator_id', 'user', 'id' )->execute();
        \Yii::$app->db->createCommand()->addForeignKey( 'fk_access_note', 'access', 'note_id', 'note', 'id' )->execute();
        \Yii::$app->db->createCommand()->addForeignKey( 'fk_access_user', 'access', 'user_id', 'user', 'id' )->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        \Yii::$app->db->createCommand()->dropForeignKey( 'fk_note_user', 'note' )->execute();
        \Yii::$app->db->createCommand()->dropForeignKey( 'fk_access_note', 'access' )->execute();
        \Yii::$app->db->createCommand()->dropForeignKey( 'fk_access_user', 'access' )->execute();

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180415_121942_foreign_keys cannot be reverted.\n";

        return false;
    }
    */
}

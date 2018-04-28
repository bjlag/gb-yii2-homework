<?php

namespace app\models;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * This is the model class for table "access".
 *
 * @property int $id
 * @property int $note_id
 * @property int $user_id
 *
 * @property Note $note
 * @property User $user
 */
class Access extends \yii\db\ActiveRecord
{
    const RELATION_USER = 'user';
    const RELATION_NOTE = 'note';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'access';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['note_id', 'user_id'], 'required'],
            [['note_id', 'user_id'], 'integer'],
            [['note_id'], 'exist', 'skipOnError' => true, 'targetClass' => Note::className(), 'targetAttribute' => ['note_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'note_id' => 'Заметка',
            'user_id' => 'Пользователь',
        ];
    }

    public function beforeSave( $insert )
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $modelAccess = Access::findOne( [ 'note_id' => $this->note_id, 'user_id' => $this->user_id ] );
        if ( $modelAccess !== null ) {
            throw new ForbiddenHttpException( 'Пользователю доступ уже предоставлен' );
        }

        return true;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNote()
    {
        return $this->hasOne(Note::className(), ['id' => 'note_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\AccessQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\AccessQuery(get_called_class());
    }
}

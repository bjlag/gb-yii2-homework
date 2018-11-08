<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "note".
 *
 * @property int $id
 * @property string $text
 * @property int $creator_id
 * @property int $created_at
 *
 * @property Access[] $accesses
 * @property User $creator
 *
 * @mixin TimestampBehavior
 */
class Note extends \yii\db\ActiveRecord
{
    const RELATION_CREATOR = 'creator';
    const RELATION_ACCESSES = 'accesses';
    const RELATION_ACCESSES_USERS = 'accessUsers';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'note';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'creator_id'], 'required'],
            [['text'], 'string'],
            [['creator_id', 'created_at'], 'integer'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Текст заметки',
            'creator_id' => 'Creator ID',
            'created_at' => 'Дата создания',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesses()
    {
        return $this->hasMany(Access::className(), ['note_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->via(self::RELATION_ACCESSES);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\NoteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\NoteQuery(get_called_class());
    }

    /**
     * Проверка что заметка принадлежит пользователю
     * @param integer $userId
     * @return bool TRUE заметка принадлежит пользователю, FALSE иначе
     */
    public function isCreator( $userId )
    {
        return $this->creator_id && $this->creator_id == $userId;
    }

    /**
     * Проверяем, есть ли доступ к заметке.
     * @param integer $userId
     * @return bool TRUE доступ есть, FALSE иначе
     */
    public function isAccessedForUser( $userId )
    {
        return $this->getAccesses()->where( [ 'user_id' => $userId ] )->exists();
    }
}

<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Note]].
 *
 * @see \app\models\Note
 */
class NoteQuery extends \yii\db\ActiveQuery
{
    public function byCreator( $userId )
    {
        return $this->andWhere( [ 'creator_id' => $userId ] );
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Note[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Note|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

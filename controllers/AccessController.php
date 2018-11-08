<?php

namespace app\controllers;

use app\models\Note;
use app\models\User;
use Yii;
use app\models\Access;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccessController implements the CRUD actions for Access model.
 */
class AccessController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'unshared-all' => ['POST'],
                    'unshared' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Предоставить доступ к указанной заметке. Кому предоставить доступ выбирается в форме.
     * @param $noteId
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate( $noteId )
    {
        $modelNote = Note::findOne( $noteId );
        if ( !$modelNote->isCreator( Yii::$app->user->getId() ) ) {
            throw new ForbiddenHttpException( 'Нет доступа' );
        }

        $model = new Access();
        $model->note_id = $noteId;

        $users = User::find()->select( [ "trim( concat( ifnull( name, '' ), ' ', ifnull( surname, '' ) ) )" ] )->indexBy( 'id' )
            ->exceptUser( Yii::$app->user->getId() )->column();

        try {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash( 'success', "Предоставлен доступ к заметке $noteId пользователю {$model->user_id}" );
                return $this->redirect( ['note/shared'] );
            }
        } catch ( ForbiddenHttpException $e ) {
            Yii::$app->session->setFlash( 'error', $e->getMessage() );
            return $this->redirect( ['access/create', 'noteId' => $noteId ] );
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users
        ]);
    }

    /**
     * Удалить доступ всем пользователям к указанной заметке.
     * @param $noteId
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionUnsharedAll( $noteId )
    {
        $modelNote = Note::findOne( $noteId );
        if ( !$modelNote->isCreator( Yii::$app->user->getId() ) ) {
            throw new ForbiddenHttpException( 'Нет доступа' );
        }

        $modelNote->unlinkAll( Note::RELATION_ACCESSES_USERS, true );
        Yii::$app->session->setFlash( 'success', "Для всех пользователей удален доступ к заметке {$noteId}" );

        return $this->redirect( [ 'note/shared' ] );
    }

    /**
     * Удалить указанный доступ.
     * @param integer $accessId
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUnshared( $accessId )
    {
        $model = Access::findOne( $accessId );
        if ( !$model->note->isCreator( Yii::$app->user->getId() ) ) {
            throw new ForbiddenHttpException( 'Нет доступа' );
        }

        $model->delete();
        Yii::$app->session->setFlash( 'success', 'Доступ к заметке удален' );

        return $this->redirect( [ 'note/view', 'id' => $model->note_id ] );
    }

    /**
     * Finds the Access model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Access the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Access::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

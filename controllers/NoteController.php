<?php

namespace app\controllers;

use app\models\Access;
use app\models\User;
use Yii;
use app\models\Note;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NoteController implements the CRUD actions for Note model.
 */
class NoteController extends Controller
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
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Note models.
     * @return mixed
     */
    public function actionMy()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Note::find()->byCreator( Yii::$app->user->getId() ),
        ]);

        return $this->render('my', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Список расшаренных заметок.
     * @return mixed
     */
    public function actionShared()
    {
        $query = Note::find()->innerJoinWith( Note::RELATION_ACCESSES )->byCreator( Yii::$app->user->getId() );

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $this->render('shared', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Доступ к чужим заметкам.
     */
    public function actionAccessed()
    {
        $query = Note::find()->innerJoinWith( [ Note::RELATION_ACCESSES, Note::RELATION_CREATOR . ' c' ] )
            ->where( [ 'user_id' => Yii::$app->user->getId() ] );

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $dataProvider->sort->attributes = array_merge(
            $dataProvider->sort->attributes,
            [
                'creator.name' => [
                    'asc' => [ 'c.name' => SORT_ASC ],
                    'desc' => [ 'c.name' => SORT_DESC ],
                    'default' => SORT_DESC,
                    'label' => 'Автор'
                ]
            ]
        );

        return $this->render('accessed', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Note model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ( !$model->isCreator( Yii::$app->user->getId() )
            && !$model->isAccessedForUser( Yii::$app->user->getId() ) ) {
            throw new ForbiddenHttpException( 'Нет доступа' );
        }

        $queryAccesses = Access::find()->innerJoinWith( Access::RELATION_USER . ' u' )->where( [ 'note_id' => $id ] );
        $dataProviderAccesses = new ActiveDataProvider([
            'query' => $queryAccesses,
        ]);

        $dataProviderAccesses->sort->attributes = array_merge(
            $dataProviderAccesses->sort->attributes,
            [
                'user.username' => [
                    'asc' => [ 'u.username' => SORT_ASC ],
                    'desc' => [ 'u.username' => SORT_DESC ],
                    'default' => SORT_DESC,
                    'label' => 'Логин'
                ]
            ],
            [
                'user.name' => [
                    'asc' => [ 'u.name' => SORT_ASC ],
                    'desc' => [ 'u.name' => SORT_DESC ],
                    'default' => SORT_DESC,
                    'label' => 'Имя'
                ]
            ],
            [
                'user.surname' => [
                    'asc' => [ 'u.surname' => SORT_ASC ],
                    'desc' => [ 'u.surname' => SORT_DESC ],
                    'default' => SORT_DESC,
                    'label' => 'Фамилия'
                ]
            ]
        );

        return $this->render('view', [
            'model' => $model,
            'dataProviderAccesses' => $dataProviderAccesses
        ]);
    }

    /**
     * Creates a new Note model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Note();
        $model->creator_id = Yii::$app->user->getId();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash( 'success', 'Заметка успешно создана' );
            return $this->redirect( [ 'my' ] );
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Note model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ( !$model->isCreator( Yii::$app->user->getId() ) ) {
            throw new ForbiddenHttpException( 'Нет доступа' );
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash( 'success', "Заметка $id успешно обновлена" );
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Note model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ( !$model->isCreator( Yii::$app->user->getId() ) ) {
            throw new ForbiddenHttpException( 'Нет доступа' );
        }

        if ( !$model->accesses ) {
            $model->delete();
            Yii::$app->session->setFlash( 'success', "Заметка $id успешно удалена" );
        } else {
            Yii::$app->session->setFlash( 'error', "Заметка расшарина для доступа другим пользователям! 
                    Удалите все доступы и повторите попытку" );

            return $this->redirect( [ 'view', 'id' => $id ] );
        }

        return $this->redirect(['my']);
    }

    /**
     * Finds the Note model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Note the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Note::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

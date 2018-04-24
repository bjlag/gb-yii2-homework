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
                ],
            ],
        ];
    }

    /**
     * Lists all Access models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Access::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Access model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Access model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $noteId
     * @return mixed
     */
    public function actionCreate( $noteId )
    {
        $model = new Access();
        $model->note_id = $noteId;

        $modelNote = Note::findOne( $noteId );
        if ( !$modelNote || $modelNote->creator_id != Yii::$app->user->getId() ) {
            throw new ForbiddenHttpException( 'Нет доступа' );
        }

        $users = User::find()->select( [ "trim( concat( ifnull( name, '' ), ' ', ifnull( surname, '' ) ) )" ] )->indexBy( 'id' )
            ->exceptUser( Yii::$app->user->getId() )->column();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash( 'success', "Предоставлен доступ к заметке $noteId пользователю {$model->user_id}" );
            return $this->redirect( ['note/my'] );
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users
        ]);
    }

    /**
     * Updates an existing Access model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Access model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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

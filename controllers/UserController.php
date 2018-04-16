<?php

namespace app\controllers;

use app\models\Access;
use app\models\Note;
use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
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
     * Deletes an existing User model.
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

    public function actionTest()
    {
        // Создаем запись в таблице user
        $modelUser = new User( [
            'username' => 'test',
            'name' => 'Test user',
            'password_hash' => uniqid()
        ] );

        $modelUser->save();

        // Создаем запись в таблице note
        $modelNote = new Note( [
            'text' => 'Текст новой заметки',
            'created_at' => time()
        ] );
        $modelNote->link( Note::RELATION_CREATOR, $modelUser );

        // Создаем запись в таблице access
        $modelUser = User::findOne( $modelUser->id );
        $modelNote = Note::findOne( $modelNote->id );
        $modelNote->link( Note::RELATION_ACCESSES_USERS, $modelUser );

        return $this->renderContent( 'create' );
    }

    public function actionLoad()
    {
        // Прочитать из базы все записи из User применив жадную подгрузку связанных данных из Note,
        // с запросами без JOIN
        //        $modelUser = User::find()->where( [ 'id' => 2 ] )->with( 'notes' )->all();
        $modelUser = User::find()->with( 'notes' )->all();

        // Прочитать из базы все записи из User применив жадную подгрузку связанных данных из Note,
        // с запросом содержащем JOIN
        $modelUser = User::find()->innerJoinWith( 'notes' )->asArray()->all();

        return $this->renderContent( VarDumper::dumpAsString( $modelUser, 10, true )  );
    }

    public function actionAccess()
    {
        $modelUser = User::findOne( 1 );
        $modelNote = Note::findOne( 3 );
        $modelUser->link( User::RELATION_ACCESSED_NOTES, $modelNote );

        return $this->renderContent( VarDumper::dumpAsString( $modelUser, 10, true )  );
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

<?php

namespace app\modules\main\controllers;

use app\modules\main\models\VirtualAssistant;
use app\modules\main\models\VirtualAssistantSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VirtualAssistantController implements the CRUD actions for VirtualAssistant model.
 */
class VirtualAssistantController extends Controller
{
    public $layout = 'main';

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all VirtualAssistant models.
     *
     * @return string
     */
    public function actionList()
    {
        $searchModel = new VirtualAssistantSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VirtualAssistant model.
     *
     * @param int $id VIRTUAL_ASSISTANT_MODEL_ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new VirtualAssistant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new VirtualAssistant();
        $model->author = Yii::$app->user->identity->getId();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success',
                Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_MESSAGE_CREATE_VIRTUAL_ASSISTANT'));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing VirtualAssistant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id VIRTUAL_ASSISTANT_MODEL_ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success',
                Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_MESSAGE_UPDATED_VIRTUAL_ASSISTANT'));

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing VirtualAssistant model.
     * If deletion is successful, the browser will be redirected to the 'list' page.
     *
     * @param int $id VIRTUAL_ASSISTANT_MODEL_ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success',
            Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_MESSAGE_DELETED_VIRTUAL_ASSISTANT'));

        return $this->redirect(['list']);
    }

    /**
     * Finds the VirtualAssistant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id VIRTUAL_ASSISTANT_MODEL_ID
     * @return VirtualAssistant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VirtualAssistant::findOne(['id' => $id])) !== null)
            return $model;
        throw new NotFoundHttpException(Yii::t('app', 'ERROR_MESSAGE_PAGE_NOT_FOUND'));
    }
}

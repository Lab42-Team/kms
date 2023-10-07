<?php

namespace app\modules\main\controllers;

use app\modules\main\models\GeneratorForm;
use app\modules\main\models\VirtualAssistant;
use app\modules\main\models\VirtualAssistantSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\bootstrap5\ActiveForm;
use app\components\DownloadFile;

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


    public function actionGenerate($id)
    {
        $generator = new GeneratorForm();

        return $this->render('generate', [
            'model' => $this->findModel($id),
            'generator' => $generator,
        ]);
    }


    public function actionOpenDialogueModel($id)
    {
        $model = $this->findModel($id);

        return $this->redirect(['/state-transition-diagrams/visual-diagram/'. $model->dialogue_model]);
    }


    public function actionOpenKnowledgeBaseModel($id)
    {
        $model = $this->findModel($id);

        return $this->redirect(['/state-transition-diagrams/visual-diagram/'. $model->knowledge_base_model]);
    }


    public function actionGeneratePlatform($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование формы
            $generator = new GeneratorForm();

            $model = $this->findModel($id);

            // Определение полей формы и валидация формы
            if ($generator->load(Yii::$app->request->post()) && $generator->validate()) {

                //Формирование файлов
                $text1 = 'json1 Платформа: <'. $generator->getPlatformsName() .'> сформировала файл Виртуального ассистента: '. $model->name;
                $text2 = 'csv Платформа: <'. $generator->getPlatformsName() .'> сформировала файл Виртуального ассистента: '. $model->name;
                $text3 = 'json2 Платформа: <'. $generator->getPlatformsName() .'> сформировала файл Виртуального ассистента: '. $model->name;

                $f1 = fopen('json/json-file1.json', 'w');
                fwrite($f1, $text1);
                fclose($f1);

                $f2 = fopen('json/csv-file.csv', 'w');
                fwrite($f2, $text2);
                fclose($f2);

                $f3 = fopen('json/json-file2.json', 'w');
                fwrite($f3, $text3);
                fclose($f3);


                // Успешный ввод данных
                $data["success"] = true;

                // Формирование данных
                $data["id"] = $id;
                $data["platform_name"] = $generator->getPlatformsName();
                $data["platform"] = $generator->platform;

            } else
                $data = ActiveForm::validate($generator);
            // Возвращение данных
            $response->data = $data;

            return $response;
        }
        return false;
    }


    public function actionDownloadJson($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = $this->findModel($id);

            $fileName = 'json/json-file1.json';

            // Успешный ввод данных
            $data["success"] = true;

            // Формирование данных
            $data["fileName"] = $fileName;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }

    public function actionDownloadCsv($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = $this->findModel($id);

            $fileName = 'json/csv-file.csv';

            // Успешный ввод данных
            $data["success"] = true;

            // Формирование данных
            $data["fileName"] = $fileName;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }

    public function actionDownloadJson2($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = $this->findModel($id);

            $fileName = 'json/json-file2.json';

            // Успешный ввод данных
            $data["success"] = true;

            // Формирование данных
            $data["fileName"] = $fileName;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }

}

<?php

namespace app\modules\main\controllers;

use app\modules\main\models\GeneratorForm;
use app\modules\main\models\VirtualAssistant;
use app\modules\main\models\VirtualAssistantModel;
use app\modules\main\models\VirtualAssistantSearch;
use app\modules\main\models\VirtualAssistantModelSearch;
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
        $searchModel = new VirtualAssistantModelSearch();
        $dataProvider = $searchModel->search($id, $this->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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


    public function actionCreateVam($id)
    {
        $model_vam = new VirtualAssistantModel();
        $model_vam->virtual_assistant_id = $id;
        if ($model_vam->load(Yii::$app->request->post()) && $model_vam->save()) {
            Yii::$app->getSession()->setFlash('success',
                Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_MESSAGE_CREATE_VIRTUAL_ASSISTANT_MODEL'));
            return $this->redirect(['view-vam', 'id' => $id, 'id_vam' => $model_vam->id]);
        }

        return $this->render('create-vam', [
            'model' => $this->findModel($id),
            'model_vam' => $model_vam,
        ]);
    }


    public function actionViewVam($id, $id_vam)
    {
        return $this->render('view-vam', [
            'model' => $this->findModel($id),
            'model_vam' => $this->findModelVam($id_vam),
        ]);
    }


    public function actionUpdateVam($id, $id_vam)
    {
        $model_vam = $this->findModelVam($id_vam);

        if ($model_vam->load(Yii::$app->request->post()) && $model_vam->save()) {
            Yii::$app->getSession()->setFlash('success',
                Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_MESSAGE_UPDATED_VIRTUAL_ASSISTANT_MODEL'));

            return $this->redirect(['view-vam', 'id' => $id, 'id_vam' => $model_vam->id]);
        }

        return $this->render('update-vam', [
            'model' => $this->findModel($id),
            'model_vam' => $model_vam,
        ]);
    }


    public function actionDeleteVam($id, $id_vam)
    {
        $this->findModelVam($id_vam)->delete();
        Yii::$app->getSession()->setFlash('success',
            Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_MESSAGE_DELETED_VIRTUAL_ASSISTANT_MODEL'));

        return $this->redirect(['view', 'id' => $id]);
    }


    protected function findModelVam($id)
    {
        if (($model = VirtualAssistantModel::findOne(['id' => $id])) !== null)
            return $model;
        throw new NotFoundHttpException(Yii::t('app', 'ERROR_MESSAGE_PAGE_NOT_FOUND'));
    }


    public function actionGenerate($id)
    {
        $generator = new GeneratorForm();

        //подбор моделей KNOWLEDGE_BASE_MODEL_TYPE
        $knowledge_base_models = VirtualAssistantModel::find()->where(['virtual_assistant_id' => $id, 'type' => VirtualAssistantModel::KNOWLEDGE_BASE_MODEL_TYPE])->all();
        $array_knowledge_base_model = array();
        $i = 0;
        if ($knowledge_base_models != null){
            foreach ($knowledge_base_models as $elem){
                $array_knowledge_base_model[$i]['label'] = $elem->targetModel->name;
                $array_knowledge_base_model[$i]['url'] = '/state-transition-diagrams/visual-diagram/'. $elem->target_model;
                $i = $i + 1;
            }
        }
        if ($array_knowledge_base_model == null){
                $array_knowledge_base_model[0]['label'] = Yii::t('app', 'MODELS_NOT_FOUND');
                $array_knowledge_base_model[0]['url'] = '';
        }

        //подбор моделей CONVERSATIONAL_INTERFACE_MODEL_TYPE
        $conversational_interface_models = VirtualAssistantModel::find()->where(['virtual_assistant_id' => $id, 'type' => VirtualAssistantModel::CONVERSATIONAL_INTERFACE_MODEL_TYPE])->all();
        $array_conversational_interface_model = array();
        $i = 0;
        if ($conversational_interface_models != null){
            foreach ($conversational_interface_models as $elem){
                $array_conversational_interface_model[$i]['label'] = $elem->targetModel->name;
                $array_conversational_interface_model[$i]['url'] = '/state-transition-diagrams/visual-diagram/'. $elem->target_model;
                $i = $i + 1;
            }
        }
        if ($array_conversational_interface_model == null){
            $array_conversational_interface_model[0]['label'] = Yii::t('app', 'MODELS_NOT_FOUND');
            $array_conversational_interface_model[0]['url'] = '';
        }


        return $this->render('generate', [
            'model' => $this->findModel($id),
            'generator' => $generator,
            'array_knowledge_base_model' => $array_knowledge_base_model,
            'array_conversational_interface_model' => $array_conversational_interface_model,
        ]);
    }


    public function actionOpenDialogueModel($id)
    {
        $model = VirtualAssistantModel::find()->where(['virtual_assistant_id' => $id])->one();

        return $this->redirect(['/state-transition-diagrams/visual-diagram/'. $model->dialogue_model]);
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

                //проверка есть ли папка
                $dir = "json";
                if(!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

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

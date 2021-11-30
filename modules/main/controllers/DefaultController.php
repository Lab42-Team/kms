<?php

namespace app\modules\main\controllers;

use app\modules\eete\models\TreeDiagram;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\modules\main\models\LoginForm;
use app\modules\main\models\ContactForm;
use app\modules\main\models\DiagramSearch;
use app\modules\main\models\Diagram;
use app\modules\main\models\Import;
use app\modules\stde\models\State;
use app\components\StateTransitionXMLImport;

class DefaultController extends Controller
{
    public $layout = 'main';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['sing-out'],
                'rules' => [
                    [
                        'actions' => ['sing-out'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Отображение главной страницы сайта редактора.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Страница входа.
     *
     * @return Response|string
     */
    public function actionSingIn()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('sing-in', [
            'model' => $model,
        ]);
    }

    /**
     * Действие выхода.
     *
     * @return Response
     */
    public function actionSingOut()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Страница обратной связи (контакта).
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Lists all diagram models.
     *
     * @return mixed
     */
    public function actionDiagrams()
    {
        $searchModel = new DiagramSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('diagrams', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Diagram model.
     *
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
     * Creates a new Diagram model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Diagram();
        $model->author = Yii::$app->user->identity->getId();
        $model->correctness = Diagram::NOT_CHECKED_CORRECT;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Если создаваемая диаграмма является деревом событий
            if ($model->type == Diagram::EVENT_TREE_TYPE) {
                // Создание диаграммы дерева событий
                $tree_diagram_model = new TreeDiagram();
                $tree_diagram_model->mode = TreeDiagram::EXTENDED_TREE_MODE;
                $tree_diagram_model->tree_view = TreeDiagram::ORDINARY_TREE_VIEW;
                $tree_diagram_model->diagram = $model->id;
                $tree_diagram_model->save();
            }
            Yii::$app->getSession()->setFlash('success',
                Yii::t('app', 'DIAGRAMS_PAGE_MESSAGE_CREATE_DIAGRAM'));

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Diagram model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success',
                Yii::t('app', 'DIAGRAMS_PAGE_MESSAGE_UPDATED_DIAGRAM'));

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Diagram model.
     * If deletion is successful, the browser will be redirected to the 'diagrams' page.
     *
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success',
            Yii::t('app', 'DIAGRAMS_PAGE_MESSAGE_DELETED_DIAGRAM'));

        return $this->redirect(['diagrams']);
    }

    /**
     * Finds the Diagram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $id
     * @return Diagram|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Diagram::findOne($id)) !== null)
            return $model;

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Импорт диаграммы.
     *
     */
    public function actionImport($id)
    {
        $model = $this->findModel($id);
        $import_model = new Import();

        //вывод сообщения об очистки если диаграмма не пуста
        $diagram = Diagram::find()->where(['id' => $id])->one();

        $count = State::find()->where(['diagram' => $id])->count();
        if ($count > 0){
            Yii::$app->getSession()->setFlash('warning',
                Yii::t('app', 'MESSAGE_CLEANING'));
        }

        //обработка импорта
        if (Yii::$app->request->isPost) {
            $import_model->file_name = UploadedFile::getInstance($import_model, 'file_name');

            if ($import_model->upload()) {

                $file = simplexml_load_file('uploads/temp.xml');

                //определение корректности файла по наличию в нем State (состояний)
                $i = 0;
                foreach($file->State as $state) {
                    $i = $i + 1;
                }
                if ($i > 0){
                    $type = Diagram::STATE_TRANSITION_DIAGRAM_TYPE;
                } else {
                    $type = -1;
                }

                //если тип диаграммы совпадает с типом диаграммы в файле
                if ($diagram->type == $type) {
                    //импорт xml файла
                    $generator = new StateTransitionXMLImport();
                    $generator->importXMLCode($id, $file);

                    //удаление файла
                    unlink('uploads/temp.xml');

                    Yii::$app->getSession()->setFlash('success',
                        Yii::t('app', 'DIAGRAMS_PAGE_MESSAGE_IMPORT_DIAGRAM'));

                    return $this->render('view', [
                        'model' => $this->findModel($id),
                    ]);
                } else {
                    Yii::$app->getSession()->setFlash('error',
                        Yii::t('app', 'MESSAGE_IMPORT_ERROR_INCOMPATIBLE_MODE'));

                    return $this->render('import', [
                        'model' => $model,
                        'import_model' => $import_model,
                    ]);
                }
            }
        }

        return $this->render('import', [
            'model' => $model,
            'import_model' => $import_model,
        ]);
    }

}
<?php

namespace app\modules\main\controllers;

use app\components\OWLOntologyImporter;
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
use app\modules\main\models\ImportCSV;
use app\modules\eete\models\TreeDiagram;
use app\modules\eete\models\Sequence;
use app\modules\eete\models\Level;
use app\modules\eete\models\Node;
use app\modules\eete\models\Parameter;
use app\modules\stde\models\State;
use app\modules\stde\models\StateProperty;
use app\modules\stde\models\Transition;
use app\modules\stde\models\TransitionProperty;
use app\modules\main\models\User;
use app\modules\main\models\OWLFileForm;
use app\components\StateTransitionXMLImport;
use app\components\EventTreeXMLImport;
use app\components\StateTransitionCSVloader;

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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, null);

        if (!Yii::$app->user->isGuest) {
            //подбор диаграмм EVENT_TREE_TYPE
            $event_tree_templates = Diagram::find()->where(['type' => Diagram::EVENT_TREE_TYPE])->all();
            $array_event_tree_template = array();
            $i = 0;
            if ($event_tree_templates != null){
                foreach ($event_tree_templates as $elem){
                    $array_event_tree_template[$i]['label'] = $elem->name;
                    $array_event_tree_template[$i]['url'] = 'creation-template/' . $elem->id;
                    $i = $i + 1;
                }
            }

            //подбор диаграмм STATE_TRANSITION_DIAGRAM_TYPE
            $state_transition_diagram_templates = Diagram::find()->where(['type' => Diagram::STATE_TRANSITION_DIAGRAM_TYPE])->all();
            $array_state_transition_diagram_template = array();
            $i = 0;
            if ($state_transition_diagram_templates != null){
                foreach ($state_transition_diagram_templates as $elem){
                    $array_state_transition_diagram_template[$i]['label'] = $elem->name;
                    $array_state_transition_diagram_template[$i]['url'] = 'creation-template/' . $elem->id;
                    $i = $i + 1;
                }
            }

            //добавление диаграмм в массив $array_template для вывода группой
            $array_template = array();
            $i = 0;
            if ($array_event_tree_template != null){
                $array_template[$i]['label'] = Yii::t('app', 'DIAGRAM_MODEL_EVENT_TREE_TYPE');
                $array_template[$i]['items'] = $array_event_tree_template;
                $i = $i + 1;
            }
            if ($array_state_transition_diagram_template != null){
                $array_template[$i]['label'] = Yii::t('app', 'DIAGRAM_MODEL_STATE_TRANSITION_DIAGRAM_TYPE');
                $array_template[$i]['items'] = $array_state_transition_diagram_template;
                $i = $i + 1;
            }

            if ($array_template == null){
                $array_template[0]['label'] = Yii::t('app', 'TEMPLATES_DIAGRAMS_NOT_FOUND');
                $array_template[0]['url'] = '';
            }
        } else {
            $array_template = array();
        }

        return $this->render('diagrams', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'array_template' => $array_template,
        ]);
    }

    /**
     * Lists diagram models for user.
     *
     * @return mixed
     */
    public function actionMyDiagrams()
    {
        $searchModel = new DiagramSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->identity->getId());

        if (!Yii::$app->user->isGuest) {
            //подбор диаграмм EVENT_TREE_TYPE принадлежащих пользователю
            $event_tree_templates = Diagram::find()->where([
                'type' => Diagram::EVENT_TREE_TYPE,
                'author' => Yii::$app->user->identity->getId()
            ])->all();
            $array_event_tree_template = array();
            $i = 0;
            if ($event_tree_templates != null){
                foreach ($event_tree_templates as $elem){
                    $array_event_tree_template[$i]['label'] = $elem->name;
                    $array_event_tree_template[$i]['url'] = 'creation-template/' . $elem->id;
                    $i = $i + 1;
                }
            }

            //подбор диаграмм STATE_TRANSITION_DIAGRAM_TYPE принадлежащих пользователю
            $state_transition_diagram_templates = Diagram::find()->where([
                'type' => Diagram::STATE_TRANSITION_DIAGRAM_TYPE,
                'author' => Yii::$app->user->identity->getId()
            ])->all();
            $array_state_transition_diagram_template = array();
            $i = 0;
            if ($state_transition_diagram_templates != null){
                foreach ($state_transition_diagram_templates as $elem){
                    $array_state_transition_diagram_template[$i]['label'] = $elem->name;
                    $array_state_transition_diagram_template[$i]['url'] = 'creation-template/' . $elem->id;
                    $i = $i + 1;
                }
            }

            //добавление диаграмм в массив $array_template для вывода группой
            $array_template = array();
            $i = 0;
            if ($array_event_tree_template != null){
                $array_template[$i]['label'] = Yii::t('app', 'DIAGRAM_MODEL_EVENT_TREE_TYPE');
                $array_template[$i]['items'] = $array_event_tree_template;
                $i = $i + 1;
            }
            if ($array_state_transition_diagram_template != null){
                $array_template[$i]['label'] = Yii::t('app', 'DIAGRAM_MODEL_STATE_TRANSITION_DIAGRAM_TYPE');
                $array_template[$i]['items'] = $array_state_transition_diagram_template;
                $i = $i + 1;
            }

            if ($array_template == null){
                $array_template[0]['label'] = Yii::t('app', 'TEMPLATES_DIAGRAMS_NOT_FOUND');
                $array_template[0]['url'] = '';
            }
        } else {
            $array_template = array();
        }

        return $this->render('my-diagrams', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'array_template' => $array_template,
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
        $visible = true;
        $model = $this->findModel($id);
        if ($model->type != Diagram::EVENT_TREE_TYPE) {
            $visible = false;
        }

        return $this->render('view', [
            'model' => $model,
            'visible' => $visible,
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
                $tree_diagram_model->mode = $model->mode_tree_diagram;
                $tree_diagram_model->diagram = $model->id;
                $tree_diagram_model->save();

                if ($model->mode_tree_diagram == TreeDiagram::CLASSIC_TREE_MODE){
                    // Создание пустого уровня
                    $level = new Level();
                    $level->tree_diagram = $tree_diagram_model->id;
                    $level->name = "Only";
                    $level->description = "";
                    $level->parent_level = null;
                    $level->save();
                }
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
        //$this->findModel($id)->delete(); //удаление по умолчанию
        $diagram = $this->findModel($id);
        if ($diagram->type == Diagram::EVENT_TREE_TYPE){
            $tree_diagram = TreeDiagram::find()->where(['diagram' => $diagram->id])->one();

            $sequence = Sequence::find()->where(['tree_diagram' => $tree_diagram->id])->all();
            foreach ($sequence as $s){
                $s -> delete();
            }
            $level = Level::find()->where(['tree_diagram' => $tree_diagram->id])->all();
            foreach ($level as $l){
                $l -> delete();
            }
            $node = Node::find()->where(['tree_diagram' => $tree_diagram->id])->all();
            foreach ($node as $n){
                $n -> delete();
            }
            $diagram -> delete();
        } elseif ($diagram->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE){
            $state = State::find()->where(['diagram' => $diagram->id])->all();
            foreach ($state as $s){
                $s -> delete();
            }
            $diagram -> delete();
        } else {
            $diagram -> delete();
        }

        Yii::$app->getSession()->setFlash('success',
            Yii::t('app', 'DIAGRAMS_PAGE_MESSAGE_DELETED_DIAGRAM'));

        return $this->redirect(Yii::$app->user->identity->role == User::ROLE_ADMINISTRATOR ? ['diagrams'] :
            ['my-diagrams']);
    }

    /**
     * Импорт диаграммы.
     *
     * @param $id - идентификатор диаграммы
     * @return string
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionImport($id)
    {
        $model = $this->findModel($id);
        $import_model = new Import();

        // Вывод сообщения об очистки если диаграмма не пуста
        $diagram = Diagram::find()->where(['id' => $id])->one();
        $count = State::find()->where(['diagram' => $id])->count();
        if ($count > 0)
            Yii::$app->getSession()->setFlash('warning', Yii::t('app', 'MESSAGE_CLEANING'));

        // Вывод сообщения об очистки если диаграмма не пуста (если диаграмма событий)
        $tree_diagram = TreeDiagram::find()->where(['diagram' => $id])->one();
        if ($tree_diagram != null) {
            if ($tree_diagram->mode == TreeDiagram::EXTENDED_TREE_MODE) {
                $count = Level::find()->where(['tree_diagram' => $tree_diagram->id])->count();
                if ($count > 0)
                    Yii::$app->getSession()->setFlash('warning', Yii::t('app', 'MESSAGE_CLEANING'));
            }
            if ($tree_diagram->mode == TreeDiagram::CLASSIC_TREE_MODE) {
                $count = Node::find()->where(['tree_diagram' => $tree_diagram->id])->count();
                if ($count > 0)
                    Yii::$app->getSession()->setFlash('warning', Yii::t('app', 'MESSAGE_CLEANING'));
            }
        }

        // Обработка импорта
        if (Yii::$app->request->isPost) {
            $import_model->file_name = UploadedFile::getInstance($import_model, 'file_name');

            if ($import_model->upload()) {

                $file = simplexml_load_file('uploads/temp.xml');

                // Определение корректности файла по наличию в нем State (состояний)
                $i = 0;
                foreach($file->State as $state)
                    $i++;
                if ($i > 0) {
                    $type = Diagram::STATE_TRANSITION_DIAGRAM_TYPE;
                    $mode = -1;
                } else
                    $type = -1;

                if (((string)$file["type"] == "Дерево событий") or ((string)$file["type"] == "Event tree")) {
                    $type = Diagram::EVENT_TREE_TYPE;
                    // Выявление расширенного или классического дерева
                    if (((string)$file["mode"] == "Расширенное дерево") or ((string)$file["mode"] == "Extended tree"))
                        $mode = TreeDiagram::EXTENDED_TREE_MODE;
                    if (((string)$file["mode"] == "Классическое дерево") or ((string)$file["mode"] == "Classic tree"))
                        $mode = TreeDiagram::CLASSIC_TREE_MODE;
                }

                // Если тип диаграммы совпадает с типом диаграммы в файле
                if (($diagram->type == $type) and ($mode == -1)) {
                    // Импорт xml файла
                    $generator = new StateTransitionXMLImport();
                    $generator->importXMLCode($id, $file);

                    // Удаление файла
                    unlink('uploads/temp.xml');

                    Yii::$app->getSession()->setFlash('success',
                        Yii::t('app', 'DIAGRAMS_PAGE_MESSAGE_IMPORT_DIAGRAM'));

                    return $this->render('view', [
                        'model' => $this->findModel($id),
                        'visible' => false,
                    ]);
                } elseif (($diagram->type == $type) and ($tree_diagram->mode == $mode)) {
                    // Импорт xml файла
                    $generator = new EventTreeXMLImport();
                    $generator->importXMLCode($tree_diagram->id, $file);

                    // Удаление файла
                    unlink('uploads/temp.xml');

                    Yii::$app->getSession()->setFlash('success',
                        Yii::t('app', 'DIAGRAMS_PAGE_MESSAGE_IMPORT_DIAGRAM'));

                    return $this->render('view', [
                        'model' => $this->findModel($id),
                        'visible' => true,
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

    /**
     * Загрузка онтологии в формате OWL и ее преобразование в диаграмму переходов состояния.
     *
     * @param $id - идентификатор диаграммы переходов состояний
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUploadOntology($id)
    {
        // Поиск модели диаграммы переходов состояний по id
        $model = $this->findModel($id);
        // Создание формы файла OWL-онтологии
        $owl_file_form = new OWLFileForm();
        // Если POST-запрос
        if (Yii::$app->request->isPost) {
            // Загрузка файла с формы
            $owl_file = UploadedFile::getInstance($owl_file_form, 'owl_file');
            $owl_file_form->owl_file = $owl_file;
            // Валидация поля файла
            if ($owl_file_form->validate(['owl_file'])) {
                // Загрузка полей формы
                if ($owl_file_form->load(Yii::$app->request->post())) {
                    // Получение XML-строк из OWL-файла онтологии
                    $xml_rows = simplexml_load_file($owl_file->tempName);
                    // Создание объекта класса импортера онтологии
                    $owl_ontology_importer = new OWLOntologyImporter();
                    // Конвертация OWL-онтологии в диаграмму переходов состояний
                    $owl_ontology_importer->convertOWLOntologyToStateTransitionDiagram($id, $xml_rows,
                        $owl_file_form->class, $owl_file_form->class_datatype_property,
                        $owl_file_form->subclass_relation, $owl_file_form->class_object_property,
                        $owl_file_form->individual, $owl_file_form->individual_datatype_property,
                        $owl_file_form->is_a_relation, $owl_file_form->individual_object_property);
                    // Вывод сообщения об успешной загрузке файла онтологии
                    Yii::$app->getSession()->setFlash('success',
                        Yii::t('app', 'DIAGRAMS_PAGE_MESSAGE_UPLOAD_ONTOLOGY'));

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        // Вывод сообщения с предупреждением перед загрузкой онтологии
        Yii::$app->getSession()->setFlash('warning',
            Yii::t('app', 'DIAGRAMS_PAGE_MESSAGE_WARNING_BEFORE_UPLOAD_ONTOLOGY'));

        return $this->render('upload-ontology', [
            'model' => $model,
            'owl_file_form' => $owl_file_form
        ]);
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

        throw new NotFoundHttpException(Yii::t('app', 'ERROR_MESSAGE_PAGE_NOT_FOUND'));
    }


    /**
     * Создание диаграммы из другой диаграммы
     *
     * @param $id - идентификатор диаграммы
     * @return string|Response
     */
    public function actionCreationTemplate($id)
    {
        //поиск Diagram шаблона
        $template_diagram = Diagram::find()->where(['id' => $id])->one();

        //создание новой diagram из шаблона
        $diagram = new Diagram();
        $diagram->author = Yii::$app->user->identity->getId();
        $diagram->correctness = Diagram::NOT_CHECKED_CORRECT;
        $diagram->name =  Yii::t('app', 'DIAGRAM_CREATED_FROM') . $template_diagram->name;
        $diagram->description = $template_diagram->description;
        $diagram->type = $template_diagram->type;
        $diagram->status = $template_diagram->status;
        $diagram->save();

        if ($template_diagram->type == Diagram::EVENT_TREE_TYPE){

            $template_tree_diagram = TreeDiagram::find()->where(['diagram' => $id])->one();

            $tree_diagram = new TreeDiagram();
            $tree_diagram->diagram = $diagram->id;
            $tree_diagram->mode = $template_tree_diagram->mode;
            $tree_diagram->save();

            //массив node (для копирования связей)
            $array_nodes = array();
            $j = 0;

            $template_level_count = Level::find()->where(['tree_diagram' => $template_tree_diagram->id])->count();
            $template_parent_level = null;
            $parent_level = null;
            for ($i = 1; $i <= $template_level_count; $i++) {
                $template_level = Level::find()->where(['parent_level' => $template_parent_level, 'tree_diagram' => $template_tree_diagram->id])->one();

                //создание нового level из шаблона
                $level = new Level();
                $level->name = $template_level->name;
                $level->description = $template_level->description;
                $level->parent_level = $parent_level;
                $level->tree_diagram = $tree_diagram->id;
                $level->comment =  $template_level->comment;
                $level->save();

                $template_parent_level = $template_level->id;
                $parent_level = $level->id;

                $template_sequences = Sequence::find()->where(['level' => $template_parent_level, 'tree_diagram' => $template_tree_diagram->id])->all();
                foreach ($template_sequences as $s){

                    $template_node = Node::find()->where(['id' => $s->node])->one();
                    //создание нового node из шаблона
                    $node = new Node();
                    $node->name = $template_node->name;
                    $node->certainty_factor = $template_node->certainty_factor;
                    $node->description = $template_node->description;
                    $node->operator = $template_node->operator;
                    $node->type = $template_node->type;
                    $node->parent_node = $template_node->parent_node;
                    $node->tree_diagram = $tree_diagram->id;
                    $node->level_id = $parent_level;
                    $node->indent_x = $template_node->indent_x;
                    $node->indent_y = $template_node->indent_y;
                    $node->comment =  $template_node->comment;
                    $node->save();

                    $array_nodes[$j]['node_template'] = $template_node->id;
                    $array_nodes[$j]['node'] = $node->id;
                    $j = $j+1;

                    //поиск всех parameter из шаблона по id node
                    $template_parameters = Parameter::find()->where(['node' => $template_node->id])->all();
                    foreach ($template_parameters as $p){
                        //создание нового parameter из шаблона
                        $parameter = new Parameter();
                        $parameter->name = $p->name;
                        $parameter->description = $p->description;
                        $parameter->operator = $p->operator;
                        $parameter->value = $p->value;
                        $parameter->node = $node->id;
                        $parameter->save();
                    }

                    //создание нового sequence из шаблона
                    $sequence = new Sequence();
                    $sequence->tree_diagram = $tree_diagram->id;
                    $sequence->level = $parent_level;
                    $sequence->node = $node->id;
                    $sequence_model_count = Sequence::find()->where(['tree_diagram' => $diagram->id])->count();
                    $sequence->priority = $sequence_model_count;
                    $sequence->save();
                }
            }

            //обновление связей
            $nodes = Node::find()->where(['tree_diagram' => $tree_diagram->id])->all();
            foreach ($nodes as $n){
                for ($i = 0; $i < $j; $i++) {
                    if ($n->parent_node == $array_nodes[$i]['node_template']){
                        $n->parent_node = $array_nodes[$i]['node'];
                        $n->updateAttributes(['parent_node']);
                    }
                }
            }
        } elseif ($template_diagram->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE){
            //массив state (для копирования связей)
            $array_states = array();
            $j = 0;

            $template_states = State::find()->where(['diagram' => $id])->all();
            foreach ($template_states as $template_state){
                //создание нового state из шаблона
                $state = new State();
                $state->name = $template_state->name;
                $state->type = $template_state->type;
                $state->description = $template_state->description;
                $state->indent_x = $template_state->indent_x;
                $state->indent_y = $template_state->indent_y;
                $state->diagram = $diagram->id;
                $state->save();

                $array_states[$j]['state_template'] = $template_state->id;
                $array_states[$j]['state'] = $state->id;
                $j = $j+1;

                //поиск всех state_propertys из шаблона по id state
                $template_state_propertys = StateProperty::find()->where(['state' => $template_state->id])->all();
                foreach ($template_state_propertys as $template_state_property){
                    //создание нового state_property из шаблона
                    $state_property = new StateProperty();
                    $state_property->name = $template_state_property->name;
                    $state_property->description = $template_state_property->description;
                    $state_property->operator = $template_state_property->operator;
                    $state_property->value = $template_state_property->value;
                    $state_property->state = $state->id;
                    $state_property->save();
                }
            }

            //подбор всех Transition
            $transition_all = Transition::find()->all();
            $template_transitions = array();//массив связей
            foreach ($transition_all as $t){
                foreach ($template_states as $s){
                    if ($t->state_from == $s->id){
                        array_push($template_transitions, $t);
                    }
                }
            }

            if ($template_transitions != null) {
                foreach ($template_transitions as $template_transition){
                    //создание нового transition из шаблона
                    $transition = new Transition();
                    $transition->name = $template_transition->name;
                    $transition->description = $template_transition->description;
                    for ($i = 0; $i < $j; $i++) {
                        if ($template_transition->state_from == $array_states[$i]['state_template']){
                            $transition->state_from = $array_states[$i]['state'];
                            $transition->updateAttributes(['state_from']);
                        }
                    }
                    for ($i = 0; $i < $j; $i++) {
                        if ($template_transition->state_to == $array_states[$i]['state_template']){
                            $transition->state_to = $array_states[$i]['state'];
                            $transition->updateAttributes(['state_to']);
                        }
                    }
                    $transition->name_property = "0";
                    $transition->operator_property = 1;
                    $transition->value_property = "0";
                    $transition->save();

                    //поиск всех transition_propertys из шаблона по id state
                    $template_transition_propertys = TransitionProperty::find()->where(['transition' => $template_transition->id])->all();
                    foreach ($template_transition_propertys as $template_transition_property){
                        //создание нового transition_property из шаблона
                        $transition_property = new TransitionProperty();
                        $transition_property->name = $template_transition_property->name;
                        $transition_property->description = $template_transition_property->description;
                        $transition_property->operator = $template_transition_property->operator;
                        $transition_property->value = $template_transition_property->value;
                        $transition_property->transition = $transition->id;
                        $transition_property->save();
                    }
                }
            }
        }

        Yii::$app->getSession()->setFlash('success',
            Yii::t('app', 'DIAGRAMS_PAGE_MESSAGE_CREATE_DIAGRAM'));

        return $this->redirect(['view', 'id' => $diagram->id]);
    }


    /**
     * Создание диаграммы из csv файла
     *
     * @param $id - идентификатор диаграммы
     * @return string|Response
     */
    public function actionUploadCsv($id)
    {

        $f = 0;
        //Массив для хранения значений csv файла
        $csv = [];

        // Поиск модели диаграммы переходов состояний по id
        $model = $this->findModel($id);
        $import_model = new ImportCSV();

        // Обработка импорта
        if (Yii::$app->request->isPost) {
            $import_model->file_name = UploadedFile::getInstance($import_model, 'file_name');

            if ($import_model->upload()) {

                //открываем файл
                $file = fopen('uploads/temp.csv', 'r');

                if ($file !== false) {
                    //просматриваем файл и заносим значения в массив $csv
                    while (!feof($file) ) {
                        $csv[] = fgetcsv($file, 0, ';');
                    }
                    fclose($file);

                    $cod = mb_check_encoding($csv[0][0], 'UTF-8');

                    if ($cod == 1){
                        //создаем диаграмму на основе $csv
                        $generator = new StateTransitionCSVloader();
                        $generator->uploadCSV($id, $csv);

                        // Удаление файла
                        unlink('uploads/temp.csv');

                        Yii::$app->getSession()->setFlash('success',
                            Yii::t('app', 'DIAGRAMS_PAGE_MESSAGE_UPLOAD_DECISION_TABLE'));

                        return $this->redirect(['view', 'id' => $id]);

                    } else {
                        Yii::$app->getSession()->setFlash('error',
                            Yii::t('app', 'DIAGRAMS_PAGE_MESSAGE_INVALID_ENCODING'));
                        return $this->render('upload-csv', [
                            'model' => $model,
                            'import_model' => $import_model,
                        ]);
                    }
                }
            }
        }

        return $this->render('upload-csv', [
            'model' => $model,
            'import_model' => $import_model,
        ]);
    }
}
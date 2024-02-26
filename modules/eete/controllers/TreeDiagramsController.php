<?php

namespace app\modules\eete\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\bootstrap5\ActiveForm;
use app\modules\main\models\Diagram;
use app\modules\eete\models\Level;
use app\modules\eete\models\Node;
use app\modules\eete\models\Sequence;
use app\modules\eete\models\Parameter;
use app\modules\eete\models\TreeDiagram;
use app\modules\eete\models\Import;
use app\modules\main\models\OWLFileForm;
use app\components\EventTreeXMLGenerator;
use app\components\OWLOntologyImporter;

/**
 * TreeDiagramsController implements the CRUD actions for TreeDiagram model.
 */
class TreeDiagramsController extends Controller
{
    public $layout = '@app/modules/main/views/layouts/main';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete', 'create', 'add-level', 'add-event', 'add-mechanism',
                    'edit-level', 'edit-event', 'edit-mechanism', 'delete-level', 'delete-event', 'delete-mechanism',
                    'add-relationship', 'delete-relationship', 'add-parameter', 'edit-parameter', 'delete-parameter',
                    'correctness', 'creation-template'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'create', 'add-level', 'add-event', 'add-mechanism',
                            'edit-level', 'edit-event', 'edit-mechanism', 'delete-level', 'delete-event', 'delete-mechanism',
                            'add-relationship', 'delete-relationship', 'add-parameter', 'edit-parameter', 'delete-parameter',
                            'correctness', 'creation-template'],
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
     * Finds the Diagram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $id
     * @return Diagram|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $tree_diagram = TreeDiagram::find()->where(['id' => $id])->one();
        if (($model = Diagram::findOne($tree_diagram->diagram)) !== null)
            return $model;
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the TreeDiagram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $id
     * @return TreeDiagram|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelTreeDiagram($id)
    {
        if (($model = TreeDiagram::findOne($id)) !== null)
            return $model;
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Страница визуального редактора деревьев.
     *
     * @param $id - id дерева событий
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionVisualDiagram($id)
    {
        $level_model_all = Level::find()->where(['tree_diagram' => $id])->all();
        $level_model_count = Level::find()->where(['tree_diagram' => $id])->count();//количество уровней
        $the_initial_event_is = Node::find()->where(['tree_diagram' => $id, 'type' => Node::INITIAL_EVENT_TYPE])->count();//переменная определяющая наличие начального события
        $initial_event_model_all = Node::find()->where(['tree_diagram' => $id, 'type' => Node::INITIAL_EVENT_TYPE])->all();
        $event_model_all = Node::find()->where(['tree_diagram' => $id, 'type' => Node::EVENT_TYPE])->all();
        $mechanism_model_all = Node::find()->where(['tree_diagram' => $id, 'type' => Node::MECHANISM_TYPE])->all();
        $sequence_model_all = Sequence::find()->where(['tree_diagram' => $id])->all();
        $node_model_all = Node::find()->where(['tree_diagram' => $id])->all();

        $parameter_all = Parameter::find()->all();
        $parameter_model_all = array();//массив пустых уровней
        foreach ($parameter_all as $p){
            foreach ($node_model_all as $n){
                if ($p->node == $n->id){
                    array_push($parameter_model_all, $p);
                }
            }
        }

        $level_model = new Level();
        $node_model = new Node();
        $parameter_model = new Parameter();
        $import_model = new Import();

        $array_levels = Level::getLevelsArray($id);
        $array_levels_initial_without = Level::getWithoutInitialLevelsArray($id);

        if (Yii::$app->request->isPost) {
            $code_generator = new EventTreeXMLGenerator();
            $code_generator->generateEETDXMLCode($id);
        }

        return $this->render('visual-diagram', [
            'model' => $this->findModel($id),
            'model_tree_diagram' => $this->findModelTreeDiagram($id),
            'level_model' => $level_model,
            'node_model' => $node_model,
            'parameter_model' => $parameter_model,
            'import_model' => $import_model,
            'level_model_all' => $level_model_all,
            'level_model_count' => $level_model_count,
            'the_initial_event_is' => $the_initial_event_is,
            'initial_event_model_all' =>$initial_event_model_all,
            'event_model_all' => $event_model_all,
            'mechanism_model_all' => $mechanism_model_all,
            'sequence_model_all' => $sequence_model_all,
            'node_model_all' => $node_model_all,
            'parameter_model_all' => $parameter_model_all,
            'array_levels' => $array_levels,
            'array_levels_initial_without' => $array_levels_initial_without,
        ]);
    }

    /**
     * Добавление нового уровня в дерево событий.
     *
     * @param $id - id дерева событий
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddLevel($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new Level();
            // Задание id диаграммы
            $model->tree_diagram = $id;

            // Задание parent_level уровня
            $mas = Level::find()->where(['tree_diagram' => $id, 'parent_level' => null ])->one();;
            if ($mas <> null){
                $a = $mas->id;
                do {
                    $b = $a;
                    $mas = Level::find()->where(['tree_diagram' => $id, 'parent_level' => $b ])->one();;
                    if ($mas <> null)
                        $a = $mas->id;
                } while ($mas <> null);
                $model->parent_level = $b;
            } else {
                $model->parent_level = null;
            }

            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
               // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового уровня в БД
                $model->save();
                // Формирование данных о новом уровне
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["parent_level"] = $model->parent_level;
                $data["level_count"] = Level::find()->where(['tree_diagram' => $id])->count();
            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;

            return $response;
        }

        return false;
    }

    /**
     * Добавление нового события в дерево событий.
     *
     * @param $id - id дерева событий
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddEvent($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new Node();
            // Задание id диаграммы
            $model->tree_diagram = $id;
            // Задание AND_OPERATOR для оператора по умолчанию
            $model->operator = Node::AND_OPERATOR;
            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Условие проверки является ли событие инициирующим
                $i = Node::find()->where(['tree_diagram' => $id, 'type' => Node::INITIAL_EVENT_TYPE])->count();
                // Если инициирующие события есть
                if ($i > '0') {
                    // Тип присваивается константа "EVENT_TYPE" как событие
                    $model->type = Node::EVENT_TYPE;
                } else {
                    // Тип присваивается константа "INITIAL_EVENT_TYPE" как инициирующее событие
                    $model->type = Node::INITIAL_EVENT_TYPE;
                    $level = Level::find()->where(['tree_diagram' => $id, 'parent_level' => null])->one();
                    $model->level_id = $level->id;
                }
                // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового уровня в БД
                $model->save();
                // Формирование данных о новом уровне
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["certainty_factor"] = $model->certainty_factor;
                $data["description"] = $model->description;
                $data["parent_node"] = $model->parent_node;
                $data["type"] = $model->type;

                $sequence = new Sequence();
                $sequence->tree_diagram = $id;
                $sequence->level = $model->level_id;
                $sequence->node = $model->id;
                $sequence_model_count = Sequence::find()->where(['tree_diagram' => $id])->count();
                $sequence->priority = $sequence_model_count;
                $sequence->save();

                $data["id_level"] = $model->level_id;
                $data["level_count"] = Level::find()->where(['tree_diagram' => $id])->count();

                $diagram = TreeDiagram::find()->where(['id' => $id])->one();
                $data["mode"] = $diagram->mode;

            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;

            return $response;
        }

        return false;
    }

    /**
     * Добавление нового механизма в дерево событий.
     *
     * @param $id - id дерева событий
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddMechanism($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new Node();
            // Задание id диаграммы
            $model->tree_diagram = $id;
            // Задание AND_OPERATOR для оператора по умолчанию
            $model->operator = Node::AND_OPERATOR;
            // Задание константы "MECHANISM_TYPE" типа узла механизма
            $model->type = Node::MECHANISM_TYPE;
            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового уровня в БД
                $model->save();
                // Формирование данных о новом уровне
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["parent_node"] = $model->parent_node;

                $sequence = new Sequence();
                $sequence->tree_diagram = $id;
                $sequence->level = $model->level_id;
                $sequence->node = $model->id;
                $sequence_model_count = Sequence::find()->where(['tree_diagram' => $id])->count();
                $sequence->priority = $sequence_model_count;
                $sequence->save();

                $data["id_level"] = $model->level_id;
            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionAddRelationship()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Node::find()->where(['id' => Yii::$app->request->post('node_id')])->one();
            $model->parent_node = Yii::$app->request->post('parent_node_id');
            $model->updateAttributes(['parent_node']);

            $data["success"] = true;
            $data["n_id"] = $model->id;
            $data["p_n_id"] = $model->parent_node;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionDeleteRelationship()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $id_target = Yii::$app->request->post('id_target');

            $model = Node::find()->where(['id' => $id_target])->one();
            $model->parent_node = null;
            $model->updateAttributes(['parent_node']);

            $data["success"] = true;
            $data["id_target"] = $id_target;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionEditLevel()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Level::find()->where(['id' => Yii::$app->request->post('level_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Формирование данных об измененном событии
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }

        return false;
    }

    public function actionEditEvent()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Node::find()->where(['id' => Yii::$app->request->post('node_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Формирование данных об измененном событии
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["certainty_factor"] = $model->certainty_factor;
                $data["description"] = $model->description;
                $data["type"] = $model->type;
                $data["id_level"] = $model->level_id;
                $data["parent_node"] = $model->parent_node;


                if ($model->level_id != Yii::$app->request->post('level_id_on_click')){
                    $sequence = Sequence::find()->where(['node' => Yii::$app->request->post('node_id_on_click')])->one();
                    $sequence->level = $model->level_id;
                    $sequence->updateAttributes(['level']);

                    //очистить связи в бд-----------------
                    //очистить входящие связи
                    $node = Node::find()->where(['id' => $data["id"]])->one();
                    $node->parent_node = null;
                    $node->updateAttributes(['parent_node']);

                    //очистить выходящие связи
                    $node_out = Node::find()->where(['parent_node' => $data["id"]])->all();
                    foreach ($node_out as $elem){
                        $elem->parent_node = null;
                        $elem->updateAttributes(['parent_node']);
                    }
                }

            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionEditMechanism()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Node::find()->where(['id' => Yii::$app->request->post('node_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Формирование данных об измененном событии
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["type"] = $model->type;
                $data["id_level"] = $model->level_id;
                $data["parent_node"] = $model->parent_node;


                if ($model->level_id != Yii::$app->request->post('level_id_on_click')){
                    $sequence = Sequence::find()->where(['node' => Yii::$app->request->post('node_id_on_click')])->one();
                    $sequence->level = $model->level_id;
                    $sequence->updateAttributes(['level']);

                    //очистить связи в бд
                    //очистить входящие связи
                    $node = Node::find()->where(['id' => $data["id"]])->one();
                    $node->parent_node = null;
                    $node->updateAttributes(['parent_node']);

                    //очистить выходящие связи
                    $node_out = Node::find()->where(['parent_node' => $data["id"]])->all();
                    foreach ($node_out as $elem){
                        $elem->parent_node = null;
                        $elem->updateAttributes(['parent_node']);
                    }
                }

            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionDeleteLevel($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $level_id_on_click = Yii::$app->request->post('level_id_on_click');

            //удаляемый уровень
            $level = Level::find()->where(['id' => $level_id_on_click])->one();
            //следующий уровень за удаляемым
            $level_descendent = Level::find()->where(['parent_level' => $level_id_on_click])->one();

            //если удаляется начальный уровень то удаляются механизмы на следующем
            if (($level->parent_level == null)&&($level_descendent != null)){
                $sequence_mas = Sequence::find()->where(['level' => $level_descendent->id])->all();
                foreach ($sequence_mas as $elem){
                    $node = Node::find()->where(['id' => $elem->node, 'type' => Node::MECHANISM_TYPE])->one();
                    if ($node != null){
                        $node_mas = Node::find()->where(['parent_node' => $node->id])->all();
                        foreach ($node_mas as $el){
                                $el->parent_node = null;
                                $el->updateAttributes(['parent_node']);
                        }
                        $node -> delete();
                    }
                }
                $data["initial"] = true;
            }

            //удаляем события и механизмы на удаляемом уровне
            $sequence_mas = Sequence::find()->where(['level' => $level_id_on_click])->all();
            foreach ($sequence_mas as $elem){
                $node_mas = Node::find()->where(['parent_node' => $elem->node])->all();
                foreach ($node_mas as $el){
                    $el->parent_node = null;
                    $el->updateAttributes(['parent_node']);
                }
                $node = Node::find()->where(['id' => $elem->node])->one();
                $node -> delete();
            }

            //удаляем уровень с учетом родительского уровня
            if ($level_descendent != null){
                $level_descendent->parent_level = $level->parent_level;
                $level_descendent->updateAttributes(['parent_level']);
                $data["id_level_descendent"] = $level_descendent->id;
            }
            $level -> delete();

            $data["level_count"] = Level::find()->where(['tree_diagram' => $id])->count();
            $data["the_initial_event_is"] = Node::find()->where(['tree_diagram' => $id, 'type' => Node::INITIAL_EVENT_TYPE])->count();
            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionDeleteEvent()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $node_id_on_click = Yii::$app->request->post('node_id_on_click');

            $node_descendent = Node::find()->where(['parent_node' => $node_id_on_click])->all();
            foreach ($node_descendent as $elem){
                $elem->parent_node = null;
                $elem->updateAttributes(['parent_node']);
            }

            $node = Node::find()->where(['id' => $node_id_on_click])->one();
            $data["type"] = $node->type;
            $node -> delete();

            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionDeleteMechanism()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $node_id_on_click = Yii::$app->request->post('node_id_on_click');

            $node_descendent = Node::find()->where(['parent_node' => $node_id_on_click])->all();
            foreach ($node_descendent as $elem){
                $elem->parent_node = null;
                $elem->updateAttributes(['parent_node']);
            }

            $node = Node::find()->where(['id' => $node_id_on_click])->one();
            $node -> delete();

            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }

    public function actionMoveLevel($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            //перемещаемый уровень
            $movable_level = Level::find()->where(['id' => Yii::$app->request->post('level_id_on_click')])->one();
            $parent_level_movable_level = $movable_level->parent_level;//id родительский уровень перемещаемого уровня

            //id уровня местонахождения
            $id_location_level = new Level;
            $id_location_level->name = "Test";
            $id_location_level->tree_diagram = $id;

            if ( $id_location_level->load(Yii::$app->request->post()) && $id_location_level->validate()) {

                // Успешный ввод данных
                $data["success"] = true;

                $level_after_movable_level = null;

                $level_after_location = Level::find()->where(['parent_level' => $id_location_level->movement_level])->one();
                if ($level_after_location != null){
                    $id_level_after_location = $level_after_location->id;
                } else {
                    $id_level_after_location = null;
                }

                if ( $id_level_after_location != $movable_level->id){
                    //меняем parent_level у уровня следующего после перемещаемого (убираем перемещаемый уровень)
                    $level_after_movable = Level::find()->where(['parent_level' => $movable_level->id])->one();
                    if ($level_after_movable != null){
                        $level_after_movable_level = $level_after_movable->id;
                        $level_after_movable->parent_level = $movable_level->parent_level;
                        $level_after_movable->updateAttributes(['parent_level']);
                    }

                    //меняем parent_level у перемещаемого уровня (перемещаем уровень)
                    $movable_level->parent_level = $id_location_level->movement_level;
                    $movable_level->updateAttributes(['parent_level']);

                    //меняем parent_level у уровня следующего после перемещенного (после перемещения )
                    if ($level_after_location != null){
                        $level_after_location->parent_level = $movable_level->id;
                        $level_after_location->updateAttributes(['parent_level']);
                    }
                }


                //Убираем связи элементов у затронутых уровней

                //удаляем связи идущие в узлы на перемещаемом уовне с предыдущего уровня
                $sequence_mas = Sequence::find()->where(['level' => $movable_level->id])->all();//все узлы на перемещаемом уровне
                foreach ($sequence_mas as $elem){
                    $node = Node::find()->where(['id' => $elem->node])->one();

                    //условие проверки что родительский узел находится на предыдущем уровне
                    $sequence = Sequence::find()->where(['node' => $node->parent_node])->one();
                    if ($sequence != null){
                        if ($sequence->level == $parent_level_movable_level){
                            $node->parent_node = null;
                            $node->updateAttributes(['parent_node']);
                        }
                    }
                }

                //удаляем связи идущие в узлы от перемещаемого уовня в следующий
                $sequence_mas = Sequence::find()->where(['level' => $movable_level->id])->all();//все узлы на перемещаемом уровне
                foreach ($sequence_mas as $elem){

                    $node_mas = Node::find()->where(['parent_node' => $elem->node])->all();//все узлы чьи parent_node на перемещаемом уровне
                    foreach ($node_mas as $el){

                        $seq = Sequence::find()->where(['node' => $el->id])->one();
                        if ($seq != null){
                            //исключаем узлы на том же уровне
                            if ($seq->level != $movable_level->id){
                                $el->parent_node = null;
                                $el->updateAttributes(['parent_node']);
                            }
                        }
                    }
                }

                //если уровень до которого нужно разместить существует
                if ($id_level_after_location != null){
                    //удаляем связи идущие в уровень до которого нужно разместить с предыдущего уровня
                    $sequence_mas = Sequence::find()->where(['level' => $id_level_after_location])->all();//все узлы на уровне до которого нужно разместить
                    foreach ($sequence_mas as $elem){
                        $node = Node::find()->where(['id' => $elem->node])->one();

                        //условие проверки что родительский узел находится на предыдущем уровне
                        $sequence = Sequence::find()->where(['node' => $node->parent_node])->one();
                        if ($sequence != null){
                            if ($sequence->level == $id_location_level->movement_level){
                                $node->parent_node = null;
                                $node->updateAttributes(['parent_node']);
                            }
                        }
                    }
                }

                $data["parent_level_movable_level"] = $parent_level_movable_level;//уровень до перемещаемого уровня
                $data["movable_level"] = $movable_level->id;//перемещаемый уровень
                $data["level_after_movable_level"] = $level_after_movable_level;//уровень после перемещаемого уровня
                $data["location_level"] = $id_location_level->movement_level;//уровень после которого нужно разместить
                $data["level_after_location"] =  $id_level_after_location;//уровень до которого нужно разместить

            } else
                $data = ActiveForm::validate($id_location_level);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }

        return false;
    }


    /**
     * Копирование события.
     */
    public function actionCopyEvent()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            //поиск копируемого события
            $event = Node::find()->where(['id' => Yii::$app->request->post('node_id_on_click')])->one();

            // Формирование модели копированного события
            $model = new Node();
            $model->name = $event->name;
            $model->certainty_factor = $event->certainty_factor;
            $model->description = $event->description;
            $model->operator = $event->operator;
            $model->type = Node::EVENT_TYPE;
            $model->tree_diagram = $event->tree_diagram;
            // Присваивает новому Node местопоожение правее копируемого
            $model->indent_x = $event->indent_x + 160;
            $model->indent_y = $event->indent_y;
            $model->level_id = 1;//для обхода обязательности заполнения
            $model->save();

            //поиск связи копируемого события с уровнем для определения уровня
            $sequence = Sequence::find()->where(['node' => $event->id])->one();

            //создаем связь между копированным событием и уровнем
            $new_sequence = new Sequence();
            $new_sequence->tree_diagram = $sequence->tree_diagram;
            $new_sequence->level = $sequence->level;
            $new_sequence->node = $model->id;
            $new_sequence_model_count = Sequence::find()->where(['tree_diagram' => $sequence->tree_diagram])->count();
            $new_sequence->priority = $new_sequence_model_count;
            $new_sequence->save();

            $i = 0;
            //копирование параметров
            $parameters = Parameter::find()->where(['node' => $event->id])->all();
            foreach ($parameters as $p) {
                $new_parameter = new Parameter();
                $new_parameter->name = $p->name;
                $new_parameter->description = $p->description;
                $new_parameter->operator = $p->operator;
                $new_parameter->value = $p->value;
                $new_parameter->node = $model->id;
                $new_parameter->save();

                $data["parameter_id_$i"] = $new_parameter->id;
                $data["parameter_name_$i"] = $new_parameter->name;
                $data["parameter_description_$i"] = $new_parameter->description;
                $data["parameter_operator_$i"] = $new_parameter->operator;
                $data["parameter_operator_name_$i"] = $new_parameter->getOperatorName();
                $data["parameter_value_$i"] = $new_parameter->value;

                $i = $i + 1;
            }

            // Успешный ввод данных
            $data["success"] = true;

            // Формирование данных о новом состоянии
            $data["id"] = $model->id;
            $data["name"] = $model->name;
            $data["certainty_factor"] = $model->certainty_factor;
            $data["description"] = $model->description;
            $data["parent_node"] = $model->parent_node;
            $data["type"] = $model->type;
            $data["indent_x"] = $model->indent_x;
            $data["indent_y"] = $model->indent_y;
            $data["i"] = $i;
            $data["id_level"] = $new_sequence->level;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Копирование события на уровень.
     */
    public function actionCopyEventToLevel()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            //поиск копируемого события
            $event = Node::find()->where(['id' => Yii::$app->request->post('node_id_on_click')])->one();

            // Формирование модели копированного события
            $model = new Node();
            $model->name = $event->name;
            $model->tree_diagram = $event->tree_diagram;

            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->certainty_factor = $event->certainty_factor;
                $model->description = $event->description;
                $model->operator = $event->operator;
                $model->type = Node::EVENT_TYPE;
                $model->indent_x = 20;
                $model->indent_y = 20;
                $model->save();

                //создаем связь между копированным событием и уровнем
                $new_sequence = new Sequence();
                $new_sequence->tree_diagram = $event->tree_diagram;
                $new_sequence->level =  $model->level_id;
                $new_sequence->node = $model->id;
                $new_sequence_model_count = Sequence::find()->where(['tree_diagram' => $event->tree_diagram])->count();
                $new_sequence->priority = $new_sequence_model_count;
                $new_sequence->save();

                $i = 0;
                //копирование параметров
                $parameters = Parameter::find()->where(['node' => $event->id])->all();
                foreach ($parameters as $p) {
                    $new_parameter = new Parameter();
                    $new_parameter->name = $p->name;
                    $new_parameter->description = $p->description;
                    $new_parameter->operator = $p->operator;
                    $new_parameter->value = $p->value;
                    $new_parameter->node = $model->id;
                    $new_parameter->save();

                    $data["parameter_id_$i"] = $new_parameter->id;
                    $data["parameter_name_$i"] = $new_parameter->name;
                    $data["parameter_description_$i"] = $new_parameter->description;
                    $data["parameter_operator_$i"] = $new_parameter->operator;
                    $data["parameter_operator_name_$i"] = $new_parameter->getOperatorName();
                    $data["parameter_value_$i"] = $new_parameter->value;

                    $i = $i + 1;
                }

                // Успешный ввод данных
                $data["success"] = true;

                // Формирование данных о новом состоянии
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["certainty_factor"] = $model->certainty_factor;
                $data["description"] = $model->description;
                $data["parent_node"] = $model->parent_node;
                $data["type"] = $model->type;
                $data["indent_x"] = $model->indent_x;
                $data["indent_y"] = $model->indent_y;
                $data["i"] = $i;
                $data["id_level"] = $new_sequence->level;
            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }



    public function actionAddParameter()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new Parameter();

            $model->node = Yii::$app->request->post('node_id_on_click');

            //поиск количества параметров у выбранного узла
            $parameter_count = Parameter::find()->where(['node' => Yii::$app->request->post('node_id_on_click')])->count();
            $data["parameter_count"] = $parameter_count;

            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового уровня в БД
                $model->save();
                // Формирование данных о новом уровне
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["operator_name"] = $model->getOperatorName();
                $data["operator"] = $model->operator;
                $data["value"] = $model->value;

            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionEditParameter()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Parameter::find()->where(['id' => Yii::$app->request->post('parameter_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;

                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["operator_name"] = $model->getOperatorName();
                $data["operator"] = $model->operator;
                $data["value"] = $model->value;

            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionDeleteParameter()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Parameter::find()->where(['id' => Yii::$app->request->post('parameter_id_on_click')])->one();
            $node_id = $model->node;
            $model -> delete();

            //поиск количества свойст у выбранного состояния
            $parameter_count = Parameter::find()->where(['node' => $node_id])->count();
            $data["parameter_count"] = $parameter_count;
            $data["node"] = $node_id;

            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionCorrectness($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = TreeDiagram::find()->where(['id' => $id])->one();
            $model_diagram = Diagram::find()->where(['id' => $model->diagram])->one();

            //поиск несвязанных элементов
            $not_connected = Node::find()->where(['tree_diagram' => $id, 'parent_node' => null])->andwhere(['!=', 'type', Node::INITIAL_EVENT_TYPE])->all();

            //поиск пустых уровней
            $level = Level::find()->where(['tree_diagram' => $id])->all();
            $sequence = Sequence::find()->where(['tree_diagram' => $id])->all();
            $empty_level = array();//массив пустых уровней
            foreach ($level as $l){
                $del = false;
                foreach ($sequence as $s){
                    if ($s->level == $l->id){
                        $del = true;
                    }
                }
                if ($del == false){
                    array_push($empty_level, $l);
                }
            }

            //поиск уровней где нет механизмов
            $del = false;
            $level_without_mechanism = array();//массив уровней где нет механизмов
            if ($model->mode == TreeDiagram::EXTENDED_TREE_MODE){

                foreach ($level as $l) {
                    $with = false;
                    foreach ($empty_level as $e) {
                        if ($l->id == $e->id) {
                            $with = true;
                        }
                    }

                    if (($l->parent_level != null) &&($with == false)) {
                        $del = true;
                        foreach ($sequence as $s) {
                            if ($s->level == $l->id) {
                                $node = Node::find()->where(['id' => $s->node])->one();
                                if ($node->type == Node::MECHANISM_TYPE) {
                                    $del = false;
                                }
                            }
                        }
                    }

                    if (($del == true) && ($with == false)) {
                        array_push($level_without_mechanism, $l);
                    }
                }
            }

            $data["success"] = true;
            $data["not_connected"] = $not_connected;
            $data["empty_level"] = $empty_level;
            $data["level_without_mechanism"] = $level_without_mechanism;

            //изменение
            if (($not_connected != null) || ($empty_level != null) || ($level_without_mechanism != null)){
                $model_diagram->correctness = Diagram::INCORRECTLY_CORRECT;
                $model_diagram->save();
            } else {
                $model_diagram->correctness = Diagram::CORRECTLY_CORRECT;
                $model_diagram->save();
            }

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionSaveIndent()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $node = Node::find()->where(['id' => Yii::$app->request->post('node_id')])->one();
            $node->indent_x = Yii::$app->request->post('indent_x');
            $node->indent_y = Yii::$app->request->post('indent_y');
            $node->updateAttributes(['indent_x']);
            $node->updateAttributes(['indent_y']);

            $data["indent_x"] = $node->indent_x;
            $data["indent_y"] = $node->indent_y;
            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionAddEventComment()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Node::find()->where(['id' => Yii::$app->request->post('node_id_on_click')])->one();

            $sequence = Sequence::find()->where(['node' => $model->id])->one();

            $model->level_id = $sequence->level;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Формирование данных об измененном событии
                $data["id"] = $model->id;
                $data["comment"] = $model->comment;
                $data["level_id"] = $model->level_id;
            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionEditEventComment()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Node::find()->where(['id' => Yii::$app->request->post('node_id_on_click')])->one();

            $sequence = Sequence::find()->where(['node' => $model->id])->one();

            $model->level_id = $sequence->level;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Формирование данных об измененном событии
                $data["id"] = $model->id;
                $data["comment"] = $model->comment;
            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionDeleteEventComment()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Node::find()->where(['id' => Yii::$app->request->post('node_id_on_click')])->one();

            $model->comment = null;
            $model->updateAttributes(['comment']);
            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionAddLevelComment()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Level::find()->where(['id' => Yii::$app->request->post('level_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Формирование данных об измененном событии
                $data["comment"] = $model->comment;

            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionEditLevelComment()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Level::find()->where(['id' => Yii::$app->request->post('level_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Формирование данных об измененном событии
                $data["comment"] = $model->comment;
            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    public function actionDeleteLevelComment()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Level::find()->where(['id' => Yii::$app->request->post('level_id_on_click')])->one();

            $model->comment = null;
            $model->updateAttributes(['comment']);
            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Загрузка онтологии вформате OWL на сервер во временную папку.
     *
     * @param $id - идентификатор диаграммы дерева событий
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUploadOntology($id)
    {
        // Поиск модели диаграммы дерева событий по id
        $model = $this->findModel($id);
        // Создание формы файла OWL-онтологии
        $owl_file_form = new OWLFileForm(['scenario' => OWLFileForm::UPLOAD_OWL_FILE_SCENARIO]);

        // Если POST-запрос
        if (Yii::$app->request->isPost) {
            $owl_file_form->owl_file = UploadedFile::getInstance($owl_file_form, 'owl_file');
            if ($owl_file_form->validate()) {
                // Временное сохранение загруженного файла онтологии
                $owl_file_form->owl_file->saveAs('uploads/uploaded-ontology.owl');
                // Вывод сообщения об успешной загрузке файла онтологии
                Yii::$app->getSession()->setFlash('success',
                    Yii::t('app', 'TREE_DIAGRAMS_PAGE_MESSAGE_UPLOAD_ONTOLOGY'));

                return $this->redirect(['convert-ontology', 'id' => $model->id]);
            }
        }

        return $this->render('upload-ontology', [
            'model' => $model,
            'owl_file_form' => $owl_file_form
        ]);
    }

    /**
     * Преобразование OWL-онтологии в классическую диаграмму деревьев событий.
     *
     * @param $id - идентификатор диаграммы дерева событий
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionConvertOntology($id)
    {
        // Поиск модели диаграммы дерева событий по id
        $model = $this->findModel($id);
        // Создание формы файла OWL-онтологии
        $owl_file_form = new OWLFileForm();
        // Массив для хранения классов онтологии
        $all_classes = array();
        // Если существует файл с OWL-онтологией
        if (file_exists(Yii::$app->basePath . '/web/uploads/uploaded-ontology.owl')) {
            // Получение XML-строк из OWL-файла онтологии
            $xml_rows = simplexml_load_file('uploads/uploaded-ontology.owl');
            // Создание объекта класса импортера онтологии
            $owl_ontology_importer = new OWLOntologyImporter();
            // Получение всех классов из онтологии
            $all_classes = $owl_ontology_importer->getClasses($xml_rows);
            // POST-запрос и валидация формы
            if ($owl_file_form->load(Yii::$app->request->post()) && $owl_file_form->validate()) {
                // Массив для хранения выбранных пользователем классов
                $selected_classes = array();
                // Обход всех найденных в онтологиии классов
                foreach ($all_classes as $key => $item)
                    if (Yii::$app->request->post('ontology-class-' . $key))
                        // Формирование массива выбранных пользователем классов
                        array_push($selected_classes, $item[0]);
                // Конвертация OWL-онтологии в классическую диаграмму дерева событий
                $owl_ontology_importer->convertOWLOntology($id, $xml_rows, $selected_classes,
                    $owl_file_form->subclass_of, $owl_file_form->object_property);
                // Вывод сообщения об успешном преобразовании онтологии
                Yii::$app->getSession()->setFlash('success',
                    Yii::t('app', 'CONVERT_ONTOLOGY_PAGE_MESSAGE_CONVERTED_ONTOLOGY'));

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('convert-ontology', [
            'model' => $model,
            'owl_file_form' => $owl_file_form,
            'classes' => $all_classes
        ]);
    }
}
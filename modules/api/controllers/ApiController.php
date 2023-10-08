<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\main\models\Diagram;
use app\modules\eete\models\TreeDiagram;
use app\components\StateTransitionXMLGenerator;
use app\components\EventTreeXMLGenerator;
use app\components\StateTransitionXMLImport;
use app\components\EventTreeXMLImport;

class ApiController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }


    /**
     * Возвращение списка всех всех публичных (public) диаграмм деревьев событий созданных в KMS.
     * @return bool|string - возвращает строку с описанием диаграмм деревьев событий,
     * иначе false, если программные компоненты не найдены
     */
    public function actionGetAllEventTreeDiagramsList()
    {
        $list = false;
        // Поиск всех публичных (public) диаграмм деревьев событий (type)
        $diagrams = Diagram::find()->where(['status' => Diagram::PUBLIC_STATUS, 'type' => Diagram::EVENT_TREE_TYPE])->all();
        // Обход диаграмм
        foreach ($diagrams as $diagram){
            $tree_diagram = TreeDiagram::find()->where(['diagram' => $diagram->id])->one();
            // Формирование строки с описанием диаграмм
            $list .= '~id=' .$diagram->id .
                ';name=' . $diagram->name .
                ';description=' . $diagram->description .
                ';type=' . $diagram->type .
                ';correctness=' . $diagram->correctness .
                ';mode=' . $tree_diagram->mode .
                ';author=' . $diagram->author;
        }
        return $list;
    }


    /**
     * Экспорт конкретной публичной (public) диаграммы деревьев событий по id
     * @param $id - идентификатор диаграммы деревьев событий
     * @return string - код сгенерированной диаграммы деревьев событий
     */
    public function actionExportEventTreeDiagram($id)
    {
        //Текущая (выбранная) диаграмма деревьев событий
        $model = Diagram::find()->where(['id' => $id, 'status' => Diagram::PUBLIC_STATUS])->one();
        $tree_diagram = TreeDiagram::find()->where(['diagram' => $model->id])->one();
        //Создание экземпляра класса EventTreeXMLGenerator (генератора кода диаграмм деревьев событий)
        $code_generator = new EventTreeXMLGenerator();
        //Генерация кода диаграммы деревьев событий
        $code_generator->generateEETDXMLCode($tree_diagram->id);
    }


    /**
     * Импорт диаграммы деревьев событий по id.
     * @param $id - идентификатор диаграммы деревьев событий
     * @return bool|string - возвращает текст хода выполнения импорта диаграммы деревьев событий, иначе false
     */
    public function actionImportEventTreeDiagram($id)
    {
        // Переменная для хранения хода выполнения импорта
        $import_progress = false;
        // Если метод запроса POST
        if (Yii::$app->request->isPost) {
            //Текущая (выбранная) диаграмма
            $diagram = Diagram::find()->where(['id' => $id])->one();
            $tree_diagram = TreeDiagram::find()->where(['diagram' => $diagram->id])->one();
            //Получение XML-строк из XML-файла
            $file = simplexml_load_file($_POST['file']);

            if (((string)$file["type"] == "Дерево событий") or ((string)$file["type"] == "Event tree")) {
                $type = Diagram::EVENT_TREE_TYPE;
                // Выявление расширенного или классического дерева
                if (((string)$file["mode"] == "Расширенное дерево") or ((string)$file["mode"] == "Extended tree"))
                    $mode = TreeDiagram::EXTENDED_TREE_MODE;
                if (((string)$file["mode"] == "Классическое дерево") or ((string)$file["mode"] == "Classic tree"))
                    $mode = TreeDiagram::CLASSIC_TREE_MODE;
            }

            // Если тип диаграммы совпадает с типом диаграммы в файле
            if (($diagram->type == $type) and ($tree_diagram->mode == $mode)) {
                //Создание экземпляра класса EventTreeXMLImport
                $generator = new EventTreeXMLImport();
                //Создание диаграммы
                $import_progress = $generator->importXMLCode($tree_diagram->id, $file);
            }
        }
        return $import_progress;
    }


    /**
     * Возвращение списка всех всех публичных (public) диаграмм переходов состояний созданных в KMS.
     * @return bool|string - возвращает строку с описанием диаграмм переходов состояний,
     * иначе false, если программные компоненты не найдены
     */
    public function actionGetAllStateTransitionDiagramsList()
    {
        $list = false;
        // Поиск всех публичных (public) диаграмм переходов состояний (type)
        $diagrams = Diagram::find()->where(['status' => Diagram::PUBLIC_STATUS, 'type' => Diagram::STATE_TRANSITION_DIAGRAM_TYPE])->all();
        // Обход диаграмм
        foreach ($diagrams as $diagram)
            // Формирование строки с описанием диаграмм
            $list .= '~id=' .$diagram->id .
                ';name=' . $diagram->name .
                ';description=' . $diagram->description .
                ';type=' . $diagram->type .
                ';correctness=' . $diagram->correctness .
                ';author=' . $diagram->author;
        return $list;
    }


    /**
     * Экспорт конкретной публичной (public) диаграммы перехода состояний по id
     * @param $id - идентификатор диаграммы перехода состояний
     * @return string - код сгенерированной диаграммы перехода состояний
     */
    public function actionExportStateTransitionDiagram($id)
    {
        //Текущая (выбранная) диаграмма переходов состояний
        $model = Diagram::find()->where(['id' => $id, 'status' => Diagram::PUBLIC_STATUS])->one();
        //Создание экземпляра класса StateTransitionXMLGenerator (генератора кода диаграмм переходов состояний)
        $code_generator = new StateTransitionXMLGenerator();
        //Генерация кода диаграммы переходов состояний
        $code_generator->generateSTDXMLCode($id);
    }


    /**
     * Импорт диаграммы перехода состояний по id.
     * @param $id - идентификатор диаграммы перехода состояний
     * @return bool|string - возвращает текст хода выполнения импорта диаграммы перехода состояний, иначе false
     */
    public function actionImportStateTransitionDiagram($id)
    {
        // Переменная для хранения хода выполнения импорта
        $import_progress = false;
        // Если метод запроса POST
        if (Yii::$app->request->isPost) {
            //Текущая (выбранная) диаграмма
            $diagram = Diagram::find()->where(['id' => $id])->one();
            //Получение XML-строк из XML-файла
            $file = simplexml_load_file($_POST['file']);

            // Определение корректности файла по наличию в нем State (состояний)
            $i = 0;
            foreach($file->State as $state)
                $i++;
            if ($i > 0) {
                $type = Diagram::STATE_TRANSITION_DIAGRAM_TYPE;
            }

            // Если тип диаграммы совпадает с типом диаграммы в файле
            if ($diagram->type == $type) {
                //Создание экземпляра класса StateTransitionXMLImport
                $generator = new StateTransitionXMLImport();
                //Создание диаграммы
                $import_progress = $generator->importXMLCode($id, $file);
            }
        }
        return $import_progress;
    }






}

<?php

namespace app\commands;

use yii\helpers\Console;
use yii\console\Controller;
use app\modules\main\models\User;
use app\modules\main\models\Diagram;
use app\modules\stde\models\State;
use app\modules\stde\models\StateProperty;
use app\modules\stde\models\Transition;
use app\modules\stde\models\TransitionProperty;
use app\modules\stde\models\StartToEnd;
use app\modules\stde\models\StateConnection;

class StateTransitionDiagramController extends Controller
{
    /**
     * Инициализация команд.
     */
    public function actionIndex()
    {
        echo 'yii state-transition-diagram/create' . PHP_EOL;
    }


    /**
     * Команда создания событий по умолчанию.
     */
    public function actionCreate()
    {
        $user = User::find()->where(['username' => 'admin'])->one();
        if ($user != null){

            $diagram = new Diagram();
            if ($diagram->find()->where(['name' => 'Тест'])->count() == 0) {
                $diagram = new Diagram();
                $diagram->name = 'Тест';
                $diagram->description = 'Описание тестовой диаграммы';
                $diagram->type = Diagram::STATE_TRANSITION_DIAGRAM_TYPE;
                $diagram->status = Diagram::PUBLIC_STATUS;
                $diagram->correctness = Diagram::NOT_CHECKED_CORRECT;
                $diagram->author = $user->id;
                $this->log($diagram->save());


                $start = new StartToEnd();
                $start->type = StartToEnd::START_TYPE;
                $start->indent_x = '0';
                $start->indent_y = '100';
                $start->diagram = $diagram->id;
                $this->log($start->save());
                $start0 = $start->id;


                $state = new State();
                $state->name = 'Состояние инициирующее';
                $state->type = State::INITIAL_STATE_TYPE;
                $state->description = 'Описание инициирующего состояния';
                $state->indent_x = '100';
                $state->indent_y = '60';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state0 = $state->id;


                $state_connection = new StateConnection();
                $state_connection->start_to_end = $start0;
                $state_connection->state = $state0;
                $this->log($state_connection->save());


                    $state = new StateProperty();
                    $state->name = 'Свойство 1';
                    $state->description = 'Описание свойства 1';
                    $state->operator = '0';
                    $state->value = '1';
                    $state->state = $state0;
                    $this->log($state->save());

                    $state = new StateProperty();
                    $state->name = 'Свойство 2';
                    $state->description = 'Описание свойства 2';
                    $state->operator = '0';
                    $state->value = '2';
                    $state->state = $state0;
                    $this->log($state->save());


                $state = new State();
                $state->name = 'Состояние 1';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = 'Описание состояния 1';
                $state->indent_x = '350';
                $state->indent_y = '60';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state1 = $state->id;

                    $state = new StateProperty();
                    $state->name = 'Свойство 1';
                    $state->description = 'Описание свойства 1';
                    $state->operator = '0';
                    $state->value = '1';
                    $state->state = $state1;
                    $this->log($state->save());

                    $state = new StateProperty();
                    $state->name = 'Свойство 2';
                    $state->description = 'Описание свойства 2';
                    $state->operator = '0';
                    $state->value = '2';
                    $state->state = $state1;
                    $this->log($state->save());

                $state = new State();
                $state->name = 'Состояние 2';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = 'Описание состояния 2';
                $state->indent_x = '600';
                $state->indent_y = '60';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state2 = $state->id;

                    $state = new StateProperty();
                    $state->name = 'Свойство 1';
                    $state->description = 'Описание свойства 1';
                    $state->operator = '0';
                    $state->value = '1';
                    $state->state = $state2;
                    $this->log($state->save());

                    $state = new StateProperty();
                    $state->name = 'Свойство 2';
                    $state->description = 'Описание свойства 2';
                    $state->operator = '0';
                    $state->value = '2';
                    $state->state = $state2;
                    $this->log($state->save());



                    $transition = new Transition();
                    $transition->name = 'Переход 1';
                    $transition->description = 'Описание перехода 1';
                    $transition->state_from = $state0;
                    $transition->state_to = $state1;
                    $transition->name_property = 'Условие 1';
                    $transition->operator_property = 0;
                    $transition->value_property = '111';
                    $this->log($transition->save());
                    $transition1 = $transition->id;

                        $transition_property = new TransitionProperty();
                        $transition_property->name = 'Условие 1';
                        $transition_property->description = 'Описание условия 1';
                        $transition_property->operator = 0;
                        $transition_property->value = '111';
                        $transition_property->transition = $transition1;
                        $this->log($transition_property->save());

                        $transition_property = new TransitionProperty();
                        $transition_property->name = 'Условие 2';
                        $transition_property->description = 'Описание условия 2';
                        $transition_property->operator = 1;
                        $transition_property->value = '222';
                        $transition_property->transition = $transition1;
                        $this->log($transition_property->save());

                    $transition = new Transition();
                    $transition->name = 'Переход 2';
                    $transition->description = 'Описание перехода 2';
                    $transition->state_from = $state1;
                    $transition->state_to = $state2;
                    $transition->name_property = 'Условие 1';
                    $transition->operator_property = 4;
                    $transition->value_property = '333';
                    $this->log($transition->save());
                    $transition2 = $transition->id;

                        $transition_property = new TransitionProperty();
                        $transition_property->name = 'Условие 1';
                        $transition_property->description = 'Описание условия 1';
                        $transition_property->operator = 4;
                        $transition_property->value = '333';
                        $transition_property->transition = $transition2;
                        $this->log($transition_property->save());

                        $transition_property = new TransitionProperty();
                        $transition_property->name = 'Условие 2';
                        $transition_property->description = 'Описание условия 2';
                        $transition_property->operator = 5;
                        $transition_property->value = '444';
                        $transition_property->transition = $transition2;
                        $this->log($transition_property->save());


                $end = new StartToEnd();
                $end->type = StartToEnd::END_TYPE;
                $end->indent_x = '850';
                $end->indent_y = '100';
                $end->diagram = $diagram->id;
                $this->log($end->save());
                $end0 = $end->id;

                $state_connection = new StateConnection();
                $state_connection->start_to_end = $end0;
                $state_connection->state = $state2;
                $this->log($state_connection->save());

            } else {
                $this->stdout('The diagram is created. - - - - - - - -', Console::FG_GREEN, Console::BOLD);
            }


            $diagram = new Diagram();
            if ($diagram->find()->where(['name' => 'Обслуживание'])->count() == 0) {
                $diagram = new Diagram();
                $diagram->name = 'Обслуживание';
                $diagram->description = '';
                $diagram->type = Diagram::STATE_TRANSITION_DIAGRAM_TYPE;
                $diagram->status = Diagram::PUBLIC_STATUS;
                $diagram->correctness = Diagram::NOT_CHECKED_CORRECT;
                $diagram->author = $user->id;
                $this->log($diagram->save());


                $start = new StartToEnd();
                $start->type = StartToEnd::START_TYPE;
                $start->indent_x = '550';
                $start->indent_y = '0';
                $start->diagram = $diagram->id;
                $this->log($start->save());
                $start0 = $start->id;


                $state = new State();
                $state->name = 'Подсоедините аэродромный источник электрического питания';
                $state->type = State::INITIAL_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '500';
                $state->indent_y = '70';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state0 = $state->id;


                $state_connection = new StateConnection();
                $state_connection->start_to_end = $start0;
                $state_connection->state = $state0;
                $this->log($state_connection->save());


                $state = new State();
                $state->name = 'Выполните включение системы электронной индикации кабины экипажа';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '500';
                $state->indent_y = '270';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state1 = $state->id;

                $state = new State();
                $state->name = 'Выполните проверку уровня масла и осмотр индикатора засорения 
                                                    маслофильтра привода-генератора левого двигателя';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '500';
                $state->indent_y = '450';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state2 = $state->id;

                $state = new State();
                $state->name = 'Выполните заправку маслом привода-генератора левого двигателя';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '250';
                $state->indent_y = '650';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state3 = $state->id;

                $state = new State();
                $state->name = 'Выполните замену маслофильтра привода-генератора';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '750';
                $state->indent_y = '650';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state4 = $state->id;

                $state = new State();
                $state->name = 'Запустите левый двигатель в автоматическом режиме';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '500';
                $state->indent_y = '850';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state5 = $state->id;

                $state = new State();
                $state->name = 'Выполните выключение левого двигателя (штатное)';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '300';
                $state->indent_y = '1050';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state6 = $state->id;

                $state = new State();
                $state->name = 'Выполните выключение левого двигателя (штатное)';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '700';
                $state->indent_y = '1050';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state7 = $state->id;

                $state = new State();
                $state->name = 'Выполните проверку электропроводки';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '300';
                $state->indent_y = '1250';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state8 = $state->id;

                $state = new State();
                $state->name = 'Выполните процедуру сцепления привод-генератора и коробки приводов левого двигателя вытягиванием кольца механизма сцепления валов';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '700';
                $state->indent_y = '1250';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state9 = $state->id;

                $state = new State();
                $state->name = 'Выполните ремонт электропроводки';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '150';
                $state->indent_y = '1450';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state10 = $state->id;

                $state = new State();
                $state->name = 'Замените блок управления генератором левый (6-X242)';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '450';
                $state->indent_y = '1450';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state11 = $state->id;

                $state = new State();
                $state->name = 'Выполните проверку цепей кнопки-табло DRIVE - OFF левой (пульт электроснабжения) на проводимость';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '700';
                $state->indent_y = '1450';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state12 = $state->id;

                $state = new State();
                $state->name = 'Замените привод-генератор левого двигателя';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '300';
                $state->indent_y = '1650';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state13 = $state->id;

                $state = new State();
                $state->name = 'Замените кнопку-табло DRIVE - OFF левую';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '550';
                $state->indent_y = '1650';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state14 = $state->id;

                $state = new State();
                $state->name = 'Замените блок управления генератором левый (6-X242)';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '850';
                $state->indent_y = '1650';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state15 = $state->id;

                $state = new State();
                $state->name = 'Замените центральную часть потолочного пульта (5-F311)';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '550';
                $state->indent_y = '1850';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state16 = $state->id;

                $state = new State();
                $state->name = 'Запустите левый двигатель в автоматическом режиме';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '500';
                $state->indent_y = '2050';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state17 = $state->id;

                $state = new State();
                $state->name = 'Убедитесь, что признаки неисправности отсутствуют';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '500';
                $state->indent_y = '2250';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state18 = $state->id;

                $state = new State();
                $state->name = 'Выполните выключение левого двигателя (штатное)';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '500';
                $state->indent_y = '2450';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state19 = $state->id;

                $state = new State();
                $state->name = 'Выполните выключение системы электронной индикации кабины экипажа';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '500';
                $state->indent_y = '2650';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state20 = $state->id;

                $state = new State();
                $state->name = 'Отсоедините аэродромный источник электрического питания';
                $state->type = State::COMMON_STATE_TYPE;
                $state->description = '';
                $state->indent_x = '500';
                $state->indent_y = '2850';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state21 = $state->id;


                $end = new StartToEnd();
                $end->type = StartToEnd::END_TYPE;
                $end->indent_x = '550';
                $end->indent_y = '3050';
                $end->diagram = $diagram->id;
                $this->log($end->save());
                $end0 = $end->id;

                $state_connection = new StateConnection();
                $state_connection->start_to_end = $end0;
                $state_connection->state = $state21;
                $this->log($state_connection->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state0;
                    $transition->state_to = $state1;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state1;
                    $transition->state_to = $state2;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Если уровень масла ниже нормы';
                    $transition->description = '';
                    $transition->state_from = $state2;
                    $transition->state_to = $state3;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Уровень масла';
                    $transition_property->description = '';
                    $transition_property->operator = 2;
                    $transition_property->value = 'нормы';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Если индикатор засорения маслофильтра привода-генератора находится в выдвинутом положении';
                    $transition->description = '';
                    $transition->state_from = $state2;
                    $transition->state_to = $state4;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Индикатор засорения маслофильтра привода-генератора';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выдвинутое положение';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state3;
                    $transition->state_to = $state5;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state4;
                    $transition->state_to = $state5;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Если обнаружены признаки неисправности пункта 1';
                    $transition->description = '';
                    $transition->state_from = $state5;
                    $transition->state_to = $state6;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Обнаружены признаки неисправности';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'пункта 1';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Если обнаружены признаки неисправности пункт 2';
                    $transition->description = '';
                    $transition->state_from = $state5;
                    $transition->state_to = $state7;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Обнаружены признаки неисправности';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'пункта 2';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state6;
                    $transition->state_to = $state8;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state7;
                    $transition->state_to = $state9;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Если есть неисправность в электропроводке';
                    $transition->description = '';
                    $transition->state_from = $state8;
                    $transition->state_to = $state10;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Неисправность';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'в электропроводке';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Если нет неисправности в электропроводке';
                    $transition->description = '';
                    $transition->state_from = $state8;
                    $transition->state_to = $state11;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Неисправность';
                    $transition_property->description = '';
                    $transition_property->operator = 5;
                    $transition_property->value = 'в электропроводке';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Если неисправность не устранена';
                    $transition->description = '';
                    $transition->state_from = $state9;
                    $transition->state_to = $state12;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Неисправность';
                    $transition_property->description = '';
                    $transition_property->operator = 5;
                    $transition_property->value = 'устранена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Если неисправность не устранена';
                    $transition->description = '';
                    $transition->state_from = $state10;
                    $transition->state_to = $state13;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Неисправность';
                    $transition_property->description = '';
                    $transition_property->operator = 5;
                    $transition_property->value = 'устранена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Если неисправность не устранена';
                    $transition->description = '';
                    $transition->state_from = $state11;
                    $transition->state_to = $state13;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Неисправность';
                    $transition_property->description = '';
                    $transition_property->operator = 5;
                    $transition_property->value = 'устранена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Если оби цепи замкнуты';
                    $transition->description = '';
                    $transition->state_from = $state12;
                    $transition->state_to = $state14;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Оби цепи';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'замкнуты';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Если оби цепи замкнуты';
                    $transition->description = '';
                    $transition->state_from = $state12;
                    $transition->state_to = $state15;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Оби цепи';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'замкнуты';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Если неисправность не устранена';
                    $transition->description = '';
                    $transition->state_from = $state14;
                    $transition->state_to = $state16;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Неисправность';
                    $transition_property->description = '';
                    $transition_property->operator = 5;
                    $transition_property->value = 'устранена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state13;
                    $transition->state_to = $state17;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state16;
                    $transition->state_to = $state17;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state15;
                    $transition->state_to = $state17;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state17;
                    $transition->state_to = $state18;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state18;
                    $transition->state_to = $state19;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state19;
                    $transition->state_to = $state20;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());


                    $transition = new Transition();
                    $transition->name = 'Работа выполнена';
                    $transition->description = '';
                    $transition->state_from = $state20;
                    $transition->state_to = $state21;
                    $transition->name_property = 'не важно';
                    $transition->operator_property = 0;
                    $transition->value_property = 'не важно';
                    $this->log($transition->save());

                    $transition_property = new TransitionProperty();
                    $transition_property->name = 'Работа';
                    $transition_property->description = '';
                    $transition_property->operator = 0;
                    $transition_property->value = 'выполнена';
                    $transition_property->transition = $transition->id;
                    $this->log($transition_property->save());





            } else {
                $this->stdout('The diagram is created. - - - - - - - -', Console::FG_GREEN, Console::BOLD);
            }



        } else
            $this->stdout('Create a user "admin"', Console::FG_GREEN, Console::BOLD);
    }


    /**
     * Вывод сообщений на экран (консоль)
     * @param bool $success
     */
    private function log($success)
    {
        if ($success) {
            $this->stdout('Success!', Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stderr('Error!', Console::FG_RED, Console::BOLD);
        }
        echo PHP_EOL;
    }

}
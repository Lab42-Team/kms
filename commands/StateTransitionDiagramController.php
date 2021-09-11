<?php

namespace app\commands;

use yii\helpers\Console;
use yii\console\Controller;
use app\modules\main\models\User;
use app\modules\main\models\Diagram;
use app\modules\stde\models\State;
use app\modules\stde\models\Transition;

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

                $state = new State();
                $state->name = 'Состояние инициирующее';
                $state->type = State::INITIAL_STATE_TYPE;
                $state->description = 'Описание инициирующего состояния';
                $state->indent_x = '10';
                $state->indent_y = '60';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state0 = $state->id;

                $state = new State();
                $state->name = 'Состояние 1';
                $state->type = State::INITIAL_STATE_TYPE;
                $state->description = 'Описание состояния 1';
                $state->indent_x = '60';
                $state->indent_y = '60';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state1 = $state->id;

                $state = new State();
                $state->name = 'Состояние 2';
                $state->type = State::INITIAL_STATE_TYPE;
                $state->description = 'Описание состояния 2';
                $state->indent_x = '110';
                $state->indent_y = '60';
                $state->diagram = $diagram->id;
                $this->log($state->save());
                $state2 = $state->id;

                $transition = new Transition();
                $transition->name = 'Переход 1';
                $transition->description = 'Описание перехода 1';
                $transition->state_from = $state0;
                $transition->state_to = $state1;
                $this->log($transition->save());

                $transition = new Transition();
                $transition->name = 'Переход 2';
                $transition->description = 'Описание перехода 2';
                $transition->state_from = $state1;
                $transition->state_to = $state2;
                $this->log($transition->save());

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
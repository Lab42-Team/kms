<?php

return [
    /* Текст на главной странице */
    'WELCOME_TO_KMS' => 'Добро пожаловать в Knowledge Modeling System!',
    'KMS_NAME' => 'Knowledge Modeling System (KMS)',
    'KMS_DEFINITION' =>  '&mdash; это ресурс, предлагающий возможность визуального моделирования предметных знаний.',
    'DIAGRAM_TYPES' => 'Визуальное моделирование знаний может быть осуществлено с использованием:',
    'FIRST_TYPE' => 'классических и расширенных деревьев событий и отказов',
    'SECOND_TYPE' => 'диаграмм переходов состояний',

    'EVENT_TREE_NAME' => 'Дерево событий (Event Tree, ET)',
    'EVENT_TREE_DEFINITION' => '&mdash; алгоритм моделирования событий, исходящих от некого основного (корневого) события. Деревья событий используется для определения и анализа последовательности (вариантов) развития событий (например, аварийных ситуаций), включающей сложные взаимодействия между техническими системами обеспечения безопасности. При его построении используется прямая логика. В общем случае данный метод можно использовать и для анализа отказов, аварий и чрезвычайных ситуаций, где в качестве основного события рассматривается исходное состояние, т.е. состояние технического объекта в момент начала его эксплуатации.',
    'ADVANCED_EVENT_TREE_DEFINITION' => 'Нашей исследовательской группой предложено расширить существующую модель деревьев событий и визуальную нотацию их представления для получения более полной информации об исследуемых процессах развития отказов и аварий. В частности, на основании результатов системного анализа проблемы исследования динамики технического состояния механической системы выделены стадии развития обозначенных процессов (субмикроуровень, микроуровень, мезоуровень, макроуровень) и элементы их описания (механизм и кинетика). В свою очередь кинетика, рассматриваемая как последовательность событий, должна быть детализирована описанием параметров (характеристик) событий. В результате в обобщенном виде получен шаблон дерева, описывающий стадии, последовательность событий (кинетика) и механизмы их возникновения.',
    'EVENT_TREE_CREATION' => 'Построение классических и расширенных деревьев реализуется в редакторе &mdash;',
    'EET_EDITOR' => 'Extended Event Tree Editor (EETE)',

    'STATE_TRANSITION_DIAGRAM_NAME' => 'Диаграмма переходов состояний (State Transition Diagram, STD)',
    'STATE_TRANSITION_DIAGRAM_DEFINITION' => '&mdash; графическая форма представления конечного автомата, представимого в виде графа, позволяющая описывать состояния объекта и их изменения, которые в совокупности характеризуют его поведение. Основное отличие от предыдущей модели заключается в возможности создания циклов.',
    'STATE_TRANSITION_DIAGRAM_CREATION' => 'Построение диаграмм переходов состояний реализуется в редакторе &mdash;',
    'STD_EDITOR' => 'State Transition Diagram Editor (STDE)',

    'YOU_CAN_SEE_THE_CREATED' => 'Вы можете посмотреть список созданных ранее ',
    'DIAGRAMS' => 'диаграмм',
    'WARNING_FOR_DIAGRAM_CREATION' => 'Построение диаграмм доступно только авторизованным пользователям!',
    'TO_CREATE_DIAGRAM' => 'Для создания новой диаграммы ',
    'SIGN_IN' => 'войдите в систему',
    'YOU_CAN_CREATE' => 'Вы можете создать ',
    'DIAGRAM' => 'новую диаграмму',

    /* Пункты главного меню */
    'NAV_HOME' => 'Главная',
    'NAV_ACCOUNT' => 'Учётная запись',
    'NAV_SIGNED_IN_AS' => 'Вы вошли как',
    'NAV_PROFILE' => 'Профиль',
    'NAV_HELP' => 'Помощь',
    'NAV_CONTACT_US' => 'Обратная связь',
    'NAV_SIGN_UP' => 'Регистрация',
    'NAV_SIGN_IN' => 'Вход',
    'NAV_SIGN_OUT' => 'Выход',
    'NAV_DIAGRAMS' => 'Диаграммы',
    'NAV_DIAGRAM' => 'Диаграмма',
    'NAV_BACK_LIST' => 'Вернуться к списку',
    'NAV_USERS' => 'Пользователи',

    'NAV_ADD' => 'Добавить',
    'NAV_ADD_LEVEL' => 'Уровень',
    'NAV_ADD_EVENT' => 'Событие',
    'NAV_ADD_MECHANISM' => 'Механизм',
    'NAV_ADD_STATE' => 'Состояние',

    'NAV_IMPORT' => 'Импортировать',
    'NAV_EXPORT' => 'Экспортировать',
    'NAV_VERIFY' => 'Проверить',
    'NAV_ALIGNMENT' => 'Выравнивание',

    /* Пункты правого меню */
    'SIDE_NAV_POSSIBLE_ACTIONS' => 'Возможные действия',

    /* Нижний колонтитул (подвал) */
    'FOOTER_INSTITUTE'=>'ИДСТУ СО РАН',
    'FOOTER_POWERED_BY' => 'Разработано',

    /* Общие кнопки */
    'BUTTON_OK' => 'Ok',
    'BUTTON_ADD' => 'Добавить',
    'BUTTON_SEND' => 'Отправить',
    'BUTTON_SAVE' => 'Сохранить',
    'BUTTON_SIGN_UP' => 'Зарегистрироваться',
    'BUTTON_SIGN_IN' => 'Войти',
    'BUTTON_CREATE' => 'Создать',
    'BUTTON_UPDATE' => 'Обновить',
    'BUTTON_EDIT' => 'Изменить',
    'BUTTON_DELETE' => 'Удалить',
    'BUTTON_CANCEL' => 'Отмена',
    'BUTTON_HIDE' => 'Скрыть',
    'BUTTON_IMPORT' => 'Импортировать',
    'BUTTON_EXPORT' => 'Экспортировать',
    'BUTTON_RETURN' => 'Вернуться к',
    'BUTTON_CONNECTION' => 'Соединение',
    'BUTTON_OPEN_DIAGRAM' => 'Открыть диаграмму',
    'BUTTON_MOVE' => 'Переместить',
    'BUTTON_COMMENT' => 'Комментарий',
    'BUTTON_UPLOAD' => 'Загрузить',
    'BUTTON_UPLOAD_ONTOLOGY' => 'Загрузить онтологию',
    'BUTTON_CONVERT' => 'Преобразовать',

    /* Общие сообщения об ошибках */
    'ERROR_MESSAGE_PAGE_NOT_FOUND' => 'Страница не найдена.',
    'ERROR_MESSAGE_ACCESS_DENIED' => 'Вам не разрешено производить данное действие.',

    /* Общие уведомления на форме с captcha */
    'CAPTCHA_NOTICE_ONE' => 'Пожалуйста, введите буквы, показанные на картинке выше.',
    'CAPTCHA_NOTICE_TWO' => 'Буквы вводятся без учета регистра.',
    'CAPTCHA_NOTICE_THREE' => 'Для смены проверочного кода нажмите на буквы, показанные на картинке выше.',

    /* Общие заголовки сообщений */
    'WARNING' => 'Предупреждение!',
    'NOTICE_TITLE' => 'Обратите внимание',
    'NOTICE_TEXT' => 'на эту важную информацию.',

    /* Страницы сайта */
    /* Страница администрирования пользователей */
    'USERS_PAGE_USER' => 'Пользователь',
    'USERS_PAGE_USERS' => 'Пользователи',
    'USERS_PAGE_CREATE_USER' => 'Создать пользователя',
    'USERS_PAGE_VIEW_USER' => 'Просмотр пользователя',
    'USERS_PAGE_UPDATE_USER_INFORMATION' => 'Обновить данные пользователя',
    'USERS_PAGE_DELETE_USER' => 'Удалить пользователя',
    'USERS_PAGE_MODAL_FORM_TEXT' => 'Вы уверены, что хотите удалить данного пользователя?',
    /* Сообщения на страницах администрирования пользователей */
    'USERS_PAGE_MESSAGE_ADD_NEW_USER' => 'Вы успешно добавили нового пользователя.',
    'USERS_PAGE_MESSAGE_UPDATED_USER_INFORMATION' => 'Вы успешно обновили данные пользователя.',
    'USERS_PAGE_MESSAGE_NOT_DELETE_USER' => 'Вы не можете удалить себя.',
    'USERS_PAGE_MESSAGE_DELETED_USER' => 'Вы успешно удалили пользователя.',
    'USERS_PAGE_MESSAGE_UPDATED_YOUR_ACCOUNT_INFORMATION' => 'Вы успешно обновили данные своего аккаунта.',
    'USERS_PAGE_MESSAGE_UPDATED_YOUR_PASSWORD' => 'Вы успешно поменяли пароль.',
    /* Страница профиля пользователя */
    'USER_PAGE_PROFILE' => 'Профиль',
    'USER_PAGE_UPDATE_PROFILE' => 'Обновить профиль',
    'USER_PAGE_UPDATE_ACCOUNT_INFORMATION' => 'Обновить учетные данные',
    'USER_PAGE_UPDATE_PASSWORD' => 'Поменять пароль',

    /* Страница диаграмм */
    'DIAGRAMS_PAGE_DIAGRAM' => 'Диаграмма',
    'DIAGRAMS_PAGE_DIAGRAMS' => 'Диаграммы',
    'DIAGRAMS_PAGE_CREATE_DIAGRAM' => 'Создать диаграмму',
    'DIAGRAMS_PAGE_VIEW_DIAGRAM' => 'Просмотр диаграммы',
    'DIAGRAMS_PAGE_UPDATE_DIAGRAM' => 'Изменить диаграмму',
    'DIAGRAMS_PAGE_DELETE_DIAGRAM' => 'Удалить диаграмму',
    'DIAGRAMS_PAGE_VISUAL_DIAGRAM' => 'Визуальная диаграмма',
    'DIAGRAMS_PAGE_IMPORT_DIAGRAM' => 'Импортирование визуальной диаграммы',
    'DIAGRAMS_PAGE_MODAL_FORM_TEXT' => 'Вы уверены, что хотите удалить данную диаграмму?',
    'DIAGRAMS_PAGE_UPLOAD_ONTOLOGY' => 'Загрузить онтологию',
    'DIAGRAMS_PAGE_CONVERT_ONTOLOGY' => 'Преобразование онтологии',
    /* Сообщения на страницах администрирования диаграмм */
    'DIAGRAMS_PAGE_MESSAGE_CREATE_DIAGRAM' => 'Вы успешно создали новую диаграмму.',
    'DIAGRAMS_PAGE_MESSAGE_UPDATED_DIAGRAM' => 'Вы успешно обновили данную диаграмму.',
    'DIAGRAMS_PAGE_MESSAGE_DELETED_DIAGRAM' => 'Вы успешно удалили диаграмму.',
    'DIAGRAMS_PAGE_MESSAGE_IMPORT_DIAGRAM' => 'Вы успешно импортировали диаграмму.',
    'DIAGRAMS_PAGE_MESSAGE_UPLOAD_ONTOLOGY' => 'Вы успешно загрузили файл OWL-онтологии.',

    /* Страница диаграммы деревьев событий */
    'TREE_DIAGRAMS_PAGE_CREATE_EVENT_TREE_FROM_TEMPLATE' => 'Создать дерево событий по шаблону',
    /* Дерево событий созданного из шаблона */
    'TREE_DIAGRAMS_CREATED_FROM' => 'Диаграмма созданная из ',
    'TEMPLATES_DIAGRAMS_NOT_FOUND' => 'Шаблоны диаграмм не найдены',

    /* Страница импорта онтологии */
    'CONVERT_ONTOLOGY_PAGE_RELATIONSHIP_INTERPRETATION' => 'Интерпретировать как связь событий',
    'CONVERT_ONTOLOGY_PAGE_CLASS_LIST' => 'Список классов онтологии',
    'CONVERT_ONTOLOGY_PAGE_SELECT_ALL_CLASSES' => 'Выбрать все классы',
    /* Сообщения на странице импорта онтологии */
    'CONVERT_ONTOLOGY_PAGE_MESSAGE_CONVERTED_ONTOLOGY' => 'Вы успешно преобразовали OWL-онтологию в диаграмму дерева событий.',

    /* Страница ошибки */
    'ERROR_PAGE_TEXT_ONE' => 'Вышеупомянутая ошибка произошла при обработке веб-сервером вашего запроса.',
    'ERROR_PAGE_TEXT_TWO' => 'Пожалуйста, свяжитесь с нами, если Вы думаете, что это ошибка сервера. Спасибо.',
    /* Страница обратной связи */
    'CONTACT_US_PAGE_TITLE' => 'Обратная связь',
    'CONTACT_US_PAGE_TEXT' => 'Если у вас есть деловое предложение или другие вопросы, пожалуйста,
        заполните следующую форму, чтобы связаться с нами. Спасибо.',
    'CONTACT_US_PAGE_SUCCESS_MESSAGE' => 'Благодарим Вас за обращение к нам. Мы ответим вам как можно скорее.',
    /* Страница входа */
    'SIGN_IN_PAGE_TITLE' => 'Вход',
    'SIGN_IN_PAGE_TEXT' => 'Пожалуйста, заполните следующие поля для входа:',
    'SIGN_IN_PAGE_RESET_TEXT' => 'Если Вы забыли свой пароль, то Вы можете',
    'SIGN_IN_PAGE_RESET_LINK' => 'сбросить его',

    /* Формы */
    /* ContactForm */
    'CONTACT_FORM_NAME' => 'ФИО',
    'CONTACT_FORM_EMAIL' => 'Электронная почта',
    'CONTACT_FORM_SUBJECT' => 'Тема',
    'CONTACT_FORM_BODY' => 'Сообщение',
    'CONTACT_FORM_VERIFICATION_CODE' => 'Проверочный код',
    /* LoginForm */
    'LOGIN_FORM_USERNAME' => 'Имя пользователя',
    'LOGIN_FORM_PASSWORD' => 'Пароль',
    'LOGIN_FORM_REMEMBER_ME' => 'Запомнить меня',
    /* Сообщения LoginForm */
    'LOGIN_FORM_MESSAGE_INCORRECT_USERNAME_OR_PASSWORD' => 'Неверное имя пользователя или пароль.',
    'LOGIN_FORM_MESSAGE_BLOCKED_ACCOUNT' => 'Ваш аккаунт заблокирован.',
    'LOGIN_FORM_MESSAGE_NOT_CONFIRMED_ACCOUNT' => 'Ваш аккаунт не подтвержден.',
    /* OWLFileForm */
    'OWL_FILE_FORM_OWL_FILE' => 'Файл онтологии в формате OWL',
    'OWL_FILE_FORM_SUBCLASS_OF' => 'Отношение наследования (класс-подкласс)',
    'OWL_FILE_FORM_OBJECT_PROPERTY' => 'Отношение между классами (объектные свойства)',

    /* Модели */
    /* Lang */
    'LANG_MODEL_ID' => 'ID',
    'LANG_MODEL_CREATED_AT' => 'Создан',
    'LANG_MODEL_UPDATED_AT' => 'Обновлен',
    'LANG_MODEL_URL' => 'URL',
    'LANG_MODEL_LOCAL' => 'Локаль',
    'LANG_MODEL_NAME' => 'Название',
    'LANG_MODEL_DEFAULT' => 'Язык по умолчанию',

    /* User */
    'USER_MODEL_ID' => 'ID',
    'USER_MODEL_CREATED_AT' => 'Зарегистрирован',
    'USER_MODEL_UPDATED_AT' => 'Обновлен',
    'USER_MODEL_USERNAME' => 'Логин',
    'USER_MODEL_PASSWORD' => 'Пароль',
    'USER_MODEL_AUTH_KEY' => 'Ключ аутентификации',
    'USER_MODEL_EMAIL_CONFIRM_TOKEN' => 'Метка подтверждения электронной почты',
    'USER_MODEL_PASSWORD_HASH' => 'Хэш пароля',
    'USER_MODEL_PASSWORD_RESET_TOKEN' => 'Метка сброса пароля',
    'USER_MODEL_ROLE' => 'Роль',
    'USER_MODEL_STATUS' => 'Статус',
    'USER_MODEL_FULL_NAME' => 'Фамилия Имя Отчество',
    'USER_MODEL_EMAIL' => 'Электронная почта',
    /* Сообщения модели User */
    'USER_MODEL_MESSAGE_USERNAME' => 'Это имя пользователя уже занято.',
    'USER_MODEL_MESSAGE_UPDATED_YOUR_DETAILS' => 'Вы успешно изменили свои данные.',
    'USER_MODEL_MESSAGE_UPDATED_YOUR_PASSWORD' => 'Вы успешно изменили пароль.',

    /* Diagram */
    'DIAGRAM_MODEL_ID' => 'ID',
    'DIAGRAM_MODEL_CREATED_AT' => 'Создана',
    'DIAGRAM_MODEL_UPDATED_AT' => 'Обновлена',
    'DIAGRAM_MODEL_NAME' => 'Название',
    'DIAGRAM_MODEL_DESCRIPTION' => 'Описание',
    'DIAGRAM_MODEL_TYPE' => 'Тип',
    'DIAGRAM_MODEL_STATUS' => 'Статус',
    'DIAGRAM_MODEL_CORRECTNESS' => 'Корректность',
    'DIAGRAM_MODEL_AUTHOR' => 'Автор',
    /* Значения полей типов диаграмм */
    'DIAGRAM_MODEL_EVENT_TREE_TYPE' => 'Дерево событий',
    'DIAGRAM_MODEL_STATE_TRANSITION_DIAGRAM_TYPE' => 'Диаграмма переходов состояний',
    /* Значения полей статусов */
    'DIAGRAM_MODEL_PUBLIC_STATUS' => 'Публичный',
    'DIAGRAM_MODEL_PRIVATE_STATUS' => 'Частный',
    /* Значения корректности диаграммы */
    'DIAGRAM_MODEL_NOT_CHECKED_CORRECT' => 'Не проверялась',
    'DIAGRAM_MODEL_CORRECTLY_CORRECT' => 'Корректно',
    'DIAGRAM_MODEL_INCORRECTLY_CORRECT' => 'Некорректно',

    /* TreeDiagram */
    'TREE_DIAGRAM_MODEL_ID' => 'ID',
    'TREE_DIAGRAM_MODEL_CREATED_AT' => 'Создана',
    'TREE_DIAGRAM_MODEL_UPDATED_AT' => 'Обновлена',
    'TREE_DIAGRAM_MODEL_MODE' => 'Режим',
    'TREE_DIAGRAM_MODEL_TREE_VIEW' => 'Вид дерева',
    'TREE_DIAGRAM_MODEL_DIAGRAM' => 'Диаграмма',
    /* Значения режимов деревьев диаграмм */
    'TREE_DIAGRAM_MODEL_EXTENDED_TREE_MODE' => 'Расширенное дерево',
    'TREE_DIAGRAM_MODEL_CLASSIC_TREE_MODE' => 'Классическое дерево',
    /* Значения вида дерева диаграмм */
    'TREE_DIAGRAM_MODEL_ORDINARY_TREE_VIEW' => 'Обычное дерево',
    'TREE_DIAGRAM_MODEL_TEMPLATE_TREE_VIEW' => 'Шаблонное дерево',

    /* Node */
    'NODE_MODEL_ID' => 'ID',
    'NODE_MODEL_CREATED_AT' => 'Создан',
    'NODE_MODEL_UPDATED_AT' => 'Обновлен',
    'NODE_MODEL_NAME' => 'Название',
    'NODE_MODEL_CERTAINTY_FACTOR' => 'Коэффициент уверенности',
    'NODE_MODEL_DESCRIPTION' => 'Описание',
    'NODE_MODEL_OPERATOR' => 'Оператор',
    'NODE_MODEL_TYPE' => 'Тип',
    'NODE_MODEL_PARENT_NODE' => 'Родительский узел',
    'NODE_MODEL_TREE_DIAGRAM' => 'Диаграмма',
    'NODE_MODEL_LEVEL_ID' => 'Название уровня',
    'NODE_MODEL_COMMENT' => 'Комментарий',
    /* Значения операторов */
    'NODE_MODEL_NOT_OPERATOR' => 'Отрицание',
    'NODE_MODEL_AND_OPERATOR' => 'И',
    'NODE_MODEL_OR_OPERATOR' => 'Или',
    'NODE_MODEL_XOR_OPERATOR' => 'Сложение по модулю 2',
    /* Значения типов узлов */
    'TREE_DIAGRAM_MODEL_INITIAL_EVENT_TYPE' => 'Инициирующее событие',
    'TREE_DIAGRAM_MODEL_EVENT_TYPE' => 'Событие',
    'TREE_DIAGRAM_MODEL_MECHANISM_TYPE' => 'Механизм',

    /* Parameter */
    'PARAMETER_MODEL_ID' => 'ID',
    'PARAMETER_MODEL_CREATED_AT' => 'Создан',
    'PARAMETER_MODEL_UPDATED_AT' => 'Обновлен',
    'PARAMETER_MODEL_NAME' => 'Название',
    'PARAMETER_MODEL_DESCRIPTION' => 'Описание',
    'PARAMETER_MODEL_OPERATOR' => 'Оператор',
    'PARAMETER_MODEL_VALUE' => 'Значение',
    'PARAMETER_MODEL_NODE' => 'Узел',
    /* Значения операторов */
    'PARAMETER_MODEL_EQUALLY_OPERATOR' => '=',
    'PARAMETER_MODEL_MORE_OPERATOR' => '>',
    'PARAMETER_MODEL_LESS_OPERATOR' => '<',
    'PARAMETER_MODEL_MORE_EQUAL_OPERATOR' => '>=',
    'PARAMETER_MODEL_LESS_EQUAL_OPERATOR' => '<=',
    'PARAMETER_MODEL_NOT_EQUAL_OPERATOR' => '≠',
    'PARAMETER_MODEL_APPROXIMATELY_EQUAL_OPERATOR' => '≈',

    /* Level */
    'LEVEL_MODEL_ID' => 'ID',
    'LEVEL_MODEL_CREATED_AT' => 'Создан',
    'LEVEL_MODEL_UPDATED_AT' => 'Обновлен',
    'LEVEL_MODEL_NAME' => 'Название',
    'LEVEL_MODEL_DESCRIPTION' => 'Описание',
    'LEVEL_MODEL_PARENT_LEVEL' => 'Родительский уровень',
    'LEVEL_MODEL_TREE_DIAGRAM' => 'Диаграмма',
    'LEVEL_MODEL_MOVEMENT_LEVEL' => 'Уровень переместить после:',
    'LEVEL_MODEL_COMMENT' => 'Комментарий',

    /* Sequence */
    'SEQUENCE_MODEL_ID' => 'ID',
    'SEQUENCE_MODEL_CREATED_AT' => 'Создан',
    'SEQUENCE_MODEL_UPDATED_AT' => 'Обновлен',
    'SEQUENCE_MODEL_TREE_DIAGRAM' => 'Диаграмма',
    'SEQUENCE_MODEL_LEVEL' => 'Уровень',
    'SEQUENCE_MODEL_NODE' => 'Узел',
    'SEQUENCE_MODEL_PRIORITY' => 'Приоритет',

    /* State */
    'STATE_MODEL_ID' => 'ID',
    'STATE_MODEL_CREATED_AT' => 'Создано',
    'STATE_MODEL_UPDATED_AT' => 'Обновлено',
    'STATE_MODEL_NAME' => 'Название',
    'STATE_MODEL_TYPE' => 'Тип',
    'STATE_MODEL_DESCRIPTION' => 'Описание',
    'STATE_MODEL_INDENT_X' => 'Отступ по X',
    'STATE_MODEL_INDENT_Y' => 'Отступ по Y',
    'STATE_MODEL_DIAGRAM' => 'Диаграмма',
    /* Значения типов состояний */
    'STATE_MODEL_INITIAL_STATE_TYPE' => 'Начальное состояние',
    'STATE_MODEL_COMMON_STATE_TYPE' => 'Состояние',

    /* StateProperty */
    'STATE_PROPERTY_MODEL_ID' => 'ID',
    'STATE_PROPERTY_MODEL_CREATED_AT' => 'Создано',
    'STATE_PROPERTY_MODEL_UPDATED_AT' => 'Обновлено',
    'STATE_PROPERTY_MODEL_NAME' => 'Название',
    'STATE_PROPERTY_MODEL_DESCRIPTION' => 'Описание',
    'STATE_PROPERTY_MODEL_OPERATOR' => 'Оператор',
    'STATE_PROPERTY_MODEL_VALUE' => 'Значение',
    'STATE_PROPERTY_MODEL_STATE' => 'Состояние',
    /* Значения операторов */
    'STATE_PROPERTY_MODEL_EQUALLY_OPERATOR' => '=',
    'STATE_PROPERTY_MODEL_MORE_OPERATOR' => '>',
    'STATE_PROPERTY_MODEL_LESS_OPERATOR' => '<',
    'STATE_PROPERTY_MODEL_MORE_EQUAL_OPERATOR' => '>=',
    'STATE_PROPERTY_MODEL_LESS_EQUAL_OPERATOR' => '<=',
    'STATE_PROPERTY_MODEL_NOT_EQUAL_OPERATOR' => '≠',
    'STATE_PROPERTY_MODEL_APPROXIMATELY_EQUAL_OPERATOR' => '≈',

    /* Transition */
    'TRANSITION_MODEL_ID' => 'ID',
    'TRANSITION_MODEL_CREATED_AT' => 'Создан',
    'TRANSITION_MODEL_UPDATED_AT' => 'Обновлен',
    'TRANSITION_MODEL_NAME' => 'Название',
    'TRANSITION_MODEL_DESCRIPTION' => 'Описание',
    'TRANSITION_MODEL_STATE_FROM' => 'Состояние из',
    'TRANSITION_MODEL_STATE_TO' => 'Состояние в',
    'TRANSITION_MODEL_NAME_PROPERTY' => 'Название условия',
    'TRANSITION_MODEL_DESCRIPTION_PROPERTY' => 'Описание условия',
    'TRANSITION_MODEL_OPERATOR_PROPERTY' => 'Оператор условия',
    'TRANSITION_MODEL_VALUE_PROPERTY' => 'Значение условия',

    /* TransitionProperty */
    'TRANSITION_PROPERTY_MODEL_ID' => 'ID',
    'TRANSITION_PROPERTY_MODEL_CREATED_AT' => 'Создано',
    'TRANSITION_PROPERTY_MODEL_UPDATED_AT' => 'Обновлено',
    'TRANSITION_PROPERTY_MODEL_NAME' => 'Название',
    'TRANSITION_PROPERTY_MODEL_DESCRIPTION' => 'Описание',
    'TRANSITION_PROPERTY_MODEL_OPERATOR' => 'Оператор',
    'TRANSITION_PROPERTY_MODEL_VALUE' => 'Значение',
    'TRANSITION_PROPERTY_MODEL_TRANSITION' => 'Переход',
    /* Значения операторов */
    'TRANSITION_PROPERTY_MODEL_EQUALLY_OPERATOR' => '=',
    'TRANSITION_PROPERTY_MODEL_MORE_OPERATOR' => '>',
    'TRANSITION_PROPERTY_MODEL_LESS_OPERATOR' => '<',
    'TRANSITION_PROPERTY_MODEL_MORE_EQUAL_OPERATOR' => '>=',
    'TRANSITION_PROPERTY_MODEL_LESS_EQUAL_OPERATOR' => '<=',
    'TRANSITION_PROPERTY_MODEL_NOT_EQUAL_OPERATOR' => '≠',
    'TRANSITION_PROPERTY_MODEL_APPROXIMATELY_EQUAL_OPERATOR' => '≈',

    /* ImportFile */
    'IMPORT_FORM_FILE_NAME' => 'Имя файла',
    'MESSAGE_CLEANING' => 'При импорте все элементы диаграммы будут удалены',
    'MESSAGE_IMPORT_ERROR_INCOMPATIBLE_MODE' => 'Режим импортируемого файла не совпадает с режимом диаграммы',

    /* Заголовки модальных форм EETE*/
    'LEVEL_ADD_NEW_LEVEL' => 'Добавить новый уровень',
    'LEVEL_EDIT_LEVEL' => 'Изменение уровня',
    'LEVEL_DELETE_LEVEL' => 'Удаление уровня',
    'LEVEL_MOVING_LEVEL' => 'Перемещение уровня',
    'LEVEL_ADD_NEW_COMMENT' => 'Добавить новый комментарий уровня',
    'LEVEL_EDIT_COMMENT' => 'Изменить комментарий уровня',
    'LEVEL_DELETE_COMMENT' => 'Удалить комментарий уровня',
    'EVENT_ADD_NEW_EVENT' => 'Добавить новое событие',
    'EVENT_EDIT_EVENT' => 'Изменение события',
    'EVENT_DELETE_EVENT' => 'Удаление события',
    'EVENT_ADD_NEW_COMMENT' => 'Добавить новый комментарий события',
    'EVENT_EDIT_COMMENT' => 'Изменить комментарий события',
    'EVENT_DELETE_COMMENT' => 'Удалить комментарий события',
    'PARAMETER_ADD_NEW_PARAMETER' => 'Добавить новый параметр',
    'PARAMETER_EDIT_PARAMETER' => 'Изменение параметра',
    'PARAMETER_DELETE_PARAMETER' => 'Удаление параметра',
    'MECHANISM_ADD_NEW_MECHANISM' => 'Добавить новый механизм',
    'MECHANISM_EDIT_MECHANISM' => 'Изменение механизма',
    'MECHANISM_DELETE_MECHANISM' => 'Удаление механизма',
    'ERROR_LINKING_ITEMS' => 'Ошибка при связывании элементов',
    'VALIDATION' => 'Проверка корректности',
    'DELETE_RELATIONSHIP' => 'Удаление связи',
    'IMPORT_FORM' => 'Импортирование',

    /* Заголовки модальных форм STDE*/
    'STATE_ADD_NEW_STATE' => 'Добавить новое состояние',
    'STATE_EDIT_STATE' => 'Изменение состояния',
    'STATE_DELETE_STATE' => 'Удаление состояния',
    'STATE_PROPERTY_ADD_NEW_STATE_PROPERTY' => 'Добавить новое свойство состояния',
    'STATE_PROPERTY_EDIT_STATE_PROPERTY' => 'Изменение свойство состояния',
    'STATE_PROPERTY_DELETE_STATE_PROPERTY' => 'Удаление свойство состояния',
    'TRANSITION_ADD_NEW_TRANSITION' => 'Добавить новый переход',
    'TRANSITION_EDIT_TRANSITION' => 'Изменение перехода',
    'TRANSITION_DELETE_TRANSITION' => 'Удаление перехода',
    'TRANSITION_PROPERTY_ADD_NEW_TRANSITION_PROPERTY' => 'Добавить новое условие',
    'TRANSITION_PROPERTY_EDIT_TRANSITION_PROPERTY' => 'Изменение условие',
    'TRANSITION_PROPERTY_DELETE_TRANSITION_PROPERTY' => 'Удаление условия',

    /* Cообщения EETE*/
    'MAXIMUM_CONNECTIONS' => 'Максимальное количество соединений ',
    'MECHANISMS_SHOULD_NOT_BE_INTERCONNECTED' => 'Механизмы не должны быть связаны между собой',
    'ELEMENTS_NOT_BE_ASSOCIATED_WITH_OTHER_ELEMENTS_HIGHER_LEVEL' => 'Элементы не должны быть связаны с другими элементами на вышестоящем уровне',
    'LEVEL_MUST_BEGIN_WITH_MECHANISM' => 'Уровень должен начинаться с механизма',
    'INITIAL_EVENT_SHOULD_NOT_BE_INCOMING_CONNECTIONS' => 'У начального события не должно быть входящих соединений',

    'ALERT_CHANGE_LEVEL' => 'При изменении уровня, связи будут удалены!',
    'ALERT_INITIAL_LEVEL' => 'Удаляется начальный уровень, поэтому будут удалены механизмы на следующем уровне!',
    'ALERT_DELETE_LEVEL' => 'При удалении будут удалены все элементы на уровне!',

    /* Cообщения STDE*/
    'THESE_ELEMENTS_ARE_ALREADY_CONNECTED' => 'Эти элементы уже связаны',

    /* Техсты модальных форм EETE*/
    'RELATIONSHIP_PAGE_DELETE_CONNECTION_TEXT' => 'Вы действительно хотите удалить связь?',
    'DELETE_LEVEL_TEXT' => 'Вы действительно хотите удалить уровень?',
    'DELETE_EVENT_TEXT' => 'Вы действительно хотите удалить событие?',
    'DELETE_MECHANISM_TEXT' => 'Вы действительно хотите удалить механизм?',
    'DELETE_PARAMETER_TEXT' => 'Вы действительно хотите удалить параметр?',
    'DELETE_COMMENT_TEXT' => 'Вы действительно хотите удалить комментарий?',

    /* Техсты модальных форм STDE*/
    'DELETE_STATE_TEXT' => 'Вы действительно хотите удалить состояние?',
    'DELETE_STATE_PROPERTY_TEXT' => 'Вы действительно хотите удалить свойство состояния?',
    'DELETE_TRANSITION_TEXT' => 'Вы действительно хотите удалить переход?',
    'DELETE_TRANSITION_PROPERTY_TEXT' => 'Вы действительно хотите удалить условие?',

    'CONNECTION_DELETE' => 'Удалить',

    /* Техсты сообщений модальных форм EETE*/
    'MESSAGE_PROBABILITY_ALLOWED_ONLY_UP_TO_HUNDREDTHS' => 'Допускается ввод числа от 0 до 1, и только до сотых',
    'MESSAGE_ELEMENT_NAME_ALREADY_ON_DIAGRAM' => 'Элемент с таким названием уже есть на диаграмме',
    'MESSAGE_PARAMETER_NAME_ALREADY_IN_EVENT' => 'Параметер с таким названием уже есть у события',
    'MESSAGE_LEVEL_NAME_ALREADY_ON_DIAGRAM' => 'Уровень с таким названием уже есть на диаграмме',

    /* Техсты сообщений модальных форм STDE*/
    'MESSAGE_STATE_PROPERTY_ALREADY_IN_STATE' => 'Свойство с таким названием, оператором и значением уже есть у состояния',
    'MESSAGE_TRANSITION_PROPERTY_ALREADY_IN_TRANSITION' => 'Условие с таким названием, оператором и значением уже есть у перехода',

    /* Текст */
    'TEXT_NODE' => 'Узел ',
    'TEXT_IS_NOT_LINKED_TO_ANY_OTHER_NODES' => ' не связан с другими узлами.',
    'TEXT_LEVEL' => 'Уровень ',
    'TEXT_DOES_NOT_CONTAIN_ANY_ITEMS' => ' не содержит ни одного элемента.',
    'TEXT_DOES_NOT_CONTAIN_ANY_MECHANISM' => ' не содержит ни одного механизма.',
    'TEXT_WHEN_CHECKING_THE_CORRECTNESS' => 'При поверке корректности данной диаграммы выявлены следующие ошибки:',
    'NO_ERRORS_WERE_FOUND' => 'При поверке корректности данной диаграммы ошибки не выявлены.',
];
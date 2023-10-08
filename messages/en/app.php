<?php

return [
    /* Текст на главной странице */
    'WELCOME_TO_KMS' => 'Welcome to Knowledge Modeling System!',
    'KMS_NAME' => 'Knowledge Modeling System (KMS)',
    'KMS_DEFINITION' => 'is a web-based service that offers the possibility of visual modeling of subject knowledge.',
    'DIAGRAM_TYPES' => 'Visual modeling of knowledge can be carried out using:',
    'FIRST_TYPE' => 'classical and extended event and fault trees',
    'SECOND_TYPE' => 'state transition diagrams',

    'EVENT_TREE_NAME' => 'Event Tree (ET)',
    'EVENT_TREE_DEFINITION' => 'is an algorithm for considering events originating from the main event (emergency). Event tree is used to determine and analyze the sequence (options) of accident development including complex interactions between technical safety systems. Crisp logic is used in its construction. In the general case, this method can also be used to analyze failures, accidents and emergencies, where the initial state is considered as the main event, i.e. the state of technical object at the time of the start of its operation.',
    'ADVANCED_EVENT_TREE_DEFINITION' => 'We have proposed to expand the existing model of event trees and the visual notation of their presentation in order to obtain more complete information about the investigated processes of development of failures and accidents. In particular, based on the results of the system analysis of the problem of studying the dynamics of the technical state of a mechanical system, the stages of development of the indicated processes (submicrolevel, microlevel, mesolevel, macrolevel) and the elements of their description (mechanism and kinetics) are identified. In turn, kinetics, considered as a sequence of events, should be detailed by describing the parameters (characteristics) of events. As a result, a tree template was obtained in a generalized form that describes the stages, the sequence of events (kinetics) and the mechanisms of their occurrence.',
    'EVENT_TREE_CREATION' => 'Classic and extended event tree diagrams are design to',
    'EET_EDITOR' => 'Extended Event Tree Editor (EETE)',

    'STATE_TRANSITION_DIAGRAM_NAME' => 'State Transition Diagram (STD)',
    'STATE_TRANSITION_DIAGRAM_DEFINITION' => 'is a graphical representation of a finite state machine represented in the form of a graph. This graph describes the states of the object and their changes, which together characterize its behavior. The main difference from the previous model is the ability to create loops.',
    'STATE_TRANSITION_DIAGRAM_CREATION' => 'State transition diagrams are design to',
    'STD_EDITOR' => 'State Transition Diagram Editor (STDE)',

    'YOU_CAN_SEE_THE_CREATED' => 'You can see the created ',
    'DIAGRAMS' => 'diagrams',
    'WARNING_FOR_DIAGRAM_CREATION' => 'Building diagrams is available only to authorized users!',
    'TO_CREATE_DIAGRAM' => 'To create a diagram ',
    'SIGN_IN' => 'sign in',
    'YOU_CAN_CREATE' => 'You can create ',
    'DIAGRAM' => 'diagram',

    /* Пункты главного меню */
    'NAV_HOME' => 'Home',
    'NAV_ACCOUNT' => 'Account',
    'NAV_SIGNED_IN_AS' => 'Signed in as',
    'NAV_PROFILE' => 'Profile',
    'NAV_HELP' => 'Help',
    'NAV_CONTACT_US' => 'Contact us',
    'NAV_SIGN_UP' => 'Sign up',
    'NAV_SIGN_IN' => 'Sign in',
    'NAV_SIGN_OUT' => 'Sign out',
    'NAV_MY_DIAGRAMS' => 'My diagrams',
    'NAV_DIAGRAMS' => 'Diagrams',
    'NAV_DIAGRAM' => 'Diagram',
    'NAV_BACK_LIST' => 'Back to the list',
    'NAV_VIRTUAL_ASSISTANTS' => 'Virtual assistants',
    'NAV_USERS' => 'Users',

    'NAV_ADD' => 'Add',
    'NAV_ADD_LEVEL' => 'Level',
    'NAV_ADD_EVENT' => 'Event',
    'NAV_ADD_MECHANISM' => 'Mechanism',
    'NAV_ADD_STATE' => 'State',
    'NAV_ADD_START' => 'Start',
    'NAV_ADD_END' => 'End',

    'NAV_IMPORT' => 'Import',
    'NAV_EXPORT' => 'Export',
    'NAV_VERIFY' => 'Verify',
    'NAV_ALIGNMENT' => 'Alignment',
    'NAV_UNLOAD_DECISION_TABLE' => 'Unload decision table',

    /* Пункты правого меню */
    'SIDE_NAV_POSSIBLE_ACTIONS' => 'Possible actions',

    /* Нижний колонтитул (подвал) */
    'FOOTER_INSTITUTE'=>'ISDCT SB RAS',
    'FOOTER_POWERED_BY' => 'Powered by',

    /* Общие кнопки */
    'BUTTON_OK' => 'Ok',
    'BUTTON_ADD' => 'Add',
    'BUTTON_SEND' => 'Send',
    'BUTTON_SAVE' => 'Save',
    'BUTTON_SIGN_UP' => 'Sign up',
    'BUTTON_SIGN_IN' => 'Sign in',
    'BUTTON_CREATE' => 'Create',
    'BUTTON_UPDATE' => 'Update',
    'BUTTON_EDIT' => 'Edit',
    'BUTTON_DELETE' => 'Delete',
    'BUTTON_CANCEL' => 'Cancel',
    'BUTTON_HIDE' => 'Hide',
    'BUTTON_IMPORT' => 'Import',
    'BUTTON_EXPORT' => 'Export',
    'BUTTON_RETURN' => 'Return to',
    'BUTTON_CONNECTION' => 'Connection',
    'BUTTON_OPEN_DIAGRAM' => 'Open diagram',
    'BUTTON_MOVE' => 'Move',
    'BUTTON_COMMENT' => 'Comment',
    'BUTTON_UPLOAD' => 'Upload',
    'BUTTON_UPLOAD_ONTOLOGY' => 'Upload ontology',
    'BUTTON_CONVERT' => 'Convert',
    'BUTTON_COPY' => 'Copy',
    'BUTTON_DECISION_TABLE' => 'Upload decision table',

    /* Общие сообщения об ошибках */
    'ERROR_MESSAGE_PAGE_NOT_FOUND' => 'Page not found.',
    'ERROR_MESSAGE_ACCESS_DENIED' => 'You are not allowed to perform this action.',

    /* Общие уведомления на форме с captcha */
    'CAPTCHA_NOTICE_ONE' => 'Please enter the letters shown in the picture above.',
    'CAPTCHA_NOTICE_TWO' => 'Letters are not case sensitive.',
    'CAPTCHA_NOTICE_THREE' => 'Click on the letters to change the verification code shown in the picture above.',

    /* Общие заголовки сообщений */
    'WARNING' => 'Warning!',
    'NOTICE_TITLE' => 'Pay attention to',
    'NOTICE_TEXT' => 'this important information.',

    /* Страницы сайта */
    /* Страница пользователей */
    'USERS_PAGE_USER' => 'User',
    'USERS_PAGE_USERS' => 'Users',
    'USERS_PAGE_CREATE_USER' => 'Create user',
    'USERS_PAGE_VIEW_USER' => 'View user',
    'USERS_PAGE_UPDATE_USER_INFORMATION' => 'Update user information',
    'USERS_PAGE_DELETE_USER' => 'Delete user',
    'USERS_PAGE_MODAL_FORM_TEXT' => 'Are you sure that you want to delete this user?',
    /* Сообщения на страницах администрирования пользователей */
    'USERS_PAGE_MESSAGE_ADD_NEW_USER' => 'You have successfully added a new user.',
    'USERS_PAGE_MESSAGE_UPDATED_USER_INFORMATION' => 'You have successfully updated user information.',
    'USERS_PAGE_MESSAGE_NOT_DELETE_USER' => 'You can not delete yourself.',
    'USERS_PAGE_MESSAGE_DELETED_USER' => 'You have successfully deleted user.',
    'USERS_PAGE_MESSAGE_UPDATED_YOUR_ACCOUNT_INFORMATION' => 'You have successfully updated your account information.',
    'USERS_PAGE_MESSAGE_UPDATED_YOUR_PASSWORD' => 'You have successfully changed your password.',
    /* Страница профиля пользователя */
    'USER_PAGE_PROFILE' => 'Profile',
    'USER_PAGE_UPDATE_PROFILE' => 'Update profile',
    'USER_PAGE_UPDATE_ACCOUNT_INFORMATION' => 'Update account information',
    'USER_PAGE_UPDATE_PASSWORD' => 'Change password',

    /* Страница диаграмм */
    'DIAGRAMS_PAGE_DIAGRAM' => 'Diagram',
    'DIAGRAMS_PAGE_DIAGRAMS' => 'Diagrams',
    'DIAGRAMS_PAGE_MY_DIAGRAMS' => 'My diagrams',
    'DIAGRAMS_PAGE_CREATE_DIAGRAM' => 'Create diagram',
    'DIAGRAMS_PAGE_VIEW_DIAGRAM' => 'View diagram',
    'DIAGRAMS_PAGE_UPDATE_DIAGRAM' => 'Update diagram',
    'DIAGRAMS_PAGE_DELETE_DIAGRAM' => 'Delete diagram',
    'DIAGRAMS_PAGE_IMPORT_DIAGRAM' => 'Import visual diagram',
    'DIAGRAMS_PAGE_MODAL_FORM_TEXT' => 'Are you sure that you want to delete this diagram?',
    'DIAGRAMS_PAGE_UPLOAD_ONTOLOGY' => 'Upload ontology',
    'DIAGRAMS_PAGE_CONVERT_ONTOLOGY' => 'Convert ontology',
    'DIAGRAMS_PAGE_CREATE_FROM_TEMPLATE' => 'Create a chart from a template',
    'DIAGRAMS_PAGE_UPLOAD_DECISION_TABLE' => 'Upload decision table',
    /* Сообщения на страницах администрирования диаграмм */
    'DIAGRAMS_PAGE_MESSAGE_CREATE_DIAGRAM' => 'You have successfully created a new diagram.',
    'DIAGRAMS_PAGE_MESSAGE_UPDATED_DIAGRAM' => 'You have successfully updated diagram.',
    'DIAGRAMS_PAGE_MESSAGE_DELETED_DIAGRAM' => 'You have successfully deleted diagram.',
    'DIAGRAMS_PAGE_MESSAGE_IMPORT_DIAGRAM' => 'You have successfully imported diagram.',
    'DIAGRAMS_PAGE_MESSAGE_WARNING_BEFORE_UPLOAD_ONTOLOGY' => 'Attention! All previous diagram elements will be deleted.',
    'DIAGRAMS_PAGE_MESSAGE_UPLOAD_ONTOLOGY' => 'You have successfully uploaded ontology.',
    'DIAGRAMS_PAGE_MESSAGE_UPLOAD_DECISION_TABLE' => 'You have successfully uploaded decision table.',

    'DIAGRAMS_PAGE_UPLOAD_DECISION_TABLE_TEXT' => 'Select a file in CSV format and UTF-8 encoding.',
    'DIAGRAMS_PAGE_MESSAGE_INVALID_ENCODING' => 'File encoding does not match UTF-8 encoding.',

    /* Диаграмма созданная из шаблона */
    'DIAGRAM_CREATED_FROM' => 'Chart created from ',
    'TEMPLATES_DIAGRAMS_NOT_FOUND' => 'Templates diagrams not found',

    /* Страница импорта онтологии */
    'CONVERT_ONTOLOGY_PAGE_RELATIONSHIP_INTERPRETATION' => 'Interpret as relation between events',
    'CONVERT_ONTOLOGY_PAGE_CLASS_LIST' => 'Ontology class list',
    'CONVERT_ONTOLOGY_PAGE_SELECT_ALL_CLASSES' => 'Select all classes',
    /* Сообщения на странице импорта онтологии */
    'CONVERT_ONTOLOGY_PAGE_MESSAGE_CONVERTED_ONTOLOGY' => 'You have successfully converted OWL ontology to event tree diagram.',

    /* Страница виртуальных ассистентов */
    'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANT' => 'Virtual assistant',
    'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANTS' => 'Virtual assistants',
    'VIRTUAL_ASSISTANT_PAGE_CREATE_VIRTUAL_ASSISTANT' => 'Create virtual assistant',
    'VIRTUAL_ASSISTANT_PAGE_VIEW_VIRTUAL_ASSISTANT' => 'View virtual assistant',
    'VIRTUAL_ASSISTANT_PAGE_UPDATE_VIRTUAL_ASSISTANT' => 'Update virtual assistant',
    'VIRTUAL_ASSISTANT_PAGE_DELETE_VIRTUAL_ASSISTANT' => 'Delete virtual assistant',
    'VIRTUAL_ASSISTANT_PAGE_MODAL_FORM_TEXT' => 'Are you sure that you want to delete this virtual assistant?',
    /* Сообщения на страницах администрирования виртуальных ассистентов */
    'VIRTUAL_ASSISTANT_PAGE_MESSAGE_CREATE_VIRTUAL_ASSISTANT' => 'You have successfully created a new virtual assistant.',
    'VIRTUAL_ASSISTANT_PAGE_MESSAGE_UPDATED_VIRTUAL_ASSISTANT' => 'You have successfully updated virtual assistant.',
    'VIRTUAL_ASSISTANT_PAGE_MESSAGE_DELETED_VIRTUAL_ASSISTANT' => 'You have successfully deleted virtual assistant.',

    /* Страница ошибки */
    'ERROR_PAGE_TEXT_ONE' => 'The above error occurred while the Web server was processing your request.',
    'ERROR_PAGE_TEXT_TWO' => 'Please contact us if you think this is a server error. Thank you.',
    /* Страница обратной связи */
    'CONTACT_US_PAGE_TITLE' => 'Contact us',
    'CONTACT_US_PAGE_TEXT' => 'If you have business inquiries or other questions,
        please fill out the following form to contact us. Thank you.',
    'CONTACT_US_PAGE_SUCCESS_MESSAGE' => 'Thank you for contacting us. We will respond to you as soon as possible.',
    /* Страница входа */
    'SIGN_IN_PAGE_TITLE' => 'Sign in',
    'SIGN_IN_PAGE_TEXT' => 'Please fill out the following fields to sign in:',
    'SIGN_IN_PAGE_RESET_TEXT' => 'If you forgot your password you can',
    'SIGN_IN_PAGE_RESET_LINK' => 'reset it',

    /* Формы */
    /* ContactForm */
    'CONTACT_FORM_NAME' => 'Name',
    'CONTACT_FORM_EMAIL' => 'Email',
    'CONTACT_FORM_SUBJECT' => 'Subject',
    'CONTACT_FORM_BODY' => 'Body',
    'CONTACT_FORM_VERIFICATION_CODE' => 'Verification Code',
    /* LoginForm */
    'LOGIN_FORM_USERNAME' => 'Username',
    'LOGIN_FORM_PASSWORD' => 'Password',
    'LOGIN_FORM_REMEMBER_ME' => 'Remember Me',
    /* Сообщения LoginForm */
    'LOGIN_FORM_MESSAGE_INCORRECT_USERNAME_OR_PASSWORD' => 'Username or password is incorrect.',
    'LOGIN_FORM_MESSAGE_BLOCKED_ACCOUNT' => 'Your account has been blocked.',
    'LOGIN_FORM_MESSAGE_NOT_CONFIRMED_ACCOUNT' => 'Your account is not confirmed.',
    /* OWLFileForm */
    'OWL_FILE_FORM_OWL_FILE' => 'OWL ontology file',
    'OWL_FILE_FORM_CLASS' => 'Import classes',
    'OWL_FILE_FORM_SUBCLASS_RELATION' => 'Consider class hierarchy',
    'OWL_FILE_FORM_CLASS_OBJECT_PROPERTY' => 'Consider object properties of classes',
    'OWL_FILE_FORM_CLASS_DATATYPE_PROPERTY' => 'Consider datatype properties of classes',
    'OWL_FILE_FORM_INDIVIDUAL' => 'Import individuals (class instances)',
    'OWL_FILE_FORM_IS_A_RELATION' => 'Consider relationships between class and its individuals',
    'OWL_FILE_FORM_INDIVIDUAL_OBJECT_PROPERTY' => 'Consider object properties of individuals',
    'OWL_FILE_FORM_INDIVIDUAL_DATATYPE_PROPERTY' => 'Consider datatype properties of individuals',

    /* Модели */
    /* Lang */
    'LANG_MODEL_ID' => 'ID',
    'LANG_MODEL_CREATED_AT' => 'Created at',
    'LANG_MODEL_UPDATED_AT' => 'Updated at',
    'LANG_MODEL_URL' => 'URL',
    'LANG_MODEL_LOCAL' => 'Local',
    'LANG_MODEL_NAME' => 'Name',
    'LANG_MODEL_DEFAULT' => 'Default language',

    /* User */
    'USER_MODEL_ID' => 'ID',
    'USER_MODEL_CREATED_AT' => 'Created at',
    'USER_MODEL_UPDATED_AT' => 'Updated at',
    'USER_MODEL_USERNAME' => 'Username',
    'USER_MODEL_PASSWORD' => 'Password',
    'USER_MODEL_AUTH_KEY' => 'Auth key',
    'USER_MODEL_EMAIL_CONFIRM_TOKEN' => 'E-mail confirm token',
    'USER_MODEL_PASSWORD_HASH' => 'Password hash',
    'USER_MODEL_PASSWORD_RESET_TOKEN' => 'Password reset token',
    'USER_MODEL_ROLE' => 'Role',
    'USER_MODEL_STATUS' => 'Status',
    'USER_MODEL_FULL_NAME' => 'Full name',
    'USER_MODEL_EMAIL' => 'E-mail',
    /* Сообщения модели User */
    'USER_MODEL_MESSAGE_USERNAME' => 'This username has already been taken.',
    'USER_MODEL_MESSAGE_FULL_NAME' => 'Specify correct full name.',
    'USER_MODEL_MESSAGE_UPDATED_YOUR_DETAILS' => 'You have successfully changed your details.',
    'USER_MODEL_MESSAGE_UPDATED_YOUR_PASSWORD' => 'You have successfully changed password.',

    /* Diagram */
    'DIAGRAM_MODEL_ID' => 'ID',
    'DIAGRAM_MODEL_CREATED_AT' => 'Created at',
    'DIAGRAM_MODEL_UPDATED_AT' => 'Updated at',
    'DIAGRAM_MODEL_NAME' => 'Name',
    'DIAGRAM_MODEL_DESCRIPTION' => 'Description',
    'DIAGRAM_MODEL_TYPE' => 'Type',
    'DIAGRAM_MODEL_STATUS' => 'Status',
    'DIAGRAM_MODEL_CORRECTNESS' => 'Correctness',
    'DIAGRAM_MODEL_AUTHOR' => 'Author',
    /* Значения полей типов диаграмм */
    'DIAGRAM_MODEL_EVENT_TREE_TYPE' => 'Event tree',
    'DIAGRAM_MODEL_STATE_TRANSITION_DIAGRAM_TYPE' => 'State transition diagram',
    /* Значения полей статусов */
    'DIAGRAM_MODEL_PUBLIC_STATUS' => 'Public',
    'DIAGRAM_MODEL_PRIVATE_STATUS' => 'Private',
    /* Значения корректности диаграммы */
    'DIAGRAM_MODEL_NOT_CHECKED_CORRECT' => 'Not checked',
    'DIAGRAM_MODEL_CORRECTLY_CORRECT' => 'Correctly',
    'DIAGRAM_MODEL_INCORRECTLY_CORRECT' => 'Incorrectly',

    /* TreeDiagram */
    'TREE_DIAGRAM_MODEL_ID' => 'ID',
    'TREE_DIAGRAM_MODEL_CREATED_AT' => 'Created at',
    'TREE_DIAGRAM_MODEL_UPDATED_AT' => 'Updated at',
    'TREE_DIAGRAM_MODEL_MODE' => 'Mode',
    'TREE_DIAGRAM_MODEL_TREE_VIEW' => 'Tree view',
    'TREE_DIAGRAM_MODEL_DIAGRAM' => 'Diagram',
    /* Значения режимов деревьев диаграмм */
    'TREE_DIAGRAM_MODEL_EXTENDED_TREE_MODE' => 'Extended tree',
    'TREE_DIAGRAM_MODEL_CLASSIC_TREE_MODE' => 'Classic tree',
    /* Значения вида дерева диаграмм */
    'TREE_DIAGRAM_MODEL_ORDINARY_TREE_VIEW' => 'Ordinary tree',
    'TREE_DIAGRAM_MODEL_TEMPLATE_TREE_VIEW' => 'Tree template',

    /* Node */
    'NODE_MODEL_ID' => 'ID',
    'NODE_MODEL_CREATED_AT' => 'Created at',
    'NODE_MODEL_UPDATED_AT' => 'Updated at',
    'NODE_MODEL_NAME' => 'Name',
    'NODE_MODEL_CERTAINTY_FACTOR' => 'Certainty factor',
    'NODE_MODEL_DESCRIPTION' => 'Description',
    'NODE_MODEL_OPERATOR' => 'Operator',
    'NODE_MODEL_TYPE' => 'Type',
    'NODE_MODEL_PARENT_NODE' => 'Parent node',
    'NODE_MODEL_TREE_DIAGRAM' => 'Diagram',
    'NODE_MODEL_LEVEL_ID' => 'Level name',
    'NODE_MODEL_COMMENT' => 'Comment',
    /* Значения операторов */
    'NODE_MODEL_NOT_OPERATOR' => 'NOT',
    'NODE_MODEL_AND_OPERATOR' => 'AND',
    'NODE_MODEL_OR_OPERATOR' => 'OR',
    'NODE_MODEL_XOR_OPERATOR' => 'XOR',
    /* Значения типов узлов */
    'TREE_DIAGRAM_MODEL_INITIAL_EVENT_TYPE' => 'Initial event',
    'TREE_DIAGRAM_MODEL_EVENT_TYPE' => 'Event',
    'TREE_DIAGRAM_MODEL_MECHANISM_TYPE' => 'Mechanism',

    /* Parameter */
    'PARAMETER_MODEL_ID' => 'ID',
    'PARAMETER_MODEL_CREATED_AT' => 'Created at',
    'PARAMETER_MODEL_UPDATED_AT' => 'Updated at',
    'PARAMETER_MODEL_NAME' => 'Name',
    'PARAMETER_MODEL_DESCRIPTION' => 'Description',
    'PARAMETER_MODEL_OPERATOR' => 'Operator',
    'PARAMETER_MODEL_VALUE' => 'Value',
    'PARAMETER_MODEL_NODE' => 'Node',
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
    'LEVEL_MODEL_CREATED_AT' => 'Created at',
    'LEVEL_MODEL_UPDATED_AT' => 'Updated at',
    'LEVEL_MODEL_NAME' => 'Name',
    'LEVEL_MODEL_DESCRIPTION' => 'Description',
    'LEVEL_MODEL_PARENT_LEVEL' => 'Parent level',
    'LEVEL_MODEL_TREE_DIAGRAM' => 'Diagram',
    'LEVEL_MODEL_MOVEMENT_LEVEL' => 'Level move after:',
    'LEVEL_MODEL_COMMENT' => 'Comment',

    /* Sequence */
    'SEQUENCE_MODEL_ID' => 'ID',
    'SEQUENCE_MODEL_CREATED_AT' => 'Created at',
    'SEQUENCE_MODEL_UPDATED_AT' => 'Updated at',
    'SEQUENCE_MODEL_TREE_DIAGRAM' => 'Diagram',
    'SEQUENCE_MODEL_LEVEL' => 'Level',
    'SEQUENCE_MODEL_NODE' => 'Node',
    'SEQUENCE_MODEL_PRIORITY' => 'Priority',

    /* State */
    'STATE_MODEL_ID' => 'ID',
    'STATE_MODEL_CREATED_AT' => 'Created at',
    'STATE_MODEL_UPDATED_AT' => 'Updated at',
    'STATE_MODEL_NAME' => 'Name',
    'STATE_MODEL_TYPE' => 'Type',
    'STATE_MODEL_DESCRIPTION' => 'Description',
    'STATE_MODEL_INDENT_X' => 'Indent X',
    'STATE_MODEL_INDENT_Y' => 'Indent Y',
    'STATE_MODEL_DIAGRAM' => 'Diagram',
    /* Значения типов состояний */
    'STATE_MODEL_INITIAL_STATE_TYPE' => 'Initial state',
    'STATE_MODEL_COMMON_STATE_TYPE' => 'State',

    /* StateProperty */
    'STATE_PROPERTY_MODEL_ID' => 'ID',
    'STATE_PROPERTY_MODEL_CREATED_AT' => 'Created at',
    'STATE_PROPERTY_MODEL_UPDATED_AT' => 'Updated at',
    'STATE_PROPERTY_MODEL_NAME' => 'Name',
    'STATE_PROPERTY_MODEL_DESCRIPTION' => 'Description',
    'STATE_PROPERTY_MODEL_OPERATOR' => 'Operator',
    'STATE_PROPERTY_MODEL_VALUE' => 'Value',
    'STATE_PROPERTY_MODEL_STATE' => 'State',
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
    'TRANSITION_MODEL_CREATED_AT' => 'Created at',
    'TRANSITION_MODEL_UPDATED_AT' => 'Updated at',
    'TRANSITION_MODEL_NAME' => 'Name',
    'TRANSITION_MODEL_DESCRIPTION' => 'Description',
    'TRANSITION_MODEL_STATE_FROM' => 'State from',
    'TRANSITION_MODEL_STATE_TO' => 'State to',
    'TRANSITION_MODEL_NAME_PROPERTY' => 'Name property',
    'TRANSITION_MODEL_DESCRIPTION_PROPERTY' => 'Description property',
    'TRANSITION_MODEL_OPERATOR_PROPERTY' => 'Operator property',
    'TRANSITION_MODEL_VALUE_PROPERTY' => 'Value property',

    /* TransitionProperty */
    'TRANSITION_PROPERTY_MODEL_ID' => 'ID',
    'TRANSITION_PROPERTY_MODEL_CREATED_AT' => 'Created at',
    'TRANSITION_PROPERTY_MODEL_UPDATED_AT' => 'Updated at',
    'TRANSITION_PROPERTY_MODEL_NAME' => 'Name',
    'TRANSITION_PROPERTY_MODEL_DESCRIPTION' => 'Description',
    'TRANSITION_PROPERTY_MODEL_OPERATOR' => 'Operator',
    'TRANSITION_PROPERTY_MODEL_VALUE' => 'Value',
    'TRANSITION_PROPERTY_MODEL_STATE' => 'State',
    /* Значения операторов */
    'TRANSITION_PROPERTY_MODEL_EQUALLY_OPERATOR' => '=',
    'TRANSITION_PROPERTY_MODEL_MORE_OPERATOR' => '>',
    'TRANSITION_PROPERTY_MODEL_LESS_OPERATOR' => '<',
    'TRANSITION_PROPERTY_MODEL_MORE_EQUAL_OPERATOR' => '>=',
    'TRANSITION_PROPERTY_MODEL_LESS_EQUAL_OPERATOR' => '<=',
    'TRANSITION_PROPERTY_MODEL_NOT_EQUAL_OPERATOR' => '≠',
    'TRANSITION_PROPERTY_MODEL_APPROXIMATELY_EQUAL_OPERATOR' => '≈',

    /* StartToEnd */
    'START_TO_END_MODEL_ID' => 'ID',
    'START_TO_END_MODEL_CREATED_AT' => 'Created at',
    'START_TO_END_MODEL_UPDATED_AT' => 'Updated at',
    'START_TO_END_MODEL_TYPE' => 'Type',
    'START_TO_END_MODEL_INDENT_X' => 'Indent X',
    'START_TO_END_MODEL_INDENT_Y' => 'Indent Y',
    'START_TO_END_MODEL_DIAGRAM' => 'Diagram',
    /* Значения типов */
    'START_TO_END_MODEL_START_TYPE' => 'Start',
    'START_TO_END_MODEL_END_TYPE' => 'End',

    /* State connection */
    'STATE_CONNECTION_MODEL_ID' => 'ID',
    'STATE_CONNECTION_MODEL_CREATED_AT' => 'Created at',
    'STATE_CONNECTION_MODEL_UPDATED_AT' => 'Updated at',
    'STATE_CONNECTION_MODEL_START_TO_END' => 'Start To End',
    'STATE_CONNECTION_MODEL_STATE' => 'State',

    /* Virtual assistant */
    'VIRTUAL_ASSISTANT_MODEL_ID' => 'ID',
    'VIRTUAL_ASSISTANT_MODEL_CREATED_AT' => 'Created at',
    'VIRTUAL_ASSISTANT_MODEL_UPDATED_AT' => 'Updated at',
    'VIRTUAL_ASSISTANT_MODEL_NAME' => 'Name',
    'VIRTUAL_ASSISTANT_MODEL_DESCRIPTION' => 'Description',
    'VIRTUAL_ASSISTANT_MODEL_STATUS' => 'Status',
    'VIRTUAL_ASSISTANT_MODEL_AUTHOR' => 'Author',
    'VIRTUAL_ASSISTANT_MODEL_DIALOGUE_MODEL' => 'Dialogue model',
    'VIRTUAL_ASSISTANT_MODEL_KNOWLEDGE_BASE_MODEL' => 'Knowledge base model',
    /* Значения полей статусов */
    'VIRTUAL_ASSISTANT_MODEL_PUBLIC_STATUS' => 'Public',
    'VIRTUAL_ASSISTANT_MODEL_PRIVATE_STATUS' => 'Private',

    'GENERATOR_FORM_PLATFORM' => 'Platform:',
    'GENERATOR_FORM_PLATFORM_AI_MYLOGIC' => 'AI MyLogic',
    /* Значения шагов */
    'STEP_1' => 'Step 1: Modeling',
    'STEP_2' => 'Step 2: Specialization',
    'STEP_3' => 'Step 3: Codes and specifications',
    /* Значения кнопок */
    'BUTTON_DIALOGUE_MODEL' => 'Dialogue model',
    'BUTTON_KNOWLEDGE_BASE_MODEL' => 'Knowledge base model',
    'BUTTON_COMMUNICATION_MODEL' => 'Communication model',
    'BUTTON_GENERATE_VA' => 'Generate virtual assistant',
    'BUTTON_GENERATE' => 'Generate',
    'BUTTON_DOWNLOAD_JSON_1' => 'Download Json file',
    'BUTTON_DOWNLOAD_CSV' => 'Download CSV file',
    'BUTTON_DOWNLOAD_JSON_2' => 'Download Json file',

    /* ImportFile */
    'IMPORT_FORM_FILE_NAME' => 'File name',
    'MESSAGE_CLEANING' => 'When importing all chart elements are removed',
    'MESSAGE_IMPORT_ERROR_INCOMPATIBLE_MODE' => 'Imported file mode does not match chart mode',

    /* Заголовки модальных форм EETE */
    'LEVEL_ADD_NEW_LEVEL' => 'Add new level',
    'LEVEL_EDIT_LEVEL' => 'Level change',
    'LEVEL_DELETE_LEVEL' => 'Level delete',
    'LEVEL_MOVING_LEVEL' => 'Transfer level',
    'LEVEL_ADD_NEW_COMMENT' => 'Add new level comment',
    'LEVEL_EDIT_COMMENT' => 'Change level comment',
    'LEVEL_DELETE_COMMENT' => 'Delete level comment',
    'EVENT_ADD_NEW_EVENT' => 'Add new event',
    'EVENT_EDIT_EVENT' => 'Event change',
    'EVENT_DELETE_EVENT' => 'Delete event',
    'EVENT_COPY_EVENT' => 'Copy event',
    'EVENT_ADD_NEW_COMMENT' => 'Add new event comment',
    'EVENT_EDIT_COMMENT' => 'Change event comment',
    'EVENT_DELETE_COMMENT' => 'Delete event comment',
    'PARAMETER_ADD_NEW_PARAMETER' => 'Add new parameter',
    'PARAMETER_EDIT_PARAMETER' => 'Parameter change',
    'PARAMETER_DELETE_PARAMETER' => 'Parameter delete',
    'MECHANISM_ADD_NEW_MECHANISM' => 'Add new mechanism',
    'MECHANISM_EDIT_MECHANISM' => 'Mechanism change',
    'MECHANISM_DELETE_MECHANISM' => 'Delete mechanism',
    'ERROR_LINKING_ITEMS' => 'Error linking items',
    'VALIDATION' => 'Validation',
    'DELETE_RELATIONSHIP' => 'Deleting connection',
    'IMPORT_FORM' => 'Import',
    'ERROR_COPY_EVENT' => 'Event copy error',

    /* Заголовки модальных форм STDE */
    'STATE_ADD_NEW_STATE' => 'Add new state',
    'STATE_EDIT_STATE' => 'State change',
    'STATE_DELETE_STATE' => 'State delete',
    'STATE_COPY_STATE' => 'State copy',
    'STATE_PROPERTY_ADD_NEW_STATE_PROPERTY' => 'Add new state property',
    'STATE_PROPERTY_EDIT_STATE_PROPERTY' => 'State property change',
    'STATE_PROPERTY_DELETE_STATE_PROPERTY' => 'State property delete',
    'TRANSITION_ADD_NEW_TRANSITION' => 'Add new transition',
    'TRANSITION_EDIT_TRANSITION' => 'Transition change',
    'TRANSITION_DELETE_TRANSITION' => 'Transition delete',
    'TRANSITION_PROPERTY_ADD_NEW_TRANSITION_PROPERTY' => 'Add new transition property',
    'TRANSITION_PROPERTY_EDIT_TRANSITION_PROPERTY' => 'Transition property change',
    'TRANSITION_PROPERTY_DELETE_TRANSITION_PROPERTY' => 'Transition property delete',
    'START_TO_END_DELETE_START' => 'Start delete',
    'START_TO_END_DELETE_END' => 'End delete',
    'CONNECTION_DELETE_CONNECTION' => 'Connection delete',

    /* Cообщения EETE */
    'MAXIMUM_CONNECTIONS' => 'Maximum connections ',
    'MECHANISMS_SHOULD_NOT_BE_INTERCONNECTED' => 'The mechanisms should not be interconnected',
    'ELEMENTS_NOT_BE_ASSOCIATED_WITH_OTHER_ELEMENTS_HIGHER_LEVEL' => 'Elements must not be associated with other elements at a higher level',
    'LEVEL_MUST_BEGIN_WITH_MECHANISM' => 'The level must begin with a mechanism',
    'INITIAL_EVENT_SHOULD_NOT_BE_INCOMING_CONNECTIONS' => 'In the initial event should not be incoming connections',
    'YOU_CANNOT_PLACE_MORE_THAN_ONE_EVENT_PER_ENTRY_LEVEL' => 'You cannot place more than one event per entry level',
    'ALERT_CHANGE_LEVEL' => 'When you change the level, the connection will be deleted!',
    'ALERT_INITIAL_LEVEL' => 'The initial level is deleted, so the mechanisms at the next level will be deleted!',
    'ALERT_DELETE_LEVEL' => 'When deleting, all the elements on the level will be deleted!',

    /* Cообщения STDE */
    'THESE_ELEMENTS_ARE_ALREADY_CONNECTED' => 'These elements are already connected',
    'START_AND_END_CANNOT_BE_LINKED' => 'Start and end elements cannot be linked',
    'DELETE_CONNECTION_TEXT' => 'Are you sure you want to delete the connection?',
    'CONNECTION_IS_ALREADY_THERE' => 'The connection between these elements already exists',

    /* Техсты модальных форм EETE */
    'RELATIONSHIP_PAGE_DELETE_CONNECTION_TEXT' => 'Are you sure you want to delete the connection?',
    'DELETE_LEVEL_TEXT' => 'Are you sure you want to delete the level?',
    'DELETE_EVENT_TEXT' => 'Are you sure you want to delete the event?',
    'DELETE_MECHANISM_TEXT' => 'Are you sure you want to delete the mechanism?',
    'DELETE_PARAMETER_TEXT' => 'Are you sure you want to delete the parameter?',
    'DELETE_COMMENT_TEXT' => 'Are you sure you want to delete the comment?',

    /* Техсты модальных форм STDE */
    'DELETE_STATE_TEXT' => 'Are you sure you want to delete the state?',
    'DELETE_STATE_PROPERTY_TEXT' => 'Are you sure you want to delete the state property?',
    'DELETE_TRANSITION_TEXT' => 'Are you sure you want to delete the transition?',
    'DELETE_TRANSITION_PROPERTY_TEXT' => 'Are you sure you want to delete the transition property?',
    'DELETE_START_TEXT' => 'Are you sure you want to delete the start?',
    'DELETE_END_TEXT' => 'Are you sure you want to delete the end?',

    'CONNECTION_DELETE' => 'Delete',

    /* Техсты сообщений модальных форм EETE */
    'MESSAGE_PROBABILITY_ALLOWED_ONLY_UP_TO_HUNDREDTHS' => 'You can enter a number from 0 to 1, and only up to hundredths',
    'MESSAGE_ELEMENT_NAME_ALREADY_ON_DIAGRAM' => 'An element with this name is already on the diagram',
    'MESSAGE_PARAMETER_NAME_ALREADY_IN_EVENT' => 'The event already has a parameter with this name',
    'MESSAGE_LEVEL_NAME_ALREADY_ON_DIAGRAM' => 'The level with this name is already on the diagram',

    /* Техсты сообщений модальных форм STDE */
    'MESSAGE_STATE_PROPERTY_ALREADY_IN_STATE' => 'The state already has a property with the same name, operator and value.',
    'MESSAGE_TRANSITION_PROPERTY_ALREADY_IN_TRANSITION' => 'The transition already has a condition with the same name, operator and value.',

    /* Текст */
    'TEXT_NODE' => 'The node ',
    'TEXT_IS_NOT_LINKED_TO_ANY_OTHER_NODES' => ' is not linked to any other nodes.',
    'TEXT_LEVEL' => 'Level ',
    'TEXT_DOES_NOT_CONTAIN_ANY_ITEMS' => ' does not contain any items.',
    'TEXT_DOES_NOT_CONTAIN_ANY_MECHANISM' => ' does not contain any mechanism.',
    'TEXT_WHEN_CHECKING_THE_CORRECTNESS' => 'When checking the correctness of this diagram, the following errors were revealed:',
    'NO_ERRORS_WERE_FOUND' => 'When checking the correctness of this diagram, no errors were found.',
];
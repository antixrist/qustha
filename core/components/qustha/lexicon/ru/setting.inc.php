<?php

$_lang['area_qustha_system'] = 'Системные';

$_lang['setting_qustha_core_path'] = 'core_path компонента';
$_lang['setting_qustha_core_path_desc'] = '';

$_lang['setting_qustha_assets_path'] = 'assets_path компонента';
$_lang['setting_qustha_assets_path_desc'] = '';

$_lang['setting_qustha_assets_url'] = 'assets_url компонента';
$_lang['setting_qustha_assets_url_desc'] = '';


$_lang['area_qustha_general'] = 'Общие';

$_lang['setting_qustha_cookie_expires'] = 'Время жизни <code>$_COOKIE</code> (дней)';
$_lang['setting_qustha_cookie_expires_desc'] = '';

$_lang['setting_qustha_session_store'] = '<code>$_SESSION</code> вместо <code>$_COOKIE</code>';
$_lang['setting_qustha_session_store_desc'] = 'По умолчанию данные хранятся в <code>cookies</code> посетителя.<br>
Использовать массив <code>$_SESSION</code> вместо <code>$_COOKIE</code>?<br>
Если эта настройка включена, то опция "<b>Время жизни <code>$_COOKIE</code></b>" игнорируется.';


$_lang['area_qustha_variables_list'] = 'Списки переменных';

$_lang['setting_qustha_storage_vars'] = 'Хранимые переменные';
$_lang['setting_qustha_storage_vars_desc'] = 'Список <code>GET</code>-переменных через запятую, значения которых будут храниться в выбранном массиве (<code>$_COOKIE</code> или <code>$_SESSION</code>).<br>
';

$_lang['setting_qustha_dependent_vars'] = 'Зависимые переменные';
$_lang['setting_qustha_dependent_vars_desc'] = '<code>GET</code>-переменные.<br>
Если переменная из этого списка не равна хранимому значению, то хранимые значения остальных переменных из этого списка так же будут обновлены<br>
(или удалены, если переменная в <code>URL</code> не установлена).<br>
<em><code>GET</code>-переменные из этого списка автоматически становятся хранимыми.</em>';

$_lang['setting_qustha_placeholder_vars'] = '<code>GET</code>-переменные для плейсхолдеров';
$_lang['setting_qustha_placeholder_vars_desc'] = 'Список <code>GET</code>-переменных через запятую, значения которых будут помещены в одноимённые плейсхолдеры.';

$_lang['setting_qustha_redirect_vars'] = '<code>GET</code>-переменные для редиректа';
$_lang['setting_qustha_redirect_vars_desc'] = 'Список <code>GET</code>-переменных через запятую, которые будут убраны из <code>URL</code> путём <code>301</code> редиректа.';

$_lang['setting_qustha_fi_hook_vars'] = '<code>GET</code>-переменные для hook\'а FormIt';
$_lang['setting_qustha_fi_hook_vars_desc'] = 'Список <code>GET</code>-переменных через запятую, значения которых будут добавляться к данным любой формы, обрабатываемой с помощью FormIt.<br>
Имя hook\'а: <b><code>userHandlerHook</code></b>';

<?php

$settings = array();

$tmp = array(
	'title_var' => array(
		'xtype' => 'textfield',
		'value' => 'ydTitle',
		'area' => 'yaddr_variables_names',
	),
  'body_var' => array(
		'xtype' => 'textfield',
		'value' => 'ydBody',
		'area' => 'yaddr_variables_names',
	),
  'keyword_var' => array(
		'xtype' => 'textfield',
		'value' => 'utm_term',
		'area' => 'yaddr_variables_names',
	),
  'title_var_enabled' => array(
		'xtype' => 'combo-boolean',
		'value' => true,
		'area' => 'yaddr_variables_activity',
	),
  'body_var_enabled' => array(
		'xtype' => 'combo-boolean',
		'value' => true,
		'area' => 'yaddr_variables_activity',
	),
  'keyword_var_enabled' => array(
		'xtype' => 'combo-boolean',
		'value' => true,
		'area' => 'yaddr_variables_activity',
	),
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'yaddr_'.$k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	),'',true,true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;

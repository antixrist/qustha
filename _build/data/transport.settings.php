<?php

$settings = array();

$corePath = (PKG_DEV_RELEASE) ? $sources['source_core'] .'/' : '';
$assetsPath = (PKG_DEV_RELEASE) ? $sources['source_assets'] .'/' : '';

$assetsUrl = '';
if (PKG_DEV_RELEASE && strpos($sources['root'], $assetsPath) !== false) {
  $assetsUrl = substr($sources['root'], strlen($assetsPath));
}


$tmp = array(
  'core_path' => array(
    'xtype' => 'textfield',
    'value' => $corePath,
    'area' => 'qustha_system',
  ),
  'assets_path' => array(
    'xtype' => 'textfield',
    'value' => $assetsPath,
    'area' => 'qustha_system',
  ),
  'assets_url' => array(
    'xtype' => 'textfield',
    'value' => $assetsUrl,
    'area' => 'qustha_system',
  ),
  'cookie_expires' => array(
    'xtype' => 'textfield',
    'value' => 365,
    'area' => 'qustha_general',
  ),
  'session_store' => array(
    'xtype' => 'combo-boolean',
    'value' => true,
    'area' => 'qustha_general',
  ),
	'storage_vars' => array(
		'xtype' => 'textfield',
		'value' => 'ydTitle, ydBody, utm_term, utm_campaign, utm_source, utm_medium, utm_content, from, openstat, gclid, source_type, source, addphrases, position_type, position, keyword, phrase, campagn-name, param1, param2, campaign_id, ad_id, banner_id, phrase_id, retargeting_id, yclid',
		'area' => 'qustha_variables_list',
	),
  'dependent_vars' => array(
		'xtype' => 'textfield',
//		'value' => 'ydTitle, ydBody, utm_term, utm_campaign, utm_source, utm_medium, utm_content, keyword, phrase, campagn-name, param1, param2, campaign_id, ad_id, banner_id, yclid',
		'value' => '',
		'area' => 'qustha_variables_list',
	),
  'placeholder_vars' => array(
		'xtype' => 'textfield',
		'value' => 'ydTitle,ydBody,utm_term',
		'area' => 'qustha_variables_list',
	),
  'redirect_vars' => array(
		'xtype' => 'textfield',
		'value' => 'ydTitle, ydBody, utm_term, utm_campaign, utm_source, utm_medium, utm_content, from, openstat, gclid, source_type, source, addphrases, position_type, position, keyword, phrase, campagn-name, param1, param2, campaign_id, ad_id, banner_id, phrase_id, retargeting_id, yclid',
		'area' => 'qustha_variables_list',
/*	),
  'fi_hook_vars' => array(
		'xtype' => 'textfield',
		'value' => 'ydTitle,ydBody,utm_term,utm_source,utm_medium,utm_campaign,from,openstat',
		'area' => 'qustha_variables_list',*/
	)
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'qustha_'.$k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	),'',true,true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;

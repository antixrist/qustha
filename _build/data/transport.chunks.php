<?php

$chunks = array();

$tmp = array(
	'tpl.qustha.email.item' => array(
		'file' => 'email.item',
		'description' => '',
	),
	'tpl.qustha.email.wrapper' => array(
		'file' => 'email.wrapper',
		'description' => '',
	),
	'tpl.qustha.json.item' => array(
		'file' => 'json.item',
		'description' => '',
	),
	'tpl.qustha.json.wrapper' => array(
		'file' => 'json.wrapper',
		'description' => '',
	),
);

foreach ($tmp as $k => $v) {
	/* @avr modChunk $chunk */
	$chunk = $modx->newObject('modChunk');
	$chunk->fromArray(array(
		'id' => 0,
		'name' => $k,
		'description' => @$v['description'],
		'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/chunk.'.$v['file'].'.tpl'),
		'static' => BUILD_CHUNK_STATIC,
		'source' => 1,
		'static_file' => $devFolderName. 'core/components/'.PKG_NAME_LOWER.'/elements/chunks/chunk.'.$v['file'].'.tpl',
	),'',true,true);

	$chunks[] = $chunk;
}

unset($tmp);
return $chunks;
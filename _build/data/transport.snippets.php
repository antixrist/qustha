<?php

$snippets = array();

$tmp = array(
	'qustha' => array(
		'file' => 'qustha',
		'description' => '',
	),
);

foreach ($tmp as $k => $v) {
	/* @avr modSnippet $snippet */
	$snippet = $modx->newObject('modSnippet');
	$snippet->fromArray(array(
		'id' => 0,
		'name' => $k,
		'description' => @$v['description'],
		'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.'.$v['file'].'.php'),
		'static' => BUILD_SNIPPET_STATIC,
		'source' => 1,
		'static_file' => $devFolderName. 'core/components/'.PKG_NAME_LOWER.'/elements/snippets/snippet.'.$v['file'].'.php',
	),'',true,true);

  $propFile = $sources['build'].'properties/properties.'.$v['file'].'.php';
  if (file_exists($propFile)) {
	  $properties = include $propFile;
	  $snippet->setProperties($properties);
  }

	$snippets[] = $snippet;
}

unset($tmp, $properties);
return $snippets;
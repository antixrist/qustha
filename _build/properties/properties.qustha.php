<?php

$properties = array();

$tmp = array(
  'type' => array(
    'type' => 'textfield'
    ,'value' => 'storage'
  )
  ,'tpl' => array(
    'type' => 'textfield'
    ,'value' => 'tpl.qustha.email.item'
  )
  ,'tplWrapper' => array(
    'type' => 'textfield'
    ,'value' => 'tpl.qustha.email.wrapper'
  )
  ,'wrapIfEmpty' => array(
    'type' => 'textfield'
    ,'value' => ''
  )
  ,'outputSeparator' => array(
    'type' => 'textfield'
    ,'value' => ''
  )
);

foreach ($tmp as $k => $v) {
  $properties[] = array_merge(array(
    'name' => $k
    ,'desc' => 'qustha_prop_'.$k
    ,'lexicon' => 'qustha:properties'
    ), $v
  );
}

return $properties;
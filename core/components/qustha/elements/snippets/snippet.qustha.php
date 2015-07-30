<?php
/** @var array $scriptProperties */
$qustha = $modx->getService('qustha','qustha',$modx->getOption('qustha_core_path',null,$modx->getOption('core_path').'components/qustha/', true).'model/qustha/');
if (!($qustha instanceof qustha)) return '';

$type = (!empty($scriptProperties['type'])) ? $scriptProperties['type'] : false;
$tpl = (!empty($scriptProperties['tpl'])) ? $scriptProperties['tpl'] : false;
if (!$type || !$tpl) return;

$output = array();
$data = $qustha->getVars($type);
//die(print_r($data));
foreach ($data as $key => $value) {
  // здесь можно делать текстовое описание.
  $description = '';
  switch ($key) {
    case 'ydTitle':
      $description = 'Заголовок из объявления Я.Директа';
      break;
    case 'ydBody':
      $description = 'Текст объявления Я.Директа';
      break;
    case 'utm_term':
      $description = 'Ключевое слово';
      break;
    case 'utm_campaign':
      $description = 'Название проводимой рекламной кампании';
      break;
    case 'utm_source':
      $description = 'Источник перехода';
      break;
    case 'utm_medium':
      $description = 'Средство маркетинга: (cpc, баннер, электронное сообщение)';
      break;
    case 'utm_content':
      $description = 'Объявление';
      break;
    case 'from':
      $description = 'Откуда';
      break;
    case 'openstat':
      $description = 'Метка Openstat';
      break;
    case 'gclid':
      $description = 'Метка Google.AdWords';
      break;
    case 'source_type':
      $description = 'Тип площадки, на которой произведён показ объявления';
      break;
    case 'source':
      $description = 'Название площадки РСЯ';
      break;
    case 'addphrases':
      $description = 'Инициирован ли этот показ «дополнительными релевантными фразами»';
      break;
    case 'position_type':
      $description = 'Тип блока, если показ произошёл на странице с результатами поиска Яндекса';
      break;
    case 'position':
      $description = 'Точная позиция объявления в блоке';
      break;
    case 'keyword':
      $description = 'Ключевая фраза, по которой было показано объявление';
      break;
    case 'phrase':
      $description = 'Первое ключевое слово';
      break;
    case 'campagn-name':
      $description = 'Название кампании';
      break;
    case 'param1':
      $description = 'Первый параметр ключевой фразы';
      break;
    case 'param2':
      $description = 'Второй параметр ключевой фразы';
      break;
    case 'campaign_id':
      $description = 'Номер (ID) рекламной кампании';
      break;
    case 'ad_id':
      $description = 'Номер (ID) объявления';
      break;
    case 'banner_id':
      $description = 'Номер (ID) объявления';
      break;
    case 'phrase_id':
      $description = 'Номер (ID) ключевой фразы';
      break;
    case 'retargeting_id':
      $description = 'Номер (ID) условия ретаргетинга';
      break;
  }

  $output[] = $modx->getChunk($tpl, array(
    'key' => $key,
    'value' => $value,
    'description' => $description
  ));
}

// Return output
/** @var string $outputSeparator */
$output = implode($outputSeparator, $output);

if (!empty($tplWrapper) && (!empty($wrapIfEmpty) || !empty($output))) {
  $output = $modx->getChunk($tplWrapper, array(
    'output' => $output
  ));
}

return $output;
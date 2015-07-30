<?php
if ($object->xpdo) {
/*  function host_url ($s) {
    $ssl      = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true : false;
    $sp       = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')).(($ssl) ? 's' : '');
    $port     = $s['SERVER_PORT'];
    $port     = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':'.$port;
    $host     = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];

    return $protocol.'://'.$host.$port;
  }*/

  /* @var modX $modx */
  $modx =& $object->xpdo;
  switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
/*      $jsFolderPart       = 'components/qustha/js/';
      $bookmarkletFile    = $modx->getOption('assets_path').$jsFolderPart.'bookmarklet.js';
      $bookmarkletContent = file_get_contents($bookmarkletFile);
      //      if ($_SERVER['HTTPS']) {
      //        $hostUrl = host_url($_SERVER);
      //        $urlToInjectFile = $hostUrl . $modx->getOption('assets_url').$jsFolderPart .'yad.inject.js';
      //      } else {
      $urlToInjectFile = 'https://gist.github.com/antixrist/8f76e8a2ba401d551803/raw/545c12f718fdf23895d7ca39b09943bcd55bbc12/yaddr.inject.js';
      //      }
      $bookmarkletContent = str_replace('ABS_URL_TO_MAIN_JS_FILE', $urlToInjectFile, $bookmarkletContent);
      if (!file_put_contents($bookmarkletFile, $bookmarkletContent)) {
        $modx->log(modX::LOG_LEVEL_ERROR, 'qustha install: coudn\'t rewrite "'. $urlToInjectFile .'" file!');
      }*/
      break;
    case xPDOTransport::ACTION_UPGRADE:
      break;
    case xPDOTransport::ACTION_UNINSTALL:
      break;
  }
}
return true;

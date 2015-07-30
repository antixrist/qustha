<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 * Helpers
 */
if (!function_exists('http_build_url')) {
  define('HTTP_URL_REPLACE', 1); // Replace every part of the first URL when there's one of the second URL
  define('HTTP_URL_JOIN_PATH', 2); // Join relative paths
  define('HTTP_URL_JOIN_QUERY', 4); // Join query strings
  define('HTTP_URL_STRIP_USER', 8); // Strip any user authentication information
  define('HTTP_URL_STRIP_PASS', 16); // Strip any password authentication information
  define('HTTP_URL_STRIP_AUTH', 32); // Strip any authentication information
  define('HTTP_URL_STRIP_PORT', 64); // Strip explicit port numbers
  define('HTTP_URL_STRIP_PATH', 128); // Strip complete path
  define('HTTP_URL_STRIP_QUERY', 256); // Strip query string
  define('HTTP_URL_STRIP_FRAGMENT', 512); // Strip any fragments (#identifier)
  define('HTTP_URL_STRIP_ALL', 1024); // Strip anything but scheme and host
  function http_build_url ($url, $parts = array(), $flags = HTTP_URL_REPLACE, &$new_url = false) {
    $keys = array('user', 'pass', 'port', 'path', 'query', 'fragment');
    // HTTP_URL_STRIP_ALL becomes all the HTTP_URL_STRIP_Xs
    if ($flags & HTTP_URL_STRIP_ALL) {
      $flags |= HTTP_URL_STRIP_USER;
      $flags |= HTTP_URL_STRIP_PASS;
      $flags |= HTTP_URL_STRIP_PORT;
      $flags |= HTTP_URL_STRIP_PATH;
      $flags |= HTTP_URL_STRIP_QUERY;
      $flags |= HTTP_URL_STRIP_FRAGMENT;
    } // HTTP_URL_STRIP_AUTH becomes HTTP_URL_STRIP_USER and HTTP_URL_STRIP_PASS
    else if ($flags & HTTP_URL_STRIP_AUTH) {
      $flags |= HTTP_URL_STRIP_USER;
      $flags |= HTTP_URL_STRIP_PASS;
    }
    // Parse the original URL
    $parse_url = parse_url($url);
    // Scheme and Host are always replaced
    if (isset($parts['scheme'])) {
      $parse_url['scheme'] = $parts['scheme'];
    }
    if (isset($parts['host'])) {
      $parse_url['host'] = $parts['host'];
    }
    // (If applicable) Replace the original URL with it's new parts
    if ($flags & HTTP_URL_REPLACE) {
      foreach ($keys as $key) {
        if (isset($parts[$key])) {
          $parse_url[$key] = $parts[$key];
        }
      }
    } else {
      // Join the original URL path with the new path
      if (isset($parts['path']) && ($flags & HTTP_URL_JOIN_PATH)) {
        if (isset($parse_url['path'])) {
          $parse_url['path'] = rtrim(str_replace(basename($parse_url['path']), '', $parse_url['path']), '/').'/'.ltrim($parts['path'], '/');
        } else {
          $parse_url['path'] = $parts['path'];
        }
      }
      // Join the original query string with the new query string
      if (isset($parts['query']) && ($flags & HTTP_URL_JOIN_QUERY)) {
        if (isset($parse_url['query'])) {
          $parse_url['query'] .= '&'.$parts['query'];
        } else {
          $parse_url['query'] = $parts['query'];
        }
      }
    }
    // Strips all the applicable sections of the URL
    // Note: Scheme and Host are never stripped
    foreach ($keys as $key) {
      if ($flags & (int)constant('HTTP_URL_STRIP_'.strtoupper($key))) {
        unset($parse_url[$key]);
      }
    }
    $new_url = $parse_url;

    return
        ((isset($parse_url['scheme'])) ? $parse_url['scheme'].'://' : '')
        .((isset($parse_url['user'])) ? $parse_url['user'].((isset($parse_url['pass'])) ? ':'.$parse_url['pass'] : '').'@' : '')
        .((isset($parse_url['host'])) ? $parse_url['host'] : '')
        .((isset($parse_url['port'])) ? ':'.$parse_url['port'] : '')
        .((isset($parse_url['path'])) ? $parse_url['path'] : '')
        .((isset($parse_url['query'])) ? '?'.$parse_url['query'] : '')
        .((isset($parse_url['fragment'])) ? '#'.$parse_url['fragment'] : '');
  }
}
/**
 * //Helpers
 */
abstract class Storage {
  public $charset;

  function __construct ($config = array()) {
    $this->charset = (isset($config['charset'])) ? $config['charset'] : 'UTF-8';
  }

  function get ($name) { }

  function set ($name, $value) { }

  function delete ($name) { }

  function sanitize ($string) {
    return htmlentities(html_entity_decode($string, ENT_QUOTES, $this->charset), ENT_QUOTES, $this->charset);
  }
}


class sessionStorage extends Storage {
  function __construct ($config = array()) {
    parent::__construct($config);
    $this->ns = (isset($config['ns'])) ? $config['ns'] : '';
//    if ($this->ns) {
//      $_SESSION[$this->ns] = (isset($_SESSION[$this->ns])) ? $_SESSION[$this->ns] : array();
//      $this->storage =& $_SESSION[$this->ns];
//    } else {
//      $this->storage =& $_SESSION;
//    }
  }

  function get ($name) {
    if ($this->ns) {
      $_SESSION[$this->ns] = (isset($_SESSION[$this->ns])) ? $_SESSION[$this->ns] : array();
      return $_SESSION[$this->ns][$name];
    } else {
      return $_SESSION[$name];
    }

//    return $this->storage[$name];
  }

  function set ($name, $value) {
    $value = $this->sanitize($value);

    if ($this->ns) {
      $_SESSION[$this->ns] = (isset($_SESSION[$this->ns])) ? $_SESSION[$this->ns] : array();
      $_SESSION[$this->ns][$name] = $value;
    } else {
      $_SESSION[$name] = $value;
    }

//    $this->storage[$name] = $value;
  }

  function delete ($name) {
    if ($this->ns) {
      $_SESSION[$this->ns] = (isset($_SESSION[$this->ns])) ? $_SESSION[$this->ns] : array();
      unset($_SESSION[$this->ns][$name]);
    } else {
      unset($_SESSION[$name]);
    }

//    unset($this->storage[$name]);
  }
}


class cookieStorage extends Storage {
  function __construct ($config = array()) {
    parent::__construct($config);
    $this->expires = (isset($config['expires'])) ? $config['expires'] : 7;
    $this->domain  = (strpos($_SERVER['HTTP_HOST'], 'www.') > -1) ? substr($_SERVER['HTTP_HOST'], 4) : $_SERVER['HTTP_HOST'];
  }

  function get ($name) {
    return $_COOKIE[$name];
  }

  function set ($name, $value, $path = '/') {
    $value = $this->sanitize($value);
    setcookie($name, $value, time() + 60 * 60 * 24 * $this->expires, $path, $this->domain, (($_SERVER['HTTPS']) ? true : false), true);
  }

  function delete ($name, $path = '/') {
    setcookie($name, '', time() - 3600, $path, $this->domain, (($_SERVER['HTTPS']) ? true : false), true);
  }
}


class qustha {
  /* @var modX $modx */
  public $modx;

  /**
   * @param modX  $modx
   */
  function __construct (&$modx) {
    $this->modx         =& $modx;
    $this->ns           = 'qustha';

    $this->charset      = $this->modx->getOption('modx_charset');
    $this->sessionStore = $this->modx->getOption('qustha_session_store');
//    $this->modx->lexicon->load('qustha:default');

    if ((bool)$this->sessionStore) {
      $this->storage = new sessionStorage(array(
        'ns'      => $this->ns,
        'charset' => $this->charset
      ));
    } else {
      $this->storage = new cookieStorage(array(
        'ns'      => $this->ns,
        'charset' => $this->charset,
        'expires' => $this->modx->getOption('qustha_cookie_expires', 7)
      ));
    }
    $this->neededRedirect  = false;
    $this->redirectVars    = $this->arrayFromSettingVal('qustha_redirect_vars');
    $this->placeholderVars = $this->arrayFromSettingVal('qustha_placeholder_vars');
    $this->redirectVars    = $this->arrayFromSettingVal('qustha_redirect_vars');
//    $this->fiHookVars      = $this->arrayFromSettingVal('qustha_fi_hook_vars');
    $this->dependentVars   = $this->arrayFromSettingVal('qustha_dependent_vars');
    $this->storageVars     = $this->arrayFromSettingVal('qustha_storage_vars');

    $newStorageVars        = array_merge($this->storageVars, array_diff($this->dependentVars, $this->storageVars));
    $this->storageVars     = $newStorageVars;
    $this->setOption('qustha_storage_vars', implode(',', $newStorageVars));

    $this->currentUrl            = $this->full_url($_SERVER);
    $this->parsedCurrentUrl      = parse_url($this->currentUrl);
    $this->currentUrlQueryString = isset($this->parsedCurrentUrl['query']) ? $this->parsedCurrentUrl['query'] : '';
    $this->currentUrlQueryArray  = array();
    parse_str($this->currentUrlQueryString, $this->currentUrlQueryArray);
    $this->newUrlQueryArray = $this->currentUrlQueryArray;
  }

  function setOption($key = '', $value) {
    if (!$key) return false;
    $modSetting = $this->modx->getObject('modSystemSetting', $key);
    $modSetting->set('value', $value);
    return $modSetting->save();
  }

  function log ($param, $text = '') {
    echo $text."<br>\n";
    echo '<pre>';
    var_dump($param);
    echo '</pre>';
    exit();
  }

  function handleDependedVars () {
    $changed = false;
    foreach ($this->dependentVars as $dependentVar) if (isset($this->currentUrlQueryArray[$dependentVar])) {
      $value = $this->currentUrlQueryArray[$dependentVar];
      if ($this->storage->get($dependentVar) !== $value) {
        $changed = true;
        break;
      }
    }
    if ($changed) {
      foreach ($this->dependentVars as $dependentVar) $this->storage->delete($dependentVar);
    }
  }

  function handleStorageVars () {
    foreach ($this->storageVars as $storageVar) if (isset($this->currentUrlQueryArray[$storageVar])) {
      $value = $this->currentUrlQueryArray[$storageVar];
      $this->storage->set($storageVar, $value);
    }
  }

  function handleRedirectVars () {
    foreach ($this->redirectVars as $redirectVar) if (isset($this->currentUrlQueryArray[$redirectVar])) {
      unset ($this->newUrlQueryArray[$redirectVar]);
      $this->neededRedirect = true;
    }
  }

  function handleRedirect () {
    if ($this->neededRedirect) {
      // collect query string back
      $this->parsedCurrentUrl['query'] = http_build_query($this->newUrlQueryArray);
      if ($this->parsedCurrentUrl['query'] == '') {
        unset($this->parsedCurrentUrl['query']);
      }
      $this->redirect(http_build_url('', $this->parsedCurrentUrl));
    }
  }

  function handlePlaceholders () {
    foreach ($this->placeholderVars as $placeholderVar) {
      $valueFromQuery   = ($this->currentUrlQueryArray[$placeholderVar]) ? $this->currentUrlQueryArray[$placeholderVar] : '';
      $valueFromStorage = ($this->storage->get($placeholderVar, true)) ? $this->storage->get($placeholderVar) : '';
      $value = ($valueFromQuery) ? $valueFromQuery : $valueFromStorage;
      $this->modx->setPlaceholder($placeholderVar, $value);
    }
  }

  function run () {
    if (count($this->currentUrlQueryArray)) {
      $this->handleDependedVars();
      $this->handleStorageVars();
      $this->handleRedirectVars();
    }
    $this->handleRedirect();
    $this->handlePlaceholders();
  }

  function getVars ($type = '') {
    if (!$type) return false;

    $name = $type .'Vars';
    $result = array();
    foreach ($this->$name as $key) {
/*
      $valueFromQuery   = ($this->currentUrlQueryArray[$fiHookVar]) ? $this->currentUrlQueryArray[$fiHookVar] : '';
      $valueFromStorage = ($this->storage->get($fiHookVar, true)) ? $this->storage->get($fiHookVar) : '';
      $value = ($valueFromQuery) ? $valueFromQuery : $valueFromStorage;
*/
      if (($valueFromStorage = $this->storage->get($key)) == '') continue;
      $value = $valueFromStorage;
      $result[$key] = $value;
    }
    return $result;
  }

  function full_url ($s) {
    $ssl      = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true : false;
    $sp       = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')).(($ssl) ? 's' : '');
    $port     = $s['SERVER_PORT'];
    $port     = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':'.$port;
    $host     = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];

    return $protocol.'://'.$host.$port.$s['REQUEST_URI'];
  }

  function arrayFromSettingVal ($name = '') {
    $string = trim($this->modx->getOption($name, null, ''));
    $array = array();
    if ($string != '') {
      $array = array_map('trim', explode(',', $string));
    }

    return $array;
  }

  function redirect ($url, $code = 301) {
//    header('HTTP/1.1 301 Moved Permanently');
    @session_write_close();
    header('Location: '.$url, true, $code);
    exit();
  }
}
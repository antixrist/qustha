<?php

switch ($modx->event->name) {
  case 'OnHandleRequest':
    if ($modx->context->key == 'mgr') {
      return;
    }
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
    function full_url ($s) {
      $ssl      = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true : false;
      $sp       = strtolower($s['SERVER_PROTOCOL']);
      $protocol = substr($sp, 0, strpos($sp, '/')).(($ssl) ? 's' : '');
      $port     = $s['SERVER_PORT'];
      $port     = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':'.$port;
      $host     = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];

      return $protocol.'://'.$host.$port.$s['REQUEST_URI'];
    }

    // get full absolute url
    $absolute_url = full_url($_SERVER);
    // parse it to array
    $parsedUrl = parse_url($absolute_url);
    // needed $_GET variables
    $variables   = array('title', 'body', 'keyword');
    $yaddrQRVars = array();
    foreach ($variables as $var) {
      $varName = 'yaddr_'.$var.'_var';
      $value   = trim($modx->getOption($varName));
      if ($value != '' && $modx->getOption($varName.'_enabled')) {
        $yaddrQRVars[] = $value;
      }
    }
    // get query string
    $query = isset($parsedUrl['query']) ? $parsedUrl['query'] : false;
    if ($query) {
      $queryArr = array();
      // parse query string to array
      parse_str($query, $queryArr);
      $redirect = false;
      // cookie domain
      $domain    = (strpos($_SERVER['HTTP_HOST'], 'www.') > -1) ? substr($_SERVER['HTTP_HOST'], 4) : $_SERVER['HTTP_HOST'];
      $charset   = $modx->getOption('modx_charset');
      $cookieSet = array();
      foreach ($yaddrQRVars as $param) if (isset($queryArr[$param])) {
        $redirect = true;
        $value    = htmlentities(html_entity_decode($queryArr[$param], ENT_QUOTES, $charset), ENT_QUOTES, $charset);
        // set cookie
        setcookie($param, $value, time() + 60 * 60 * 24 * 7, '/', $domain, (($_SERVER['HTTPS']) ? true : false), true);
        // memorize what kind of cookie is set
        $cookieSet[] = $param;
        // unset this from query string
        unset($queryArr[$param]);
      }
      // collect query string back
      $parsedUrl['query'] = http_build_query($queryArr);
      if ($redirect) {
        // unset outdated cookies
        $cookieUnset = array_diff($yaddrQRVars, $cookieSet);
        foreach ($cookieUnset as $param) {
          setcookie($param, '', time() - 3600, '/', $domain, (($_SERVER['HTTPS']) ? true : false), true);
        }
        // redirect to url without needed $_GET variable
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: '.http_build_url('', $parsedUrl));
        exit();
      }
    }
    // check cookies
    foreach ($yaddrQRVars as $param) {
      if (isset($_COOKIE[$param])) {
        // set placeholders:
        // [[+ydTitle]], [[+ydBody]], [[utm_term]]
        $modx->setPlaceholder($param, $_COOKIE[$param]);
      }
    }
}
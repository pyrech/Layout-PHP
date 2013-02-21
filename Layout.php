<?php

class Layout {

  const DOCTYPE_HTML5                 = 1;
  const DOCTYPE_HTML4_01_TRANSITIONAL = 2;
  const DOCTYPE_HTML4_01_STRICT       = 3;
  const DOCTYPE_HTML4_01_FRAMESET     = 4;
  const DOCTYPE_XHTML1_0_TRANSITIONAL = 5;
  const DOCTYPE_XHTML1_0_STRICT       = 6;
  const DOCTYPE_XHTML1_0_FRAMESET     = 7;

  public function __construct() { }

  public static function getInstance() {
    return new Layout();
  }

  public static function getDoctype($version = self::DOCTYPE_HTML5) {
    $doctype = '';
    switch($version) {
      case self::DOCTYPE_HTML5:
       $doctype = '<!DOCTYPE html>';
       break;
      case self::DOCTYPE_HTML4_01_TRANSITIONAL:
       $doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
       break;
      case self::DOCTYPE_HTML4_01_STRICT:
       $doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
       break;
      case self::DOCTYPE_HTML4_01_FRAMESET:
       $doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
       break;
      case self::DOCTYPE_XHTML1_0_TRANSITIONAL:
       $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
       break;
      case self::DOCTYPE_XHTML1_0_STRICT:
       $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
       break;
      case self::DOCTYPE_XHTML1_0_FRAMESET:
       $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
       break;
    }
    return $doctype;
  }

  public static function getMeta($key_value, $content_value) {
    $key_attribute = 'name';
    if (strpos($key_value, ':') !== false) {
      list($key_attribute, $key_value) = explode(':', $key_value, 2);
    }
    $attributes = array($key_attribute.'="'.$key_value.'"');
    if (!empty($content_value)) {
      $attributes[] = 'content="'.$content_value.'"';
    }
    return '<meta '.join(' ', $attributes).' />';
  }

  private function getHead($opts) {
    $head = self::getDoctype($opts['doctype']);
    $head .= '
<!--[if lte IE 7]><html class="no-js ie7 oldie" lang="fr"><![endif]-->
<!--[if IE 8]><html class="no-js ie8 oldie" lang="fr"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="fr"><!--<![endif]-->
  <head>';
    if (array_key_exists('meta', $opts) && is_array($opts['meta'])) {
      foreach ($opts['meta'] as $name => $content) {
        $head .= '
    '.self::getMeta($name, $content);
      }
    }
    $head .='
    <title>'.$opts['title'].'</title>
    if (array_key_exists('styles', $opts) && is_array($opts['styles'])) {
      foreach ($opts['styles'] as $style) {
        if (!is_array($style)) {
          $media = 'all';
          $href = $style;
        }
        else {
          $href  = $style['href'];
          $media = $style['media'];
        }
        $head .= '
    <link href="'.$href.'" rel="stylesheet" media="'.$media'." />';
      }
    }

    if (array_key_exists('icon', $opts) && is_array($opts['icon'])) {
      if (array_key_exists('png', $opts)) {
        $head .= '
    <link rel="icon" type="image/png" href="'.$opts['icon']['png'].'">';
      }
      if (array_key_exists('ico', $opts)) {
        $head .= '
    <link rel="shortcut icon" type="image/x-icon" href="'.$opts['icon']['ico'].'">';
      }
    }
    $defer = array_key_exists('defer', $opts) ? $opts['defer'] : false;
    if (array_key_exists('scripts', $opts) && is_array($opts['scripts'])) {
      foreach ($opts['scripts'] as $script) {
        if (is_array($script) && array_key_exists('internal', $script)) {
          $head .= '
    <script type="text/javascript">'.$script['internal'].'</script>';
        }
        else {
          $head .= '
    <script src="'.$script.'" type="text/javascript"'.($defer?' defer':'').'></script>';
        }
      }
    }
    $head .= '
  </head>
  <body class="'.join(' ', (array)$opts['class']).'">';
    return $head;
  }

  private function getFooter() {
    return '
  </body>
</html>';
  }

  public function render($opts, $content) {
    return $this->getHead($opts).$content.$this->getFooter();
  }

}

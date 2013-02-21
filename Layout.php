<?php

/**
 * Wrapper of the HTML Layout
 *
 * This class provide a wrapper of the Layout. You can choose the doctype,
 * title, meta, styles, scripts, etc
 *
 * @author     Loick Piera <contact@loickpiera.com>
 * 
 */

class Layout {

  /**
   * Constants for each Doctype available
   * 
   * @param const int
   */
  const DOCTYPE_HTML5                 = 1;
  const DOCTYPE_HTML4_01_TRANSITIONAL = 2;
  const DOCTYPE_HTML4_01_STRICT       = 3;
  const DOCTYPE_HTML4_01_FRAMESET     = 4;
  const DOCTYPE_XHTML1_0_TRANSITIONAL = 5;
  const DOCTYPE_XHTML1_0_STRICT       = 6;
  const DOCTYPE_XHTML1_0_FRAMESET     = 7;

  public function __construct() { }

  /**
   * Return a new Layout
   * 
   * @return Layout
   */
  public static function getInstance() {
    return new Layout();
  }

  /**
   * Return the doctype according to the constant passed to the function
   * 
   * @param int $version
   * @return string
   */
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

  /**
   * Return the meta tag
   *
   * The default key attribute is name. If you need a custom key,
   * you simply have to prefixe the key_value by 'my-key:'.
   *
   * Eg : if you need a meta refresh of 60, you should call this method like that :
   * Layout::getMeta('http-equiv:refresh', 60)
   *
   * Or with the array $opts in render method : $opts['meta']['http-equiv:refresh'] = 60
   * 
   * @param string $key_value
   * @param string $content_value
   * @return string
   */
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

  /**
   * Return the link tag
   * 
   * $style can be a string and represent the href of the file and media by default is 'all',
   * or an array with a key 'href' and 'media' 
   * 
   * @param mixed $style
   * @return string
   */
  public static function getStyle($style) {
    if (is_array($style)) {
      $href  = $style['href'];
      $media = $style['media'];
    }
    elseif (is_string($style)) {
      $media = 'all';
      $href = $style;
    }
    else {
      return '';
    }
    return '<link href="'.$href.'" rel="stylesheet" media="'.$media.'" />';
  }

  /**
   * Return the script tag
   *
   * $script can be a string and represent the src of the file,
   * or an array with a key 'internal' which represent the js inside the script's tag  
   * 
   * @param mixed $script
   * @param boolean $defer
   * @return string
   */
  public static function getScript($script, $defer) {
    if (is_array($script) && array_key_exists('internal', $script)) {
      return '<script type="text/javascript">'.$script['internal'].'</script>';
    }
    elseif (is_string($script)) {
      return '<script src="'.$script.'" type="text/javascript"'.($defer?' defer':'').'></script>';
    }
    else {
      return '';
    }
  }

  /**
   * Return the top of the layout (doctype, head, open body)
   *
   * Include the doctype, open html tag, add meta, stylesheets, scripts in the head and open body tag.
   *
   * Options available :
   *
   * doctype -> one of the DOCTYPE_X constant
   * meta    -> array of meta. @see Layout::getMeta() for details
   * title   -> title of the page
   * styles  -> array of style. @see Layout::getStyle() for details
   * icon    -> array with the url of favicon in .png and/or .ico
   *            Eg : $opts['icon'] = array('png' => '/favicon.png', 'ico' => '/favicon.ico');
   * scripts -> array of script. @see Layout::getScript() for details
   * defer   -> boolean. @see Layout::getScript() for details
   * class   -> array of classnames for body
   * 
   * @param string $key_value
   * @param string $content_value
   * @return string
   */
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
    <title>'.$opts['title'].'</title>';
    if (array_key_exists('styles', $opts) && is_array($opts['styles'])) {
      foreach ($opts['styles'] as $style) {
        $head .= '
    '.self::getStyle($style);
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
        $head .= '
    '.self::getScript($script, $defer);
      }
    }
    $head .= '
  </head>
  <body class="'.join(' ', (array)$opts['class']).'">';
    return $head;
  }

  /**
   * Return the footer of HTML
   *
   * Close body et html tags
   * 
   * @return string
   */
  private function getFooter() {
    return '
  </body>
</html>';
  }

  /**
   * Return the entire HTML
   *
   * Require the $opts array for the head section and $content to include in the layout
   * 
   * @param string $opts
   * @param string $content
   * @return string
   */
  public function render($opts, $content) {
    return $this->getHead($opts).$content.$this->getFooter();
  }

}

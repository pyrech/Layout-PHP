<?php

namespace Pyrech;

/**
 * Wrapper of the HTML Layout
 *
 * This class provides a wrapper for the HTML layout. You can customize the doctype,
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
  const DOCTYPE_HTML4_01_TRANSITIONAL = 1;
  const DOCTYPE_HTML4_01_STRICT       = 2;
  const DOCTYPE_HTML4_01_FRAMESET     = 3;
  const DOCTYPE_XHTML1_0_TRANSITIONAL = 4;
  const DOCTYPE_XHTML1_0_STRICT       = 5;
  const DOCTYPE_XHTML1_0_FRAMESET     = 6;
  const DOCTYPE_HTML5                 = 7;


  /**
   * Array of doctypes available
   *
   * @static
   * @param array
   */
  public static $doctypes_available = array(DOCTYPE_HTML4_01_TRANSITIONAL,
                                            DOCTYPE_HTML4_01_STRICT,
                                            DOCTYPE_HTML4_01_FRAMESET,
                                            DOCTYPE_XHTML1_0_TRANSITIONAL,
                                            DOCTYPE_XHTML1_0_STRICT,
                                            DOCTYPE_XHTML1_0_FRAMESET,
                                            DOCTYPE_HTML5);

  /**
   * Doctype setted. Default is HTML5
   *
   * @param int
   */
  private $doctype = self::DOCTYPE_HTML5;

  /**
   * Body classes.
   *
   * @param string
   */
  private $body_classes = "";

  /**
   * Array of element to add in head section
   *
   * @param array
   */
  private $head = '';


  /**
   * Public constructor
   * 
   * @static
   * @return void
   */
  public function __construct() { }

  /**
   * Return a new Layout
   * 
   * @static
   * @return Layout
   */
  public static function getInstance() {
    return new Layout();
  }

  /**
   * Return the doctype tag according to the doctype setted
   * 
   * @return string
   */
  public function getDoctypeTag() {
    $str = '';
    switch($this->doctype) {
      case self::DOCTYPE_HTML4_01_TRANSITIONAL:
       $str = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
       break;
      case self::DOCTYPE_HTML4_01_STRICT:
       $str = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
       break;
      case self::DOCTYPE_HTML4_01_FRAMESET:
       $str = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
       break;
      case self::DOCTYPE_XHTML1_0_TRANSITIONAL:
       $str = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
       break;
      case self::DOCTYPE_XHTML1_0_STRICT:
       $str = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
       break;
      case self::DOCTYPE_XHTML1_0_FRAMESET:
       $str = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
       break;
      case self::DOCTYPE_HTML5:
       $str = '<!DOCTYPE html>';
       break;
    }
    return $str;
  }

  /**
   * Set the doctype according to the doctype passed to the function
   *
   * $doctype should be one in Pyrech\Layout::$doctypes_available
   * 
   * @param int $doctype
   * @return string
   */
  public function setDoctype($doctype) {
    if (in_array($doctype, self::$doctypes_available)) {
      $this->doctype = $doctype;
    }
  }

  /**
   * Add a new element to the head
   * 
   * @param string $element
   * @return string
   */
  public function addElement($element) {
    $this->head[] = $element;
  }

  /**
   * Return a meta tag
   *
   * The default key attribute is name. If you need a custom key,
   * you simply have to prefixe the key_value by 'my-key:'.
   *
   * Eg : if you need a meta refresh of 60, you should call this method like that :
   * Pyrech\Layout::getMetaTag('http-equiv:refresh', 60)
   *
   * Or with the array $opts in render method : $opts['meta']['http-equiv:refresh'] = 60
   * 
   * @param string $key_value
   * @param string $content_value
   * @return string
   */
  public function getMetaTag($key_value, $content_value) {
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
   * Add a meta tag to the head section
   * 
   * @see Pyrech\Layout::getMetaTag for details
   * @param string $key_value
   * @param string $content_value
   * @return void
   */
  public function addMeta($key_value, $content_value) {
    $this->addElement($this->getMetaTag($key_value, $content_value));
  }

  /**
   * Return a link tag
   * 
   * $link is an array of attributes to set to the tag
   * 
   * @param array $link
   * @return string
   */
  public function getLinkTag($link) {
    if (!is_array($link)) {
      return '';
    }
    $tag = '<link ';
    foreach ($link as $key => $value) {
      $tag .= $key.'="'.$value.'" ';
    }
    return $tag.'/>';
  }

  /**
   * Add a link tag to the head section
   * 
   * @see Pyrech\Layout::getLinkTag for details
   * @param array $link
   * @return void
   */
  public function addLink($link) {
    $this->addElement($this->getLinkTag($link));
  }

  /**
   * Return a link tag
   * 
   * $style can be a string and represent the href of the file and media by default is 'all',
   * or an array with a key 'href' and 'media' 
   * 
   * @param mixed $style
   * @return string
   */
  public function getStyleTag($style) {
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
    return $this->getLinkTag(array('href'  => $href,
                                   'rel'   => 'stylesheet',
                                   'media' => $media));
  }

  /**
   * Add a style tag to the head section
   * 
   * @see Pyrech\Layout::getStyleTag for details
   * @param mixed $style
   * @return void
   */
  public function addStyle($style) {
    $this->addElement($this->getStyleTag($style));
  }

  /**
   * Return a script tag
   *
   * $script can be a string and represent the src of the file,
   * or an array with a key 'internal' which represent the js inside the script's tag  
   * 
   * @param mixed $script
   * @param boolean $defer
   * @return string
   */
  public function getScriptTag($script, $defer=false) {
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
   * Add a script tag to the head section
   * 
   * @see Pyrech\Layout::getScriptTag for details
   * @param mixed $script
   * @param boolean $defer
   * @return void
   */
  public function addScript($script, $defer=false) {
    $this->addElement($this->getScriptTag($script, $defer));
  }

  /**
   * Return a title tag
   * 
   * @param string $title
   * @return string
   */
  public function getTitleTag($title) {
    return '<title>'.$title.'</title>';
  }

  /**
   * Add a title tag to the head section
   * 
   * @param string $title
   * @return void
   */
  public function addTitle($title) {
    $this->addElement($this->getTitleTag($title));
  }

  /**
   * Return a icon tag
   * 
   * $style can be a string and represent the href of the file and media by default is 'all',
   * or an array with a key 'href' and 'media' 
   * 
   * @param string $type
   * @param string $href
   * @return string
   */
  public function getIconTag($type, $href) {
    switch ($type) {
      case 'png':
        return $this->getLinkTag(array('rel'  => 'icon',
                                       'type' => 'image/png',
                                       'href' => $href));
        break;

      case 'ico':
        return $this->getLinkTag(array('rel'  => 'shortcut icon',
                                       'type' => 'image/x-icon',
                                       'href' => $href));
        break;
      
      default:
        return '';
        break;
    }
  }

  /**
   * Add an icon to the head section
   * 
   * @see Pyrech\Layout::getStyleTag for details
   * @param string $type
   * @param string $href
   * @return void
   */
  public function addIcon($type, $href) {
    $this->addElement($this->getIconTag($type, $href));
  }

  /**
   * Set body classnames
   *
   * $classes is an array of classnames or string for body
   * 
   * @param mixed $classes
   * @return void
   */
  public function addBodyClass($classes) {
    $str = '';
    if (is_string($classes)) {
      $str = $classes;
    }
    else if (is_array($classes)) {
      $str = join(' ', $classes);
    }
    $this->body_classes = trim($this->body_classes.' '.$str);
  }

  /**
   * Return the top of the layout (doctype, head, open body)
   *
   * Include the doctype, open html tag, add meta, stylesheets, scripts in the head and open body tag.
   *
   * Options available :
   *
   * doctype -> one of the DOCTYPE_X constant.@see Pyrech\Layout::setDoctype() for details
   * meta    -> array of meta. @see Pyrech\Layout::getMetaTag() for details
   * title   -> title of the page. @see Pyrech\Layout::getTitleTag() for details
   * styles  -> array of style. @see Pyrech\Layout::getStyleTag() for details
   * icon    -> array with the url of favicon in .png and/or .ico
   *            Eg : $opts['icon'] = array('png' => '/favicon.png', 'ico' => '/favicon.ico');
   *            @see Pyrech\Layout::getIconTag() for details
   * scripts -> array of script. @see Pyrech\Layout::getScriptTag() for details
   * defer   -> boolean. @see Pyrech\Layout::getScriptTag() for details
   * class   -> mixed. @see Pyrech\Layout::addBodyClass() for details
   * 
   * @param string $key_value
   * @param string $content_value
   * @return string
   */
  private function getHead(array $opts) {
    if (array_key_exists('doctype', $opts)) {
      $this->setDoctype($opts['doctype']);
    }
    $head = $this->getDoctypeTag();
    $head .= '
<!--[if lte IE 7]><html class="no-js ie7 oldie" lang="fr"><![endif]-->
<!--[if IE 8]><html class="no-js ie8 oldie" lang="fr"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="fr"><!--<![endif]-->
  <head>';
    if (array_key_exists('meta', $opts) && is_array($opts['meta'])) {
      foreach ($opts['meta'] as $name => $content) {
        $this->addMeta($name, $content);
      }
    }
    if (array_key_exists('styles', $opts)) {
        $this->addTitle($name, $content);
    }
    if (array_key_exists('styles', $opts) && is_array($opts['styles'])) {
      foreach ($opts['styles'] as $style) {
        $this->addStyle($style);
      }
    }

    if (array_key_exists('icon', $opts) && is_array($opts['icon'])) {
      foreach ($opts['icon'] as $type => $icon) {
        $this->addIcon($type, $icon);
      }
    }
    $defer = array_key_exists('defer', $opts) ? $opts['defer'] : false;
    if (array_key_exists('scripts', $opts) && is_array($opts['scripts'])) {
      foreach ($opts['scripts'] as $script) {
        $this->addScript($script, $defer);
      }
    }
    if (array_key_exists('class', $opts)) {
      $this->addBodyClass($opts['class']);
    }
    foreach ($this->head as $value) {
      $head .= '
    '.$value;
    }
    $head .= '
  </head>
  <body class="'.$this->body_classes.'">
';
    return $head;
  }

  /**
   * Return the HTML footer
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
   * @param string $content
   * @param array $opts
   * @return string
   */
  public function render($content, array $opts=array()) {
    return $this->getHead($opts).$content.$this->getFooter();
  }

}

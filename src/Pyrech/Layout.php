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
   * Constant for defer the loading of the script
   * 
   * @var const int
   */
  const SCRIPT_DEFER    = 1;

  /**
   * Constant for an internal script
   * 
   * @var const int
   */
  const SCRIPT_INTERNAL = 2;

  /**#@+
   * Constants for each Doctype available
   * 
   * @var const int
   */
  const DOCTYPE_HTML4_01_TRANSITIONAL = 1;
  const DOCTYPE_HTML4_01_STRICT       = 2;
  const DOCTYPE_HTML4_01_FRAMESET     = 3;
  const DOCTYPE_XHTML1_0_TRANSITIONAL = 4;
  const DOCTYPE_XHTML1_0_STRICT       = 5;
  const DOCTYPE_XHTML1_0_FRAMESET     = 6;
  const DOCTYPE_HTML5                 = 7;
  /**#@-*/

  /**
   * Array of doctypes available
   *
   * @static
   * @var array
   */
  public static $doctypes_available = array(self::DOCTYPE_HTML4_01_TRANSITIONAL,
                                            self::DOCTYPE_HTML4_01_STRICT,
                                            self::DOCTYPE_HTML4_01_FRAMESET,
                                            self::DOCTYPE_XHTML1_0_TRANSITIONAL,
                                            self::DOCTYPE_XHTML1_0_STRICT,
                                            self::DOCTYPE_XHTML1_0_FRAMESET,
                                            self::DOCTYPE_HTML5);

  /**
   * Doctype setted. Default is HTML5
   *
   * @access int
   * @var int
   */
  private $doctype = self::DOCTYPE_HTML5;

  /**
   * Body classes.
   *
   * @var string
   */
  private $body_classes = "";

  /**
   * Array of element to add in head section
   *
   * @var array
   */
  private $head = array();


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
   * @return Pyrech\Layout
   */
  public function setDoctype($doctype) {
    if (in_array($doctype, self::$doctypes_available)) {
      $this->doctype = $doctype;
    }
    return $this;
  }

  /**
   * Add a new element to the head
   * 
   * @param string $element
   * @return Pyrech\Layout
   */
  public function addElement($element) {
    $this->head[] = $element;
    return $this;
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
    if ($key_value == 'charset' && $this->doctype == self::DOCTYPE_HTML5) {
      $key_attribute = 'charset';
      $key_value = $content_value;
      unset($content_value);
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
   * @return Pyrech\Layout
   */
  public function addMeta($key_value, $content_value) {
    $this->addElement($this->getMetaTag($key_value, $content_value));
    return $this;
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
   * @return Pyrech\Layout
   */
  public function addLink($link) {
    $this->addElement($this->getLinkTag($link));
    return $this;
  }

  /**
   * Return a link tag
   * 
   * @param string $href
   * @param string $media
   * @return string
   */
  public function getStyleTag($href, $media='all') {
    return $this->getLinkTag(array('href'  => $href,
                                   'rel'   => 'stylesheet',
                                   'media' => $media));
  }

  /**
   * Add a style tag to the head section
   * 
   * @see Pyrech\Layout::getStyleTag for details
   * @param string $href
   * @param string $media
   * @return Pyrech\Layout
   */
  public function addStyle($href, $media='all') {
    $this->addElement($this->getStyleTag($href, $media));
    return $this;
  }

  /**
   * Return a script tag
   *
   * $src is a string which represents the src of the script or the script content if
   * Pyrech\Layout::SCRIPT_INTERNAL is passed in $opts
   * 
   * @param string $src
   * @param int $opts
   * @return string
   */
  public function getScriptTag($src, $opts=0) {
    if (($opts & self::SCRIPT_INTERNAL) > 0) {
      return '<script type="text/javascript">'.$src.'</script>';
    }
    return '<script src="'.$src.'" type="text/javascript"'.(($opts & self::SCRIPT_DEFER) > 0?' defer':'').'></script>';
  }

  /**
   * Add a script tag to the head section
   * 
   * @see Pyrech\Layout::getScriptTag for details
   * @param mixed $script
   * @param boolean $defer
   * @return Pyrech\Layout
   */
  public function addScript($src, $opts=0) {
    $this->addElement($this->getScriptTag($src, $opts));
    return $this;
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
   * @return Pyrech\Layout
   */
  public function addTitle($title) {
    $this->addElement($this->getTitleTag($title));
    return $this;
  }

  /**
   * Return a icon tag
   * 
   * $style can be a string and represent the href of the file and media by default is 'all',
   * or an array with a key 'href' and 'media' 
   * 
   * @param string $href
   * @param string $type
   * @return string
   */
  public function getIconTag($href, $type='png') {
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
   * @param string $href
   * @param string $type
   * @return Pyrech\Layout
   */
  public function addIcon($href, $type='png') {
    $this->addElement($this->getIconTag($href, $type));
    return $this;
  }

  /**
   * Set body classnames
   *
   * $classes is an array of classnames or string for body
   * 
   * @param mixed $classes
   * @return Pyrech\Layout
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
    return $this;
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
   * styles  -> array of style. Each item can be href or href => media.
   *            Eg : $opts['styles'] = array('/style.css', '/print.css' => 'print');
   *            @see Pyrech\Layout::getStyleTag() for details
   * icon    -> array with the url of favicon in .png and/or .ico
   *            Eg : $opts['icon'] = array('/favicon.png' => 'png', '/favicon.ico' => 'ico');
   *            @see Pyrech\Layout::getIconTag() for details
   * scripts -> array of script. Each item can be src or src => opts.
   *            Eg : $opts['scripts'] = array('/script.js', '/script2.js' => $script_options);
   *            @see Pyrech\Layout::getScriptTag() for details
   * defer   -> boolean. @see Pyrech\Layout::getScriptTag() for details
   * class   -> mixed. @see Pyrech\Layout::addBodyClass() for details
   * 
   * @param array $opts
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
    if (array_key_exists('title', $opts)) {
        $this->addTitle($opts['title']);
    }
    if (array_key_exists('icon', $opts) && is_array($opts['icon'])) {
      foreach ($opts['icon'] as $href => $type) {
        $this->addIcon($href, $type);
      }
    }
    if (array_key_exists('styles', $opts) && is_array($opts['styles'])) {
      foreach ($opts['styles'] as $href => $media) {
        if (is_integer($href)) {
          $this->addStyle($media);
        }
        else {
          $this->addStyle($href, $media);
        }
      }
    }
    $defer = array_key_exists('defer', $opts) && $opts['defer'] ? true : false;
    if (array_key_exists('scripts', $opts) && is_array($opts['scripts'])) {
      foreach ($opts['scripts'] as $src => $script_opts) {
        if (is_integer($src)) {
          $src = $script_opts;
          $script_opts = 0;
        }
        if ($defer) {
          $script_opts |= self::SCRIPT_DEFER;
        }
        $this->addScript($src, $script_opts);
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
   * Require the $content to include in the layout
   *
   * @see Pyrech\Layout::getHead() for more details on $opts
   * @param string $content
   * @param array $opts
   * @return string
   */
  public function render($content, array $opts=array()) {
    return $this->getHead($opts).$content.$this->getFooter();
  }

}

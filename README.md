Layout-PHP
==========

A PHP class that render HTML Layout. You can choose meta, stylesheet, scripts

You can use it like that :

    $content = '<h1>Hello World</h1>';

    $opts = array('doctype' => Layout::DOCTYPE_HTML5,
                  'meta'    => array('charset'            => 'utf-8',
                                     'description'        => 'Description of your page',
                                     'robot'              => 'index',
                                     'http-equiv:refresh' => '60'),
                  'title'   => 'My wonderful title',
                  'class'   => array('some-class', 'another-class'),
                  'icon'    => array('png' => '/favicon.png',
                                     'ico' => '/favicon.ico'),
                  'styles'  => array('/my-stylesheet.css',
                                     array('media' => 'print',
                                           'href'  => '/print.css')),
                  'scripts' => array('/my-js.css',
                                     array('internal' => 'alert("Hello World !")')),
                  'defer'   => true);
                  
    echo Layout::getInstance()->render($opts, $content);

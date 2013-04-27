Layout-PHP
==========

A PHP class that wrap and render an HTML Layout. You can customize the doctype, meta, stylesheet, scripts, etc. 

See an example below :


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
                                     array('internal' => 'alert("Hello World!");')),
                  'defer'   => true);
                  
    echo Layout::getInstance()->render($content, $opts);

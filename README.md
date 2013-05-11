Layout-PHP
==========

Don't handle the HTML doctype, meta, title, style, script, etc, use Pyrech\Layout.

Layout is a PHP class that wrap and render an HTML Layout. You can customize the doctype, meta, stylesheet, scripts, etc. 

The class can be used in two ways. You can either specify your settings in the render method or add each element in head section in the order you want.

First way (via render method):

    $content = '<h1>Hello World</h1>';

    $layout1 = \Pyrech\Layout::getInstance(); // Or new \Pyrech\Layout();

    $opts = array('doctype' => \Pyrech\Layout::DOCTYPE_HTML5,
                  'meta'    => array('charset'            => 'utf-8',
                                     'description'        => 'Description of your page',
                                     'robot'              => 'index',
                                     'http-equiv:refresh' => '60'), // If the attribute is not name, prefix the value by the attribute and ':'
                  'title'   => 'My wonderful title',
                  'icon'    => array('png' => '/favicon.png',
                                     'ico' => '/favicon.ico'),
                  'styles'  => array('/my-stylesheet.css',
                                     array('media' => 'print',
                                           'href'  => '/print.css')),
                  'scripts' => array('/my-javascript.js',
                                     array('internal' => 'alert("Hello World!");')),
                  'defer'   => true,
                  'class'   => array('some-class', 'another-class')); // Array of classes or a string with several classes
                  
    echo $layout1->render($content, $opts);

Second way (add each element as you want):

    $content = '<h1>Hello World</h1>';

    $layout2 = \Pyrech\Layout::getInstance(); // Or new \Pyrech\Layout();

    $layout2->setDoctype(\Pyrech\Layout::DOCTYPE_HTML5)
            ->addMeta('charset', 'utf-8')
            ->addTitle('My wonderful title')
            ->addMeta('description', 'Description of your page')
            ->addMeta('robot', 'index')
            ->addMeta('http-equiv:refresh', '60') // If the attribute is not name, prefix the value by the attribute and ':''
            ->addIcon('png', '/favicon.png')
            ->addIcon('ico', '/favicon.ico')
            ->addStyle('/my-stylesheet.css')
            ->addStyle(array('media' => 'print',
                             'href'  => '/print.css'))
            ->addScript('/my-javascript.js', true) // Second parameter is to set defer or not
            ->addScript(array('internal' => 'alert("Hello World!")'))
            ->addBodyClass(array('some-class', 'another-class')); // Array of classes or a string with several classes

    echo $layout2->render($content);

If you want to insert a custom element in the head part, you must use the second way (see above) and call the addElement method :

    $layout->addElement('<!--Your html comment-->');

Pyrech\Layout can be implemented in any PHP framework or can be used in a simple structure. Available via composer : pyrech/layout
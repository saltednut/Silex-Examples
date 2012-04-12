<?php

require __DIR__ . '/../vendor/.composer/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/vendor/silex-extensions/src');
$app->register(new SilexExtension\MongoDbExtension(),
  array(
    'mongodb.class_path' => __DIR__ . 'vendor/doctrine/mongodb/lib',
    'mongodb.connection' => array(
      'configuration' => function($configuration) {
        $configuration->setLoggerCallable(function($logs){
          print_r($logs);
        });
      },
    )
  )
);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path'       => __DIR__.'/../views',
  'twig.class_path' => __DIR__.'vendor/twig/twig/lib',
));

$app->get('/{collection}/{document_id}', function($collection, $document_id) use($app) {
  $_id = new MongoId($document_id);
  $result = $app['mongodb']->database->$collection->findOne(array('_id' => $_id));
  // result requires title, description and date keys from results
  return $app['twig']->render('page.twig', array(
    'result' => $result
  ));
});

return $app;

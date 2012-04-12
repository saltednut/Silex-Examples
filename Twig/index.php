<?php

require_once __DIR__.'/silex.phar';

$app = new Silex\Application();
// $app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path'       => __DIR__.'/views',
  'twig.class_path' => __DIR__.'/vendor/twig/twig/lib',
));

$app->get('/', function() use($app) {
  return $app['twig']->render('index.twig', array(
    'path' => 'home',
  ));
});

$app->get('/{path}', function($path) use($app) {
  return $app['twig']->render('index.twig', array(
    'path' => $path,
  ));
});

$app->run();

?>

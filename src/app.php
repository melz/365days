<?php
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Nicl\Silex\MarkdownServiceProvider;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new TwigServiceProvider(), array(
  'twig.path'    => array(__DIR__.'/../views')
));
$app->register(new MonologServiceProvider(), array(
  'monolog.logfile' => __DIR__.'/../logs/dev.log',
));
$app->register(new MarkdownServiceProvider());

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
  // add custom globals, filters, tags, ...
  return $twig;
}));

return $app;
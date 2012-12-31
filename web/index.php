<?php
use Symfony\Component\HttpFoundation\Request;
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

// Register some useful stuff
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => __DIR__.'/../logs/dev.log',
));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/../views',
));

/* Homepage */
$app->get('/', function() use($app) {
  // Determine the number of day for today
  $start_date = new DateTime("2012-12-29");
  $today = new DateTime("now");
  $interval = $today->diff($start_date);
  $day_num = $interval->format('%a');

  return $app->redirect($app['url_generator']->generate('view_by_day', array('num' => $day_num)));
})
->bind('homepage');

/* About */
$app->get('/about', function() use($app) {
  return $app['twig']->render('about.html.twig');
})
->bind('about');

/* Archives */
$app->get('/archives', function() use($app) {
  return $app['twig']->render('archives.html.twig');
})
->bind('archives');

/* View By Day */
$app->get('/day/{num}', function($num) use($app) {
  // Check if this day exists. Redirects to 404 if not.
  $meta_file_path = __DIR__.'/../data/metadata/'.str_pad($num, 3, 0, STR_PAD_LEFT).".json";
  //$this->forward404Unless(file_exists($meta_file_path));

  // Grab meta information for this day
  $data = file_get_contents($meta_file_path);
  $meta_data = json_decode($data, true);

  // Determine if there is a Prev/Next
  $prev = $num - 1;
  $next = $num + 1;
  $has_prev = file_exists(__DIR__.'/../data/metadata/'.str_pad($prev, 3, 0, STR_PAD_LEFT).".json");
  $has_next = file_exists(__DIR__.'/../data/metadata/'.str_pad($next, 3, 0, STR_PAD_LEFT).".json");

  return $app['twig']->render('view_by_day.html.twig', array(
    'num'        => $num,
    'padded_num' => str_pad($num, 3, 0, STR_PAD_LEFT),
    'meta_data'  => $meta_data,
    'has_prev'   => $has_prev,
    'has_next'   => $has_next
  ));
})
->assert('num', '\d+')
->bind('view_by_day');

$app['debug'] = true;
$app->run();
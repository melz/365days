<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/* Homepage */
$app->get('/', function() use($app) {
  // Determine the number of day for today
  $start_date = new DateTime(START_DATE);
  $today = new DateTime("now");
  $interval = $today->diff($start_date);
  $day_num = $interval->format('%a') + 1;

  $last_added = trim(file_get_contents(__DIR__.'/../data/last_added'));
  if ($day_num > $last_added)
    $day_num = $last_added;
  
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
  $files = scandir(__DIR__.'/../data/metadata/', 1);
  $recent_photos = array();

  foreach($files as $f)
  {
    $md_file = __DIR__.'/../data/metadata/'.$f;
    if (is_readable($md_file) && $f != "." && $f != ".." && $f != "empty")
    {
      $data = file_get_contents($md_file);
      $meta_data = json_decode($data, true);
      $fname = explode(".", $f);
      $recent_photos[] = array(
        'day'        => intval($fname[0]),
        'padded_day' => $fname[0],
        'title'      => $meta_data["entry_title"],
      );
    }
  }
  return $app['twig']->render('archives.html.twig', array("recent_photos" => $recent_photos));
})
->bind('archives');

/* View By Day */
$app->get('/day/{num}', function(Silex\Application $app, $num) {
  // Check if this day exists. Redirects to 404 if not.
  $meta_file_path = __DIR__.'/../data/metadata/'.str_pad($num, 3, 0, STR_PAD_LEFT).".json";
  if (!is_readable($meta_file_path)) $app->abort(404);

  // Grab meta information for this day
  $data = file_get_contents($meta_file_path);
  $meta_data = json_decode($data, true);

  // Determine if there is a Prev/Next
  $prev = $num - 1;
  $next = $num + 1;
  $has_prev = is_readable(__DIR__.'/../data/metadata/'.str_pad($prev, 3, 0, STR_PAD_LEFT).".json");
  $has_next = is_readable(__DIR__.'/../data/metadata/'.str_pad($next, 3, 0, STR_PAD_LEFT).".json");

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

$app->error(function (\Exception $e, $code) use($app) {
  if ($app['debug']) {
    return;
  }
  switch ($code) {
    case 404:
      $message = 'The requested page could not be found.';
      break;
    default:
      $message = 'We are sorry, but something went terribly wrong.';
  }
  return $app['twig']->render('error.html.twig', array(
    'code'    => $code,
    'message' => $message
  ), $code);
});
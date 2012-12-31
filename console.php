<?php
use Cilex\Provider\Console\Adapter\Silex\ConsoleServiceProvider,
    Silex\Application,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface;

require_once __DIR__ . '/vendor/autoload.php';
$app = new Application;

// Console Service Provider and command-line commands
$app->register(new ConsoleServiceProvider(), array(
  'console.name' => '365days.me',
  'console.version' => '1.0',
));

$app['console']
  ->register('photo:add')
  ->setDescription('Generates and stores meta-data such as title, date and EXIF for a photo.')
  ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
    $dialog = $app['console']->getHelperSet()->get('dialog');
    $day = $dialog->ask(
      $output,
      'Which day are we processing (Number only!): ',
      ''
    );

    $title = $dialog->ask(
      $output,
      'What would you like the photo title to be? ',
      ''
    );

    if ($day == "") {
      // Calculate the number of day for today
      $start_date = new DateTime("2012-12-29");
      $today = new DateTime("now");
      $interval = $today->diff($start_date);
      $day = $interval->format('%a');
    }
    $date = date('Y-m-d');

    // Path to meta data file
    $meta_file_path = __DIR__.'/data/metadata/'.str_pad($day, 3, 0, STR_PAD_LEFT).".json";

    // Path to image file
    $image_file_path = __DIR__.'/web/assets/'.str_pad($day, 3, 0, STR_PAD_LEFT).".jpg";

    if (file_exists($image_file_path))
    {
      $meta_info = exif_read_data($image_file_path);
      $meta_data = array();
      $meta_data["entry_day"] = $day;
      $meta_data["entry_date"] = $date;
      $meta_data["entry_title"] = strlen($title)?$title:$date;
      $meta_data["image_exif"] = $meta_info;
      $meta_data_json = json_encode($meta_data, JSON_PRETTY_PRINT);

      file_put_contents($meta_file_path, $meta_data_json);
      $output->writeln(sprintf("\nPhoto for day #%s has been processed. See you tomorrow!", $day));
    }
    else {
      $output->writeln(sprintf("\nERROR: Photo %s.jpg was not found.", str_pad($day, 3, 0, STR_PAD_LEFT)));
    }
  })
;

$app['console']->run();
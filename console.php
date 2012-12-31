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
  ->setDefinition(array(
    new InputArgument('day', InputArgument::VALUE_NONE, 'Which day are we processing? (Number only!)'),
    new InputArgument('title', InputArgument::VALUE_NONE, 'What would you like the photo title to be?'),
  ))
  ->setDescription('Generates and stores meta-data such as title, date and EXIF for a photo.')
  ->setCode(function (InputInterface $input, OutputInterface $output) {
    $day = $input->getArgument('day');
    $title = $input->getArgument('title');

    if ($day == "") {
      // Calculate the number of day for today
      $start_date = new DateTime("2012-12-29");
      $today = new DateTime("now");
      $interval = $today->diff($start_date);
      $day = $interval->format('%a');
    }
    $date = date('Y-m-d');

    // Path to meta data file
    $meta_file_path = __DIR__.'/data/metadata/'.str_pad($num, 3, 0, STR_PAD_LEFT).".json";

    // Path to image file
    $image_file_path = __DIR__.'/web/assets/'.str_pad($num, 3, 0, STR_PAD_LEFT).".json";

    $meta_info = exif_read_data($image_file_path);

    $meta_data = array();
    $meta_data["entry_day"] = $day;
    $meta_data["entry_date"] = $date;
    $meta_data["entry_title"] = strlen($title)?$title:$date;
    $meta_data["image_exif"] = $meta_info;
    $meta_data_json = json_encode($meta_data, JSON_PRETTY_PRINT);

    file_put_contents($meta_file_path, $meta_data_json);
    $output->writeln(sprintf('Photo for day #%s has been processed. See you tomorrow!', $day));
  })
;

$commands = array(
  new Command\XyzInfoCommand(),
  new Command\XyzSnapshotCommand(),
);

foreach ($commands as $command) {
  $app['console']->add($command);
}

$app['console']->run();
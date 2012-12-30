<?php
class addPhotoTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'photo';
    $this->name             = 'add';
    $this->briefDescription = 'Check photo for details and extracts or adds information related to it.';
    $this->detailedDescription = '';

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'main'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));

  }

  protected function execute($arguments = array(), $options = array())
  {
    $context = sfContext::createInstance($this->configuration);

    $day = $this->ask("Which day are we processing? (Photo filename must be identical) Default: today.");
    $title = $this->ask("Title of this photo:");

    if ($day == "") {
      // Calculate the number of day for today
      $start_date = new DateTime(sfConfig::get('start_date'));
      $today = new DateTime("now");
      $interval = $start_date->diff($today);
      $day = $interval->format('%a') + 1;
    }
    $date = date('Y-m-d');

    // Path to meta data file
    $meta_file_path = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'metadata'.DIRECTORY_SEPARATOR.str_pad($day, 3, 0, STR_PAD_LEFT).".json";

    // Path to image file
    $image_file_path = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.str_pad($day, 3, 0, STR_PAD_LEFT).".jpg";

    $meta_info = exif_read_data($image_file_path);

    $meta_data = array();
    $meta_data["entry_day"] = $day;
    $meta_data["entry_date"] = $date;
    $meta_data["entry_title"] = strlen($title)?$title:$date;
    $meta_data["image_exif"] = $meta_info;
    $meta_data_json = json_encode($meta_data, JSON_PRETTY_PRINT);

    file_put_contents($meta_file_path, $meta_data_json);
  }

}
?>
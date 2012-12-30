<?php
class homeActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    // Calculate the number of day for today
    $start_date = new DateTime(sfConfig::get('start_date'));
    $today = new DateTime("now");
    $interval = $start_date->diff($today);
    $day_num = $interval->format('%a') + 1;

    $this->redirect('@view_by_day?num='.$day_num);
  }

  public function executeViewByDay(sfWebRequest $request)
  {
    // Check if this day exists. Redirects to 404 if not.
    $this->num = $request->getParameter('num');
    $meta_file_path = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'metadata'.DIRECTORY_SEPARATOR.str_pad($this->num, 3, 0, STR_PAD_LEFT).".json";
    $this->forward404Unless(file_exists($meta_file_path));

    // Grab meta information for this day
    $data = file_get_contents($meta_file_path);
    $this->meta_data = json_decode($data, true);

    // Determine if there is a Prev/Next
    $prev = $this->num - 1;
    $next = $this->num + 1;
    $this->has_prev = file_exists(sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'metadata'.DIRECTORY_SEPARATOR.str_pad($prev, 3, 0, STR_PAD_LEFT).".json");
    $this->has_next = file_exists(sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'metadata'.DIRECTORY_SEPARATOR.str_pad($next, 3, 0, STR_PAD_LEFT).".json");
  }
}
?>
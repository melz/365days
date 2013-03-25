**365days.me** is the codebase for (surprise!) [http://365days.me](http://365days.me), which hosts a 365-days photo blog challenge.

The project was written using the [Silex](http://silex.sensiolabs.org/) micro-framework and uses [Twig](http://twig.sensiolabs.org/) template engine. For historical reasons, the first edition of the website was written using symfony 1.4 but I later ported the code to [Silex](http://silex.sensiolabs.org/) to make it a little more portable. It was also an excuse to experiment with [Silex](http://silex.sensiolabs.org/) for the first time.

## Background ##
I wanted to avoid the "regular" workflow for web applications of this nature which consists of "logging" into a website, filling in a form, uploading the photo, add tags, etc. It felt like a colossal waste of time, especially in a single user environment. Not to mention being unable to quickly browse meta data without having to dig through a database.

With this website, photos are named and uploaded into a specific directory and then meta information can be added and/or generated via command line. More instructions is available under Usage.

## Requirements ##
* PHP 5.4+ with EXIF module enabled.
* [Composer](http://getcomposer.org), a dependency manager for PHP.
* Access to command line interface.

## Installation ##
* Clone the repository to a directory.
* Run `composer update` to update all dependencies.
* Run `chown -R www-data.www-data logs` (change as necessary) to make the logs directory writable by the web server.
* Point your domain name to the /web directory.

## Usage ##
* Upload your photo into `/web/assets`, naming the file 001.jpg ... 365.jpg to correspond to the day.
* Using the command line in your working directory, run `php console.php photo:add` and answer the questions.
* The script will create a JSON file inside `/data/metadata/XXX.json` where XXX is the number of the day. This file will store your answers as well as the extracted EXIF information.
* Blurbs (in Markdown format), which are optional, can be added into `/data/blurb/XXX.md` and will be rendered in the day's entry page.

## Limitations ##
* You can't do much (in terms of adding photos) if you do not have access to the command line.
* You can only add one photo at a time, as it should be because it was designed to be updated daily. There is no "bulk add" method right now - depending on future needs, I may include a tool for this.

### Warning! ###
The repository history contains photos from my own photo blog. They were added because I originally meant to store my photos in the repository, and then decided that I would like to share the code. **Please note that these photos are copyrighted by me and are not meant for re-distribution**.
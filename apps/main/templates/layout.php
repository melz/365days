<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>365days.me</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
  </head>
  <body>
    <div class="container">

      <header class="mast" role="navigation">
        <nav>
          <img class="site-logo" src="/images/365days.png" />
          <ul id="nav-list">
            <li class="nav-today"><?php echo link_to('Today', '@homepage') ?></li>
            <li class="nav-archives"><?php echo link_to('Archives', '@homepage') ?></li>
            <li class="nav-about"><?php echo link_to('About', '@homepage') ?></li>
          </ul>
        </nav>
      </header><!-- /end .mast -->

      <div class="content" role="main">
        <?php echo $sf_content ?>
      </div>

      <footer>
        <p class="copyright-notice">All photos &amp; content &copy; <?php echo date('Y') ?> Melissa Peh (melz). Please do not repost my photos without prior consent.</p>
      </footer>
    </div>
  </body>
</html>
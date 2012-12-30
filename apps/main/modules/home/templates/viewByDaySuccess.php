<div class="photo-top">
  <div class="day-nav nav-prev">
    <?php if ($has_prev): ?>
    <span class="arrow"><?php echo link_to('&laquo;', '@view_by_day?num='.$num - 1) ?></span>
    <?php endif; ?>
    &nbsp;
  </div>
  <div class="meta-info" style="width:70%;">
    <h2>Day #<?php echo $num ?></h2>
    <h3 class="entry-title"><?php echo $meta_data['entry_title']; ?></h3>
  </div>
  <div class="day-nav nav-next">
    &nbsp;
    <?php if ($has_next): ?>
    <span class="arrow"><?php echo link_to('&raquo;', '@view_by_day?num='.$num + 1) ?></span>
    <?php endif; ?>
  </div>
</div>
<div class="single-photo">
  <img src="/assets/<?php echo str_pad($num, 3, 0, STR_PAD_LEFT) ?>.jpg" alt="<?php echo $meta_data['entry_title'] ?>" title="<?php echo $meta_data['entry_title'] ?>"/>
</div>

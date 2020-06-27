<form class="ottt-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" enctype="multipart/form-data">
<?php /*<form method='post' action='<?= $_SERVER['REQUEST_URI']; ?>' enctype='multipart/form-data'>*/ ?>
  <input type="file" name="looker_report" >
  <input type="hidden" name="action" value="ottt_activity_report">
  <input type="submit" name="activity_import" value="Generate Report">
</form>
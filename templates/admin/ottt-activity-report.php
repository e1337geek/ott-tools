<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form class="ottt-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="looker_report" >
        <input type="hidden" name="action" value="ottt_activity_report">
        <input type="submit" name="activity_import" value="Generate Report">
    </form>
</div>
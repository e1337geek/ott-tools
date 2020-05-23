<?php

$customer_fname = isset( $_GET['fname'] ) ? $_GET['fname'] : '';
$customer_lname = isset( $_GET['lname'] ) ? $_GET['lname'] : '';
$customer_email = isset( $_GET['email'] ) ? $_GET['email'] : '';

?>

<div class="ottt-container enroll-customer">
    <form class="ottt-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
        <label for="fname"><?php _e('First Name', 'ott-tools'); ?></label><br />
        <input type="text" id="fname" name="fname" value="<?php echo $customer_fname; ?>"<?php echo(!$customer_fname ?: 'readonly'); ?>><br />

        <label for="lname"><?php _e('Last Name', 'ott-tools'); ?></label><br />
        <input type="text" id="lname" name="lname" value="<?php echo $customer_lname; ?>"<?php echo(!$customer_lname ?: 'readonly'); ?>><br />

        <?php if(!$customer_employer): ?>
            <label for="employer"><?php _e('Employer', 'ott-tools'); ?></label><br />
            <input type="text" id="employer" name="employer"><br />
        <?php else: ?>
            <input type="hidden" id="employer" name="employer" value="<?php echo $customer_employer; ?>"><br />
        <?php endif; ?>

        <label for="email"><?php _e('Email', 'ott-tools'); ?></label><br />
        <input type="text" id="email" name="email" value="<?php echo $customer_email; ?>"<?php echo(!$customer_email ?: 'readonly'); ?>><br />

        <label for="password"><?php _e('Password', 'ott-tools'); ?></label><br />
        <input type="password" id="password" name="password"><br />

        <input type="hidden" name="action" value="ottt_enroll_customer"><br />
        <input type="submit">
    </form>
</div>
<div class="ottt-container enroll-customer">
    <form class="ottt-form">
        <label for="name"><?php _e('Name', 'ottt-tools'); ?></label><br />
        <input type="text" id="name" name="name"><br />

        <?php if(!employer): ?>
            <label for="employer"><?php _e('Employer', 'ottt-tools'); ?></label><br />
            <input type="text" id="employer" name="employer"><br />
        <?php else: ?>
            <input type="hidden" id="employer" name="employer" value="<?php echo $customer_employer; ?>"><br />
        <?php endif; ?>

        <label for="email"><?php _e('Email', 'ottt-tools'); ?></label><br />
        <input type="text" id="email" name="email"><br />

        <label for="password"><?php _e('Password', 'ottt-tools'); ?></label><br />
        <input type="text" id="password" name="password"><br />

        <input type="hidden" name="action" value="ottt_enroll_customer"><br />
        <input type="submit">
    </form>
</div>
<?php

$key = get_post_meta( get_the_ID(), 'ottt_decryption_key', true );
$customer_fname = isset( $_GET['fname'] ) ? ( $key ? ottt_decrypt_string( $_GET['fname'], $key ) : $_GET['fname'] ) : '';
$customer_lname = isset( $_GET['lname'] ) ? ( $key ? ottt_decrypt_string( $_GET['lname'], $key ) : $_GET['lname'] ) : '';
$customer_email = isset( $_GET['email'] ) ? ( $key ? ottt_decrypt_string( $_GET['email'], $key ) : $_GET['email'] ) : '';

?>
<div class="ottt-container enroll-customer">

    <?php if( !get_option( 'ottt_api_key' ) || !get_option( 'ottt_product_id' ) ): ?>

        <h2><?php _e( 'Please ensure the OTT Tools settings have been completed!', 'ott-tools' ); ?></h2>

    <?php elseif( isset( $_GET['success'] ) && $_GET['success'] ): ?>

    <?php else: ?>

        <?php if( isset( $_GET['success'] ) && $_GET['success'] === '0' ): ?>
            <div class="ottt-error">
                <?php if( isset( $_GET['ott-error'] ) && $_GET['ott-error'] === 'fields' ): ?>
                    <p><?php _e( 'We were unable to process your request. Please ensure all form fields are completed and try again.', 'ott-tools' ); ?></p>
                <?php else: ?>
                    <p><?php _e( 'We were unable to process your request at this time. Please try again later or contact us for assistance.','ott-tools' ); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form class="ottt-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
            <div class="halfcol">
                <label for="fname"><?php echo $label_fname; ?></label>
                <input type="text" id="fname" name="fname" value="<?php echo $customer_fname; ?>"<?php echo(!$customer_fname ?: 'readonly'); ?>>
            </div>

            <div class="halfcol">
                <label for="lname"><?php echo $label_lname; ?></label>
                <input type="text" id="lname" name="lname" value="<?php echo $customer_lname; ?>"<?php echo(!$customer_lname ?: 'readonly'); ?>>
            </div>

            <?php if(!$employer): ?>
                <div class="fullcol">
                    <label for="employer"><?php echo $label_employer ?></label>
                    <input type="text" id="employer" name="employer">
                </div>
            <?php else: ?>
                <input type="hidden" id="employer" name="employer" value="<?php echo $employer; ?>">
            <?php endif; ?>

            <div class="fullcol">
                <label for="email"><?php echo $label_email; ?></label>
                <input type="text" id="email" name="email" value="<?php echo $customer_email; ?>"<?php echo(!$customer_email ?: 'readonly'); ?>>
            </div>

            <div class="fullcol">
                <label for="password"><?php echo $label_password; ?></label>
                <input type="password" id="password" name="password">
            </div>
            
            <?php if( $expiration ): ?>
                <input type="hidden" id="expiration" name="expiration" value="<?php echo $expiration; ?>">
            <?php endif; ?>
            <?php wp_nonce_field(); ?>
            <input type="hidden" name="action" value="ottt_enroll_customer">
            <input type="submit" value="<?php echo $label_submit; ?>">
        </form>

    <?php endif; ?>

</div>
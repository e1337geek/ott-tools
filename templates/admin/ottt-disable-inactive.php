<?php

global $wpdb;
$customerTable = 'ottt_customers';
$currentTimestamp = time();
$gracePeriodSec = 2419200;
$minLastViewed = $currentTimestamp - $gracePeriodSec;
$getCustomersSQL = "SELECT * FROM `$customerTable` WHERE `ottt_customer_last_viewed` < $minLastViewed AND `ottt_customer_success` = 1 ORDER BY `ottt_customer_last_viewed` ASC;";
echo var_dump($getCustomersSQL);
$customers = $wpdb->get_results( $getCustomersSQL, 'ARRAY_A' );

?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <form class="ottt-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="ottt_disable_inactive">
        <input type="submit" name="disable_inactive" value="Disable Inactive">
    </form>

    <div id="poststuff">
        <table style="min-width: 50%;">
            <tr>
                <th style="text-align: left;">ID</th>
                <th style="text-align: left;">First Name</th>
                <th style="text-align: left;">Last Name</th>
                <th style="text-align: left;">Email</th>
                <th style="text-align: left;">Source</th>
                <th style="text-align: left;">Success</th>
                <th style="text-align: left;">Error</th>
                <th style="text-align: left;">Last Viewed</th>
                <th style="text-align: left;">Disabled</th>                
            </tr>

            <?php foreach( $customers as $customer ): ?>

            <?php
            if( $customer['ottt_customer_last_viewed'] > 0 ) {
                $customer_last_viewed = gmdate("Y-m-d", $customer['ottt_customer_last_viewed']);
            } else {
                $customer_last_viewed = '';
            }
            if( $customer['ottt_customer_disabled'] > 0 ) {
                $customer_disabled = gmdate("Y-m-d", $customer['ottt_customer_disabled']);
            } else {
                $customer_disabled = '';
            }
            ?>

            <tr>
                <td><?php echo $customer['ottt_vhx_customer_id']?></td>
                <td><?php echo $customer['ottt_customer_fname']?></td>
                <td><?php echo $customer['ottt_customer_lname']?></td>
                <td><?php echo $customer['ottt_customer_email']?></td>
                <td><?php echo $customer['ottt_customer_source']?></td>
                <td><?php echo $customer['ottt_customer_success']?></td>
                <td><?php echo $customer['ottt_customer_error']?></td>
                <td><?php echo $customer_last_viewed ?></td>
                <td><?php echo $customer_disabled ?></td>
            </tr>

            <?php endforeach; ?>

        </table>
    </div>
</div>
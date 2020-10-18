<?php

global $wpdb;
$customerTable = 'ottt_customers_220';
$currentTimestamp = time();
$gracePeriodSec = 2419200;
$minLastViewed = $currentTimestamp - $gracePeriodSec;
$getCustomersSQL = "SELECT * FROM `$customerTable` WHERE `ottt_customer_last_viewed` < $minLastViewed ORDER BY `ottt_customer_last_viewed` ASC;";
$customers = $wpdb->get_results( $getCustomersSQL, 'ARRAY_A' );

?>

<div class="wrap">
    <h2>OTT Customers</h2>

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

            <tr>
                <td><?php echo $customer['ottt_vhx_customer_id']?></td>
                <td><?php echo $customer['ottt_customer_fname']?></td>
                <td><?php echo $customer['ottt_customer_lname']?></td>
                <td><?php echo $customer['ottt_customer_email']?></td>
                <td><?php echo $customer['ottt_customer_source']?></td>
                <td><?php echo $customer['ottt_customer_success']?></td>
                <td><?php echo $customer['ottt_customer_error']?></td>
                <td><?php echo gmdate("Y-m-d\TH:i:s\Z", $customer['ottt_customer_last_viewed'])?></td>
                <td><?php echo gmdate("Y-m-d\TH:i:s\Z", $customer['ottt_customer_disabled'])?></td>
            </tr>

            <?php endforeach; ?>

        </table>
    </div>
</div>
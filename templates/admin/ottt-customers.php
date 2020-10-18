<?php

global $wpdb;
$customerTable = 'ottt_customers_220';
$getCustomersSQL = "SELECT * FROM `$customerTable` ORDER BY `customer_id` DESC;";
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

<?php /*
$customerListTable = new OTTT_Customers_List();
?>
<div class="wrap">
    <h2>OTT Customers</h2>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post">
                        <?php
                        $customerListTable->prepare_items();
                        $customerListTable->display(); ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>
<?php */
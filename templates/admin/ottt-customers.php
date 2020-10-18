<?php

global $wpdb;
$customerTable = 'ottt_customers_220';
$getCustomersSQL = "SELECT * FROM `$customerTable` ORDER BY `customer_id` DESC LIMIT 200;";
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
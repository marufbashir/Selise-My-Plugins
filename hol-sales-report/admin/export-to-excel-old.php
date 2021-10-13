<!-- <excel download> -->
<?php
// variable comes from filter-option.php
if (isset($_POST['pagination'])) {
    $pagedPagination = $_POST['pagination'];
} else {
    $pagedPagination = 1;
}

$argsPagination = array(
    'limit' => 200,
    'return' => '*',
    'status' => $order_statuses,
    'date_query' => array(
        array(
            'after'     =>  $purchaseDateFrom,
            'before'    => $purchaseDateTo,
            'inclusive' => true,
        ),
    ),
    'meta_transaction_id' => 'just-a-value',
    'payment_method_title' => $filterPaymentType,
    'paged' => $pagedPagination,
    'billing_email' => $filterEventCustomerEmail,
    // meta_key
    'event_name' => $filter_event_names,
    'start_date' => array($eventStarteDateFrom, $eventStartDateTo),
    'start_time'=>  $filterEventTime,
    //'meta_end_date' => $filter_event_end_date,
    'meta_key' => 'start_date',
    'meta_compare' => 'EXISTS',
    'meta_relation' => 'AND',
    'orderby'   => array(
        'date' => 'DESC',
    ),
);
$queryAllOrders = new WC_Order_Query($argsPagination);
////////////////////////////////// 

function handle_custom_query_var_all_order($queryAllOrders, $query_vars)
{
    if (!empty($query_vars['event_name'])) {
        $queryAllOrders['meta_query'][] = array(
            'key' => 'event_name',
            'value' => $query_vars['event_name'],
            'compare' => 'IN',

        );
    }
    if (!empty($query_vars['start_date']) && isset($_GET['event_start_date']) && !empty($_GET['event_start_date'])) {
        $queryAllOrders['meta_query'][] = array(
            'key' => 'start_date',
            'value' => $query_vars['start_date'],
            'compare'   => 'BETWEEN',
            'type'      => 'DATETIME'

        );
    }
    if ( ! empty( $query_vars['start_time'] ) ) {
        $queryAllOrders['meta_query'][] = array(
            'key' => 'start_date',
            'value' =>  $query_vars['start_time'],
            'compare' => 'LIKE',
            'type'      => 'TIME'

        );
    }
    // payemnt status
    if (isset($_GET['payment_status']) && $_GET['payment_status']=='paid'){
        if ( ! empty( $query_vars['meta_transaction_id'] ) ) {
            $queryAllOrders['meta_query'][] = array(
                'key' => '_transaction_id',
                //'value' => '',
                'compare' => 'EXISTS'

            );
        }
    }
    elseif(isset($_GET['payment_status']) && $_GET['payment_status']=='unpaid'){
        if ( ! empty( $query_vars['meta_transaction_id'] ) ) {
            $queryAllOrders['meta_query'][] = array(
                'key' => '_transaction_id',
                'value' => '',
                'compare' => 'NOT EXISTS'
            );
        }
    }
    else{

    }


    return $queryAllOrders;
}
add_filter('woocommerce_order_data_store_cpt_get_orders_query', 'handle_custom_query_var_all_order', 10, 2);
////////////////////////////////////////////////////////////////////////// 
$allOrders = wc_get_orders($argsPagination);
// echo 'Export to excel ',count($allOrders);
// total array 
$totalTicketPurchasedArray = array();
$totalPriceArray = array();
$totalAdultArray = array();
$totalAdultPriceArray = array();
$totalChildrenArray = array();
$totalChildrenPriceArray = array();
$totalInfantsArray = array();
$totalInfantsPriceArray = array();
$totalSchoolClassesArray = array();
$totalSchoolClassesPriceArray = array();
$totalTeacherArray = array();
$totalTeacherPriceArray = array();
$AdChWorkspaceArray  = array();
$AdChWorkspacePriceArray  = array();
$totalBookableOrderArray = array();

?>
    <button type="button" class="button button-primary download-btn" onclick="fnExcelReport(this)">Download Excel</button>

    <table class="wp-list-table widefat fixed striped table-view-list pages" id="SalesReportTableAllOrders" style="width: 4000px; display: none;">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Purchase Date</th>
                <th>Event Start Time & Date</th>
                <th>Event End Time & Date</th>
                <th>Payment Status </th>
                <th>Payment type</th>
                <th>Customer name</th>
                <th>Customer Email</th>
                <th>Event Language</th>
                <th>Event Name</th>
                <th>Total ticket purchased</th>
                <th>Total Price</th>
                <th>Adult Count</th>
                <th>Total Adult Price</th>
                <th>Children Count</th>
                <th>Total Children Price</th>
                <th>Infants count</th>
                <th>Total Infants Price</th>
                <th>School Classes count</th>
                <th>Total School Classes price</th>
                <th>Teacher Count</th>
                <th>Total Teacher Price</th>
                <th>Adults and children with own workspace (atelier) count</th>
                <th>Total Adults and children with own workspace (atelier) price</th>
            </tr>
        </thead>

        <?php


        echo '<tbody>';

        foreach ($allOrders as $key => $order) {
            $order_data = wc_get_order($order->parent_id);

            array_push($totalBookableOrderArray, 1);
        ?>
            <tr>
                <td><?php
                    //echo $order->date_created, '<br/>';

                    if ($order->parent_id == 0) {
                        echo '', $order->id;
                        $order_data = wc_get_order($order->id);
                    } else {
                        echo 'PID ', $order->parent_id;
                        $order_data = wc_get_order($order->parent_id);
                    }

                    ?>
                </td>
                <!-- purchage date-->
                <td><?php echo date('j F Y', strtotime($order->date_created)); ?></td>

                <!-- Event Start Time & Date-->
                <td>

                    <?php
                    // echo '<pre>';
                    // print_r($order);
                    // echo '</pre>';
                    if ($order->get_meta('start_date')) {
                        echo date('j F Y H:i', strtotime($order->get_meta('start_date')));
                    } else {
                        echo '-';
                    }
                    ?>

                </td>

                <!-- Event End Time & Date -->
                <td>

                    <?php
                    if ($order->get_meta('end_date')) {
                        echo date('j F Y H:i', strtotime($order->get_meta('end_date')));
                    } else {
                        echo '-';
                    }
                    ?>

                </td>

                <!-- Payment Status-->
                <td>
                    <?php
                    if ($order->transaction_id) {
                        echo '<mark>Paid</mark>';
                    } else {
                        echo 'Unpaid';
                    }
                    ?>
                </td>

                <!-- payment type-->
                <td>
                    <p title="<?php echo $order->transaction_id; ?>">
                        <?php
                        if ($order->payment_method_title) {
                            echo $order->payment_method_title;
                        } else {
                            echo '-';
                        }
                        ?>
                    </p>
                </td>
                <!-- Customer name-->
                <td>
                    <?php
                    echo @$order_data->get_billing_first_name(), ' ', @$order_data->get_billing_last_name();
                    ?>
                </td>
                <!-- Customer Email-->
                <td>
                    <?php
                    echo @$order_data->get_billing_email();
                    ?>
                </td>

                <!-- Event Language-->
                <!-- <td>
                   
                </td> -->
                <!-- Event Name-->
                <td>
                    <?php
                    if ($order->get_meta('event_name')) {
                        echo $order->get_meta('event_name');
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
                <!-- Total ticket purchased -->
                <td>
                    <?php
                    if ($order->get_meta('perticipant_count')) {
                        echo $order->get_meta('perticipant_count');
                        array_push($totalTicketPurchasedArray, $order->get_meta('perticipant_count'));
                    } else {
                        echo '-';
                    }

                    ?>
                </td>
                <td>
                    <?php
                    echo $order->total;
                    array_push($totalPriceArray, $order->total);
                    ?>
                </td>
                <!-- Adult Count-->
                <td>
                    <?php
                    $personTypes = $order->get_meta('attendee_types');

                    $personExtraData = json_decode($personTypes, true);
                    if ($personExtraData) {
                        foreach ($personExtraData as $value) {
                            //echo $valueName = $value['name'] . "<br>";
                            $valueName = $value['name'];
                            if ($valueName == 'Erwachsene' || $valueName == 'Adults') {
                                echo $value['count'];
                                array_push($totalAdultArray, $value['count']);
                            }
                        }
                    }



                    // foreach($personTypesArray as $pType){
                    //     echo $pType->name;
                    // }

                    ?>
                </td>
                <!-- Total Adult Price-->
                <td>
                    <?php
                    if ($personExtraData) {
                        foreach ($personExtraData as $value) {
                            //echo $valueName = $value['name'] . "<br>";
                            $valueName = $value['name'];
                            if ($valueName == 'Erwachsene' || $valueName == 'Adults') {
                                echo $value['cost'];
                                array_push($totalAdultPriceArray, $value['cost']);
                            }
                        }
                    }
                    ?>
                </td>
                <!-- Children Count-->
                <td>
                    <?php
                    if ($personExtraData) {
                        foreach ($personExtraData as $value) {
                            //echo $valueName = $value['name'] . "<br>";
                            $valueName = $value['name'];
                            if ($valueName == 'Kinder' || $valueName == 'Children') {
                                echo $value['count'];
                                array_push($totalChildrenArray, $value['count']);
                            }
                        }
                    }
                    ?>
                </td>

                <!-- Total Children Price-->
                <td>
                    <?php
                    if ($personExtraData) {
                        foreach ($personExtraData as $value) {
                            //echo $valueName = $value['name'] . "<br>";
                            $valueName = $value['name'];
                            if ($valueName == 'Kinder' || $valueName == 'Children') {
                                echo $value['cost'];
                                array_push($totalChildrenPriceArray, $value['cost']);
                            }
                        }
                    }
                    ?>
                </td>
                <!-- Infants count-->
                <td>
                    <?php
                    if ($personExtraData) {
                        foreach ($personExtraData as $value) {
                            //echo $valueName = $value['name'] . "<br>";
                            $valueName = $value['name'];
                            if ($valueName == 'Kleinkinder' || $valueName == 'Infants') {
                                echo $value['count'];
                                array_push($totalInfantsArray, $value['count']);
                            }
                        }
                    }
                    ?>
                </td>
                <!-- Total Infants Price-->
                <td>
                    <?php
                    if ($personExtraData) {
                        foreach ($personExtraData as $value) {
                            //echo $valueName = $value['name'] . "<br>";
                            $valueName = $value['name'];
                            if ($valueName == 'Kleinkinder' || $valueName == 'Infants') {
                                echo $value['cost'];
                                array_push($totalInfantsPriceArray, $value['cost']);
                            }
                        }
                    }
                    ?>
                </td>
                <!-- School Classes count-->
                <td>
                    <?php
                    if ($personExtraData) {
                        foreach ($personExtraData as $value) {
                            //echo $valueName = $value['name'] . "<br>";
                            $valueName = $value['name'];
                            if ($valueName == 'Schulklassen' || $valueName == 'School Classes') {
                                echo $value['count'];
                                array_push($totalSchoolClassesArray, $value['count']);
                            }
                        }
                    }
                    ?>
                </td>
                <!-- Total School Classes price-->
                <td>
                    <?php
                    if ($personExtraData) {
                        foreach ($personExtraData as $value) {
                            $valueName = $value['name'];
                            if ($valueName == 'Schulklassen' || $valueName == 'School Classes') {
                                echo $value['cost'];
                                array_push($totalSchoolClassesPriceArray, $value['cost']);
                            }
                        }
                    }
                    ?>
                </td>
                <!-- Teacher Count-->
                <td>
                    <?php
                    if ($personExtraData) {
                        foreach ($personExtraData as $value) {
                            $valueName = $value['name'];
                            if ($valueName == 'Teacher') {
                                echo $value['count'];
                                array_push($totalTeacherArray, $value['count']);
                            }
                        }
                    }
                    ?>
                </td>
                <!-- Total Teacher Price-->
                <td>
                    <?php
                    if ($personExtraData) {
                        foreach ($personExtraData as $value) {
                            $valueName = $value['name'];
                            if ($valueName == 'Teacher') {
                                echo $value['cost'];
                                array_push($totalTeacherPriceArray, $value['cost']);
                            }
                        }
                    }
                    ?>
                </td>
                <!-- Adults and children with own workspace (atelier) count-->
                <td>
                    <?php
                    if ($personExtraData) {
                        foreach ($personExtraData as $value) {
                            $valueName = $value['name'];
                            if ($valueName == 'Adults and children with own workspace (atelier)') {
                                echo $value['count'];
                                array_push($AdChWorkspaceArray, $value['count']);
                            }
                        }
                    }
                    ?>
                </td>
                <!-- Total Adults and children with own workspace (atelier) price-->
                <td>
                    <?php
                    if ($personExtraData) {
                        foreach ($personExtraData as $value) {
                            $valueName = $value['name'];
                            if ($valueName == 'Adults and children with own workspace (atelier)') {
                                echo $value['cost'];
                                array_push($AdChWorkspacePriceArray, $value['cost']);
                            }
                        }
                    }
                    ?>
                </td>
            </tr>



        <?php
            wp_reset_query();
        }

        ?>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    Total Order
                    <?php echo array_sum($totalBookableOrderArray); ?>
                </td>
                <td>
                    <!-- Purchase Date-->
                </td>
                <td>
                    <!-- Event Start Time & Date-->
                </td>
                <td>
                    <!-- Event End Time & Date-->
                </td>
                <td>
                    <!-- Payment Status-->
                </td>
                <td>
                    <!-- Payment type-->
                </td>
                <td>
                    <!-- Customer name-->
                </td>
                <td>
                    <!-- Customer email-->
                </td>
                  <!-- Event Language-->
                <!-- <td>
                  
                </td> -->
                <td>
                    <!-- Event Name-->
                </td>
                <td>
                    <!-- 	Total ticket purchased-->
                    Total Ticket
                    <?php echo array_sum($totalTicketPurchasedArray); ?>
                </td>
                <td>
                    <!-- Total Price-->
                    Total Price
                    <?php echo array_sum($totalPriceArray); ?>

                </td>
                <td>
                    <!-- Adult Count-->
                    Total Adult
                    <?php echo array_sum($totalAdultArray); ?>
                </td>
                <!---------------------------------------------------------------->
                <td>
                    <!-- Total Adult Price-->
                    Total Adult Price
                    <?php echo array_sum($totalAdultPriceArray); ?>
                </td>
                <td>
                    <!-- Children Count-->
                    Total Children
                    <?php echo array_sum($totalChildrenArray); ?>

                </td>
                <td>
                    <!-- Total Children Price-->
                    Total Children Price
                    <?php echo array_sum($totalChildrenPriceArray); ?>
                </td>
                <td>
                    <!-- Infants count-->
                    Total Infants
                    <?php echo array_sum($totalInfantsArray); ?>
                </td>
                <td>
                    <!-- Total Infants Price-->
                    Total Infants Price
                    <?php echo array_sum($totalInfantsPriceArray); ?>
                </td>
                <td>
                    <!-- School Classes count-->
                    Total School Classes
                    <?php echo array_sum($totalSchoolClassesArray); ?>
                </td>
                <td>
                    <!-- Total School Classes price-->
                    Total School Classes price
                    <?php echo array_sum($totalSchoolClassesPriceArray); ?>
                </td>
                <td>
                    <!-- Teacher Count-->
                    Total Teacher
                    <?php echo array_sum($totalTeacherArray); ?>
                </td>
                <td>
                    <!-- Total Teacher Price-->
                    Total Teacher Price
                    <?php echo array_sum($totalTeacherPriceArray); ?>
                </td>
                <td>
                    <!-- Adults and children with own workspace (atelier) count-->
                    Total with own workspace
                    <?php echo array_sum($AdChWorkspaceArray); ?>
                </td>
                <td>
                    <!-- Total Adults and children with own workspace (atelier) price-->
                    Total with own workspace
                    <?php echo array_sum($AdChWorkspacePriceArray); ?>
                </td>
            </tr>
        </tfoot>
    </table>
    <iframe id="txtArea1" style="display:none"></iframe>
    <script type="text/javascript">
        function fnExcelReport() {
            var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
            var textRange;
            var j = 0;
            tab = document.getElementById('SalesReportTableAllOrders'); // id of table

            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
                //tab_text=tab_text+"</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
            {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
            } else //other browser not tested on IE 11
                sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

            return (sa);
        }
    </script>

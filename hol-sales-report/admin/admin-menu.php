<?php


add_action('admin_menu', 'extra_post_info_menu');
function extra_post_info_menu()
{
    $page_title = 'HoL Sales Report';
    $menu_title = 'HoL Sales Report';
    $capability = 'manage_options';
    $menu_slug  = 'hol-sales-report';
    $function   = 'hol_sales_report_info';
    $icon_url   = 'dashicons-analytics';
    $position   = 4;
    add_menu_page(
        $page_title,
        $menu_title,
        $capability,
        $menu_slug,
        $function,
        $icon_url,
        $position
    );
}
function hol_sales_report_info()
{
    include_once('filter-options.php');
?>
    <table class="wp-list-table widefat fixed striped table-view-list pages" id="SalesReportTable" style="width: 4000px;">
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
                <!-- <th>Event Language</th> -->
                <th>
                    <span href="#" onclick="orderByEvent()" title="Click to Sort By Name ASC/DESC" class="sortableItem" style="color: #135e96; cursor: pointer;"> Event Name</span>

                    <!-- Script on the end -->

                </th>
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
        // variable comes from filter-option.php
        if (isset($_POST['pagination'])) {
            $pagedPagination = $_POST['pagination'];
        } else {
            $pagedPagination = 1;
        }
        $order_statuses = array('wc-completed', 'wc-processing');
        //+1day 2021-09-13 00:00:00
        $modifyPurchaseDateTo =  date('Y-m-d H:i:s', strtotime($purchaseDateTo . ' +1 day'));
        $modifyEventStartDateTo =  date('Y-m-d H:i:s', strtotime($eventStartDateTo . ' +1 day'));
        $args = array(
            'limit' => 200,
            'return' => '*',
            'status' => $order_statuses,
            'date_query' => array(
                array(
                    'after'     =>  $purchaseDateFrom,
                    'before'    => $modifyPurchaseDateTo,
                    'inclusive' => true,
                ),
            ),
            // pagination 
            'meta_transaction_id' => 'just-a-value',
            // 'paginate' => true,
            'paged' => $pagedPagination,
            // pagination 
            'payment_method_title' => $filterPaymentType,
            'billing_email' => $filterEventCustomerEmail,
            // meta_key
            'event_name' => $filter_event_names,
            'start_date' => array($eventStarteDateFrom, $modifyEventStartDateTo), //$filter_event_start_date,
            'start_time' =>  $filterEventTime,
            //'meta_end_date' => $filter_event_end_date,
            'meta_key' => 'start_date',
            'meta_compare' => 'EXISTS',
            'meta_relation' => 'AND',

            'orderby'   => array(
                'date' => 'DESC',
            ),
            // new code order by event name
            'meta_key' => 'event_name',
            'orderby' => 'event_name',
            'order' => $filterEventName,
        );
        $query = new WC_Order_Query($args);
        // print_r($modifyPurchaseDateTo);
        ////////////////////////////////// 

        function handle_custom_query_var($query, $query_vars)
        {
            if (!empty($query_vars['event_name'])) {
                $query['meta_query'][] = array(
                    'key' => 'event_name',
                    'value' => $query_vars['event_name'],
                    'compare' => 'IN',

                );
            }
            if (!empty($query_vars['start_date']) && isset($_GET['event_start_date']) && !empty($_GET['event_start_date'])) {
                $query['meta_query'][] = array(
                    'key' => 'start_date',
                    'value' => $query_vars['start_date'],
                    'compare'   => 'BETWEEN',
                    'type'      => 'DATETIME'

                );
            }
            if (!empty($query_vars['start_time'])) {
                $query['meta_query'][] = array(
                    'key' => 'start_date',
                    'value' =>  $query_vars['start_time'],
                    'compare' => 'LIKE',
                    'type'      => 'TIME'

                );
            }
            // payemnt status
            if (isset($_GET['payment_status']) && $_GET['payment_status'] == 'paid') {
                if (!empty($query_vars['meta_transaction_id'])) {
                    $query['meta_query'][] = array(
                        'key' => '_transaction_id',
                        //'value' => '',
                        'compare' => 'EXISTS'

                    );
                }
            } elseif (isset($_GET['payment_status']) && $_GET['payment_status'] == 'unpaid') {
                if (!empty($query_vars['meta_transaction_id'])) {
                    $query['meta_query'][] = array(
                        'key' => '_transaction_id',
                        'value' => '',
                        'compare' => 'NOT EXISTS'
                    );
                }
            } else {
            }



            return $query;
        }
        add_filter('woocommerce_order_data_store_cpt_get_orders_query', 'handle_custom_query_var', 10, 2);
        ////////////////////////////////////////////////////////////////////////// 
        $orders = wc_get_orders($args);
        // $orders = wc_get_orders($query);
        //  $orders = $query->get_orders( );
        //print_r($orders);
        //echo 'Order Count', count($orders);
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

        echo '<tbody>';
        if ($orders) {


            foreach ($orders as $key => $order) {
                $order_data = wc_get_order($order->parent_id);

                array_push($totalBookableOrderArray, 1);
        ?>

                <tr>
                    <td>#<?php
                            //echo  get_post_meta($order->id, '_transaction_id', true);
                            //  echo '<pre>';
                            //  echo $order;
                            //  echo '</pre>';
                            // echo $order->transaction_id, '<br/>';
                            if ($order->parent_id == 0) {
                                echo '<a href="post.php?post=' . $order->id . '&action=edit" target="_blank">', '', $order->id, '</a>';
                                $order_data = wc_get_order($order->id);
                            } else {
                                echo '', $order->parent_id;
                                $order_data = wc_get_order($order->parent_id);
                            }

                            ?></td>
                    <!-- purchage date-->
                    <td><?php echo date('j F Y', strtotime($order->date_created)); ?></td>

                    <!-- Event Start Time & Date-->
                    <td>

                        <?php
                        // echo $order->get_meta('start_date');
                        // echo '<pre>';
                        //     echo $order;
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
                        // $booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id($order->id);
                        // echo 'order id ', $order->id;
                        // print_r($booking_ids);

                        // foreach ($booking_ids as $booking_id) {
                        //     $booking = new WC_Booking($booking_id);
                        //     echo $booking->get_status();
                        //     print_r($booking);
                        // }

                        //echo '+booking </pre>';
                        ?>
                        <?php
                        // echo $order->transaction_id;
                        if ($order->transaction_id) {
                            echo '<span class="status-paid" title="' . $order->transaction_id . '">Paid</span>';
                        } else {
                            echo '<span class="status-unpaid">Unpaid</span>';
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
                    <!-- <td> -->
                    <?php
                    //     $booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id($order->id);
                    //    // $bookingEventId = $booking_ids;
                    //     //echo $order->id;
                    //    // print_r($booking_ids);

                    //     foreach ($booking_ids as $booking_id) {
                    //         $booking = new WC_Booking($booking_id);
                    //         $bookingEventId = $booking->product_id;

                    //     }
                    //     echo get_post_meta( $bookingEventId,'event_language', true );
                    ?>
                    <!-- </td> -->
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
                                if ($valueName == 'Schulklassen' || $valueName == 'School Classes' || $valueName == 'Pupils' || $valueName == 'Student' || $valueName == 'Schüler') {
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
                                if ($valueName == 'Schulklassen' || $valueName == 'School Classes' || $valueName == 'Pupils' || $valueName == 'Student' || $valueName == 'Schüler') {
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
                                if ($valueName == 'Teacher' || $valueName == 'Lehrer') {
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
                                if ($valueName == 'Teacher' || $valueName == 'Lehrer') {
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
                                if ($valueName == 'Adults and children with own workspace (atelier)' || $valueName == 'Adults and children with their own workplace' ||  $valueName == 'Erwachsene und Kinder mit eigenem Arbeitsplatz') {
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
                                if ($valueName == 'Adults and children with own workspace (atelier)' || $valueName == 'Adults and children with their own workplace' ||  $valueName == 'Erwachsene und Kinder mit eigenem Arbeitsplatz') {
                                    echo $value['cost'];
                                    array_push($AdChWorkspacePriceArray, $value['cost']);
                                }
                            }
                        }
                        ?>
                    </td>
                </tr>

        <?php   }



            wp_reset_query();
        } else {
            echo '<tr><td colspan="23">';
            echo '<p class="text-warning">No Record Fund</p>';
            echo '</td></tr>';
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

<?php
    include_once('pagination.php');
    include_once('export-to-excel.php');
    // include_once('export-to-excel-2.php');
?>
<?php
if (!empty($_GET['order_by_event']) && $_GET['order_by_event']) { ?>
    <script>
        // order asc 
        function orderByEvent() {
            $theEventOrder = document.getElementById("orderByEvent");
            $requestOrder = "<?php echo $_GET['order_by_event'] ?>";
            
            if ($requestOrder == "ASC") {
                $theEventOrder.value = "DESC";
            } else if ($requestOrder == "DESC") {
                $theEventOrder.value = "ASC";
            } else {
                $theEventOrder.value = "ASC";
            }

            document.getElementById("holSalesReportFilterForm").submit();
        }
    </script>

<?php } else { ?>
    <script>
        // order asc 
        function orderByEvent() {
            $theEventOrder = document.getElementById("orderByEvent");
            // $theEventOrder.value = "ASC";
            if ($theEventOrder.value == 'ASC') {
                $theEventOrder.value = "DESC";
            } else if ($theEventOrder.value == 'DESC') {
                $theEventOrder.value = 'ASC'
            } else {
                $theEventOrder.value = 'ASC'
            }
            document.getElementById("holSalesReportFilterForm").submit();
        }
    </script>
<?php }
?>
<?php 
}

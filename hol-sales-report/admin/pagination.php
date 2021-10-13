<?php
// download excel
$modifyPurchaseDateToP =  date('Y-m-d H:i:s', strtotime($purchaseDateTo . ' +1 day'));
$modifyEventStartDateToP =  date('Y-m-d H:i:s', strtotime($eventStartDateTo . ' +1 day'));
$argsxxx = array(
    'limit' => -1,

    'return' => '*',
    'status' => $order_statuses,
    'date_query' => array(
        array(
            'after'     =>  $purchaseDateFrom,
            'before'    => $modifyPurchaseDateToP,
            'inclusive' => true,
        ),
    ),
    // pagination 
    'paginate' => true,
    'limit' => 200,
    'paged' => $pagedPagination,
    'meta_transaction_id' => 'just-a-value',
    'payment_method_title' => $filterPaymentType,
    'billing_email' => $filterEventCustomerEmail,
    // meta key
    'event_name' => $filter_event_names,
    'start_date' => array($eventStarteDateFrom, $modifyEventStartDateToP), //$filter_event_start_date,
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

$results = wc_get_orders($argsxxx);
if ($results->total > 50) {
    echo '<p class="total-result-text">';
    echo $results->total . " orders found\n. ";
    echo 'Page ' . $pagedPagination . ' of ' . $results->max_num_pages . "\n";
    echo '</p>';


    // echo 'First order id is: ' . $results->orders[0]->get_id() . "\n";


?>

    <form method="POST" class="pagination-form">

        <select name="pagination">
            <?php
            if ($results) {
                $total_page = $results->max_num_pages;
                //echo 'Total Page',  $total_page;
                for ($x = 1; $x <= $total_page; $x++) { ?>
                    <option value="<?php echo $x; ?>" <?php if ($pagedPagination == $x) {
                                                            echo 'selected';
                                                        } ?>>Page <?php echo $x; ?></option>;
            <?php }
            }
            ?>
        </select>
        <input type="submit" value="Pagination" class="button button-success">
    </form>

<?php
}

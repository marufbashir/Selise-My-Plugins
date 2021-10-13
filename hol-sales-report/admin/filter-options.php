<?php
// assing value 
$filter_event_start_date = null;




if (isset($_GET['purchase_date']) && !empty($_GET['purchase_date'])) {

    $filterDate = $_GET['purchase_date'];

    $purchaseDateFrom = substr($filterDate, 0, 10);
    $purchaseDateFrom = date('Y-m-d H:i:s', strtotime($purchaseDateFrom));

    $purchaseDateToArray = substr($filterDate, 12, 20);
    $purchaseDateTo = date('Y-m-d H:i:s', strtotime($purchaseDateToArray));

    //echo 'From dates 1: ', $purchaseDateFrom, ' To DATE 2:  ', $purchaseDateTo;

    $filter_purchase_date = $_GET['purchase_date'];
} else {
    $filter_purchase_date = '';
    $purchaseDateFrom = '';

    $purchaseDateTo =  '';
}
if (isset($_GET['event_start_date']) && !empty($_GET['event_start_date'])) {

    $filterStartEventDate = $_GET['event_start_date'];

    // echo '<h3>start date raw </h3>', $filterStartEventDate;

    $eventStarteDateFrom = substr($filterStartEventDate, 0, 10);
    //echo 'after sub str-',$eventStarteDateFrom ;
    $eventStarteDateFrom = date('Y-m-d H:i:s', strtotime($eventStarteDateFrom));

    $eventStartDateTo = substr($filterStartEventDate, 12, 20);
    $eventStartDateTo = date('Y-m-d H:i:s', strtotime($eventStartDateTo));
} else {
    $filter_event_start_date = '';
    $eventStarteDateFrom = '';
    $eventStartDateTo = '';
}

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ event start time !!!!!!!!!!!!!@@@@@@@@@@@@@@@@@@@@
if (isset($_GET['event_start_time']) && !empty($_GET['event_start_time'])) {
    $filterEventTime = $_GET['event_start_time'];
    echo 'event start date ', $_GET['event_start_time'];
} else {
    $filterEventTime = null;
}



//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ event start time fikter end @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
// if(isset($_POST['event_end_date']) && !empty($_POST['event_end_date'])){

//     $orgDate = $_POST['event_end_date'];  
//     $date = str_replace('T"', ' ', $orgDate);  
//     $newDate = date("Y-m-d H:i", strtotime($date));
//     $newEndDate = $newDate.':00';
//     $filter_event_end_date = $newEndDate;
// }
// else{
//     $filter_event_end_date = '';
// }

// payment_status filter on admin-menu.php custom query args line[66,]

//payment_type
if (isset($_GET['payment_type'])) {
    $filterPaymentType = $_GET['payment_type'];

    foreach ($_GET['payment_type'] as $paymentType) {
        array_push($filterPaymentType, $paymentType);
    }
} else {
    $filterPaymentType = array();
}
//event_lang
if (isset($_GET['event_lang'])) {
    $filterEventLang = $_GET['event_lang'];
} else {
    $filterEventLang = '';
}
// event name
if (isset($_GET['event_names']) && !empty($_GET['event_names'])) {

    $filter_event_names = array();

    if (empty($_GET['event_names'][0]) || $_GET['event_names'][0] == 'all') {
        $filter_event_names  = array(); // keep array empty

    } else {
        foreach ($_GET['event_names'] as $eventName) {
            array_push($filter_event_names, $eventName);
        }
    }

    // print_r($filter_event_name);
    //echo '<h1>event set</h1>';
} else {
    $filter_event_names  = array();
    // echo '<h1>event not set</h1>';
}
// event event_customer_email
if (isset($_GET['event_customer_email']) && !empty($_GET['event_customer_email'])) {

    $filterEventCustomerEmail = $_GET['event_customer_email'];
} else {
    $filterEventCustomerEmail = null;
}
// event order_id
// if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {

//     $filterEventOrderID = array($_GET['order_id']);
// } else {
//     $filterEventOrderID = array();
// }


//echo 
//echo 'start date', $filter_event_start_date, ' Payement type', $filterPaymentType;


// order_by_event name
if (isset($_GET['order_by_event']) && !empty($_GET['order_by_event'])) {
    $filterEventName = $_GET['order_by_event'];
} 
else {
    $filterEventName = null;
}
  

?>

<div class="hol-sales-report-filter" style="width: 125%;">
    <h3>Sales Report</h3>
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="" method="get" id="holSalesReportFilterForm">
        <!-- <div class="col-md-3">
            <label for="orderId">
               Order ID
            </label>
            <input type="number" id="orderId" autocomplete="off" placeholder="Order ID" name="order_id" class="form-control" value="<?php echo @$filterEventOrderID; ?>">
        </div>     -->
        <input type="hidden" value="hol-sales-report" name="page">
        <div class="col-md-3">
            <label for="eventDate">
                Purchase date
            </label>
            <input type="text" id="eventDate" autocomplete="off" placeholder="Date Range" name="purchase_date" class="form-control" value="<?php if (isset($_GET['purchase_date']) && !empty($purchaseDateFrom)) {
                                                                                                                                                echo date('Y-m-d', strtotime($purchaseDateFrom)), '-', date('Y-m-d', strtotime($purchaseDateTo));
                                                                                                                                            } ?>">
        </div>

        <div class="col-md-3">
            <label for="eventStartDate">
                Event start date
            </label>
            <input type="text" id="eventStartDate" autocomplete="off" name="event_start_date" class="form-control" value="<?php if (isset($_GET['event_start_date']) && !empty($eventStarteDateFrom)) {
                                                                                                                                echo date('Y-m-d', strtotime($eventStarteDateFrom)), '-', date('Y-m-d', strtotime($eventStartDateTo));
                                                                                                                            } ?>">
        </div>
        <div class="col-md-3">
            <label for="eventTime">
                Event Time
            </label>
            <input type="time" id="eventTime" name="event_start_time" class="form-control" value="<?php echo $filterEventTime; ?>">
        </div>
        <div class="col-md-3">
            <label for="eventCustomerEmail">
                Customer Email </label>
            <input type="email" id="eventCustomerEmail" name="event_customer_email" class="form-control" value="<?php echo @$filterEventCustomerEmail; ?>">
        </div>
        <!-- <label>
            Event end date
            <input type="datetime-local" name="event_end_date" class="form-control">
        </label>  -->
        <!-- <div class="col-md-3">
            <label for="paymentStatus">
                Payment Status</label>
                <?php
                // $gatewayss = WC()->payment_gateways->get_available_payment_gateways();
                // $enabled_gatewayss = [];
                // $paymentStatusses = array();
                // if ($gatewayss) {
                //     foreach ($gatewayss as $gatew) {
                //         $optionValue = strip_tags($gateway->method_title);
                //         if($optionValue  == 'Cash on delivery' || $optionValue  =='Per Nachnahme' ||  $optionValue == 'Paiement à la livraison'){
                //             $optionValue ='Cash on delivery';
                //         }
                //         else{
                //             $optionValue = strip_tags($gateway->method_title);
                //         }
                //         array_push($paymentStatusses, $optionValue);
                //     }

                // }

                ?> -->
        <div class="col-md-3">
            <label for="paymentStatus">
                Payment Status</label>
            <select class="" id="paymentStatus" name="payment_status">
                <option value="">Select</option>
                <option value="paid" <?php if (isset($_GET['payment_status']) && $_GET['payment_status'] == 'paid') {
                                            echo 'selected';
                                        } ?>>Paid</option>
                <option value="unpaid" <?php if (isset($_GET['payment_status']) && $_GET['payment_status'] == 'unpaid') {
                                            echo 'selected';
                                        } ?>>Unpaid</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="paymentType">
                Payment type</label>
            <?php

            $gateways = WC()->payment_gateways->get_available_payment_gateways();
            $enabled_gateways = [];
            ?>
            <select class="" id="paymentType" name="payment_type[]" multiple="multiple" style="width: 250px;">
                <option value="">Select</option>
                <?php

                if ($gateways) {
                    foreach ($gateways as $gateway) {

                        $optionValue = strip_tags($gateway->method_title);
                        if ($optionValue  == 'Cash on delivery' || $optionValue  == 'Per Nachnahme' ||  $optionValue == 'Paiement à la livraison') {
                            $optionValue = 'Cash on delivery';
                        } else {
                            $optionValue = strip_tags($gateway->method_title);
                        }
                ?>
                        <option value="<?php echo $optionValue; ?>" <?php if (isset($_GET['payment_type'])) {
                                                                        foreach ($_GET['payment_type'] as $methodTitle) {
                                                                            if ($gateway->method_title == $methodTitle) {
                                                                                echo 'selected';
                                                                            }
                                                                        }
                                                                    } ?>><?php echo $optionValue; ?></option>;
                <?php
                    }
                }

                // print_r( $enabled_gateways ); // Should return an array of enabled gateways
                ?>
            </select>
        </div>
        <!-- <div class="col-md-3">

            <label for="eventlang">
                Event language </label>
            <select class="" id="eventlang" name="event_lang">
                <option>Select</option>
                <option value="EN">EN</option>
                <option value="DE">DE</option>
                <option value="FR">FR</option>
            </select>
        </div> -->
        <div class="col-md-3">
            <label for="eventName">
                Event Name</label>

            <select class="" id="eventName" name="event_names[]" multiple="multiple" style="width: 400px;" id="eventName" autocomplete="off">
                <option value="all">All Events</option>
                <?php
                $event_args = array('numberposts' => -1, 'type' => 'booking', /*'post_status' => 'publish'*/);

                $events = wc_get_products($event_args);


                //$events = array_column($events, 'name','id');
                foreach ($events as $loadEvent) { ?>
                    <option value="<?php echo $loadEvent->name; ?>" <?php if (isset($_GET['event_names'])) {
                                                                        foreach ($_GET['event_names'] as $getEvent) {
                                                                            if ($loadEvent->name == $getEvent) {
                                                                                echo 'selected';
                                                                            }
                                                                        }
                                                                    } ?>><?php echo $loadEvent->name; ?> </option>
                <?php  }
                ?>

            </select>
        </div>
        <div class="action-div">
            <input type="hidden" name="order_by_event" id="orderByEvent" value="">
            <input type="submit" class="button button-primary" value="Submit" />
            <a href="<?php echo get_home_url(); ?>/wp-admin/admin.php?page=hol-sales-report" class="button button-warning" value="Reset">Reset</a>
        </div>

    </form>
</div>


<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!--select 2 -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.js"></script>
<script>
    function resetHolFilterForm() {
        document.getElementById("holSalesReportFilterForm").reset();
    }
    // select 2
    jQuery(document).ready(function($) {
        if ($('#eventName').length > 0) {
            $('#eventName').select2();
            $(document.body).on("click", function() {
                $('#eventName').select2();
            });
        }
        if ($('#paymentType').length > 0) {
            $('#paymentType').select2();
            $(document.body).on("click", function() {
                $('#paymentType').select2();
            });
        }
    });

    // select 2

    // datepicker
    $('#eventDate').daterangepicker({
        "showWeekNumbers": true,
        // "timePicker": true,
        // "timePicker24Hour": true,
        ranges: {
            // 'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "autoUpdateInput": false,
        // "startDate": [moment(), moment()],
        // "endDate":  [moment(), moment()]
        "applyClass": "btn-primary",
    }, function(start, end, label) {
        console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        $('#eventDate').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
    });
    // keep auto update input false
    // $('#eventDate').daterangepicker({autoUpdateInput: false}, (from_date, to_date) => {
    //   console.log(from_date.toDate(), to_date.toDate());
    //   $('#eventDate').val(from_date.format('YYYY-MM-DD') + ' - ' + to_date.format('YYYY-MM-DD'));
    // });


    $('#eventStartDate').daterangepicker({
        // "timePicker": true,
        // "timePicker24Hour": true,
        // "timePickerIncrement": 5,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "autoUpdateInput": false,
        "alwaysShowCalendars": true,
        // "startDate": "09/02/2021",
        // "endDate": "09/08/2021",

        // locale: {
        //     format: 'YYYY-MM-DD HH:mm'
        // }
    }, function(start, end, label) {
        console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        //   SetDates(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        $('#eventStartDate').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));

    });
    // event start date time 
    // $('#eventStartTime').daterangepicker({
    //     "timePicker": true,
    //     "timePicker24Hour": true,
    //     "timePickerIncrement": 5,
    //     // ranges: {
    //     //     'Today': [moment(), moment()],
    //     //     'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //     //     'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    //     //     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    //     //     'This Month': [moment().startOf('month'), moment().endOf('month')],
    //     //     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //     // },
    //     "autoUpdateInput": true,
    //     "alwaysShowCalendars": true,
    //     // "startDate": "09/02/2021",
    //     // "endDate": "09/08/2021",
    //     locale: {
    //                 format: 'HH:mm'
    //             }

    // }, function(start, end, label) {
    //   console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    // //   SetDates(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));

    // });
</script>
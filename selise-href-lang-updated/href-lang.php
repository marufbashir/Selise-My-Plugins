<?php



function seliseHrefLang()
{
    global $wp;
    $home_url = get_home_url();
    $websiteTypeSSL = substr($home_url, 8, 7); // stage with ssl like https://staging-ch.laderach.com
    $websiteTypeNotSSL = substr($home_url, 7, 7);  // stage without not ssl like http://staging-ch.laderach.com

    if ($websiteTypeSSL == 'staging' || $websiteTypeNotSSL == 'staging') {
        $jsonUrl = file_get_contents('https://ch.laderach.com/wp-content/uploads/href-lang-tag/stage-data.json'); // for stage
    } else {
        $jsonUrl = file_get_contents('https://staging-ch.laderach.com/wp-content/uploads/href-lang-tag/production-data.json');
    }

    $decodedUrls =  json_decode($jsonUrl, true);
    // current url 
    $current_url_1 = home_url($wp->request);
    $current_url_2 = home_url($wp->request) . '/';

   // echo '<pre>'; print_r($decodedUrls); echo '</pre>';
    //echo  '<br/>current url 1 ', $current_url_1;
    //echo  '<br/>current url 2 ', $current_url_2;
    if ($decodedUrls['Sheet1']) {
        foreach ($decodedUrls['Sheet1'] as $key=>$data) {
            
            if(isset($data['de-ch'])){
                $data_de_ch = $data["de-ch"];
            } 
            if(isset($data['en-ca'])){
                $data_en_ca =  $data['en-ca'];
            }
            if(isset($data['en-ch'])){
                $data_en_ch = $data['en-ch'];
            }
           if(isset($data['fr-ch'])){
                $data_fr_ch = $data['fr-ch'];
           }
           if(isset($data['en-de'])){
            $data_en_de = $data['en-de'];
           }
           if(isset($data['de-de'])){
            $data_de_de = $data['de-de'];
           }
           if(isset($data['fr-de'])){
            $data_fr_de = $data['fr-de'];
           }
           if(isset($data['fr-ca'])){
            $data_fr_ca = $data['fr-ca'];
           }
           if(isset($data['en-us'])){
            $data_en_us = $data['en-us'];
           }
           if(isset($data['en-gb'])){
            $data_en_gb = $data['en-gb'];
           }
          // echo 'data en ca is ',  $data_en_ca;
            if (
                $current_url_1 == $data_de_ch || $current_url_1 == $data_en_ch || $current_url_1 == $data_fr_ch  || $current_url_1 == $data_en_de || $current_url_1 == $data_de_de || $current_url_1 == $data_fr_de || $current_url_1 ==  $data_en_ca || $current_url_1 == $data_fr_ca || $current_url_1 == $data_en_us || $current_url_1 == $data_en_gb

                || $current_url_2 == $data_de_ch || $current_url_2 == $data_en_ch || $current_url_2 == $data_fr_ch  || $current_url_2 == $data_en_de || $current_url_2 == $data_de_de || $current_url_2 == $data_fr_de || $current_url_2 ==  $data_en_ca || $current_url_2 == $data_fr_ca || $current_url_2 == $data_en_us || $current_url_2 == $data_en_gb 
            ) {   ?>
                <!--custom href-lang -->
                <link rel="alternate" href="<?php echo $data['de-ch'] ?>" hreflang="de-ch" />
                <link rel="alternate" href="<?php echo $data['en-ch'] ?>" hreflang="en-ch" />
                <link rel="alternate" href="<?php echo $data['fr-ch'] ?>" hreflang="fr-ch" />
                <link rel="alternate" href="<?php echo $data['en-de'] ?>" hreflang="en-de" />
                <link rel="alternate" href="<?php echo $data['de-de'] ?>" hreflang="de-de" />
                <link rel="alternate" href="<?php echo $data['fr-de'] ?>" hreflang="fr-de" />
                <link rel="alternate" href="<?php echo $data['en-ca'] ?>" hreflang="en-ca" />
                <link rel="alternate" href="<?php echo $data['fr-ca'] ?>" hreflang="fr-ca" />
                <link rel="alternate" href="<?php echo $data['en-us'] ?>" hreflang="en-us" />
                <link rel="alternate" href="<?php echo $data['en-gb'] ?>" hreflang="en-gb" />
                <!--/custom href-lang -->
<?php }
        } //foreach end
    } // if sheet1 end
    // else {
    //     echo 'Href Lang Json Root Variable name must be <strong>Sheet1</strong>';
    // }
    return; // return default  
}

add_action('wp_head', 'seliseHrefLang');


// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ remove default hreflang-tag from polylang @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 
function filter_pll_rel_hreflang_attributes($hreflangs)
{
    //return $hreflangs; 
};
add_filter('pll_rel_hreflang_attributes', 'filter_pll_rel_hreflang_attributes', 10, 1);

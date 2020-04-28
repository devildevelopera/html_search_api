<?php
    $data;
    $flag1 = false;
    $flag2 = false;
    $flag3 = false;
    $search_val = $_POST['search_val'];


    // itunes.apple.com
    $contentone = file_get_contents('https://itunes.apple.com/search?term='.$search_val);
    $jsonone = json_decode($contentone, true);
    // print_r($jsonone);
    display($jsonone['results'], 'itunes.apple.com');


    // api.tvmaze.com
    $contenttwo = file_get_contents('http://api.tvmaze.com/search/shows?q='.$search_val);
    $jsontwo = json_decode($contenttwo, true);
    // print_r($contenttwo);
    display($jsontwo, 'api.tvmaze.com');


    // api.worldbank.org
    $contentthree = file_get_contents('http://api.worldbank.org/v2/country/'.$search_val.'?format=json');
    $jsonthree = json_decode($contentthree, true);
    // print_r($jsonthree);
    display($jsonthree, 'api.worldbank.org');
    
    function display($response, $url) {
        // print_r($response);
        if($url === 'itunes.apple.com') {
            for ($i = 0; $i <count($response); $i++) {
                if(isset($response[$i]['trackViewUrl'])) {
                    $GLOBALS['data'][$response[$i]['artistName']] = [$url, $response[$i]['trackViewUrl']];
                } else {
                    $GLOBALS['data'][$response[$i]['artistName']] = [$url, '#'];
                }
            }
            $GLOBALS['flag1'] = true;
        }
        if($url === 'api.tvmaze.com') {
            for ($i = 0; $i <count($response); $i++) {
                $GLOBALS['data'][$response[$i]['show']['name']] = [$url, $response[$i]['show']['url']];
            }
            $GLOBALS['flag2'] = true;
        }
        if($url === 'api.worldbank.org') {
            if(count($response) != 1){
                $GLOBALS['data'][$response[1][0]['name']] = [$url, '#'];
            }
            $GLOBALS['flag3'] = true;
        }
        if($GLOBALS['flag1'] && $GLOBALS['flag2'] && $GLOBALS['flag3']) {
            echo json_encode($GLOBALS['data']);
        }
    }
?>
<?php
header('Content-Type: application/json');
include 'simple_html_dom.php';

# Use the Curl extension to query Google and get back a page of results
$broken = [
    21
];

function compress_htmlcode($codedata)
{
    $searchdata = array(
        '/\>[^\S ]+/s', // remove whitespaces after tags
        '/[^\S ]+\</s', // remove whitespaces before tags
        '/(\s)+/s' // remove multiple whitespace sequences
    );
    $replacedata = array('>','<','\\1');
    $codedata = preg_replace($searchdata, $replacedata, $codedata);
    return $codedata;
}

function trim_whitespace($str) {
    return preg_replace('/\s+/', ' ',$str);
}
if (!empty($_POST["i"])) {
    $index = $_POST["i"];
//for ($index = 372; $index <= 100001; $index++) {
    if (!in_array($index, $broken)) {
        //$zeroes = str_pad(strval($index), 6, '0', STR_PAD_LEFT);
        $url = "https://www.imdb.com/title/" . $index . "/";
        $html = file_get_html($url);
        $doc = str_get_html($html);
        $tit = $doc->find('title')[0]->plaintext;
        $tit = str_replace(') - IMDb', '', $tit);
        $titl = explode('(', $tit);
        $titleYear = '('.$titl[1].')';
        $title = trim($titl[0]);

        preg_match_all('/ld\+json">(.*?)<\/script>/s', $html, $item);
        $imdb = json_decode($item[1][0],true);
        $poster = $imdb['image'];

        $ratingValue = $imdb['aggregateRating']['ratingValue'];
        $ratingCount = $imdb['aggregateRating']['ratingCount'];
        $date = explode('-', $imdb['datePublished']);
        $info = str_replace(array('PT','H','M'), array('','h ','min'), $imdb['duration']).' | '.implode(', ', $imdb['genre']);
        $plot = $imdb['description'];

        $cred = [];
        for ($x = 0; $x < count($imdb['director']); $x++) {
            $cred['director'][$x] = $imdb['director'][$x]['name'];
        }
        for ($x = 0; $x < count($imdb['creator']); $x++) {
            $cred['creator'][$x] = $imdb['creator'][$x]['name'];
        }
        for ($x = 0; $x < count($imdb['actor']); $x++) {
            $cred['actor'][$x] = $imdb['actor'][$x]['name'];
        }
        $credit[0] = '[B]Director[/B]: '.implode(', ', array_filter($cred['director']));
        $credit[1] = '[B]Writer[/B]: '.implode(', ', array_filter($cred['creator']));
        $credit[2] = '[B]Stars[/B]: '.implode(', ', array_filter($cred['actor']));

        $post_data = json_encode(
            array(
                'imdbID' => $index,
                'Year' => $titleYear,
                'Title' => $title,
                'Name' => $title . ' ' . $titleYear,
                'Poster' => $poster,
                'imdbRating' => $ratingValue,
                'imdbVotes' => $ratingCount,
                'info' => $info,
                'Plot' => $plot,
                'Credit' => $credit
            )
        );
        //file_put_contents('out.json', $post_data . ",", FILE_APPEND | LOCK_EX);
        echo $post_data;
    }
}
?>

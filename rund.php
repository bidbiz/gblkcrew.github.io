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
        $url = "https://mydramalist.com/" . $index . "/";

        $html = file_get_html($url);
        $doc = str_get_html($html);
//echo $doc->find('.film-cover')[0];
//exit();
        $title = $doc->find('title')[0]->innertext;
        $title = str_replace(" - MyDramaList", "", $title);

        $poster = "";
        if (count($doc->find('.film-cover')) > 0) {
            $poster = $doc->find('.film-cover')[0]->find('img')[1]->src;
        }

        $ratingValue = '0';
        if (count($doc->find('.deep-orange')) > 0) {
            $ratingValue = $doc->find('.deep-orange')[0]->innertext;
        }
        $info = "N/A";
        if (count($doc->find('.plot')) > 0) {
            $info = $doc->find('.plot')[0]->find('span')[0]->innertext;
            $doc->find(".subtext")->outertext = '';
            $info = str_replace("  ", "", $info);
            $info = str_replace(",", ", ", $info);
            $info = str_replace("|", " | ", $info);
        }
        $plot = "N/A";
        if (count($doc->find('.show-synopsis')) > 0) {
            $plot = $doc->find('.show-synopsis')[0]->find('span')[0]->innertext;
            $plot = str_replace('<span class="read-more-hidden">', "", $plot);
            $plot = str_replace("</span>", "", $plot);
        }
        $credit = [];
        if (count($doc->find('.show-detailsxss')[0]->find('li')) > 0) {
            for ($i = 0; $i < count($doc->find('.show-detailsxss')[0]->find('li')); $i++) {
                $cred[$i] = $doc->find('.show-detailsxss')[0]->find('li')[$i]->find('b')[0]->plaintext;
                $cred[$i] = str_replace(":", "", $cred[$i]);
                $creds[$i] = str_replace(" ", "", $cred[$i]);
                $credi[$i] = $doc->find('.show-detailsxss')[0]->find('li')[$i]->plaintext;
                $credi[$i] = str_replace($cred[$i].":", "", $credi[$i]);
                $credit[$creds[$i]] = trim($credi[$i]);
            }
        }
        $actors = [];
        if (count($doc->find('.p-a-sm')[0]->find('li')) > 0) {
            for ($i = 0; $i < count($doc->find('.p-a-sm')[0]->find('li')); $i++) {
                $actors[$i] = $doc->find('.p-a-sm')[0]->find('li')[$i]->find('a')[1]->plaintext;
            }
        }

/*
        $urlFullCredit = $url.'fullcredits/';
        $htmlFullCredit = file_get_html($urlFullCredit);
        $docFullCredit = str_get_html($htmlFullCredit);
        $h4FullCredit = $docFullCredit->find('#fullcredits_content')[0]->find('h4');
        $tableFullCredit = $docFullCredit->find('#fullcredits_content')[0]->find('table');
        $arrayFullCredit = [];
        if (count($h4FullCredit) !== count($tableFullCredit)) {
            echo 'error not matching counts h4 vs table';
        }

        for ($i = 0; $i < count($h4FullCredit); $i++) {
            $h4 = $h4FullCredit[$i];
            $table = $tableFullCredit[$i];

            $name = str_replace('&nbsp;','', $h4->innertext);
            $name = trim_whitespace($name);
            $text = compress_htmlcode($table->outertext);
            $arrayFullCredit[$i] = array('id' => $i, 'name' => $name, 'text' => $text);
        }

        $urlPlotSummary = $url.'plotsummary/';
        $htmlPlotSummary = file_get_html($urlPlotSummary);
        $docPlotSummary = str_get_html($htmlPlotSummary);
        $plotSummaryName = $docPlotSummary->find('#summaries')[0]->innertext;
        $plotSummaryUl= $docPlotSummary->find('#plot-summaries-content')[0]->find('li');

        $arrayPlotSummary = [];
        for ($i = 0; $i < count($plotSummaryUl); $i++) {
            $li = $plotSummaryUl[$i];

            $name = str_replace('&nbsp;', '', $plotSummaryName);
            $text = str_replace('   ', '', $li->find('p')[0]->innertext);
            $text = str_replace(' <p>', '<p>',$text);
            $text = str_replace('</p> ', '<p>', $text);
            $text = str_replace('"', '\\"', $text);
            $author = "";
            if (count($li->find('div')) > 0) {
                $author = str_replace(' ', '', $li->find('div')[0]->find('em')[0]->find('a')[0]->innertext);
            }

            $arrayPlotSummary[$i] = array('id' => $i, 'name' => $name, 'text' => $text, 'author' => $author);
        }

        $synopsisName = $docPlotSummary->find('#synopsis')[0]->innertext;
        $synopsisUl = $docPlotSummary->find('#plot-synopsis-content')[0]->find('li');

        $arraySynopsis = [];
        for ($i = 0; $i < count($synopsisUl); $i++) {
            $li = $synopsisUl[$i];

            $name = str_replace('&nbsp;','', $synopsisName);
            $text = str_replace('   ','', $li->innertext);
            $text = str_replace('  <p>', '<p>', $text);
            $text = str_replace('</p> ', '<p>', $text);
            $text = str_replace('"', '\\"', $text);

            $arraySynopsis[$i] = array('id' => $i, 'name' => $name, 'text' => $text, 'author' => $author);
        }

        $urlKeywords = $url.'keywords/';
        $htmlKeywords = file_get_html($urlKeywords);
        $docKeywords = str_get_html($htmlKeywords);

        $keywords = [];
        if (count($docKeywords->find('.dataTable.evenWidthTable2Col')) > 0) {
            $tableKeywords = $docKeywords->find('.dataTable.evenWidthTable2Col')[0];
            $docKeywords->find('.dataTable.evenWidthTable2Col')[0]->find('.did-you-know-actions')[0]->outertext = '';

            $tableKeywords = str_replace("   ", "", $tableKeywords);
            $keywords = array('id' => 0, 'name' => 'Keywords', 'text' => $tableKeywords);
        }

        $urlTaglines = $url.'taglines/';
        $htmlTaglines = file_get_html($urlTaglines);
        $docTaglines = str_get_html($htmlTaglines);
        $arrayTaglines = [];
        if (count($docTaglines->find('.soda')) > 0) {
            $taglineDivs = $docTaglines->find('.soda');
            for ($i = 0; $i < count($taglineDivs); $i++) {
                $div = $taglineDivs[$i];
                $text = str_replace('   ','', $div->innertext);
                if (strpos($text, 'It looks like') !== false) {
                    $text = '';
                } else {
                    $text = str_replace('  ','', $text);
                }

                $arrayTaglines[$i] = array('id' => $i, 'name' => 'Taglines', 'text' => $text);
            }
        }

        $wraps = $doc->find('.see-more.inline.canwrap');
        $aGenres = '';
        for ($i = 0; $i < count($wraps); $i++) {
            $h4Txt = $wraps[$i]->find('h4')[0]->innertext;

            if ($h4Txt === 'Genres:') {
                $aGenres = $aGenres . $wraps[$i]->find('a')[0]->innertext;
                for ($j = 0; $j < count($wraps[$i]->find('a')); $j++) {
                    if ($j > 0) {
                        $aGenres = $aGenres . ',' . $wraps[$i]->find('a')[$j]->innertext;
                    }
                }
            }
        }

        if ($aGenres === '') {
            $aGenres = 'Horror';
        }

        $urlParentalGuide = $url.'parentalguide/';
        $htmlParentalGuide = file_get_html($urlParentalGuide);
        $docParentalGuide = str_get_html($htmlParentalGuide);
        $arrayParentalGuide = [];
        $sections = $docParentalGuide->find('section.article.listo.content-advisories-index')[0]->find('section');

        for ($i = 0; $i < count($sections); $i++) {

            $section = $sections[$i];

            if ($i === 0) {
                $name = $section->find('header')[0]->find('h4')[0]->innertext;
                $text = '';
                if (count($section->find('table')) > 0) {
                    $text = compress_htmlcode($section->find('table')[0]->outertex);
                }

                $arrayParentalGuide[$i] = array('id' => $i, 'name' => $name, 'text' => [$text]);
            } else {
                $name = $section->find('h4')[0]->innertext;
                $texts = $section->find('li.ipl-zebra-list__item');
                $textsArr = [];
                for ($j = 0; $j < count($texts); $j++) {
                    $texts[$j]->find('.ipl-hideable-container.ipl-hideable-container--hidden.ipl-zebra-list__action-row')[0]->outertext = '';
                    $text = $texts[$j]->innertext;
                    $text = str_replace('                         ', '', $text);
                    $text = str_replace('.                           ', '.', $text);
                    $text = str_replace('.  ', '.', $text);
                    $textsArr[$j] = $text;
                }
                $arrayParentalGuide[$i] = array('id' => $i, 'name' => $name, 'text' => $textsArr);
            }
        }


        $urlReleaseInfo = $url.'releaseinfo/';
        $htmlReleaseInfo = file_get_html($urlReleaseInfo);
        $docReleaseInfo = str_get_html($htmlReleaseInfo);
        $arrayReleaseInfo = [];
        $h4Arr = $docReleaseInfo->find('#releaseinfo_content')[0]->find('h4');
        $tableArr = $docReleaseInfo->find('#releaseinfo_content')[0]->find('table');

        if (count($h4Arr) !== count($tableArr)) {
            echo 'error not matching counts h4 vs table';
        }

        for ($i = 0; $i < count($h4Arr); $i++) {
            $h4 = $h4Arr[$i];
            $table = $tableArr[$i];

            $name = str_replace('&nbsp;','', $h4->innertext);
            $name = str_replace('     ','', $h4->innertext);
            $text = compress_htmlcode($table->outertext);
            $arrayReleaseInfo[$i] = array('id' => $i, 'name' => $name, 'text' => $text);
        }

        $urlLocations = $url.'locations/';
        $htmlLocations = file_get_html($urlLocations);
        $docLocations = str_get_html($htmlLocations);
        $locations = array('id' => 0, 'name' => 'Filming Locations', 'text' => '');

        if (count($docLocations->find('h4.ipl-header__content.ipl-list-title')) > 0) {
            $nameLocations = $docLocations->find('h4.ipl-header__content.ipl-list-title')[0]->innertext;

            $sodaDivs = $docLocations->find('div.soda.sodavote');
            $texts = [];
            for ($i = 0; $i < count($sodaDivs); $i++) {
                $soda = $sodaDivs[$i];
                $text = $soda->find('dt')[0]->find('a')[0]->innertext;
                $texts[$i] = $text;
            }

            $locations = array('id' => 0, 'name' => $nameLocations, 'text' => $texts);
        }

        $dates = array('id' => 0, 'name' => 'Filming Dates', 'text' => '');
        if (count($docLocations->find('#filming_dates')) > 0) {
            $nameDates = $docLocations->find('#filming_dates')[0]->find('h4')[0]->innertext;

            $lis = $docLocations->find('#filming_dates')[0]->find('ul')[0]->find('li.ipl-zebra-list__item');
            $texts = [];
            for ($i = 0; $i < count($lis); $i++) {
                $li = $lis[$i];
                $text = $li->innertext;
                $text = str_replace('                     ', '', $text);
                $text = str_replace('    ', '', $text);
                $texts[$i] = $text;
            }

            $dates = array('id' => 0, 'name' => $nameDates, 'text' => $texts);
        }

        $urlTechnical = $url.'technical/';
        $htmlTechnical = file_get_html($urlTechnical);
        $docTechnical = str_get_html($htmlTechnical);
        $technical = array('id' => 0, 'name' => 'Technical Specifications', 'text' => '');

        if (count($docTechnical->find('table.dataTable.labelValueTable')) > 0) {
            $text = compress_htmlcode($docTechnical->find('table.dataTable.labelValueTable')[0]->outertext);

            $technical = array('id' => 0, 'name' => 'Technical Specifications', 'text' => $text);
        }

        $urlFAQ = $url.'faq/';
        $htmlFAQ = file_get_html($urlFAQ);
        $docFAQ = str_get_html($htmlFAQ);

        $faqHeads = $docFAQ->find('.ipl-header__content.ipl-list-title');
        $faqUls = $docFAQ->find('ul.ipl-zebra-list');

        $arrayFAQ = [];

        for ($j = 0; $j < count($faqHeads); $j++) {
            $name = $faqHeads[$j]->innertext;
            $faqTexts = [];
            for ($i = 0; $i < count($faqUls[$j]->find('div.faq-question-text')); $i++) {
                $text = $faqUls[$j]->find('div.faq-question-text')[$i]->innertext;
                $faqTexts[$i] = $text;
            }
            $arrayFAQ[$i] = array('id' => $i, 'name' => $name, 'text' => $faqTexts);
        }

        $urlAwards = $url.'awards/';
        $htmlAwards = file_get_html($urlAwards);
        $docAwards = str_get_html($htmlAwards);

        $awardsHeads = $docAwards->find('.article.listo')[0]->find('h3');
        $awardsTables = $docAwards->find('table.awards');

        $arrayAwards = [];

        for ($j = 0; $j < count($awardsTables); $j++) {
            $arrayAwards[$j] = array('id' => $j, 'name' =>  trim_whitespace($awardsHeads[$j]->plaintext), 'text' => compress_htmlcode($awardsTables[$j]->outertext));
        }

        $urlSoundtrack = $url.'soundtrack/';
        $htmlSoundtrack = file_get_html($urlSoundtrack);
        $docSoundtrack = str_get_html($htmlSoundtrack);
        $soundtracks = array('id' => 0, 'name' => 'Soundtrack Credits', 'text' => '');

        if (count($docSoundtrack->find('#no_content')) <= 0) {
            $nameSoundtrack = $docSoundtrack->find('#soundtracks_content')[0]->find('h4')[0]->innertext;
            $nameSoundtrack = str_replace("&nbsp;","", $nameSoundtrack);
            $sodaDivs = $docSoundtrack->find('.soundTrack.soda');
            $texts = [];
            for ($i = 0; $i < count($sodaDivs); $i++) {
                $soda = $sodaDivs[$i];
                $text = $soda->plaintext;
                $texts[$i] =  trim_whitespace($text);
            }

            $soundtracks = array('id' => 0, 'name' => $nameSoundtrack, 'text' => $texts);
        }

        $urlTrivia = $url.'trivia/';
        $htmlTrivia = file_get_html($urlTrivia);
        $docTrivia = str_get_html($htmlTrivia);
        $trivias = array('id' => 0, 'name' => 'Trivia', 'text' => '');

        if (count($docTrivia->find('#no_content')) <= 0) {
            $sodaDivs = $docTrivia->find('.sodatext');
            $texts = [];
            for ($i = 0; $i < count($sodaDivs); $i++) {
                $soda = $sodaDivs[$i];
                $text = $soda->plaintext;
                $texts[$i] =  trim_whitespace($text);
            }

            $trivias = array('id' => 0, 'name' => 'Trivia', 'text' => $texts);
        }

        $urlQuotes = $url.'quotes/';
        $htmlQuotes = file_get_html($urlQuotes);
        $docQuotes = str_get_html($htmlQuotes);
        $quotes = array('id' => 0, 'name' => 'Quotes', 'text' => '');

        if (count($docQuotes->find('#no_content')) <= 0) {
            $sodaDivs = $docQuotes->find('.sodatext');
            $texts = [];
            for ($i = 0; $i < count($sodaDivs); $i++) {
                $soda = $sodaDivs[$i];
                $text = $soda->plaintext;
                $texts[$i] =  trim_whitespace($text);
            }

            $quotes = array('id' => 0, 'name' => 'Quotes', 'text' => $texts);
        }

        $urlGoofs = $url.'goofs/';
        $htmlGoofs = file_get_html($urlGoofs);
        $docGoofs = str_get_html($htmlGoofs);
        $goofs = array('id' => 0, 'name' => 'Goofs', 'text' => '');

        if (count($docGoofs->find('#no_content')) <= 0) {
            $sodaDivs = $docGoofs->find('.sodatext');
            $texts = [];
            for ($i = 0; $i < count($sodaDivs); $i++) {
                $soda = $sodaDivs[$i];
                $text = $soda->plaintext;
                $texts[$i] =  trim_whitespace($text);
            }

            $goofs = array('id' => 0, 'name' => 'Goofs', 'text' => $texts);
        }

        $urlCC = $url.'crazycredits/';
        $htmlCC = file_get_html($urlCC);
        $docCC = str_get_html($htmlCC);
        $CC = array('id' => 0, 'name' => 'Crazy Credits', 'text' => '');

        if (count($docCC->find('#no_content')) <= 0) {
            $sodaDivs = $docTrivia->find('.sodatext');
            $texts = [];
            for ($i = 0; $i < count($sodaDivs); $i++) {
                $soda = $sodaDivs[$i];
                $text = $soda->plaintext;
                $texts[$i] =  trim_whitespace($text);
            }

            $CC = array('id' => 0, 'name' => 'Crazy Credits', 'text' => $texts);
        }

        $urlAV = $url.'alternateversions/';
        $htmlAV = file_get_html($urlAV);
        $docAV = str_get_html($htmlAV);
        $AV = array('id' => 0, 'name' => 'Alternate Versions', 'text' => '');

        if (count($docAV->find('#no_content')) <= 0) {
            $sodaDivs = $docAV->find('.sodatext');
            $texts = [];
            for ($i = 0; $i < count($sodaDivs); $i++) {
                $soda = $sodaDivs[$i];
                $text = $soda->plaintext;
                $texts[$i] =  trim_whitespace($text);
            }

            $AV = array('id' => 0, 'name' => 'Crazy Credits', 'text' => $texts);
        }

        $urlMC = $url.'movieconnections/';
        $htmlMC = file_get_html($urlMC);
        $docMC = str_get_html($htmlMC);
        $MC = array('id' => 0, 'name' => 'Connections', 'text' => '');

        if (count($docMC->find('#no_content')) <= 0) {
            $sodaDivs = $docMC->find('.sodatext');
            $texts = [];
            for ($i = 0; $i < count($sodaDivs); $i++) {
                $soda = $sodaDivs[$i];
                $text = $soda->plaintext;
                $texts[$i] =  trim_whitespace($text);
            }

            $MC = array('id' => 0, 'name' => 'Crazy Credits', 'text' => $texts);
        }

        echo $url . "\n";

        $post_data = json_encode(
            array(
                'titleYear' => $title,
                'title' => $titleYear,
                'name' => $title . ' ' . $titleYear,
                'imdb' => array(
                    'url' => $url,
                    'poster' => $poster,
                    'rating' => $ratingValue,
                    'count' => $ratingCount,
                    'genre' => $aGenres,
                    'arrayFullCredit' => $arrayFullCredit,
                    'arrayPlotSummary' => $arrayPlotSummary,
                    'arraySynopsis' => $arraySynopsis,
                    'keywords' => $keywords,
                    'arrayTaglines' => $arrayTaglines,
                    'arrayParentalGuide' => $arrayParentalGuide,
                    'arrayReleaseInfo' => $arrayReleaseInfo,
                    'locations' => $locations,
                    'dates' => $dates,
                    'technical' => $technical,
                    'arrayFAQ' => $arrayFAQ,
                    'arrayAwards' => $arrayAwards,
                    'soundtracks' => $soundtracks,
                    'trivias' => $trivias,
                    'quotes' => $quotes,
                    'goofs' => $goofs,
                    'CC' => $CC,
                    'AV' => $AV,
                    'MC' => $MC
                )
            )
        );*/
        $post_data = json_encode(
            array(
                'imdbID' => $index,
                'Title' => $title,
                'Name' => $title,
                'Poster' => $poster,
                'imdbRating' => $ratingValue,
                'Plot' => $plot,
                'Credit' => array_unique($credit),
                'Actors' => implode("\n", $actors)
            )
        );
        //file_put_contents('out.json', $post_data . ",", FILE_APPEND | LOCK_EX);
        echo $post_data;
    }
}
?>

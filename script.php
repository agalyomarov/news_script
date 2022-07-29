<?php

namespace App;

ini_set('log_errors', 'On');
ini_set('error_log', 'php_errors.log');
ini_set('allow_url_fopen', 1);
require "vendor/autoload.php";

use PHPHtmlParser\Dom;

$dom1 = new Dom();
$dom2 = new Dom();
$dom3 = new Dom();
try {
   $run = false;
   define('NEWSES', 'новости');
   define('NEWS', 'новость');
   $li = scandir('li/');
   $page = scandir('page/');
   if (count($li) < 3 || count($page) < 3) {
      die;
   }
   $news1 = NEWS . '1.html';
   $news2 = NEWS . '2.html';
   $news3 = NEWS . '3.html';
   unlink($news3);
   rename($news2, $news3);
   rename($news1, $news2);
   rename('page/' . $page[2], $news1);
   $block = file_get_contents('li/' . $li[2]);
   $main = file_get_contents(NEWSES . '.html');
   $news1html = file_get_contents('./' . $news1);
   $title = file_get_contents('./' . 'title.txt');
   $news1html = str_replace("{{title}}", $title, $news1html);
   file_put_contents($news1, $news1html);
   unlink('li/' . $li[2]);
   $li3Start = strpos($main, '<!--<li3>-->');
   $li3End = strpos($main, '<!--</li3>-->');
   $li2Start = strpos($main, '<!--<li2>-->');
   $li2End = strpos($main, '<!--</li2>-->');
   $li1Start = strpos($main, '<!--<li1>-->');
   $li1End = strpos($main, '<!--</li1>-->');
   $li3Length = $li3End - $li3Start;
   $li2Length = $li2End - $li2Start;
   $li1Length = $li1End - $li1Start;
   $li3Block = mb_strcut($main, $li3Start + 13, $li3Length - 14);
   $li2Block = mb_strcut($main, $li2Start + 13, $li2Length - 14);
   $li1Block = mb_strcut($main, $li1Start + 13, $li1Length - 14);
   $dom3->loadStr($li3Block);
   $dom2->loadStr($li2Block);
   $dom1->loadStr($li1Block);
   $dom1->find('a')[0]->setAttribute('href', $dom2->find('a')[0]->getAttribute('href'));
   $dom2->find('a')[0]->setAttribute('href', $dom3->find('a')[0]->getAttribute('href'));
   $main = substr_replace($main, $dom2, $li3Start + 13, $li3Length - 14);
   $main = substr_replace($main, $dom1, $li2Start + 13, $li2Length - 14);
   $main = substr_replace($main, $block, $li1Start + 13, $li1Length - 14);
   echo $main;
   file_put_contents(NEWSES . '.html', $main);
} catch (\Exception $e) {
   print_r($e->getMessage());
}

<?php

  require_once('FeedItem.class.php');
  $summary = '';
  foreach(FeedItem::$words as $word) {
      $summary .= '<tr><td>'.$word[0] . '<td>' . $word[1]  ;
  }
  $data['content'][0]['title'] = 'Вес слов';
  $data['content'][0]['summary'] = "<table>$summary</table>";
  require_once('template.php');
?>

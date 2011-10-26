<?php
  $filename = getFilename( $_GET['label'], $_GET['date'] );
  $feed = getFeed( $filename );
  
      $item = $feed->getItem( $_GET['id'] )->rate( $rate );
 
  setFeed( $filename, $feed );
?>

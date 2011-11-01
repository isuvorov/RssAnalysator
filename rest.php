<?php

  require_once 'FeedCollection.class.php';
  if ( isset( $_GET['rate'] ) ) {
      $label = $_GET['label'];
      $id = $_GET['id'];
      $rating = $_GET['rating'];
      $feedColl = FeedCollection();
      $filename = getFilename( $label, $id );
      $feed = getFeed( $filename );

      $item = $feed->getItem( $_GET['id'] )->rate( $rate );

      setFeed( $filename, $feed );
  }
?>

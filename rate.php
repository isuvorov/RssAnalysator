<?php

  require_once 'Feed.class.php';
  require_once 'RatingFeed.class.php';
  $login = 'coder24ru';
  $label = 'RusFreelance';
  $location = $login.'/'.$label;
  $rFeed = new RatingFeed( $location );

  if ( !$_GET['id'] || !$_GET['rating'] )
      exit( "no id, no rating" );
  $id = $_GET['id'];
  $rating = (int) $_GET['rating'];
  var_dump( $rFeed->rate( $id, $rating ) );
  //echo $rFeed->getLocation();
  //$rFeer->getLocation();
  //echo $rFeer->getLocation();
  //$rFeer->rate( 'qwe', 1 );
?>

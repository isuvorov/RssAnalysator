<?php

  interface IFeed {

      public function getItems ();

      //public function getItem ( $id );

      public function addItems ( $items );

      public function sort ();
  }

  class Feed implements IFeed {

      public $timestamp;
      public $items;
      public $filename;

      public function __construct () {
          $this->timestamp = 0; //time();
          $this->items = array( );
      }

      public function getItems () {

          //TODO PHP53 lambda
          return $this->items;
      }

      public function addItems ( $items ) {
          $items = array_map( 'Array2FeedItem', $items );
          $this->items = array_unique( array_merge( $this->items, $items ) );
      }

      public function sort () {
          if ( !function_exists( "cmp" ) ) {

              function cmp ( FeedItem $a, FeedItem $b ) {
                  if ( $a->getWeight() == $b->getWeight() ) {
                      return 0;
                  }
                  return ($a->getWeight() < $b->getWeight()) ? 1 : -1;
              }

          }

          usort( $this->items, "cmp" );
      }

  }

  class LongFeed extends Feed {
      
  }

  function getFilename ( $label, $date = 0 ) {
      return 'feed_' . $label . '.txt';
  }

  function getFeed ( $filename ) {
      @$content = file_get_contents( $filename );
      if ( !$content )
          return new Feed();
      return unserialize( $content );
  }

  function setFeed ( $filename, $feed ) {
      $content = serialize( $feed );
      file_put_contents( $filename, $content );
  }

?>

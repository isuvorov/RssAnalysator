<?php
require_once 'Feed.class.php';
  class FeedCollection {

      public $feed;//Feed
      
      
      public $label, $date, $id;
      public $login;

      //TODO only login
      function __construct ( $label, $date, $id, $login = 'coder24ru@gmail.com' ) {
          $this->label = $label;
          $this->date = $date;
          $this->id = $id;
      }

      protected static function getFilename ( $label, $date, $id ) {
          return 'feed_' . $label . '.txt';
      }

      protected static function getFeedFromFile ( $filename ) {
          @$content = file_get_contents( $filename );
          if ( !$content )
              return new Feed();
          return unserialize( $content );
      }

      public function loadFeed () {
          $filename = $this->getFilename( $this->label, $this->date, $this->id );
          $this->feed = $this->getFeedFromFile( $filename );
          return $this->feed;
      }

      function saveFeed () {
          $content = serialize( $this->feed );
          $filename = $this->getFilename( $this->label, $this->date, $this->id );
          file_put_contents( $filename, $content );
      }

  }
?>
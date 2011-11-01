<?php

  require_once 'Feed.class.php';

  interface IRatingFeed {

      public function rate ( $id, $rating );
  }

  interface ISavingFeed {

      public function setLocation ( $location );

      public function getLocation ();

      public function save ();

      public function load ();
  }
/**
 *  * 

      public function setLocation ( $location ) {
          $this->location = $location;
      }

      public function getLocation () {
          return $this->location;
      }

      public function save () {
          
      }

      public function load () {
          
      }
 * 
 */
  class RatingFeed extends Feed implements IRatingFeed {

      public $stopWords;
      public $wordWeigth;
      public $ratingFeed;
      public $location;

      public function __construct ( $location ) {
          parent::__construct();
          //$this->setLocation( $location );
          //$this->location = $location;
      }

     

      public function rate ( $id, $rating ) {
          $feedItem = $this->getItem( $id );
          if ( $feedItem !== null ) {
              $feedItem->setRating( $rating );
              var_dump($feedItem->getWordAndCount());
              return true;
          } else {
              return false;
          }
          //echo $id;
          //echo $rating;
      }

  }

?>

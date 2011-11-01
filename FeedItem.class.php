<?php

  require_once ('ilib.php');
  require_once ('interfaces.php');

  class FeedItem implements IFeedItem {

      private $id;
      private $href;
      private $title;
      private $content;
      private $published;
      private $updated;
      private $rating;
      protected $weight;

      public function __construct ( array $param ) {
          $paramKeys = array( 'id', 'href', 'title', 'content', 'published', 'updated', 'rating' );
          foreach ( $paramKeys as $key ) {
              $this->$key = $param[$key];
          }
      }

      public function getId () {

          return substr( $this->id, strlen( 'tag:google.com,2005:reader/item/' ) );
          return $this->id;
      }

      public function getHref () {
          return $this->href;
      }

      public function getTitle () {
          return $this->title;
      }

      public function getContent () {
          return $this->content;
      }

      public function getSite () {
          return domain_from_url( $this->getHref() );
      }

      public function getDate () {
          return $this->published;
      }

      public function getIcon () {
          return 'http://' . domain_from_url( $this->getHref() ) . '/favicon.ico';
      }

      //need for comparing 
      public function __toString () {
          return $this->getId();
      }

      public function getRating () {
          return $this->rating;
      }

      public function isRated () {
          return $this->rating != 0;
      }

      public function setRating ( $rating ) {
          $this->rating = $rating;
      }

      public function getWeight () {
          return $this->weight;
      }

      //породить или не породить вот в чем вопрос
      //class RatedFeedItem extends FeedItem implements IFeedItem {
      /**
       *
       * @param IFeedAnalyser $feedAnalyser 
       */
      public static function canonizeText ( $content ) {
          //TODO cut emails phone numbers  
          $content = mb_strtolower( $content, 'UTF-8' );
          $pattern = "/[^a-zа-я0-9]/ui";
          return preg_replace( $pattern, ' ', $content );
          $puntctuation = array( '(', ')', '{', '}', '[', ']', '`', '~', '/', ',', ':', '!', '.', '?', "\n", "\r", "\t", );
          return ' ' . str_replace( $puntctuation, ' ', $content ) . ' ';
      }

      public function countWeight ( $feedAnalyser ) {
          $weight = 0;
          foreach ( $feedAnalyser->getWordsWeight() as $word => $count ) {
              $weight += substr_count( $this->getContent(), $word ) * $count;
          }
          $this->weight = $weight;
      }

      public function getWordsCount () {
          $content = $this->getContent();
          //var_dump( $content );
          $content = $this->canonizeText( $content );
          //var_dump( $content );
          $keywords = explode( ' ', $content );
          return array_count_values( $keywords );
      }

      public function getWordsWeight () {
          $rating = $this->getRating();
          if($rating == 0)
              return array();
          $keywords = $this->getWordsCount(  );
          $keywordsCount = count( $keywords );
          $multiply = $rating / $keywordsCount;
          return array_multiply( $keywords, $multiply );
      }

  }

  /*
    class EditableFeedItem extends FeedItem {

    } */
?>

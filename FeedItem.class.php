<?php

  require_once ('functions.php');

  function Array2FeedItem ( $item ) {
      return new FeedItem( $item );
  }

  interface IFeedItem {

      public function __construct ( $data );

      public function getId ();

      public function link ();

      public function rate ( int $i );

      public function getStopWords ();

      public function getWordAndCount ();

      public function getWeight ();

      public function radio ();
  }

  class FeedItem/* extends IFeedItem */ {

      private $id;
      private $href;
      private $title;
      private $content;
      private $published;
      private $updated;
      private $rate;

      public function __construct ( $data ) {
          $this->id = $data->id;
          $this->href = $data->alternate[0]->href;
          $this->title = $data->title;
          $this->content = $data->summary->content;
          $this->content = $data->summary->content;
          $this->published = $data->published;
          $this->updated = $data->updated;
          $this->rate = 0;
          //$this->data = $data;
          //return $this->data->summary->content;
      }

      public function getId () {
          return substr( $this->id, strlen( 'tag:google.com,2005:reader/item/' ) );
          return $this->id;
      }
      public function getRate () {
          return $this->rate;
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
          return getDomain( $this->getHref() );
      }

      public function getDate () {
          return  $this->published ;
      }

      public function getIcon () {
          return 'http://' . getDomain( $this->getHref() ) . '/favicon.ico';
      }

      static $stopWords = array( );
      static $freelanceLogo = array(
          'http://www.free-lance.ru' => 'http://www.free-lance.ru/images/logo.png',
          'http://www.free-lance.ru' => 'http://www.free-lance.ru/images/logo.png',
      );
      static $words = array(
          array( 'веб-программист', 3 ),
          array( 'программист', 2 ),
          array( 'веб', 2 ),
          array( 'CMS ', 2 ),
          array( 'Joomla', 2 ),
          array( 'сайт', 1 ),
          array( 'веб-студи', 1 ),
          array( 'сайт', 1 ),
          array( 'сайт', 1 ),
          array( 'JS+CSS', 4 ),
          array( 'Карт', 5 ),
          array( 'GoogleMap', 5 ),
          array( 'YandexMap', 5 ),
          array( 'Map', 4 ),
          array( 'рерайтеры', 4 ),
          array( 'Android', 1 ),
          array( 'рерайтеры', 4 ),
          array( 'Битрикс', -5 ),
          array( 'кроссбраузер', 4 ),
          array( 'XML', 4 ),
          array( 'JavaScript', 4 ),
          array( 'JS', 4 ),
          array( 'MySQL', 2 ),
          array( 'юзабили', 4 ),
          array( 'юзабили', 4 ),
          array( 'статья', -4 ),
          array( 'статьи', -4 ),
          array( 'статей', -4 ),
          array( 'сложные сайты', 5 ),
          array( 'агрегатор', 5 ),
      );

      public function getWeight () {
          $weight = 0;
          foreach ( self::$words as $word ) {
              $weight += substr_count( $this->content, $word[0] ) * $word[1];
          }
          return $weight;
      }

      public function getItem ( $id ) {
          foreach ( $this->items as $item ) {
              if ( $item->getId() == $id )
                  return $item;
          }
          return NULL;
      }

      public function clearStopWords ( $content ) {
          return str_replace( $this->stopWords, ' ', $content );
      }

      public function getWordAndCount () {
          $array = array( );
          $content = $this->clearStopWords( $this->content );
          $title = $this->clearStopWords( $this->title );
          $contentArray = explode( ' ', $content );
          $titleArray = explode( ' ', $title );
          return $contentArray;
      }

      public function getRadio ( $name = '' ) {
          $rateBegin = -5;
          $rateEnd = 5;
          if ( !$name ) {
              $name = 'item_' . $this->getId();
          }
          $str = $this->getId() . '<br>';
          for ( $i = $rateBegin; $i <= $rateEnd; $i++ ) {
              $str .= '<a href="rate.php/rate=' . $i . '&id=' . $this->getId() . '">[' . $i . ']</a>';
          }
          return $str;
      }

      public function __toString () {
          return $this->getId();
      }

      //public function addItems ( IFeedItem ifi )
  }
?>

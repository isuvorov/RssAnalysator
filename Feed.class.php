<?php

  require_once 'interfaces.php';
  require_once 'FeedItem.class.php';

  class Feed implements IFeed {

      /**
       * @var FeedItem[string]
       */
      public $items;

      public function __construct () {
          $this->timestamp = 0; //time();
          $this->items = array( );
      }

      //public $filename;

      public function getItems () {
          return $this->items;
      }

      public function addItems ( $items ) {
          foreach ( $items as $item ) {
              $this->addItem( $item );
          }
          //$this->items = array_unique( array_merge( $this->items, $items ) );
      }

      public function addItem ( $item ) {
          $id = $item->getId();
          if ( isset( $this->items[$id] ) )
              return false;;
          $this->items[$id] = $item;
          return true;
          //$this->addItems( array( $item ) );
      }

      /**
       *
       * @param string $id
       * @return IFeedItem 
       */
      public function getItem ( $id ) {
          if ( !isset( $this->items[$id] ) )
              throw new Exception( 'ID_NOT_FOUND' );
          return $this->items[$id];
      }

      //todo возможна перетасовка ключей
      public function sort () {
          //TODO PHP53 lambda
          if ( !function_exists( "cmpIFeedItem" ) ) {

              function cmpIFeedItem ( IFeedItem $a, IFeedItem $b ) {
                  if ( $a->getWeight() == $b->getWeight() ) {
                      return 0;
                  }
                  return ($a->getWeight() < $b->getWeight()) ? 1 : -1;
              }

          }

          uasort( $this->items, "cmpIFeedItem" );
      }

      /**
       *
       * @return type 
       */
      public function getWordsWeight () {
          $array_of_array = array( );
          foreach ( $this->items as $item ) {
              /**
               * @var $item FeedItem
               */
              $array = $item->getWordsWeight( $this );
              if ( $array )
                  $array_of_array[] = $array;
          }
          return array_merge_sum( $array_of_array );
      }

      /**
       * пересчитать вес всех элементов
       * @param $feedAnalyser   IFeedAnalyser
       * 
       */
      public function countWeight ( $feedAnalyser ) {
          foreach ( $this->items as &$item ) {
              $item->countWeight( $feedAnalyser );
          }
      }

      public function clearUnratedFeedItems () {
          foreach ( $this->items as $key => $item ) {
              /**
               * @var FeedItem $item
               */
              if ( !$item->isRated() ) {
                  unset( $this->items[$key] );
              }
          }
      }

  }

?>

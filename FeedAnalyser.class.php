<?php

  //TODO in functions.php

  require_once 'interfaces.php';
  require_once 'GoogleReader.class.php';
  require_once 'FeedItem.class.php';
  require_once 'Feed.class.php';



  define( 'FEED_ANALYSER_DEFAULT_LIMIT', 3 );

  class FeedAnalyser implements IFeedAnalyser {

      var $login;
      var $pass;
      var $labels = array( ); //feeds
      var $limit = FEED_ANALYSER_DEFAULT_LIMIT;
      static $saveFilename = 'ser.txt';
      static $stopWordsFilename = 'stop_words.txt';
      static $stopWords = array( );
      static $wordsWeightFilename = 'words_weight.txt';
      static $wordsWeight = array( );

      //static $wordsWeightHash = null;

      static function getStopWords () {
          return self::$stopWords;
      }

      static function getWordsWeight () {
          return self::$wordsWeight;
      }

      function __construct ( $login, $pass ) {
          $this->login = $login;
          $this->pass = $pass;
      }

      function save () {
          if ( self::$wordsWeight !== null )
              file_put_contents( self::$wordsWeightFilename, json_encode( self::$wordsWeight ) );
          if ( self::$stopWords !== null )
              file_put_contents( self::$stopWordsFilename, json_encode( self::$stopWords ) );
          if ( $this->labels !== null )
              file_put_contents( self::$saveFilename, serialize( $this->labels ) );
      }

      function load () {
          self::$stopWords = json_decode( file_get_contents( self::$stopWordsFilename ) );

          self::$wordsWeight = object2array( json_decode( file_get_contents( self::$wordsWeightFilename ) ) );

          $this->labels = unserialize( file_get_contents( self::$saveFilename ) );
      }

      /*
        static function countWordsWeightHash () {
        $weightSum = 0;
        foreach ( self::$wordsWeight as $weight ) {
        $weightSum += $weight;
        }
        } */

      //FIX todo
      function getLables () {
          return array( 'RusFreelance', 'EngFreelance' );
      }

      //TODO get sub dir 
      //FIX
      function getCashedLabels () {
          return array_keys( $this->labels );
          $location = 'data';
          $files = scandir( $location );
          $cashed = array( );
          foreach ( $files as $file ) {
              if ( $file == '.' || $file == '..' )
                  continue;
              $loc = $location . '/' . $file;
              if ( is_dir( $loc ) )
                  $cashed[] = $file;
          }
          return $cashed;
      }

      /**
       *
       * @param string $label
       * @throws FEED_NOT_FOUND
       * @return IFeed 
       */
      function getFeed ( $label ) {
          //TODO file_get_content
          if ( isset( $this->labels[$label] ) )
              return $this->labels[$label];
          throw new Exception( 'FEED_NOT_FOUND' );
      }

      //TODO setLimit or setLimitFeedItems
      function setLimitFeedItems ( $limit ) {
          $this->limit = $limit;
      }

      function rateFeedItems ( $label, $id_rating_array ) {
          
      }

      /**
       * @static
       * @param [words=>weight,..] $wordsWeight
       */
      static public function applyWordsWeight ( $wordsWeight ) {
          /* var_dump( self::$wordsWeight );
            var_dump( $wordsWeight ); */
          foreach ( $wordsWeight as $key => $val ) {
              if ( in_array( $key, self::$stopWords ) )
                  continue;
              self::$wordsWeight[$key] += $val;
          }
      }

      /**
       * @todo if return true
       * @param type $label
       * @param type $id
       * @param type $rating
       * @throws LABEL_NOT_FOUND, ID_NOT_FOUND
       * @return bool true (reRating) означает, что для достоверного отображения self::wordsWeight необходимо сделать self::recountWordsWeight
       */
      function rateFeedItem ( $label, $id, $rating ) {
          $feed = $this->getFeed( $label );
          $feedItem = $feed->getItem( $id );
          $ratingBefore = $feedItem->getRating();
          //var_dump($ratingBefore);
          //var_dump($rating);
          
          if ( $ratingBefore == $rating )
              return false;
          $feedItem->setRating( $rating );
          $wordsWeight = $feedItem->getWordsWeight();
          if ( $ratingBefore == 0 ) {
              $this->applyWordsWeight( $wordsWeight );
              return false;
          }
          return true;
      }

      /**
       * @todo pattern for new Feed
       * @todo pattern for new FeedItem
       * @param string $label 
       */
      function cashLabel ( $label ) {
          if ( isset( $this->labels[$label] ) )
              $feed = $this->labels[$label];
          else
              $feed = new Feed( );

          $gr = new GoogleReader( $this->login, $this->pass );
          $items = $gr->getItems( $label, 0, $this->limit );
          if ( count( $items ) != 0 ) {
              $feedItems = array( );
              foreach ( $items as $item ) {
                  $param = JsonObj2FeedItemArray( $item );
                  $feedItems[] = new FeedItem( $param );
              }
              $feed->addItems( $feedItems );
              $this->labels[$label] = $feed;
          }
      }

      /**
       * 
       * 
       */
      function recountWordsWeight () {
          $array_words_weight = array( );
          foreach ( $this->labels as $feed ) {
              /**
               * @var $feed Feed
               */
              $array_words_weight[] = $feed->getWordsWeight();
          }
          var_dump( $array_words_weight );
          self::$wordsWeight = array( );
          $this->applyWordsWeight( array_merge_sum( $array_words_weight ) );
      }

      /**
       * @param string_or_null $label  label or null for all labels
       */
      function clearUnratedFeedItems ( $label = null ) {
          if ( $label === null ) {
              foreach ( $this->labels as $feed ) {
                  $feed->clearUnratedFeedItems();
              }
          } else {
              $this->getFeed( $label )->clearUnratedFeedItems();
          }
      }

      /**
       * @param string_or_null $label label or null for all labels
       */
      function clearFeed ( $label ) {

          if ( $label === null ) {
              foreach ( $this->labels as $feed ) {
                  $feed->clearUnratedFeedItems();
              }
          } else {
              $this->getFeed( $label )->clearUnratedFeedItems();
          }
          //unset( $this->getFeed($label) );
          unset( $this->labels[$label] );
      }

      /**
       * 
       */
      function countWeight () {
          foreach ( $this->labels as &$feed ) {
              /**
               * @param Feed $feed
               */
              $feed->countWeight( $this );
          }
      }

  }

?>

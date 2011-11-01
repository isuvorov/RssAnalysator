<?php

  require_once 'interfaces.php';
  require_once 'FeedItem.class.php';
  define( "GOOGLE_AUTH_URL", 'https://www.google.com/accounts/ClientLogin' );
  /*
    interface IGoogleReader {

    public function __construct ( $username, $password );

    public function getAuth ();

    static function getAuthData ( $username, $password );

    public function login ();

    public function getData ( $url );

    static public function getUrl ( $label, $timestamp = 0, $continuation = NULL, $userId = NULL, $json = true );

    public function getResponse ( $label, $timestamp = 0, $continuation = NULL, $userId = NULL, $json = true );

    public function getItems ( $label, $startTimestamp = 0, $limit = 2000 );
    } */

  class GoogleReader implements IGoogleReader {

      private $_username;
      private $_password;
      private $_auth;

      public function __construct ( $username, $password ) {
          $this->_username = $username;
          $this->_password = $password;
      }

      public function getAuth () {
          if ( $this->_auth === NULL )
              $this->login();
          return $this->_auth;
      }

      static $authUrl = GOOGLE_AUTH_URL;

      static function getAuthData ( $username, $password ) {
          $loginData = array( );
          $loginData['service'] = 'reader';
          $loginData['Email'] = $username;
          $loginData['Passwd'] = $password;
          $loginData['accountType'] = 'GOOGLE';

          $ch = curl_init();
          curl_setopt( $ch, CURLOPT_URL, self::$authUrl );
          //curl_setopt( $ch, CURLOPT_USERAGENT, $agent );
          curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
          curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
          curl_setopt( $ch, CURLOPT_POST, 1 );
          curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $loginData ) );
          curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
          curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
          $data = curl_exec( $ch );

          $authData = array( );
          foreach ( explode( "\n", $data ) as $str ) {
              if ( !empty( $str ) ) {
                  $data = explode( "=", $str );
                  $authData[$data[0]] = $data[1];
              }
          }
          return $authData;
      }

      public function login () {
          $authData = self::getAuthData( $this->_username, $this->_password );
          $this->_auth = $authData['Auth'];
          return $this->_auth !== NULL;
      }

      public function getData ( $url ) {
          //FIX clear string
          //return file_get_contents( 'temp.txt' );
          $auth = $this->getAuth();
          $headers = array( "Authorization:GoogleLogin auth={$auth}" );
          $ch = curl_init();
          curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
          curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch, CURLOPT_URL, $url );
          $data = curl_exec( $ch );
          //file_put_contents( 'temp.txt', $data );
          return $data;
      }

      static public $itemPerPage = 1000;

      static public function getUrl ( $label, $timestamp = 0, $continuation = NULL, $userId = NULL, $json = true ) {
          if ( $userId === NULL )
              $userId = '-';
          if ( $json )
              $type = 'api/0/stream/contents';
          else
              $type = 'atom';
          $itemPerPage = self::$itemPerPage;
          $url .= "http://www.google.ru/reader/{$type}/user/{$userId}/label/{$label}?r=o&n={$itemPerPage}&ck=" . time();

          if ( $timestamp )
              $url .= "&ot={$timestamp}";
          if ( $continuation )
              $url .= "&c={$continuation}";
          return $url;
      }

      public function getResponse ( $label, $timestamp = 0, $continuation = NULL, $userId = NULL, $json = true ) {
          return $this->getData( self::getUrl( $label, $timestamp, $continuation, $userId, $json ) );
      }

      public function getItems ( $label, $startTimestamp = 0, $limit = 2000 ) {
          $items = array( );
          $continuation = 0;
          $empty = false;
          while ( !$empty && $limit > 0 ) {
              $json = $this->getResponse( $label, $startTimestamp, $continuation );
              $continuation = $obj->continuation;
              $obj = json_decode( $json );
              //TODO analyse $obj
              //var_dump($obj);
              $empty = count( $obj->items ) == 0;
              $limit -= count( $obj->items );
              $items = array_merge( $items, $obj->items );
          }
          return $items;
      }

  }

  //GoogleReader ITEM to FeedItem
  //FIX stdClass or not
  function JsonObj2FeedItemArray ( /* stdClass */ $item ) {

      $param = array( );
      $param['id'] = $item->id;
      $param['href'] = $item->alternate[0]->href;
      $param['title'] = $item->title;
      $param['content'] = $item->summary->content;
      $param['published'] = $item->published;
      $param['updated'] = $item->updated;
      $param['rating'] = 0;
      return $param;
  }

  //TODO Когда-нибудь в будущем - Для того чтобы скачивать фиды больше 2000 без таймлимита
  interface ITaskableGoogleReader extends IGoogleReader {

      public function isTask ();

      public function clearTask ();

      public function setTask ( $task );

      public function doTask ();
  }

  class TaskableGoogleReader extends GoogleReader implements ITaskableGoogleReader {

      private $task;

      public function isTask () {
          return $this->task !== null;
      }

      public function clearTask () {
          $this->task = null;
      }

      public function setTask ( $task ) {
          $this->task = $task;
      }

      public function doTask () {
          $task = $this->task;
          $json = $this->getResponse( $task['label'], $task['startTimestamp'], $task['continuation'] );
          $obj = json_decode( $json );
          $task['continuation'] = $obj->continuation;
          $task['limit'] -= count( $obj->items );
          $this->items = array_merge( $this->items, $obj->items );
          if ( count( $obj->items ) != 0 && $task['limit'] > 0 )
              $this->setTask( $this->task );
          else
              $this->clearTask();
      }

  }

?>
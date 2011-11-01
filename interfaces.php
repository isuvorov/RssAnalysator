<?php

  //класс для загрузки рсс-фидов с гугл ридр, получает данные в джейсоне
  interface IGoogleReader {

      public function __construct ( $username, $password );

      static public function getAuthData ( $username, $password );

      static public function getUrl ( $label, $timestamp = 0, $continuation = NULL, $userId = NULL, $json = true );

      public function getAuth ();

      public function login ();

      public function getData ( $url );

      public function getResponse ( $label, $timestamp = 0, $continuation = NULL, $userId = NULL, $json = true );

      public function getItems ( $label, $startTimestamp = 0, $limit = 2000 );
  }

  //Айтем - нужен чтобы отображать данные рсс
  interface IFeedItem {

      public function __construct ( array $param );

      public function getId ();

      public function getHref ();

      public function getTitle ();

      public function getContent ();

      public function getSite ();

      public function getIcon ();

      public function getDate ();

      //  Возможно здесь должно быть
      //  } interface IRatedFeedItem extend IFeedItem {


      public function getRating ();

      public function isRated ();

      /**
       * просто установить рейтинг
       * @param int $rating
       */
      public function setRating ( $rating );

      public function getWeight ();

      /**
       * посчитать вес записи
       * @param IFeedAnalyser $feedAnalyser 
       */
      public function countWeight ( $feedAnalyser );

      /**
       * посчитывает изменение $feedAnalyser::wordWeight в зависимости от рейтинга 
       * @param IFeedAnalyser $feedAnalyser для того чтобы осуществить обратную связь ввиде изменения $feedAnalyser::wordWeight
       */
      public function getWordsCount ();

      public function getWordsWeight ();
  }

  //Фид -коллекция айтемов
  interface IFeed {

      //Взять все айтемы для чтения
      public function getItems ();

      //Взять 1 айтем для чтения
      /**
       * @return IFeedItem
       */
      public function getItem ( $id );

      /**
       * добавить группу айтемов
       * @param array(IFeedItem) $item
       */
      public function addItems ( $items );

      /**
       * добавить айтем
       * @param IFeedItem $item
       */
      public function addItem ( $item );

      //  Возможно здесь должно быть
      //  } interface IRatedFeed extend IFeed {

      /**
       * пересчитать вес всех итемов
       */
      public function countWeight ( $feedAnalyser );

      /**
       * @param IFeedAnalyser $feedAnalyser для того чтобы осуществить обратную связь ввиде изменения $feedAnalyser::wordWeight и воспользоваться $feedAnalyser::stopWords
       * @return array $wordWeight изменения в массив весов слов
       */
      public function getWordsWeight ();

      /**
       * отсортировать по getWeight()
       */
      public function sort ();

      public function clearUnratedFeedItems ();
  }

  //ообертка на фиды - позволяет их сохранять загружать использовать лейблы
  interface IFeedAnalyser {

      function __construct ( $login, $pass );

      //Получить все доступные лейблы на сервере
      function getLables ();

      //Получить все доступные лейблы скаченные
      //function getCashedData ();
      function getCashedLabels ();

      /**
       *
       * @param string $label
       * @return IFeed 
       */
      function getFeed ( $label );

      static function getStopWords ();

      static function getWordsWeight ();

      /**
       * может и не нужно
       */
      function setLimitFeedItems ( $limit );

      //отрейтинговать 
      function rateFeedItem ( $label, $id, $rating );
      /* {
        $labels[$label]->rateItem($id, $rating, &$this);
        $labels[$label]->countWeight(&$this);

        } */

      /*
       * переподсчет веса слов в соотвествии с отрейтингованными
       */

      function recountWordsWeight ();
      /*
       * foreach $lables
       *    foreach $feedItem
       *        
       * 
       */

      /**
       * Связаться с сервером и обновить $label
       */
      function cashLabel ( $label );

      /**
       * очистить все записи кроме отрейтингованных
       * @param string $label
       */
      function clearUnratedFeedItems ( $label );

      /**
       * очистить все записи
       * @param string $label
       */
      function clearFeed ( $label );

      function save ();

      function load ();
  }

?>

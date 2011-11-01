<?php

  function cmp ( $a, $b ) {
      return strcmp( $a["fruit"], $b["fruit"] );
  }

  $fruits['lemons']["fruit"] = "lemons";
  $fruits['apples']["fruit"] = "apples";
  $fruits['grapes']["fruit"] = "grapes";

  var_dump($fruits);
  uasort( $fruits, "cmp" );

  var_dump($fruits);

  class B {

      public $array;

      function __construct ( $array ) {
          $this->array = $array;
      }

      public function display () {
          var_dump( $this->array );
      }

  }

  class A {

      var $b;

      function __construct ( $b ) {
          $this->b = $b;
      }

      public function display () {
          var_dump( $this->b );
      }

  }

  $b = new B( array( 1, 2, 3, 4, 5 ) );

  $a = new A( $b );
  var_dump( $a );
  $b->array[] = 6;
  var_dump( $a );
?>
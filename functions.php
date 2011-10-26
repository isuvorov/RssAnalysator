<?php

  function getDomain ( $url ) {
      $nowww = ereg_replace( 'www\.', '', $url );
      $domain = parse_url( $nowww );
      if ( !empty( $domain["host"] ) ) {
          return $domain["host"];
      } else {
          return $domain["path"];
      }
  }

  function GetShot ( $url, $msie ) {
      $host = "ipinfo.info";
      $fp = fsockopen( $host, 80, $errno, $errstr, 30 ); //Коннектимся к сайту №1
      if ( $fp ) {
          $data = array( );
          $temp = array( ); //Формируем данные для POST запроса к скрипту index.php
          $data[] = "browser";
          $data[] = $msie;
          $data[] = "url";
          $data[] = "http://" . $url;
          $data[] = "go";
          $data[] = "Render";
          for ( $ii = 0; $ii <= count( $data ); $ii++ ) {
              $name = $data[$ii];
              $value = $data[$ii + 1];
              $ii++;
              $temp[] = urlencode( $name ) . "=" . urlencode( $value );
          }
          $PostData = implode( "&", $temp );
          $script_adress = "/netrenderer/index.php"; //Адрес скрипта на сайте №1
//Отсылаем запрос
          fputs( $fp, "POST $script_adress HTTP/1.1\r\n" );
          fputs( $fp, "Host: $host\r\n" );
          fputs( $fp, "Referer: http://" . $host . $script_adress . "\r\n" );
          fputs( $fp, "Content-Type: application/x-www-form-urlencoded\r\n" );
          fputs( $fp, "Content-Length: " . strlen( $PostData ) . "\r\n" );
          fputs( $fp, "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7" );
          fputs( $fp, "Keep-Alive: 300" );
          fputs( $fp, "Connection: keep-alive" );
          fputs( $fp, "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.6) Gecko/20060728 Firefox/1.5.0.6\r\n\r\n" );
          fputs( $fp, $PostData ); //Считываем ответ. В ответе будет содержаться страница со скриншотом с сайта №2
          $con = '';
          while ( !feof( $fp ) ) {
              $con .= fgets( $fp, 200 );
          }
          fclose( $fp );  //Выдираем (ищем, парсим) адрес картинки-скриншота
          preg_match( "/http:\/\/renderer\.geotek\.de\/image\.php\?imgid=(.*?)\&browser=" . $msie . "/is", $con, $matches );
          $img = file_get_contents( $matches[0] ); //Скачиваем скриншот. Формат PNG
//Убеждаемся, что картинка скачана, т.е. имеет не нулевой размер
          if ( strlen( $img ) > 0 ) {
//Сохраняем картинку на диск
              $ff = fopen( "screenshot.png", "wb" );
              fputs( $ff, $img );
              fclose( $ff );
              unset( $con, $img );
              return true; //Возвращаем TRUE - скриншот получен
          } else
              return false; //Если картинка не скачалась, возвращаем FALSE
      }
      else
          return false; //Если сайт №1 не открылся, возвращаем FALSE
  }
?>

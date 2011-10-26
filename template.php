<?php

  $data['title'] = 'RSS-анализатор';
  $data['slogan'] = 'сервис авто-анализиза Freelance предложений';
  $data['menu'] = array(
      'get.php' => 'Обновить Базу',
      'view.php?label=RusFreelance' => 'Предложения Ru',
      'view.php?label=EngFreelance' => 'Предложения Eng',
      'weight.php' => 'Вес слов'
  );
  if ( $data['sidebar'] === NULL )
      $data['sidebar'] = array( );
  require_once ('../templates/Template.class.php');
  $template = new Template( $data );
  require_once ('../templates/orangemint/layout.php');
?>

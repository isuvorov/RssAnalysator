<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript" src="underscore.js"></script>
        <script type="text/javascript" src="backbone.js"></script>
    </head>
    <body>
        <div id="block" class="block">
        </div>

        <!-- Блок ввода имени пользователя -->
        <script type="text/template" id="start"> 
            <div class="start"> 
                <div class="userplace">
                    <label for="username">Имя пользователя: </label>
                    <input type="text" id="username" />
                </div>
                <div class="buttonplace">
                    <input type="button" value="Проверить" />
                </div>
            </div>
        </script>

        <!-- Блок ошибки -->
        <script type="text/template" id="error">
            <div class="error">
                Ошибка. Пользователь  <%= username %> не найден.
                <a href="#!/">Go back</a>
            </div>
        </script>

        <!-- Блок удачи -->
        <script type="text/template" id="success">
            <div class="success">
                Пользователь <%= username %> найден.
                <a href="#!/">Go back</a>
            </div>        
        </script>
    </body>
</html>


http://www.fyneworks.com/jquery/star-rating/#tab-Testing
<script>
      
<?php
  /*
    require_once ('Feed.class.php');
    require_once ('FeedItem.class.php');


    $rowPerPage = 3;

    $label = 'RusFreelance';
    $filename = getFilename( $label );
    $feed = getFeed( $filename );
    $feed->sort();
    //var_dump( $feed->items[0]->getWordAndCount() );
    $begin = $_GET['page'] * $rowPerPage;
    $end = ($_GET['page'] + 1) * $rowPerPage; //$item
    for ( $i = $begin; $i < $end; $i++ ) {
    $item = $feed->items[$i];
    if ( $item === null )
    break;
    $post = array();
    $post['href'] = $item->getHref();
    $post['weight'] = $item->getWeight();
    $post['rate'] = $item->getRate();
    $post['title'] = $item->getTitle();

    $post['date'] = date( "d/m/Y H:i:s", $item->getDate() );
    $post['content'] = $item->getContent();
    $post['icon'] = $item->getIcon();
    $post['site'] = $item->getSite();
    $data['content'][] = $post;
    }
    $array = json_encode($data['content']); ?>
    var ar = <?=$array?>;
    <?php
   */
?>
    
    /*
      
    $(function () {

        var AppState = Backbone.Model.extend({
            defaults: {
                username: "",
                state: "start"
            }
        });

        var appState = new AppState();


       
    
        //console.log(MyFeedList);

        var UserNameModel = Backbone.Model.extend({ // Модель пользователя
            defaults: {
                "Name": ""
            }
        });

        var Family = Backbone.Collection.extend({ // Коллекция пользователей

            model: UserNameModel,

            checkUser: function (username) { // Проверка пользователя
                var findResult = this.find(function (user) { return user.get("Name") == username })
                return findResult != null;
            }

        });

        var MyFamily = new Family([ // Моя семья
            {Name: "Саша" },
            { Name: "Юля" },
            { Name: "Елизар" },

        ]);



        var Controller = Backbone.Router.extend({
            routes: {
                "": "start", // Пустой hash-тэг
                "!/": "start", // Начальная страница
                "!/success": "success", // Блок удачи
                "!/error": "error" // Блок ошибки
            },

            start: function () {
                appState.set({ state: "start" });
            },

            success: function () {
                appState.set({ state: "success" });
            },

            error: function () {
                appState.set({ state: "error" });
            }
        });

        var controller = new Controller(); // Создаём контроллер


        var Block = Backbone.View.extend({
            el: $("#block"), // DOM элемент widget'а

            templates: { // Шаблоны на разное состояние
                "start": _.template($('#start').html()),
                "success": _.template($('#success').html()),
                "error": _.template($('#error').html())
            },

            events: {
                "click input:button": "check" // Обработчик клика на кнопке "Проверить"
            },

            initialize: function () { // Подписка на событие модели
                this.model.bind('change', this.render, this);
            },

            check: function () {
                var username = this.el.find("input:text").val();
                var find = MyFamily.checkUser(username); // Проверка имени пользователя
                appState.set({ // Сохранение имени пользователя и состояния
                    "state": find ? "success" : "error",
                    "username": username
                });
            },

            render: function () {
                var state = this.model.get("state");
                $(this.el).html(this.templates[state](this.model.toJSON()));
                return this;
            }
        });

        var block = new Block({ model: appState }); // создадим объект

        appState.trigger("change"); // Вызовем событие change у модели

        appState.bind("change:state", function () { // подписка на смену состояния для контроллера
            var state = this.get("state");
            if (state == "start")
                controller.navigate("!/", false); // false потому, что нам не надо 
            // вызывать обработчик у Router
            else
                controller.navigate("!/" + state, false);
        });

        Backbone.history.start();  // Запускаем HTML5 History push    


    });*/
    
</script>





<!DOCTYPE html>
<html>

    <head>
        <title>Backbone Demo: Todos</title>
        <link href="http://documentcloud.github.com/backbone/examples/todos/todos.css" media="all" rel="stylesheet" type="text/css"/>
        <script src="http://documentcloud.github.com/backbone/test/vendor/json2.js"></script>
        <script src="http://documentcloud.github.com/backbone/test/vendor/jquery-1.5.js"></script>
        <script src="http://documentcloud.github.com/backbone/test/vendor/underscore-1.1.6.js"></script>
        <script src="http://documentcloud.github.com/backbone/backbone.js"></script>
        <script src="http://documentcloud.github.com/backbone/examples/backbone-localstorage.js"></script>
        <script src="todos.js"></script>
    </head>

    <body>

        <!-- Todo App Interface -->

        <div id="todoapp">

            <div class="title">
                <h1>Todos</h1>
            </div>

            <div class="content">

                <div id="create-todo">
                    <input id="new-todo" placeholder="What needs to be done?" type="text" />
                    <span class="ui-tooltip-top" style="display:none;">Press Enter to save this task</span>
                </div>

                <div id="todos">
                    <ul id="todo-list"></ul>
                </div>

                <div id="todo-stats"></div>

            </div>

        </div>



        <!-- Templates -->

        <script type="text/template" id="item-template">
            <!--<div class="todo <%= done ? 'done' : '' %>">
                <div class="display">
                    <input class="check" type="checkbox" <%= done ? 'checked="checked"' : '' %> />
                           <div class="todo-text">empty text</div>
                    <span class="todo-destroy"></span>
                </div>
                <div class="edit">
                    <input class="todo-input" type="text" value="" />
                </div>
            </div>-->
            <div class="feed">
                <a href="<%= href %>">
                    <div class="title"><%= title %></div>
                </a>
                <div class="site"><img src="<%= icon %>" /><%= site %></div>
                <div class="weight">w <%= weight %></div>
                <div class="date"><%= date %></div>
                <div class="content"><%= content %></div>
                <div class="rate"><%= rate %></div> 
                <div class="changeRate">
                    <?php
                      for($i = -5; $i <= 5; $i++){
                          //if(!$i)
                            //  continue;
                        echo "[<span class=\"rateNum rateNum$i\" style=\"color:red;cursor:pointer;\">{$i}</span>]";
                        
                      }
                    ?>
                </div>
            </div>
        </script>

        <script type="text/template" id="stats-template">
            <% if (total) { %>
            <span class="todo-count">
                <span class="number"><%= remaining %></span>
                <span class="word"><%= remaining == 1 ? 'item' : 'items' %></span> left.
            </span>
            <% } %>
            <% if (done) { %>
            <span class="todo-clear">
                <a href="#">
                    Clear <span class="number-done"><%= done %></span>
                    completed <span class="word-done"><%= done == 1 ? 'item' : 'items' %></span>
                </a>
            </span>
            <% } %>
        </script>

    </body>

</html>

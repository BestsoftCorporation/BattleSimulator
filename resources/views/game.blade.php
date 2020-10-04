<!DOCTYPE html>
<html lang="en">

<head>
  <title>Battle Simulator</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>

<body>

  <div class="container">
    <h2>Battle Simulator | Create Game</h2>
    <form id="gameForm" name="gameForm" action="/api/createGame">
      @csrf
      <button type="submit" class="btn btn-primary" class="btn btn-default">Create Game </button>
    </form>
    <div id="response" class="alert alert-success" role="alert">
      Create game to start!
    </div>

    <form style="display: none" id="armyForm" action="/api/Army">
      <h2>Add Army</h2>
      @csrf
      <div class="form-group">
        <label for="name">Army name:</label>
        <input type='text' name='name' id='name'>
      </div>
      <div class="form-group">
        <label for="units">Units (80-100):</label>
        <input type="number" name='units' id='units' min="80" max="100">
      </div>
      <div class="form-group">
        <label for="strategy">Strategy:</label>
        <select class="form-control" id="strategy" name="strategy">
          <option value="1">Random</option>
          <option value="2">Weakest</option>
          <option value="3">Strongest</option>
        </select>
      </div>
      <input type='hidden' name='gameID' id='gameID' value='18'>
      <button type="submit" class="btn btn-default">Create</button>
    </form>
    <button type="button" class="btn btn-primary" id="play" disabled>Play Round!</button>
    <ul id="ul" class="list-group">
    </ul>
    <p>© Battle Simulator by Marko Stojkovic </p>
  </div>


  <script>
    var active = null;
    var attacked = null;
    var mess = "";
    $("#play").click(function(e) {
      e.preventDefault();
      $.ajax({
        type: "GET",
        url: "/api/startGame",
        dataType: 'json',

        success: function(result) {
          // var json = $.parseJSON(result); // create an object with the key of the array
          // alert(json);
          //alert(result['message']);
          $("#response").html(result['message']);
          mess = result['message'];
          if (mess.includes("won")) {
            alert(mess);
          }
          if (result['message'].includes('vs')) {
            var res = result['message'].split("vs");
            active = res[0];
            attacked = res[1];
          }
          $("#armyForm").css("display", "block");
          reloadF();
        },
        error: function(result) {
          alert('error');
        }
      });
    });

    var gameID;
    $(document).ready(function() {
      $.ajax({
        type: "GET",
        url: "/api/getGame",

        success: function(data) {
          gameID = data;
          if (gameID != undefined) {
            $("#response").html("Game with id " + gameID + " created! Lets now add some armies to the game.");
            $("#armyForm").css("display", "block");
            reloadF();
          }
        }
      });
    });

    function reloadF() {
      $.ajax({
        type: "GET",
        url: "/api/gameArmy/" + gameID,

        success: function(data) {
          $("#ul").html("");
          $("#response").html("");
          var attack = "";
          var vic = "";
          jQuery.each(data, function(i, val) {
            var sign = "list-group-item-success";
            var text = "";

            if (active != null) {

              if (active == val.id) {
                text = "ATTACK";
                sign = "list-group-item-info";
                attack = val.name + " ATTACK ";
              }
              if (attacked == val.id) {
                text = "ATTACKED";
                sign = "list-group-item-warning";
                vic = val.name;

              }

            }


            if (val.units == 0) {
              $("#ul").append('<li class="list-group-item list-group-item-danger"><b>' + val.name + '</b>   <span style="color:red">units left:' + val.units + '</span>   <span style="color:green">attack strategy:' + val.strategy + '</span> <b>DEAD</b></li>');
            } else {
              $("#ul").append('<li class="list-group-item ' + sign + '"><b>' + val.name + '</b>   <span style="color:red">units left:' + val.units + '</span>   <span style="color:green">attack strategy:' + val.strategy + '</span> <b>' + text + '</b></li>');
            }
            if (i >= 4) {
              document.getElementById("play").disabled = false;
            }
          });

          if (!mess.includes("won")) {
            $("#response").html(attack + vic);
          }


        }

      });
    }

    $("#gameForm").submit(function(e) {

      e.preventDefault(); // avoid to execute the actual submit of the form.

      var form = $(this);
      var url = form.attr('action');

      $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(data) {
          $("#response").html("Game with id " + data.id + " created! Lets now add some armies to the game.");
          $("#ul").css("display", "none");
          $("#armyForm").css("display", "block");
        }
      });
      $("#ul").html("");
      $.ajax({
        type: "GET",
        url: "/api/getGame",

        success: function(data) {
          gameID = data;

        }
      });
      reloadF();
    });
    $("#armyForm").submit(function(e) {

      $.ajax({
        type: "GET",
        url: "/api/getGame",

        success: function(data) {
          gameID = data;

        }
      });


      e.preventDefault(); // avoid to execute the actual submit of the form.

      var form = $(this);
      var url = form.attr('action');

      $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(data) {
          $("#response").html("Army with id " + data.id + " created! Lets now add some more armies to the game or play round.");
          $("#ul").css("display", "block");
          $("#armyForm").css("display", "block");
        }
      });

      reloadF();
    });
  </script>
  </script>




</body>

</html>
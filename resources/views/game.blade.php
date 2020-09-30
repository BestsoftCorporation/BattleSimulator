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
    <button type="submit" class="btn btn-default">Create</button>
  </form>
  <div id="response" class="alert alert-success" role="alert">
     Create game to start!
  </div>

  <form style="display: none"  id="armyForm" action="/api/Army">
    <h2>Add Army</h2>
    @csrf
    <div class="form-group">
        <label for="name">Army name:</label>
        <input type='text' name='name' id='name'>
    </div>
    <div class="form-group">
        <label for="units">Units (80-100):</label>
        <input type='text' name='units' id='units'>
    </div>
    <div class="form-group">
        <label for="strategy">Strategy:</label>
        <input type='text' name='strategy' id='strategy'>
    </div>
    <input type='hidden' name='gameID' id='gameID' value='18'>
    <button type="submit" class="btn btn-default">Create</button>
  </form>
  <button type="button" class="btn btn-primary" id="play" disabled>Play Round!</button>
  <ul id="ul" class="list-group">
  </ul>

</div>


<script>

$("#gameForm").submit(function(e) {

e.preventDefault(); // avoid to execute the actual submit of the form.

var form = $(this);
var url = form.attr('action');

$.ajax({
       type: "POST",
       url: url,
       data: form.serialize(), 
       success: function(data)
       {
           $("#response").html("Game with id "+data.id+" created! Lets now add some armies to the game.");
        
           $("#armyForm").css("display", "block");
       }
     });

     $.ajax({
       type: "GET",
       url: "/api/Army",
       data: form.serialize(), // serializes the form's elements.
       success: function(data)
       {
           $("#ul").html("");
           jQuery.each(data, function(i, val) {
               $("#ul").append('<li class="list-group-item"><b>'+val.name+'</b>   <span style="color:red">units left:'+val.units+'</span>   <span style="color:green">attack strategy:'+ val.strategy + '</span> </li>');
           });
          
       }
     });
});
$("#armyForm").submit(function(e) {

e.preventDefault(); // avoid to execute the actual submit of the form.

var form = $(this);
var url = form.attr('action');

$.ajax({
       type: "POST",
       url: url,
       data: form.serialize(), 
       success: function(data)
       {
           $("#response").html("Army with id "+data.id+" created! Lets now add some more armies to the game or play round.");
        
           $("#armyForm").css("display", "block");
       }
     });

     $.ajax({
       type: "GET",
       url: "/api/Army",
       data: form.serialize(), // serializes the form's elements.
       success: function(data)
       {
           $("#ul").html("");
           jQuery.each(data, function(i, val) {
               $("#ul").append('<li class="list-group-item"><b>'+val.name+'</b>   <span style="color:red">units left:'+val.units+'</span>   <span style="color:green">attack strategy:'+ val.strategy + '</span> </li>');
               if (i>=5){
                document.getElementById("play").disabled = false; 
               }
           });
          
       }
     });
});
</script>
</script>




</body>
</html>

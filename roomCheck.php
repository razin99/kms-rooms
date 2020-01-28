<head>
  <title>KMS Rooms</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/custom.css">
  <link rel="stylesheet" href="css/timetablejs.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
<?php
  $hostname = 'mysql1.gear.host';
  $username = 'kmsrooms';
  $password = 'h0m3br3w~';
  $dbname = 'kmsrooms';
  $localDate = date ("Y-m-d");
  $userDate = $_POST["checkdate"];
  $localDateTS = strtotime($localDate);
  $userDateTS = strtotime($userDate);

  try {
    $dbc = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

    $sql = $dbc->prepare("SELECT * FROM reservation WHERE date='$_POST[checkdate]'");

    if($sql->execute()) {
       $sql->setFetchMode(PDO::FETCH_ASSOC);
    }
  }
  catch(Exception $error) {
      echo '<p>', $error->getMessage(), '</p>';
  }
?>
<!-- Modal -->
  <div class="modal fade" id="details" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <!-- Content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Booking details</h4>
        </div>
        <div class="modal-body">
          <p id="modal-content"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-lg btn-block" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

<div class="jumbotron">
  <h1>Available rooms</h1>
  <p>Date: <?print $userDate?></p>
  <button onclick="goBack()" type="button" class="btn btn-primary btn-sm">Back</button>
  <script>
    function goBack() {
      window.history.back();
    }
  </script>
</div>
<!--<div class= "container-fluid-a">-->
<div class="timetable"></div>
<script src="scripts/timetable.js"></script>

<script>
console.log('modal2');
var timetable = new Timetable();
timetable.setScope(0, 23);
timetable.addLocations(['WP 1','WP 2', 'WP 3', 'WP 4', 'WP 5', 'WP 6']);
<?php while($row = $sql->fetch()) { ?>
  timetable.addEvent('<?echo $row['collegeID'];?>','WP <?echo substr($row['wp'],-1);?>',
  new Date(2015,7,17,<?echo substr($row['tstart'],0,-6);?>,<?echo substr($row['tstart'],3,-3);?>),
  new Date(2015,7,17,<?echo substr($row['tend'],0,-6);?>,<?echo substr($row['tend'],3,-3);?>), {
onClick: function(event){
  $("#modal-content").html("Name: <?echo $row['fullName']?><br>Time: <?echo substr($row['tstart'],0,-3);?> - <?echo substr($row['tend'],0,-3);?>");
  $("#details").modal();
}});
<?php } ?>
var renderer = new Timetable.Renderer(timetable);
renderer.draw('.timetable');
</script>


</body>

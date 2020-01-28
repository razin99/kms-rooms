<html>
<head>
  <title>KMS Rooms</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/custom.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>

<?php
$con = mysql_connect("mysql1.gear.host","kmsrooms","h0m3br3w~");
$localDate = date ("Y-m-d");
$userDate = $_POST["bookdate"];
$localDateTS = strtotime($localDate);
$userDateTS = strtotime($userDate);
$tStart = $_POST["tStart"];
$tEnd = $_POST["tEnd"];
$fullName = $_POST["fullName"];
$email = $_POST["email"];
$domain = $_POST["domain"];

if ($domain != "kmseremban.edu.my")
  {
    $message = "Login with kmseremban account";
    echo "<script type='text/javascript'>alert('$message');</script>";
    header('Refresh: 3; URL=http://kms-rooms.gearhostpreview.com/forms.html');
    die();
  }

if ($localDateTS > $userDateTS)//Date check
  {
    header('Refresh: 1; URL=http://kms-rooms.gearhostpreview.com/error/date.html');
    die();
  }

$dateDiff = $userDateTS - $localDateTS;
if ($dateDiff > 604800)//Force max 7 days advanced booking
{
  header('Refresh: 1; URL=http://kms-rooms.gearhostpreview.com/error/week.html');
  die();
}

if ($tStart >= $tEnd)//time validation
{
  header('Refresh: 1; URL=http://kms-rooms.gearhostpreview.com/error/time.html');
  die();
}

if (!$con)//Data submit
  {
  die('Could not connect: ' . mysql_error());
  }

$wp = $_POST["WP"];
if ($wp == "null")//wp validation
  {
    header('Refresh: 1; URL=http://kms-rooms.gearhostpreview.com/error/wp.html');
    die();
  }

mysql_select_db("kmsrooms", $con);

$nullCheck = mysql_query("SELECT * FROM  reservation WHERE date='$_POST[bookdate]' AND wp='$_POST[WP]'");
$rows0 = mysql_num_rows($nullCheck);
$collisionCheck = mysql_query("SELECT * FROM reservation WHERE date='$_POST[bookdate]'
  AND wp='$_POST[WP]'
  AND tend < '$_POST[tStart]' OR tstart > '$_POST[tEnd]'");
$rows = mysql_num_rows($collisionCheck);
$sql="INSERT INTO reservation (collegeID,wp,date,tstart,tend,fullName,email)
VALUES
('$_POST[collegeID]','$_POST[WP]','$_POST[bookdate]','$_POST[tStart]','$_POST[tEnd]','$_POST[fullName]','$_POST[email]')";
if($rows0 == 0){
  //proceed book
  if (!mysql_query($sql,$con))
    {
    die('Error: ' . mysql_error());
    }
  mysql_close($con);
} elseif($rows == 0){
  //no book + redirect
  header('Refresh: 1; URL=http://kms-rooms.gearhostpreview.com/error/overlap.html');
  die();
} else {
  //proceed book
  if (!mysql_query($sql,$con))
    {
    die('Error: ' . mysql_error());
    }
  mysql_close($con);
}
?>
<div class="jumbotron">
  <h1>Room booked</h1>
  <p></p>
</div>
<div class="row justify-content-center">
  <div class="col-1"></div>
  <div class="col-10">
    <a class="btn btn-primary btn-lg btn-block" href="default.html" role="button">Return home</a><br>
  </div>
  <div class="col-1"></div>
</div>

</body>
</html>

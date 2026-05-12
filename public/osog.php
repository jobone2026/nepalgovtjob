<?php
declare(strict_types=1);

$defaultTimeZone = 'Atlantic/Azores';

$timeZones = [
    'Asia/Kolkata',
    'America/New_York',
    'Atlantic/Azores',
    'Europe/London',
    'UTC'
];

$latitude = "15.08738";
$longitude = "76.547741";
$timeZone = $defaultTimeZone;

$allPorts = '9093,9094,9095,9096,9097,9098,9099';
$ip = '103.138.197.90';

$successCount = 0;
$showSuccess = false;
$sentLogs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $latitude = $_POST['latitude'] ?? $latitude;
    $longitude = $_POST['longitude'] ?? $longitude;
    $timeZone = $_POST['timeZone'] ?? $defaultTimeZone;
    $desiredValues = $_POST['desiredValues'] ?? '';

    $imeiList = explode(',', $desiredValues);

    date_default_timezone_set($timeZone);
    $currentTime = date('His', strtotime('+1 hour'));

    $ports = explode(',', $allPorts);
    $openPorts = [];

    foreach ($ports as $port) {

        $socket = @fsockopen($ip, (int)$port, $errno, $errstr, 1);

        if ($socket) {
            $openPorts[] = $port;
            fclose($socket);
        }
    }

    foreach ($imeiList as $imei) {

        $imei = trim($imei);

        if ($imei === '') {
            continue;
        }

        $batteryVoltage = number_format(
            3.0 + (mt_rand() / mt_getrandmax()) * (4.2 - 3.0),
            2
        );

        $data = [
            '$TEL124,Teltonik,032709,NR,2,H,' . $imei . ',,1,' .
            date('dmY') . ',' .
            $currentTime . ',' .
            $latitude . ',N,' .
            $longitude . ',E,0.0,266.00,18,485.0,' .
            $batteryVoltage .
            ',0.80,Airtel,1,1,25.4,4.1,0,,23,404,45'
        ];

        $jsonData = json_encode($data);

        foreach ($openPorts as $port) {

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

            if ($socket !== false && @socket_connect($socket, $ip, (int)$port)) {

                socket_write($socket, $jsonData, strlen($jsonData));
                socket_close($socket);

                $successCount++;

                $sentLogs[] = [
                    'imei' => $imei,
                    'port' => $port
                ];
            }
        }
    }

    $showSuccess = true;
}
?>

<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>IMEI Sender</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:linear-gradient(135deg,#1d2671,#c33764);
min-height:100vh;
display:flex;
align-items:center;
justify-content:center;
}

.card{
border-radius:15px;
width:100%;
max-width:420px;
}

.loading{
display:none;
}

.small-table td{
font-size:13px;
padding:4px;
}

</style>

<script>

function showLoading(){
document.getElementById("loading").style.display="block";
}

function autoClear(){
setTimeout(function(){
window.location.href=window.location.pathname;
},4000);
}

window.onload=function(){
<?php if($showSuccess){ echo "autoClear();"; } ?>
};

function setLocation(){

var location=document.getElementById("location").value;

var lat=document.getElementById("latitude");
var longi=document.getElementById("longitude");

if(location==="Sandur"){
lat.value="15.0881259";
longi.value="76.543628";
}

else if(location==="Thakur"){
lat.value="15.317533";
longi.value="76.235290";
}

}

</script>

</head>

<body>

<div class="card shadow p-3">

<h5 class="text-center text-white bg-primary p-2 rounded mb-3">

🚀 IMEI Sender

</h5>

<?php if($showSuccess): ?>

<div class="alert alert-success text-center p-2">

✔ Sent Successfully <br>

<small>Total Sent : <?php echo $successCount; ?></small>

</div>

<?php if(!empty($sentLogs)): ?>

<div class="table-responsive">

<table class="table table-sm table-bordered small-table">

<thead class="table-light">

<tr>
<th>IMEI</th>
<th>Port</th>
</tr>

</thead>

<tbody>

<?php foreach($sentLogs as $log): ?>

<tr>

<td><?php echo htmlspecialchars($log['imei']); ?></td>

<td>

<span class="badge bg-success">

<?php echo htmlspecialchars($log['port']); ?>

</span>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php endif; ?>

<?php endif; ?>

<form method="post" onsubmit="showLoading()">

<div class="mb-2">

<label class="form-label small">IMEI</label>

<input type="text" name="desiredValues" class="form-control form-control-sm" required>

</div>

<div class="mb-2">

<label class="form-label small">Location</label>

<select id="location" class="form-select form-select-sm" onchange="setLocation()">

<option value="">Choose</option>

<option value="Sandur">Sandur</option>

<option value="Thakur">Thakur</option>

</select>

</div>

<div class="mb-2">

<label class="form-label small">Latitude</label>

<input type="text" id="latitude" name="latitude"
value="<?php echo htmlspecialchars($latitude); ?>"
class="form-control form-control-sm">

</div>

<div class="mb-2">

<label class="form-label small">Longitude</label>

<input type="text" id="longitude" name="longitude"
value="<?php echo htmlspecialchars($longitude); ?>"
class="form-control form-control-sm">

</div>

<div class="mb-2">

<label class="form-label small">Time Zone</label>

<select name="timeZone" class="form-select form-select-sm">

<?php foreach($timeZones as $tz): ?>

<option value="<?php echo $tz; ?>" <?php if($tz==$timeZone) echo "selected"; ?>>

<?php echo $tz; ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="d-grid mt-2">

<button type="submit" class="btn btn-primary btn-sm">

Send

</button>

</div>

<div class="text-center mt-2 loading" id="loading">

<div class="spinner-border spinner-border-sm text-primary"></div>

<small> Sending...</small>

</div>

</form>

</div>

</body>

</html>
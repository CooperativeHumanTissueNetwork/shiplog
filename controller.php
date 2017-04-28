<?php
// Turn off all error reporting
error_reporting(0);
// Report all PHP errors
//error_reporting(-1);
include 'getters.php';


$myfile = fopen("/root/shiplog/log/logfile_" . date('YmdHi') . ".log", "w") or die("Unable to open file!");
$txt = "Data Push Began: " . date('Y-m-d H:i') . "\n";
$txt .= "------------------------------------------\n";
$txt .= "| 1) GET RECORDS NEEDING UPLOAD          |\n";
$txt .= "| 2) CYCLE THROUGH THE RECORDS           |\n";
$txt .= "| 3) SEND THE RECORD TO CHTN.APP         |\n";
$txt .= "| 4) CHECK RESPONSE FROM CHTN.APP        |\n";
$txt .= "| 5) IF GOOD MARK RECORDS UPLOADED       |\n";
$txt .= "------------------------------------------\n";
fwrite($myfile, $txt);

$dSets = new datasets();
$rtnVal = $dSets->getreconciledrecords();
$data = json_decode($rtnVal, true);

for ($i = 0, $l = count($data['DATA']); $i < $l; $i++) { 
    fwrite($myfile, $data['DATA'][$i]['assignedReq'] . "  " . $data['DATA'][$i]['uploadNote'] . "\n");
}
fclose($myfile);
exit;


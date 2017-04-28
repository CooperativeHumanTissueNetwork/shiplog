<?php
// Turn off all error reporting
error_reporting(0);
// Report all PHP errors
//error_reporting(-1);
include 'getters.php';
$myfile = fopen("/root/shiplog/log/logfile_" . date('YmdHi') . ".log", "w") or die("Unable to open file!");
$txt = "Data Push Began: " . date('Y-m-d H:i') . "\n------------------------------------------\n| 1) GET RECORDS NEEDING UPLOAD          |\n| 2) CYCLE THROUGH THE RECORDS           |\n| 3) SEND THE RECORD TO CHTN.APP         |\n| 4) CHECK RESPONSE FROM CHTN.APP        |\n| 5) IF GOOD MARK RECORDS UPLOADED       |\n------------------------------------------\n";
fwrite($myfile, $txt);
$txt = "[1] RETRIEVE UPLOADABLE RECORDS\n-----------------------------------\n";
fwrite($myfile, $txt);
$dSets = new datasets();
$rtnVal = $dSets->getreconciledrecords();
$data = json_decode($rtnVal, true);
$txt = "[2-5] CYCLE RECORDS AND WRITE TO INVESTIGATOR+ AND MARK FINISHED\n-----------------------------------\n";
fwrite($myfile, $txt);
for ($i = 0, $l = count($data['DATA']); $i < $l; $i++) { 
    $payloadData = array('requestId' => $data['DATA'][$i]['assignedReq']
                    , 'shipCount' => (int)$data['DATA'][$i]['qty']
                    , 'comments' => $data['DATA'][$i]['uploadNote']
                    , 'shipDate' => $data['DATA'][$i]['shippedDate']
                    , 'preparationId' => strtolower($data['DATA'][$i]['tvcode'])
                    , 'anatomicSiteId' => $data['DATA'][$i]['siteid']
                    , 'specimenTypeId' => $data['DATA'][$i]['catid']
    );
    $jStr = json_encode($payloadData);
    echo $jStr . "\n";
    fwrite($myfile, $jStr . "\n");
    $collection = $dSets->putchtnapprecord($jStr);
    if ($collection === "{\"shiplogs\":null,\"message\":null}") { 
        $sdupdater = $dSets->markuploaded($data['DATA'][$i]['shipdocrefid']);
        fwrite($myfile, $sdupdater . "\n");
    }
}
fwrite($myfile, "\n----------------------\nFINISHED: " .  date('Y-m-d H:i'));
fclose($myfile);
exit;
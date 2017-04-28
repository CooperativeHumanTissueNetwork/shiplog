<?php

class datasets { 
    
    function getreconciledrecords() { 
        $ws = callRestAPI('GET','https://www.chtneast.org/fourapi/reconciliation/shiplogsend');
        return $ws;
    }
    
    function putchtnapprecord($thisJSON) {
        include 'auth.zck';
        $ws = callRestAPI('PUT', 'https://chtn.app.vumc.org/investigatorplus/rest/communicationlog/shiplog/' . $invplusEast,$thisJSON);
        return $ws;
    }
    
    function markuploaded($whichSD) { 
        $ws = callRestAPI('GET','https://www.chtneast.org/fourapi/reconciliation/shiplogupdate/' . $whichSD);
        return $ws;
    }
    
}


function callRestAPI($method, $url, $data = false) { 
    //echo $url;
    try {
        $ch = curl_init();
        if (FALSE === $ch) throw new Exception('failed to initialize');
        switch ($method) { 
            case "PUT":
                //curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data)));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
            break;
            case "GET":
                curl_setopt($ch, CURLOPT_GET, 1);
                break;
        } 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //$headers = array(
        //    "Authorization: Basic " . base64_encode(  $_SESSION['ssUSER'] . ":" . session_id())
        //); 
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $content = curl_exec($ch);
        if (FALSE === $content) throw new Exception(curl_error($ch), curl_errno($ch));
        return $content;
    } catch(Exception $e) {
        trigger_error(sprintf('Curl failed with error #%d: %s',$e->getCode(), $e->getMessage()),E_USER_ERROR);
    }
}




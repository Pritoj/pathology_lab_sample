<?php
function escapeArray($arr){
    if(!is_array($arr)){
        return $arr;
    }
    $retArr=array();
    foreach ($arr as $key => $value) {
        if(gettype($key)=='string'){
            $key = mysql_real_escape_string($key);
        }
        if(gettype($value)=='string'){
            $value = mysql_real_escape_string($value);
            
        }
        $retArr[$key] = $value;
    }
    return $retArr;
} 


function extractReqDataArray($respArray,$fields){
    $retArr=array();
    foreach($fields as $fi){
        if(isset($respArray[$fi])){
            $retArr[$fi]=$respArray[$fi];
        }
    }
    return $retArr;
}
function extractReqDataObj($respArray,$fields){
    $retObj=array();
    foreach($fields as $fi){
        if(isset($respArray->$fi)){
            $retObj[$fi]=$respArray->$fi;
        }
    }
    return $retObj;
}
function sendJSONResponse($status = true, $statusCode = 1, $data = NULL){
    
    echo json_encode(
        array(
            'status' => $status,
            'statusCode' => $statusCode,
            'data' => $data
        )
    );
};

function getLimitString($page, $count)
{
    if (is_numeric($page) && is_numeric($count)) {
        return "LIMIT " . (($page - 1) * $count) . ", $count";
    } else {
        return "";
    }
}
function getFilterString($filtering,$replications=array(),$skip = array()){
    //print_r($filtering);
    $filterConds = array();
    if (is_array($filtering)) {
        foreach ($filtering as $k => $s) {
            if(in_array($k,$skip)){
                continue;
                //skip this key
            }
            if(isset($replications[$k])){
                $k=$replications[$k];
            }

            if(is_numeric($s)){
                $filterConds[] = "$k = '$s'";
            }
            else if(is_array($s) && count($s)>0){
                $filterConds[] = "$k IN (".implode(",",$s).")";
            }
            else if (is_null($s)){
                $filterConds[] = "$k IS NULL";
            }
            else if (substr($k,-4)=='Slug'){
                $filterConds[] = "$k LIKE '$s'";
            }
            else{
                $filterConds[] = "$k LIKE '%$s%'";
            }
        }
    }
    if (count($filterConds) > 0) {
        $filterConds = " AND " . implode(" AND ", $filterConds);
    } else {
        $filterConds = "";
    }

    return $filterConds;
}

function getSortingString($sorting,$replications){
    $sortingConds = array();
    if (is_array($sorting)) {
        foreach ($sorting as $k => $s) {
            if(isset($replications[$k])){
                $k=$replications[$k];
            }
            $k = mysql_real_escape_string($k);
            $s = mysql_real_escape_string($s);
            $sortingConds[] = "$k $s";
        }
    }
    if (count($sortingConds) > 0) {
        $sortingConds = "ORDER BY " . implode(",", $sortingConds);
    } else {
        $sortingConds = "";
    }
    return $sortingConds;
}


function getRangeCondString($rangeFilter,$replications){
    $rangeConds = array();
    if (is_array($rangeFilter)) {
        foreach ($rangeFilter as $k => $s) {
            if(isset($replications[$k])){
                $k=$replications[$k];
            }
            if(isset($s['min']) && isset($s['max'])){
                $rangeConds[] = " $k BETWEEN '".$s['min']."' AND '".$s['max']."' ";
            }
            elseif(isset($s['min'])){
                $rangeConds[] = " $k >= '".$s['min']."' ";
            }
            elseif(isset($s['max'])){
                $rangeConds[] = " $k <= '".$s['max']."' ";
            }
        }
    }
    if (count($rangeConds) > 0) {
        $rangeConds = " AND " . implode(" AND ", $rangeConds);
    } else {
        $rangeConds = "";
    }

    return $rangeConds;
}
<?php

// generate general API format for response
function getAPIFormat( $message='', $result=null, $errors=null ) {
    return [
        'message' => $message,
        'result' => $result,
        'errors' => $errors
    ];
}

// API format for pagination
function getAPIPaginatedFormat( $data=[], $pointer=[], $errors=null ) {
    return [
        'start' => ($pointer)?$pointer['start']:0,
        'length' => ($pointer)?$pointer['length']:0,
        'total' => ($pointer)?$pointer['total']:0,
        'total_all' => ($pointer)?$pointer['total_all']:0,
        'data' => $data,
        'errors' => $errors
    ];
}

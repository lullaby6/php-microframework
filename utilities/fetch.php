<?php

function fetch($url, $options = []) {
    $defaultOptions = [
        'method' => 'GET',
        'headers' => array(),
        'content' => null,
    ];
    $options = array_merge($defaultOptions, $options);

    $context = stream_context_create([
        'http' => [
            'method' => $options['method'],
            'header' => implode("\r\n", $options['headers']),
            'content' => $options['content'],
        ],
    ]);

    return file_get_contents($url, false, $context);
}

// example:
// $url = 'https://api.example.com/data';
// $jsonData = json_encode(array('key' => 'value'));
// $response = fetch($url, array(
//     'method' => 'POST',
//     'headers' => array(
//         'Content-Type: application/json',
//     ),
//     'content' => $jsonData,
// ));
// $data = json_decode($response, true);

?>
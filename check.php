<?php

require('./lib/db.php');

$vars = ['company', 'phone', 'email', 'text'];

if (isSetPostVars($vars)) {
    $post_data = getPostVars($vars);
    if (!$post_data['company']) {
        echo json_encode([
            'err_code' => 1,
            'text' => 'fill company name',
        ]);
        exit;
    }
    if (!$post_data['phone']) {
        echo json_encode([
            'err_code' => 2,
            'text' => 'fill phone',
        ]);
        exit;
    }
    if (!$post_data['email']) {
        echo json_encode([
            'err_code' => 3,
            'text' => 'fill email',
        ]);
        exit;
    }
    if (!filter_var($post_data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'err_code' => 4,
            'text' => 'incorrect email',
        ]);
        exit;
    }
    if (!$post_data['text']) {
        echo json_encode([
            'err_code' => 5,
            'text' => 'fill brief',
        ]);
        exit;
    }
    // echo json_encode([
    //     'err_code' => 6,
    //     'text' => 'data is exist',
    // ]);
    echo json_encode([
        'err_code' => 0,
        'text' => 'data saved',
    ]);

    exit;

}
echo 'no data error!';
?>
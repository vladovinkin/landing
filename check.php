<?php

require('./lib/db.php');

$vars = ['company', 'phone', 'email', 'text'];

if (isSetPostVars($vars)) {
    $post_data = getPostVars($vars);
    if (!$post_data['company']) {
        echo json_encode([
            'err_code' => 1,
            'text' => 'Please, fill company name',
        ]);
        exit;
    }
    if (!$post_data['phone']) {
        echo json_encode([
            'err_code' => 2,
            'text' => 'Please, fill phone',
        ]);
        exit;
    }
    if (!$post_data['email']) {
        echo json_encode([
            'err_code' => 3,
            'text' => 'Please, fill email',
        ]);
        exit;
    }
    if (!filter_var($post_data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'err_code' => 4,
            'text' => 'Please, enter correct email',
        ]);
        exit;
    }
    if (!$post_data['text']) {
        echo json_encode([
            'err_code' => 5,
            'text' => 'Please, fill brief',
        ]);
        exit;
    }

    if (!array_intersect($post_data, [1 => ''])) {

        try {
            $db = getConnect();

            $data = [
                'company' => $post_data['company'],
                'phone' => $post_data['phone'],
                'email' => $post_data['email'],
            ];

            // поиск совпадений по имени компании, номеру телефона или email
            $query = $db->prepare("SELECT id FROM $table_company WHERE `name` = :company OR phone = :phone OR email = :email");
            $query->execute($data);

            $company_id = $query->fetchColumn();

            // если есть совпадение, то заявка уже есть в базе:
            // - всё равно запишем ещё одну заявку, чтобы не пропустить возможно важную информацию
            // - иначе: добавление новой компании и заявки от неё
            if ($company_id) {

                $data = [
                    'company_id' => $company_id,
                    'text' => $post_data['text'],
                    'created_at' => time(),
                ];

                $query = $db->prepare("INSERT INTO $table_requests (company_id, text, created_at) values (:company_id, :text, :created_at)");
                $query->execute($data);

                echo json_encode([
                    'err_code' => 6,
                    'text' => 'Your company data is exist',
                ]);

                exit;

            } else {
                $query = $db->prepare("INSERT INTO $table_company (name, phone, email) values (:company, :phone, :email)");
                $query->execute($data);
                
                $query = $db->query("SELECT MAX(id) FROM $table_company");
                $company_id = $query->fetchColumn();
                
                $data = [
                    'company_id' => $company_id,
                    'text' => $post_data['text'],
                    'created_at' => time(),
                ];

                $query = $db->prepare("INSERT INTO $table_requests (company_id, text, created_at) values (:company_id, :text, :created_at)");
                $query->execute($data);

                echo json_encode([
                    'err_code' => 0,
                    'text' => 'Your data saved. Thank you!',
                ]);
            
                exit;
            }

        } catch (PDOException $e) {
            print "Ошибка!: " . $e->getMessage() . "<br/>";
        }
    }

}
echo 'no data error!';
?>
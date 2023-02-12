<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Check company data</title>
</head>
<body>
    <div class="container container-check">
        <?php

            require('./lib/db.php');

            $vars = ['email'];
            $message_show = false;

            if (isSetPostVars($vars)) {
                $post_data = getPostVars($vars);
                if (!array_intersect($post_data, [1 => ''])) {

                    try {
                        $db = getConnect();

                        $data = [
                            'email' => $post_data['email'],
                        ];

                        $query = $db->prepare("SELECT * FROM $table_company WHERE email = :email");
                        $query->execute($data);

                        $company_id = $query->fetch();

                        // если есть совпадение, то в базе есть информация по заявкам
                        if ($company_id) {
                            ?>
                            <span>Сведения о компании</span>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Company name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th><?= $company_id['name'] ?></th>
                                        <th><?= $company_id['phone'] ?></th>
                                        <th><?= $company_id['email'] ?></th>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <?php
                                $query = $db->prepare("SELECT created_at, text FROM $table_requests WHERE company_id = :company_id ORDER BY created_at ASC");
                                $query->execute(['company_id' => $company_id['id']]);
                                $requests = $query->fetchAll(PDO::FETCH_KEY_PAIR);
                                if ($requests) {
                                    ?>
                                        <span>Поступившие заявки</span>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Number</th>
                                                    <th>Text</th>
                                                    <th>Date(time)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $count = 1;
                                                    foreach ($requests as $date => $text) {
                                                        ?>
                                                            <tr>
                                                                <th><?= $count++ ?></th>
                                                                <th><?= $text ?></th>
                                                                <th><?= date("Y-m-d (H:i)", $date); ?></th>
                                                            </tr>
                                                        <?php 
                                                    } 
                                                ?>
                                            </tbody>
                                        </table>
                                    <?php
                                }
                        } else {
                            // иначе - сообщение об ошибке
                            ?>
                            <span>ОШИБКА: Информация по <?= $data['email'] ?> отсутствует</span>
                            <?php
                        }

                    } catch (PDOException $e) {
                        print "Ошибка!: " . $e->getMessage() . "<br/>";
                    }
                }
            } else {
                ?>
                <div class="column">
                    <span class="title">Data checking</span>
                    <form method="POST" action="">
                        <input name="email" type="text" placeholder="E-mail"/><br>
                        <input type="submit" value="Check data"/>
                    </form>
                </div>
                <?php
            }
        ?>
        <a href="/">Main page</a>
        <a href="/info.php">Check again</a>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Info company data</title>
</head>
<body>
    <div class="info-container">
        <div class="info-container-tables">
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
                                <div class="table-company">
                                    <div class="table-title"><span class="table-title-text">Cведения о компании</span></div>
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
                                                <td><?= $company_id['name'] ?></td>
                                                <td><?= $company_id['phone'] ?></td>
                                                <td><?= $company_id['email'] ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <?php
                                    $query = $db->prepare("SELECT created_at, text FROM $table_requests WHERE company_id = :company_id ORDER BY created_at ASC");
                                    $query->execute(['company_id' => $company_id['id']]);
                                    $requests = $query->fetchAll(PDO::FETCH_KEY_PAIR);
                                    if ($requests) {
                                        ?>
                                            <div class="table-briefs">
                                                <div class="table-title"><span class="table-title-text">Поступившие заявки</span></div>
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
                                                                        <td><?= $count++ ?></td>
                                                                        <td><?= $text ?></td>
                                                                        <td><?= date("Y-m-d (H:i)", $date); ?></td>
                                                                    </tr>
                                                                <?php 
                                                            } 
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php
                                    }
                            } else {
                                // иначе - сообщение об ошибке
                                ?>
                                <span class="error-message">ОШИБКА: Информация по <?= $data['email'] ?> отсутствует</span>
                                <?php
                            }

                        } catch (PDOException $e) {
                            print "Ошибка!: " . $e->getMessage() . "<br/>";
                        }
                    }
                } else {
                    ?>
                    <div class="column">
                        <div class="table-title"><span class="table-title-text">Check info by email</span></div>
                        <form method="POST" id="form" action="/info.php">
                            <input name="email" type="email" id="email" class="check-email-input" placeholder="E-mail"/><br>
                            <input type="submit" id="submit" class="info-button button-green" value="Info data"/>
                        </form>
                    </div>
                    <?php
                }
            ?>
        </div>
        <div class="info-buttons">
            <a class="info-button button-blue" href="/">Main page</a>
                <?php if (isSetPostVars($vars)) { ?>
                <a class="info-button button-brown" href="/info.php">Search again</a>
                <?php } ?>
        </div>
    </div>
    <script src="./js/info.js"></script>
</body>
</html>

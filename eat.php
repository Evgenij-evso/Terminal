<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <title>Terminal</title>
</head>
<body>
    <div class="container_quiz_gl">
    <span class="text_header_quiz">Отлично</span>
        <?php

        include_once __DIR__ . '/create_lead.php';

        $success = success_task($_GET['task_id']);

        if ($success == 200){
            echo '<div class="container_buts" id="default">
                    <div class="text-quiz">1. Возьмите, а затем ознаĸомьтесь с договором - оферты.<br><br>
                    2. Займите один из свободных ĸрасных стульев напротив
                    администратора.<br><br>
                    3. Подготовьте ваши вопросы<br><br>
                    4. Каĸ будете готовы - просто поднимите глаза и мы продолжим оформление!</div>
                </div>';
        }else{
            echo 'div class="container_buts" id="default">
                    <div class="text-quiz">Ошибка, обратитесь к администратору!</div>
                </div>';
        }
        ?>
        
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="script.js"></script>
</body>
</html>

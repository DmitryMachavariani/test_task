<?php
require("connect.php");
require("lang.php");

function secureData($data){
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);

    return $data;
}

function generateName($mime = NULL){
    $name_word = [
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"
    ];
    $lenght = 10;
    $name = null;

    for($num = 0; $num <= $lenght; $num++){
        $rand_num = rand(0, count($name_word) - 1);
        $name .= $name_word[$rand_num];
    }

    switch($mime){
        case 'image/gif':
            $ext = '.gif';
            break;
        case 'image/png':
            $ext = '.png';
            break;
        case 'image/jpeg':
            $ext = '.jpeg';
            break;
        default:
            $ext = NULL;
            break;
    }

    return $ext != NULL ? $name.$ext : $name;
}

if(isset($_POST) && $_POST["type"] == "register"){
    $firstname = secureData($_POST["firstname"]);
    $lastname = secureData($_POST["lastname"]);
    $email = secureData($_POST["email"]);
    $interesting = secureData($_POST["interesting"]);

    $errors = [];

    if($firstname == ""){
        array_push($errors, $language[$cl]['empty_field']);
    }else if(strlen($firstname) < 3){
        array_push($errors, $language[$cl]['lenght_error']);
    }

    if($lastname == ""){
        array_push($errors, $language[$cl]['empty_field']);
    }else if(strlen($lastname) < 3){
        array_push($errors, $language[$cl]['lenght_error']);
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        array_push($errors, $language[$cl]['incorrect_email']);
    }else if($firstname == ""){
        array_push($errors, $language[$cl]['empty_field']);
    }

    if($interesting == ""){
        array_push($errors, $language[$cl]['empty_field']);
    }else if(strlen($interesting) < 3){
        array_push($errors, $language[$cl]['lenght_error']);
    }

    $query = mysqli_query($connect, "SELECT * FROM tb_users WHERE email = '{$email}'");
    if(mysqli_num_rows($query) > 0){
        array_push($errors, $language[$cl]['email_exist']);
    }

    if(isset($_FILES["avatar"])){
        $uploaddir = "avatar/";
        $filename = generateName($_FILES["avatar"]["type"]);
        $uploadfile = $uploaddir.$filename;

        if(getimagesize($_FILES["avatar"]["tmp_name"]) != NULL){
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $uploadfile);
        }else{
            array_push($errors, $language[$cl]['incorrect_image']);
        }
    }else{
        array_push($errors, $language[$cl]['choose_avatar']);
    }

    if(count($errors) == 0){
        $date_reg = time();
        $password = generateName();
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        mysqli_query($connect, "INSERT INTO tb_users(firstname, lastname, password, email, interesting, avatar, date_reg)VALUES('{$firstname}', '{$lastname}', '{$password_hash}', '{$email}', '{$interesting}', '{$uploadfile}', '{$date_reg}')");

        echo $language[$cl]['success']."{$password}";
    }else{
        echo json_encode($errors);
    }
}

if(isset($_POST) && $_POST['type'] == 'login'){
    $email = secureData($_POST['email']);
    $password = secureData($_POST['password']);

    $errors = [];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        array_push($errors, $language[$cl]['incorrect_email']);
    }else if($email == ""){
        array_push($errors, $language[$cl]['empty_field']);
    }

    $query = mysqli_query($connect, "SELECT * FROM tb_users WHERE email = '{$email}'");
    if(mysqli_num_rows($query) > 0){
        $result = mysqli_fetch_assoc($query);

        if(!password_verify($password, $result['password'])){
            array_push($errors, $language[$cl]['incorrect_password']);
        }
    }else{
        array_push($errors, $language[$cl]['user_not_found']);
    }

    if(count($errors) == 0){
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        $date = time();

        mysqli_query($connect, "UPDATE tb_users SET date_last = '{$date}' WHERE email = '{$email}'");

        echo $language[$cl]['success_login'];
    }else{
        echo json_encode($errors);
    }
}

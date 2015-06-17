<?php
session_start();

$language = [
    'rus'=>[
        'firstname'=>'Ваше имя',
        'lastname'=>'Ваша фамилия',
        'email'=>'Ваш Email адрес',
        'interesting'=>'Ваши интересы',
        'password'=>'Ваш пароль',
        'continue'=>'Продолжить',
        'choose_avatar'=>'Выберите аватар',
        'empty_field'=>'Поле не может быть пустым',
        'empty_avatar'=>'Выберите аватар',
        'incorrect_email'=>'Поле Email заполнено не корректно',
        'lenght_error'=>'Длина поля должна быть больше 2-х символов',
        'incorrect_image'=>'Неправильное изображение',
        'login'=>'Войти',
        'register'=>'Регистрация',
        'success'=>'Успешно зарегистрировались. Ваш пароль: ',
        'success_login'=>'Успешно вошли'
    ],
    'eng'=>[
        'firstname'=>'Your name',
        'lastname'=>'Your last name',
        'email'=>'Your email',
        'interesting'=>'Your interesting',
        'password'=>'Your password',
        'continue'=>'Continue',
        'choose_avatar'=>'Choose Avatar',
        'empty_field'=>'Field cannot be empty',
        'empty_avatar'=>'Select the avatar',
        'incorrect_email'=>'Email filled incorrectly',
        'lenght_error'=>'The field length must be greater than 2 characters',
        'incorrect_image'=>'Wrong image',
        'login'=>'Login',
        'register'=>'Register',
        'success'=>'Successfully registered. Your password: ',
        'success_login'=>'Successfully login'
    ]
];

if(isset($_GET['l'])){
    if($_GET['l'] == 'eng')
        $_SESSION['language'] = 'eng';
    else if($_GET['l'] == 'rus')
        $_SESSION['language'] = 'rus';

    if(isset($_SERVER["HTTP_REFERER"])){
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }else{
        header("Location: index.php");
    }
}

$cl = isset($_SESSION['language']) ? $_SESSION['language'] : 'rus';
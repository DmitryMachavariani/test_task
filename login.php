<?php require("lang.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Тестовое задание</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<div class="container">
    <a href="lang.php?l=rus"><img src="img/rus.png" width="64" height="64"></a>
    <a href="lang.php?l=eng"><img src="img/usa.png" width="64" height="64"></a>

    <form action="index.php" method="post" id="login" onsubmit="return false;" enctype="multipart/form-data">
        <input type="text" class="field" name="email" placeholder="<?=$language[$cl]['email'];?>"><br />
        <span class="validate_error" name="email_error"></span><br />

        <input type="password" class="field" name="password" placeholder="<?=$language[$cl]['password'];?>"><br />
        <span class="validate_error" name="password_error"></span><br />

        <br />
        <input type="submit" class="submit" id="process" value="<?=$language[$cl]['continue'];?>">
        <input type="button" class="submit" value="<?=$language[$cl]['register'];?>" onclick="location.replace('index.php')">
        <br />
        <span class="validate_error" id="error_summary"></span>
    </form>
</div>

<script>
    var errors = 0;
    document.getElementById("process").onclick = function(){
        var form = document.forms[0];

        var email = form.elements["email"].value;
        var password = form.elements["password"].value;

        if(trim(email) == ''){
            addError("email");
            addErrorText("email_error", "<?=$language[$cl]['empty_field'];?>");
            errors++;
        }else if(email.length < 3){
            addError("email");
            addErrorText("email_error", "<?=$language[$cl]['lenght_error'];?>");
            errors++;
        }else{
            removeError("email");
            addErrorText("email_error");
        }

        if(trim(password) == ''){
            addError("password");
            addErrorText("password_error", "<?=$language[$cl]['empty_field'];?>");
            errors++;
        }else if(password.length < 3){
            addError("password");
            addErrorText("password_error", "<?=$language[$cl]['lenght_error'];?>");
            errors++;
        }else{
            removeError("password");
            addErrorText("password_error");
        }

        if(errors == 0){
            try{
                var http = new XMLHttpRequest();
                http.open("POST", "ajax.php", true);
                http.onreadystatechange = function(){
                    if(http.readyState == 4 && http.status == 200){
                        try{
                            var data = JSON.parse(http.responseText);

                            for(var i = 0; i < data.length; i++){
                                document.getElementById("error_summary").innerHTML += data[i] + "<br />";
                            }
                        }catch(e){
                            document.getElementById("process").disabled = "disabled";
                            document.getElementById("process").className = "notactive";

                            document.getElementById("error_summary").innerHTML = http.responseText;
                        }
                    }
                };

                var _form = document.getElementById("login");
                var formData = new FormData(_form);
                formData.append("type", "login");

                http.send(formData);
            }catch(e){
                console.log(e.message);
            }
        }
    };

    function addError(name){
        var element = document.getElementsByName(name)[0];
        element.className += " error";
    }

    function removeError(name){
        var element = document.getElementsByName(name)[0];
        element.className = "field";
    }

    function addErrorText(name, text){
        text = text || "";
        document.getElementsByName(name)[0].innerHTML = text;
    }

    function trim(str){
        return str.replace(/^\s+|\s+$/g, "");
    }
</script>
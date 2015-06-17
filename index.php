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

    <form action="index.php" method="post" id="register" onsubmit="return false;" enctype="multipart/form-data">
        <input type="text" class="field" name="firstname" placeholder="<?=$language[$cl]['firstname'];?>"><br />
        <span class="validate_error" name="firstname_error"></span><br />

        <input type="text" class="field" name="lastname" placeholder="<?=$language[$cl]['lastname'];?>"><br />
        <span class="validate_error" name="lastname_error"></span><br />

        <input type="text" class="field" name="email" placeholder="<?=$language[$cl]['email'];?>"><br />
        <span class="validate_error" name="email_error"></span><br />

        <input type="text" class="field" name="interesting" placeholder="<?=$language[$cl]['interesting'];?>"><br />
        <span class="validate_error" name="interesting_error"></span><br />

        <input type="file" name="avatar" style="display: none;" accept="image/jpeg,image/png,image/gif">
        <input type="button" class="button" value="<?=$language[$cl]['choose_avatar'];?>" onclick="document.getElementsByName('avatar')[0].click()">
        <br />
        <span class="validate_error" name="avatar_error"></span><br />

        <br />
        <input type="submit" class="submit" id="process" value="<?=$language[$cl]['continue'];?>">
        <input type="button" class="submit" value="<?=$language[$cl]['login'];?>" onclick="location.replace('login.php')">
        <br />
        <span class="validate_error" id="error_summary"></span>
    </form>
</div>

<script>
    var errors = 0;
    document.getElementById("process").onclick = function(){
        var form = document.forms[0];

        var firstname = form.elements["firstname"].value;
        var lastname = form.elements["lastname"].value;
        var email = form.elements["email"].value;
        var avatar = form.elements["avatar"].value;
        var interesting = form.elements["interesting"].value;

        if(trim(firstname) == ''){
            addError("firstname");
            addErrorText("firstname_error", "<?=$language[$cl]['empty_field'];?>");
            errors++;
        }else if(firstname.length < 3){
            addError("firstname");
            addErrorText("firstname_error", "<?=$language[$cl]['lenght_error'];?>");
            errors++;
        }else{
            removeError("firstname");
            addErrorText("firstname_error");
        }

        if(trim(lastname) == ''){
            addError("lastname");
            addErrorText("lastname_error", "<?=$language[$cl]['empty_field'];?>");
            errors++;
        }else if(lastname.length < 3){
            addError("lastname");
            addErrorText("lastname_error", "<?=$language[$cl]['lenght_error'];?>");
            errors++;
        }else{
            removeError("lastname");
            addErrorText("lastname_error");
        }

        if(trim(email) == ''){
            addError("email");
            addErrorText("email_error", "<?=$language[$cl]['empty_field'];?>");
            errors++;
        }else if(!email.match(/^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$/)){
            addError("email");
            addErrorText("email_error", "<?=$language[$cl]['incorrect_email'];?>");
            errors++;
        }else{
            removeError("email");
            addErrorText("email_error");
        }

        if(trim(avatar) == ''){
            addError("avatar");
            addErrorText("avatar_error", "<?=$language[$cl]['empty_avatar'];?>");
            errors++;
        }else{
            removeError("avatar");
            addErrorText("avatar_error");
        }

        if(trim(interesting) == ''){
            addError("interesting");
            addErrorText("interesting_error", "<?=$language[$cl]['empty_field'];?>");
            errors++;
        }else if(interesting.length < 4){
            addError("interesting");
            addErrorText("interesting_error", "<?=$language[$cl]['lenght_error'];?>");
            errors++;
        }else{
            removeError("interesting");
            addErrorText("interesting_error");
        }

        if(errors == 0){
            try{
                var http = new XMLHttpRequest();
                http.open('POST', 'ajax.php', true);

                http.upload.onprogress = function(event){
                    console.log(event.loaded + "/" + event.total);
                };

                http.onreadystatechange = function(){
                    if(http.readyState == 4 && http.status == 200){
                        document.getElementById("error_summary").innerHTML = "";
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

                var form_data = document.getElementById("register");
                var formData = new FormData(form_data);
                formData.append("type", "register");

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
</body>
</html>
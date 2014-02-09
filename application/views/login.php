<!DOCTYPE html>
<html>
    <head>
        <title>Login page</title>
    </head>
    <body>
        <div>
            <form action="/login" method="POST">
                <label>User name: </label><br>
                <input name="login" type="text"><br>
                <label>Password: </label><br>
                <input type="password" name="pass"><br>
                <input type="submit" name="send" value="signin">
            </form>
        </div>
        <br>
        <span><?php echo @$_SESSION['error']; ?></span>
    </body>
</html>

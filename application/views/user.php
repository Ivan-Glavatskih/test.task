<!DOCTYPE html>
<html>
    <head>
        <title>User page</title>
    </head>
    <body>
        <div>
            <span>Добрый день, <?php echo $_SESSION['userName']; ?>!</span>
            <a href="/logout">Logout</a>
        </div>
    </body>
</html>

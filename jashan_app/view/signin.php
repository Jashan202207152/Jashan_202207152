<main>
    <h1>Login</h1>
    <form action="jashan_app.php" method="post">
        <p style="color:red;"><?php echo $error; ?></p>
        <label>Username:</label>
        <input type="text" name="username"><br>
        <label>Password:</label>
        <input type="password" name="password"><br>
        <input type="submit" name="action" value="signin">
    </form>
</main>

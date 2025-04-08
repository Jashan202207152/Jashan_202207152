<main>
    <h1>Registration Form</h1>
    <form action="jashan_app.php" method="post">
        <p style="color:red;"><?php echo $error; ?></p>
        <label>Username:</label>
        <input type="text" name="username"><br>

        <label>Password:</label>
        <input type="password" name="password"><br>

        <label>Email:</label>
        <input type="email" name="email"><br>

        <label>First Name:</label>
        <input type="text" name="fname"><br>

        <label>Last Name:</label>
        <input type="text" name="lname"><br>

        <input type="submit" name="action" value="registerNow">
    </form>
</main>

<main>
    <h1>Welcome!!!!!</h1>
    <nav>
        <?php if ($loggedIn): ?>
            <a href="jashan_app.php?action=newPost">Make a New Post</a> | 
            <a href="jashan_app.php?action=panel">View Account</a> | 
            <a href="jashan_app.php?action=signout">Logout</a>
        <?php else: ?>
            <a href="jashan_app.php?action=signinForm">Login</a> | 
            <a href="jashan_app.php?action=signup">Register</a>
        <?php endif; ?>
    </nav>
    <?php echo $result; ?>
</main>

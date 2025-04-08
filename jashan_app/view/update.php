<main>
    <h1>Edit Your Post</h1>
    <form action="jashan_app.php" method="post">
        <label for="comment">Your Comment:</label><br>
        <textarea name="comment" id="comment" maxlength="255"><?php echo $editComment; ?></textarea><br>
        <input type="hidden" name="id" value="<?php echo $commentId; ?>">
        <input type="submit" name="action" value="updatePost">
    </form>
</main>

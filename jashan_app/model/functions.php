<?php
// Database Connection
$dsn = 'mysql:host=localhost;dbname=myapp';
$dbuser = 'root';
$dbpass = '';
$db = new PDO($dsn, $dbuser, $dbpass);

function fieldsFilled($username, $password, $email, $fname, $lname) {
    return !(empty($username) || empty($password) || empty($email) || empty($fname) || empty($lname));
}
// Check Username 
function isUsernameAvailable($username) {
    global $db;
    $statement = $db->prepare("SELECT * FROM accounts WHERE username = :username");
    $statement->bindValue(':username', $username);
    $statement->execute();
    return $statement->fetch() == 0;
}

// User Registration
function createUser($username, $password, $email, $fname, $lname) {
    global $db;
    $statement = $db->prepare("INSERT INTO accounts (username, password, email, fname, lname)
                               VALUES (:username, :password, :email, :fname, :lname)");
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password',$password);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':fname', $fname);
    $statement->bindValue(':lname', $lname);
    $statement->execute();
}

// User Login
function attemptLogin($username, $password) {
    global $db;
    $statement = $db->prepare("SELECT * FROM accounts WHERE username = :username");
    $statement->bindValue(':username', $username);
    $statement->execute();
    $account = $statement->fetch();
    if ($account && $password == $account['password']) {
        return $account;
    }
    return null;
}

// Fetch Account Details
function fetchUserDetails($accountid) {
    global $db;
    $statement = $db->prepare("SELECT * FROM accounts WHERE id = :id");
    $statement->bindValue(':id', $accountid);
    $statement->execute();
    return $statement->fetch();
}

// Add Comment
function insertComment($user_id, $comment) {
    global $db;
    $statement = $db->prepare("INSERT INTO comments (user_id, comment) VALUES (:user_id, :comment)");
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':comment', $comment);
    $statement->execute();
}

// Retrieve All Comments
function grabComments() {
    global $db;
    $statement = $db->prepare("SELECT c.*, u.username, u.fname, u.lname
                               FROM comments c
                               JOIN accounts u ON c.user_id = u.id
                               ORDER BY c.posted_date DESC");
    $statement->execute();
    return $statement->fetchAll();
}

// Single Comment by ID
function fetchComment($commentId) {
    global $db;
    $statement = $db->prepare("SELECT * FROM comments WHERE id = :id");
    $statement->bindValue(':id', $commentId);
    $statement->execute();
    return $statement->fetch();
}

// Update Comment
function modifyComment($commentId, $newComment) {
    global $db;
    $statement = $db->prepare("UPDATE comments SET comment = :comment WHERE id = :id");
    $statement->bindValue(':comment', $newComment);
    $statement->bindValue(':id', $commentId);
    $statement->execute();
}

// Delete Comment
function removeComment($commentId) {
    global $db;
    $statement = $db->prepare("DELETE FROM comments WHERE id = :id");
    $statement->bindValue(':id', $commentId);
    $statement->execute();
}

// Build HTML Output
function htmlOutput($comments, $loggedin) {
    $result= "<h2>All Posts</h2>";

    if (empty($comments)) {
        $result .= "<p>No content yet.</p>";
    } else {
        foreach ($comments as $comment) {
            $result .= "<p><strong>" . htmlspecialchars($comment['username']) . ":</strong> " .
                       htmlspecialchars($comment['comment']) . "<br><small>" .
                       $comment['posted_date'] . "</small>";

            if ($loggedin && $comment['user_id'] == $_SESSION['accountid']) {
                $result .= " <a href='jashan_app.php?action=editPost&id=" . $comment['id'] . "'>Edit</a> | ";
                $result .= "<a href='jashan_app.php?action=deletePost&id=" . $comment['id'] . "'>Delete</a>";
            }

            $result .= "</p>";
        }
    }

    return $result;
}
?>

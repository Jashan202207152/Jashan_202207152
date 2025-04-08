<?php
session_start();

include("model/functions.php");

// Check session state
if (isset($_SESSION['loggedin'])) {
    $loggedIn = true;
    $accountid = $_SESSION['accountid'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $userDisplay = $_SESSION['userDisplay'];
    $menu = "<a href='jashan_app.php?action=panel'>Dashboard</a> | <a href='jashan_app.php?action=signout'>Logout</a>";
} else {
    $loggedIn = false;
    $menu = "<a href='jashan_app.php?action=signinForm'>Login</a> | <a href='jashan_app.php?action=signup'>Register</a>";
}

$action = filter_input(INPUT_POST, 'action');
if($action == NULL)
	$action = filter_input(INPUT_GET, 'action');
	
$error="";

// Logic Flow
if ($action === 'signout') {
    session_destroy();
    header("Location: jashan_app.php");

} elseif ($action === 'signinForm') {
    include('view/signin.php');

} elseif ($action === 'signin') {
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    $account = attemptLogin($username, $password);

    if ($account) {
        $_SESSION['loggedin'] = true;
        $_SESSION['accountid'] = $account['id'];
        $_SESSION['username'] = $account['username'];
        $_SESSION['email'] = $account['email'];
        $_SESSION['userDisplay'] = $account['fname'] . ' ' . $account['lname'];
        header("Location: jashan_app.php");
    } else {
        $error = "Incorrect login.";
        include('view/signin.php');
    }

} elseif ($action === 'signup') {
    include('view/signup.php');

} elseif ($action === 'registerNow') {
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    $email = filter_input(INPUT_POST, 'email');
    $fname = filter_input(INPUT_POST, 'fname');
    $lname = filter_input(INPUT_POST, 'lname');

    if (fieldsFilled($username, $password, $email, $fname, $lname)) {
        if (isUsernameAvailable($username)) {
            createUser($username, $password, $email, $fname, $lname);
            $error = "Registration successful. Please log in.";
            include('view/signin.php');
        } else {
            $error = "Username is not available.";
            include('view/signup.php');
        }
    } else {
        $error = "All fields are required.";
        include('view/signup.php');
    }

} elseif ($action === 'newPost') {
    include('view/post.php');

} elseif ($action === 'submitPost') {
    $comment = filter_input(INPUT_POST, 'comment');
    insertComment($_SESSION['accountid'], $comment);
    header("Location: jashan_app.php");

} elseif ($action === 'deletePost') {
    $commentId = filter_input(INPUT_GET, 'id');
    $comment = fetchComment($commentId);
    if ($comment['user_id'] == $_SESSION['accountid']) {
        removeComment($commentId);
    }
    header("Location: jashan_app.php");

} elseif ($action === 'editPost') {
    $commentId = filter_input(INPUT_GET, 'id');
    $comment = fetchComment($commentId);
    $editComment = trim($comment['comment']);
    include('view/update.php');

} elseif ($action === 'updatePost') {
    $commentId = filter_input(INPUT_POST, 'id');
    $newComment = filter_input(INPUT_POST, 'comment');
    modifyComment($commentId, $newComment);
    header("Location: jashan_app.php");

} elseif ($action === 'account') {
    $profile = fetchUserDetails($_SESSION['accountid']);
    include('view/account.php');

} else {
    $comments = grabComments();
    $result = htmlOutput($comments, $loggedIn);
    include('view/home.php');
}
?>

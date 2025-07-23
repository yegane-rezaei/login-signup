<?php

session_start();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        die("SQL error: " . $mysqli->error);
    }

    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($_POST["password"], $user["password_hash"])) {

            session_regenerate_id();
            $_SESSION["user_id"] = $user["id"];
            header("location: index.php");
            exit;
        } else {
            $is_invalid = true;
        }
        } else {
        $is_invalid = true;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>login</h1>
    <?php if (isset($is_invalid)): ?>
        <em>Invalid login</em>
    <?php endif; ?>
    <form  method="post" novalidate>
        <div>
            <label for="email">email:</label>
            <input type="email" id="email" name="email">
        </div>
        <div>
            <label for="password">password:</label>
            <input type="password" id="password" name="password">
        </div>
        <button>log in </button>
</body>
</html>
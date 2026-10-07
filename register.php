<?php

require_once "functions.php";

if (isLoggedIn()) {
    redirect("dashboard.php");
}

$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";


    if ($name == "" || $email == "" || $password == "") {

        $error = "All fields are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Enter a valid email.";

    } elseif (strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

    } else {

        $check = $pdo->prepare(
            "SELECT id FROM users WHERE email=?"
        );

        $check->execute([$email]);


        if ($check->fetch()) {

            $error = "Email already registered.";

        } else {

            $hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            $stmt = $pdo->prepare(
                "INSERT INTO users
                (name,email,password)
                VALUES (?,?,?)"
            );

            $stmt->execute([
                $name,
                $email,
                $hash
            ]);


            $_SESSION["user_id"] =
                $pdo->lastInsertId();

            $_SESSION["user_name"] =
                $name;

            $_SESSION["user_role"] =
                "jobseeker";


            redirect("dashboard.php");
        }
    }
}


$pageTitle = "Register";

include "header.php";

?>


<section class="auth">

    <div class="auth-card">

        <span class="section-label">
            JOIN MINIHIRE
        </span>

        <h1>
            Create your account
        </h1>

        <p>
            Find jobs and apply from one place.
        </p>


        <?php if ($error): ?>

            <div class="alert error">
                <?= e($error) ?>
            </div>

        <?php endif; ?>


        <form method="POST">


            <label>
                Full name

                <input
                    type="text"
                    name="name"
                    required
                >

            </label>


            <label>
                Email

                <input
                    type="email"
                    name="email"
                    required
                >

            </label>


            <label>
                Password

                <input
                    type="password"
                    name="password"
                    required
                    minlength="6"
                >

            </label>


            <button
                type="submit"
                class="primary-btn full"
            >
                Create account
            </button>


        </form>


        <p class="auth-bottom">

            Already have an account?

            <a href="login.php">
                Login
            </a>

        </p>

    </div>

</section>


<?php include "footer.php"; ?>
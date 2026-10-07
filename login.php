<?php

require_once "functions.php";

if (isLoggedIn()) {
    redirect("dashboard.php");
}

$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email =
        strtolower(
            trim($_POST["email"] ?? "")
        );

    $password =
        $_POST["password"] ?? "";


    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE email=?"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch();


    if (
        $user &&
        password_verify(
            $password,
            $user["password"]
        )
    ) {

        session_regenerate_id(true);


        $_SESSION["user_id"] =
            $user["id"];

        $_SESSION["user_name"] =
            $user["name"];

        $_SESSION["user_role"] =
            $user["role"];


        redirect("dashboard.php");

    } else {

        $error =
            "Invalid email or password.";

    }
}


$pageTitle = "Login";

include "header.php";

?>


<section class="auth">

    <div class="auth-card">

        <span class="section-label">
            WELCOME BACK
        </span>

        <h1>
            Login to MiniHire
        </h1>

        <p>
            Continue your job search.
        </p>


        <?php if ($error): ?>

            <div class="alert error">
                <?= e($error) ?>
            </div>

        <?php endif; ?>


        <form method="POST">

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
                >
            </label>


            <button
                type="submit"
                class="primary-btn full"
            >
                Login
            </button>

        </form>


        <p class="auth-bottom">

            New to MiniHire?

            <a href="register.php">
                Create account
            </a>

        </p>

    </div>

</section>


<?php include "footer.php"; ?>
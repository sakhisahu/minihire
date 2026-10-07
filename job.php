<?php

require_once "functions.php";

$id = (int)($_GET["id"] ?? 0);


$stmt = $pdo->prepare(
    "SELECT *
     FROM jobs
     WHERE id=?
     AND status='active'"
);

$stmt->execute([$id]);

$job = $stmt->fetch();


if (!$job) {

    redirect("index.php");

}


$message = "";
$messageType = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    requireLogin();

    $userId = $_SESSION["user_id"];


    $check = $pdo->prepare(
        "SELECT id
         FROM applications
         WHERE user_id=?
         AND job_id=?"
    );

    $check->execute([
        $userId,
        $id
    ]);


    if ($check->fetch()) {

        $message = "You have already applied for this job.";
        $messageType = "error";

    } else {

        $apply = $pdo->prepare(
            "INSERT INTO applications
             (user_id, job_id)
             VALUES (?, ?)"
        );

        $apply->execute([
            $userId,
            $id
        ]);

        $message = "Application submitted successfully!";
        $messageType = "success";

    }

}


$pageTitle = $job["title"];

include "header.php";

?>


<section class="container detail-section">

    <a href="index.php" class="back">
        ← Back to jobs
    </a>


    <?php if ($message): ?>

        <div class="alert <?= e($messageType) ?>">
            <?= e($message) ?>
        </div>

    <?php endif; ?>


    <div class="detail-card">


        <div class="detail-header">

            <div class="company-logo large">

                <?= e(
                    strtoupper(
                        substr($job["company"], 0, 1)
                    )
                ) ?>

            </div>


            <div>

                <span class="section-label">
                    JOB OPPORTUNITY
                </span>

                <h1>
                    <?= e($job["title"]) ?>
                </h1>

                <p class="company">
                    <?= e($job["company"]) ?>
                </p>

            </div>

        </div>


        <div class="detail-info">

            <span>
                📍 <?= e($job["city"]) ?>
            </span>

            <span>
                💼 <?= e(
                    jobTypeLabel(
                        $job["job_type"]
                    )
                ) ?>
            </span>

            <span>
                💰 <?= e($job["salary"]) ?>
            </span>

        </div>


        <div class="detail-grid">


            <div>

                <h2>
                    Job description
                </h2>

                <p>
                    <?= nl2br(
                        e($job["description"])
                    ) ?>
                </p>


                <h2>
                    Required skills
                </h2>


                <div class="skills">

                    <?php

                    foreach (
                        explode(",", $job["skills"])
                        as $skill
                    ):

                    ?>

                        <span>
                            <?= e(trim($skill)) ?>
                        </span>

                    <?php endforeach; ?>

                </div>


                <h2>
                    Requirements
                </h2>

                <p>
                    <?= nl2br(
                        e($job["requirements"])
                    ) ?>
                </p>

            </div>


            <div class="apply-box">

                <h3>
                    Interested in this job?
                </h3>

                <p>
                    Apply now and track your
                    application from dashboard.
                </p>


                <?php if (isLoggedIn()): ?>

                    <form method="POST">

                        <button
                            type="submit"
                            class="primary-btn full"
                        >
                            Apply now
                        </button>

                    </form>

                <?php else: ?>

                    <a
                        href="login.php"
                        class="primary-btn full"
                    >
                        Login to apply
                    </a>

                    <p class="small">
                        Don't have an account?
                        <a href="register.php">
                            Register
                        </a>
                    </p>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>


<?php include "footer.php"; ?>
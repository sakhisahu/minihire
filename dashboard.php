<?php

require_once "functions.php";

requireLogin();


$userId = $_SESSION["user_id"];


$stmt = $pdo->prepare(
    "SELECT
        applications.*,
        jobs.title,
        jobs.company,
        jobs.city,
        jobs.job_type
     FROM applications
     JOIN jobs
     ON applications.job_id = jobs.id
     WHERE applications.user_id=?
     ORDER BY applications.applied_at DESC"
);

$stmt->execute([$userId]);

$applications = $stmt->fetchAll();


$pageTitle = "Dashboard";

include "header.php";

?>


<section class="container dashboard">

    <div class="dashboard-head">

        <div>

            <span class="section-label">
                YOUR SPACE
            </span>

            <h1>
                Hello,
                <?= e($_SESSION["user_name"]) ?> 👋
            </h1>

            <p>
                Track your applications and
                discover new jobs.
            </p>

        </div>


        <a
            href="index.php"
            class="primary-btn"
        >
            Find more jobs
        </a>

    </div>


    <div class="dashboard-card">

        <h2>
            My applications
        </h2>


        <?php if ($applications): ?>


            <?php foreach ($applications as $app): ?>

                <div class="application">

                    <div>

                        <h3>
                            <?= e($app["title"]) ?>
                        </h3>

                        <p>
                            <?= e($app["company"]) ?>
                            ·
                            <?= e($app["city"]) ?>
                        </p>

                    </div>


                    <span class="status">
                        <?= e(
                            ucfirst(
                                $app["status"]
                            )
                        ) ?>
                    </span>

                </div>

            <?php endforeach; ?>


        <?php else: ?>


            <div class="empty-small">

                <p>
                    You haven't applied to any job yet.
                </p>

                <a href="index.php">
                    Browse jobs →
                </a>

            </div>


        <?php endif; ?>

    </div>

</section>


<?php include "footer.php"; ?>
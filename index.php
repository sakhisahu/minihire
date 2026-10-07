<?php

require_once "functions.php";

$pageTitle = "Find Jobs";

$search = trim($_GET["search"] ?? "");
$city   = trim($_GET["city"] ?? "");
$type   = trim($_GET["type"] ?? "");


/* Cities */

$cityQuery = $pdo->query(
    "SELECT DISTINCT city
     FROM jobs
     WHERE status='active'
     ORDER BY city"
);

$cities = $cityQuery->fetchAll();


/* Jobs */

$sql = "SELECT * FROM jobs WHERE status='active'";

$params = [];


if ($search != "") {

    $sql .= "
        AND (
            title LIKE :search
            OR company LIKE :search
            OR description LIKE :search
            OR skills LIKE :search
        )
    ";

    $params["search"] = "%$search%";
}


if ($city != "") {

    $sql .= " AND city = :city";

    $params["city"] = $city;
}


if ($type != "") {

    $sql .= " AND job_type = :type";

    $params["type"] = $type;
}


$sql .= " ORDER BY created_at DESC";


$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$jobs = $stmt->fetchAll();


include "header.php";

?>


<section class="hero">

    <div class="container hero-box">

        <div class="hero-small">
            A BETTER NEXT STEP STARTS HERE
        </div>

        <h1>
            Apni agli opportunity
            <br>
            dhoondhiye.
        </h1>

        <p>
            Jobs search karein, city ke according filter karein
            aur seedha apply karein.
        </p>


        <form
            method="GET"
            action="index.php"
            class="search-box"
        >

            <div class="search-field">

                🔍

                <input
                    type="text"
                    name="search"
                    value="<?= e($search) ?>"
                    placeholder="Role, company ya skill"
                >

            </div>


            <select name="city">

                <option value="">
                    All cities
                </option>

                <?php foreach ($cities as $c): ?>

                    <option
                        value="<?= e($c["city"]) ?>"
                        <?= $city == $c["city"] ? "selected" : "" ?>
                    >
                        <?= e($c["city"]) ?>
                    </option>

                <?php endforeach; ?>

            </select>


            <select name="type">

                <option value="">
                    All job types
                </option>

                <option
                    value="full-time"
                    <?= $type == "full-time" ? "selected" : "" ?>
                >
                    Full-time
                </option>

                <option
                    value="part-time"
                    <?= $type == "part-time" ? "selected" : "" ?>
                >
                    Part-time
                </option>

                <option
                    value="internship"
                    <?= $type == "internship" ? "selected" : "" ?>
                >
                    Internship
                </option>

                <option
                    value="contract"
                    <?= $type == "contract" ? "selected" : "" ?>
                >
                    Contract
                </option>

            </select>


            <button type="submit">
                Search jobs
            </button>

        </form>

    </div>

</section>



<section class="container jobs-section">

    <div class="section-head">

        <div>

            <span class="section-label">
                OPPORTUNITIES
            </span>

            <h2>
                Latest jobs
            </h2>

        </div>

        <span>
            <?= count($jobs) ?> jobs found
        </span>

    </div>


    <?php if ($search || $city || $type): ?>

        <div class="filters">

            <?php if ($search): ?>
                <span>
                    Search: <?= e($search) ?>
                </span>
            <?php endif; ?>


            <?php if ($city): ?>
                <span>
                    City: <?= e($city) ?>
                </span>
            <?php endif; ?>


            <?php if ($type): ?>
                <span>
                    Type: <?= e(jobTypeLabel($type)) ?>
                </span>
            <?php endif; ?>


            <a href="index.php">
                Clear filters
            </a>

        </div>

    <?php endif; ?>



    <?php if ($jobs): ?>


        <div class="job-list">

            <?php foreach ($jobs as $job): ?>


                <div class="job-card">

                    <div class="company-logo">

                        <?= e(
                            strtoupper(
                                substr($job["company"], 0, 1)
                            )
                        ) ?>

                    </div>


                    <div class="job-content">

                        <div class="job-title-row">

                            <div>

                                <h3>
                                    <?= e($job["title"]) ?>
                                </h3>

                                <p class="company">
                                    <?= e($job["company"]) ?>
                                </p>

                            </div>


                            <span class="job-type">
                                <?= e(
                                    jobTypeLabel(
                                        $job["job_type"]
                                    )
                                ) ?>
                            </span>

                        </div>


                        <div class="job-info">

                            <span>
                                📍 <?= e($job["city"]) ?>
                            </span>

                            <span>
                                💰 <?= e($job["salary"]) ?>
                            </span>

                            <span>
                                📅 <?= e(
                                    formatDate(
                                        $job["created_at"]
                                    )
                                ) ?>
                            </span>

                        </div>


                        <div class="skills">

                            <?php

                            $skills = explode(
                                ",",
                                $job["skills"]
                            );

                            foreach ($skills as $skill):

                            ?>

                                <span>
                                    <?= e(trim($skill)) ?>
                                </span>

                            <?php endforeach; ?>

                        </div>


                        <p class="description">
                            <?= e($job["description"]) ?>
                        </p>


                        <a
                            href="job.php?id=<?= $job["id"] ?>"
                            class="view-job"
                        >
                            View details →
                        </a>

                    </div>

                </div>


            <?php endforeach; ?>

        </div>


    <?php else: ?>


        <div class="empty">

            <div class="empty-icon">
                🔍
            </div>

            <h3>
                Koi job nahi mili
            </h3>

            <p>
                Search terms ya city badal kar
                dobara try karein.
            </p>

            <a
                href="index.php"
                class="primary-btn"
            >
                View all jobs
            </a>

        </div>


    <?php endif; ?>

</section>


<?php include "footer.php"; ?>
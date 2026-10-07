<?php

require_once "functions.php";

requireAdmin();


$message = "";
$type = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";


    if ($action === "add_job") {

        $title =
            trim($_POST["title"] ?? "");

        $company =
            trim($_POST["company"] ?? "");

        $city =
            trim($_POST["city"] ?? "");

        $jobType =
            trim($_POST["job_type"] ?? "");

        $salary =
            trim($_POST["salary"] ?? "");

        $skills =
            trim($_POST["skills"] ?? "");

        $description =
            trim($_POST["description"] ?? "");

        $requirements =
            trim($_POST["requirements"] ?? "");


        if (
            !$title ||
            !$company ||
            !$city ||
            !$jobType ||
            !$salary ||
            !$skills ||
            !$description
        ) {

            $message =
                "Please fill all required fields.";

            $type = "error";

        } else {

            $stmt = $pdo->prepare(
                "INSERT INTO jobs
                (
                    title,
                    company,
                    city,
                    job_type,
                    salary,
                    skills,
                    description,
                    requirements
                )
                VALUES (?,?,?,?,?,?,?,?)"
            );


            $stmt->execute([
                $title,
                $company,
                $city,
                $jobType,
                $salary,
                $skills,
                $description,
                $requirements
            ]);


            $message =
                "Job added successfully!";

            $type = "success";
        }
    }


    if ($action === "delete") {

        $jobId =
            (int)$_POST["job_id"];


        $stmt = $pdo->prepare(
            "UPDATE jobs
             SET status='closed'
             WHERE id=?"
        );

        $stmt->execute([$jobId]);


        $message =
            "Job removed.";

        $type = "success";
    }
}


$jobs = $pdo->query(
    "SELECT *
     FROM jobs
     ORDER BY created_at DESC"
)->fetchAll();


$pageTitle = "Admin";

include "header.php";

?>


<section class="container admin">

    <div class="dashboard-head">

        <div>

            <span class="section-label">
                ADMIN PANEL
            </span>

            <h1>
                Manage Jobs
            </h1>

            <p>
                Add jobs with city information
                for city-wise search.
            </p>

        </div>

    </div>


    <?php if ($message): ?>

        <div class="alert <?= e($type) ?>">
            <?= e($message) ?>
        </div>

    <?php endif; ?>


    <div class="admin-grid">


        <!-- ADD JOB -->

        <div class="dashboard-card">

            <h2>
                Add New Job
            </h2>


            <form method="POST">

                <input
                    type="hidden"
                    name="action"
                    value="add_job"
                >


                <label>
                    Job title *

                    <input
                        type="text"
                        name="title"
                        placeholder="PHP Developer"
                        required
                    >

                </label>


                <label>
                    Company *

                    <input
                        type="text"
                        name="company"
                        placeholder="ABC Technologies"
                        required
                    >

                </label>


                <label>
                    City *

                    <input
                        type="text"
                        name="city"
                        placeholder="Raipur"
                        required
                    >

                </label>


                <label>
                    Job type *

                    <select
                        name="job_type"
                        required
                    >

                        <option value="">
                            Select
                        </option>

                        <option value="full-time">
                            Full-time
                        </option>

                        <option value="part-time">
                            Part-time
                        </option>

                        <option value="internship">
                            Internship
                        </option>

                        <option value="contract">
                            Contract
                        </option>

                    </select>

                </label>


                <label>
                    Salary *

                    <input
                        type="text"
                        name="salary"
                        placeholder="₹15,000 - ₹25,000"
                        required
                    >

                </label>


                <label>
                    Skills *

                    <input
                        type="text"
                        name="skills"
                        placeholder="PHP, MySQL, JavaScript"
                        required
                    >

                </label>


                <label>
                    Description *

                    <textarea
                        name="description"
                        rows="5"
                        required
                    ></textarea>

                </label>


                <label>
                    Requirements

                    <textarea
                        name="requirements"
                        rows="4"
                    ></textarea>

                </label>


                <button
                    type="submit"
                    class="primary-btn"
                >
                    + Add Job
                </button>

            </form>

        </div>


        <!-- EXISTING JOBS -->

        <div class="dashboard-card">

            <h2>
                All Jobs
            </h2>


            <div class="admin-jobs">

                <?php foreach ($jobs as $job): ?>

                    <div class="admin-job">

                        <div>

                            <strong>
                                <?= e($job["title"]) ?>
                            </strong>

                            <p>
                                <?= e($job["company"]) ?>
                                ·
                                <?= e($job["city"]) ?>
                            </p>

                            <small>
                                <?= e(
                                    ucfirst(
                                        $job["status"]
                                    )
                                ) ?>
                            </small>

                        </div>


                        <?php if ($job["status"] === "active"): ?>

                            <form
                                method="POST"
                                onsubmit="
                                return confirm(
                                    'Remove this job?'
                                );
                                "
                            >

                                <input
                                    type="hidden"
                                    name="action"
                                    value="delete"
                                >

                                <input
                                    type="hidden"
                                    name="job_id"
                                    value="<?= $job["id"] ?>"
                                >

                                <button
                                    type="submit"
                                    class="delete-btn"
                                >
                                    Remove
                                </button>

                            </form>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </div>

</section>


<?php include "footer.php"; ?>
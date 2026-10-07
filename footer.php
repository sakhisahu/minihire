</main>

<footer class="footer">

    <div class="container footer-content">

        <div>
            <h3>MiniHire</h3>
            <p>Find jobs by role, company and city.</p>
        </div>

        <div class="footer-links">

            <a href="index.php">Jobs</a>

            <?php if (!isLoggedIn()): ?>

                <a href="login.php">Login</a>
                <a href="register.php">Register</a>

            <?php endif; ?>

        </div>

    </div>

    <div class="copyright">
        © <?= date("Y") ?> MiniHire. All rights reserved.
    </div>

</footer>

<script src="assets/app.js"></script>

</body>
</html>
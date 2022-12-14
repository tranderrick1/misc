<?php error_reporting(E_ERROR | E_PARSE); ?>

<nav class="navbar navbar-expand-xl navbar-dark bg-dark" style="background-color: #ffffff;">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="assets/logo.png" width="300" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main_nav">
            <ul class="navbar-nav ms-auto">
                <?php
                if ($_SESSION["account_type"] == 1) {
                    echo '<li class="nav-item"><a class="nav-link" href="browse.php">Browse Files</a></li>';
                }
                ?>
                <li class="nav-item"> <a class="nav-link" href="index.php">Home </a> </li>
                <li class="nav-item"> <a class="nav-link" href="search.php">Search Listing </a> </li>
                <?php
                if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                    echo '<li class="nav-item"><a class="nav-link" href="login.php">Create Listing</a></li>';
                } else {
                    echo '<li class="nav-item"><a class="nav-link" href="create_listing.php">Create Listing</a></li>';
                }
                ?>
                <?php
                if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                    echo '<li class="nav-item"><a class="nav-link" href="login.php">Log In</a></li>';
                } else {
                    include('profile_menu.php');
                }
                ?>
            </ul>
        </div> <!-- navbar-collapse.// -->
    </div> <!-- container-fluid.// -->
</nav>
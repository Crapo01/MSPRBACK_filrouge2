<?php
require_once (__DIR__.'/../config/global.php')
?>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="<?= BASE_URL."index.php"?>">Menu</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="<?= BASE_URL."index.php"?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL."controler/customer.php"?>" >customer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL."controler/vehicle.php"?>" >vehicle</a>
                </li>
            </ul>
        </div>
    </nav>
</header>


<?php
// Controleur qui gère l'affichage de tous les utilisateurs
require "../include/header.php";
require "/xampp/htdocs/app1/model/customer_entity.php";
require "/xampp/htdocs/app1/model/customerManager.php";







?>
<h1>add</h1>
<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Enregistrement du Client</h4>
                    </div>
                    <div class="card-body">
                        <form action="customer_add.php" method="post">
                            <div class="form-group">
                                <label for="fname">Prénom</label>
                                <input type="text" name="fname" id="fname" class="form-control" placeholder="Entrez le prénom" required>
                            </div>
                            <div class="form-group">
                                <label for="sname">Nom</label>
                                <input type="text" name="sname" id="sname" class="form-control" placeholder="Entrez le nom" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Adresse</label>
                                <input type="text" name="address" id="address" class="form-control" placeholder="Entrez l'adresse complète" required>
                            </div>
                            <div class="form-group">
                                <label for="permit">Numéro de permis</label>
                                <input type="text" name="permit" id="permit" class="form-control" placeholder="Entrez le numéro de permis" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary m-3">Soumettre</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<h1>find</h1>
<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Recherche de Client</h4>
                    </div>
                    <div class="card-body">
                        <form action="customer_find_by_names.php" method="get">
                            <div class="form-group">
                                <label for="fname">Prénom</label>
                                <input type="text" name="fname" id="fname" class="form-control" placeholder="Entrez le prénom" required>
                            </div>
                            <div class="form-group">
                                <label for="sname">Nom</label>
                                <input type="text" name="sname" id="sname" class="form-control" placeholder="Entrez le nom" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary m-3">Soumettre</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<h1>edit</h1>
<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Modifier le Client</h4>
                    </div>
                    <div class="card-body">
                        <form action="customer_edit.php" method="post">
                            <div class="form-group">
                                <label for="id">ID du Client</label>
                                <input type="number" name="id" id="id" class="form-control" placeholder="Entrez l'ID du client" required>
                            </div>
                            <div class="form-group">
                                <label for="fname">Prénom</label>
                                <input type="text" name="fname" id="fname" class="form-control" placeholder="Entrez le prénom" required>
                            </div>
                            <div class="form-group">
                                <label for="sname">Nom</label>
                                <input type="text" name="sname" id="sname" class="form-control" placeholder="Entrez le nom" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Adresse</label>
                                <input type="text" name="address" id="address" class="form-control" placeholder="Entrez l'adresse complète" required>
                            </div>
                            <div class="form-group">
                                <label for="permit">Numéro de permis</label>
                                <input type="text" name="permit" id="permit" class="form-control" placeholder="Entrez le numéro de permis" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary m-3">Soumettre</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
require "../include/footer.php";
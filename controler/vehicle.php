<?php
// Controleur qui gère l'affichage de tous les utilisateurs
require "../include/header.php";
require "/xampp/htdocs/app1/model/vehicle_entity.php";
require "/xampp/htdocs/app1/model/vehicleManager.php";







?>
<h1>add</h1>
<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Ajouter un Véhicule</h4>
                    </div>
                    <div class="card-body">
                        <form action="vehicle_add.php" method="post">
                            <div class="form-group">
                                <label for="licence_plate">Immatriculation</label>
                                <input type="text" name="licence_plate" id="licence_plate" class="form-control" placeholder="Entrez l'immatriculation du véhicule" required>
                            </div>
                            <div class="form-group">
                                <label for="informations">Informations</label>
                                <textarea name="informations" id="informations" class="form-control" placeholder="Entrez les informations concernant le véhicule (ex : dégradations)" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="km">Kilométrage</label>
                                <input type="number" name="km" id="km" class="form-control" placeholder="Entrez le kilométrage du véhicule" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Ajouter le Véhicule</button>
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
                        <h4>Rechercher un Véhicule par Immatriculation</h4>
                    </div>
                    <div class="card-body">
                        <form action="vehicle_find_by_plates.php" method="get">
                            <div class="form-group">
                                <label for="licence_plate">Immatriculation</label>
                                <input type="text" name="licence_plate" id="licence_plate" class="form-control" placeholder="Entrez l'immatriculation du véhicule" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Rechercher</button>
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
                        <h4>Modifier un Véhicule</h4>
                    </div>
                    <div class="card-body">
                        <form action="vehicle_edit.php" method="post">
                            <div class="form-group">
                                <label for="id">ID du Véhicule</label>
                                <input type="number" name="id" id="id" class="form-control" placeholder="Entrez l'ID du véhicule" required>
                            </div>
                            <div class="form-group">
                                <label for="licence_plate">Immatriculation</label>
                                <input type="text" name="licence_plate" id="licence_plate" class="form-control" placeholder="Entrez l'immatriculation du véhicule" required>
                            </div>
                            <div class="form-group">
                                <label for="informations">Informations</label>
                                <textarea name="informations" id="informations" class="form-control" placeholder="Entrez les informations concernant le véhicule (ex : dégradations)" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="km">Kilométrage</label>
                                <input type="number" name="km" id="km" class="form-control" placeholder="Entrez le kilométrage du véhicule" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Modifier le Véhicule</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
require "../include/footer.php";
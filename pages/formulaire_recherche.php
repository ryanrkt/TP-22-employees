<?php

require_once __DIR__ . '/../inc/fonction.php';
// Récupérer la liste des départements pour le dropdown
$departements = [];
$conn = dbconnect();
$res = mysqli_query($conn, "SELECT dept_no, dept_name FROM departments ORDER BY dept_name");
while ($row = $res->fetch_assoc()) {
    $departements[] = $row;
}
$chemin="/22-tp/pages/recherche.php";
?>

<div class="container my-3">
    <form class="row gy-2 gx-3 align-items-center bg-white p-3 rounded shadow-sm flex-nowrap" method="get" action="<?php echo $chemin; ?>" style="overflow-x:auto;">
        <div class="col-auto">
            <label class="visually-hidden" for="dept_no">Département</label>
            <select class="form-select" id="dept_no" name="dept_no">
                <option value="">Département</option>
                <?php foreach ($departements as $dep): ?>
                    <option value="<?= htmlspecialchars($dep['dept_no']) ?>">
                        <?= htmlspecialchars($dep['dept_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <label class="visually-hidden" for="nom">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom ou prénom">
        </div>
        <div class="col-auto">
            <label class="visually-hidden" for="age_min">Âge min</label>
            <input type="number" class="form-control form-control-lg" id="age_min" name="age_min" min="16" max="100" placeholder="Âge min" style="min-width:150px;">
        </div>
        <div class="col-auto">
            <label class="visually-hidden" for="age_max">Âge max</label>
            <input type="number" class="form-control form-control-lg" id="age_max" name="age_max" min="16" max="100" placeholder="Âge max" style="min-width:150px;">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary px-4">Rechercher</button>
        </div>
    </form>
</div>
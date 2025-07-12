<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'inc/fonction.php';
$departments = getDepartementEtManagerEncours();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Liste des départements</title>
 
    <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <header class="text-white py-4 mb-4">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="mb-0" style="letter-spacing:1px;font-size:2.2rem;">Départements et leurs Managers</h1>
            <a href="pages/stats_emplois.php" class="btn btn-outline-light">
                Statistiques des emplois
            </a>
        </div>
    </header>

    <?php include __DIR__ . '/pages/formulaire_recherche.php'; ?>

    <main class="container">
        <section>
            <div class="table-responsive">
                <table class="table table-dark table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Nom du département</th>
                            <th scope="col">Manager actuel</th>
                            <th>Nombre des employés</th>
                            <th>Voir le département</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($departments)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-danger">Aucun département trouvé.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($departments as $dept): ?>
                                <tr>
                                    <td><?= htmlspecialchars($dept['dept_name']) ?></td>
                                    <td><?= htmlspecialchars($dept['manager_first_name']) ?> <?= htmlspecialchars($dept['manager_last_name']) ?></td>
                                    <td><?= getNbEmployeesDepartement($dept['dept_no']); ?></td>
                                    <td>
                                        <a href="pages/departement_employees.php?dept_no=<?= urlencode($dept['dept_no']); ?>" class="btn btn-sm">
                                            Voir département
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
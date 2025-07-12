<?php
require '../inc/fonction.php';

$dept = $_GET['dept_no'];
$employes = [];
$departement = null;

if ($dept !== '') {
    $employes = getEmployesParDepartement($dept);
    $departement = getInfosDepartement($dept);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Employés du département <?= htmlspecialchars($dept) ?></title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <header class="text-white py-4 mb-4">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="mb-0">Détails du département</h1>
            <!-- <a href="recherche.php" class="btn btn-light fw-bold px-4 py-2 rounded-pill shadow-sm" style="font-size:1.1rem;">
                Faire une recherche
            </a> -->
        </div>
    </header>

    <?php include __DIR__ . '/formulaire_recherche.php'; ?>

    <main class="container">
        <?php if (!$departement): ?>
            <div class="alert alert-danger">Département introuvable.</div>
        <?php else: ?>
            <section class="mb-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title"><?= htmlspecialchars($departement['dept_name']) ?></h2>
                        <p class="card-text text-white">
                            <strong>Manager actuel :</strong> <?= htmlspecialchars($departement['manager_name'] ?? 'Aucun') ?>
                        </p>
                        <a href="../index.php" class="btn btn-sm">← Retour à la liste des départements</a>
                    </div>
                </div>
            </section>

            <section>
                <h3 class="mb-3">Employés du département</h3>
                <?php if (empty($employes)): ?>
                    <div class="alert alert-warning">Aucun employé trouvé dans ce département.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Numéro</th>
                                    <th>Nom</th>
                                    <th>Genre</th>
                                    <th>Date d'embauche</th>
                                    <th>Fiche</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employes as $emp): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($emp['emp_no']) ?></td>
                                        <td><?= htmlspecialchars($emp['last_name']) . ' ' . htmlspecialchars($emp['first_name']) ?></td>
                                        <td><?= htmlspecialchars($emp['gender']) ?></td>
                                        <td><?= htmlspecialchars($emp['hire_date']) ?></td>
                                        <td>
                                            <a href="fiche_employee.php?emp_no=<?= urlencode($emp['emp_no']); ?>" class="btn btn-sm">
                                                Voir fiche
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

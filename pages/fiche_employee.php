<?php
require '../inc/fonction.php';

$emp_no = $_GET['emp_no'] ?? '';
$employe = null;
$historique = [];

if ($emp_no !== '') {
    $employe = getInfosEmploye($emp_no);
    $historique = getEmployeeHistory($emp_no);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Fiche employé <?= htmlspecialchars($emp_no) ?></title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <header class="text-white py-4 mb-0">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="mb-0">Fiche de l’employé</h1>
        </div>
    </header>

    <?php include __DIR__ . '/formulaire_recherche.php'; ?>

    <main class="container">
        <?php if (!$employe): ?>
            <div class="alert alert-danger mt-4">Employé introuvable.</div>
        <?php else: ?>
            <section class="mb-4 mt-4">
                <div class="section-title">Informations générales</div>
                <table class="table fiche-table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <th>Nom et prénom</th>
                            <td><?= htmlspecialchars($employe['first_name'] . ' ' . $employe['last_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Numéro</th>
                            <td><?= htmlspecialchars($employe['emp_no']) ?></td>
                        </tr>
                        <tr>
                            <th>Genre</th>
                            <td><?= htmlspecialchars($employe['gender']) ?></td>
                        </tr>
                        <tr>
                            <th>Date de naissance</th>
                            <td><?= htmlspecialchars($employe['birth_date']) ?></td>
                        </tr>
                        <tr>
                            <th>Date d'embauche</th>
                            <td><?= htmlspecialchars($employe['hire_date']) ?></td>
                        </tr>
                        <tr>
                            <th>Département</th>
                            <td>
                                <?php if (!empty($employe['dept_no'])): ?>
                                    <a href="departement_employees.php?dept_no=<?= urlencode($employe['dept_no']) ?>" class="btn btn-outline-primary btn-sm">
                                        <?= htmlspecialchars($employe['dept_name']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Aucun</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="mb-3">
                    <a href="departement_employees.php?dept_no=<?= urlencode($employe['dept_no']) ?>" class="btn btn-sm me-2">← Employés du département</a>
                    <a href="../index.php" class="btn btn-sm">← Liste des départements</a>
                </div>
            </section>

            <section class="mb-4">
                <div class="section-title">Historique des titres</div>
                <?php if (empty($historique["titles"])): ?>
                    <div class="alert alert-warning">Aucun titre enregistré.</div>
                <?php else: ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Du</th>
                                <th>Au</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historique["titles"] as $titre): ?>
                                <tr>
                                    <td><?= htmlspecialchars($titre["title"]) ?></td>
                                    <td><?= htmlspecialchars($titre["from_date"]) ?></td>
                                    <td>
                                        <?= $titre["en_cours"] ? '<span class="badge badge-en-cours">En cours</span>' : htmlspecialchars($titre["to_date"]) ?>
                                    </td>
                                    <td>
                                        <?= $titre["en_cours"] ? '<span class="badge badge-en-cours">Actuel</span>' : '<span class="text-muted">Ancien</span>' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>

            <section class="mb-4">
                <div class="section-title">Historique des salaires</div>
                <?php if (empty($historique["salaries"])): ?>
                    <div class="alert alert-warning">Aucun salaire enregistré.</div>
                <?php else: ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Salaire</th>
                                <th>Du</th>
                                <th>Au</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historique["salaries"] as $salaire): ?>
                                <tr>
                                    <td><?= htmlspecialchars($salaire["salary"]) ?> Ar</td>
                                    <td><?= htmlspecialchars($salaire["from_date"]) ?></td>
                                    <td><?= htmlspecialchars($salaire["to_date"]) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

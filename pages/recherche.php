<?php

require_once __DIR__ . '/../inc/fonction.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);


$dept_no = $_GET['dept_no'] ?? '';
$nom = trim($_GET['nom'] ?? '');
$age_min = $_GET['age_min'] ?? '';
$age_max = $_GET['age_max'] ?? '';

// Pagination
$page = isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0 ? (int)$_GET['p'] : 1;
$parPage = 20;
$offset = ($page - 1) * $parPage;

// Nombre total de résultats
$total = compterEmployes($dept_no, $nom, $age_min, $age_max);
$nbPages = max(1, ceil($total / $parPage));

// Récupérer les employés pour la page courante
$employes = rechercherEmployes($dept_no, $nom, $age_min, $age_max, $offset, $parPage);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Résultat de la recherche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
    <?php include __DIR__ . '/formulaire_recherche.php'; ?>
    <main class="container py-4">
        <h2 class="mb-3">Résultat de la recherche</h2>
        <a href="../index.php" class="btn btn-secondary mb-3">← Retour</a>

        <?php if (empty($employes)): ?>
            <div class="alert alert-warning">Aucun employé ne correspond à la recherche.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>Numéro</th>
                            <th>Nom</th>
                            <th>Genre</th>
                            <th>Date de naissance</th>
                            <th>Date d'embauche</th>
                            <th>Département</th>
                            <th>Fiche</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employes as $emp): ?>
                            <tr>
                                <td><?= htmlspecialchars($emp['emp_no']) ?></td>
                                <td><?= htmlspecialchars($emp['last_name'] . ' ' . $emp['first_name']) ?></td>
                                <td><?= htmlspecialchars($emp['gender']) ?></td>
                                <td><?= htmlspecialchars($emp['birth_date']) ?></td>
                                <td><?= htmlspecialchars($emp['hire_date']) ?></td>
                                <td>
                                    <?php if (!empty($emp['dept_no'])): ?>
                                        <a href="departement_employees.php?dept_no=<?= urlencode($emp['dept_no']) ?>" class="btn btn-sm btn-outline-primary">
                                            <?= htmlspecialchars($emp['dept_name']) ?>
                                        </a>
                                        
                                    <?php else: ?>
                                        <span class="text-muted">Aucun</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="fiche_employee.php?emp_no=<?= urlencode($emp['emp_no']) ?>" class="btn btn-sm btn-outline-secondary">Voir fiche</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php
            // Pagination intelligente : 5 avant et 5 après la page courante
            $start = max(1, $page - 5);
            $end = min($nbPages, $page + 5);
            ?>
            <?php if ($nbPages > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination pagination-sm justify-content-center flex-wrap" style="gap:2px;">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['p' => $page - 1])) ?>">Précédent</a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = $start; $i <= $end; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['p' => $i])) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($page < $nbPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['p' => $page + 1])) ?>">Suivant</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>
</html>
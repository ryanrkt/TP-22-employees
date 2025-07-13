<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../inc/fonction.php';

$emp_no = $_GET['emp_no'] ?? '';
$employe = null;
$historique = [];
$departements = [];

if ($emp_no !== '') {
    $employe = getInfosEmploye($emp_no);
    $historique = getEmployeeHistory($emp_no);
    $departements = getAllDepartements();
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
            <?php if (isset($_GET['error']) && $_GET['error'] === 'duplicate'): ?>
                <div class="alert alert-danger">L’employé est déjà affecté à ce département (même dans le passé).</div>
            <?php elseif (isset($_GET['error']) && $_GET['error'] === 'missing'): ?>
                <div class="alert alert-danger">Veuillez remplir tous les champs du formulaire.</div>
            <?php endif; ?>

            <section class="mb-4 mt-4">
                <div class="section-title">Informations générales</div>
                <table class="table table-dark table-hover table-bordered align-middle fiche-table">
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
                                <a href="#" class="btn btn-sm bg-success ms-2" data-bs-toggle="modal" data-bs-target="#changerDepartementModal">
                                    Changer de département
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Aucun</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                <div class="mb-3">
                    <a href="departement_employees.php?dept_no=<?= urlencode($employe['dept_no']) ?>" class="btn btn-sm me-2">← Employés du département</a>
                    <a href="../index.php" class="btn btn-sm">← Liste des départements</a>
                </div>
            </section>

            <!-- Modal Bootstrap pour changement de département -->
            <div class="modal fade" id="changerDepartementModal" tabindex="-1" aria-labelledby="changerDepartementModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content bg-dark text-white">
                  <div class="modal-header">
                    <h5 class="modal-title" id="changerDepartementModalLabel">Changer de département</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                  </div>
                  <div class="modal-body">
                    <form action="../traitement/changer_departement.php" method="POST" class="needs-validation" novalidate>
                      <input type="hidden" name="emp_no" value="<?= htmlspecialchars($employe['emp_no']) ?>">

                      <div class="mb-3">
                        <label for="dept_no" class="form-label">Nouveau département</label>
                        <select name="dept_no" id="dept_no" class="form-select" required>
                          <option value="">-- Choisir un département --</option>
                          <?php foreach ($departements as $dept): ?>
                            <?php if ($dept['dept_no'] !== $employe['dept_no']): ?>
                              <option value="<?= $dept['dept_no'] ?>"><?= htmlspecialchars($dept['dept_name']) ?></option>
                            <?php endif; ?>
                          <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Veuillez choisir un département.</div>
                      </div>

                      <div class="mb-3">
                        <label for="from_date" class="form-label">Date de début</label>
                        <input type="date" name="from_date" id="from_date" class="form-control" required>
                        <div class="invalid-feedback">Veuillez indiquer une date de début.</div>
                      </div>

                      <button type="submit" class="btn btn-success">Valider</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- Historique des titres -->
            <section class="mb-4">
                <div class="section-title">Historique des titres</div>
                <?php if (empty($historique["titles"])): ?>
                    <div class="alert alert-warning">Aucun titre enregistré.</div>
                <?php else: ?>
                    <table class="table table-dark table-hover table-bordered align-middle">
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
                                        <?= $titre["en_cours"] ? '<span class="badge badge-en-cours">Actuel</span>' : '<span class="text-white">Ancien</span>' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>

            <!-- Historique des salaires -->
            <section class="mb-4">
                <div class="section-title">Historique des salaires</div>
                <?php if (empty($historique["salaries"])): ?>
                    <div class="alert alert-warning">Aucun salaire enregistré.</div>
                <?php else: ?>
                    <table class="table table-dark table-hover table-bordered align-middle">
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
                                    <td>
                                        <?= $salaire["to_date"] == '9999-01-01'
                                            ? '<span class="badge badge-en-cours">Actuel</span>'
                                            : htmlspecialchars($salaire["to_date"]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    (() => {
      'use strict'
      const forms = document.querySelectorAll('.needs-validation')
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
    })()
    </script>

    <?php if (isset($_GET['error'])): ?>
    <script>
      var myModal = new bootstrap.Modal(document.getElementById('changerDepartementModal'));
      myModal.show();
    </script>
    <?php endif; ?>

</body>
</html>
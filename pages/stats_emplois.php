<?php
require_once '../inc/connection.php';

$conn = dbconnect();
$sql = "SELECT * FROM v_stats_emploi";
$res = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques des emplois</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
    <header class="bg-primary text-white py-4 mb-4">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="mb-0" style="letter-spacing:1px;font-size:2.2rem;">Statistiques des emplois</h1>
            <a href="../index.php" class="btn btn-outline-light btn-lg fw-bold shadow-sm">Accueil</a>
        </div>
    </header>
    <main class="container">
        <div class="card shadow mb-5">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>Emploi</th>
                                <th>Nombre d'hommes</th>
                                <th>Nombre de femmes</th>
                                <th>Salaire moyen</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($res)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= (int)$row['nb_hommes'] ?></td>
                                <td><?= (int)$row['nb_femmes'] ?></td>
                                <td><?= number_format($row['salaire_moyen'], 2, ',', ' ') ?> €</td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
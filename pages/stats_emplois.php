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
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>
<body>
    <header class="text-white py-4 mb-4">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="mb-0" style="letter-spacing:1px;font-size:2.2rem;">Statistiques des emplois</h1>
        </div>
    </header>

    <main class="container">
        <a href="../index.php" class="btn btn-sm mb-3">← Retour à la liste des départements</a>

        <div class="card shadow mb-5">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover table-bordered align-middle">
                        
                            <tr>
                                <th>Emploi</th>
                                <th>Nombre d'hommes</th>
                                <th>Nombre de femmes</th>
                                <th>Salaire moyen</th>
                            </tr>
                        
                    
                            <?php while ($row = mysqli_fetch_assoc($res)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['title']) ?></td>
                                    <td><?= (int)$row['nb_hommes'] ?></td>
                                    <td><?= (int)$row['nb_femmes'] ?></td>
                                    <td><?= number_format($row['salaire_moyen'], 2, ',', ' ') ?> Ar</td>
                                </tr>
                            <?php endwhile; ?>
                    
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

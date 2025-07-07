<?php 
require 'connection.php';

function getDepartementEtManagerEncours(){
    $connectt=dbconnect();
    $sql="
    SELECT 
    d.dept_no,
    d.dept_name,
    m.emp_no AS manager_emp_no,
    CONCAT(e.first_name, ' ', e.last_name) AS manager_name
    FROM departments d
    JOIN dept_manager m ON d.dept_no = m.dept_no
    JOIN employees e ON m.emp_no = e.emp_no
    WHERE CURRENT_DATE BETWEEN m.from_date AND m.to_date;
    ";
    $result =mysqli_query($connectt, $sql);
    $departements = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $departements[] = $row;
        }
    }
    return $departements;
}
function getEmployesParDepartement($dept_no) {
    $connexion=dbconnect();

    $dept_no = mysqli_real_escape_string($connexion, $dept_no); // Sécurise l'entrée

    $requete = "
        SELECT e.emp_no, e.first_name, e.last_name, e.gender, e.hire_date
        FROM employees e
        JOIN dept_emp de ON e.emp_no = de.emp_no
        WHERE de.dept_no = '$dept_no'
          AND CURDATE() BETWEEN de.from_date AND de.to_date
        ORDER BY e.last_name, e.first_name
    ";

    $resultat = mysqli_query($connexion, $requete);

    $employes = [];
    if ($resultat) {
        while ($ligne = mysqli_fetch_assoc($resultat)) {
            $employes[] = $ligne;
        }
    }
    return $employes;
}
function getInfosDepartement($dept_no) {
    $connexion = dbconnect();

    $dept_no = mysqli_real_escape_string($connexion, $dept_no); 

    $requete = "
        SELECT d.dept_no, d.dept_name,
               CONCAT(e.first_name, ' ', e.last_name) AS manager_name
        FROM departments d
        LEFT JOIN dept_manager dm ON d.dept_no = dm.dept_no
        LEFT JOIN employees e ON dm.emp_no = e.emp_no
        WHERE d.dept_no = '$dept_no'
          AND CURDATE() BETWEEN dm.from_date AND dm.to_date
        LIMIT 1
    ";

    $resultat = mysqli_query($connexion, $requete);

    $departement = null;
    if ($resultat && mysqli_num_rows($resultat) > 0) {
        $departement = mysqli_fetch_assoc($resultat);
    }

    
    return $departement;
}


function getEmployeeHistory($emp_no) {
    $connection = dbconnect();
    $history = ["titles" => [], "salaries" => []];

    // Recupere les titres avec une indication "en_cours"
    $res = "SELECT title, from_date, to_date, 
                   (to_date = '9999-01-01') AS en_cours
            FROM titles 
            WHERE emp_no = $emp_no 
            ORDER BY from_date";
    $res = mysqli_query($connection, $res);
    while ($row = $res->fetch_assoc()) {
        // en_cours sera 1 si le titre est en cours, 0 sinon
        $row['en_cours'] = (bool)$row['en_cours'];
        $history["titles"][] = $row;
    }

    $res = "SELECT salary, from_date, to_date FROM salaries WHERE emp_no = $emp_no ORDER BY from_date";
    $res = mysqli_query($connection, $res);
    while ($row = $res->fetch_assoc()) {
        $history["salaries"][] = $row;
    }

    return $history;
}

function getInfosEmploye($emp_no) {
    $connexion = dbconnect();
    $emp_no = mysqli_real_escape_string($connexion, $emp_no);
    $query = "SELECT e.*, d.dept_no, dep.dept_name
              FROM employees e 
              LEFT JOIN dept_emp d ON d.emp_no = e.emp_no 
              LEFT JOIN departments dep ON d.dept_no = dep.dept_no
              WHERE e.emp_no = '$emp_no' 
              ORDER BY d.to_date DESC 
              LIMIT 1";
    $res = mysqli_query($connexion, $query);
    return $res ? mysqli_fetch_assoc($res) : null;
}

function rechercherEmployes($dept_no, $nom, $age_min, $age_max, $offset = 0, $parPage = 20) {
    $conn = dbconnect();
    $sql = "SELECT e.emp_no, e.first_name, e.last_name, e.gender, e.birth_date, e.hire_date,
                   d.dept_no, dep.dept_name
            FROM employees e
            LEFT JOIN dept_emp d ON e.emp_no = d.emp_no
            LEFT JOIN departments dep ON d.dept_no = dep.dept_no
            WHERE 1=1";

    if (!empty($dept_no)) {
        $dept_no = mysqli_real_escape_string($conn, $dept_no);
        $sql .= " AND d.dept_no = '$dept_no'";
    }
    if (!empty($nom)) {
        $nom = mysqli_real_escape_string($conn, $nom);
        $sql .= " AND (e.first_name LIKE '%$nom%' OR e.last_name LIKE '%$nom%')";
    }
    if (!empty($age_min)) {
        $age_min = (int)$age_min;
        $sql .= " AND TIMESTAMPDIFF(YEAR, e.birth_date, CURDATE()) >= $age_min";
    }
    if (!empty($age_max)) {
        $age_max = (int)$age_max;
        $sql .= " AND TIMESTAMPDIFF(YEAR, e.birth_date, CURDATE()) <= $age_max";
    }

    $sql .= " GROUP BY e.emp_no ORDER BY e.last_name, e.first_name";
    $sql .= " LIMIT $offset, $parPage";

    $res = mysqli_query($conn, $sql);
    $employes = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $employes[] = $row;
        }
    }
    return $employes;
}

function compterEmployes($dept_no, $nom, $age_min, $age_max) {
    $conn = dbconnect();
    $sql = "SELECT COUNT(DISTINCT e.emp_no) AS total
            FROM employees e
            LEFT JOIN dept_emp d ON e.emp_no = d.emp_no
            WHERE 1=1";

    if (!empty($dept_no)) {
        $dept_no = mysqli_real_escape_string($conn, $dept_no);
        $sql .= " AND d.dept_no = '$dept_no'";
    }
    if (!empty($nom)) {
        $nom = mysqli_real_escape_string($conn, $nom);
        $sql .= " AND (e.first_name LIKE '%$nom%' OR e.last_name LIKE '%$nom%')";
    }
    if (!empty($age_min)) {
        $age_min = (int)$age_min;
        $sql .= " AND TIMESTAMPDIFF(YEAR, e.birth_date, CURDATE()) >= $age_min";
    }
    if (!empty($age_max)) {
        $age_max = (int)$age_max;
        $sql .= " AND TIMESTAMPDIFF(YEAR, e.birth_date, CURDATE()) <= $age_max";
    }

    $res = mysqli_query($conn, $sql);
    $row = $res ? $res->fetch_assoc() : ['total' => 0];
    return (int)$row['total'];
}


?>
CREATE OR REPLACE VIEW v_departements_manager_current AS
SELECT 
    d.dept_no,
    d.dept_name,
    e.emp_no AS manager_no,
    e.first_name AS manager_first_name,
    e.last_name AS manager_last_name,
    dm.from_date,
    dm.to_date
FROM departments d
JOIN dept_manager dm ON d.dept_no = dm.dept_no
JOIN employees e ON dm.emp_no = e.emp_no
WHERE dm.to_date = '9999-01-01'



CREATE OR REPLACE VIEW v_employes_departement AS
SELECT 
    e.emp_no,
    e.first_name,
    e.last_name,
    e.gender,
    e.birth_date,
    e.hire_date,
    d.dept_no,
    dep.dept_name
FROM 
    employees e
JOIN dept_emp d ON e.emp_no = d.emp_no
JOIN departments dep ON d.dept_no = dep.dept_no
WHERE CURDATE() BETWEEN d.from_date AND d.to_date;



CREATE OR REPLACE VIEW v_departements_nb_employes AS
SELECT 
    d.dept_no,
    d.dept_name,
    COUNT(DISTINCT de.emp_no) AS nb_employes
FROM 
    departments d
LEFT JOIN dept_emp de ON d.dept_no = de.dept_no
    AND CURDATE() BETWEEN de.from_date AND de.to_date
GROUP BY d.dept_no, d.dept_name;

CREATE OR REPLACE VIEW v_stats_emploi AS
SELECT 
    t.title,
    SUM(e.gender = 'M') AS nb_hommes,
    SUM(e.gender = 'F') AS nb_femmes,
    ROUND(AVG(s.salary), 2) AS salaire_moyen
FROM 
    employees e
JOIN titles t ON e.emp_no = t.emp_no AND t.to_date = '9999-01-01'
JOIN salaries s ON e.emp_no = s.emp_no AND s.to_date = '9999-01-01'
GROUP BY t.title;

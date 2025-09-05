<?php
$config = include('config.php');

$servername = $config['servername'];
$username = $config['username'];
$password = $config['password'];
$dbname = $config['dbname'];
$dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";

try {
    $conn = new PDO($dsn, $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexão bem-sucedida!<br>";

    $sql = "SELECT * FROM Clientes";
    $query = $conn->query($sql);

    if ($query) {
        echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Endereço</th><th>Cidade</th><th>Telefone</th></tr>";

        $row_num = 0;
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $row_class = $row_num % 2 == 0 ? "style='background-color: #f2f2f2;'" : "";
            echo "<tr $row_class>";
            echo "<td>" . htmlspecialchars($row['Id_Cliente']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Endereco']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Cidade']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Telefone']) . "</td>";
            echo "</tr>";
            $row_num++;
        }
        echo "</table>";
    } else {
        echo "Erro na consulta SQL!";
    }
} catch (PDOException $e) {
    echo "Falha na conexão: " . $e->getMessage();
}

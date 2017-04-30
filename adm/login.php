<?php
include_once __DIR__ . "/../config.php";


//cabecera y menu izquierdo
echo "<div style='width: 100%;text-align: left;background-color: #983030;position: absolute;top: 0px;left: 0px; padding-left: 10px;'>

    <img src='./cabecera.jpg' style='float: left;'>
    <h1 style='float: left;padding-left: 10px;'>MYTickets</h1>
    </div>

    ";

echo "<div style='position: absolute;top: 70px;left: 250px;'>";

echo "<h1>Login</h1>
<form method='post' action='./accion.login.php'>
    <table>
        <tr>
            <td>Nombre de usuario</td>
            <td><input type='text' name='username'> </td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type='text' name='password'> </td>
        </tr>
        <tr>
            <td colspan='2'><input type='submit'></td>
        </tr>
    </table>
</form>";

echo "</table>";

echo "</table>
    </div>";

?>

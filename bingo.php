<?php
session_start();
$numero = -1;
srand((float)microtime() * 1000000);
$numero = rand(1, 99);

if (isset($_POST['finPartida'])) {
    $_SESSION['i'] = 100;
    unset($_POST['finPartida']);
}

if (!isset($_SESSION['i'])) {
    $_SESSION['i'] = 0;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bingo</title>
    <style>

        body {
            margin: 20px;
            padding: 0px;
        }

        div.mensaje {
            color: red;
            padding: 20px;
            font-size: 100px;
            width: 100%;
            text-align: center;
        }

        div#pizarraMain {
            padding: 20px;
            width: 30%;
            padding-left: 10%;
            font-size: 200px;
            color: red;
            float: left;
        }

        div#pizarraNumero {
            padding-left: 25%;
            width: 100%;
        }

        div#panelControl {
            width: 100%;
            font-size: 30px;
        }

        div#pizarraResumen {
            width: 50%;
            float: left;
        }

        table {
            font-size: 30px;
            margin: 0;
            padding: 0;
            margin-left: 50px;
        }

        td {
            padding: 5px 10px;
        }

        .cantado {
            color: red;
        }

        .instrucciones {
            display: none;
            font-size: 20px;
            width: 100%;
            color: grey;
            background-color: #efefef;
        }

        .detalle {
            display: block;
            font-size: 30px;
            width:100%;
            padding-left: 15%;
        }

        .negrita {
            font-weight: bold;
        }

        form {
            margin: 0px;
            padding: 0px;
        }

        input {
            height: 120px;
            width: 190px;
            font-size: 20px;
        }

        form#nuevoJuego {
            width: 100%;
            text-align: center;
        }
        h1 {
          PADDING-LEFT: 45%;
          COLOR: darkred;
          FONT-SIZE: xxx-large;
        }

    </style>
</head>

<body>
    <h1>BINGO</h1>
    <?php if ($_SESSION['i'] == 100) : ?>
        <div class="mensaje">JUEGA OTRA PARTIDA</div>
        <div id="panelControl">
            <form id="nuevoJuego" method="post" action="bingo.php">
                <input type="submit" id="nuevaPartida" name="nuevaPartida" value="NUEVA PARTIDA" />
            </form>
        </div>
        <?php
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        session_destroy();
        ?>
    <?php else : ?>
        <?php
        if (isset($_SESSION['cantados']) == FALSE) {

            $_SESSION['i'] = 1;
            $_SESSION['cantados'][] = $numero;
        } else {
            while (in_array($numero, $_SESSION['cantados'])) {
                $numero = rand(1, 100);
            }
            $_SESSION['cantados'][] = $numero;
            $_SESSION['i'] = $_SESSION['i'] + 1;
        }
        sort($_SESSION['cantados']);
        $decena = 0;
        ?>
        <div class="detalle"># restantes: <span class="negrita"><?php echo 100 - $_SESSION['i']; ?></span></div>
        <div id="pizarraMain">
            <div id="pizarraNumero"><?php echo $numero; ?></div>
            <div id="panelControl">
                <form method="post" action="bingo.php">
                    <input type="submit" id="nuevoNumero" name="nuevoNumero" value="NUEVO NUMERO" />
                    <input type="submit" id="finPartida" name="finPartida" value="FIN PARTIDA" />
                </form>
            </div>
        </div>
        <div id="pizarraResumen">
            <table>
                <?php for ($num = 1; $num < 100; $num++) : ?>
                    <?php if ($decena == 0) : ?>
                        <tr>
                        <?php endif ?>
                        <?php if (in_array($num, $_SESSION['cantados'])) : ?>
                            <td class="cantado"> <?php echo $num; ?></td>
                        <?php else : ?>
                            <td><?php echo $num; ?></td>
                        <?php endif ?>

                        <?php
                        if ($decena == 10) {
                            $decena = 0;
                            echo '</tr>';
                        } else {
                            $decena++;
                        }
                        ?>
                    <?php endfor; ?>

            </table>
        </div>
    <?php endif; ?>

</body>
</html>

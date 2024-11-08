<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @page {
            margin-top: 42mm;
            margin-right: 12mm;
            margin-bottom: 30mm;
            margin-left: 12mm;
        }
        .newPage {
            display: inline-block;
            width: 100%;
            font-family: Arial,
            sans-serif;
            font-size: 12px;
            text-align: justify;
        }
        .table-container {
            text-align: left;
        }
        .table-container table{
            margin: 0 auto;
        }
        th {
            color: #1a948d;
        }
        th, td {
            text-align: left;
            padding-left: 10px;
            padding-right: 10px;
        }
    </style>
</head>
<body>    
    <div class="newPage">
        <?php if ($goals[0]['id_mo_contratacion_metas_estados'] == "3"): ?>
            <h2><?php echo $textos['titulo']['rechazo']; ?></h2>
            <p>
                <?php echo $textos['parrafos']['rechazo'][0]; ?>
                <?php echo $goals[0]['nombre_jefe']; ?>
                <?php echo $textos['parrafos']['rechazo'][1]; ?>
                <?php echo $goals[0]['cargo']; ?>
                <?php echo $textos['parrafos']['rechazo'][2]; ?>
            </p>
            <p>
                <?php echo $goals[0]['observacion'] ?>
            </p>
        <?php else: ?>
        <h2><?php echo $textos['titulo']['aceptacion']; ?></h2>
        <p>
            <?php echo $textos['parrafos']['aceptacion'][0]; ?> <?php echo $goals[0]['nombre_jefe']; ?>
            <?php echo $textos['parrafos']['aceptacion'][1]; ?> <?php echo $goals[0]['cargo']; ?>
            <?php echo $textos['parrafos']['aceptacion'][2]; ?>
        </p>
        <p>
            <?php echo $textos['parrafos']['aceptacion'][3]; ?>
            <?php echo $textos['parrafos']['aceptacion'][4]; ?>
        </p>
        <p>
            <?php echo $textos['parrafos']['aceptacion'][5]; ?>
        </p>
        <?php endif ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <?php foreach (array_keys($dataTableGoals[0]) as $key): ?>
                                <th scope="col"><?php echo $key; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataTableGoals as $tr): ?>
                        <tr>
                            <?php foreach ($tr as $value): ?>
                                <td><?php echo $value; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <br>
        <h3><?php echo $textos['firma']; ?></h3>
        <p><b><?php echo $textos['firma_detalles']['aceptado_por']; ?></b> <?php echo isset($goals[0]['nombre_historial']) ? $goals[0]['nombre_historial'] : ''; ?></p>
        <p><b><?php echo $textos['firma_detalles']['documento']; ?></b> <?php echo isset($goals[0]['documento_historial']) ? $goals[0]['documento_historial'] : ''; ?></p>
        <p><b><?php echo $textos['firma_detalles']['fecha']; ?></b> <?php echo isset($goals[0]['fecha_historial']) ? $goals[0]['fecha_historial'] : ''; ?></p>
        <?php echo isset($goals[0]['ip']) ? '<p><b>'. $textos['firma_detalles']['ip'] .'</b> ' . $goals[0]['ip'] . '</p>' : ''; ?>
        <?php echo isset($goals[0]['usuario_red']) ? '<p><b>'. $textos['firma_detalles']['usuario_red'] .'</b> ' . $goals[0]['usuario_red'] . '</p>' : ''; ?>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse;
        }
        table, thead, tbody, tr, th, td {
            border: 1px solid black;
        }
        th, td {
            text-align: center;
            padding: 10px;
        }
        th {
            background-color: rgb(200, 200, 200);
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th scope="col"></th>
                <?php for ($j = 0; $j < 10; $j++) { ?>
                    <th scope="col"><?php echo $j+1?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < 10; $i++) { ?>
                <tr>
                    <th scope="row"><?php echo chr(65 + $i)?></th>
                    <?php for ($j = 0; $j < 10; $j++) { ?>
                        <td></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        include ("header.php");
    ?>
    <div class="main">
        <div>
            <h2>Spectacles à venir</h2>
            <?php
                for ($i=0; $i<3; $i++){
                ?>
                <div>
                    <p>Date</p>
                    <h3>Titre</h3>
                    <div>
                        <p>00H00</p>
                        <h4>Adresse</h4>
                    </div>
                </div>
                <?php
                    }
                ?>
        </div>
        <div>
            <h2>Spectacles passé</h2>
            <?php
                for ($i=0; $i<3; $i++){
                ?>
                <div>
                    <p>Date</p>
                    <h3>Titre</h3>
                    <div>
                        <p>00H00</p>
                        <h4>Adresse</h4>
                    </div>
                </div>
                <?php
                    }
                ?>
        </div>
    </div>
    <?php
        include ("footer.html");
    ?>
</body>
</html>
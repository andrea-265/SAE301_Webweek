<?php
session_start();
require_once('../config/ConfigBDD.php');
require_once('../class/Users.php');
require_once('fonctions/verifierConnexionEtDroitsAdmin.php');

// vérifier si la personne a le droit à cette page
//verifierConnexionEtDroits();

$id_user = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id_user) {
   die("ID utilisateur invalide");
}
$_SESSION['id_user_m'] = $id_user;  // Stocker l'ID dans la session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width,initial-scale=1">
   <title>Modifier les infos - Espace Administrateur</title>
   <meta name="description" content=""/>
   <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
   <!-- Lien vers la feuille de style Bootstrap 5 -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link href="../assets/css/styles_admin.css" rel="stylesheet" />
</head>
<body>
   <?php include('includes/nav.php'); ?>
   <div id="mySidebar">
      <?php include('includes/menu.php'); ?>
      
      <div id="myContenu">
         <main>
            <div class="container-fluid px-4">
               <h1 class="mt-4"><img src="../assets/images/half-wheel-yellow.svg" class="img-fluid" width="32" height="auto" alt=""> Tableau de bord</h1>
               <ol class="breadcrumb mb-4">
                  <li class="breadcrumb-item"><a class="text-decoration-none" href="#">Utilisateurs </a></li>
                  <li class="breadcrumb-item"><a class="text-decoration-none" href="#">Gestions utilisateurs</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Liste</li>
               </ol>
               
               <div class="row ">
                  <div class="col-12 col-lg-1 colg-md-1 col-sm-12">
                     <button type="button" class="btn btn-primary bg-danger btn-sm" onclick="window.location.href = 'liste_users.php';">
                        <i class="fas fa-arrow-left"></i> Retour
                     </button>
                  </div>
                  <div class="col-12 col-lg-11 colg-md-11 col-sm-12">
                     <div id="message" class="mt-1"></div>
                     <form id="testForm">
                        <div class="mb-3">
                           <label for="nom" class="form-label">Nom</label>
                           <input type="text" class="form-control" id="nom" name="nom">
                        </div>
                        <div class="mb-3">
                           <label for="prenom" class="form-label">Prénom</label>
                           <input type="text" class="form-control" id="prenom" name="prenom">
                        </div>
                        <div class="mb-3">
                           <label for="email" class="form-label">Email</label>
                           <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                           <label for="telephone" class="form-label">Téléphone</label>
                           <input type="text" class="form-control" id="telephone" name="telephone">
                        </div>
                        <div class="mb-3">
                           <label for="date_naissance" class="form-label">Date de naissance</label>
                           <input type="date" class="form-control" id="date_naissance" name="date_naissance">
                        </div>
                        <button type="submit" class="btn btn-primary bg-primary">Envoyer</button>
                     </form>
                  </div>                  
               </div>
            </div>
         </main>
         <?php include('includes/footer.php'); ?>
      </div>
   </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
   $(document).ready(function() {

      // stocker les valeurs actuelles 
      let currentNom = "", currentPrenom = "", currentEmail = "", currentTelephone = "", currentDateNaissance = "";

      // récupérer les données de l'utilisateur
      function recupererUtilisateur(id) {
        $.ajax({
            url: "../API/recupererInfoUser.php",  
            method: "GET",
            dataType: "json",  
            data: { id: id },  
            success: function(response) {
               if (response.status === "OK" && response.user) {
                 // console.log(response.user);
                 // remplir les champs 
               $('#nom').val(response.user.nom);
               $('#prenom').val(response.user.prenom);
               $('#email').val(response.user.mail);
               $('#telephone').val(response.user.telephone);
               $('#date_naissance').val(response.user.date_naissance);

               // sauvegarder les valeurs actuelles
               currentNom = response.user.nom;
               currentPrenom = response.user.prenom;
               currentEmail = response.user.mail;
               currentTelephone = response.user.telephone;
               currentDateNaissance = response.user.date_naissance;
               } else {
                  window.location.href = "liste_users.php";
               }
            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX:", error);
                alert("Une erreur est survenue lors de la récupération des données.");
            }
        });
      }

      // fonction pour modifier les données de l'utilisateur
      function modifierUtilisateur(id) {
         const messageDiv = document.getElementById('message');
         let nom = $('#nom').val();
         
         let prenom = $('#prenom').val();
        //console.log(prenom);
         let email = $('#email').val();
         let telephone = $('#telephone').val();
         let date_naissance = $('#date_naissance').val();

         if (!nom || !prenom || !email || !telephone || !date_naissance) {
            messageDiv.innerHTML = '<div class="alert alert-danger">Tous les champs sont obligatoires.</div>';
           // alert("Tous les champs sont obligatoires.");
            return;
         }

         // ne faire la mise à jour que si le nom a changé
         if (nom === currentNom && prenom === currentPrenom && email === currentEmail && telephone === currentTelephone && date_naissance === currentDateNaissance) {
            messageDiv.innerHTML = '<div class="alert alert-warning">Aucune modification effectuée.</div>';
            //alert("Aucune modification effectuée.");
            return;
         }


        $.ajax({
            url: "../API/modifierInfosUsers.php?id=" + id,
            method: "POST",
            dataType: "json",
            data: { nom: nom, prenom: prenom, mail: email, telephone: telephone, date_naissance: date_naissance },
            success: function(response) {
               let messageDiv = $('#message');
               if (response.status === "OK") {
                  messageDiv.html('<div class="alert alert-success">' + response.message + '</div>');
                  currentNom = nom;
                  currentPrenom = prenom;
                  currentEmail = email;
                  currentTelephone = telephone;
                  currentDateNaissance = date_naissance;
               } else {
                  messageDiv.html('<div class="alert alert-warning">' + response.message + '</div>');
               }
            },
            error: function(xhr, status, error) {
               alert("Une erreur est survenue lors de la modification des données.");
            }
        });
      }

      let id_user = <?php echo $_SESSION['id_user_m']; ?>;
      recupererUtilisateur(id_user); // récupère les informations de l'utilisateur

      // soumettre le formulaire
      $('#testForm').submit(function(event) {
        event.preventDefault();
        modifierUtilisateur(id_user); 
      });
   });
  </script>

<script src="../assets/JS/script_admin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>
</html>

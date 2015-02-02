 <?php

class C_Utilisateur extends C_ControleurGenerique {

    /**
     * préparation et affichage des coordonnées de l'utilisateur courant
     */
    function coordonnees() {
        $this->vue = new V_Vue("../vues/templates/template.inc.php");
        $this->vue->ecrireDonnee('titreVue', 'Vos informations');
        // charger les coordonnées de l'utilisateur connecté depuis la BDD       
        $daoPers = new M_DaoPersonne();
        $daoPers->connecter();
        $utilisateur = $daoPers->getOneByLogin(MaSession::get('login'));
        $daoPers->deconnecter();
        $this->vue->ecrireDonnee('utilisateur', $utilisateur);
        // transmettre le login        
        $this->vue->ecrireDonnee('loginAuthentification', MaSession::get('login'));
        // vue centrale à inclure
        $this->vue->ecrireDonnee('centre', "../vues/includes/utilisateur/centreAfficherMesInformationsFormulaire.inc.php");
        $this->vue->afficher();
    }

    /**
     *  modification des coordonnées de l'utilisateur courant
     */
    function modifierCoordonnees() {
        $this->vue = new V_Vue("../vues/templates/template.inc.php");
        $this->vue->ecrireDonnee('titreVue', 'Modification de vos informations');
        // charger les coordonnées de l'utilisateur connecté depuis la BDD       
        $daoPers = new M_DaoPersonne();
        $daoPers->connecter();
        $utilisateur = $daoPers->getOneByLogin(MaSession::get('login'));
        $daoPers->deconnecter();
        $this->vue->ecrireDonnee('utilisateur', $utilisateur);
        // transmettre le login        
        $this->vue->ecrireDonnee('loginAuthentification', MaSession::get('login'));

        $this->vue->ecrireDonnee('centre', "../vues/includes/utilisateur/centreModifierMesInformationsFormulaire.inc.php");
        $this->vue->afficher();
    }

    //validation de modification des donnée personelle à l'utilisateur
    function validerModifierCoordonnees() {
        $this->vue = new V_Vue("../vues/templates/template.inc.php");
        $this->vue->ecrireDonnee('titreVue', "Modification de vos informations");
        $this->vue->ecrireDonnee('centre',"../vues/includes/utilisateur/centreValiderModifierMesInformations.inc.php");
        $daoPers = new M_DaoPersonne();
        $daoPers->connecter();
        // récupérer les données du formulaire l'identifiant de l'utilisateur courant
        $id = $_GET["id"];

        // charger l'objet métier correspondant à l'utilisateur courant
//        $utilisateur = $daoPers->getOneByLoginEager($id);
        $utilisateur = $daoPers->getOneById($id);
//        var_dump($utilisateur);
        // mettre à jour l'objet métier d'après le formilaire de saisie
        $utilisateur->setCivilite($_POST["civilite"]);
        $utilisateur->setNom($_POST["nom"]);
        $utilisateur->setPrenom($_POST["prenom"]);
        $utilisateur->setNumTel($_POST["tel"]);
        $utilisateur->setMail($_POST["mail"]);
        if (MaSession::get('role') == 4) {
            $utilisateur->setEtudes($_POST["etudes"]);
            $utilisateur->setFormation($_POST["formation"]);
        }
        $ok = $daoPers->update($id, $utilisateur);
        if ($ok) {
            $this->vue->ecrireDonnee('message',"Modifications enregistr&eacute;es");
        } else {
            $this->vue->ecrireDonnee('message',"Echec des modifications");
        }
        $this->vue->afficher();
    }
    
    function afficheListeStage() {
        $this->vue = new V_Vue("../vues/templates/template.inc.php");
        $this->vue->ecrireDonnee('titreVue', 'Liste des stages');
        // charger la liste des stages pour l'envoyer vers la vue concernée      
        $daoStage = new M_DaoStage();
        $daoStage->connecter();
        $lesStages = $daoStage->getAll();
        $this->vue->ecrireDonnee('lesStages', $lesStages);
        $daoStage->deconnecter();
        $this->vue->ecrireDonnee('centre', "../vues/includes/utilisateur/centreListeStage.inc.php");
        $this->vue->ecrireDonnee('loginAuthentification', MaSession::get('login'));
        $this->vue->afficher();
    }
    
    function afficherStage(){
        $this->vue = new V_Vue("../vues/templates/template.inc.php");
        $this->vue->ecrireDonnee('titreVue', 'Détails du stage');
        $daoStage = new M_DaoStage();
        $daoPersonne = new M_DaoPersonne();
        $daoStage->connecter();
        $daoPersonne->connecter();
            $unStage = $daoStage->getOneById($_GET['idStage']);
            $this->vue->ecrireDonnee('unStage', $unStage); 
            $unEtudiant = $daoPersonne->getOneById($_GET['idEtudiant']);
            $this->vue->ecrireDonnee('unEtudiant', $unEtudiant);
            $unProfesseur = $daoPersonne->getOneById($_GET['idProfesseur']);
            $this->vue->ecrireDonnee('unProfesseur', $unProfesseur);
            $uneOrganisation = $daoPersonne->getOneById($_GET['idOrganisation']);
            $this->vue->ecrireDonnee('uneOrganisation', $uneOrganisation);
            $unMaitreStage = $daoPersonne->getOneById($_GET['idMaitreStage']);
            $this->vue->ecrireDonnee('unMaitreStage', $unMaitreStage);
        $daoPersonne->deconnecter();
        $daoStage->deconnecter();
        
        $this->vue->ecrireDonnee('centre', "../vues/includes/utilisateur/centreDetailsStage.inc.php");
        $this->vue->ecrireDonnee('loginAuthentification', MaSession::get('login'));
        $this->vue->afficher();
    }
}

?>
<?php
setlocale(LC_ALL, 'fr_FR.utf-8', 'fra');
date_default_timezone_set('Europe/Paris');

$today = date("d.m.y");
$totalOfDay = 0;
$totalCommandeDay = 0;

$commandeListQuery = $bdd->connexion->query('SELECT * FROM commande');

$commandeList = $commandeListQuery->fetchAll();

foreach($commandeList as $item){
    if(date("d.m.y", $item['identifiant_commande']) === $today){
        $totalOfDay += $item['prix_commande'];
    }
}

$starProductQuery = $bdd->connexion->prepare('SELECT categories.nom_categories, produits.nom_produits, `id_commande`, `identifiant_commande`, commande.id_produits, commande.id_ingredients, `id_users`, `prix_commande`, count(commande.id_produits) AS total 
                                            FROM `commande` 
                                            INNER JOIN produits 
                                            ON produits.id_produits = commande.id_produits 
                                            INNER JOIN categories 
                                            ON categories.id_categories = produits.id_categorie 
                                            GROUP BY id_produits');

if($starProductQuery->execute()){
    $starProduct = $starProductQuery->fetchAll();
}
else{
    echo '<div class="text-danger">Impossible de se connecter, si le problème persiste contactez votre web Master</div>';
}



if(isset($starProduct) && !empty($starProduct)){
    for($i = 0; $i < sizeof($starProduct); $i++){
        if($i === 0){
            $maxCount = $starProduct[$i]['total'];
            $star = $starProduct[$i]['nom_produits'];
            $starCat = $starProduct[$i]['nom_categories'];
        }
        else{
            if($starProduct[$i]['total'] > $maxCount){
                $maxCount = $starProduct[$i]['total'];
                $star = $starProduct[$i]['nom_produits'];
                $starCat = $starProduct[$i]['nom_categories'];
            }
        }
    }
}
else{
    $starCat = '';
    $star = 'Aucun produit star';
    $maxCount = 0;
}

$actualMonth = date('m');
$newClient = 0;

$clientListQuery = $bdd->connexion->query('SELECT * FROM users');
$clientList = $clientListQuery->fetchAll();

foreach($clientList as $client){
    if(date('m', $client['identifiant_users']) === $actualMonth){
        $newClient++;
    }
}

$panierMoyen = 0;
$nbPanier = 0;
$totauxPanier = [];

foreach($commandeList as $commandeItem){

    if(!isset($totauxPanier[$commandeItem['identifiant_commande']])){

        $totauxPanier[$commandeItem['identifiant_commande']] = $commandeItem['prix_commande'];
    
        foreach($commandeList as $commandeItemCompare){
            
            if($commandeItem['identifiant_commande'] === $commandeItemCompare['identifiant_commande'] && $commandeItem['id_commande'] != $commandeItemCompare['id_commande']){
                $totauxPanier[$commandeItem['identifiant_commande']] += $commandeItemCompare['prix_commande'];
            }
        }
    }
}

foreach($totauxPanier as $panier){
    $nbPanier++;
    $panierMoyen += $panier;
}

if($nbPanier != 0){   
    $panierMoyen = $panierMoyen / $nbPanier;
}

$commandeEnCour = [];

$commandeEnCour = $bdd->getCommande0();

$commandePriseEnCharge = $bdd->getCommande1();


?>

<div class="mx-5" style="margin-top: 70px;">
    <h1 class="mb-3 h1 me-3">
        DashBoard
    </h1>

    <div class="row row-cols-1 row-cols-md-4 g-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="h4">
                            Chiffre de la journée
                        </h4>
                    </div>
                    <div class="card-content h2 mt-5 mb-3 text-center text-primary">
                        <?=number_format($totalOfDay, 2)?>€
                    </div>
                </div>
                <div class="card-footer">
                    <?php
                        foreach($totauxPanier as $time => $value){
                            if(date('d.m.y', $time) === $today){
                                $totalCommandeDay ++; 
                            }
                        }

                        echo $totalCommandeDay.' commandes';
                    ?>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="h4">
                            Produit le plus commandé
                        </h4>
                    </div>
                    <div class="card-content h2 mt-5 mb-3 text-center text-primary">
                        <?=$starCat.' - '.$star?>
                    </div>
                </div>
                <div class="card-footer">
                    Commandé <?=$maxCount?> fois
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="h4">
                            Nouveaux clients au cours du mois
                        </h4>
                    </div>
                    <div class="card-content h2 mt-5 mb-3 text-center text-primary">
                        + <?=$newClient?> clients
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="h4">
                            Panier moyen
                        </h4>
                    </div>
                    <div class="card-content h2 mt-5 mb-3 text-center text-primary">
                        <?=number_format($panierMoyen, 2)?>€
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="d-flex mt-3 row row-col-1 row-col-md-1">
        <?php
            if(empty($commandePriseEnCharge) && empty($commandeEnCour)){
                echo '<div class="text-white text-center border bg-success py-2 h4">Aucune commande en attente</div>';
            }
        ?>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h3 class="h3 text-center fw-bold">Commandes en cours</h3>
            </div>
            <div class="card-content">
                <div class="accordion row" id="commandeAccordion">
                    <?php
                        foreach($commandeEnCour as $commandeNb => $value){
                            $commandeTime = strftime(' %A %d %b %Y à %H:%M', $commandeNb);
                            echo '
                                <div class="accordion-item ms-1 col-12">
                                   <h2 class="accordion-header ms-2 p-1" id="b'.$commandeNb.'">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c'.$commandeNb.'" aria-expanded="true" aria-controls="c'.$commandeNb.'">
                                            Commande N°'.$commandeNb.' <small class="text-muted ms-5">'.$commandeTime.'</small> <strong class="fw-bold ms-5" style="margin-left: auto;">'.number_format($value['prix_total'], 2, ',', '.').' €</strong>
                                        </button>
                                   </h2>
                                   <div id="c'.$commandeNb.'" class="accordion-collapse collapse" aria-labelledby="b'.$commandeNb.'">
                                        <div class="accordion-body">
                                            <div class="card border-primary col-md-6 col-12 mb-4">
                                                <div class="card-header bg-primary text-white">
                                                    <div class="card-title">
                                                        <h4>Client</h4>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div>
                                                        Nom : <strong class="fw-bold">'.$value['user']['nom_users'].'</strong>
                                                    </div>
                                                    <div>
                                                        Prenom : <strong class="fw-bold">'.$value['user']['prenom_users'].'</strong>
                                                    </div>
                                                    <div>
                                                        Adresse : <strong class="fw-bold">'.$value['user']['adresse_users'].', '.$value['user']['nom_ville'].' - '.$value['user']['codePostal'].'</strong>
                                                    </div>
                                                    <div>
                                                        Numero : <strong class="fw-bold">+33'.$value['user']['tel_users'].'</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">';
                                        foreach($value as $itemKey => $item){
                                            if(isset($item['ingredients']) && is_int($itemKey)){
                                                $ingredientStr = '';
                                                foreach($item['ingredients'] as $ingredient){
                                                    if($ingredient != false){
                                                        $ingredientStr = $ingredientStr.'<li class="list-group-item">'.$ingredient['nom_ingredients'].'</li>';
                                                    }
                                                }
                                                echo '
                                                    <div class="card mx-2 mb-2 bg-secondary text-white col-12 col-md-5">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <h4>
                                                                    '.$item['nom_produits'].'
                                                                </h4>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <ul class="list-group ">
                                                                '.$ingredientStr.'
                                                            </ul>
                                                        </div>
                                                    </div>
                                                ';
                                            }
                                            elseif($itemKey != 'user' && $itemKey != 'prix_total'){
                                                echo '
                                                <div class="card mx-0 mb-2 bg-secondary text-white col-6 col-md-1">
                                                    <div class="card-body text-center py-5">
                                                        <div class="card-title ">
                                                            <h4>
                                                                '.$item['nom_produits'].'
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>';
                                            }
                                        }

                            echo '
                                            </div>
                                            <div class="d-grid">
                                                <button class="btn btn-outline-success" onclick="validateCommande('.$commandeNb.')">
                                                    Valider
                                                </button>
                                            </div>
                                        </div>
                                   </div>
                                </div>
                            ';
                        }
                    ?>
                </div>                
            </div>
        </div>
    </div>
</div>
<div class="d-flex mt-3 row row-col-1 row-col-md-1">
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h3 class="h3 text-center fw-bold">Commandes prise en charge</h3>
            </div>
            <div class="card-content">
                <div class="accordion row" id="commandeAccordion">
                    <?php
                        foreach($commandePriseEnCharge as $commandeNb => $value){
                            $commandeTime = strftime(' %A %d %b %Y à %H:%M', $commandeNb);
                            echo '
                                <div class="accordion-item ms-1 col-12">
                                   <h2 class="accordion-header ms-2 p-1" id="b'.$commandeNb.'">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c'.$commandeNb.'" aria-expanded="true" aria-controls="c'.$commandeNb.'">
                                            Commande N°'.$commandeNb.' <small class="text-muted ms-5">'.$commandeTime.'</small> <strong class="fw-bold ms-5" style="margin-left: auto;">'.number_format($value['prix_total'], 2, ',', '.').' €</strong>
                                        </button>
                                   </h2>
                                   <div id="c'.$commandeNb.'" class="accordion-collapse collapse" aria-labelledby="b'.$commandeNb.'">
                                        <div class="accordion-body">
                                            <div class="card border-primary col-md-6 col-12 mb-4">
                                                <div class="card-header bg-primary text-white">
                                                    <div class="card-title">
                                                        <h4>Client</h4>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div>
                                                        Nom : <strong class="fw-bold">'.$value['user']['nom_users'].'</strong>
                                                    </div>
                                                    <div>
                                                        Prenom : <strong class="fw-bold">'.$value['user']['prenom_users'].'</strong>
                                                    </div>
                                                    <div>
                                                        Adresse : <strong class="fw-bold">'.$value['user']['adresse_users'].', '.$value['user']['nom_ville'].' - '.$value['user']['codePostal'].'</strong>
                                                    </div>
                                                    <div>
                                                        Numero : <strong class="fw-bold">+33'.$value['user']['tel_users'].'</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">';
                                        foreach($value as $itemKey => $item){
                                            if(isset($item['ingredients']) && is_int($itemKey)){
                                                $ingredientStr = '';
                                                foreach($item['ingredients'] as $ingredient){
                                                    if($ingredient != false){
                                                        $ingredientStr = $ingredientStr.'<li class="list-group-item">'.$ingredient['nom_ingredients'].'</li>';
                                                    }
                                                }
                                                echo '
                                                    <div class="card mx-2 mb-2 bg-secondary text-white col-12 col-md-5">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <h4>
                                                                    '.$item['nom_produits'].'
                                                                </h4>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <ul class="list-group ">
                                                                '.$ingredientStr.'
                                                            </ul>
                                                        </div>
                                                    </div>
                                                ';
                                            }
                                            elseif($itemKey != 'user' && $itemKey != 'prix_total'){
                                                echo '
                                                <div class="card mx-0 mb-2 bg-secondary text-white col-6 col-md-1">
                                                    <div class="card-body text-center py-5">
                                                        <div class="card-title ">
                                                            <h4>
                                                                '.$item['nom_produits'].'
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>';
                                            }
                                        }

                            echo '
                                            </div>
                                            <div class="d-grid">
                                                <button class="btn btn-outline-success" onclick="deliverCommande('.$commandeNb.')">
                                                    Commande livrée
                                                </button>
                                            </div>
                                        </div>
                                   </div>
                                </div>
                            ';
                        }
                    ?>
                </div>                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

function validateCommande(id){
    var url = '../config/validateCommande.php?id_commande='+id;

    var request = new XMLHttpRequest();

    request.open('GET', url);
    request.send();
    request.onload = function(){
        location.reload();
    }
}

function deliverCommande(id){
    var url = '../config/deliverCommande.php?id_commande='+id;

    var request = new XMLHttpRequest();

    request.open('GET', url);
    request.send();
    request.onload = function(){
        location.reload();
    }
}

setTimeout(function(){
    location.reload();
}, 60000)

</script>
<?php
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

$starProductQuery = $bdd->connexion->prepare('SELECT categories.nom_categories, produits.nom_produits, `id_commande`, `identifiant_commande`, commande.id_produits, commande.id_ingredients, `id_users`, `prix_commande`, count(commande.id_produits) AS total FROM `commande` INNER JOIN produits ON produits.id_produits = commande.id_produits INNER JOIN categories ON categories.id_categories = produits.id_categorie GROUP BY id_produits');

if($starProductQuery->execute()){
    $starProduct = $starProductQuery->fetchAll();
}
else{
    var_dump($starProductQuery->errorInfo());
}

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

$panierMoyen = $panierMoyen / $nbPanier;



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
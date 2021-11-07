<?php

$catArray = $bdd->getCategoriesName();

if(isset($_POST['nom']) && !empty($_POST['nom'])){

    $imgPath = $_FILES['img']['name'];
    echo $imgPath;
    $identifiant = time();
    $nom = $_POST['nom'];

    $insertQuery = $bdd->connexion->prepare('INSERT INTO categories (identifiant_categories, nom_categories, img_categories) VALUES (:identifiant, :nom, :img)');
    $insertQuery->bindParam(':identifiant', $identifiant, PDO::PARAM_INT);
    $insertQuery->bindParam(':nom', $nom, PDO::PARAM_STR);
    $insertQuery->bindParam(':img', $imgPath, PDO::PARAM_STR);

    if($insertQuery->execute()){
        move_uploaded_file($_FILES['img']['tmp_name'], '../assets/img/'.$imgPath);
        header('Location: index.php?page=1');
    }
    else{
        echo '<div class="text-danger text-center h5">
            Une erreur est survenu : '.var_dump($insertQuery->errorInfo()).'
        </div>';
    }

}
?>


<div id="categoriesWrapper" class="w-100 d-flex bg-light" style="justify-content: center; align-items: center; margin-top: 70px;">
    <div class="w-75 card-deck row row-cols-1 row-cols-md-3 gap-3" style="justify-content: space-evenly;">
        <div class="card col">
            <h3 class="card-title text-left card-header">
                    Catégories
            </h3>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    
                <?php

                    foreach($catArray as $cat){
                        echo '
                            <li id="I'.$cat['identifiant_categories'].'" class="list-group-item d-flex" style="align-items: center;">
                                <h5 class="h5 me-auto">'.$cat['nom_categories'].'</h5> 
                                <button class="btn btn-outline-danger me-2">
                                    <span class="bi bi-trash" onclick="deleteItem('.$cat['identifiant_categories'].')"></span>
                                </button>
                                <button class="btn btn-primary">
                                    <span class="bi bi-pencil"></span>
                                </button>
                            </li>                            
                        ';
                    }
                ?>

                </ul>
            </div>
        </div>
        <div class="card col mx-2">
            <div class="card-body">
                <h3 class="card-title text-left">
                    Créer une nouvelle catégorie
                </h3>
                <form class="needs-validation" action="index.php?page=1" method="post" enctype="multipart/form-data">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="catName" placeholder="Nom" name="nom" required />
                        <label for="catName">Nom</label>
                    </div>
                    <div class="mb-2">
                        <label for="catImg" class="form-label">Ajouter une image</label>
                        <input id="catImg" name="img" class="form-control" type="file" accept="img/*" required /> 
                    </div>

                    <button class="btn btn-primary" type="submit">
                        Ajouter
                        <span class="bi bi-plus"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

function deleteItem(item){

    var itemDiv = document.querySelector('#I'+item);

    var deleteRequest = new XMLHttpRequest();
    var deleteUrl = "../config/deleteCategories.php?id="+item;

    deleteRequest.open('GET', deleteUrl);
    deleteRequest.send();

    deleteRequest.onload = function(){
        itemDiv.remove();
    }

}

</script>
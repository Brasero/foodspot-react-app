<?php
$horaire = '11:00 - 14:30 <br /> 18:00 - 22:00';
?>

<div class="w-100 border-top pt-3 p-4 mx-auto p-md-3 pb-5">
    <div style="max-width: 1024px;" class="mx-auto">
        <div class="row row-cols-1 row-cols-md-3 gap-3 gap-md-0">
            <div class="col my-auto">
                <div class="h3 text-center mb-4 ms-2">
                    Nous retrouver
                </div>
                <div class="card">
                    <iframe class="card-img" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2635.0836527466085!2d6.164285115715103!3d48.66564287926912!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x479499714044f4b9%3A0x537dd2d334aa202f!2sFood%20Spot!5e0!3m2!1sfr!2sfr!4v1637015691545!5m2!1sfr!2sfr" width="420" height="250" style="border: 0;" allowfullscreen="true" loading="lazy"></iframe>
                </div>
            </div>
            <div class="col mt-3 mt-auto mb-auto">
                <div class="h3 text-center ms-2">
                    Nos horaires
                </div>
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="d-flex list-group-item">
                                <span class="text-primary me-4 w-50">Lundi, Mardi, Mercredi, Jeudi, Samedi</span>
                                <div class="text-end ms-auto">
                                    <?= $horaire ?>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <span class="text-primary me-4">Vendredi, Dimanche</span>
                                <div class="text-end ms-auto">
                                    18:00 - 22:00
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col mt-auto mb-auto">
                <div class="h3 text-center mb-4 ms-2">
                    Nous contacter
                </div>
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <span class="bi bi-telephone text-primary fs-5 fw-bold me-4"></span>
                                09 51 53 38 36
                            </li>
                            <li class="list-group-item d-flex" style="align-items: center;">
                                <span class="bi bi-facebook fs-4 text-primary me-2"></span>
                                <a class="nav-link d-inline fw-bold text-primary" href="https://www.facebook.com/FoodSportVelodrome/">FaceBook</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-light d-grid border-top pt-3 p-1 mt-2">
        <div class="mx-auto text-center d-flex" style="align-items: center;">
            Site créé par<a href="http://www.linkedin.com/in/brandon-ricci-76ab78144" class="nav-link text-secondary mx-0">Ricci Brandon</a> - 2021 - <a href="https://fr.wikipedia.org/wiki/Plagiat" class="nav-link text-secondary mx-0">Tous droits déposés.</a>
        </div>
    </div>
</div>
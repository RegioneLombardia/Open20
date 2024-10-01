<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);
?>

<div class="agid-carousel-container">
    <div class="it-carousel-wrapper it-carousel-landscape-abstract m-0">
        <div class="it-carousel-all owl-carousel">
            <!--inizio primo elemento-->
            <div class="it-single-slide-wrapper d-flex flex-column-reverse flex-sm-row">
                <div class="it-text-slider-wrapper-outside">
                    <div class="card-wrapper h-100">
                    <div class="card">
                        <div class="card-body pb-0 flex-grow-1 d-flex flex-column">
                        <div class="category-top">
                            <!-- category heading-->
                            <a class="category" href="#">Categoria</a>
                            <!-- category data-->
                            <span class="data">31/10/2020</span>
                        </div>
                            <a href="#" class="card-link-title">
                                <h5 class="card-title big-heading text-black">
                                io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare
                                </h5>
                            </a>
                            <p class="text-serif ">io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare </p>

                            <div class="chip-container d-flex">
                                <?php
                                    echo $this->render(
                                    '@vendor/open20/design/src/components/agid/views/agid-tag',[
                                        'tagName' => 'Argomento 1',
                                        'tagColor' => 'chip-primary',
                                    ]
                                    );
                                ?>
                                <?php
                                    echo $this->render(
                                    '@vendor/open20/design/src/components/agid/views/agid-tag',[
                                        'tagName' => 'Argomento 2',
                                        'tagColor' => 'chip-primary',
                                    ]
                                    );
                                ?>
                            </div>


                            <a class="read-more flex-grow-1 align-items-end w-auto justify-content-start" href="#">
                                <span class="text">Leggi di più →</span>
                                <svg class="icon">
                                    <use xlink:href="/bootstrap-italia/dist/svg/sprite.svg#it-arrow-right"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                    </div>
                </div>
                <a href="#">
                    <div class="img-responsive-wrapper">
                    <div class="img-responsive">
                        <div class="img-wrapper"><img src="https://picsum.photos/1600/900?random=61" title="img title" alt="imagealt"></div>
                    </div>
                    </div>
                </a>
            </div>
            <!--fine primo elemento-->  
            <!--inizio secondo elemento-->
            <div class="it-single-slide-wrapper">
                <div class="it-text-slider-wrapper-outside">
                    <div class="card-wrapper h-100">
                    <div class="card">
                        <div class="card-body pb-0 flex-grow-1 d-flex flex-column">
                        <div class="category-top">
                            <!-- category heading-->
                            <a class="category" href="#">Categoria</a>
                            <!-- category data-->
                            <span class="data">31/10/2020</span>
                        </div>
                            <a href="#" class="card-link-title">
                                <h5 class="card-title big-heading text-black">
                                io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare
                                </h5>
                            </a>
                            <p class="text-serif ">io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare </p>

                            <div class="chip-container d-flex">
                                <?php
                                    echo $this->render(
                                    '@vendor/open20/design/src/components/agid/views/agid-tag',[
                                        'tagName' => 'Argomento 1',
                                        'tagColor' => 'chip-primary',
                                    ]
                                    );
                                ?>
                                <?php
                                    echo $this->render(
                                    '@vendor/open20/design/src/components/agid/views/agid-tag',[
                                        'tagName' => 'Argomento 2',
                                        'tagColor' => 'chip-primary',
                                    ]
                                    );
                                ?>
                            </div>


                            <a class="read-more flex-grow-1 align-items-end w-auto justify-content-start" href="#">
                                <span class="text">Leggi di più →</span>
                                <svg class="icon">
                                    <use xlink:href="/bootstrap-italia/dist/svg/sprite.svg#it-arrow-right"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                    </div>
                </div>
                <a href="#">
                    <div class="img-responsive-wrapper">
                    <div class="img-responsive">
                        <div class="img-wrapper"><img src="https://picsum.photos/1600/900?random=61" title="img title" alt="imagealt"></div>
                    </div>
                    </div>
                </a>
            </div>
            <!--fine secondo elemento-->         
        </div>
    </div>
</div>

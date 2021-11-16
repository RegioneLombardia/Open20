<?php

use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);


$image = (isset($image)) ? $image :  'https://picsum.photos/1600/900?random=5';

$paidLabel = ($isPaid) ? 'a pagamento' : '';
$availableSeatsLabel = (isset($availableSeats)) ? '(disponibili' . ' ' . $availableSeats . ' ' . 'posti)' : '';
$infos = $type . ', ' . $paidLabel . ' ' . $availableSeatsLabel;

?>

<div class="scope-eventi-container d-flex flex-column flex-lg-row">
    <div class="d-block flex-column-reverse flex-lg-row h-100">

        <?=
            $this->render(
                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-event',
                [
                    'image' => $image,
                    'dateHourStart' => $dateHourStart,
                    'inScope' => true,
                    'url' => $url,
                    'urlCta' => $urlCta,
                ]
            );
        ?>
    </div>
    <div class="d-flex flex-column ml-0 ml-lg-3 text-container">
        <h3 class="h5 font-weight-bold mt-3 mt-lg-0 mb-0"><?= $title ?></h3>
        <p class="mb-0"><?= $summary ?></p>
        <p class="location"><?= $location . ' ' . $address . ', ' . $addressNumber . ' ' . $cap . ' ' . $city . ' (' . $province . ')'  ?></p>
        <p class="infos"><?= $infos ?></p>
        <p class="descrizione"><?= $description ?></p>
    </div>
</div>
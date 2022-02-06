<?php
require_once "baza.php";

// Dodavanje zapisa
if (isset($_POST['dodaj-zapis']) && isset($_POST['zapocinje']) && isset($_POST['zavrsava']) && isset($_POST['dan']) && isset($_POST['broj-studenata']) && isset($_POST['vrsta']) && isset($_POST['dvorana']) && isset($_POST['kolegij'])) {
    $zapocinje = $_POST['zapocinje'];
    $zavrsava = $_POST['zavrsava'];
    $dan = $_POST['dan'];
    $broj = $_POST['broj-studenata'];
    $vrsta = $_POST['vrsta'];
    $dvorana = $_POST['dvorana'];
    $kolegij = $_POST['kolegij'];

    $rezultat = Baza::upit("INSERT INTO zapis (pocetak_z, zapocinje, zavrsava, dan, br_studenata_zapis, fk_vrsta, fk_dvorana, fk_raspored, fk_kolegij) VALUES (CURRENT_TIMESTAMP, '$zapocinje', '$zavrsava', '$dan', $broj, $vrsta, $dvorana, 1, $kolegij);");

    if (!empty($rezultat) && strlen(trim($rezultat)) > 1) {
        $porukaGreske = $rezultat;
    }
}


if (isset($_POST['obrisi-zapis'])) {
    $id = $_POST['obrisi-zapis'];

    $rezultat = Baza::upit("UPDATE zapis SET kraj_z = CURRENT_TIMESTAMP WHERE id_zapis = $id");

    if (empty($rezultat)) {
        $porukaGreske = $rezultat;
    }
}

// Dodavanje biljeske
if (isset($_POST['dodaj-biljesku']) && isset($_POST['tekst'])) {
    $tekst = $_POST['tekst'];
    $zapisId = $_POST['zapis-biljeska'];

    Baza::upit("INSERT INTO biljeske (tekst, pocetak_b, fk_zapis) VALUES ('$tekst', CURRENT_TIMESTAMP, $zapisId)");
}

// Brisanje biljeske
if (isset($_POST['obrisi-biljesku'])) {
    $id = $_POST['obrisi-biljesku'];

    $rezultat = Baza::upit("UPDATE biljeske SET kraj_b = CURRENT_TIMESTAMP WHERE id_biljeske = $id");

    if (empty($rezultat)) {
        $porukaGreskeBiljeska = $rezultat;
    }
}


if (isset($_POST['uredi-biljesku-spremi']) && isset($_POST['tekst'])) {
    $id = $_POST['uredi-biljesku-spremi'];
    $noviTekst = $_POST['tekst'];
    $zapisId = $_POST['zapis-biljeska'];

    $rezultat = Baza::upit("UPDATE biljeske SET kraj_b = CURRENT_TIMESTAMP WHERE id_biljeske = $id");

    $rezultat2 = Baza::upit("INSERT INTO biljeske (tekst, pocetak_b, fk_zapis) VALUES ('$noviTekst', CURRENT_TIMESTAMP, $zapisId)");

    $porukaGreskeBiljeska = '';

    if (empty($rezultat)) {
        $porukaGreskeBiljeska .= $rezultat . '<br/>';
    }

    if (empty($rezultat2)) {
        $porukaGreskeBiljeska .= $rezultat . '<br/>';
    }
}


$sviZapisi = Baza::select("SELECT id_zapis AS id, pocetak_z AS pocetak, kraj_z AS kraj, zapocinje, zavrsava, dan, br_studenata_zapis AS broj, fk_vrsta AS vrsta, fk_dvorana AS dvorana, fk_raspored AS raspored, fk_kolegij AS kolegij FROM zapis ORDER BY kraj_z DESC;");
$sviAktivniZapisi = Baza::select("SELECT id_zapis AS id, pocetak_z AS pocetak, kraj_z AS kraj, zapocinje, zavrsava, dan, br_studenata_zapis AS broj, fk_vrsta AS vrsta, fk_dvorana AS dvorana, fk_raspored AS raspored, fk_kolegij AS kolegij FROM zapis WHERE kraj_z IS NOT NULL;");

$odabraniZapis = $_POST['zapis-biljeska'] ?? null;

if ($odabraniZapis) {
    $sveBiljeske = Baza::select("SELECT id_biljeske AS id, pocetak_b AS pocetak, kraj_b AS kraj, tekst, fk_zapis AS zapis FROM biljeske WHERE fk_zapis = $odabraniZapis ORDER BY kraj_b DESC;");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semestri/kolegiji</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Raspored sati</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Početna</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="semestri-kolegiji.php">Semestri/kolegiji</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="zapisi-biljeske.php">Zapisi/bilješke</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <div class="px-4 py-5 my-5 text-center">
        <h1 class="display-5 fw-bold">Zapisi i bilješke</h1>
    </div>

    <div class="container container-fluid">
        <div class="row">
            <div class="col w-25 ">

                <div class="mb-5">
                    <h2>Zapisi</h2>

                    <ul class="list-group">
                        <?php
                        foreach ($sviZapisi as $zapis) {
                            $pocetak = (new DateTime($zapis->pocetak))->format('d.m.Y. H:i');
                            $kraj = $zapis->kraj ? (new DateTime($zapis->kraj))->format('d.m.Y. H:i') : null;

                            $pocetakSat = (new DateTime($zapis->zapocinje))->format('H:i');
                            $krajSat = (new DateTime($zapis->zavrsava))->format('H:i');

                            $dan = $zapis->dan;

                            $brojStudenata = $zapis->broj;

                            $vrsta = $zapis->vrsta;
                            $vrsta = Baza::select("SELECT naziv_vrsta as naziv FROM vrsta_nastave WHERE id_vrsta = $vrsta LIMIT 1;");
                            $vrsta = $vrsta[0]->naziv;

                            $dvorana = $zapis->dvorana;
                            $dvorana = Baza::select("SELECT naziv, kat, br_studenata_dvorana AS kapacitet FROM dvorana WHERE id_dvorana = $dvorana LIMIT 1;");
                            $dvoranaNaziv = $dvorana[0]->naziv;
                            $dvoranaKat = $dvorana[0]->kat;
                            $dvoranaKapacitet = $dvorana[0]->kapacitet;

                            $kolegij = $zapis->kolegij;
                            $kolegij = Baza::select("SELECT naziv_kolegija as naziv FROM kolegij WHERE id_kolegij = $kolegij LIMIT 1;");
                            $kolegij = $kolegij[0]->naziv;

                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold"><?php echo $kolegij; ?></div>

                                    <ul style="list-style-type: none; padding: 0;">
                                        <li>Vrsta: <?php echo $vrsta; ?></li>
                                        <li><?php echo $dan; ?>, <?php echo $pocetakSat; ?> - <?php echo $krajSat; ?></li>
                                        <li>Dvorana <?php echo $dvoranaNaziv; ?> (kat: <?php echo $dvoranaKat; ?>)</li>
                                        <li>Popunjenost dvorane: <?php echo $brojStudenata; ?> / <?php echo $dvoranaKapacitet; ?></li>

                                    </ul>


                                    <br />
                                    Vrijedi od <?php echo $pocetak; ?>
                                    <?php
                                    if ($kraj) {
                                    ?>
                                        <br /> do <?php echo $kraj; ?>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div>
                                    <?php
                                    if (!$kraj || $kraj == null) {
                                    ?>
                                        <form method="POST" class="row g-3">
                                            <input type="hidden" name="obrisi-zapis" value="<?php echo $zapis->id; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Obriši</button>
                                        </form>

                                        &nbsp;

                                        <form method="POST" class="row g-3">
                                            <input type="hidden" name="zapis-biljeska" value="<?php echo $zapis->id; ?>">
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Bilješke</button>
                                        </form>
                                    <?php
                                    }
                                    ?>


                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="mb-5">
                    <?php if (!empty($porukaGreske)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <b>Dogodila se greška!</b> <br />
                            <?php echo $porukaGreske; ?>
                        </div>
                    <?php } ?>

                    <h2>Dodaj zapis</h2>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="zapocinje" class="form-label">Početak (h)</label>
                            <input type="time" class="form-control" id="zapocinje" name="zapocinje">
                        </div>

                        <div class="mb-3">
                            <label for="zavrsava" class="form-label">Kraj (h)</label>
                            <input type="time" class="form-control" id="zavrsava" name="zavrsava">
                        </div>

                        <div class="mb-3">
                            <label for="dan" class="form-label">Dan</label>
                            <select class="form-select" id="dan" name="dan">
                                <option value="Ponedjeljak">Ponedjeljak</option>
                                <option value="Utorak">Utorak</option>
                                <option value="Srijeda">Srijeda</option>
                                <option value="Četvrtak">Četvrtak</option>
                                <option value="Petak">Petak</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="broj-studenata" class="form-label">Broj studenata</label>
                            <input type="number" min="1" max="300" class="form-control" id="broj-studenata" name="broj-studenata">
                        </div>

                        <div class="mb-3">
                            <label for="vrsta" class="form-label">Vrsta nastave</label>
                            <select class="form-select" id="vrsta" name="vrsta">
                                <?php

                                foreach (Baza::select('SELECT id_vrsta, naziv_vrsta FROM vrsta_nastave;') as $v) { ?>
                                    <option value="<?php echo $v->id_vrsta; ?>"><?php echo $v->naziv_vrsta; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="dvorana" class="form-label">Dvorana</label>
                            <select class="form-select" id="dvorana" name="dvorana">
                                <?php

                                foreach (Baza::select('SELECT id_dvorana, naziv, br_studenata_dvorana AS broj FROM dvorana;') as $d) { ?>
                                    <option value="<?php echo $d->id_dvorana; ?>"><?php echo $d->naziv; ?> (kapacitet: <?php echo $d->broj; ?>)</option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kolegij" class="form-label">Kolegij</label>
                            <select class="form-select" id="kolegij" name="kolegij">
                                <?php

                                foreach (Baza::select('SELECT id_kolegij, naziv_kolegija FROM kolegij WHERE kraj_k IS NULL;') as $k) { ?>
                                    <option value="<?php echo $k->id_kolegij; ?>"><?php echo $k->naziv_kolegija; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <input type="hidden" name="dodaj-zapis" value="1">

                        <button type="submit" class="btn btn-primary">Dodaj</button>
                    </form>
                </div>

            </div>


            <div class="col">
                <div class="mb-3">

                    <h2>Bilješke</h2>


                    <div class="mt-3">
                        <?php
                        if (!isset($_POST['zapis-biljeska'])) {
                            echo '<i>Odaberite zapis za prikaz njegovih bilješki!</i>';
                        } else {
                        ?>


                            <ul class="list-group">
                                <?php
                                foreach ($sveBiljeske as $b) {
                                    $pocetak = (new DateTime($b->pocetak))->format('d.m.Y. H:i');
                                    $kraj = $b->kraj ? (new DateTime($b->kraj))->format('d.m.Y. H:i') : null;

                                ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <blockquote class="blockquote">
                                                <?php echo $b->tekst; ?>
                                            </blockquote>


                                            Vrijedi od <?php echo $pocetak; ?>
                                            <?php
                                            if ($kraj) {
                                            ?>
                                                <br /> do <?php echo $kraj; ?>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div>
                                            <?php
                                            if (!$kraj || $kraj == null) {
                                            ?>
                                                <form method="POST" class="row g-3">
                                                    <?php if (isset($_POST['zapis-biljeska'])) { ?>
                                                        <input type="hidden" name="zapis-biljeska" value="<?php echo $_POST['zapis-biljeska']; ?>">
                                                    <?php } ?>

                                                    <input type="hidden" name="obrisi-biljesku" value="<?php echo $b->id; ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">Obriši</button>
                                                </form>

                                                &nbsp;

                                                <form method="POST" class="row g-3">
                                                    <?php if (isset($_POST['zapis-biljeska'])) { ?>
                                                        <input type="hidden" name="zapis-biljeska" value="<?php echo $_POST['zapis-biljeska']; ?>">
                                                    <?php } ?>

                                                    <input type="hidden" name="uredi-biljesku" value="<?php echo $b->id; ?>">
                                                    <button type="submit" class="btn btn-outline-primary btn-sm">Uredi</button>
                                                </form>
                                            <?php
                                            }
                                            ?>


                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>

                            <div class="mb-5 mt-5">
                                <?php if (!empty($porukaGreskeBiljeska)) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <b>Dogodila se greška!</b> <br />
                                        <?php echo $porukaGreskeBiljeska; ?>
                                    </div>
                                <?php } ?>

                                <h2>
                                    <?php echo isset($_POST['uredi-biljesku']) ? 'Uredi' : 'Dodaj'; ?>
                                    bilješku
                                </h2>

                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="tekst" class="form-label">Tekst</label>

                                        <?php
                                        $trenutniTekst = '';
                                        if (isset($_POST['uredi-biljesku'])) {
                                            $idBiljeske = $_POST['uredi-biljesku'];

                                            $trazenaBiljeska = Baza::select("SELECT tekst FROM biljeske WHERE id_biljeske = $idBiljeske LIMIT 1;");
                                            $trenutniTekst = $trazenaBiljeska[0]->tekst;
                                        }
                                        ?>

                                        <?php if (isset($_POST['uredi-biljesku'])) { ?>
                                            <input type="hidden" name="uredi-biljesku-spremi" value="<?php echo $idBiljeske ?? ''; ?>">
                                        <?php } else { ?>
                                            <input type="hidden" name="dodaj-biljesku" value="1">
                                        <?php } ?>

                                        <?php if (isset($_POST['zapis-biljeska'])) { ?>
                                            <input type="hidden" name="zapis-biljeska" value="<?php echo $_POST['zapis-biljeska']; ?>">
                                        <?php } ?>

                                        <textarea type="text" class="form-control" id="tekst" name="tekst" rows="10"><?php echo $trenutniTekst; ?></textarea>
                                    </div>



                                    <button type="submit" class="btn btn-primary">
                                        <?php echo isset($_POST['uredi-biljesku']) ? 'Spremi' : 'Dodaj'; ?>
                                    </button>
                                </form>
                            </div>

                        <?php
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>
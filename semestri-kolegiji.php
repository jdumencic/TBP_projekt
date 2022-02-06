<?php
require_once "baza.php";

// Dodavanje semestra
if (isset($_POST['dodaj-semestar']) && isset($_POST['naziv'])) {
    $naziv = $_POST['naziv'];

    Baza::upit("INSERT INTO semestar (naziv_semestra, pocetak_s) VALUES ('{$naziv}', CURRENT_TIMESTAMP)");
}

// Brisanje semestra
if (isset($_POST['obrisi-semestar'])) {
    $id = $_POST['obrisi-semestar'];

    Baza::upit("UPDATE semestar SET kraj_s = CURRENT_TIMESTAMP WHERE id_semestar = $id");
}

// Uređivanje semestra
if (isset($_POST['uredi-semestar-spremi']) && isset($_POST['naziv'])) {
    $id = $_POST['uredi-semestar-spremi'];
    $noviNaziv = $_POST['naziv'];

    Baza::upit("UPDATE semestar SET kraj_s = CURRENT_TIMESTAMP WHERE id_semestar = $id");

    Baza::upit("INSERT INTO semestar (naziv_semestra, pocetak_s) VALUES ($noviNaziv, CURRENT_TIMESTAMP)");
}

// Dodavanje kolegija
if (isset($_POST['dodaj-kolegij']) && isset($_POST['naziv'])) {
    $naziv = $_POST['naziv'];
    $semestar = $_POST['kolegij-semestar'];

    Baza::upit("INSERT INTO kolegij (naziv_kolegija, pocetak_k, fk_semestar) VALUES ('{$naziv}', CURRENT_TIMESTAMP, $semestar)");
}

// Brisanje kolegija
if (isset($_POST['obrisi-kolegij'])) {
    $id = $_POST['obrisi-kolegij'];

    Baza::upit("UPDATE kolegij SET kraj_k = CURRENT_TIMESTAMP WHERE id_kolegij = $id");
}

// Uređivanje kolegija
if (isset($_POST['uredi-kolegij-spremi']) && isset($_POST['naziv'])) {
    $id = $_POST['uredi-kolegij-spremi'];
    $noviNaziv = $_POST['naziv'];
    $semestar = $_POST['kolegij-semestar'];

    Baza::upit("UPDATE kolegij SET kraj_k = CURRENT_TIMESTAMP WHERE id_kolegij = $id");

    Baza::upit("INSERT INTO kolegij (naziv_kolegija, pocetak_k, fk_semestar) VALUES ($noviNaziv, CURRENT_TIMESTAMP, $semestar)");
}


$sviSemestri = Baza::select("SELECT id_semestar AS id, pocetak_s AS pocetak, kraj_s AS kraj, naziv_semestra AS naziv FROM semestar ORDER BY kraj_s DESC;");
$sviAktivniSemestri = Baza::select("SELECT id_semestar AS id, pocetak_s AS pocetak, kraj_s AS kraj, naziv_semestra AS naziv FROM semestar WHERE kraj_S IS NULL;");

$odabraniSemestar = $_POST['kolegij-semestar'] ?? null;

if ($odabraniSemestar) {
    $sviKolegiji = Baza::select("SELECT naziv_kolegija AS naziv, id_kolegij AS id, pocetak_k AS pocetak, kraj_k AS kraj FROM kolegij WHERE fk_semestar = $odabraniSemestar ORDER BY kraj_k DESC;");
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
                        <a class="nav-link active" href="semestri-kolegiji.php">Semestri/kolegiji</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="zapisi-biljeske.php">Zapisi/bilješke</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <div class="px-4 py-5 my-5 text-center">
        <h1 class="display-5 fw-bold">Semestri i kolegiji</h1>
    </div>

    <div class="container container-fluid">
        <div class="row">
            <div class="col w-25 ">

                <div class="mb-5">
                    <h2>Semestri</h2>

                    <ul class="list-group">
                        <?php
                        foreach ($sviSemestri as $semestar) {
                            $pocetak = (new DateTime($semestar->pocetak))->format('d.m.Y. H:i');
                            $kraj = $semestar->kraj ? (new DateTime($semestar->kraj))->format('d.m.Y. H:i') : null;

                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold"><?php echo $semestar->naziv; ?></div>
                                    od <?php echo $pocetak; ?>
                                    <?php
                                    if ($kraj) {
                                    ?>
                                        <br /> do <?php echo $kraj; ?>
                                    <?php
                                    } else {
                                    ?>
                                        <br /> <i>Trenutno se izvodi</i>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div>
                                    <?php
                                    if (!$kraj || $kraj == null) {
                                    ?>
                                        <form method="POST" class="row g-3">
                                            <input type="hidden" name="obrisi-semestar" value="<?php echo $semestar->id; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Obriši</button>
                                        </form>

                                        &nbsp;

                                        <form method="POST" class="row g-3">
                                            <input type="hidden" name="uredi-semestar" value="<?php echo $semestar->id; ?>">
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Uredi</button>
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
                    <h2>
                        <?php echo isset($_POST['uredi-semestar']) ? 'Uredi' : 'Dodaj'; ?>
                        semestar
                    </h2>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="naziv" class="form-label">Naziv</label>

                            <?php
                            $trenutniSemestarNaziv = '';
                            if (isset($_POST['uredi-semestar'])) {
                                $idSemestra = $_POST['uredi-semestar'];

                                $trazeniSemestar = Baza::select("SELECT naziv_semestra FROM semestar WHERE id_semestar = $idSemestra LIMIT 1;");
                                $trenutniSemestarNaziv = $trazeniSemestar[0]->naziv_semestra;
                            }
                            ?>

                            <?php if (isset($_POST['uredi-semestar'])) { ?>
                                <input type="hidden" name="uredi-semestar-spremi" value="<?php echo $idSemestra ?? ''; ?>">
                            <?php } ?>

                            <input type="text" class="form-control" id="naziv" name="naziv" value="<?php echo $trenutniSemestarNaziv; ?>">
                        </div>

                        <input type="hidden" name="dodaj-semestar" value="1">

                        <button type="submit" class="btn btn-primary">
                            <?php echo isset($_POST['uredi-semestar']) ? 'Spremi' : 'Dodaj'; ?>
                        </button>
                    </form>
                </div>

            </div>

            <div class="col">
                <div class="mb-3">

                    <h2>Kolegiji</h2>

                    <form method="POST">
                        <div class="mt-1 row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="kolegij-semestar" class="col-form-label">Semestar</label>
                            </div>

                            <div class="col-auto">
                                <select class="form-select" id="kolegij-semestar" name="kolegij-semestar">
                                    <?php foreach ($sviAktivniSemestri as $s) { ?>
                                        <option value="<?php echo $s->id; ?>"><?php echo $s->naziv; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Odaberi</button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3">
                        <?php
                        if (!isset($_POST['kolegij-semestar'])) {
                            echo '<i>Odaberite semestar za prikaz njegovih kolegija!</i>';
                        } else {
                        ?>


                            <ul class="list-group">
                                <?php
                                foreach ($sviKolegiji as $kolegij) {
                                    $pocetak = (new DateTime($kolegij->pocetak))->format('d.m.Y. H:i');
                                    $kraj = $kolegij->kraj ? (new DateTime($kolegij->kraj))->format('d.m.Y. H:i') : null;

                                ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-bold"><?php echo $kolegij->naziv; ?></div>
                                            od <?php echo $pocetak; ?>
                                            <?php
                                            if ($kraj) {
                                            ?>
                                                <br /> do <?php echo $kraj; ?>
                                            <?php
                                            } else {
                                            ?>
                                                <br /> <i>Trenutno se izvodi</i>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div>
                                            <?php
                                            if (!$kraj || $kraj == null) {
                                            ?>
                                                <form method="POST" class="row g-3">
                                                    <?php if (isset($_POST['kolegij-semestar'])) { ?>
                                                        <input type="hidden" name="kolegij-semestar" value="<?php echo $_POST['kolegij-semestar']; ?>">
                                                    <?php } ?>

                                                    <input type="hidden" name="obrisi-kolegij" value="<?php echo $kolegij->id; ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">Obriši</button>
                                                </form>

                                                &nbsp;

                                                <form method="POST" class="row g-3">
                                                    <?php if (isset($_POST['kolegij-semestar'])) { ?>
                                                        <input type="hidden" name="kolegij-semestar" value="<?php echo $_POST['kolegij-semestar']; ?>">
                                                    <?php } ?>

                                                    <input type="hidden" name="uredi-kolegij" value="<?php echo $kolegij->id; ?>">
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
                                <h2>
                                    <?php echo isset($_POST['uredi-kolegij']) ? 'Uredi' : 'Dodaj'; ?>
                                    kolegij
                                </h2>

                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="naziv" class="form-label">Naziv</label>

                                        <?php
                                        $trenutniKolegijNaziv = '';
                                        if (isset($_POST['uredi-kolegij'])) {
                                            $idKolegija = $_POST['uredi-kolegij'];

                                            $trazeniKolegij = Baza::select("SELECT naziv_kolegija FROM kolegij WHERE id_kolegij = $idKolegija LIMIT 1;");
                                            $trenutniKolegijNaziv = $trazeniKolegij[0]->naziv_kolegija;
                                        }
                                        ?>

                                        <?php if (isset($_POST['uredi-kolegij'])) { ?>
                                            <input type="hidden" name="uredi-kolegij-spremi" value="<?php echo $idKolegija ?? ''; ?>">
                                        <?php } ?>

                                        <?php if (isset($_POST['kolegij-semestar'])) { ?>
                                            <input type="hidden" name="kolegij-semestar" value="<?php echo $_POST['kolegij-semestar']; ?>">
                                        <?php } ?>

                                        <input type="text" class="form-control" id="naziv" name="naziv" value="<?php echo $trenutniKolegijNaziv; ?>">
                                    </div>

                                    <input type="hidden" name="dodaj-kolegij" value="1">

                                    <button type="submit" class="btn btn-primary">
                                        <?php echo isset($_POST['uredi-kolegij']) ? 'Spremi' : 'Dodaj'; ?>
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
<?php
require_once "baza.php";

$mapiranjeVrsta = [
  'predavanje' => '#e8f5e9',
  'seminar' => '#e3f2fd',
  'labos' => '#fbe9e7',
];

$mapiranjeVrstaRub = [
  'predavanje' => '#4caf50',
  'seminar' => '#2196f3',
  'labos' => '#ff5722',
];

$sati = [
  '8:00',
  '8:30',
  '9:00',
  '9:30',
  '10:00',
  '10:30',
  '11:00',
  '11:30',
  '12:00',
  '12:30',
  '13:00',
  '13:30',
  '14:00',
  '14:30',
  '15:00',
  '15:30',
  '16:00',
  '16:30',
  '17:00',
  '17:30',
  '18:00',
  '18:30',
  '19:00',
  '19:30',
  '20:00',
];

$mapiranjeDana = [
  'Ponedjeljak' => 0,
  'Utorak' => 1,
  'Srijeda' => 2,
  'Četvrtak' => 3,
  'Cetvrtak' => 3,
  'Petak' => 4,
];

$mapiranjeSata = [
  '8,0' => 0,
  '8,30' => 1,
  '9,0' => 2,
  '9,30' => 3,
  '10,0' => 4,
  '10,30' => 5,
  '11,0' => 6,
  '11,30' => 7,
  '12,0' => 8,
  '12,30' => 9,
  '13,0' => 10,
  '13,30' => 11,
  '14,0' => 12,
  '14,30' => 13,
  '15,0' => 14,
  '15,30' => 15,
  '16,0' => 16,
  '16,30' => 17,
  '17,0' => 18,
  '17,30' => 19,
  '18,0' => 20,
  '18,30' => 21,
  '19,0' => 22,
  '19,30' => 23,
  '20,0' => 24,
];

$stavkeRasporeda = Baza::select("SELECT Z.zapocinje, Z.zavrsava, Z.zavrsava - Z.zapocinje AS trajanje, Z.dan, Z.br_studenata_zapis, VN.naziv_vrsta, D.naziv, D.br_studenata_dvorana, K.naziv_kolegija FROM zapis Z JOIN vrsta_nastave VN ON Z.fk_vrsta = VN.id_vrsta JOIN dvorana D ON Z.fk_dvorana = D.id_dvorana JOIN kolegij K ON Z.fk_kolegij = K.id_kolegij WHERE kraj_z IS NULL;");
$procesiraneStavke = [];

foreach ($stavkeRasporeda as $stavka) {
  $procesiraneStavke[] = (object) [
    'predmet' => $stavka->naziv_kolegija,
    'ljudi' => $stavka->br_studenata_dvorana,
    'dan' => $mapiranjeDana[$stavka->dan],
    'dvorana' => $stavka->naziv,
    'trajanje' => (int) explode(':', $stavka->trajanje)[0] * 2 + (strpos($stavka->zavrsava, '30') ? 1 : 0),
    'pocetak' => implode(',', [(int) explode(':', $stavka->zapocinje)[0], (int) explode(':', $stavka->zapocinje)[1]]),
    'vrsta' => $stavka->naziv_vrsta,
  ];
}

$podaci = [];

for ($i = 0; $i < 25; $i++) {
  $podaci[] = [[], [], [], [], []];
}

foreach ($procesiraneStavke as $trenutnaStavka) {
  $praviPocetak = $mapiranjeSata[$trenutnaStavka->pocetak];

  for ($i = 0; $i < $trenutnaStavka->trajanje; $i++) {
    $podaci[$praviPocetak + $i][$trenutnaStavka->dan] = [
      'predmet' => $trenutnaStavka->predmet,
      'ljudi' => $trenutnaStavka->ljudi,
      'dvorana' => $trenutnaStavka->dvorana,
      'vrsta' => $trenutnaStavka->vrsta,
    ];
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Početna stranica</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  <style>
    table {
      table-layout: fixed;
    }
  </style>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Raspored sati</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Početna</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="semestri-kolegiji.php">Semestri/kolegiji</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="zapisi-biljeske.php">Zapisi/bilješke</a>
          </li>
        </ul>

      </div>
    </div>
  </nav>


  <div class="px-4 py-5 my-5 text-center">
    <h1 class="display-5 fw-bold">Raspored sati</h1>
  </div>

  <div class="container-fluid mb-5">
    <table class="table table-hover">
      <thead class="thead-dark">
        <tr>
          <th>Sat</th>
          <th>Ponedjeljak</th>
          <th>Utorak</th>
          <th>Srijeda</th>
          <th>Četvrtak</th>
          <th>Petak</th>
        </tr>
      </thead>
      <tbody>
        <?php

        $i = 0;
        foreach ($podaci as $dan) {
          echo '<tr>';

          $trenutniSat = $sati[$i];
          echo "<td style='font-weight: bold;'>{$trenutniSat}</td>";

          foreach ($dan as $sat) {
            if (!$sat || empty($sat)) {
              echo '<td></td>';
            } else {
              $brojLjudi = $sat['ljudi'];
              $predmet = $sat['predmet'];
              $dvorana = $sat['dvorana'];

              $boja = $mapiranjeVrsta[$sat['vrsta']];
              $bojaRub = $mapiranjeVrstaRub[$sat['vrsta']];
              $vrstaSata = $sat['vrsta'];

              echo "<td style='border-left: 3px solid {$bojaRub}; background: {$boja}; color: black;' >{$predmet} <br /> <small>{$vrstaSata} <br/> {$dvorana}, osoba: {$brojLjudi} </small></td>";
            }
          }

          echo '</tr>';
          $i++;
        }

        ?>
      </tbody>
    </table>
  </div>
</body>

</html>
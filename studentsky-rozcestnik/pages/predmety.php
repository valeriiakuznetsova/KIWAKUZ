<?php
include('../includes/header.php');
require_once "../db/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["user"])) {
  $code = trim($_POST["code"]);
  $name = trim($_POST["name"]);
  $description = trim($_POST["description"]);  $tip = trim($_POST["tip"]);

  if ($code && $name && $description) {
    $stmt = $conn->prepare("INSERT INTO subjects (code, name, description, tip) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $code, $name, $description, $tip);
    $stmt->execute();
    header("Location: predmety.php");
    exit();
  }
}
?>

<style>
  h2, h3, h4 {
    font-family: 'Caveat', cursive;
    color: #BE185D;
  }
  p, li, input, textarea, button {
    font-family: 'Nunito', sans-serif;
  }

  .flip-card {
    perspective: 1000px;
  }
  .flip-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transition: transform 0.7s;
    transform-style: preserve-3d;
  }
  .flip-card:hover .flip-inner {
    transform: rotateY(180deg);
  }
  .flip-front, .flip-back {
    backface-visibility: hidden;
    position: absolute;
    width: 100%;
   height: 100%;
    border-radius: 0.75rem;
    padding: 1.5rem;
   display: flex;
   flex-direction: column;
    justify-content: center;
  }
  .flip-front {
    background-color: #FFD8DE;
    color: #831843;
    border: none;
  }
  .flip-back {
    background-color: #FEBAC2;
    color: #881337;
    transform: rotateY(180deg);
    border: none;
  }
  .flip-wrapper {
    width: 100%;    height: 250px;
    position: relative;
  }
</style>

<section class="bg-white p-6 rounded-lg shadow max-w-7xl mx-auto">
  <h2 class="text-2xl font-bold mb-6 text-center">🌸 Předměty 🌸</h2>

  <?php if (isset($_SESSION["user"])): ?>
   <form method="POST" class="mb-8 p-8 bg-pink-50 rounded-lg shadow space-y-5 text-[17px]">
  <h3 class="text-2xl font-bold text-[#BE185D]" style="font-family: 'Caveat', cursive;">➕ Přidat nový předmět</h3>

      <input name="code" placeholder="Zkratka (např. KMA/MA1-E)" class="w-full border border-pink-300 p-2 rounded" required>
    <input name="name" placeholder="Název předmětu" class="w-full border border-pink-300 p-2 rounded" required>
      <input name="description" placeholder="Popis (krátký)" class="w-full border border-pink-300 p-2 rounded" required>
      <textarea name="tip" placeholder="Tipy ke studiu" rows="3" class="w-full border border-pink-300 p-2 rounded"></textarea>
      <button class="bg-[#FFD8DE] text-pink-800 px-4 py-2 rounded hover:brightness-95">Přidat</button>
    </form>
  <?php endif; ?>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php
 $predmety = [
  ["KIN/IS", "Informace a systémy", "Základy IS, logika, databáze, ERP systémy.", "Vysvětli si pojmy srozumitelně, zkoušej příklady v Excelu, kresli si modely."],
  ["KMA/MA1-E", "Matematika I", "Derivace, matice, rovnice, funkce.", "Procvičuj ručně každý typ příkladu, použij ChatGPT na ověření a zpětnou kontrolu."],
  ["KEK/MI1", "Mikroekonomie I", "Trh, poptávka, elasticita, rovnováha.", "Začni od základních pojmů, nauč se kreslit všechny typy grafů a chápat jejich logiku."],
  ["KPE/NP", "Nauka o podniku", "Typy firem, řízení, náklady, podnikové procesy.", "Udělej si přehledový souhrn, trénuj otevřené otázky přes vlastní příklady."],
  ["KPE/UM", "Úvod do managementu", "Plánování, řízení lidí, motivace, rozhodování.", "Přemýšlej o příkladech z praxe, porovnej různé styly řízení a jejich výhody."],
  ["KIN/ZP", "Základy programování", "Proměnné, cykly, podmínky, funkce.", "Zkoušej si každý úkol psát od nuly, poslouchej na cvičeních, zeptej se, když něčemu nerozumíš."],
  ["IKC", "Čeština pro cizince", "Komunikace, gramatika, poslech, výslovnost.", "Sleduj česká videa, mluv každý den i drobnosti, piš si poznámky o chybách."],
  ["KCJ/A1BH", "Angličtina I & II", "Gramatika, business slovíčka, čtení a psaní.", "Uč se slovíčka přes Quizlet, čti články nahlas, piš si fráze z učebnice."],
  ["KMA/MA2-E", "Matematika II", "Logika, integrály, číselné řady, limity.", "Procvič typové příklady, neboj se ptát, používej i vizuální pomůcky jako grafy."],
  ["KSY/ST", "Statistika", "Popisná data, hypotézy, testy, SPSS.", "Trénuj analýzy v SPSS, pochop, co znamená každý výstup a jak se liší hypotézy."],
  ["KIN/WA", "Vývoj webových aplikací", "HTML, CSS, PHP, databáze, CRUD operace.", "Piš si poznámky ručně, přepisuj hotové příklady s vlastními úpravami."],
  ["KIN/ZIT", "Základy IT a technologií", "Základní IT pojmy, podnikové technologie, hardware/software.", "Udělej si vlastní mapu pojmů, spoj si teorii s reálným světem – proč to firmy používají."]
];
   



    foreach ($predmety as $p) {
      echo '
        <div class="flip-card flip-wrapper">
          <div class="flip-inner">
     <div class="flip-front shadow">
              <h3 class="text-lg font-bold mb-2">' . $p[0] . ' – ' . $p[1] . '</h3>
              <p class="text-sm">' . $p[2] . '</p>
   </div>
            <div class="flip-back shadow">
    <h4 class="font-semibold text-base mb-2">Tipy:</h4>
              <p class="text-sm">' . $p[3] . '</p>
      </div>
          </div>
        </div>
      ';
    }

    $result = $conn->query("SELECT * FROM subjects ORDER BY id DESC");

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
      }    
      echo '
        <div class="flip-card flip-wrapper">
          <div class="flip-inner">
            <div class="flip-front shadow">
              <h3 class="text-lg font-bold mb-2">' . htmlspecialchars($row["code"]) . ' – ' . htmlspecialchars($row["name"]) . '</h3>
              <p class="text-sm">' . htmlspecialchars($row["description"]) . '</p>
            </div>
            <div class="flip-back shadow">
              <h4 class="font-semibold text-base mb-2">Tipy:</h4>
              <p class="text-sm">' . nl2br(htmlspecialchars($row["tip"])) . '</p>
            </div>
          </div>
        </div>
      ';
    }
    ?>
  </div>
</section>

<?php include('../includes/footer.php'); ?>

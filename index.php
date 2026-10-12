<?php
/**
 * CEK KHODAM — aplikasi hiburan (bukan ramalan sungguhan)
 * Jalankan: php -S localhost:8000   lalu buka http://localhost:8000
 *
 * Hasil bersifat deterministik: nama yang sama selalu menghasilkan khodam yang sama.
 */

declare(strict_types=1);

// ---------- DATA ----------
$khodamList = [
    ['Macan Putih',            'Penjaga wibawa',      'Auramu tenang tapi bikin orang segan. Sekali mengaum, rapat langsung hening.'],
    ['Naga Emas',              'Pembawa rezeki',      'Rezeki datang dari arah yang tidak disangka, termasuk dari diskon ongkir.'],
    ['Elang Hitam',            'Pengintai nasib',     'Matamu tajam melihat peluang, apalagi promo tanggal kembar.'],
    ['Kucing Oren',            'Penguasa gang',       'Santai, percaya diri, dan selalu dapat tempat paling hangat di rumah.'],
    ['Kulkas 2 Pintu',         'Penjaga ketenangan',  'Dingin di luar, penuh isi di dalam. Orang sering membukamu saat lapar.'],
    ['Sendal Jepit Swallow',   'Pelindung langkah',   'Sederhana, awet, dan selalu ada saat dibutuhkan. Hilang satu, panik sekampung.'],
    ['Nasi Padang Rendang',    'Pemberi semangat',    'Kehadiranmu bikin hari orang lain jauh lebih baik. Porsi hati selalu extra.'],
    ['Kuda Lumping',           'Penggerak nyali',     'Berani melangkah ke tempat yang orang lain ragukan. Iringan musik opsional.'],
    ['Harimau Kumbang',        'Penjaga malam',       'Paling hidup saat orang lain tidur. Ide terbaikmu muncul jam 02.00.'],
    ['Bidadari Pelangi',       'Pembawa keberuntungan', 'Hujan reda karena kamu lewat. Setidaknya begitu kata pawang cuaca lokal.'],
    ['Ular Sanca Batik',       'Penjaga kesabaran',   'Tenang, melingkar, dan menunggu waktu yang tepat. Tidak pernah terburu-buru.'],
    ['Wereng Cokelat',         'Penyeimbang kebun',   'Kecil tapi bikin heboh. Para petani sudah mengenal reputasimu.'],
    ['Garuda Perkasa',         'Pemandu arah',        'Visimu luas. Orang sering minta saran padamu soal arah hidup dan arah Google Maps.'],
    ['Sapu Lidi Sakti',        'Pembersih energi',    'Menyapu masalah sampai ke sudut-sudut. Dilarang dipakai untuk hal lain.'],
    ['Cicak Dinding',          'Pengamat setia',      'Diam-diam menyimak semuanya. Tahu banyak, bicara seperlunya.'],
    ['Rice Cooker Tua',        'Penjaga kehangatan',  'Sudah banyak makan asam garam, tapi masih setia menghangatkan suasana.'],
    ['Singa Laut Biru',        'Penguasa gelombang',  'Tenang di tengah badai. Sinyal wifi pun takluk padamu.'],
    ['Pocong Sopan',           'Penjaga etika',       'Menyeramkan tapi selalu bilang permisi. Banyak yang respect.'],
    ['Kuntilanak Wangi',       'Penebar pesona',      'Kehadiranmu terasa dari jauh dan harumnya bertahan seharian.'],
    ['Kerbau Bule',            'Pembawa kekuatan',    'Langkahmu lambat tapi pasti. Sekali berdiri, tidak ada yang bisa menggeser.'],
    ['Burung Hantu Bijak',     'Pemberi petunjuk',    'Pikiranmu jernih tengah malam. Orang bertanya, kamu menjawab dengan tenang.'],
    ['Keris Luk Tujuh',        'Pelindung martabat',  'Tidak banyak bicara, tapi kehadiranmu membuat orang berpikir dua kali.'],
    ['Mie Instan Telur',       'Penolong akhir bulan', 'Selalu hadir di saat genting. Dompet tipis? Kamu penyelamatnya.'],
    ['Angin Pesisir',          'Penyejuk suasana',    'Datang membawa tenang. Orang gerah, kamu yang jadi kipasnya.'],
];

$elemenList = ['Api', 'Air', 'Angin', 'Tanah', 'Petir', 'Bayangan', 'Cahaya'];
$asalList   = ['Gunung Bromo', 'Pesisir Selatan', 'Hutan Larangan', 'Pasar Malam', 'Sumur Tua', 'Candi Terlupakan', 'Warung Kopi 24 Jam', 'Kaki Pelangi', 'Perempatan Sepi'];
$sifatList  = ['Pendiam tapi tajam', 'Ceria dan usil', 'Setia sampai akhir', 'Misterius', 'Hangat dan melindungi', 'Keras kepala', 'Santai berwibawa', 'Pemalu tapi sakti'];
$pesanList  = [
    'Hari ini baik untuk memulai hal yang tertunda.',
    'Jangan lupa makan dan minum, khodam pun butuh tenaga.',
    'Ada kabar baik yang sedang mengantre di notifikasimu.',
    'Percaya pada prosesmu, bukan pada ramalan aplikasi.',
    'Istirahat cukup adalah ilmu kebal paling ampuh.',
    'Bagikan hasil ini ke temanmu, bandingkan siapa yang lebih sakti.',
];

// ---------- LOGIKA ----------
function pilih(array $arr, string $hash, int $offset): mixed
{
    return $arr[hexdec(substr($hash, $offset, 6)) % count($arr)];
}

$nama   = '';
$hasil  = null;
$galat  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim((string)($_POST['nama'] ?? ''));
    $nama = preg_replace('/\s+/u', ' ', $nama) ?? '';

    if ($nama === '') {
        $galat = 'Tulis namamu dulu, supaya khodammu bisa ditemukan.';
    } elseif (mb_strlen($nama) > 40) {
        $galat = 'Nama maksimal 40 karakter.';
    } else {
        $hash = md5(mb_strtolower($nama));
        $k    = pilih($khodamList, $hash, 0);
        $hasil = [
            'khodam'  => $k[0],
            'peran'   => $k[1],
            'arti'    => $k[2],
            'elemen'  => pilih($elemenList, $hash, 6),
            'asal'    => pilih($asalList, $hash, 12),
            'sifat'   => pilih($sifatList, $hash, 18),
            'pesan'   => pilih($pesanList, $hash, 24),
            'level'   => 50 + (hexdec(substr($hash, 28, 4)) % 50),
        ];
    }
}

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cek Khodam<?= $hasil ? ' · ' . e($hasil['khodam']) : '' ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Chonburi&family=Hanken+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
<style>
:root {
    --tinta:   #1b0e17;
    --tinta-2: #2a1624;
    --kunyit:  #f2c94c;
    --kertas:  #f6e6b0;
    --kertas-2:#ecd48a;
    --cinnabar:#c1272d;
    --giok:    #3f8f78;
    --teks:    #f4ead2;
    --redup:   #b7a58c;
    --display: 'Chonburi', Georgia, serif;
    --body:    'Hanken Grotesk', system-ui, sans-serif;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
html { color-scheme: dark; }
body {
    min-height: 100vh;
    font-family: var(--body);
    color: var(--teks);
    background:
        radial-gradient(900px 500px at 50% -10%, #4a1f3a 0%, transparent 70%),
        var(--tinta);
    display: flex;
    justify-content: center;
    padding: 40px 20px 60px;
    overflow-x: hidden;
}
.mandala {
    position: fixed;
    top: 50%; left: 50%;
    width: min(1100px, 140vw);
    translate: -50% -50%;
    opacity: .13;
    pointer-events: none;
    animation: putar 140s linear infinite;
}
@keyframes putar { to { rotate: 360deg; } }

main { position: relative; width: 100%; max-width: 560px; }

header { text-align: center; margin-bottom: 30px; }
h1 {
    font-family: var(--display);
    font-weight: 400;
    font-size: clamp(2.6rem, 11vw, 4.2rem);
    line-height: 1;
    color: var(--kunyit);
    letter-spacing: .01em;
    text-shadow: 0 0 40px rgba(242, 201, 76, .25);
}
header p {
    margin-top: 12px;
    color: var(--redup);
    font-size: 1.02rem;
    line-height: 1.5;
}

form {
    background: var(--tinta-2);
    border: 1px solid #4a2c40;
    border-radius: 18px;
    padding: 22px;
    display: grid;
    gap: 14px;
}
label { font-weight: 700; font-size: .95rem; }
input[type=text] {
    width: 100%;
    font: inherit;
    font-size: 1.15rem;
    color: var(--teks);
    background: var(--tinta);
    border: 1.5px solid #5b3a50;
    border-radius: 12px;
    padding: 14px 16px;
}
input[type=text]::placeholder { color: #7d6a74; }
input[type=text]:focus-visible,
button:focus-visible,
a:focus-visible { outline: 3px solid var(--kunyit); outline-offset: 2px; }
.galat { color: #ff9b9b; font-size: .92rem; }

.btn {
    font: inherit;
    font-weight: 700;
    font-size: 1.05rem;
    border: 0;
    border-radius: 12px;
    padding: 14px 18px;
    cursor: pointer;
    color: var(--tinta);
    background: var(--kunyit);
    box-shadow: 0 4px 0 #a98a1f;
    transition: translate .1s, box-shadow .1s;
}
.btn:hover  { translate: 0 1px; box-shadow: 0 3px 0 #a98a1f; }
.btn:active { translate: 0 4px; box-shadow: 0 0 0 #a98a1f; }
.btn.ghost {
    background: transparent;
    color: var(--teks);
    border: 1.5px solid #5b3a50;
    box-shadow: none;
}
.btn.ghost:hover { border-color: var(--kunyit); translate: 0; }

/* ---------- KARTU JIMAT (satu momen yang diingat) ---------- */
.jimat-wrap { perspective: 900px; margin-top: 28px; }
.jimat {
    position: relative;
    color: #3a1410;
    background:
        repeating-linear-gradient(0deg, rgba(120, 70, 10, .045) 0 2px, transparent 2px 7px),
        linear-gradient(160deg, var(--kertas) 0%, var(--kertas-2) 100%);
    padding: 30px 26px 26px;
    clip-path: polygon(0 3%, 4% 0, 12% 2%, 22% 0, 35% 2%, 48% 0, 60% 2%, 73% 0, 86% 2%, 96% 0, 100% 3%,
                       100% 97%, 95% 100%, 84% 98%, 70% 100%, 58% 98%, 44% 100%, 30% 98%, 17% 100%, 6% 98%, 0 97%);
    animation: tempel .7s cubic-bezier(.2, .9, .3, 1.2) both;
    transform-origin: 50% 0;
}
@keyframes tempel {
    0%   { opacity: 0; transform: rotateX(-70deg) translateY(-30px) scale(.92); }
    100% { opacity: 1; transform: none; }
}
.jimat::before, .jimat::after {
    content: '';
    position: absolute;
    left: 14px; right: 14px;
    height: 2px;
    background: var(--cinnabar);
    opacity: .7;
}
.jimat::before { top: 14px; }
.jimat::after  { bottom: 14px; }

.jimat .atas { text-align: center; color: var(--cinnabar); font-weight: 700; font-size: .95rem; margin-top: 6px; }
.jimat .atas b { color: #3a1410; }
.jimat h2 {
    font-family: var(--display);
    font-weight: 400;
    text-align: center;
    color: var(--cinnabar);
    font-size: clamp(2rem, 9vw, 3rem);
    line-height: 1.05;
    margin: 10px 0 4px;
}
.jimat .peran { text-align: center; font-weight: 500; font-style: italic; }
.jimat .arti {
    margin: 18px 0;
    text-align: center;
    line-height: 1.55;
    font-size: 1.05rem;
}

.kesaktian { margin: 4px 0 20px; }
.kesaktian .baris { display: flex; justify-content: space-between; font-weight: 700; margin-bottom: 6px; }
.bar { height: 14px; border: 2px solid var(--cinnabar); border-radius: 99px; overflow: hidden; background: rgba(193, 39, 45, .1); }
.bar span {
    display: block; height: 100%;
    width: var(--lv);
    background: repeating-linear-gradient(45deg, var(--cinnabar) 0 8px, #9c1d22 8px 16px);
    animation: isi 1.3s .5s cubic-bezier(.2, .8, .2, 1) both;
}
@keyframes isi { from { width: 0; } }

dl {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 18px;
}
dl div { border: 1.5px dashed rgba(193, 39, 45, .55); border-radius: 10px; padding: 9px 10px; }
dt { font-size: .78rem; color: #7a3a2c; }
dd { font-weight: 700; font-size: .95rem; margin-top: 2px; }

.pesan { text-align: center; font-size: .95rem; padding: 0 6px; }

.stempel {
    position: absolute;
    right: 18px; bottom: 34px;
    width: 86px; height: 86px;
    display: grid; place-items: center;
    text-align: center;
    font-family: var(--display);
    font-size: .8rem;
    line-height: 1.1;
    color: var(--cinnabar);
    border: 3px double var(--cinnabar);
    border-radius: 50%;
    rotate: -14deg;
    opacity: .85;
    mix-blend-mode: multiply;
    animation: cap .35s 1s cubic-bezier(.3, 1.6, .5, 1) both;
}
@keyframes cap { from { opacity: 0; scale: 2.2; } to { opacity: .85; scale: 1; } }

.aksi { display: flex; gap: 10px; margin-top: 18px; flex-wrap: wrap; }
.aksi .btn { flex: 1 1 180px; text-align: center; text-decoration: none; }

.catatan { text-align: center; color: var(--redup); font-size: .85rem; margin-top: 26px; line-height: 1.5; }

@media (max-width: 440px) {
    dl { grid-template-columns: 1fr 1fr; }
    .stempel { width: 70px; height: 70px; font-size: .68rem; bottom: 28px; }
}
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after { animation: none !important; transition: none !important; }
}
</style>
</head>
<body>

<svg class="mandala" viewBox="0 0 400 400" aria-hidden="true" fill="none" stroke="#f2c94c" stroke-width=".6">
    <circle cx="200" cy="200" r="190"/>
    <circle cx="200" cy="200" r="170" stroke-dasharray="2 6"/>
    <circle cx="200" cy="200" r="130"/>
    <circle cx="200" cy="200" r="90" stroke-dasharray="10 5"/>
    <circle cx="200" cy="200" r="50"/>
    <?php for ($i = 0; $i < 12; $i++): $a = $i * 30; ?>
        <ellipse cx="200" cy="95" rx="22" ry="62" transform="rotate(<?= $a ?> 200 200)"/>
    <?php endfor; ?>
    <?php for ($i = 0; $i < 12; $i++): $a = $i * 30 + 15; ?>
        <line x1="200" y1="10" x2="200" y2="70" transform="rotate(<?= $a ?> 200 200)"/>
    <?php endfor; ?>
</svg>

<main>
    <header>
        <h1>Cek Khodam</h1>
        <p>Tulis namamu, lihat siapa yang konon menemanimu.<br>Hasil selalu sama untuk nama yang sama.</p>
    </header>

    <form method="post" action="" autocomplete="off">
        <label for="nama">Nama kamu</label>
        <input type="text" id="nama" name="nama" maxlength="40"
               placeholder="Contoh: Budi Santoso"
               value="<?= e($nama) ?>" required autofocus>
        <?php if ($galat): ?><p class="galat" role="alert"><?= e($galat) ?></p><?php endif; ?>
        <button class="btn" type="submit">Cek khodamku</button>
    </form>

    <?php if ($hasil): ?>
    <div class="jimat-wrap">
        <article class="jimat" aria-live="polite">
            <p class="atas">Khodam milik <b><?= e($nama) ?></b> adalah</p>
            <h2><?= e($hasil['khodam']) ?></h2>
            <p class="peran"><?= e($hasil['peran']) ?></p>
            <p class="arti"><?= e($hasil['arti']) ?></p>

            <div class="kesaktian">
                <div class="baris"><span>Tingkat kesaktian</span><span><?= (int)$hasil['level'] ?>%</span></div>
                <div class="bar" role="img" aria-label="Kesaktian <?= (int)$hasil['level'] ?> persen">
                    <span style="--lv: <?= (int)$hasil['level'] ?>%"></span>
                </div>
            </div>

            <dl>
                <div><dt>Elemen</dt><dd><?= e($hasil['elemen']) ?></dd></div>
                <div><dt>Asal</dt><dd><?= e($hasil['asal']) ?></dd></div>
                <div><dt>Sifat</dt><dd><?= e($hasil['sifat']) ?></dd></div>
            </dl>

            <p class="pesan">Pesan hari ini: <?= e($hasil['pesan']) ?></p>
            <div class="stempel" aria-hidden="true">Sudah<br>terbukti<br>(katanya)</div>
        </article>

        <div class="aksi">
            <button class="btn" type="button" id="salin"
                    data-teks="Khodam <?= e($nama) ?> adalah <?= e($hasil['khodam']) ?> (<?= (int)$hasil['level'] ?>% sakti). Cek khodammu juga!">
                Salin hasil
            </button>
            <a class="btn ghost" href="">Cek nama lain</a>
        </div>
    </div>
    <?php endif; ?>

    <p class="catatan">Hanya untuk hiburan. Hasil dibuat dari nama secara acak-tetap, bukan ramalan sungguhan.</p>
</main>

<script>
const tombol = document.getElementById('salin');
if (tombol) {
    tombol.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(tombol.dataset.teks);
            tombol.textContent = 'Tersalin';
        } catch (_) {
            tombol.textContent = 'Gagal menyalin';
        }
        setTimeout(() => (tombol.textContent = 'Salin hasil'), 1800);
    });
}
</script>
</body>
</html>

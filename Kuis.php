<?php


// Mendefinisikan opsi form dalam array agar mudah dikelola
$opsi_prodi = [
    'Informatika' => 'Informatika',
    'Sistem Informasi' => 'Sistem Informasi',
    'Teknik Elektro' => 'Teknik Elektro',
    'DKV' => 'Desain Komunikasi Visual'
];

$opsi_hobi = [
    'Membaca' => 'Membaca Buku',
    'Olahraga' => 'Olahraga',
    'Musik' => 'Mendengarkan Musik',
    'Gaming' => 'Bermain Game'
];

// Inisialisasi variabel untuk data, error, dan status submit
$nama = $nim = $prodi = $jenis_kelamin = $alamat = '';
$hobi = [];
$errors = [];
$biodata_submitted_successfully = false;

// --- LOGIKA PEMROSESAN FORM BIODATA (METHOD POST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_biodata'])) {

    // 1. Ambil dan bersihkan data (Sanitize)
    $nama = trim(htmlspecialchars($_POST['nama_lengkap'] ?? ''));
    $nim = trim(htmlspecialchars($_POST['nim'] ?? ''));
    $prodi = htmlspecialchars($_POST['program_studi'] ?? '');
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin'] ?? '');
    $alamat = trim(htmlspecialchars($_POST['alamat'] ?? ''));
    $hobi = $_POST['hobi'] ?? [];
    
    // Pastikan hobi yang disubmit valid
    if (!empty($hobi)) {
        foreach($hobi as $key => $value) {
            $hobi[$key] = htmlspecialchars($value);
        }
    }

    // 2. Validasi Data
    if (empty($nama)) {
        $errors['nama_lengkap'] = 'Nama lengkap wajib diisi.';
    }
    if (empty($nim)) {
        $errors['nim'] = 'NIM wajib diisi.';
    } elseif (!is_numeric($nim)) {
        $errors['nim'] = 'NIM harus berupa angka.';
    }
    if (empty($prodi)) {
        $errors['program_studi'] = 'Program studi wajib dipilih.';
    }
    if (empty($jenis_kelamin)) {
        $errors['jenis_kelamin'] = 'Jenis kelamin wajib dipilih.';
    }
    if (empty($alamat)) {
        $errors['alamat'] = 'Alamat wajib diisi.';
    }
    if (count($hobi) < 1) {
        $errors['hobi'] = 'Pilih minimal satu hobi.';
    }

    // 3. Jika tidak ada error, proses data
    if (empty($errors)) {
        $biodata_submitted_successfully = true;
    }
}

// --- LOGIKA PEMROSESAN FORM PENCARIAN (METHOD GET) ---
$search_keyword = '';
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['keyword'])) {
    $search_keyword = trim(htmlspecialchars($_GET['keyword']));
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuis Pemrograman Web - Biodata Mahasiswa</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #007bff;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
            --border-color: #dee2e6;
            --error-color: #dc3545;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            color: var(--dark-gray);
            margin: 0;
            padding: 2rem;
        }
        .container {
            max-width: 750px;
            margin: 0 auto;
            background-color: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        header { text-align: center; margin-bottom: 2rem; }
        header h1 { font-size: 2rem; color: var(--dark-gray); }
        .icon { display: inline-block; width: 1em; height: 1em; margin-right: 0.5rem; vertical-align: -0.125em; }
        
        /* --- PENGUATAN HTML: Styling untuk Fieldset --- */
        fieldset {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        legend {
            font-weight: 600;
            color: var(--primary-color);
            padding: 0 0.5rem;
        }
        label { display: block; font-weight: 500; margin-bottom: 0.5rem; }
        .form-group { margin-bottom: 1.25rem; }
        input[type="text"], select, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            box-sizing: border-box;
        }
        .radio-group label, .checkbox-group label { font-weight: 400; display: inline-block; margin-right: 1rem; }
        .radio-group input, .checkbox-group input { margin-right: 0.4rem; }
        
        /* --- PENGUATAN PHP: Styling untuk Pesan Error --- */
        .error-message { color: var(--error-color); font-size: 0.875rem; margin-top: 0.25rem; }
        input.input-error, select.input-error, textarea.input-error { border-color: var(--error-color); }
        
        .button-primary {
            width: 100%;
            padding: 0.85rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .button-primary:hover { background-color: #0056b3; }
        
        /* --- Tampilan Hasil yang Lebih Menarik --- */
        .profile-card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            margin-top: 2rem;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }
        .profile-header { text-align: center; margin-bottom: 1.5rem; }
        .profile-header .nim { color: #6c757d; }
        .profile-body { display: grid; grid-template-columns: 150px 1fr; gap: 0.75rem; }
        .profile-body dt { font-weight: 600; }
        .profile-body dd { margin: 0; }
        
        .search-result { text-align: center; margin-top: 1.5rem; padding: 1rem; background-color: #e3f2fd; border-radius: 8px; }
        .search-result span { font-weight: 600; color: var(--primary-color); }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>
                <!-- PENGUATAN HTML: Ikon SVG -->
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 22C4 17.5817 7.58172 14 12 14C16.4183 14 20 17.5817 20 22H4ZM12 13C8.68629 13 6 10.3137 6 7C6 3.68629 8.68629 1 12 1C15.3137 1 18 3.68629 18 7C18 10.3137 15.3137 13 12 13Z"></path></svg>
                Formulir Biodata Mahasiswa
            </h1>
        </header>

        <main>
            <!-- ====== FORM BIODATA (METHOD POST) ====== -->
            <section>
                <form action="index.php" method="POST" novalidate>
                    <fieldset>
                        <legend>Data Pribadi</legend>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <!-- PENGUATAN PHP: Menampilkan kembali nilai jika ada & class error dinamis -->
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= $nama; ?>" class="<?= isset($errors['nama_lengkap']) ? 'input-error' : '' ?>">
                            <?php if (isset($errors['nama_lengkap'])): ?><div class="error-message"><?= $errors['nama_lengkap']; ?></div><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="nim">NIM</label>
                            <input type="text" id="nim" name="nim" value="<?= $nim; ?>" class="<?= isset($errors['nim']) ? 'input-error' : '' ?>">
                            <?php if (isset($errors['nim'])): ?><div class="error-message"><?= $errors['nim']; ?></div><?php endif; ?>
                        </div>
                        <div class="form-group radio-group">
                            <label>Jenis Kelamin</label>
                            <!-- PENGUATAN PHP: Menjaga radio button tetap terpilih -->
                            <input type="radio" id="laki_laki" name="jenis_kelamin" value="Laki-laki" <?= ($jenis_kelamin == 'Laki-laki') ? 'checked' : ''; ?>>
                            <label for="laki_laki">Laki-laki</label>
                            <input type="radio" id="perempuan" name="jenis_kelamin" value="Perempuan" <?= ($jenis_kelamin == 'Perempuan') ? 'checked' : ''; ?>>
                            <label for="perempuan">Perempuan</label>
                            <?php if (isset($errors['jenis_kelamin'])): ?><div class="error-message"><?= $errors['jenis_kelamin']; ?></div><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" name="alamat" class="<?= isset($errors['alamat']) ? 'input-error' : '' ?>"><?= $alamat; ?></textarea>
                            <?php if (isset($errors['alamat'])): ?><div class="error-message"><?= $errors['alamat']; ?></div><?php endif; ?>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Data Akademik</legend>
                        <div class="form-group">
                            <label for="program_studi">Program Studi</label>
                            <select id="program_studi" name="program_studi" class="<?= isset($errors['program_studi']) ? 'input-error' : '' ?>">
                                <option value="" disabled <?= empty($prodi) ? 'selected' : ''; ?>>-- Pilih Program Studi --</option>
                                <?php foreach($opsi_prodi as $value => $text): ?>
                                    <option value="<?= $value; ?>" <?= ($prodi == $value) ? 'selected' : ''; ?>><?= $text; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['program_studi'])): ?><div class="error-message"><?= $errors['program_studi']; ?></div><?php endif; ?>
                        </div>
                        <div class="form-group checkbox-group">
                            <label>Hobi</label>
                            <?php foreach($opsi_hobi as $value => $text): ?>
                                <!-- PENGUATAN PHP: Menjaga checkbox tetap tercentang -->
                                <input type="checkbox" id="hobi_<?= strtolower($value); ?>" name="hobi[]" value="<?= $value; ?>" <?= in_array($value, $hobi) ? 'checked' : ''; ?>>
                                <label for="hobi_<?= strtolower($value); ?>"><?= $text; ?></label>
                            <?php endforeach; ?>
                            <?php if (isset($errors['hobi'])): ?><div class="error-message"><?= $errors['hobi']; ?></div><?php endif; ?>
                        </div>
                    </fieldset>

                    <button type="submit" name="submit_biodata" class="button-primary">Kirim Biodata</button>
                </form>

                <!-- ====== HASIL FORM BIODATA (Tampilan Kartu) ====== -->
                <?php if ($biodata_submitted_successfully): ?>
                <div class="profile-card">
                    <div class="profile-header">
                        <h2><?= $nama; ?></h2>
                        <p class="nim">NIM: <?= $nim; ?></p>
                    </div>
                    <dl class="profile-body">
                        <dt>Program Studi</dt><dd><?= $opsi_prodi[$prodi]; ?></dd>
                        <dt>Jenis Kelamin</dt><dd><?= $jenis_kelamin; ?></dd>
                        <dt>Hobi</dt><dd><?= !empty($hobi) ? implode(', ', $hobi) : 'Tidak ada'; ?></dd>
                        <dt>Alamat</dt><dd><?= $alamat; ?></dd>
                    </dl>
                </div>
                <?php endif; ?>
            </section>
            
            <hr style="margin: 3rem 0; border: none; border-top: 1px solid var(--border-color);">

            <!-- ====== FORM PENCARIAN (METHOD GET) ====== -->
            <section>
                <h2 style="text-align: center;">Pencarian Data</h2>
                <form action="index.php" method="GET">
                    <div class="form-group">
                        <label for="keyword">Kata Kunci</label>
                        <input type="text" id="keyword" name="keyword" placeholder="Masukkan kata kunci di sini..." value="<?= $search_keyword ?>">
                    </div>
                    <button type="submit" class="button-primary">Cari</button>
                </form>

                <?php if (!empty($search_keyword)): ?>
                <div class="search-result">
                    <p>Anda mencari data dengan kata kunci: <span>"<?= $search_keyword; ?>"</span></p>
                </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>
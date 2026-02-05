<?php
include 'conn.php';

/* ================= AMBIL DATA EDIT ================= */
$edit = false;
if(isset($_GET['edit'])){
    $edit = true;
    $q = mysqli_query($conn,"
        SELECT t.*, p.nama, p.no_hp, l.nama_layanan, l.harga
        FROM transaksi t
        JOIN pelanggan p ON t.id_pelanggan=p.id_pelanggan
        JOIN layanan l ON t.id_layanan=l.id_layanan
        WHERE t.id_transaksi='$_GET[edit]'
    ");
    $e = mysqli_fetch_assoc($q);
}

/* ================= SIMPAN ================= */
if(isset($_POST['submit'])){

    mysqli_query($conn,"INSERT INTO pelanggan (nama,no_hp)
        VALUES ('$_POST[nama]','$_POST[no_hp]')");
    $id_pelanggan = mysqli_insert_id($conn);

    mysqli_query($conn,"INSERT INTO layanan (nama_layanan,harga)
        VALUES ('$_POST[nama_layanan]','$_POST[harga]')");
    $id_layanan = mysqli_insert_id($conn);

    mysqli_query($conn,"INSERT INTO transaksi
        (id_pelanggan,id_layanan,tanggal_masuk,status)
        VALUES (
            '$id_pelanggan',
            '$id_layanan',
            '$_POST[tanggal]',
            '$_POST[status]'
        )");
}

/* ================= UPDATE ================= */
if(isset($_POST['update'])){
    mysqli_query($conn,"UPDATE pelanggan SET
        nama='$_POST[nama]',
        no_hp='$_POST[no_hp]'
        WHERE id_pelanggan='$_POST[id_pelanggan]'");

    mysqli_query($conn,"UPDATE layanan SET
        nama_layanan='$_POST[nama_layanan]',
        harga='$_POST[harga]'
        WHERE id_layanan='$_POST[id_layanan]'");

    mysqli_query($conn,"UPDATE transaksi SET
        tanggal_masuk='$_POST[tanggal]',
        status='$_POST[status]'
        WHERE id_transaksi='$_POST[id_transaksi]'");

    header("Location:dashboard.php");
}

/* ================= HAPUS ================= */
if(isset($_GET['hapus'])){
    mysqli_query($conn,"DELETE FROM transaksi WHERE id_transaksi='$_GET[id_transaksi]'");
    mysqli_query($conn,"DELETE FROM pelanggan WHERE id_pelanggan='$_GET[id_pelanggan]'");
    mysqli_query($conn,"DELETE FROM layanan WHERE id_layanan='$_GET[id_layanan]'");
    header("Location:dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CRUD Cuci Sepatu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 min-h-screen p-6">
<div class="max-w-6xl mx-auto">

    <!-- ================= HEADER ================= -->
    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        CRUD Cuci Sepatu
    </h1>

    <!-- ================= FORM ================= -->
    <div class="bg-white p-6 rounded-2xl shadow mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">
            <?= $edit ? 'Edit Data' : 'Tambah Data' ?>
        </h2>

        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Hidden ID -->
            <input type="hidden" name="id_pelanggan" value="<?= $edit ? $e['id_pelanggan'] : '' ?>">
            <input type="hidden" name="id_layanan" value="<?= $edit ? $e['id_layanan'] : '' ?>">
            <input type="hidden" name="id_transaksi" value="<?= $edit ? $e['id_transaksi'] : '' ?>">

            <!-- Nama -->
            <div>
                <label class="block text-sm font-medium mb-1">Nama Pelanggan</label>
                <input
                    type="text"
                    name="nama"
                    value="<?= $edit ? $e['nama'] : '' ?>"
                    placeholder="Masukkan nama"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    required
                >
            </div>

            <!-- No HP -->
            <div>
                <label class="block text-sm font-medium mb-1">No HP</label>
                <input
                    type="text"
                    name="no_hp"
                    value="<?= $edit ? $e['no_hp'] : '' ?>"
                    placeholder="08xxxxxxxxxx"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    required
                >
            </div>

            <!-- Layanan -->
            <div>
                <label class="block text-sm font-medium mb-1">Layanan</label>
                <select
                    name="nama_layanan"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    required
                >
                    <option value="Reguler" <?= $edit && $e['nama_layanan']=='Reguler'?'selected':'' ?>>Reguler</option>
                    <option value="Deep Clean" <?= $edit && $e['nama_layanan']=='Deep Clean'?'selected':'' ?>>Deep Clean</option>
                    <option value="Repaint" <?= $edit && $e['nama_layanan']=='Repaint'?'selected':'' ?>>Repaint</option>
                </select>
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-sm font-medium mb-1">Harga</label>
                <input
                    type="number"
                    name="harga"
                    value="<?= $edit ? $e['harga'] : '' ?>"
                    placeholder="Masukkan harga"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    required
                >
            </div>

            <!-- Tanggal -->
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal Masuk</label>
                <input
                    type="date"
                    name="tanggal"
                    value="<?= $edit ? $e['tanggal_masuk'] : '' ?>"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    required
                >
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select
                    name="status"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    required
                >
                    <option value="Proses" <?= $edit && $e['status']=='Proses'?'selected':'' ?>>Proses</option>
                    <option value="Selesai" <?= $edit && $e['status']=='Selesai'?'selected':'' ?>>Selesai</option>
                    <option value="Diambil" <?= $edit && $e['status']=='Diambil'?'selected':'' ?>>Diambil</option>
                </select>
            </div>

            <!-- Button -->
            <div class="md:col-span-2">
                <button
                    name="<?= $edit ? 'update' : 'submit' ?>"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition"
                >
                    <?= $edit ? 'Update Data' : 'Simpan Data' ?>
                </button>
            </div>

        </form>
    </div>

    <!-- ================= TABLE ================= -->
    <div class="bg-white p-6 rounded-2xl shadow overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="p-3 border">Nama</th>
                    <th class="p-3 border">No HP</th>
                    <th class="p-3 border">Layanan</th>
                    <th class="p-3 border">Harga</th>
                    <th class="p-3 border">Tanggal</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php
            $data = mysqli_query($conn, "
                SELECT t.*, p.nama, p.no_hp, l.nama_layanan, l.harga,
                       p.id_pelanggan, l.id_layanan
                FROM transaksi t
                JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                JOIN layanan l ON t.id_layanan = l.id_layanan
            ");

            while ($d = mysqli_fetch_assoc($data)) {
            ?>
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border"><?= $d['nama'] ?></td>
                    <td class="p-2 border"><?= $d['no_hp'] ?></td>
                    <td class="p-2 border"><?= $d['nama_layanan'] ?></td>
                    <td class="p-2 border">Rp <?= number_format($d['harga']) ?></td>
                    <td class="p-2 border"><?= $d['tanggal_masuk'] ?></td>
                    <td class="p-2 border"><?= $d['status'] ?></td>
                    <td class="p-2 border space-x-2">
                        <a href="?edit=<?= $d['id_transaksi'] ?>" class="text-blue-600 hover:underline">
                            Edit
                        </a>
                        |
                        <a
                            href="?hapus=1&id_transaksi=<?= $d['id_transaksi'] ?>&id_pelanggan=<?= $d['id_pelanggan'] ?>&id_layanan=<?= $d['id_layanan'] ?>"
                            onclick="return confirm('Hapus semua data ini?')"
                            class="text-red-600 hover:underline"
                        >
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

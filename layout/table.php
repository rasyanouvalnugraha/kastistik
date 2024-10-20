<div class="overflow-x-auto p-5">
    <div class="max-h-96 overflow-y-auto relative">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg">
            <thead class="bg-gray-200">
                <tr class="text-gray-700">
                    <th class="px-4 py-3 border sticky top-0 bg-gray-200 z-10">Tanggal</th>
                    <th class="px-4 py-3 border sticky top-0 bg-gray-200 z-10">Pemasukan</th>
                    <th class="px-4 py-3 border sticky top-0 bg-gray-200 z-10">Pengeluaran</th>
                    <th class="px-4 py-3 border sticky top-0 bg-gray-200 z-10">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data as $row) {
                    echo "<tr class='text-center'>";
                    foreach ($row as $cell) {
                        echo "<td class='border px-4 py-2'>{$cell}</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Tambahkan CSS berikut -->
<style>
    .sticky {
        position: sticky;
        z-index: 10; /* Agar header selalu di atas konten lain */
    }

    .bg-gray-200 {
        background-color: #e5e7eb; /* Warna latar belakang untuk header */
    }
</style>

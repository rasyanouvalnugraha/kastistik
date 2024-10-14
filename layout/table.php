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
                <!-- Data Dummy -->
                <?php
                // Data Dummy
                $data = [
                    ["01/01/2024", "Rp 1.000.000", "Rp 200.000", "Pembayaran A"],
                    ["02/01/2024", "Rp 500.000", "Rp 100.000", "Pembayaran B"],
                    ["03/01/2024", "Rp 750.000", "Rp 300.000", "Pembayaran C"],
                    ["04/01/2024", "Rp 600.000", "Rp 150.000", "Pembayaran D"],
                    ["05/01/2024", "Rp 1.200.000", "Rp 400.000", "Pembayaran E"],
                    ["06/01/2024", "Rp 900.000", "Rp 250.000", "Pembayaran F"],
                    ["07/01/2024", "Rp 1.500.000", "Rp 600.000", "Pembayaran G"],
                    ["08/01/2024", "Rp 800.000", "Rp 350.000", "Pembayaran H"],
                    ["09/01/2024", "Rp 650.000", "Rp 200.000", "Pembayaran I"],
                    ["10/01/2024", "Rp 1.100.000", "Rp 450.000", "Pembayaran J"],
                    ["11/01/2024", "Rp 1.300.000", "Rp 300.000", "Pembayaran K"],
                    ["12/01/2024", "Rp 700.000", "Rp 350.000", "Pembayaran L"],
                    ["13/01/2024", "Rp 500.000", "Rp 200.000", "Pembayaran M"],
                    ["14/01/2024", "Rp 1.600.000", "Rp 500.000", "Pembayaran N"],
                    ["15/01/2024", "Rp 1.400.000", "Rp 350.000", "Pembayaran O"],
                    ["16/01/2024", "Rp 1.800.000", "Rp 600.000", "Pembayaran P"],
                    ["17/01/2024", "Rp 950.000", "Rp 200.000", "Pembayaran Q"],
                    ["18/01/2024", "Rp 600.000", "Rp 150.000", "Pembayaran R"],
                    ["19/01/2024", "Rp 1.200.000", "Rp 350.000", "Pembayaran S"],
                    ["20/01/2024", "Rp 850.000", "Rp 300.000", "Pembayaran T"],
                ];

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

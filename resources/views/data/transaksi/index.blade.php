<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Transaksi</h3>
                    <button onclick="document.getElementById('modalTambah').style.display='flex'"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded">
                        Tambah Transaksi
                    </button>
                </div>

                <!-- Tabel Transaksi -->
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700 w-full">
                        <thead class="bg-gray-200 dark:bg-gray-900">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Nama Kasir</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Total Bayar</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Tanggal Transaksi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-gray-100 dark:bg-gray-800 divide-y divide-gray-300 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                            @forelse ($transaksis as $no => $t)
                                <tr class="hover:bg-gray-200 dark:hover:bg-gray-700">
                                    <td class="px-6 py-2">{{ $no + 1 }}</td>
                                    <td class="px-6 py-2">{{ $t->user->name }}</td>
                                    <td class="px-6 py-2">{{ $t->total_bayar }}</td>
                                    <td class="px-6 py-2">{{ $t->tanggal }}</td>
                                    <td class="px-6 py-2 space-x-2">
                                        <button
                                            onclick="document.getElementById('modalEdit-{{ $t->id_transaksi }}').style.display='flex'"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">Edit</button>
                                        <button
                                            onclick="document.getElementById('modalDelete-{{ $t->id_transaksi }}').style.display='flex'"
                                            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Hapus</button>
                                        <button
                                            onclick="document.getElementById('modalDetail-{{ $t->id_transaksi }}').style.display='flex'"
                                            class="bg-gray-500 hover:bg-green-500 text-white px-2 py-1 rounded">Detail</button>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div id="modalDetail-{{ $t->id_transaksi }}"
                                    class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40"
                                    style="display:none;">

                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700 relative animate-fadeIn">

                                        <!-- Tombol Close -->
                                        <button
                                            onclick="document.getElementById('modalDetail-{{ $t->id_transaksi }}').style.display='none'"
                                            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>

                                        <!-- Header -->
                                        <h3
                                            class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100 text-center">
                                            Edit Transaksi
                                        </h3>

                                        <!-- Detail Item -->
                                        <div class="space-y-4 mb-4">
                                            <div
                                                class="p-4 border rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-gray-900 shadow-sm hover:shadow-md transition duration-200">

                                                @forelse ($t->details as $d)
                                                    <!-- Nama Barang & Subtotal -->
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="font-semibold text-gray-800 dark:text-gray-200">
                                                            {{ $d->barang->nama_barang }}
                                                        </span>
                                                        <span
                                                            class="font-semibold text-indigo-600 dark:text-indigo-400">
                                                            Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                                                        </span>
                                                    </div>

                                                    <!-- Info Tambahan -->
                                                    <div
                                                        class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                                        Jumlah: <span class="font-medium">{{ $d->jumlah }}</span>
                                                        <br>
                                                        Harga Jual:
                                                        <span class="font-medium">
                                                            Rp {{ number_format($d->harga_jual, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @empty
                                                    <p class="text-center py-4 text-gray-500 dark:text-gray-300">
                                                        Tidak ada detail transaksi.
                                                    </p>
                                                @endforelse
                                            </div>
                                        </div>

                                    </div>
                                </div>



                                <!-- Modal Edit -->
                                <div id="modalEdit-{{ $t->id_transaksi }}"
                                    class="modal fixed inset-0 z-50 flex items-center justify-center"
                                    style="display:none;">
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700">
                                        <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Edit
                                            Transaksi</h3>

                                        <form action="{{ route('transaksi.update', $t->id_transaksi) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="grid grid-cols-1 gap-4">
                                                <!-- User -->
                                                <select name="user_id" required
                                                    class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                                                    @foreach ($users as $u)
                                                        <option value="{{ $u->id }}"
                                                            {{ $t->user_id == $u->id ? 'selected' : '' }}>
                                                            {{ $u->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <!-- Total Bayar -->
                                                <input type="number" name="total_bayar" value="{{ $t->total_bayar }}"
                                                    min="0" required
                                                    class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">

                                                <!-- Tanggal -->
                                                <input type="date" name="tanggal" value="{{ $t->tanggal }}"
                                                    required
                                                    class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                                            </div>

                                            <div class="mt-6 flex justify-end gap-3">
                                                <button type="button"
                                                    onclick="document.getElementById('modalEdit-{{ $t->id_transaksi }}').style.display='none'"
                                                    class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600">Batal</button>

                                                <button type="submit"
                                                    class="px-4 py-2 rounded-lg bg-yellow-600 text-white">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>


                                <!-- Modal Delete -->
                                <div id="modalDelete-{{ $t->id_transaksi }}"
                                    class="modal fixed inset-0 z-50 flex items-center justify-center"
                                    style="display:none;">
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-md p-6 sm:p-8 border border-gray-200 dark:border-gray-700">

                                        <h3 class="text-2xl font-bold mb-6">Hapus Transaksi</h3>

                                        <p>Yakin ingin menghapus transaksi milik
                                            <span class="font-semibold">{{ $t->user->name }}</span>?
                                        </p>

                                        <form action="{{ route('transaksi.destroy', $t->id_transaksi) }}"
                                            method="POST" class="mt-4">
                                            @csrf
                                            @method('DELETE')

                                            <div class="flex justify-end gap-3">
                                                <button type="button"
                                                    onclick="document.getElementById('modalDelete-{{ $t->id_transaksi }}').style.display='none'"
                                                    class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600">Batal</button>

                                                <button type="submit"
                                                    class="px-4 py-2 rounded-lg bg-red-600 text-white">Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>


                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Data Transaksi Belum Tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal Tambah -->
        <div id="modalTambah" class="modal fixed inset-0 z-50 flex items-center justify-center" style="display:none;">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-xl p-6 sm:p-8 border">

                <h3 class="text-2xl font-bold mb-6">Tambah Transaksi</h3>

                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 gap-4">
                        <!-- User -->
                        <select name="user_id" required
                            class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                            <option value="">-- Pilih Pengguna --</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>

                        <!-- Total Bayar -->
                        <input type="number" name="total_bayar" placeholder="Total Bayar" min="0" required
                            class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">

                        <!-- Tanggal -->
                        <input type="date" name="tanggal" required
                            class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('modalTambah').style.display='none'"
                            class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600">Batal</button>

                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-app-layout>

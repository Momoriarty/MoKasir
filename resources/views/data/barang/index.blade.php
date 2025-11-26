<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Barang</h3>
                    <button onclick="document.getElementById('modalTambah').style.display='flex'"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded">
                        Tambah Barang
                    </button>
                </div>

                <!-- Tabel Barang -->
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700 w-full">
                        <thead class="bg-gray-200 dark:bg-gray-900">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Nama Barang</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Harga Modal (Kardus)</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Harga Jual (Kardus)</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Harga Modal (Ecer)</th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Harga Jual (Ecer)</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Isi per Kardus</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Stok Kardus</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Stok Ecer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-gray-100 dark:bg-gray-800 divide-y divide-gray-300 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                            @forelse ($barangs as $barang)
                                <tr class="hover:bg-gray-200 dark:hover:bg-gray-700">
                                    <td class="px-6 py-2">{{ $barang->nama_barang }}</td>
                                    <td class="px-6 py-2">Rp
                                        {{ number_format($barang->harga_modal_kardus, 0, ',', '.') }}</td>
                                    <td class="px-6 py-2">Rp
                                        {{ number_format($barang->harga_jual_kardus, 0, ',', '.') }}</td>
                                    <td class="px-6 py-2">Rp {{ number_format($barang->harga_modal_ecer, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-2">Rp {{ number_format($barang->harga_jual_ecer, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-2">{{ $barang->isi_per_kardus }}</td>
                                    <td class="px-6 py-2">{{ $barang->stok_kardus }}</td>
                                    <td class="px-6 py-2">{{ $barang->stok_ecer }}</td>
                                    <td class="px-6 py-2 space-x-2">
                                        <button
                                            onclick="document.getElementById('modalEdit-{{ $barang->id_barang }}').style.display='flex'"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">Edit</button>
                                        <button
                                            onclick="document.getElementById('modalDelete-{{ $barang->id_barang }}').style.display='flex'"
                                            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Hapus</button>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div id="modalEdit-{{ $barang->id_barang }}"
                                    class="modal fixed inset-0 z-50 flex items-center justify-center"
                                    style="display:none;">
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700">
                                        <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Edit Barang
                                        </h3>
                                        <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <input type="text" name="nama_barang"
                                                    value="{{ $barang->nama_barang }}" required
                                                    class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                                                <input type="number" name="harga_modal_kardus"
                                                    value="{{ $barang->harga_modal_kardus }}" min="0" required
                                                    class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                                                <input type="number" name="harga_modal_ecer"
                                                    value="{{ $barang->harga_modal_ecer }}" min="0" required
                                                    class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                                                <input type="number" name="harga_jual_kardus"
                                                    value="{{ $barang->harga_jual_kardus }}" min="0" required
                                                    class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                                                <input type="number" name="harga_jual_ecer"
                                                    value="{{ $barang->harga_jual_ecer }}" min="0" required
                                                    class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                                                <input type="number" name="isi_per_kardus"
                                                    value="{{ $barang->isi_per_kardus }}" min="1" required
                                                    class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                                                <input type="number" name="stok_kardus"
                                                    value="{{ $barang->stok_kardus }}" min="0" required
                                                    class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                                                <input type="number" name="stok_ecer" value="{{ $barang->stok_ecer }}"
                                                    min="0" required
                                                    class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                                            </div>
                                            <div class="mt-6 flex justify-end gap-3">
                                                <button type="button"
                                                    onclick="document.getElementById('modalEdit-{{ $barang->id_barang }}').style.display='none'"
                                                    class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100">Batal</button>
                                                <button type="submit"
                                                    class="px-4 py-2 rounded-lg bg-yellow-600 dark:bg-yellow-500 text-white dark:text-gray-900">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Modal Delete -->
                                <div id="modalDelete-{{ $barang->id_barang }}"
                                    class="modal fixed inset-0 z-50 flex items-center justify-center"
                                    style="display:none;">
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-md p-6 sm:p-8 border border-gray-200 dark:border-gray-700">
                                        <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Hapus
                                            Barang</h3>
                                        <p class="text-gray-900 dark:text-gray-100">Apakah yakin ingin menghapus <span
                                                class="font-semibold">{{ $barang->nama_barang }}</span>?</p>
                                        <form action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST"
                                            class="mt-4">
                                            @csrf
                                            @method('DELETE')
                                            <div class="flex justify-end gap-3">
                                                <button type="button"
                                                    onclick="document.getElementById('modalDelete-{{ $barang->id_barang }}').style.display='none'"
                                                    class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100">Batal</button>
                                                <button type="submit"
                                                    class="px-4 py-2 rounded-lg bg-red-600 dark:bg-red-500 text-white dark:text-gray-900">Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Data Barang Belum Tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $barangs->firstItem() }} to {{ $barangs->lastItem() }} of {{ $barangs->total() }}
                        results
                    </div>

                    <div>
                        {{ $barangs->onEachSide(1)->links('pagination::tailwind') }}
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal Tambah -->
        <div id="modalTambah" class="modal fixed inset-0 z-50 flex items-center justify-center"
            style="display:none;">
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700">
                <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100 text-center sm:text-left">Tambah
                    Barang Baru</h3>
                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="text" name="nama_barang" placeholder="Nama Barang" required
                            class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                        <input type="number" name="harga_modal_kardus" placeholder="Harga Modal (Kardus)"
                            min="0" required
                            class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                        <input type="number" name="harga_modal_ecer" placeholder="Harga Modal (Ecer)"
                            min="0" required
                            class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                        <input type="number" name="harga_jual_kardus" placeholder="Harga Jual (Kardus)"
                            min="0" required
                            class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                        <input type="number" name="harga_jual_ecer" placeholder="Harga Jual (Ecer)" min="0"
                            required
                            class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                        <input type="number" name="isi_per_kardus" placeholder="Isi per Kardus" min="1"
                            required
                            class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                        <input type="number" name="stok_kardus" placeholder="Stok Kardus" min="0" required
                            class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                        <input type="number" name="stok_ecer" placeholder="Stok Ecer" min="0" required
                            class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('modalTambah').style.display='none'"
                            class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-blue-600 dark:bg-blue-500 text-white dark:text-gray-900">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>

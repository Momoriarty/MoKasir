<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">

                <!-- Header & Search -->
                <div class="flex flex-col gap-4 mb-4 md:flex-row md:items-center md:justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Barang</h3>
                    <form method="GET" action="{{ route('barang.index') }}" class="flex gap-2">
                        <input type="text" name="nama_barang" value="{{ request('nama_barang') }}"
                            placeholder="Cari nama barang..."
                            class="px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                        <button type="submit"
                            class="px-4 py-2 font-semibold text-white bg-blue-500 rounded hover:bg-blue-600">Search</button>
                    </form>
                    <button onclick="document.getElementById('modalTambah').style.display='flex'"
                        class="px-4 py-2 font-semibold text-white bg-green-500 rounded hover:bg-green-600">
                        Tambah Barang
                    </button>
                </div>

                <!-- Tabel Barang -->
                <div class="w-full overflow-x-auto">
                    <table class="w-full min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">Nama Barang</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">Harga Modal (Kardus)</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">Harga Jual (Kardus)</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">Harga Modal (Ecer)</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">Harga Jual (Ecer)</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">Isi per Kardus</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">Stok Kardus</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">Stok Ecer</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-900 bg-gray-100 divide-y divide-gray-300 dark:bg-gray-800 dark:divide-gray-700 dark:text-gray-100">
                            @forelse ($barangs as $barang)
                                <tr class="hover:bg-gray-200 dark:hover:bg-gray-700">
                                    <td class="px-6 py-2">{{ $barang->nama_barang }}</td>
                                    <td class="px-6 py-2">Rp {{ number_format($barang->harga_modal_kardus, 0, ',', '.') }}</td>
                                    <td class="px-6 py-2">Rp {{ number_format($barang->harga_jual_kardus, 0, ',', '.') }}</td>
                                    <td class="px-6 py-2">Rp {{ number_format($barang->harga_modal_ecer, 0, ',', '.') }}</td>
                                    <td class="px-6 py-2">Rp {{ number_format($barang->harga_jual_ecer, 0, ',', '.') }}</td>
                                    <td class="px-6 py-2">{{ $barang->isi_per_kardus }}</td>
                                    <td class="px-6 py-2">{{ $barang->stok_kardus }}</td>
                                    <td class="px-6 py-2">{{ $barang->stok_ecer }}</td>
                                    <td class="px-6 py-2 space-x-2">
                                        <button onclick="document.getElementById('modalEdit-{{ $barang->id_barang }}').style.display='flex'"
                                            class="px-2 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">Edit</button>
                                        <button onclick="document.getElementById('modalDelete-{{ $barang->id_barang }}').style.display='flex'"
                                            class="px-2 py-1 text-white bg-red-500 rounded hover:bg-red-600">Hapus</button>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div id="modalEdit-{{ $barang->id_barang }}" class="fixed inset-0 z-50 flex items-center justify-center modal" style="display:none;">
                                    <div class="w-full max-w-xl p-6 bg-white border border-gray-200 shadow-2xl dark:bg-gray-800 rounded-3xl sm:p-8 dark:border-gray-700">
                                        <h3 class="mb-6 text-2xl font-bold text-gray-900 dark:text-gray-100">Edit Barang</h3>
                                        <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                                                <input type="number" name="harga_modal_kardus" value="{{ $barang->harga_modal_kardus }}" min="0" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                                                <input type="number" name="harga_modal_ecer" value="{{ $barang->harga_modal_ecer }}" min="0" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                                                <input type="number" name="harga_jual_kardus" value="{{ $barang->harga_jual_kardus }}" min="0" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                                                <input type="number" name="harga_jual_ecer" value="{{ $barang->harga_jual_ecer }}" min="0" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                                                <input type="number" name="isi_per_kardus" value="{{ $barang->isi_per_kardus }}" min="1" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                                                <input type="number" name="stok_kardus" value="{{ $barang->stok_kardus }}" min="0" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                                                <input type="number" name="stok_ecer" value="{{ $barang->stok_ecer }}" min="0" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                                            </div>
                                            <div class="flex justify-end gap-3 mt-6">
                                                <button type="button" onclick="document.getElementById('modalEdit-{{ $barang->id_barang }}').style.display='none'" class="px-4 py-2 text-gray-800 bg-gray-300 rounded-lg dark:bg-gray-600 dark:text-gray-100">Batal</button>
                                                <button type="submit" class="px-4 py-2 text-white bg-yellow-600 rounded-lg dark:bg-yellow-500 dark:text-gray-900">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Modal Delete -->
                                <div id="modalDelete-{{ $barang->id_barang }}" class="fixed inset-0 z-50 flex items-center justify-center modal" style="display:none;">
                                    <div class="w-full max-w-md p-6 bg-white border border-gray-200 shadow-2xl dark:bg-gray-800 rounded-3xl sm:p-8 dark:border-gray-700">
                                        <h3 class="mb-6 text-2xl font-bold text-gray-900 dark:text-gray-100">Hapus Barang</h3>
                                        <p class="text-gray-900 dark:text-gray-100">Apakah yakin ingin menghapus <span class="font-semibold">{{ $barang->nama_barang }}</span>?</p>
                                        <form action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST" class="mt-4">
                                            @csrf
                                            @method('DELETE')
                                            <div class="flex justify-end gap-3">
                                                <button type="button" onclick="document.getElementById('modalDelete-{{ $barang->id_barang }}').style.display='none'" class="px-4 py-2 text-gray-800 bg-gray-300 rounded-lg dark:bg-gray-600 dark:text-gray-100">Batal</button>
                                                <button type="submit" class="px-4 py-2 text-white bg-red-600 rounded-lg dark:bg-red-500 dark:text-gray-900">Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Data Barang Belum Tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between mt-3">
                    <div>
                        Showing {{ $barangs->firstItem() }} to {{ $barangs->lastItem() }} of {{ $barangs->total() }} results
                    </div>
                    <div>
                        {{ $barangs->onEachSide(1)->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah -->
        <div id="modalTambah" class="fixed inset-0 z-50 flex items-center justify-center modal" style="display:none;">
            <div class="w-full max-w-xl p-6 bg-white border border-gray-200 shadow-2xl dark:bg-gray-800 rounded-3xl sm:p-8 dark:border-gray-700">
                <h3 class="mb-6 text-2xl font-bold text-center text-gray-900 dark:text-gray-100 sm:text-left">Tambah Barang Baru</h3>
                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <input type="text" name="nama_barang" placeholder="Nama Barang" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                        <input type="number" name="harga_modal_kardus" placeholder="Harga Modal (Kardus)" min="0" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                        <input type="number" name="harga_modal_ecer" placeholder="Harga Modal (Ecer)" min="0" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                        <input type="number" name="harga_jual_kardus" placeholder="Harga Jual (Kardus)" min="0" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                        <input type="number" name="harga_jual_ecer" placeholder="Harga Jual (Ecer)" min="0" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                        <input type="number" name="isi_per_kardus" placeholder="Isi per Kardus" min="1" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                        <input type="number" name="stok_kardus" placeholder="Stok Kardus" min="0" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                        <input type="number" name="stok_ecer" placeholder="Stok Ecer" min="0" required class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="document.getElementById('modalTambah').style.display='none'" class="px-4 py-2 text-gray-800 bg-gray-300 rounded-lg dark:bg-gray-600 dark:text-gray-100">Batal</button>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg dark:bg-blue-500 dark:text-gray-900">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>

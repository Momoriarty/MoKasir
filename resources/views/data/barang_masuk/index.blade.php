<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Barang Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">

                {{-- Header --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium dark:text-gray-100">Daftar Barang Masuk</h3>

                    <button onclick="document.getElementById('modalTambahMasuk').style.display='flex'"
                        class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                        Tambah Barang Masuk
                    </button>
                </div>

                {{-- Tabel --}}
                <div class="w-full overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-900">
                            <tr>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">
                                    Barang</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">
                                    Jumlah Kardus</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">
                                    Jumlah Ecer</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">
                                    Tanggal Masuk</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-900 uppercase dark:text-gray-300">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-100 divide-y dark:bg-gray-800 dark:divide-gray-700">


                            @forelse ($barangMasuks as $masuk)
                                <tr>
                                    <td class="px-4 py-2">{{ $masuk->barang->nama_barang }}</td>
                                    <td class="px-4 py-2">{{ $masuk->jumlah_kardus }}</td>
                                    <td class="px-4 py-2">{{ $masuk->jumlah_ecer }}</td>
                                    <td class="px-4 py-2">{{ $masuk->tanggal_masuk }}</td>

                                    <td class="px-4 py-2 space-x-2">
                                        <button
                                            onclick="document.getElementById('modalEditMasuk-{{ $masuk->id_barang_masuk }}').style.display='flex'"
                                            class="px-3 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                            Edit
                                        </button>

                                        <button
                                            onclick="document.getElementById('modalDeleteMasuk-{{ $masuk->id_barang_masuk }}').style.display='flex'"
                                            class="px-3 py-1 text-white bg-red-500 rounded hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>

                                {{-- Modal Edit --}}
                                <div id="modalEditMasuk-{{ $masuk->id_barang_masuk }}"
                                    class="fixed inset-0 z-50 items-center justify-center modal" style="display:none;">
                                    <div class="w-full max-w-lg p-6 bg-white dark:bg-gray-800 rounded-2xl">

                                        <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Edit Barang Masuk</h3>

                                        <form action="{{ route('barangMasuk.update', $masuk->id_barang_masuk) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="grid grid-cols-1 gap-3">

                                                <select name="id_barang" required
                                                    class="w-full px-3 py-2 border rounded dark:bg-gray-700">
                                                    @foreach ($barangs as $barang)
                                                        <option value="{{ $barang->id_barang }}"
                                                            @if ($barang->id_barang == $masuk->id_barang) selected @endif>
                                                            {{ $barang->nama_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <input type="number" name="jumlah_kardus"
                                                    value="{{ $masuk->jumlah_kardus }}" required min="0"
                                                    class="px-3 py-2 border rounded dark:bg-gray-700">

                                                <input type="number" name="jumlah_ecer"
                                                    value="{{ $masuk->jumlah_ecer }}" required min="0"
                                                    class="px-3 py-2 border rounded dark:bg-gray-700">

                                                <input type="date" name="tanggal_masuk"
                                                    value="{{ $masuk->tanggal_masuk }}" required
                                                    class="px-3 py-2 border rounded dark:bg-gray-700">
                                            </div>

                                            <div class="flex justify-end gap-3 mt-4">
                                                <button type="button"
                                                    onclick="document.getElementById('modalEditMasuk-{{ $masuk->id_barang_masuk }}').style.display='none'"
                                                    class="px-4 py-2 bg-gray-300 rounded">
                                                    Batal
                                                </button>

                                                <button class="px-4 py-2 text-white bg-yellow-600 rounded"
                                                    type="submit">
                                                    Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- Modal Delete --}}
                                <div id="modalDeleteMasuk-{{ $masuk->id_barang_masuk }}"
                                    class="fixed inset-0 z-50 items-center justify-center modal" style="display:none;">
                                    <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-2xl">

                                        <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Hapus Barang Masuk</h3>
                                        <p class="mb-4 dark:text-gray-100">Yakin ingin menghapus data?</p>

                                        <form action="{{ route('barangMasuk.destroy', $masuk->id_barang_masuk) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <div class="flex justify-end gap-3">
                                                <button type="button"
                                                    onclick="document.getElementById('modalDeleteMasuk-{{ $masuk->id_barang_masuk }}').style.display='none'"
                                                    class="px-4 py-2 bg-gray-300 rounded">Batal</button>

                                                <button class="px-4 py-2 text-white bg-red-600 rounded"
                                                    type="submit">Hapus</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500 dark:text-gray-300">
                                        Belum ada data barang masuk.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                    <div class="flex justify-center mt-4">
                     {{ $barangMasuks->onEachSide(1)->links('pagination::tailwind') }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="modalTambahMasuk" class="fixed inset-0 z-50 items-center justify-center modal" style="display:none;">
        <div class="w-full max-w-lg p-6 bg-white dark:bg-gray-800 rounded-2xl">

            <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Tambah Barang Masuk</h3>

            <form action="{{ route('barangMasuk.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-3">

                    <select name="id_barang" required class="px-3 py-2 border rounded dark:bg-gray-700">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id_barang }}">{{ $barang->nama_barang }}</option>
                        @endforeach
                    </select>

                    <input type="number" name="jumlah_kardus" placeholder="Jumlah Kardus" min="0" required
                        class="px-3 py-2 border rounded dark:bg-gray-700">

                    <input type="number" name="jumlah_ecer" placeholder="Jumlah Ecer" min="0" required
                        class="px-3 py-2 border rounded dark:bg-gray-700">

                    <input type="date" name="tanggal_masuk" required
                        class="px-3 py-2 border rounded dark:bg-gray-700">
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="document.getElementById('modalTambahMasuk').style.display='none'"
                        class="px-4 py-2 bg-gray-300 rounded">Batal</button>

                    <button class="px-4 py-2 text-white bg-blue-600 rounded" type="submit">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>

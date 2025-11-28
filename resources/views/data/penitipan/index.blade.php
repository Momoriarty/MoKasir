<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Penitipan') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">

                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-medium dark:text-gray-100">Daftar Penitipan</h3>

                    <button onclick="document.getElementById('modalTambahPenitipan').style.display='flex'"
                        class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                        Tambah Penitipan
                    </button>
                </div>

                {{-- Filter Toggle Button --}}
                <div class="mb-4">
                    <button onclick="document.getElementById('filterSection').classList.toggle('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        <span class="mr-2">🔍</span> Filter
                    </button>
                </div>

                {{-- Filter Section (Hidden by default) --}}
                <div id="filterSection" class="hidden p-4 mb-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <form id="filterForm" action="{{ route('penitipan.index') }}" method="GET">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nama Penitip
                                </label>
                                <input type="text" id="filterNama" name="nama_penitip"
                                    value="{{ request('nama_penitip') }}" placeholder="Cari nama..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Tanggal Dari
                                </label>
                                <input type="date" id="filterTanggalDari" name="tanggal_dari"
                                    value="{{ request('tanggal_dari') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Tanggal Sampai
                                </label>
                                <input type="date" id="filterTanggalSampai" name="tanggal_sampai"
                                    value="{{ request('tanggal_sampai') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            </div>

                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <button type="submit" class="px-6 py-2 text-white bg-green-500 rounded hover:bg-green-600">
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Active Filter Chips --}}
                @if (request()->hasAny(['nama_penitip', 'tanggal_dari', 'tanggal_sampai']))
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Filter Aktif:</span>

                        @if (request('nama_penitip'))
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                <span>Nama: {{ request('nama_penitip') }}</span>
                                <a href="{{ route('penitipan.index', array_merge(request()->except('nama_penitip'), [])) }}"
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-100">
                                    ✕
                                </a>
                            </div>
                        @endif

                        @if (request('tanggal_dari'))
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 text-sm bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                <span>Dari: {{ \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') }}</span>
                                <a href="{{ route('penitipan.index', array_merge(request()->except('tanggal_dari'), [])) }}"
                                    class="text-green-600 hover:text-green-800 dark:text-green-300 dark:hover:text-green-100">
                                    ✕
                                </a>
                            </div>
                        @endif

                        @if (request('tanggal_sampai'))
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 text-sm bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 rounded-full">
                                <span>Sampai:
                                    {{ \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') }}</span>
                                <a href="{{ route('penitipan.index', array_merge(request()->except('tanggal_sampai'), [])) }}"
                                    class="text-purple-600 hover:text-purple-800 dark:text-purple-300 dark:hover:text-purple-100">
                                    ✕
                                </a>
                            </div>
                        @endif

                        <a href="{{ route('penitipan.index') }}"
                            class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800 rounded-full">
                            Hapus Semua Filter
                        </a>
                    </div>
                @endif

                <div class="w-full overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-900">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Nama Penitip</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Tanggal Titip</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-gray-100 divide-y dark:bg-gray-800 dark:divide-gray-700">

                            @forelse ($penitipans as $data)
                                <tr>
                                    <td class="px-4 py-2">{{ $data->nama_penitip }}</td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($data->tanggal_titip)->format('d/m/Y') }}</td>

                                    <td class="px-4 py-2 space-x-2">
                                        <button onclick="toggleDetail('detail-{{ $data->id_penitipan }}')"
                                            class="px-3 py-1 text-white bg-blue-500 rounded hover:bg-blue-600">
                                            Detail
                                        </button>

                                        <button
                                            onclick="document.getElementById('modalEditPenitipan-{{ $data->id_penitipan }}').style.display='flex'"
                                            class="px-3 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                            Edit
                                        </button>

                                        <button
                                            onclick="document.getElementById('modalDeletePenitipan-{{ $data->id_penitipan }}').style.display='flex'"
                                            class="px-3 py-1 text-white bg-red-500 rounded hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>

                                {{-- Detail Row (Hidden by default) --}}
                                <tr id="detail-{{ $data->id_penitipan }}" class="hidden bg-gray-50 dark:bg-gray-900">
                                    <td colspan="3" class="px-4 py-4">
                                        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                                            <div class="flex justify-between mb-3">
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Detail Barang
                                                    Titipan</h4>
                                                <button
                                                    onclick="document.getElementById('modalTambahDetail-{{ $data->id_penitipan }}').style.display='flex'"
                                                    class="px-3 py-1 text-sm text-white bg-green-500 rounded hover:bg-green-600">
                                                    + Tambah Barang
                                                </button>
                                            </div>

                                            <div class="overflow-x-auto">
                                                <table
                                                    class="min-w-full text-sm divide-y divide-gray-300 dark:divide-gray-700">
                                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                                        <tr>
                                                            <th
                                                                class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300">
                                                                Nama Barang</th>
                                                            <th
                                                                class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300">
                                                                Harga Modal</th>
                                                            <th
                                                                class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300">
                                                                Harga Jual</th>
                                                            <th
                                                                class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300">
                                                                Jml Titip</th>
                                                            <th
                                                                class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300">
                                                                Jml Terjual</th>
                                                            <th
                                                                class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300">
                                                                Jml Sisa</th>
                                                            <th
                                                                class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300">
                                                                Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                                        @php
                                                            $details = \App\Models\PenitipanDetail::where(
                                                                'id_penitipan',
                                                                $data->id_penitipan,
                                                            )->get();
                                                        @endphp
                                                        @forelse ($details as $detail)
                                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                <td class="px-3 py-2">{{ $detail->nama_barang }}</td>
                                                                <td class="px-3 py-2">Rp
                                                                    {{ number_format($detail->harga_modal, 0, ',', '.') }}
                                                                </td>
                                                                <td class="px-3 py-2">Rp
                                                                    {{ number_format($detail->harga_jual, 0, ',', '.') }}
                                                                </td>
                                                                <td class="px-3 py-2">{{ $detail->jumlah_titip }}</td>
                                                                <td class="px-3 py-2">{{ $detail->jumlah_terjual }}
                                                                </td>
                                                                <td class="px-3 py-2">{{ $detail->jumlah_sisa }}</td>
                                                                <td class="px-3 py-2 space-x-1">
                                                                    <button
                                                                        onclick="document.getElementById('modalEditDetail-{{ $detail->id_penitipan_detail }}').style.display='flex'"
                                                                        class="px-2 py-1 text-xs text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                                                        Edit
                                                                    </button>
                                                                    <button
                                                                        onclick="document.getElementById('modalDeleteDetail-{{ $detail->id_penitipan_detail }}').style.display='flex'"
                                                                        class="px-2 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-600">
                                                                        Hapus
                                                                    </button>
                                                                </td>
                                                            </tr>

                                                            {{-- Modal Edit Detail --}}
                                                            <div id="modalEditDetail-{{ $detail->id_penitipan_detail }}"
                                                                class="fixed inset-0 z-50 items-center justify-center modal"
                                                                style="display:none;">
                                                                <div
                                                                    class="w-full max-w-lg p-6 bg-white dark:bg-gray-800 rounded-2xl">
                                                                    <h3
                                                                        class="mb-4 text-xl font-bold dark:text-gray-100">
                                                                        Edit Barang Titipan</h3>
                                                                    <form
                                                                        action="{{ route('penitipanDetail.update', $detail->id_penitipan_detail) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="grid grid-cols-1 gap-3">
                                                                            <input type="text" name="nama_barang"
                                                                                value="{{ $detail->nama_barang }}"
                                                                                placeholder="Nama Barang" required
                                                                                class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                                                            <input type="number" name="harga_modal"
                                                                                value="{{ $detail->harga_modal }}"
                                                                                placeholder="Harga Modal" required
                                                                                class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                                                            <input type="number" name="harga_jual"
                                                                                value="{{ $detail->harga_jual }}"
                                                                                placeholder="Harga Jual" required
                                                                                class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                                                            <input type="number" name="jumlah_titip"
                                                                                value="{{ $detail->jumlah_titip }}"
                                                                                placeholder="Jumlah Titip" required
                                                                                class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                                                            <input type="number"
                                                                                name="jumlah_terjual"
                                                                                value="{{ $detail->jumlah_terjual }}"
                                                                                placeholder="Jumlah Terjual"
                                                                                class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                                                            <input type="number" name="jumlah_sisa"
                                                                                value="{{ $detail->jumlah_sisa }}"
                                                                                placeholder="Jumlah Sisa" required
                                                                                class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                                                        </div>
                                                                        <div class="flex justify-end gap-3 mt-4">
                                                                            <button type="button"
                                                                                onclick="document.getElementById('modalEditDetail-{{ $detail->id_penitipan_detail }}').style.display='none'"
                                                                                class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                                                                            <button
                                                                                class="px-4 py-2 text-white bg-yellow-600 rounded"
                                                                                type="submit">Update</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>

                                                            {{-- Modal Delete Detail --}}
                                                            <div id="modalDeleteDetail-{{ $detail->id_penitipan_detail }}"
                                                                class="fixed inset-0 z-50 items-center justify-center modal"
                                                                style="display:none;">
                                                                <div
                                                                    class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-2xl">
                                                                    <h3
                                                                        class="mb-4 text-xl font-bold dark:text-gray-100">
                                                                        Hapus Barang Titipan</h3>
                                                                    <p class="mb-4 dark:text-gray-100">Yakin ingin
                                                                        menghapus barang ini?</p>
                                                                    <form
                                                                        action="{{ route('penitipanDetail.destroy', $detail->id_penitipan_detail) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <div class="flex justify-end gap-3">
                                                                            <button type="button"
                                                                                onclick="document.getElementById('modalDeleteDetail-{{ $detail->id_penitipan_detail }}').style.display='none'"
                                                                                class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                                                                            <button
                                                                                class="px-4 py-2 text-white bg-red-600 rounded"
                                                                                type="submit">Hapus</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>

                                                        @empty
                                                            <tr>
                                                                <td colspan="7"
                                                                    class="px-3 py-4 text-center text-gray-500 dark:text-gray-400">
                                                                    Belum ada barang titipan
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Tambah Detail untuk Penitipan Spesifik --}}
                                <div id="modalTambahDetail-{{ $data->id_penitipan }}"
                                    class="fixed inset-0 z-50 items-center justify-center modal"
                                    style="display:none;">
                                    <div class="w-full max-w-lg p-6 bg-white dark:bg-gray-800 rounded-2xl">
                                        <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Tambah Barang Titipan
                                        </h3>
                                        <form action="{{ route('penitipanDetail.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_penitipan"
                                                value="{{ $data->id_penitipan }}">
                                            <div class="grid grid-cols-1 gap-3">
                                                <input type="text" name="nama_barang" placeholder="Nama Barang"
                                                    required
                                                    class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                                <input type="number" name="harga_modal" placeholder="Harga Modal"
                                                    required
                                                    class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                                <input type="number" name="harga_jual" placeholder="Harga Jual"
                                                    required
                                                    class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                                <input type="number" name="jumlah_titip" placeholder="Jumlah Titip"
                                                    required
                                                    class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                                <input type="number" name="jumlah_terjual"
                                                    placeholder="Jumlah Terjual" value="0"
                                                    class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                                <input type="number" name="jumlah_sisa" placeholder="Jumlah Sisa"
                                                    required
                                                    class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                            </div>
                                            <div class="flex justify-end gap-3 mt-4">
                                                <button type="button"
                                                    onclick="document.getElementById('modalTambahDetail-{{ $data->id_penitipan }}').style.display='none'"
                                                    class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                                                <button class="px-4 py-2 text-white bg-blue-600 rounded"
                                                    type="submit">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- Modal Edit Penitipan --}}
                                <div id="modalEditPenitipan-{{ $data->id_penitipan }}"
                                    class="fixed inset-0 z-50 items-center justify-center modal"
                                    style="display:none;">
                                    <div class="w-full max-w-lg p-6 bg-white dark:bg-gray-800 rounded-2xl">
                                        <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Edit Data Penitipan</h3>
                                        <form action="{{ route('penitipan.update', $data->id_penitipan) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid grid-cols-1 gap-3">
                                                <input type="text" name="nama_penitip"
                                                    value="{{ $data->nama_penitip }}" required
                                                    class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                                <input type="date" name="tanggal_titip"
                                                    value="{{ $data->tanggal_titip }}" required
                                                    class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                            </div>
                                            <div class="flex justify-end gap-3 mt-4">
                                                <button type="button"
                                                    onclick="document.getElementById('modalEditPenitipan-{{ $data->id_penitipan }}').style.display='none'"
                                                    class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                                                <button class="px-4 py-2 text-white bg-yellow-600 rounded"
                                                    type="submit">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- Modal Delete Penitipan --}}
                                <div id="modalDeletePenitipan-{{ $data->id_penitipan }}"
                                    class="fixed inset-0 z-50 items-center justify-center modal"
                                    style="display:none;">
                                    <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-2xl">
                                        <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Hapus Penitipan</h3>
                                        <p class="mb-4 dark:text-gray-100">Yakin ingin menghapus data ini?</p>
                                        <form action="{{ route('penitipan.destroy', $data->id_penitipan) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="flex justify-end gap-3">
                                                <button type="button"
                                                    onclick="document.getElementById('modalDeletePenitipan-{{ $data->id_penitipan }}').style.display='none'"
                                                    class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                                                <button class="px-4 py-2 text-white bg-red-600 rounded"
                                                    type="submit">Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-center text-gray-500 dark:text-gray-300">
                                        Belum ada data penitipan.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $penitipans->appends(request()->query())->links('pagination::tailwind') }}
                </div>

            </div>

        </div>
    </div>

    {{-- Modal Tambah Penitipan --}}
    <div id="modalTambahPenitipan" class="fixed inset-0 z-50 items-center justify-center modal"
        style="display:none;">
        <div class="w-full max-w-lg p-6 bg-white dark:bg-gray-800 rounded-2xl">
            <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Tambah Penitipan</h3>
            <form action="{{ route('penitipan.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-3">
                    <input type="text" name="nama_penitip" placeholder="Nama Penitip" required
                        class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                    <input type="date" name="tanggal_titip" required
                        class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button"
                        onclick="document.getElementById('modalTambahPenitipan').style.display='none'"
                        class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button class="px-4 py-2 text-white bg-blue-600 rounded" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto show filter section if there are active filters
        @if (request()->hasAny(['nama_penitip', 'tanggal_dari', 'tanggal_sampai']))
            document.getElementById('filterSection').classList.remove('hidden');
        @endif

        // Toggle detail row
        function toggleDetail(detailId) {
            const detailRow = document.getElementById(detailId);
            if (detailRow.classList.contains('hidden')) {
                detailRow.classList.remove('hidden');
            } else {
                detailRow.classList.add('hidden');
            }
        }
    </script>

</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Detail Transaksi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">

                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-medium dark:text-gray-100">
                        List Detail Transaksi
                    </h3>

                    <button onclick="document.getElementById('modalTambah').style.display='flex'"
                        class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                        Tambah Detail
                    </button>
                </div>

                <div class="w-full overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-900">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-900 dark:text-gray-300">
                                    Barang
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-900 dark:text-gray-300">
                                    Jumlah
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-900 dark:text-gray-300">
                                    Harga Jual
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-900 dark:text-gray-300">
                                    Subtotal
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-900 dark:text-gray-300">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-gray-100 divide-y dark:bg-gray-800 dark:divide-gray-700">

                            @forelse ($details as $d)
                                <tr>
                                    <td class="px-4 py-2">{{ $d->barang->nama_barang }}</td>
                                    <td class="px-4 py-2">{{ $d->jumlah }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($d->harga_jual, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>

                                    <td class="px-4 py-2 space-x-2">
                                        <button
                                            onclick="document.getElementById('modalEdit-{{ $d->id_transaksi_detail }}').style.display='flex'"
                                            class="px-3 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                            Edit
                                        </button>

                                        <button
                                            onclick="document.getElementById('modalDelete-{{ $d->id_transaksi_detail }}').style.display='flex'"
                                            class="px-3 py-1 text-white bg-red-500 rounded hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>

                                {{-- Modal Edit --}}
                                <div id="modalEdit-{{ $d->id_transaksi_detail }}"
                                    class="fixed inset-0 z-50 flex items-center justify-center" style="display:none;">

                                    <div class="w-full max-w-lg p-6 bg-white dark:bg-gray-800 rounded-xl">

                                        <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Edit Detail</h3>

                                        <form action="{{ route('transaksiDetail.update', $d->id_transaksi_detail) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="grid grid-cols-1 gap-3">

                                                <select name="id_barang" required
                                                    class="px-3 py-2 border rounded dark:bg-gray-700">
                                                    @foreach ($barangs as $b)
                                                        <option value="{{ $b->id_barang }}"
                                                            {{ $b->id_barang == $d->id_barang ? 'selected' : '' }}>
                                                            {{ $b->nama_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <input type="number" name="jumlah" value="{{ $d->jumlah }}"
                                                    required class="px-3 py-2 border rounded dark:bg-gray-700">

                                                <input type="number" name="harga_jual" value="{{ $d->harga_jual }}"
                                                    required class="px-3 py-2 border rounded dark:bg-gray-700">
                                            </div>

                                            <div class="flex justify-end gap-3 mt-4">
                                                <button type="button"
                                                    onclick="document.getElementById('modalEdit-{{ $d->id_transaksi_detail }}').style.display='none'"
                                                    class="px-4 py-2 bg-gray-300 rounded">
                                                    Batal
                                                </button>

                                                <button type="submit"
                                                    class="px-4 py-2 text-white bg-yellow-600 rounded">
                                                    Update
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                </div>

                                {{-- Modal Delete --}}
                                <div id="modalDelete-{{ $d->id_transaksi_detail }}"
                                    class="fixed inset-0 z-50 flex items-center justify-center" style="display:none;">

                                    <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-xl">
                                        <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Hapus Detail</h3>
                                        <p class="mb-4 dark:text-gray-100">Yakin ingin menghapus item ini?</p>

                                        <form action="{{ route('transaksiDetail.destroy', $d->id_transaksi_detail) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <div class="flex justify-end gap-3">
                                                <button type="button"
                                                    onclick="document.getElementById('modalDelete-{{ $d->id_transaksi_detail }}').style.display='none'"
                                                    class="px-4 py-2 bg-gray-300 rounded">
                                                    Batal
                                                </button>

                                                <button type="submit" class="px-4 py-2 text-white bg-red-600 rounded">
                                                    Hapus
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500 dark:text-gray-300">
                                        Belum ada detail transaksi.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

            </div>

        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="modalTambah" class="fixed inset-0 z-50 flex items-center justify-center" style="display:none;">

        <div class="w-full max-w-lg p-6 bg-white dark:bg-gray-800 rounded-xl">

            <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Tambah Detail Transaksi</h3>

            <form action="{{ route('transaksiDetail.store') }}" method="POST">
                @csrf

                <input type="hidden" name="id_transaksi" value="{{ $id_transaksi }}">

                <div class="grid grid-cols-1 gap-3">

                    <select name="id_barang" required class="px-3 py-2 border rounded dark:bg-gray-700">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $b)
                            <option value="{{ $b->id_barang }}">
                                {{ $b->nama_barang }}
                            </option>
                        @endforeach
                    </select>

                    <input type="number" name="jumlah" placeholder="Jumlah" required
                        class="px-3 py-2 border rounded dark:bg-gray-700">

                    <input type="number" name="harga_jual" placeholder="Harga Jual" required
                        class="px-3 py-2 border rounded dark:bg-gray-700">

                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="document.getElementById('modalTambah').style.display='none'"
                        class="px-4 py-2 bg-gray-300 rounded">
                        Batal
                    </button>

                    <button class="px-4 py-2 text-white bg-blue-600 rounded" type="submit">
                        Simpan
                    </button>
                </div>

            </form>

        </div>

    </div>

</x-app-layout>

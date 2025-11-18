<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Penitipan') }}
        </h2>
    </x-slot>

    <style>
        .modal {
            display: flex;
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
            pointer-events: none;
        }
        .modal[style*="display: flex"] {
            animation: fadeIn 0.3s forwards;
            pointer-events: auto;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95)
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1)
            }
        }
    </style>

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

                <div class="w-full overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left">Nama Penitip</th>
                                <th class="px-4 py-2 text-left">Tanggal Titip</th>
                                <th class="px-4 py-2 text-left">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-gray-100 divide-y dark:bg-gray-800 dark:divide-gray-700">

                            @forelse ($penitipans as $data)
                                <tr>
                                    <td class="px-4 py-2">{{ $data->nama_penitip }}</td>
                                    <td class="px-4 py-2">{{ $data->tanggal_titip }}</td>

                                    <td class="px-4 py-2 space-x-2">
                                        <button onclick="document.getElementById('modalEditPenitipan-{{ $data->id_penitipan }}').style.display='flex'"
                                            class="px-3 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                            Edit
                                        </button>

                                        <button onclick="document.getElementById('modalDeletePenitipan-{{ $data->id_penitipan }}').style.display='flex'"
                                            class="px-3 py-1 text-white bg-red-500 rounded hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>

                                {{-- Modal Edit --}}
                                <div id="modalEditPenitipan-{{ $data->id_penitipan }}"
                                    class="fixed inset-0 z-50 items-center justify-center modal" style="display:none;">

                                    <div class="w-full max-w-lg p-6 bg-white dark:bg-gray-800 rounded-2xl">

                                        <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Edit Data Penitipan</h3>

                                        <form action="{{ route('penitipan.update', $data->id_penitipan) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="grid grid-cols-1 gap-3">

                                                <input type="text" name="nama_penitip"
                                                    value="{{ $data->nama_penitip }}" required
                                                    class="px-3 py-2 border rounded dark:bg-gray-700">

                                                <input type="date" name="tanggal_titip"
                                                    value="{{ $data->tanggal_titip }}" required
                                                    class="px-3 py-2 border rounded dark:bg-gray-700">

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

                                {{-- Modal Delete --}}
                                <div id="modalDeletePenitipan-{{ $data->id_penitipan }}"
                                    class="fixed inset-0 z-50 items-center justify-center modal" style="display:none;">

                                    <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-2xl">

                                        <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Hapus Penitipan</h3>
                                        <p class="mb-4 dark:text-gray-100">Yakin ingin menghapus data ini?</p>

                                        <form action="{{ route('penitipan.destroy', $data->id_penitipan) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <div class="flex justify-end gap-3">

                                                <button type="button"
                                                    onclick="document.getElementById('modalDeletePenitipan-{{ $data->id_penitipan }}').style.display='none'"
                                                    class="px-4 py-2 bg-gray-300 rounded">
                                                    Batal
                                                </button>

                                                <button class="px-4 py-2 text-white bg-red-600 rounded" type="submit">
                                                    Hapus
                                                </button>

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

            </div>

        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="modalTambahPenitipan" class="fixed inset-0 z-50 items-center justify-center modal" style="display:none;">

        <div class="w-full max-w-lg p-6 bg-white dark:bg-gray-800 rounded-2xl">

            <h3 class="mb-4 text-xl font-bold dark:text-gray-100">Tambah Penitipan</h3>

            <form action="{{ route('penitipan.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-3">

                    <input type="text" name="nama_penitip" placeholder="Nama Penitip" required
                        class="px-3 py-2 border rounded dark:bg-gray-700">

                    <input type="date" name="tanggal_titip" required
                        class="px-3 py-2 border rounded dark:bg-gray-700">

                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="document.getElementById('modalTambahPenitipan').style.display='none'"
                        class="px-4 py-2 bg-gray-300 rounded">Batal</button>

                    <button class="px-4 py-2 text-white bg-blue-600 rounded" type="submit">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>

</x-app-layout>

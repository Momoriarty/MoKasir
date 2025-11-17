<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Barang</h3>
                    <a href="{{ route('barang.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded">
                        Tambah Barang
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($barangs as $barang)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $barang->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $barang->nama_barang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $barang->kategori }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $barang->stok }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                        <a href="{{ route('barang.edit', $barang->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">Edit</a>
                                        <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus barang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data barang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

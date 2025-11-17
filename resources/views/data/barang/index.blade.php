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
                    <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded">
                        Tambah Barang
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nama Barang</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Kategori</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Stok</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Harga</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Dummy Data Row 1 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">1</td>
                                <td class="px-6 py-4 whitespace-nowrap">Laptop Acer</td>
                                <td class="px-6 py-4 whitespace-nowrap">Elektronik</td>
                                <td class="px-6 py-4 whitespace-nowrap">10</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp 7.500.000</td>
                                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                    <a href="#"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">Edit</a>
                                    <a href="#"
                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Hapus</a>
                                </td>
                            </tr>
                            <!-- Dummy Data Row 2 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">2</td>
                                <td class="px-6 py-4 whitespace-nowrap">Meja Kayu</td>
                                <td class="px-6 py-4 whitespace-nowrap">Furniture</td>
                                <td class="px-6 py-4 whitespace-nowrap">5</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp 1.200.000</td>
                                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                    <a href="#"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">Edit</a>
                                    <a href="#"
                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Hapus</a>
                                </td>
                            </tr>
                            <!-- Dummy Data Row 3 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">3</td>
                                <td class="px-6 py-4 whitespace-nowrap">Pulpen</td>
                                <td class="px-6 py-4 whitespace-nowrap">Alat Tulis</td>
                                <td class="px-6 py-4 whitespace-nowrap">50</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp 5.000</td>
                                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                    <a href="#"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">Edit</a>
                                    <a href="#"
                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Hapus</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

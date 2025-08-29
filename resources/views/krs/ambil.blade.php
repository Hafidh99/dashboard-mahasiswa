<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ambil Mata Kuliah') }} - {{ $tahunAktif->Nama ?? 'Tahun Akademik Tidak Aktif' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($mataKuliah->isNotEmpty())
                    <form method="POST" action="{{ route('krs.simpan') }}">
                        @csrf
                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ambil</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode MK</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($mataKuliah as $mk)
                                    <tr>
                                        <td class="px-6 py-4 text-center">
                                            <input type="checkbox" name="mk_ids[]" value="{{ $mk->JadwalID }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $mk->MKKode }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $mk->Nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $mk->SKS }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $mk->NamaDosen ? $mk->NamaDosen . ', ' . $mk->Gelar : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $mk->NamaKelas }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $hari[$mk->HariID] ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ \Carbon\Carbon::parse($mk->JamMulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($mk->JamSelesai)->format('H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-8 text-gray-500">Tidak ada mata kuliah yang ditawarkan pada semester ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Simpan KRS
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="text-center py-16 text-gray-500">
                        <p>Tidak ada mata kuliah yang ditawarkan pada semester ini.</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ambil Mata Kuliah') }} - {{ $tahunAktif->Nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-content">
                    <form method="POST" action="{{ route('krs.simpan') }}">
                        @csrf
                        <div class="overflow-x-auto mb-6">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Ambil</th>
                                        <th>Kode MK</th>
                                        <th>Nama Mata Kuliah</th>
                                        <th>SKS</th>
                                        <th>Dosen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($mataKuliah as $mk)
                                        <tr>
                                            <td><input type="checkbox" name="mk_ids[]" value="{{ $mk->JadwalID }}" class="rounded"></td>
                                            <td>{{ $mk->MKKode }}</td>
                                            <td>{{ $mk->Nama }}</td>
                                            <td>{{ $mk->SKS }}</td>
                                            <td>{{ $mk->NamaDosen ? $mk->NamaDosen . ', ' . $mk->Gelar : '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-gray-500">Tidak ada mata kuliah yang ditawarkan pada semester ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-green w-auto mt-0">
                            Simpan KRS
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

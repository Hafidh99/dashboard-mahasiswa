<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cetak Dokumen')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                font-size: 9pt;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid black;
                padding: 4px;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 bg-white">
        <div class="flex justify-end mb-4 no-print">
            <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Cetak
            </button>
        </div>
        @yield('content')
    </div>
</body>
</html>
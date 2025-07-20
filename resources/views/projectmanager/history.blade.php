<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoveIt | Project Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md min-h-screen fixed">
        @include('projectmanager.sidebar')
    </aside>

    <div class="flex-1 flex flex-col pl-64">
        <!-- Header -->
        <header class="fixed top-0 w-[calc(100%-16rem)] shadow-md z-50">
            @include('projectmanager.header')
        </header>
    </div>

    <!-- History -->
    <div class="bg-white mt-[125px] ml-[275px] mr-[20px] rounded-xl shadow-lg flex-col">
        <div>
            <div class="border border-black rounded-lg w-full">
                <form method="GET" action="{{ route('projectmanager.history') }}" class="py-3 px-4 rounded-lg">
                    <div class="border border-black rounded-lg relative max-w-xs">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="py-2 px-3 ps-9 block w-full border-gray-200 shadow-sm rounded-lg text-sm 
               focus:z-10 focus:border-gray-500 focus:ring-gray-500 disabled:opacity-50 disabled:pointer-events-none 
               dark:bg-gray-100 dark:border-gray-600 dark:text-black dark:placeholder-gray-700 dark:focus:ring-gray-500"
                            placeholder="Search Project Title">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>
                </form>
                <div class="flex flex-col gap-2 w-full">
                    <table class="min-w-full">
                        <thead class="bg-white">
                            <tr class="border border-black bg-gray-800 text-white">
                                <th class="text-left p-3">Project Manager</th>
                                <th class="text-left p-3">Project Title</th>
                                <th class="text-left w-xl p-3">Status</th>
                                <th class="text-center p-3">Document</th>
                                <th class="text-center p-3">Update</th>
                            </tr>
                        </thead>
                        <tbody class="text-md divide-y divide-black">
                            @foreach ($historyprojects as $history)
                                <tr>
                                    <td class="p-3">{{ $history['ProjectManager'] }}</td>
                                    <td class="p-3">{{ $history['Title'] }}</td>
                                    <td class="p-3">
                                        @php
                                            $status = $history['Status'];
                                            $badgeClass = '';

                                            if ($status === 'Finish') {
                                                $badgeClass = 'bg-green-600 text-white';
                                            } elseif ($status === 'On Progress') {
                                                $badgeClass = 'bg-yellow-400 text-white';
                                            }
                                        @endphp

                                        <span class="inline-flex items-center {{ $badgeClass }} text-sm font-medium px-2.5 py-0.5 rounded-full">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="text-center p-3">
                                        @if (isset($docsHistory[$history['Id']]))
                                            @foreach ($docsHistory[$history['Id']] as $docshistory)
                                                <a href="{{ route('projectmanager.history.document', $docshistory['ProjectId']) }}" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-gray-600
                                                hover:text-gray-800 focus:outline-none focus:text-gray-800 disabled:opacity-50 disabled:pointer-events-none dark:text-gray-500 dark:hover:text-gray-400
                                                dark:focus:text-gray-400">Click Here</a>
                                            @endforeach
                                        @else
                                            <a href="{{ route('projectmanager.history.document', $history['ProjectId']) }}" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-gray-600
                                            hover:text-gray-800 focus:outline-none focus:text-gray-800 disabled:opacity-50 disabled:pointer-events-none dark:text-gray-500 dark:hover:text-gray-400
                                            dark:focus:text-gray-400">Click Here</a>
                                        @endif
                                    </td>
                                    <td class="text-center p-3">
                                        <a href="{{ route('projectmanager.history.updateproject', $history['Id']) }}" type="button" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-gray-600
                                        hover:text-gray-800 focus:outline-none focus:text-gray-800 disabled:opacity-50 disabled:pointer-events-none dark:text-gray-500 dark:hover:text-gray-400
                                        dark:focus:text-gray-400">Click Here</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    @if (session('success'))
        <div id="toast-success" class="fixed bottom-4 right-4 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white border border-gray-200 rounded-lg shadow-sm" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <span class="sr-only">Check icon</span>
            </div>
            <div class="ms-3 text-sm font-bold text-black">{{ session('success') }}</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#toast-success" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
    @endif
</body>

</html>
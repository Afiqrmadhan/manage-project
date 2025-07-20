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

    <div class="mt-[125px] ml-[275px] mr-[20px] text-xl font-bold text-center underline">
        <h2>
            Project Title: {{ $project['Title'] }}
        </h2>
    </div>

    <form id="mainForm" action="" method="post">
        @csrf
        @method('PUT')
        <!-- feature -->
        <div class="mt-[25px] ml-[275px] mr-[20px] rounded-xl flex justify-between bg-gray-100 p-5 gap-40">
            <!-- Button Save UAT Table -->
            <button type="submit" id="saveButton"
                class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 
        focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-1/2 
        px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 
        dark:focus:ring-gray-800">
                Save UAT Table
            </button>

            <!-- Button Generate PDF -->
            <a href="{{ isset($project['Id']) ? route('projectmanager.generatepdf', $project['Id']) : '#' }}"
                class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 
        focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-1/2 
        px-5 py-2.5 text-center inline-block dark:bg-green-600 dark:hover:bg-green-700 
        dark:focus:ring-green-800">
                Generate PDF
            </a>
        </div>
        <div class="bg-white mt-[25px] ml-[275px] mr-[20px] rounded-xl shadow-lg flex-col">
            <div>
                <div class="border border-black rounded-lg w-full">
                    <div class="flex flex-col gap-2 w-full">
                        <table class="min-w-full">
                            <thead class="bg-white">
                                <tr class="bg-gray-800 text-white">
                                    <th class="text-left p-3">No</th>
                                    <th class="text-left p-3">Feature</th>
                                    <th class="text-left p-3">Date</th>
                                    <th class="text-left w-xl p-3">Status Validation Desnet</th>
                                    <th class="text-left p-3">Status Validation Client</th>
                                    <th class="text-left p-3">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="text-md divide-y divide-black">
                                @php $no = 1; @endphp
                                <!-- Form -->

                                @foreach ($features as $feature)
                                    <tr>
                                        <input type="hidden" name="FeatureId[]" value="{{ $feature['Id'] }}">
                                        <td class="p-3">{{ $no++ }}.</td>
                                        </td>
                                        <td class="p-3">{{ $feature['Feature'] }}</td>
                                        <td class="p-3">
                                            <input type="date" name="UATDate[]" class="bg-white border text-black
                                        text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                                placeholder="UAT Date" value="{{ $feature['UATDate'] }}">
                                        </td>
                                        <td class="p-3">
                                            <select name="ValidationStatus[]" class="bg-white border text-black text-sm rounded-lg w-full p-2.5 
                                    focus:outline-none focus:ring-2 focus:ring-gray-500">
                                                <option value="" selected>Status Validation Desnet</option>
                                                <option value="Worked" {{ $feature['ValidationStatus'] == 'Worked' ? 'selected' : '' }}>Worked</option>
                                                <option value="Failed" {{ $feature['ValidationStatus'] == 'Failed' ? 'selected' : '' }}>Failed</option>
                                            </select>
                                        </td>
                                        <td class="text-center p-3">
                                            <select name="ClientFeedbackStatus[]" class="bg-white border text-black text-sm rounded-lg w-full p-2.5 
                                    focus:outline-none focus:ring-2 focus:ring-gray-500">
                                                <option value="" selected>Status Validation Client</option>
                                                <option value="Accepted" {{ $feature['ClientFeedbackStatus'] == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                                <option value="Revision" {{ $feature['ClientFeedbackStatus'] == 'Revision' ? 'selected' : '' }}>Revision</option>
                                            </select>
                                        </td>
                                        <td class="text-center p-3">
                                            <textarea id="message" rows="4" name="RevisionNotes[]" class="bg-white border text-black text-sm rounded-lg w-full p-2.5 
                                    focus:outline-none focus:ring-2 focus:ring-gray-500" placeholder="Leave a comment...">{{ $feature['RevisionNotes'] }}</textarea>
                                        </td>
                                    </tr>
                                @endforeach
    </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <!-- Toast Notification -->
    @if (session('error'))
        <div id="toast-danger" class="fixed bottom-4 right-4 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white border border-gray-200 rounded-lg shadow-sm" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                </svg>
                <span class="sr-only">Error icon</span>
            </div>
            <div class="ms-3 text-sm font-bold text-black">{{ session('error') }}</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#toast-danger" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
    @endif
        
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

    <script>
        document.getElementById('saveButton').addEventListener('click', function() {
            document.getElementById('mainForm').action = '{{ route("projectmanager.featureuat.update") }}';
        });
    </script>
</body>

</html>
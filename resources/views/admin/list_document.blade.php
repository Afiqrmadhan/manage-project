<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MoveIt | Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">
  <!-- Sidebar -->
  <aside class="w-64 bg-white shadow-md min-h-screen fixed">
    @include('admin.sidebar')
  </aside>

  <div class="flex-1 flex flex-col pl-64">
    <!-- Header -->
    <header class="fixed top-0 w-[calc(100%-16rem)] shadow-md z-50">
      @include('admin.header')
    </header>
  </div>

  <!-- Judul Project -->
  <div class="mt-[125px] ml-[275px] mr-[20px] text-xl font-bold text-center underline">
    <h2>
      Project Title: {{ $projectTitle }}
    </h2>
  </div>

  <!-- List -->
  <div class="justify-center bg-white mt-5 ml-[275px] mr-[20px] rounded-xl shadow-lg flex-col">
    <div>
      <div class="border border-black rounded-lg  w-full">
        <form method="GET" action="{{ route('admin.history.document', $Id) }}" class="py-3 px-4 rounded-lg">
          <div class="border border-black rounded-lg relative max-w-xs">
            <input type="text" name="search" value="{{ request('search') }}"
              class="py-2 px-3 ps-9 block w-full border-gray-200 shadow-sm rounded-lg text-sm 
               focus:z-10 focus:border-gray-500 focus:ring-gray-500 disabled:opacity-50 disabled:pointer-events-none 
               dark:bg-gray-100 dark:border-gray-600 dark:text-black dark:placeholder-gray-700 dark:focus:ring-gray-500"
              placeholder="Search Date Updated">
            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
              <i class="fa-solid fa-magnifying-glass"></i>
            </div>
          </div>
        </form>

        <div class="flex flex-col gap-2 w-full">
          <table class="min-w-full">
            <thead class="bg-white">
              <tr class="border border-black bg-gray-800 text-white">
                <th class="text-left p-3">Date Updated</th>
                <th class="text-left p-3">Document</th>
              </tr>
            </thead>
            <tbody class="text-md divide-y divide-black">
              @foreach ($docshistory as $docs)
                <tr>
                  <td class="p-3">{{ $docs['DateAdded'] }}</td>
                  <td class="p-3">
                    <a href="{{ route('admin.download', $docs['Id']) }}" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-gray-600
                        hover:text-gray-800 focus:outline-none focus:text-gray-800 disabled:opacity-50 disabled:pointer-events-none dark:text-gray-500 dark:hover:text-gray-400
                        dark:focus:text-gray-400">
                            <i class="fa-regular fa-file" title="{{ $docs['Document'] }}"></i>
                        </a>
                    </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</body>

</html>
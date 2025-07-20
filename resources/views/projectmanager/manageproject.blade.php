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
  <!-- Sidebar dengan z-index tinggi -->
  <aside class="w-64 bg-white shadow-md min-h-screen fixed z-20">
    @include('projectmanager.sidebar')
  </aside>

  <!-- Konten utama -->
  <div class="flex-1 flex flex-col">
    <!-- Header dengan z-index lebih rendah -->
     <div class="flex pl-64">
         <header class="fixed top-0 w-[calc(100%-16rem)] shadow-md z-10">
           @include('projectmanager.header')
         </header>
     </div>
    
    <!-- Manage Project Section -->
    <div class="bg-white mt-[125px] ml-[275px] mr-[20px] p-6 rounded-xl shadow-lg">
      <h2 class="text-3xl font-bold text-center">Manage Features</h2>
      <p class="mt-[5px] text-gray-400 text-center text-lg">{{ $project['Title'] }}</p>
      <!-- Form dan konten lainnya -->
      <form action="{{ route('projectmanager.manageproject.save') }}" method="post" class="mt-6">
        @csrf
        <input type="hidden" name="ProjectId" value="{{ $project['Id'] }}">
        <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white font-medium rounded-lg text-sm px-5 py-2.5">
          Make UAT Table
        </button>
        <div id="feature-list" class="mt-4">
          <div class="feature-item flex gap-2 mb-2">
            <span class="feature-number font-bold">1.</span>
            <input type="text" name="Feature[]" class="border p-2 w-full rounded" placeholder="Enter Feature" required>
            <button type="button" onclick="removeFeature(this)" class="bg-red-500 text-white px-4 py-2 rounded-lg">Delete</button>
          </div>
        </div>
        <button type="button" onclick="addFeature()" class="bg-gray-500 text-white px-4 py-2 rounded-lg mt-4">Add Feature</button>
      </form>
      <!-- Daftar Fitur yang Sudah Ada -->
      <h3 class="text-xl font-semibold mt-8">Saved Features</h3>
      <ul class="mt-4">
        @php $no = 1; @endphp
        @foreach ($features as $feature)
          <li class="flex justify-between items-center p-2 gap-2 rounded-lg mb-2">
            <span class="feature-number font-bold">{{ $no++ }}.</span>
            <form action="{{ route('projectmanager.manageproject.update', $feature['Id']) }}" method="post" class="flex w-full gap-2">
              @csrf
              @method('PUT')
              <input type="text" name="Feature" value="{{ $feature['Feature'] }}" class="border p-2 w-full rounded">
              <button type="submit" class="bg-yellow-500 text-white px-4 py-2 mx-2 rounded-lg">Update</button>
            </form>
            <button data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="bg-red-500 text-white px-4 py-2 rounded-lg">Delete</button>
            <!-- Modal Delete -->
            <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[1000] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
              <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm">
                  <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="popup-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                  </button>
                  <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500">Are you sure you want to delete this feature?</h3>
                    <a href="{{ route('projectmanager.manageproject.delete', $feature['Id']) }}" data-modal-hide="popup-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                      Yes, I'm sure
                    </a>
                    <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-gray-700 focus:z-10 focus:ring-4 focus:ring-gray-100">No, cancel</button>
                  </div>
                </div>
              </div>
            </div>
          </li>
        @endforeach
      </ul>
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

  <!-- JavaScript -->
  <script>
    function addFeature() {
      let container = document.getElementById("feature-list");
      let div = document.createElement("div");
      div.classList.add("feature-item", "flex", "gap-2", "mb-2");

      let featureNumber = container.children.length + 1;

      div.innerHTML = `
                <span class="feature-number font-bold">${featureNumber}.</span>
                <input type="text" name="Feature[]" class="border p-2 w-full rounded" placeholder="Input feature" required>
                <button type="button" onclick="removeFeature(this)" class="bg-red-500 text-white px-4 py-2 rounded-lg">Delete</button>
            `;
      container.appendChild(div);
      updateFeatureNumbers();
    }

    function removeFeature(button) {
      button.parentElement.remove();
      updateFeatureNumbers();
    }

    function updateFeatureNumbers() {
      let featureItems = document.querySelectorAll(".feature-item .feature-number");
      featureItems.forEach((item, index) => {
        item.textContent = (index + 1) + ".";
      });
    }
  </script>
</body>

</html>

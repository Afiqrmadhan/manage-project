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

        <!-- Update New Project -->
        <div class="justify-center bg-white mt-[125px] ml-[275px] mr-[20px] h-[585px] rounded-xl shadow-lg grid grid-cols-2">
            <div class="my-auto text-black text-center text-3xl font-sans font-bold">
                    <h2> Update Project </h2>
                        <form action="{{ route('projectmanager.history.updateproject.save') }}" method="post" enctype="multipart/form-data" class="max-w-sm mx-auto">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="Id" value="{{ $history['Id'] }}"> 
                            
                            <p class="bg-white border text-black text-left
                            text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-gray-500 mt-10" placeholder="Project Manager">{{ $history['ProjectManager'] }}</p>

                            <p class="bg-white border text-black text-left
                            text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-gray-500 mt-3" placeholder="Project Title">{{ $history['Title'] }}</p>

                            <!-- Dropdown Status -->
                            <select name="ProjectStatus" class="bg-white border text-black text-sm rounded-lg w-full p-2.5 
                            focus:outline-none focus:ring-2 focus:ring-gray-500 mt-3" required>
                                <option value="" selected>Project Status</option>
                                <option value="On Progress" {{ $history['Status'] == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                <option value="Finish" {{ $history['Status'] == 'Finish' ? 'selected' : '' }}>Finish</option>
                            </select>

                            
                            <div class="mt-3 mb-10">
                                <input type="file" name="Document" class="w-full text-gray-400 font-bold text-sm bg-white border file:cursor-pointer 
                                    cursor-pointer file:border-0 file:py-3 file:px-4 file:mr-4 file:bg-gray-100 file:hover:bg-gray-200 file:text-gray-500 rounded-lg" />
                            </div>


                            <button type="submit" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 
                            focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full 
                            px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 
                            dark:focus:ring-gray-800">Submit</button>
                        </form>
            </div>
            <div class="items-center flex justify-center">
                <img src="{{ asset('img/updateproject.png') }}" alt="update">
            </div>
        </div>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoveIt | Project Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<!-- Side Bar -->
<div class="bg-white h-screen w-[250px] flex flex-col p-4">
    <div class = "ml-6 mt-6">
        <img src="{{ asset('img/logo.png') }}" alt="desnet logo" class="w-3/5" >
    </div>
        <!-- Dashboard -->
        <a href="{{ route('projectmanager.dashboard') }}" class="flex items-center space-x-3 mt-[55px] text-black p-3 rounded-xl cursor-pointer hover:text-white hover:bg-gray-800 ">
            <i class="fa-regular fa-calendar"></i>
            <span class="font-semibold">Dashboard</span>
        </a>
        
        <!-- Menu Items -->
        <a href="{{ route('projectmanager.addnewproject') }}" class="flex items-center space-x-3 text-black p-3 rounded-xl cursor-pointer hover:text-white hover:bg-gray-800 ">
            <i class="fa-solid fa-plus"></i>
            <span class="font-semibold">Create New Project</span>
        </a>
        
        <a href="{{ route('projectmanager.listproject') }}" class="flex items-center space-x-3 text-black p-3 rounded-xl cursor-pointer hover:text-white hover:bg-gray-800 ">
            <i class="fa-regular fa-file"></i>
            <span class="font-semibold">Manage Project</span>
        </a>
    
        <a href="{{ route('projectmanager.history') }}" class="flex items-center space-x-3 text-black p-2.5 rounded-xl cursor-pointer hover:text-white hover:bg-gray-800 ">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span class="font-semibold">History</span>
        </a>

        <!-- Logout -->
        <a href="{{ route('logout') }}" class="mt-auto">
            <div class="flex items-center space-x-3 text-black p-2.5 rounded-xl cursor-pointer hover:text-white hover:bg-gray-800 ">
              <i class="fa-solid fa-arrow-right-from-bracket"></i>
             <span class="font-medium">Logout</span>
            </div>
        </a>
    </div>
</div>
</html>
<!-- Side Bar -->
<div class="bg-white flex h-screen w-[250px] flex-col p-4">
    <div class = "ml-6 mt-6">
        <img src="{{ asset('img/logo.png') }}" alt="desnet logo" class="w-3/5" >
    </div>

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 mt-[55px] text-black p-3 rounded-xl cursor-pointer hover:text-white hover:bg-gray-800 ">
            <i class="fa-regular fa-calendar"></i>
            <span class="font-semibold">Dashboard</span>
        </a>
        
        <!-- Menu Items -->
        <a href="{{ route('admin.addnewproject') }}" class="flex items-center space-x-3 text-black p-3 rounded-xl cursor-pointer hover:text-white hover:bg-gray-800 ">
            <i class="fa-solid fa-plus"></i>
            <span class="font-semibold">Create New Project</span>
        </a>
    
        <a href="{{ route('admin.history') }}" class="flex items-center space-x-3 text-black p-2.5 rounded-xl cursor-pointer hover:text-white hover:bg-gray-800 ">
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
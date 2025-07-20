<div class="flex bg-white p-7 top-0 left-0 w-full">
    <div class="grid grid-cols-2 ml-auto pr-10">
        <div class="items-center flex justify-center">
            <img src="{{ asset('img/user.png') }}" alt="header">
        </div>
        <div>
            <h1 class="text-bs font-medium text-black">
                {{ $username }}
            </h1>
            <h2 class="text-sm font-light">
                {{ $level }}
            </h2>
        </div>
    </div>
</div>
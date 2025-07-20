<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoveIt | Project Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

    <!-- Atas -->
    <div class=" mt-[125px] ml-[275px] mr-[20px] h-[200px] grid grid-cols-3 gap-10">
        <div class="bg-white rounded-xl shadow-lg p-5 justify-center">
            <div class="text-black text-3xl font-bold">
                <h2>Total Project Assigned</h2>
            </div>
            <div class="mt-2.5 text-black text-7xl font-bold">
                <h2>{{ $totalProjects }}</h2>
            </div>
            <div class="mt-2.5 text-gray-700 text-xl font-normal">
                <h2>Focused on Project Growth 🚀</h2>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-5 justify-center">
            <div class="text-black text-3xl font-bold">
                <h2>Total Project Finished</h2>
            </div>
            <div class="mt-2.5 text-black text-7xl font-bold">
                <h2>{{ $finishedProjects }}</h2>
            </div>
            <div class="mt-2.5 text-gray-700 text-xl font-normal">
                <h2>Bringing Projects to The Finish Line ✨</h2>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-5 justify-center">
            <div class="flex justify-between items-center">
                <button id="prevMonth" class="text-gray-500 hover:text-black text-sm">&lt;</button>
                <h2 id="calendarTitle" class="text-sm font-semibold"></h2>
                <button id="nextMonth" class="text-gray-500 hover:text-black text-sm">&gt;</button>
            </div>

            <div class="grid grid-cols-7 text-center font-semibold mt-0.5 text-xs">
                <span>Mo</span> <span>Tu</span> <span>We</span> <span>Th</span> <span>Fr</span> <span>Sa</span> <span>Su</span>
            </div>

            <div id="calendarDays" class="grid grid-cols-7 gap-1 mt-0.5 text-xs text-center"></div>

        </div>
    </div>

    <!-- On progress -->
    <div class="bg-white mt-[52px] ml-[275px] mr-[20px] rounded-xl shadow-lg">
        <div>
            <div class="border border-black rounded-lg w-full">
                <form method="GET" action="{{ route('projectmanager.dashboard') }}" class="py-3 px-4 rounded-lg">
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const calendarDays = document.getElementById("calendarDays");
        const calendarTitle = document.getElementById("calendarTitle");
        const prevMonthBtn = document.getElementById("prevMonth");
        const nextMonthBtn = document.getElementById("nextMonth");

        let currentDate = new Date();
        let websiteLaunchDate = new Date(2022, 6, 15); // 15 Juli 2022
        let today = new Date(); // Menyimpan tanggal saat ini

        function renderCalendar() {
            calendarDays.innerHTML = "";
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const firstDayIndex = firstDay.getDay();
            const lastDate = lastDay.getDate();

            calendarTitle.innerText = firstDay.toLocaleString('en-US', {
                month: 'long',
                year: 'numeric'
            });

            for (let i = 1; i < firstDayIndex; i++) {
                calendarDays.innerHTML += `<div></div>`; // Empty slots
            }

            for (let i = 1; i <= lastDate; i++) {
                let dayClass = "py-1 px-2 rounded-full cursor-pointer";

                // Jika tanggal adalah tanggal launching website
                if (currentDate.getFullYear() === websiteLaunchDate.getFullYear() &&
                    currentDate.getMonth() === websiteLaunchDate.getMonth() &&
                    i === websiteLaunchDate.getDate()) {
                    dayClass += " bg-gray-500 text-white font-bold"; // Highlight tanggal launching
                }

                // Jika tanggal adalah hari ini (tanggal saat ini)
                if (currentDate.getFullYear() === today.getFullYear() &&
                    currentDate.getMonth() === today.getMonth() &&
                    i === today.getDate()) {
                    dayClass += " bg-gray-700 text-white font-bold"; // Highlight tanggal hari ini
                }

                calendarDays.innerHTML += `<div class="${dayClass}">${i}</div>`;
            }
        }

        prevMonthBtn.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        nextMonthBtn.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });

        renderCalendar();
    </script>


</body>

</html>
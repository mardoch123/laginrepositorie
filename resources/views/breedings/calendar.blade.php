<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Calendrier des portées
                        </h2>
                        <a href="{{ route('breedings.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Nouvelle portée
                        </a>
                    </div>

                    <div class="mb-4">
                        <div class="flex flex-wrap gap-2">
                            <div class="flex items-center">
                                <span class="inline-block w-4 h-4 bg-blue-500 rounded-full mr-2"></span>
                                <span class="text-sm text-gray-600">Accouplement</span>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-block w-4 h-4 bg-orange-400 rounded-full mr-2"></span>
                                <span class="text-sm text-gray-600">Naissance prévue</span>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-block w-4 h-4 bg-green-500 rounded-full mr-2"></span>
                                <span class="text-sm text-gray-600">Naissance réelle</span>
                            </div>
                        </div>
                    </div>

                    <div id="calendar" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclure FullCalendar -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },
                events: @json($events),
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                }
            });
            calendar.render();
        });
    </script>
</x-app-layout>
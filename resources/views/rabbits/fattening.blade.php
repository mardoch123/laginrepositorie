<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion de l\'engraissement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <!-- Section des portées en engraissement -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Portées en engraissement</h3>
                        
                        @if($weanedBreedings->isEmpty())
                            <p class="text-gray-500 italic">Aucune portée en engraissement pour le moment.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mère</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Père</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de naissance</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Âge</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de lapereaux</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mâles/Femelles</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($weanedBreedings as $breeding)
                                            <tr>
                                                <td class="py-2 px-4 border-b border-gray-200">{{ $breeding->id }}</td>
                                                <td class="py-2 px-4 border-b border-gray-200">{{ $breeding->mother->name }}</td>
                                                <td class="py-2 px-4 border-b border-gray-200">{{ $breeding->father->name }}</td>
                                                <td class="py-2 px-4 border-b border-gray-200">{{ $breeding->actual_birth_date->format('d/m/Y') }}</td>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    {{ $breeding->actual_birth_date->diffInDays(now()) }} jours
                                                </td>
                                                <td class="py-2 px-4 border-b border-gray-200">{{ $breeding->number_of_kits }}</td>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    <span class="text-blue-600">♂ {{ $breeding->number_of_males ?? 0 }}</span> / 
                                                    <span class="text-pink-600">♀ {{ $breeding->number_of_females ?? 0 }}</span>
                                                </td>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('breedings.show', $breeding->id) }}" class="text-blue-600 hover:text-blue-900">
                                                            Détails
                                                        </a>
                                                        <a href="{{ route('breedings.edit', $breeding->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                                            Modifier
                                                        </a>
                                                        <button onclick="openWeightModal('breeding', {{ $breeding->id }}, '{{ $breeding->mother->name }} - Portée')" class="text-green-600 hover:text-green-900">
                                                            Peser
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Section des lapins individuels en engraissement -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Lapins individuels en engraissement</h3>
                        
                        @if($fatteningRabbits->isEmpty())
                            <p class="text-gray-500 italic">Aucun lapin individuel en engraissement pour le moment.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sexe</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Âge</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cage</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poids</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($fatteningRabbits as $rabbit)
                                            <tr>
                                                <td class="py-2 px-4 border-b border-gray-200">{{ $rabbit->id }}</td>
                                                <td class="py-2 px-4 border-b border-gray-200">{{ $rabbit->name }}</td>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    @if($rabbit->gender == 'male')
                                                        <span class="text-blue-600">♂ Mâle</span>
                                                    @elseif($rabbit->gender == 'female')
                                                        <span class="text-pink-600">♀ Femelle</span>
                                                    @else
                                                        <span class="text-gray-500">Inconnu</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    @if($rabbit->birth_date)
                                                        {{ $rabbit->birth_date->diffInDays(now()) }} jours
                                                    @else
                                                        Inconnu
                                                    @endif
                                                </td>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    {{ $rabbit->cage ? $rabbit->cage->name : 'Non assigné' }}
                                                </td>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    {{ $rabbit->current_weight ? $rabbit->current_weight . ' g' : 'Non pesé' }}
                                                </td>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('rabbits.show', $rabbit->id) }}" class="text-blue-600 hover:text-blue-900">
                                                            Détails
                                                        </a>
                                                        <a href="{{ route('rabbits.edit', $rabbit->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                                            Modifier
                                                        </a>
                                                        <button onclick="openWeightModal('rabbit', {{ $rabbit->id }}, '{{ $rabbit->name }}')" class="text-green-600 hover:text-green-900">
                                                            Peser
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Section pour ajouter des lapins à l'engraissement -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ajouter des lapins à l'engraissement</h3>
                        
                        <form action="{{ route('kits.start-fattening') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="rabbit_ids" class="block text-sm font-medium text-gray-700 mb-1">Sélectionner des lapins</label>
                                    <select id="rabbit_ids" name="rabbit_ids[]" multiple class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" size="5">
                                        @foreach(App\Models\Rabbit::where('status', 'active')->where('category', 'kit')->whereNotNull('weaning_date')->get() as $rabbit)
                                            <option value="{{ $rabbit->id }}">
                                                {{ $rabbit->name }} ({{ $rabbit->gender == 'male' ? '♂' : ($rabbit->gender == 'female' ? '♀' : '?') }}) - 
                                                @if($rabbit->birth_date)
                                                    {{ $rabbit->birth_date->diffInDays(now()) }} jours
                                                @else
                                                    Âge inconnu
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs lapins</p>
                                </div>
                                
                                <div>
                                    <label for="cage_id" class="block text-sm font-medium text-gray-700 mb-1">Cage d'engraissement</label>
                                    <select id="cage_id" name="cage_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">Sélectionner une cage</option>
                                        @foreach($cages as $cage)
                                            <option value="{{ $cage->id }}">{{ $cage->name }} ({{ $cage->type }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea id="notes" name="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                </div>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Démarrer l'engraissement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour enregistrer le poids -->
    <div id="weightModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" style="z-index: 50;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Enregistrer le poids</h3>
                <form id="weightForm" action="{{ route('rabbits.record-weight') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" id="record_type" name="record_type" value="rabbit">
                    <input type="hidden" id="record_id" name="record_id">
                    
                    <div class="mb-4">
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-1 text-left">Poids (en grammes)</label>
                        <input type="number" id="weight" name="weight" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="weight_date" class="block text-sm font-medium text-gray-700 mb-1 text-left">Date de la pesée</label>
                        <input type="date" id="weight_date" name="weight_date" value="{{ date('Y-m-d') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    
                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="closeWeightModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Annuler
                        </button>
                        <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openWeightModal(type, id, name) {
            document.getElementById('record_type').value = type;
            document.getElementById('record_id').value = id;
            document.getElementById('modalTitle').textContent = 'Enregistrer le poids pour ' + name;
            document.getElementById('weightModal').classList.remove('hidden');
        }
        
        function closeWeightModal() {
            document.getElementById('weightModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
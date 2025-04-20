<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier un lapin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('rabbits.update', $rabbit) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <x-input-label for="name" :value="__('Nom')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $rabbit->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Numéro d'identification -->
                            <div>
                                <x-input-label for="identification_number" :value="__('Numéro d\'identification')" />
                                <x-text-input id="identification_number" class="block mt-1 w-full" type="text" name="identification_number" :value="old('identification_number', $rabbit->identification_number)" required />
                                <x-input-error :messages="$errors->get('identification_number')" class="mt-2" />
                            </div>

                            <!-- Sexe -->
                            <div>
                                <x-input-label for="gender" :value="__('Sexe')" />
                                <select id="gender" name="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="male" {{ old('gender', $rabbit->gender) == 'male' ? 'selected' : '' }}>Mâle</option>
                                    <option value="female" {{ old('gender', $rabbit->gender) == 'female' ? 'selected' : '' }}>Femelle</option>
                                </select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>

                            <!-- Date de naissance -->
                            <div>
                                <x-input-label for="birth_date" :value="__('Date de naissance')" />
                                <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date', $rabbit->birth_date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                            </div>

                            <!-- Race -->
                            <div>
                                <x-input-label for="breed" :value="__('Race')" />
                                <x-text-input id="breed" class="block mt-1 w-full" type="text" name="breed" :value="old('breed', $rabbit->breed)" required />
                                <x-input-error :messages="$errors->get('breed')" class="mt-2" />
                            </div>

                            <!-- Couleur -->
                            <div>
                                <x-input-label for="color" :value="__('Couleur')" />
                                <x-text-input id="color" class="block mt-1 w-full" type="text" name="color" :value="old('color', $rabbit->color)" />
                                <x-input-error :messages="$errors->get('color')" class="mt-2" />
                            </div>

                            <!-- Cage -->
                            <div>
                                <x-input-label for="cage_id" :value="__('Cage')" />
                                <select id="cage_id" name="cage_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">Sélectionner une cage</option>
                                    @foreach($cages as $cage)
                                        <option value="{{ $cage->id }}" {{ old('cage_id', $rabbit->cage_id) == $cage->id ? 'selected' : '' }}>{{ $cage->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('cage_id')" class="mt-2" />
                            </div>

                            <!-- Statut -->
                            <div>
                                <x-input-label for="status" :value="__('Statut')" />
                                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="alive" {{ old('status', $rabbit->status) == 'alive' ? 'selected' : '' }}>Vivant</option>
                                    <option value="dead" {{ old('status', $rabbit->status) == 'dead' ? 'selected' : '' }}>Mort</option>
                                    <option value="sold" {{ old('status', $rabbit->status) == 'sold' ? 'selected' : '' }}>Vendu</option>
                                    <option value="given" {{ old('status', $rabbit->status) == 'given' ? 'selected' : '' }}>Donné</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Actif -->
                            <div class="flex items-center mt-4">
                                <input id="is_active" type="checkbox" name="is_active" value="1" {{ old('is_active', $rabbit->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <x-input-label for="is_active" :value="__('Actif')" class="ml-2" />
                                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('notes', $rabbit->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('rabbits.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                Annuler
                            </a>
                            <x-primary-button>
                                {{ __('Mettre à jour') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
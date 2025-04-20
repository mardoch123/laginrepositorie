<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier la cage') }} : {{ $cage->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('cages.update', $cage) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nom de la cage -->
                        <div>
                            <x-input-label for="name" :value="__('Nom de la cage')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $cage->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Emplacement -->
                        <div>
                            <x-input-label for="location" :value="__('Emplacement')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $cage->location)" />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $cage->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Statut -->
                        <div>
                            <x-input-label for="is_active" :value="__('Statut')" />
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio text-indigo-600" name="is_active" value="1" {{ $cage->is_active ? 'checked' : '' }}>
                                    <span class="ml-2">Active</span>
                                </label>
                                <label class="inline-flex items-center ml-6">
                                    <input type="radio" class="form-radio text-indigo-600" name="is_active" value="0" {{ !$cage->is_active ? 'checked' : '' }}>
                                    <span class="ml-2">Inactive</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('cages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
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
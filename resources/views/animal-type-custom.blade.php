<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-900">
                    Personnaliser le type d'animal
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Veuillez spécifier le type d'animal que vous élevez
                </p>
            </div>

            <form method="POST" action="{{ route('animal.type.custom.store') }}">
                @csrf

                <!-- Type d'animal au pluriel -->
                <div>
                    <x-input-label for="animal_type_plural" value="Nom des animaux (pluriel)" />
                    <x-text-input id="animal_type_plural" class="block mt-1 w-full" type="text" name="animal_type_plural" :value="old('animal_type_plural')" required autofocus placeholder="Ex: Canards" />
                    <x-input-error :messages="$errors->get('animal_type_plural')" class="mt-2" />
                </div>

                <!-- Type d'animal au singulier -->
                <div class="mt-4">
                    <x-input-label for="animal_type_singular" value="Nom de l'animal (singulier)" />
                    <x-text-input id="animal_type_singular" class="block mt-1 w-full" type="text" name="animal_type_singular" :value="old('animal_type_singular')" required placeholder="Ex: Canard" />
                    <x-input-error :messages="$errors->get('animal_type_singular')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ml-3">
                        Enregistrer
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
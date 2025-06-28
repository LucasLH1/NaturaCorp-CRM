<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            Mon profil
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 space-y-8">

            <section class="bg-white shadow rounded-lg p-6">
                @include('profile.partials.update-profile-information-form')
            </section>

            <section class="bg-white shadow rounded-lg p-6">
                @include('profile.partials.update-password-form')
            </section>

            <section class="bg-white shadow rounded-lg p-6">
                @include('profile.partials.delete-user-form')
            </section>

        </div>
    </div>
</x-app-layout>

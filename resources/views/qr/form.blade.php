<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('QR Code Generator') }}
        </h2>
    </x-slot>

    <div id="qr-form" class="py-6">
        {{-- Formulaire classique pour Laravel --}}
        <form action="{{ route('qr.generate') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Boucle sur les sites dynamiques via Vue --}}
            <div v-for="(site, sIndex) in sites" :key="sIndex" class="p-4 border rounded">
                <input v-model="site.name" name="sites[@{{ sIndex }}][name]" placeholder="Nom du site" class="border p-2 w-full">

                <div v-for="(dept, dIndex) in site.departments" :key="dIndex" class="ml-4 mt-2 p-2 border-l">
                    <input v-model="dept.name" name="sites[@{{ sIndex }}][departments][@{{ dIndex }}][name]" placeholder="Nom du département" class="border p-2 w-full">

                    <div v-for="(pole, pIndex) in dept.poles" :key="pIndex" class="ml-4 mt-2 p-2 border-l">
                        <input v-model="pole.name" name="sites[@{{ sIndex }}][departments][@{{ dIndex }}][poles][@{{ pIndex }}][name]" placeholder="Nom du pôle" class="border p-2 w-full">

                        <div v-for="(equip, eIndex) in pole.equipments" :key="eIndex" class="ml-4 mt-2">
                            <input v-model="equip.name" name="sites[@{{ sIndex }}][departments][@{{ dIndex }}][poles][@{{ pIndex }}][equipments][@{{ eIndex }}][name]" placeholder="Nom de l'équipement" class="border p-2 w-full">
                        </div>

                        <button type="button" @click="addEquipment(sIndex, dIndex, pIndex)" class="text-sm text-blue-600 mt-2">+ Équipement</button>
                    </div>

                    <button type="button" @click="addPole(sIndex, dIndex)" class="text-sm text-green-600 mt-2">+ Pôle</button>
                </div>

                <button type="button" @click="addDepartment(sIndex)" class="text-sm text-purple-600 mt-2">+ Département</button>
            </div>

            <button type="button" @click="addSite" class="bg-gray-200 px-3 py-1 rounded">+ Site</button>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Générer le QR Code</button>
        </form>
    </div>
</x-app-layout>


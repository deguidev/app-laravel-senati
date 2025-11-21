<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { Pencil, Trash2, Plus } from 'lucide-vue-next';

interface Marca {
    id: number;
    nombre: string;
    descripcion: string | null;
    activo: boolean;
    created_at: string;
    updated_at: string;
}

defineProps<{
    marcas: Marca[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Marcas',
        href: '/marcas',
    },
];

const deleteMarca = (id: number) => {
    if (!confirm('¿Estás seguro de eliminar esta marca?')) {
        return;
    }
    
    router.delete(`/marcas/${id}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Marcas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Marcas</h1>
                <Link
                    :href="'/marcas/create'"
                    class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                >
                    <Plus :size="20" />
                    Nueva Marca
                </Link>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Nombre
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Descripción
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="marca in marcas" :key="marca.id" class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                {{ marca.id }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                {{ marca.nombre }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ marca.descripcion || '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span
                                    :class="[
                                        'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                                        marca.activo
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800',
                                    ]"
                                >
                                    {{ marca.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                <div class="flex gap-2">
                                    <Link
                                        :href="`/marcas/${marca.id}/edit`"
                                        class="text-blue-600 hover:text-blue-900"
                                        title="Editar"
                                    >
                                        <Pencil :size="18" />
                                    </Link>
                                    <button
                                        @click="deleteMarca(marca.id)"
                                        class="text-red-600 hover:text-red-900"
                                        title="Eliminar"
                                    >
                                        <Trash2 :size="18" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="marcas.length === 0">
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No hay marcas registradas
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

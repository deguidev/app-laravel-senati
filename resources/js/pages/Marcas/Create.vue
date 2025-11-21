<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Marcas',
        href: '/marcas',
    },
    {
        title: 'Nueva Marca',
        href: '/marcas/create',
    },
];

const form = useForm({
    nombre: '',
    descripcion: '',
    activo: true,
});

const submit = () => {
    form.post('/marcas', {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Nueva Marca" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-2xl">
            <div class="mb-6">
                <h1 class="text-3xl font-bold">Nueva Marca</h1>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.nombre"
                            type="text"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.nombre }"
                        />
                        <p v-if="form.errors.nombre" class="mt-1 text-sm text-red-500">
                            {{ form.errors.nombre }}
                        </p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea
                            v-model="form.descripcion"
                            rows="4"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.descripcion }"
                        ></textarea>
                        <p v-if="form.errors.descripcion" class="mt-1 text-sm text-red-500">
                            {{ form.errors.descripcion }}
                        </p>
                    </div>

                    <div class="flex items-center">
                        <input
                            v-model="form.activo"
                            type="checkbox"
                            id="activo"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        />
                        <label for="activo" class="ml-2 text-sm text-gray-700">Activo</label>
                    </div>

                    <div class="flex justify-end gap-3 border-t pt-6">
                        <Link
                            href="/marcas"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Guardando...' : 'Guardar Marca' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

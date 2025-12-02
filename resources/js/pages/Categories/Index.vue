<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import axios from '@/lib/axios';
import { Pencil, Trash2, Plus, FileDown, FileSpreadsheet } from 'lucide-vue-next';


interface Category {
    id: number;
    name: string;
    description: string | null;
    active: boolean;
    created_at: string;
    updated_at: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Categorías',
        href: '/categories',
    },
];

const categories = ref<Category[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);
const showModal = ref(false);
const editingCategory = ref<Category | null>(null);


import { FwbButton, FwbModal } from 'flowbite-vue'

const isShowModal = ref(false)

function closeModalv2 () {
  isShowModal.value = false
}
function showModalv2 () {
  isShowModal.value = true
}

// Formulario
const form = ref({
    name: '',
    description: '',
    active: true,
});

const fetchCategories = async () => {
    loading.value = true;
    error.value = null;
    try {
        const response = await axios.get('/categories-data');
        if (response.data.success) {
            categories.value = response.data.data;
        }
    } catch (err: any) {
        error.value = err.response?.data?.message || 'Error al cargar las categorías';
        console.error('Error fetching categories:', err);
    } finally {
        loading.value = false;
    }
};

const openCreateModal = () => {
    editingCategory.value = null;
    form.value = {
        name: '',
        description: '',
        active: true,
    };
    showModal.value = true;
};

const openEditModal = (category: Category) => {
    editingCategory.value = category;
    form.value = {
        name: category.name,
        description: category.description || '',
        active: category.active,
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingCategory.value = null;
    form.value = {
        name: '',
        description: '',
        active: true,
    };
};

const saveCategory = async () => {
    loading.value = true;
    error.value = null;
    try {
        if (editingCategory.value) {
            // Actualizar
            const response = await axios.put(`/categories-data/${editingCategory.value.id}`, form.value);
            if (response.data.success) {
                await fetchCategories();
                closeModal();
                alert('Categoría actualizada exitosamente');
            }
        } else {
            // Crear
            const response = await axios.post('/categories-data', form.value);
            if (response.data.success) {
                await fetchCategories();
                closeModal();
                alert('Categoría creada exitosamente');
            }
        }
    } catch (err: any) {
        error.value = err.response?.data?.message || 'Error al guardar la categoría';
        console.error('Error saving category:', err);
    } finally {
        loading.value = false;
    }
};

const deleteCategory = async (id: number) => {
    if (!confirm('¿Estás seguro de eliminar esta categoría?')) {
        return;
    }
    
    loading.value = true;
    error.value = null;
    try {
        const response = await axios.delete(`/categories-data/${id}`);
        if (response.data.success) {
            await fetchCategories();
            alert('Categoría eliminada exitosamente');
        }
    } catch (err: any) {
        error.value = err.response?.data?.message || 'Error al eliminar la categoría';
        console.error('Error deleting category:', err);
    } finally {
        loading.value = false;
    }
};

const exportToPdf = () => {
    // Crear un enlace temporal para descargar el PDF
    const url = '/categories-export-pdf';
    window.location.href = url;
};

const exportToExcel = () => {
    // Descargar el archivo Excel
    const url = '/categories-export-excel';
    window.location.href = url;
};

onMounted(() => {
    fetchCategories();
});
</script>

<template>
    <Head title="Categorías" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Categorías</h1>
                <div class="flex gap-3">
                    <button
                        @click="exportToExcel"
                        class="flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700"
                        title="Exportar a Excel"
                    >
                        <FileSpreadsheet :size="20" />
                        Exportar Excel
                    </button>
                    <button
                        @click="exportToPdf"
                        class="flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700"
                        title="Exportar a PDF"
                    >
                        <FileDown :size="20" />
                        Exportar PDF
                    </button>
                    <button
                        @click="openCreateModal"
                        class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                    >
                        <Plus :size="20" />
                        Nueva Categoría
                    </button>
                </div>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="rounded-lg bg-red-50 p-4 text-red-800">
                {{ error }}
            </div>

            <!-- Loading -->
            <div v-if="loading && categories.length === 0" class="text-center py-8">
                <p class="text-gray-500">Cargando categorías...</p>
            </div>

            <!-- Table -->
            <div v-else class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow">
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
                        <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                {{ category.id }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                {{ category.name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ category.description || '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span
                                    :class="[
                                        'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                                        category.active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800',
                                    ]"
                                >
                                    {{ category.active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                <div class="flex gap-2">
                                    <button
                                        @click="openEditModal(category)"
                                        class="text-blue-600 hover:text-blue-900"
                                        title="Editar"
                                    >
                                        <Pencil :size="18" />
                                    </button>
                                    <button
                                        @click="deleteCategory(category.id)"
                                        class="text-red-600 hover:text-red-900"
                                        title="Eliminar"
                                    >
                                        <Trash2 :size="18" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="categories.length === 0">
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No hay categorías registradas
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div
            v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            @click.self="closeModal"
        >
            <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <h2 class="mb-4 text-xl font-bold">
                    {{ editingCategory ? 'Editar Categoría' : 'Nueva Categoría' }}
                </h2>

                <form @submit.prevent="saveCategory" class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Nombre</label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        ></textarea>
                    </div>

                    <div class="flex items-center">
                        <input
                            v-model="form.active"
                            type="checkbox"
                            id="active"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        />
                        <label for="active" class="ml-2 text-sm text-gray-700">Activo</label>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button
                            type="button"
                            @click="closeModal"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{ loading ? 'Guardando...' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>


  <fwb-button @click="showModalv2">
    Open modal
  </fwb-button>

  <fwb-modal v-if="isShowModal" @close="closeModalv2" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
>
    <template #header>
      <div class="flex items-center text-lg">
        Terms of Service
      </div>
    </template>
    <template #body>
      <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
        With less than a month to go before the European Union enacts new consumer privacy laws for its citizens, companies around the world are updating their terms of service agreements to comply.
      </p>
      <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
        The European Union’s General Data Protection Regulation (G.D.P.R.) goes into effect on May 25 and is meant to ensure a common set of data rights in the European Union. It requires organizations to notify users as soon as possible of high-risk data breaches that could personally affect them.
      </p>
    </template>
    <template #footer>
      <div class="flex justify-between">
        <fwb-button @click="closeModalv2" color="alternative">
          Decline
        </fwb-button>
        <fwb-button @click="closeModalv2" color="green">
          I accept
        </fwb-button>
      </div>
    </template>
  </fwb-modal>


    </AppLayout>
</template>

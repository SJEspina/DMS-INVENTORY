<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  supply: Object,
})

const form = useForm({
  name: props.supply.name,
  category: props.supply.category,
  price: props.supply.price,
  stock: props.supply.stock,
})

const submit = () => {
  form.put(route('supplies.update', props.supply.id))
}
</script>

<template>
  <Head title="Edit Supply" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Edit Supply</h2>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
        <div class="rounded-xl bg-white p-6 shadow">
          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <label class="mb-1 block text-sm font-medium text-gray-700">Name</label>
              <input v-model="form.name" type="text" class="w-full rounded-lg border-gray-300" />
              <div v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</div>
            </div>

            <div>
              <label class="mb-1 block text-sm font-medium text-gray-700">Category</label>
              <input v-model="form.category" type="text" class="w-full rounded-lg border-gray-300" />
              <div v-if="form.errors.category" class="mt-1 text-sm text-red-500">{{ form.errors.category }}</div>
            </div>

            <div>
              <label class="mb-1 block text-sm font-medium text-gray-700">Price</label>
              <input v-model="form.price" type="number" step="0.01" class="w-full rounded-lg border-gray-300" />
              <div v-if="form.errors.price" class="mt-1 text-sm text-red-500">{{ form.errors.price }}</div>
            </div>

            <div>
              <label class="mb-1 block text-sm font-medium text-gray-700">Stock</label>
              <input v-model="form.stock" type="number" class="w-full rounded-lg border-gray-300" />
              <div v-if="form.errors.stock" class="mt-1 text-sm text-red-500">{{ form.errors.stock }}</div>
            </div>

            <div class="flex gap-2">
              <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                Update
              </button>
              <Link
                :href="route('supplies.index')"
                class="rounded-lg bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400"
              >
                Cancel
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
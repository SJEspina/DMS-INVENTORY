<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  supplies: {
    type: Object,
    default: () => ({
      data: [],
      links: [],
    }),
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
})

const search = ref(props.filters.search || '')

const searchSupplies = () => {
  router.get(
    route('supplies.index'),
    { search: search.value },
    {
      preserveState: true,
      replace: true,
    }
  )
}

const deleteSupply = (id) => {
  if (confirm('Are you sure you want to delete this supply?')) {
    router.delete(route('supplies.destroy', id))
  }
}

const statusClass = (status) => {
  if (status === 'In Stock') return 'bg-green-100 text-green-700'
  if (status === 'Low Stock') return 'bg-yellow-100 text-yellow-700'
  if (status === 'Out of Stock') return 'bg-red-100 text-red-700'
  return 'bg-gray-100 text-gray-700'
}
</script>

<template>
  <Head title="Supplies" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Supplies</h2>

        <div class="flex items-center gap-2">
          <input
            v-model="search"
            @input="searchSupplies"
            type="text"
            placeholder="Search supply..."
            class="rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
          />

          <Link
            :href="route('supplies.create')"
            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
          >
            Add Supply
          </Link>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl bg-white shadow">
          <div class="p-6">
            <table class="min-w-full border-collapse">
              <thead>
                <tr class="border-b text-left text-sm text-gray-500">
                  <th class="px-4 py-3">Date Created</th>
                  <th class="px-4 py-3">Name</th>
                  <th class="px-4 py-3">Category</th>
                  <th class="px-4 py-3">Price</th>
                  <th class="px-4 py-3">Stock</th>
                  <th class="px-4 py-3">Status</th>
                  <th class="px-4 py-3">Actions</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="supply in props.supplies.data"
                  :key="supply.id"
                  class="border-b text-sm"
                >
                  <td class="px-4 py-3">
                    {{ new Date(supply.created_at).toLocaleDateString() }}
                  </td>
                  <td class="px-4 py-3">{{ supply.name }}</td>
                  <td class="px-4 py-3">{{ supply.category }}</td>
                  <td class="px-4 py-3">₱{{ supply.price }}</td>
                  <td class="px-4 py-3">{{ supply.stock }}</td>
                  <td class="px-4 py-3">
                    <span
                      class="rounded-full px-3 py-1 text-xs font-medium"
                      :class="statusClass(supply.status)"
                    >
                      {{ supply.status }}
                    </span>
                  </td>
                  <td class="space-x-2 px-4 py-3">
                    <Link
                      :href="route('supplies.edit', supply.id)"
                      class="rounded bg-blue-500 px-3 py-1 text-white hover:bg-blue-600"
                    >
                      Edit
                    </Link>
                    <button
                      @click="deleteSupply(supply.id)"
                      class="rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600"
                    >
                      Delete
                    </button>
                  </td>
                </tr>

                <tr v-if="props.supplies.data.length === 0">
                  <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                    No supplies found.
                  </td>
                </tr>
              </tbody>
            </table>

            <div
              v-if="props.supplies.links && props.supplies.links.length > 3"
              class="mt-6 flex flex-wrap gap-2"
            >
              <Link
                v-for="(link, index) in props.supplies.links"
                :key="index"
                :href="link.url || '#'"
                v-html="link.label"
                class="rounded border px-3 py-1 text-sm"
                :class="[
                  link.active
                    ? 'border-blue-600 bg-blue-600 text-white'
                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-100',
                  !link.url ? 'pointer-events-none opacity-50' : ''
                ]"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
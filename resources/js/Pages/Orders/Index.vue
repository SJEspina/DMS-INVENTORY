<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  orders: {
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

const searchOrders = () => {
  router.get(
    route('orders.index'),
    { search: search.value },
    {
      preserveState: true,
      replace: true,
    }
  )
}

const deleteOrder = (id) => {
  if (confirm('Are you sure you want to delete this order?')) {
    router.delete(route('orders.destroy', id))
  }
}

const statusClass = (status) => {
  if (status === 'Pending') return 'bg-yellow-100 text-yellow-700'
  if (status === 'Ongoing') return 'bg-blue-100 text-blue-700'
  if (status === 'Done') return 'bg-green-100 text-green-700'
  return 'bg-gray-100 text-gray-700'
}
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Orders</h2>

        <div class="flex items-center gap-2">
          <input
            v-model="search"
            @input="searchOrders"
            type="text"
            placeholder="Search customer..."
            class="rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
          />

          <Link
            :href="route('orders.create')"
            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
          >
            Add Order
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
                  <th class="px-4 py-3">Deadline</th>
                  <th class="px-4 py-3">Customer</th>
                  <th class="px-4 py-3">Price</th>
                  <th class="px-4 py-3">Product</th>
                  <th class="px-4 py-3">Qty</th>
                  <th class="px-4 py-3">Status</th>
                  <th class="px-4 py-3">Actions</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="order in props.orders.data"
                  :key="order.id"
                  class="border-b text-sm"
                >
                  <td class="px-4 py-3">
                    {{ new Date(order.created_at).toLocaleDateString() }}
                  </td>
                  <td class="px-4 py-3">
                    {{ order.deadline ?? 'No deadline' }}
                  </td>
                  <td class="px-4 py-3">{{ order.customer }}</td>
                  <td class="px-4 py-3">₱{{ order.price }}</td>
                  <td class="px-4 py-3">{{ order.product }}</td>
                  <td class="px-4 py-3">{{ order.qty }}</td>
                  <td class="px-4 py-3">
                    <span
                      class="rounded-full px-3 py-1 text-xs font-medium"
                      :class="statusClass(order.status)"
                    >
                      {{ order.status }}
                    </span>
                  </td>
                  <td class="space-x-2 px-4 py-3">
                    <Link
                      :href="route('orders.edit', order.id)"
                      class="rounded bg-blue-500 px-3 py-1 text-white hover:bg-blue-600"
                    >
                      Edit
                    </Link>
                    <button
                      @click="deleteOrder(order.id)"
                      class="rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600"
                    >
                      Delete
                    </button>
                  </td>
                </tr>

                <tr v-if="props.orders.data.length === 0">
                  <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                    No orders found.
                  </td>
                </tr>
              </tbody>
            </table>

            <div
              v-if="props.orders.links && props.orders.links.length > 3"
              class="mt-6 flex flex-wrap gap-2"
            >
              <Link
                v-for="(link, index) in props.orders.links"
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
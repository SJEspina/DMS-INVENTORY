<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

const props = defineProps({
  orders: {
    type: Array,
    default: () => [],
  },
})

const deleteOrder = (id) => {
  if (confirm('Are you sure you want to delete this order?')) {
    router.delete(`/orders/${id}`)
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
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Orders</h2>
        <Link
          href="/orders/create"
          class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
        >
          Add Order
        </Link>
      </div>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl bg-white shadow">
          <div class="p-6">
            <table class="min-w-full border-collapse">
              <thead>
                <tr class="border-b text-left text-sm text-gray-500">
                  <th class="px-4 py-3">Date</th>
                  <th class="px-4 py-3">Customer</th>
                  <th class="px-4 py-3">Product</th>
                  <th class="px-4 py-3">Qty</th>
                  <th class="px-4 py-3">Status</th>
                  <th class="px-4 py-3">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="order in props.orders"
                  :key="order.id"
                  class="border-b text-sm"
                >
                  <td class="px-4 py-3">{{ order.date }}</td>
                  <td class="px-4 py-3">{{ order.customer }}</td>
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
                  <td class="px-4 py-3 space-x-2">
                    <Link
                      :href="`/orders/${order.id}/edit`"
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

                <tr v-if="props.orders.length === 0">
                  <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                    No orders found.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  order: Object,
})

const form = useForm({
  customer: props.order.customer,
  price: props.order.price ?? '',
  product: props.order.product,
  qty: props.order.qty,
  status: props.order.status,
  deadline: props.order.deadline ?? '',
})

const submit = () => {
  form.put(route('orders.update', props.order.id))
}
</script>

<template>
  <Head title="Edit Order" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Edit Order</h2>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
        <div class="rounded-xl bg-white p-6 shadow">
          <form @submit.prevent="submit" class="space-y-4">

            <div>
              <label class="mb-1 block text-sm font-medium">Customer</label>
              <input v-model="form.customer" type="text" class="w-full rounded-lg border-gray-300" />
              <div v-if="form.errors.customer" class="mt-1 text-sm text-red-500">
                {{ form.errors.customer }}
              </div>
            </div>

            <div>
              <label class="mb-1 block text-sm font-medium">Price</label>
              <input
                v-model="form.price"
                type="number"
                step="0.01"
                min="0"
                class="w-full rounded-lg border-gray-300"
              />
              <div v-if="form.errors.price" class="mt-1 text-sm text-red-500">
                {{ form.errors.price }}
              </div>
            </div>

            <div>
              <label class="mb-1 block text-sm font-medium">Product</label>
              <input v-model="form.product" type="text" class="w-full rounded-lg border-gray-300" />
              <div v-if="form.errors.product" class="mt-1 text-sm text-red-500">
                {{ form.errors.product }}
              </div>
            </div>

            <div>
              <label class="mb-1 block text-sm font-medium">Deadline</label>
              <input v-model="form.deadline" type="date" class="w-full rounded-lg border-gray-300" />
              <div v-if="form.errors.deadline" class="mt-1 text-sm text-red-500">
                {{ form.errors.deadline }}
              </div>
            </div>

            <div>
              <label class="mb-1 block text-sm font-medium">Qty</label>
              <input v-model="form.qty" type="number" min="1" class="w-full rounded-lg border-gray-300" />
              <div v-if="form.errors.qty" class="mt-1 text-sm text-red-500">
                {{ form.errors.qty }}
              </div>
            </div>

            <div>
              <label class="mb-1 block text-sm font-medium">Status</label>
              <select v-model="form.status" class="w-full rounded-lg border-gray-300">
                <option value="Pending">Pending</option>
                <option value="Ongoing">Ongoing</option>
                <option value="Done">Done</option>
              </select>
              <div v-if="form.errors.status" class="mt-1 text-sm text-red-500">
                {{ form.errors.status }}
              </div>
            </div>

            <div class="flex gap-3 pt-2">
              <button
                type="submit"
                class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                :disabled="form.processing"
              >
                Update
              </button>

              <Link
                :href="route('orders.index')"
                class="rounded-lg bg-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-300"
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
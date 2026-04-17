<script setup>
import { ref } from "vue";

const stats = [
    { title: "Pending Orders", value: 24, change: "+5%" },
    { title: "Ongoing Orders", value: 12, change: "+2%" },
    { title: "Completed Orders", value: 40, change: "+10%" },
    { title: "Low Stock Supplies", value: 5, change: "-3%" },
];

const orders = ref([
    {
        date: "Mar 28",
        customer: "John",
        product: "T-shirt",
        qty: 20,
        status: "Pending",
    },
    {
        date: "Mar 27",
        customer: "Anna",
        product: "Jersey",
        qty: 15,
        status: "Ongoing",
    },
    {
        date: "Mar 26",
        customer: "Mark",
        product: "Tarpaulin",
        qty: 5,
        status: "Done",
    },
]);

const supplies = ref([
    {
        name: "Fabric",
        category: "Material",
        stock: 50,
        unit: "meters",
        status: "Normal",
    },
    {
        name: "Ink",
        category: "Consumable",
        stock: 10,
        unit: "ml",
        status: "Low",
    },
    {
        name: "Film",
        category: "Material",
        stock: 30,
        unit: "pcs",
        status: "Normal",
    },
]);
</script>

<template>
    <div class="flex h-screen bg-gray-100">
        <aside class="w-64 bg-white shadow-lg p-5">
            <h1 class="text-xl font-bold text-blue-600 mb-6">DM's</h1>
            <nav class="flex flex-col gap-3 text-gray-600">
                <a href="/dashboard">Dashboard</a>
                <a href="/orders">Orders</a>
                <a href="/supplies">Supplies</a>
                <a href="/inventory">Inventory Logs</a>
                <a href="/reports">Reports</a>
                <a href="/user">Users</a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header
                class="flex justify-between items-center bg-white p-4 shadow"
            >
                <input
                    placeholder="Search..."
                    class="bg-gray-100 px-4 py-2 rounded-lg w-1/3"
                />
                <span>🔔</span>
            </header>

            <main class="p-6 grid grid-cols-4 gap-6">
                <div
                    v-for="stat in stats"
                    :key="stat.title"
                    class="bg-white p-4 rounded-2xl shadow"
                >
                    <p class="text-sm text-gray-500">{{ stat.title }}</p>
                    <h2 class="text-2xl font-bold">{{ stat.value }}</h2>
                    <span class="text-xs text-blue-500">{{ stat.change }}</span>

                    <div class="h-2 bg-gray-200 rounded mt-2">
                        <div class="h-2 bg-blue-500 rounded w-2/3"></div>
                    </div>
                </div>

                <div class="col-span-3 bg-white p-5 rounded-2xl shadow">
                    <h2 class="font-semibold mb-4">Orders Overview</h2>
                    <div
                        class="h-60 flex items-center justify-center text-gray-400"
                    >
                        Chart here (use Chart.js or ApexCharts)
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow">
                    <h2 class="font-semibold mb-3">Alerts</h2>
                    <p class="text-red-500 text-sm">Ink stock running low</p>
                    <p class="text-sm">Fabric needs reorder</p>
                    <p class="text-sm">High pending orders</p>
                </div>

                <div class="col-span-2 bg-white p-5 rounded-2xl shadow">
                    <h2 class="font-semibold mb-3">Recent Orders</h2>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-gray-500">
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in orders" :key="order.date">
                                <td>{{ order.date }}</td>
                                <td>{{ order.customer }}</td>
                                <td>{{ order.product }}</td>
                                <td>{{ order.qty }}</td>
                                <td>
                                    <span
                                        :class="{
                                            'text-yellow-500':
                                                order.status === 'Pending',
                                            'text-blue-500':
                                                order.status === 'Ongoing',
                                            'text-green-500':
                                                order.status === 'Done',
                                        }"
                                    >
                                        {{ order.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-span-2 bg-white p-5 rounded-2xl shadow">
                    <h2 class="font-semibold mb-3">Supplies</h2>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-gray-500">
                                <th>Name</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Unit</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="supply in supplies" :key="supply.name">
                                <td>{{ supply.name }}</td>
                                <td>{{ supply.category }}</td>
                                <td>{{ supply.stock }}</td>
                                <td>{{ supply.unit }}</td>
                                <td>
                                    <span
                                        :class="{
                                            'text-green-500':
                                                supply.status === 'Normal',
                                            'text-red-500':
                                                supply.status === 'Low',
                                        }"
                                    >
                                        {{ supply.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</template>

<template>
     <div class="w-full flex justify-start mb-12">
         <button @click="closeModal(null)" class="font-semibold text-2xl text-indigo-600">x</button>
    </div>
    <div class="mb-6">
        <span class="block text-sm text-indigo-500 font-medium mb-1">Title</span>
        <h2 class="text-gray-700 font-medium text-lg">{{ task.title }}</h2>    
    </div>
    <div class="mb-6">
        <span class="block text-sm text-indigo-500 font-medium mb-1">Content</span>
        <p class="text-gray-700 font-medium text-lg">{{ task.content }}</p>    
    </div>
    <div class="mb-6">
        <span class="block text-sm text-indigo-500 font-medium mb-1">Status</span>
        <p :class="[
                task.status === 'to_do' && 'text-blue-600 font-semibold',
                task.status === 'in_progress' && 'text-yellow-600 font-semibold',
                task.status === 'completed' && 'text-green-600 font-semibold'
            ]">
            {{ formatStatus(task.status) }}
        </p>    
    </div>  
    <div class="mb-6">
        <span class="block text-sm text-indigo-500 font-medium mb-1">Assigned user</span>
        <p class="text-gray-700 font-medium text-lg">{{ task.user.name }}
        </p>    
    </div>   
</template>

<script>

import { formatStatus } from "../helpers";

export default {
    name: 'SidebarTaskInfo',
    props: {
        task: Object,
        closeModal: Function
    },
    setup() {
        return {
            formatStatus
        }
    }
}
</script>
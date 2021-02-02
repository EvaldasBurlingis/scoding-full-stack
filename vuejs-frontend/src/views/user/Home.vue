<template>
    <div class="relative">

        <!-- Show task details -->
        <div v-if="isShowTaskModalOpen && selectedTask" class="fixed h-full bg-white w-full md:w-2/3 lg:w-1/3 top-0 right-0 z-20 shadow-xl px-8 py-20">
            <SidebarTaskInfo :task="selectedTask" :closeModal="toggleShowTask" />
        </div>

        <!-- change task status modal -->
        <div v-if="isEditTaskStatusModalOpen && selectedTask" class="fixed h-full bg-white w-full md:w-2/3 lg:w-1/3 top-0 right-0 z-20 shadow-xl px-8 py-20">
            <div class="mb-6">
                <button @click="toggleChangeStatus(null)" class="font-semibold text-2xl text-indigo-600">x</button>
            </div>

            <form @submit.prevent="editTaskStatus">
                <div class="mb-4">
                    <label 
                        for="editTaskStatus" 
                        class="text-sm font-semibold text-gray-900 mb-1 block"
                    >Change status</label>
                    <select
                        v-model="statusChange" 
                        id="editTaskStatus" 
                        class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring">
                        <option value="to_do">To-do</option>
                        <option value="in_progress">In progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div class="mb-4">
                    <button class="w-full bg-green-500 hover:bg-green-600 py-2 rounded text-white">Save</button>
                </div>
            </form>

        </div>

        <div class="container mx-auto py-4 px-4 relative">

            <div class="w-full mb-8">
                <span class="text-purple-600 tracking-tight text-lg block mb-4">My Tasks</span>
                <table class="table">
                    <thead>
                        <tr >
                            <th class="th">Created</th>
                            <th class="th">Title</th>
                            <th class="th">Assigned user</th>
                            <th class="th w-40"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(task, index) in tasks" :key="index" class="hover:bg-gray-100">
                            <td class="td">{{ task.created_at }}</td>
                            <td class="td cursor-pointer" @click="toggleShowTask(task)">{{ task.title }}</td>
                            <td class="td">
                                <span class="block text-black">{{ user.name }}</span>
                                <span class="text-sm text-gray-700">{{ user.email }}</span>
                            </td>
                            <td class="td cursor-pointer" @click="toggleChangeStatus(task)">
                                <span :class="[
                                    task.status === 'to_do' && 'pill pill__blue',
                                    task.status === 'in_progress' && 'pill pill__yellow',
                                    task.status === 'completed' && 'pill pill__green'
                                ]">
                                    {{ formatStatus(task.status) }}
                                </span> 
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import SidebarTaskInfo from '@/components/SidebarTaskInfo.vue';
import { formatStatus } from '../../helpers';

export default {
    name: 'Home',
    components: {
        SidebarTaskInfo
    },
    setup() {
        const store = useStore();
        const user = computed(() => store.getters['auth/user']);
        const tasks = computed(() => store.getters['user/tasks']);
        const selectedTask = ref(null);
        const isShowTaskModalOpen = ref(false);
        const isEditTaskStatusModalOpen = ref(false);
        const statusChange = ref("to_do");

        console.log(tasks.value)

        function toggleShowTask(task) {
            isShowTaskModalOpen.value = !isShowTaskModalOpen.value;
            selectedTask.value = task;
        }

        function toggleChangeStatus(task) {
            selectedTask.value = task;
            isEditTaskStatusModalOpen.value = !isEditTaskStatusModalOpen.value;
        }

        async function editTaskStatus() {
            const payload = {
                user_id: user.value.id,
                task_id: selectedTask.value.id,
                status: statusChange.value
            }

            store.dispatch('user/updateTaskStatus', payload);
        }

        return {
            user,
            formatStatus,
            selectedTask,
            isShowTaskModalOpen,
            isEditTaskStatusModalOpen,
            toggleShowTask,
            toggleChangeStatus,
            editTaskStatus,
            statusChange,
            tasks
        }
    }

}
</script>
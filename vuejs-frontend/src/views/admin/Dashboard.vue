<template>
    <div class="relative">
        <!-- Show task details -->
        <div v-if="isShowTaskModalOpen && selectedTask" class="fixed h-full bg-white w-full md:w-2/3 lg:w-1/3 top-0 right-0 z-20 shadow-xl px-8 py-20">
            <SidebarTaskInfo :task="selectedTask" :closeModal="toggleShowTask" />
        </div>

        <!-- Edit task -->
        <div v-if="isEditTaskModalOpen && selectedTask" class="fixed h-full bg-white w-full md:w-2/3 lg:w-1/3 top-0 right-0 z-20 shadow-xl px-8 py-2 md:py-12">
            <div class="w-full flex justify-start mb-2">
                <button @click="toggleEditTask(null)" class="font-semibold text-2xl text-indigo-600">x</button>
            </div>

            <form @submit.prevent="editTask">
                    <div class="mb-4">
                        <label 
                            for="editTaskTitle" 
                            class="text-sm font-semibold text-gray-900 mb-1 block"
                        >Title</label>
                        <input
                            v-model="editTaskForm.title" 
                            id="editTaskTitle" 
                            type="text"
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring" 
                            required />
                    </div>
                    <div class="mb-4">
                        <label 
                            for="editTaskContent" 
                            class="text-sm font-semibold text-gray-900 mb-1 block"
                        >Content</label>
                        <textarea
                            v-model="editTaskForm.content" 
                            id="editTaskContent" 
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring" 
                            rows=10
                            required></textarea>
                    </div>
                    <div class="mb-4">
                        <label 
                            for="editTaskStatus" 
                            class="text-sm font-semibold text-gray-900 mb-1 block"
                        >Status</label>
                        <select
                            v-model="editTaskForm.status" 
                            id="editTaskStatus" 
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring">
                            <option value="to_do">To-do</option>
                            <option value="in_progress">In progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label 
                            for="editTaskAssignedUser" 
                            class="text-sm font-semibold text-gray-900 mb-1 block"
                        >Assigned user</label>
                        <select 
                            id="editTaskAssignedUser" 
                            class="bg-gray-100 cursor-not-allowed text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring" disabled>
                            <option v-for="(user, index) in users" :key="index" :value="user.email" :selected="selectedTask.user.email">{{ selectedTask.user.email }}</option>
                        </select>
                    </div>

                     <div class="mb-4">
                        <button class="w-full bg-green-500 hover:bg-green-600 py-2 rounded text-white">Edit</button>
                    </div>

                    <div v-if="successMessage">
                        <p class="text-sm text-green-600">{{ successMessage }}</p>
                    </div>
            </form>
        </div>

        <!-- Add new task -->
        <div v-if="isCreateTaskModalOpen" class="fixed h-full bg-white w-full md:w-2/3 lg:w-1/3 top-0 right-0 z-20 shadow-xl px-8 py-2 md:py-12">
            <div class="w-full flex justify-start mb-4">
                <button @click="toggleCreateTask" class="font-semibold text-2xl text-indigo-600">x</button>
            </div>

            <form @submit.prevent="createTask">
                    <div class="mb-4">
                        <label 
                            for="createTaskTitle" 
                            class="text-sm font-semibold text-gray-900 mb-1 block"
                        >Title</label>
                        <input
                            v-model="createTaskForm.title" 
                            id="createTaskTitle" 
                            type="text"
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring" 
                            required />
                    </div>
                    <div class="mb-4">
                        <label 
                            for="createTaskContent" 
                            class="text-sm font-semibold text-gray-900 mb-1 block"
                        >Content</label>
                        <textarea
                            v-model="createTaskForm.content" 
                            id="createTaskContent" 
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring" 
                            rows=10
                            required></textarea>
                    </div>
                    <div class="mb-4">
                        <label 
                            for="createTaskStatus" 
                            class="text-sm font-semibold text-gray-900 mb-1 block"
                        >Status</label>
                        <select
                            v-model="createTaskForm.status" 
                            id="createTaskStatus" 
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring">
                            <option value="to_do">To-do</option>
                            <option value="in_progress">In progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label 
                            for="createTaskAssignedUser" 
                            class="text-sm font-semibold text-gray-900 mb-1 block"
                        >Assigned user</label>
                        <select 
                            id="createTaskAssignedUser"
                            v-model="createTaskForm.user_id" 
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring">
                            <option v-for="(user, index) in users" :key="index" :value="user.id">{{ user.email }}</option>
                        </select>
                    </div>

                     <div class="mb-4">
                        <button class="w-full bg-green-500 hover:bg-green-600 py-2 rounded text-white">Create</button>
                    </div>

                    <div v-if="successMessage">
                        <p class="text-sm text-green-600">{{ successMessage }}</p>
                    </div>
            </form>
        </div>

        <div class="container mx-auto py-4 px-4 relative">

            <div class="flex flex-col justify-between items-start mb-4">
                <div class="mb-6 w-full flex flex-col md:flex-row">
                    <Stats 
                        v-if="tasks" 
                        :count="[{count: users.length, title: 'Users'}, {count: tasks.length, title: 'Tasks'}]" 
                        variant="purple"
                    />
                    <Stats 
                        v-if="tasks" 
                        :count="[{count: countTasksByStatus('to_do', tasks), title: 'To-do'}]" 
                        variant="blue"
                    />
                    <Stats 
                        v-if="tasks" 
                        :count="[{count: countTasksByStatus('in_progress', tasks), title: 'In progress'}]" 
                        variant="yellow"
                    />
                    <Stats 
                        v-if="tasks" 
                        :count="[{count: countTasksByStatus('completed', tasks), title: 'Completed'}]" 
                        variant="green"
                    />
                </div>

                <div>
                    <button class="bg-green-600 text-white rounded px-4 py-2" @click="toggleCreateTask">New Task</button>
                </div>
            </div>
            
            <div class="w-full mb-8" v-if="tasks">
                <span class="text-purple-600 tracking-tight text-lg block mb-4">Task list</span>
                <table class="table">
                    <thead>
                        <tr >
                            <th class="th">Id</th>
                            <th class="th">Created on</th>
                            <th class="th">Title</th>
                            <th class="th">Assigned user</th>
                            <th class="th w-40"></th>
                            <th class="th"></th>
                            <th class="th"></th>
                            <th class="th"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(task, index) in tasks" :key="index" class="hover:bg-gray-100">
                            <td class="td">{{ task.id }}</td>
                            <td class="td">{{ task.created_at }}</td>
                            <td class="td">{{ task.title }}</td>
                            <td class="td">
                                <span class="block text-black">{{ task.user.name }}</span>
                                <span class="text-sm text-gray-700">{{ task.user.email }}</span>
                            </td>
                            <td class="td">
                                <span :class="[
                                    task.status === 'to_do' && 'pill pill__blue',
                                    task.status === 'in_progress' && 'pill pill__yellow',
                                    task.status === 'completed' && 'pill pill__green'
                                ]">
                                    {{ formatStatus(task.status) }}
                                </span> 
                            </td>
                            <td class="td relative"><button class="text-indigo-600 hover:text-indigo-900" @click="toggleShowTask(task)">Show task</button></td>
                            <td class="td"><button class="text-green-600 hover:text-green-900" @click="toggleEditTask(task)">Edit</button></td>
                            <td class="td"><button class="text-red-600 hover:text-red-900" @click="deleteTask(task.id)">Delete</button></td>
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
import Stats from '../../components/Stats';
import SidebarTaskInfo from '@/components/SidebarTaskInfo.vue';
import { formatStatus, countTasksByStatus } from "../../helpers";

export default {
    components: {
        Stats,
        SidebarTaskInfo
    },
    setup() {
        const store = useStore();
        const users = computed(() => store.getters['admin/users']);
        const tasks = computed(() => store.getters['admin/tasks']);
        const successMessage = computed(() => store.getters['app/successMessage']);
        const isShowTaskModalOpen = ref(false);
        const isEditTaskModalOpen = ref(false);
        const isCreateTaskModalOpen = ref(false);
        const selectedTask = ref(null);
        const editTaskForm = ref({
            title: '',
            content: '',
            status: ''
        });
        const createTaskForm = ref({
            title: '',
            content: '',
            status: '',
            user_id: ''
        });

        async function deleteTask(id) {
            store.dispatch('admin/deleteTask', id);
        }

        async function editTask() {
            const payload = {
                id: selectedTask.value.id,
                title: editTaskForm.value.title,
                content: editTaskForm.value.content,
                status: editTaskForm.value.status,
                user_id: selectedTask.value.user.id
            }

            await store.dispatch('admin/updateTask', payload);
        }

        async function createTask() {
            const payload = {
                title: createTaskForm.value.title,
                content: createTaskForm.value.content,
                status: createTaskForm.value.status,
                user_id: createTaskForm.value.user_id
            }

            await store.dispatch('admin/createTask', payload);

            createTaskForm.value = {
                title: '',
                content: '',
                status: '',
                user_id: ''
            }
        }

        function toggleShowTask(task) {
            isEditTaskModalOpen.value = false;
            isCreateTaskModalOpen.value = false;
            isShowTaskModalOpen.value = !isShowTaskModalOpen.value;
            selectedTask.value = task;
        }

        function toggleCreateTask() {
            isEditTaskModalOpen.value = false;
            isShowTaskModalOpen.value = false;
            isCreateTaskModalOpen.value = !isCreateTaskModalOpen.value;
            store.commit('app/SET_SUCCESS_MESSAGE', null);
        }

        function toggleEditTask(task) {
            isEditTaskModalOpen.value = !isEditTaskModalOpen.value;
            isShowTaskModalOpen.value = false;
            isCreateTaskModalOpen.value = false;
            selectedTask.value = task;
            store.commit('app/SET_SUCCESS_MESSAGE', null);

            if (task !== null) {
                editTaskForm.value.title = selectedTask.value.title;
                editTaskForm.value.content = selectedTask.value.content;
                editTaskForm.value.status = selectedTask.value.status;
            }
        }

        return {
            tasks,
            users,
            countTasksByStatus,
            formatStatus, 
            deleteTask,
            isShowTaskModalOpen,
            isEditTaskModalOpen,
            isCreateTaskModalOpen,
            toggleShowTask,
            toggleEditTask,
            toggleCreateTask,
            selectedTask,
            editTask,
            editTaskForm,
            createTaskForm,
            createTask,
            successMessage
        }
    }
}
</script>
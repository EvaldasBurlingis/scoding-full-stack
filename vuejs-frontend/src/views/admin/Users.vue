<template>
    <div class="relative">
        <!-- show user details -->
        <div v-if="isSeeTasksModalOpen && selectedUser" class="fixed h-full bg-white w-full md:w-2/3 lg:w-1/3 top-0 right-0 z-20 shadow-xl px-8 py-20">
            <div class="w-full flex justify-start mb-12">
                <button @click="toggleUserTasks(null)" class="font-semibold text-2xl text-indigo-600">x</button>
            </div>

            <div class="mb-6">
                <span class="block text-sm text-indigo-500 font-medium mb-1">Name</span>
                <h2 class="text-gray-700 font-medium text-lg">{{ selectedUser.name }}</h2>    
            </div>

            <div class="mb-6">
                <span class="block text-sm text-indigo-500 font-medium mb-1">Email</span>
                <h2 class="text-gray-700 font-medium text-lg">{{ selectedUser.email }}</h2>    
            </div>

            <div class="mb-6">
                <span class="block text-sm text-indigo-500 font-medium mb-1">Assigned tasks</span>
                <div v-if="selectedUser.tasks.tasks.length > 0">
                    <div v-for="task in selectedUser.tasks.tasks" :key="task.id" class="py-1 border-b border-gray-100">
                        <details>
                            <summary class="text-sm">{{ task.title }}</summary>
                            <p class="py-2 text-sm text-gray-700 mb-1">{{ task.content }}</p>
                            <button class="text-white bg-red-500 px-2 py-1 text-sm rounded hover:bg-red-600" @click="deleteTask(task.id)">Delete</button>
                        </details>
                    </div>
                </div>
                <div v-else>
                    No tasks assigned
                </div>  
            </div>
        </div>

        <!-- show edit user form -->
        <div v-if="isEditUserModalOpen && selectedUser" class="fixed h-full bg-white w-full md:w-2/3 lg:w-1/3 top-0 right-0 z-20 shadow-xl px-8 py-20">
            <div class="w-full flex justify-start mb-12">
                <button @click="toggleEditUser(null)" class="font-semibold text-2xl text-indigo-600">x</button>
            </div>

            <form @submit.prevent="editUser">
                    <div class="mb-4">
                        <Label id="editUserName" title="Name"/>
                        <input
                            v-model="editUserForm.name" 
                            id="editUserName" 
                            type="text"
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring" 
                            required />
                    </div>
                    <div class="mb-4">
                        <Label id="editUserEmail" title="Email"/>
                        <input
                            v-model="editUserForm.email" 
                            id="editUserEmail" 
                            type="email"
                            :placeholder="selectedUser.email"
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring placeholder-black" 
                            />
                    </div>

                    <div class="mb-4">
                        <Label id="editUserPassword" title="New Password"/>
                        <input
                            v-model="editUserForm.password" 
                            id="editUserPassword" 
                            type="password"
                            placeholder="min 8 characters"
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring" 
                            required />
                    </div>

                    <div class="mb-4">
                        <Label id="editUserPasswordConfirmatio" title="New Password Confirmation"/>
                        <input
                            v-model="editUserForm.password_confirmation" 
                            id="editUserPasswordConfirmation" 
                            type="password"
                            placeholder="min 8 characters"
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring" 
                            required />
                    </div>

                     <div class="mb-4">
                        <button class="w-full bg-green-500 hover:bg-green-600 py-2 rounded text-white">Edit</button>
                    </div>

                    <div v-if="successMessage">
                        <p class="text-sm text-green-600">{{ successMessage }}</p>
                    </div>
            </form>

        </div>

        <!-- show create new user form -->
        <div v-if="isCreateUserModalOpen" class="fixed h-full bg-white w-full md:w-2/3 lg:w-1/3 top-0 right-0 z-20 shadow-xl px-8 py-20">
            <div class="w-full flex justify-start mb-12">
                <button @click="toggleCreateUser" class="font-semibold text-2xl text-indigo-600">x</button>
            </div>

            <form @submit.prevent="createUser">
                <div class="mb-4">
                    <Label id="createUserName" title="Name"/>
                    <input
                        v-model="editUserForm.name" 
                        id="createUserName" 
                        type="text"
                        class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring" 
                        required />
                </div>

                <div class="mb-4">
                    <Label id="createUserEmail" title="Email"/>
                    <input
                        v-model="editUserForm.email" 
                        id="creteUserEmail" 
                        type="email"
                        class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring placeholder-black" 
                        />
                </div>

                <div class="mb-4">
                    <Label id="createUserPassword" title="Password"/>
                    <input
                        v-model="editUserForm.password" 
                        id="createUserPassword" 
                        type="password"
                        placeholder="min 8 characters"
                        class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring" 
                        required />
                </div>

                <div class="mb-4">
                    <Label id="createUserPasswordConfirmatio" title="New Password Confirmation"/>
                    <input
                        v-model="editUserForm.password_confirmation" 
                        id="creteUserPasswordConfirmation" 
                        type="password"
                        placeholder="min 8 characters"
                        class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring" 
                        required />
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
            <button @click="toggleCreateUser" class="block px-4 mb-4 bg-green-600 text-white py-2 text-sm rounded">Add new user</button>        
            <div>
                <div class="w-full mb-8" v-if="users">
                    <span class="text-purple-600 text-lg font-bold block mb-4">User list</span>
                    <table class="table">
                        <thead>
                            <tr >
                                <th class="th">Id</th>
                                <th class="th">Created</th>
                                <th class="th">Name</th>
                                <th class="th">Email</th>
                                <th class="th">Assigned tasks</th>
                                <th class="th"></th>
                                <th class="th"></th>
                                <th class="th"></th>
                            </tr>
                        </thead>
                        <tbody v-if="users">
                            <tr v-for="(user, index) in users" :key="index" class="hover:bg-gray-100">
                                <td class="td">{{ user.id }}</td>
                                <td class="td">{{ user.created_at }}</td>
                                <td class="td">{{ user.name }}</td>
                                <td class="td">{{ user.email }}</td>
                                <td class="td">{{ user.tasks.tasks.length }}</td>
                                <td class="td">
                                    <button class="text-purple-600 hover:text-purple-900" @click="toggleUserTasks(user)">See tasks</button>
                                </td>
                                <td class="td">
                                    <button class="text-green-600 hover:text-green-900" @click="toggleEditUser(user)">Edit</button>
                                </td>
                                <td class="td">
                                    <button class="text-red-600 hover:text-red-900" @click="deleteUser(user.id)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>   
        </div>
    </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import { formatStatus, countTasksByStatus } from "../../helpers";
import Label from '../../components/Form/Label.vue';

export default {
    components: {
        Label,
    },
    setup() {
        const store = useStore();
        const users = computed(() => store.getters['admin/users']);
        const selectedUser = ref(null);
        const successMessage = computed(() => store.getters['app/successMessage']);
        const isSeeTasksModalOpen = ref(false);
        const isCreateUserModalOpen = ref(false);
        const isEditUserModalOpen = ref(false);
        const editUserForm = ref({
            name: '',
            email: '',
            password: '',
            password_confirmation: ''
        });

        async function deleteUser(id) {
            await store.dispatch('admin/deleteUser', id);
        }

        async function deleteTask(id) {
            await store.dispatch('admin/deleteTask', id);
        }

        async function createUser() {
            const payload = {
                name: editUserForm.value.name,
                email: editUserForm.value.email,
                password: editUserForm.value.password,
                password_confirmation: editUserForm.value.password_confirmation
            }

            await store.dispatch('admin/createUser', payload);

            if (successMessage.value) {
                editUserForm.value = {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                };
            }
        }

        async function editUser() {
            const payload = {
                id: selectedUser.value.id,
                name: editUserForm.value.name,
                password: editUserForm.value.password,
                password_confirmation: editUserForm.value.password_confirmation
            }

            if (editUserForm.value.email !== '') payload.email = editUserForm.value.email;

            await store.dispatch('admin/updateUser', payload);
        }

        function toggleEditUser(user) {
            selectedUser.value = user;
            isSeeTasksModalOpen.value = false;
            isCreateUserModalOpen.value = false;
            isEditUserModalOpen.value = !isEditUserModalOpen.value;

            if (user !== null) editUserForm.value.name = selectedUser.value.name
            
            if (user === null) {
                editUserForm.value = {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                };
            }

            store.commit('app/SET_SUCCESS_MESSAGE', null);
        }

        function toggleUserTasks(user) {
            selectedUser.value = user;
            isSeeTasksModalOpen.value = !isSeeTasksModalOpen.value;
            isEditUserModalOpen.value = false;
            isCreateUserModalOpen.value = false;
        }

        function toggleCreateUser() {
            isCreateUserModalOpen.value = !isCreateUserModalOpen.value;
            isEditUserModalOpen.value = false;
            isSeeTasksModalOpen.value = false;

            store.commit('app/SET_SUCCESS_MESSAGE', null);
        }

        return {
            users,
            countTasksByStatus,
            selectedUser,
            formatStatus, 
            deleteUser,
            deleteTask,
            toggleUserTasks,
            toggleEditUser,
            toggleCreateUser,
            isSeeTasksModalOpen,
            isEditUserModalOpen,
            isCreateUserModalOpen,
            editUserForm,
            editUser,
            successMessage,
            createUser
        }
    }
}
</script>
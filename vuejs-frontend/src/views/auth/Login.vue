<template>
    <div class='w-full flex min-h-screen items-center'>

        <div class='w-full md:max-w-xl md:mx-auto'>
            <div class="px-8">
                <div class="mb-8">
                    <h2 class="text-4xl font-bold tracking-tight mb-2">Log in.</h2>
                    <p class="text-sm text-gray-800">Log in with email and password you were provided.</p>
                </div>
                
                <form @submit.prevent="submitForm" class="mb-4">
                    <div class="mb-4">
                        <label 
                            for="loginEmail" 
                            class="text-sm font-semibold text-gray-900 mb-1 block"
                        >Enter your email address</label>
                        <input 
                            id="loginEmail" 
                            type="text"
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring" 
                            placeholder="name@example.com"
                            v-model="email"
                            required />
                    </div>
                    <div class="mb-4">
                        <label 
                            for="loginPassword" 
                            class="text-sm font-semibold text-gray-900 mb-1 block">Enter your password</label>
                        <input 
                            id="loginPassword" 
                            type="password"
                            class="bg-white text-gray-700 border border-gray-300 rounded py-2 px-4 block w-full focus:border-blue-500 focus:outline-none focus:ring"  
                            placeholder="at least 8 characters"
                            v-model="password"
                            required />
                    </div>

                    <div class="mb-4 flex items-center">
                        <input type="checkbox" id="checkbox" class="mr-2" v-model="adminLogin">
                        <label for="checkbox" class="text-indigo-500 text-sm font-semibold">Admin login</label>
                    </div>

                    <div>
                        <button class="w-full bg-indigo-500 py-2 rounded text-white">Log in</button>
                    </div>
                </form>

                <ul v-if="errors"
                    id="error"
                    class="text-sm text-red-500 list-disc list-inside">
                    <li>
                    {{ errors }}
                    </li>
                </ul>

            </div>
        </div>

    </div>
</template>
    
<script>
import { ref, computed } from 'vue';
import { useStore } from "vuex";

export default {
    name: 'Login',
    setup() {
        const store = useStore();
        const email = ref("");
        const password = ref("");
        const adminLogin = ref(false);
        const loading = computed(() => store.getters["app/loading"]);
        const errors = computed(() => store.getters["app/errors"]);

        async function submitForm() {
            const payload = {
                email: email.value,
                password: password.value,
            };

            if(adminLogin.value) {
                await store.dispatch("auth/adminLogin", payload);
            } else {
                await store.dispatch("auth/userLogin", payload);
            }
        }

        return {
            email,
            password,
            adminLogin,
            loading,
            errors,
            submitForm
        }
    }
}
</script>
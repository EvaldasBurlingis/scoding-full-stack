<template>
  <div class="w-full" v-if="isAuthenticated">
    <div class="relative border-b border-gray-200">
      <div class="container mx-auto px-4">

        <!-- Admin -->
        <div class="flex justify-between items-center border-b-2 border-gray-100 py-2" v-if="isAuthenticated && isAdmin">
          <div>
            <span class="text-gray-800 text-sm tracking-tight hidden md:inline-block">
              Logged in as 
              <span class="text-purple-600 font-medium block md:inline-block">
                {{ user?.name }}
              </span>
            </span>
          </div>
          <div class="flex items-center">

            <div class="mr-16">
              <router-link exact :to="{ name: 'admin-home' }" class="mr-4 font-medium" active-class="text-indigo-600">Dashboard</router-link>
              <router-link exact :to="{ name: 'admin-users' }" class="mr-4 font-medium" active-class="text-indigo-600">Users</router-link>
            </div>

            <button @click="logout" class="btn btn__dark">
              Logout
            </button>

          </div>
        </div>
        <!-- Admin ended -->

        <!--Guest -->
        <div v-if="isAuthenticated && !isAdmin" class="flex justify-between items-center py-2">
            <div>
              <span class="text-gray-800 text-sm tracking-tight hidden md:inline-block">
                Logged in as 
                <span class="text-purple-600 font-medium block md:inline-block">
                  {{ user?.name }}
                </span>
              </span>
            </div>
            <button @click="logout" class="btn btn__dark">
              Logout
            </button>
        </div>
        <!-- Guest ended -->
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue';
import { useStore } from 'vuex';

export default {
    name: 'Header',
    setup() {
        const store = useStore();
        const user = computed(() => store.getters['auth/user']);
        const isAuthenticated = computed(() => store.getters['auth/isAuthenticated']);
        const isAdmin = computed(() => store.getters['auth/isAdmin']);


        async function logout() {
            await store.dispatch('auth/logout');
        }

        return {
            logout,
            user,
            isAuthenticated,
            isAdmin
        }
    }
}
</script>
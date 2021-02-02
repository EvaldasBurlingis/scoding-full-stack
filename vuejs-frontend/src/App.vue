<template>
  <div id="app" class="min-h-screen bg-white relative">
    <Loader />
    <Header />
    <router-view/>
  </div>
</template>

<script>
import Header from '@/components/TheHeader';
import Loader from '@/components/Loader';

export default {
  name: 'App',
  components: {
    Header,
    Loader
  }
}
</script>

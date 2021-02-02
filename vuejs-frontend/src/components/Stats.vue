<template>
  <div class="px-4 py-4 rounded mr-4" 
    :class="[ 
      variant === 'purple' ? 'bg-purple-100' : 'bg-gray-100',
      variant === 'blue' ? 'bg-blue-100' : 'bg-gray-100',
      variant === 'yellow' ? 'bg-yellow-100' : 'bg-gray-100',
      variant === 'green' ? 'bg-green-100' : 'bg-gray-100'
    ]">
    <div class="flex justify-center">
      <div class="text-center mx-2" v-for="(c, index) in count" :key="index">
        <h6 
          class="text-2xl font-bold" 
          :class="[ 
            variant === 'purple' && 'text-purple-600',
            variant === 'blue' && 'text-blue-600',
            variant === 'yellow' && 'text-yellow-600',
            variant === 'green' && 'text-green-600'
          ]" 
        >{{ c.count }}</h6>
        <p class="text-xs text-gray-800">{{ c.title }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: 'Stats',
    props: {
      count: {
        type: Array
      },
      variant: {
        type: String
      }
    }
}
</script>
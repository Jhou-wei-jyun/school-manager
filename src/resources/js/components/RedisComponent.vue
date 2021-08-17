<template>
    <div class="container card" style="border-radius: 5px;">
          {{ data }}
    </div>
</template>
<script>
export default {
    data: function (){
        return {
            data:[],
        }
    },
    watch:{},
    mounted() {
        this.getRedis();
    },
    methods:{
        getRedis(){
             axios
              .get('redis')
                .then(response => {
                    console.log(response.data);
                    this.data = response.data;
                })
                .catch(error => {})
                .finally(() => {
                });
        },
    },
}
</script>
<style land="scss" scoped>

</style>

<template>
  <div>
      <div>
          <slot></slot>
      </div>
      <div>
          <section class="p-0 py-1 navbar fixed-bottom bg-white" id="navbar" style="box-shadow: 1px 1px 2px 1px #dbdbdb;">
            <div class="rounded-top d-flex justify-content-around" style="width: 100%">
                <router-link style="text-decoration: none; width: 50%" to="/app-teacher/schedule">
                    <div class="d-flex justify-content-center align-items-center my-2">
                        <i style="font-size: 11pt" class="fas fa-qrcode nav-icon"></i>
                    </div>
                </router-link>
                <router-link style="text-decoration: none; width: 50%" to="/app-teacher/profile">
                    <div class="d-flex justify-content-center align-items-center my-2">
                        <i style="font-size: 11pt" class="far fa-user nav-icon"></i>
                    </div>
                </router-link>
            </div>
        </section>
      </div>
  </div>
</template>

<script>
export default {
    
}
</script>

<style scoped>
    #app{
        background: white;
    }
</style>
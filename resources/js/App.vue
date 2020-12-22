<template>
  <div id="app">

    <div class="container">

      <div class="row">
        <div class="col-sm-12 col-md-12">
          <Nav/>
        </div>
      </div>
      
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="main-content">
            <router-view></router-view>
          </div>
        </div>
      </div>

    </div>

  </div>
</template>

<script>
import Nav from './partials/Nav'

export default {

    name: 'App',
    
    components: {
      Nav
    }

}
</script>

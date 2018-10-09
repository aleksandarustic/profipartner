@extends('layouts.master')

@section('content')

<div class="content-wrapper">

    <!-- Main content -->
    <div class="content">
        <router-view :user="user" @updateuser="updateUser"></router-view>
        <vue-progress-bar></vue-progress-bar>
    </div>
    <!-- /.content -->
  </div>

@endsection

@section('custom_script')
<script>

    var app = new Vue({
          router,
          el: '#app',
          data: {
            logout_route: '{{route("logout")}}',
            role_types: {!!$roles!!},
            user:{!!Auth::user()!!}
          },
          methods:{
            logout(){
                axios.post(this.logout_route).then(response => {
                window.location.reload();
             })
           },
           subIsActive(input) {
              const paths = Array.isArray(input) ? input : [input]
              return paths.some(path => {
                return this.$route.path.indexOf(path) === 0 // current path starts with this path string
              });
           },
          updateUser(user) {
            this.user = user;
           }
         }

    });

</script>
@endsection
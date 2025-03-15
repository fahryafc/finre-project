 <!-- bundle -->
 @yield('script')
 <!-- App js -->
 @yield('script-bottom')

 <script>
     // Cek apakah user berpindah halaman
     const lastPage = localStorage.getItem("last_page");
     const currentPage = window.location.pathname;
     if (lastPage && lastPage !== currentPage) {
         localStorage.clear();
     }
 </script>

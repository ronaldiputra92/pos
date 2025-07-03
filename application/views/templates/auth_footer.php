</div>
    </div>

    <p style="text-align: center;">2025 Copyright &copy; by Digital Kreasi Komputer.</p>

    <script src="<?php echo base_url('assets/') ?>vendors/jquery/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Universal auto-hide alert functionality
            setTimeout(function() {
                $('.alert-container .alert').fadeOut('slow', function() {
                    $(this).closest('.alert-container').remove();
                });
                
                // Fallback for any alert elements
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
                
                // Also target common Bootstrap alert classes
                $('.alert-success, .alert-danger, .alert-warning, .alert-info').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 3000);

            // Add close button functionality for alerts
            $(document).on('click', '.alert .close', function() {
                $(this).closest('.alert').fadeOut('fast', function() {
                    $(this).remove();
                });
            });
        });

        const password = document.getElementById('password'); // id dari input password
        const showHide = document.getElementById('showHide'); // id span showHide dalam input group password

        if (password && showHide) {
            password.type = 'password'; // set type input password menjadi password
            showHide.innerHTML = '<i class="glyphicon glyphicon-eye-open"></i>'; // masukan icon eye dalam icon bootstrap
            showHide.style.cursor = 'pointer'; // ubah cursor menjadi pointer
            // jadi ketika span di hover maka cursornya berubah pointer

            showHide.addEventListener('click', () => {
            // ketika span diclick
                if (password.type === 'password') {
                    // jika type inputnya password
                    password.type = 'text'; // ubah type menjadi text
                    showHide.innerHTML = '<i class="glyphicon glyphicon-eye-close"></i>'; // ubah icon menjadi eye slash
                } else {
                    // jika type bukan password (text)
                    showHide.innerHTML = '<i class="glyphicon glyphicon-eye-open"></i>'; // ubah icon menjadi eye
                    password.type = 'password'; // ubah type menjadi password
                }
            });
        }
    </script>

  </body>
</html>
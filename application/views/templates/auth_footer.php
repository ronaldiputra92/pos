</div>
    </div>

    <p style="text-align: center;">2025 Copyright &copy; by Digital Kreasi Komputer.</p>

    <script>
        const password = document.getElementById('password'); // id dari input password
        const showHide = document.getElementById('showHide'); // id span showHide dalam input group password

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
    </script>

  </body>
</html>
<footer class="site-footer" id="tentang">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5"><h4 class="fw-black">KosKu</h4><p>Platform pencarian dan pengelolaan kos untuk membantu pengguna menemukan hunian yang nyaman, jelas, dan sesuai kebutuhan di Palu.</p></div>
            <div class="col-lg-3"><h6>Menu</h6><a href="{{ route('home') }}">Home</a><a href="{{ route('kos.index') }}">Daftar Kos</a><a href="{{ route('kontak') }}">Kontak</a></div>
            <div class="col-lg-4"><h6>Kontak</h6><p><i class="bi bi-geo-alt"></i> Palu, Sulawesi Tengah</p><p><i class="bi bi-whatsapp"></i> 0821-0000-0000</p><div class="socials"><i class="bi bi-instagram"></i><i class="bi bi-facebook"></i><i class="bi bi-envelope"></i></div></div>
        </div>
        <div class="footer-bottom">© {{ date('Y') }} KosKu. Semua hak cipta dilindungi.</div>
    </div>
</footer>

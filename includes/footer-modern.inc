    <!-- ===== FOOTER ===== -->
    <footer class="site-footer">
        <p>&copy; UK Pinball League <?= date('Y') ?></p>
    </footer>

</div><!-- /.site-wrapper -->

<?php if (!empty($pageScript)): ?>
<script>
<?= $pageScript ?>
</script>
<?php endif; ?>

<script>
(function ()
{
    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('mainNav');
    if (!toggle || !nav) return;

    toggle.addEventListener('click', function ()
    {
        var open = nav.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
})();
</script>

</body>
</html>

    </main>
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-5 flex items-center justify-center">
            <p class="text-gray-400 text-xs text-center">
                <?php if(isset($associacao['nome'])): ?>
                    <?= htmlspecialchars($associacao['nome']) ?> &middot;
                <?php endif; ?>
                Plataforma AssocieHub &copy; <?= date('Y') ?>
            </p>
        </div>
    </footer>
</body>
</html>

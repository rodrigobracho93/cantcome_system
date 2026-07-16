<div x-data="pwaInstall()" x-cloak
    x-show="showBanner && !dismissed"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-full opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-full opacity-0"
    class="fixed bottom-0 inset-x-0 z-50 p-3 sm:p-4 pointer-events-none">
    <div class="max-w-md mx-auto pointer-events-auto">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 shrink-0">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Instalar <?php echo e($systemName ?? 'CantCome'); ?></p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Acceso rápido desde tu pantalla de inicio</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button @click="dismiss()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="Ahora no">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <button @click="install()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors whitespace-nowrap">
                    Instalar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function pwaInstall() {
    return {
        showBanner: false,
        dismissed: false,
        deferredPrompt: null,

        init() {
            if (localStorage.getItem('pwa_dismissed') === 'true') {
                this.dismissed = true;
                return;
            }
            if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone) {
                return;
            }
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.deferredPrompt = e;
                setTimeout(() => { this.showBanner = true; }, 3000);
            });
            window.addEventListener('appinstalled', () => {
                this.showBanner = false;
                this.deferredPrompt = null;
            });
        },

        async install() {
            if (!this.deferredPrompt) return;
            this.deferredPrompt.prompt();
            const { outcome } = await this.deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                this.showBanner = false;
            }
            this.deferredPrompt = null;
        },

        dismiss() {
            this.showBanner = false;
            this.dismissed = true;
            localStorage.setItem('pwa_dismissed', 'true');
        }
    };
}
</script>
<?php /**PATH C:\xampp\Proyectos\cantcome_system\resources\views/components/pwa-install.blade.php ENDPATH**/ ?>
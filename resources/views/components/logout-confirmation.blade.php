<div id="logout-confirmation" class="fixed inset-0 z-[100] hidden items-center justify-center p-4" aria-hidden="true">
    <div data-logout-overlay class="absolute inset-0 bg-slate-950/50 opacity-0 transition-opacity duration-200"></div>
    <section data-logout-dialog class="relative w-full max-w-sm translate-y-2 scale-95 rounded-2xl bg-white p-6 opacity-0 shadow-2xl transition duration-200" role="dialog" aria-modal="true" aria-labelledby="logout-confirmation-title">
        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-full bg-red-50 text-red-600">
            <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
        </div>
        <h2 id="logout-confirmation-title" class="text-lg font-bold text-slate-900">Konfirmasi Logout</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">Apakah Anda yakin ingin logout?</p>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" data-logout-cancel class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">Batal</button>
            <button type="button" data-logout-confirm class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">Logout</button>
        </div>
    </section>
</div>

<script>
    (() => {
        const modal = document.getElementById('logout-confirmation');
        if (!modal) return;

        const overlay = modal.querySelector('[data-logout-overlay]');
        const dialog = modal.querySelector('[data-logout-dialog]');
        const cancel = modal.querySelector('[data-logout-cancel]');
        const confirm = modal.querySelector('[data-logout-confirm]');
        let logoutForm = null;
        let lastTrigger = null;

        const close = () => {
            overlay.classList.remove('opacity-100');
            dialog.classList.remove('opacity-100', 'translate-y-0', 'scale-100');
            window.setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
                lastTrigger?.focus();
            }, 200);
        };

        const open = (form, trigger) => {
            logoutForm = form;
            lastTrigger = trigger;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            requestAnimationFrame(() => {
                overlay.classList.add('opacity-100');
                dialog.classList.add('opacity-100', 'translate-y-0', 'scale-100');
                cancel.focus();
            });
        };

        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement) || !form.matches('form[action$="/logout"]')) return;
            event.preventDefault();
            event.stopImmediatePropagation();
            open(form, document.activeElement);
        }, true);

        document.querySelectorAll('aside').forEach((sidebar) => {
            const title = Array.from(sidebar.querySelectorAll('h1')).find((heading) => heading.textContent.trim() === 'SIMARSIP');
            if (!title) return;

            const brand = title.parentElement;
            const currentLogo = brand.querySelector('img');
            if (currentLogo) {
                currentLogo.src = "{{ asset('images/logo-wuri.webp') }}";
                currentLogo.classList.add('h-10', 'w-10', 'rounded-lg', 'object-contain');
                return;
            }

            const iconContainer = Array.from(brand.children).find((child) => child.querySelector('.fa-book-open'));
            if (!iconContainer) return;
            const logo = document.createElement('img');
            logo.src = "{{ asset('images/logo-wuri.webp') }}";
            logo.alt = 'Logo SIMARSIP';
            logo.className = 'h-10 w-10 rounded-lg object-contain';
            iconContainer.replaceWith(logo);
        });

        document.querySelectorAll('a').forEach((link) => {
            if (link.textContent.trim() !== 'Logout') return;
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('logout') }}";
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                open(form, link);
            });
        });

        document.querySelectorAll('form[action$="/logout"] button, a').forEach((trigger) => {
            if (trigger.matches('a') && trigger.textContent.trim() !== 'Logout') return;
            trigger.classList.add('rounded-lg', 'px-4', 'py-3', 'transition', 'duration-200', 'hover:bg-gray-800', 'hover:text-white');
        });

        document.querySelectorAll('input[type="file"]').forEach((input) => {
            if (input.parentElement?.querySelector('[data-upload-limit]') || input.closest('form')?.textContent.includes('Maximal upload file 5 MB.')) return;
            const notice = document.createElement('p');
            notice.dataset.uploadLimit = 'true';
            notice.className = 'mt-2 text-xs text-gray-500';
            notice.textContent = 'Maximal upload file 5 MB.';
            input.insertAdjacentElement('afterend', notice);
        });

        confirm.addEventListener('click', () => logoutForm?.submit());
        cancel.addEventListener('click', close);
        overlay.addEventListener('click', close);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) close();
        });
    })();
</script>

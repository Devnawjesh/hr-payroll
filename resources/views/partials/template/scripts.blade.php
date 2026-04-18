<script src="{{ asset(config('madpos_ui.assets_base').'/js/vendor/modernizr-3.5.0.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset(config('madpos_ui.assets_base').'/js/bootstrap5-bridge.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
<script src="{{ asset(config('madpos_ui.assets_base').'/js/vendor/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset(config('madpos_ui.assets_base').'/js/vendor/select2.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="{{ asset(config('madpos_ui.assets_base').'/js/main.js') }}"></script>
<script src="{{ asset(config('madpos_ui.assets_base').'/js/jquery.PrintArea.js') }}"></script>
<script src="{{ asset(config('madpos_ui.assets_base').'/js/jquery-jvectormap-2.0.3.min.js') }}"></script>
<script src="{{ asset(config('madpos_ui.assets_base').'/js/jquery-jvectormap-world-mill.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<style>
    .table .btn.btn-sm.action-icon-btn {
        position: relative;
        min-width: 34px;
        padding: 0.35rem 0.45rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        font-size: 0;
    }
    .table .btn.btn-sm.action-icon-btn i {
        font-size: 14px;
        line-height: 1;
    }
    .table .btn.btn-sm.action-icon-btn::after {
        content: attr(data-label);
        position: absolute;
        left: 50%;
        top: -34px;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: #fff;
        font-size: 11px;
        line-height: 1;
        padding: 6px 8px;
        border-radius: 4px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.12s ease-in-out;
    }
    .table .btn.btn-sm.action-icon-btn:hover::after {
        opacity: 1;
    }
</style>
<script>
    (function () {
        const iconMap = {
            view: 'icon-eye',
            edit: 'icon-pencil',
            delete: 'icon-trash',
        };

        function normalizeText(el) {
            return (el.textContent || '').trim().toLowerCase();
        }

        function decorateActionButtons() {
            const selectors = [
                'table .btn.btn-sm',
                'table form button.btn.btn-sm',
            ];

            document.querySelectorAll(selectors.join(',')).forEach((btn) => {
                if (btn.classList.contains('action-icon-btn')) {
                    return;
                }

                const label = normalizeText(btn);
                if (!Object.prototype.hasOwnProperty.call(iconMap, label)) {
                    return;
                }

                const iconClass = iconMap[label];
                btn.classList.add('action-icon-btn');
                btn.setAttribute('data-label', label.charAt(0).toUpperCase() + label.slice(1));
                btn.setAttribute('title', label.charAt(0).toUpperCase() + label.slice(1));
                btn.setAttribute('aria-label', label.charAt(0).toUpperCase() + label.slice(1));
                btn.innerHTML = `<i class="${iconClass}"></i>`;
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', decorateActionButtons);
        } else {
            decorateActionButtons();
        }
    })();
</script>
@stack('scripts')

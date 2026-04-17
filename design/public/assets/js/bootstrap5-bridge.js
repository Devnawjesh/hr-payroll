(function() {
    function syncBootstrapDataAttributes(root) {
        var scope = root || document;

        scope.querySelectorAll("[data-toggle]").forEach(function(element) {
            if (!element.hasAttribute("data-bs-toggle")) {
                element.setAttribute("data-bs-toggle", element.getAttribute("data-toggle"));
            }
        });

        scope.querySelectorAll("[data-target]").forEach(function(element) {
            if (!element.hasAttribute("data-bs-target")) {
                element.setAttribute("data-bs-target", element.getAttribute("data-target"));
            }
        });

        scope.querySelectorAll("[data-dismiss]").forEach(function(element) {
            if (!element.hasAttribute("data-bs-dismiss")) {
                element.setAttribute("data-bs-dismiss", element.getAttribute("data-dismiss"));
            }
        });
    }

    function installJQueryBridge($) {
        if (!window.bootstrap || !$) {
            return;
        }

        $.fn.modal = function(action) {
            return this.each(function() {
                var instance = window.bootstrap.Modal.getOrCreateInstance(this);

                if (!action || action === "toggle") {
                    instance.toggle();
                    return;
                }

                if (action === "show") {
                    instance.show();
                    return;
                }

                if (action === "hide") {
                    instance.hide();
                    return;
                }

                if (action === "dispose") {
                    instance.dispose();
                }
            });
        };

        $.fn.dropdown = function(action) {
            return this.each(function() {
                var instance = window.bootstrap.Dropdown.getOrCreateInstance(this);

                if (!action || action === "toggle") {
                    instance.toggle();
                    return;
                }

                if (action === "show") {
                    instance.show();
                    return;
                }

                if (action === "hide") {
                    instance.hide();
                    return;
                }

                if (action === "dispose") {
                    instance.dispose();
                }
            });
        };

        $.fn.tab = function(action) {
            return this.each(function() {
                var instance = window.bootstrap.Tab.getOrCreateInstance(this);

                if (!action || action === "show") {
                    instance.show();
                }
            });
        };
    }

    function boot() {
        syncBootstrapDataAttributes(document);

        if (window.jQuery) {
            installJQueryBridge(window.jQuery);
        }
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", boot);
    } else {
        boot();
    }
})();

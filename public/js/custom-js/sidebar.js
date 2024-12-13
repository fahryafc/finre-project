document.addEventListener('DOMContentLoaded', function () {
    const collapsibleLinks = document.querySelectorAll('[data-fc-type="collapse"]');

    collapsibleLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            const subMenu = this.nextElementSibling;
            if (subMenu) {
                if (subMenu.classList.contains('hidden')) {
                    subMenu.classList.remove('hidden');
                } else {
                    subMenu.classList.add('hidden');
                }
            }
        });
    });
});
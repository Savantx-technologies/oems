/* =========================
   Global Toast System
========================= */

window.showToast = function(message = "Success") {

    const toast = document.createElement('div');

    toast.className = `
        fixed top-6 right-6 z-50
        bg-white border border-green-200
        shadow-xl rounded-xl px-6 py-4
        transform translate-x-full opacity-0
        transition-all duration-500
    `;

    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 flex items-center justify-center
                        rounded-full bg-green-100 text-green-600">
                ✓
            </div>
            <span class="text-sm font-semibold text-gray-800">
                ${message}
            </span>
        </div>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-x-full','opacity-0');
    }, 50);

    setTimeout(() => {
        toast.classList.add('translate-x-full','opacity-0');
        setTimeout(() => toast.remove(), 500);
    }, 3000);
};


/* =========================
   Global Debounce
========================= */

window.debounce = function(func, delay = 500) {

    let timer;

    return function(...args) {
        clearTimeout(timer);
        timer = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
};
document.getElementById('selectAll').addEventListener('change', function () {

    const isChecked = this.checked;

    const rows = document.querySelectorAll('.question-row');

    rows.forEach(row => {

        const checkbox = row.querySelector('.question-check');

        if (checkbox) {
            checkbox.checked = isChecked;
        }

    });

});

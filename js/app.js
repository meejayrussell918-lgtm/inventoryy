// app.js - client-side scripts

// 1. Bootstrap form validation (shows red messages when a field is invalid)
document.querySelectorAll('.needs-validation').forEach(function (form) {
    var pass = form.querySelector('#password');
    var confirm = form.querySelector('#confirm');

    function checkMatch() {
        if (pass && confirm) {
            confirm.setCustomValidity(pass.value === confirm.value ? '' : 'Passwords do not match');
        }
    }
    if (pass && confirm) {
        pass.addEventListener('input', checkMatch);
        confirm.addEventListener('input', checkMatch);
    }

    form.addEventListener('submit', function (event) {
        checkMatch();
        if (!form.checkValidity()) {
            event.preventDefault();
        }
        form.classList.add('was-validated');
    });
});

// 2. Show / hide password
document.querySelectorAll('.toggle-pass').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var input = btn.parentElement.querySelector('input');
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
        btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });
});

// 3. Password strength meter (register page)
var strengthBar = document.getElementById('strengthBar');
var regPass = document.getElementById('password');
if (strengthBar && regPass) {
    var strengthText = document.getElementById('strengthText');
    regPass.addEventListener('input', function () {
        var v = regPass.value, score = 0;
        if (v.length >= 6) score++;
        if (v.length >= 10) score++;
        if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
        if (/\d/.test(v) && /[^A-Za-z0-9]/.test(v)) score++;
        var levels = [
            { w: '0%',   c: '#e2e8f0', t: '' },
            { w: '25%',  c: '#ef4444', t: 'Weak' },
            { w: '50%',  c: '#f59e0b', t: 'Fair' },
            { w: '75%',  c: '#22c55e', t: 'Good' },
            { w: '100%', c: '#16a34a', t: 'Strong' }
        ];
        var l = v ? levels[Math.max(score, 1)] : levels[0];
        strengthBar.style.width = l.w;
        strengthBar.style.background = l.c;
        strengthText.textContent = l.t;
    });
}

// 4. Live search: hide table rows that do not match what is typed
var search = document.getElementById('search');
if (search) {
    var rowCount = document.getElementById('rowCount');
    search.addEventListener('input', function () {
        var text = search.value.toLowerCase(), shown = 0;
        document.querySelectorAll('#productTable tbody tr:not(.empty-row)').forEach(function (row) {
            var match = row.textContent.toLowerCase().includes(text);
            row.style.display = match ? '' : 'none';
            if (match) shown++;
        });
        if (rowCount) rowCount.textContent = shown;
    });
    // Enter would reload the page; live search already handles it
    search.form.addEventListener('submit', function (e) { e.preventDefault(); });
}

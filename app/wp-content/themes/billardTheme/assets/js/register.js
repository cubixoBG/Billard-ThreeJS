document.getElementById('password').addEventListener('input', function () {
    const val = this.value;
    const bar = document.getElementById('strength-bar');
    const text = document.getElementById('strength-text');
    let score = 0;

    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { w: '0%', color: 'transparent', label: '' },
        { w: '33%', color: '#c62828', label: 'Faible' },
        { w: '66%', color: '#f57f17', label: 'Moyen' },
        { w: '85%', color: '#2e7d32', label: 'Fort' },
        { w: '100%', color: '#1b5e20', label: 'Très fort' },
    ];

    bar.style.width = levels[score].w;
    bar.style.background = levels[score].color;
    text.textContent = val.length > 0 ? levels[score].label : '';
    text.style.color = levels[score].color;
});

document.getElementById('btn-register').addEventListener('click', async () => {
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const password2 = document.getElementById('password2').value;
    const btnText = document.querySelector('.btn-text');
    const spinner = document.getElementById('spinner');
    const errorEl = document.getElementById('alert-error');
    const successEl = document.getElementById('alert-success');

    errorEl.style.display = 'none';
    successEl.style.display = 'none';

    if (!username || !email || !password || !password2) {
        errorEl.textContent = 'Tous les champs sont obligatoires.';
        errorEl.style.display = 'block';
        return;
    }
    if (password !== password2) {
        errorEl.textContent = 'Les mots de passe ne correspondent pas.';
        errorEl.style.display = 'block';
        return;
    }
    if (password.length < 8) {
        errorEl.textContent = 'Le mot de passe doit faire au moins 8 caractères.';
        errorEl.style.display = 'block';
        return;
    }

    btnText.style.display = 'none';
    spinner.style.display = 'block';
    document.getElementById('btn-register').disabled = true;

    const formData = new FormData();
    formData.append('action', 'billard_register');
    formData.append('nonce', billardRegister.nonce);
    formData.append('username', username);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('password2', password2);

    try {
        const res = await fetch(billardRegister.ajaxUrl, {
            method: 'POST',
            body: formData,
        });
        const data = await res.json();

        if (data.success) {
            successEl.textContent = 'Compte créé ! Redirection...';
            successEl.style.display = 'block';
            setTimeout(() => window.location.href = data.data.redirect, 800);
        } else {
            errorEl.textContent = data.data.message;
            errorEl.style.display = 'block';
            btnText.style.display = 'inline';
            spinner.style.display = 'none';
            document.getElementById('btn-register').disabled = false;
        }
    } catch (e) {
        errorEl.textContent = 'Erreur réseau, réessayez.';
        errorEl.style.display = 'block';
        btnText.style.display = 'inline';
        spinner.style.display = 'none';
        document.getElementById('btn-register').disabled = false;
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') document.getElementById('btn-register').click();
});
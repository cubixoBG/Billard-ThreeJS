document.getElementById('btn-login').addEventListener('click', async () => {
    const username  = document.getElementById('username').value.trim();
    const password  = document.getElementById('password').value;
    const spinner   = document.getElementById('spinner');
    const errorEl   = document.getElementById('alert-error');
    const successEl = document.getElementById('alert-success');

    errorEl.style.display   = 'none';
    successEl.style.display = 'none';

    if (!username || !password) {
        errorEl.textContent   = 'Veuillez remplir tous les champs.';
        errorEl.style.display = 'block';
        return;
    }

    spinner.style.display = 'block';
    document.getElementById('btn-login').disabled = true;

    const formData = new FormData();
    formData.append('action',   'billard_login');
    formData.append('nonce',    billardLogin.nonce);
    formData.append('username', username);
    formData.append('password', password);

    try {
        const res  = await fetch(billardLogin.ajaxUrl, {
            method: 'POST',
            body: formData,
        });
        const data = await res.json();

        if (data.success) {
            successEl.textContent   = 'Connexion réussie, redirection...';
            successEl.style.display = 'block';
            setTimeout(() => window.location.href = data.data.redirect, 800);
        } else {
            errorEl.textContent   = data.data.message;
            errorEl.style.display = 'block';
            spinner.style.display = 'none';
            document.getElementById('btn-login').disabled = false;
        }
    } catch (e) {
        errorEl.textContent   = 'Erreur réseau, réessayez.';
        errorEl.style.display = 'block';
        spinner.style.display = 'none';
        document.getElementById('btn-login').disabled = false;
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') document.getElementById('btn-login').click();
});
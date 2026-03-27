<?php
/*
 * Template Name: Page Register
 */

if (is_user_logged_in()) {
    wp_redirect(home_url('/'));
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription — Billard 3D</title>
    <?php wp_head(); ?>
</head>
<body>

<div class="auth-container">

    <div class="auth-logo">
        <div class="ball-icon"></div>
        <h1>Billard 3D</h1>
        <p>Créez votre compte</p>
    </div>

    <div class="auth-card">
        <h2>Inscription</h2>

        <div class="alert error"   id="alert-error"></div>
        <div class="alert success" id="alert-success"></div>

        <div class="field">
            <label for="username">Pseudo</label>
            <input type="text" id="username" placeholder="Votre pseudo" autocomplete="username">
        </div>

        <div class="field">
            <label for="email">Adresse email</label>
            <input type="email" id="email" placeholder="vous@example.com" autocomplete="email">
        </div>

        <div class="field">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" placeholder="Min. 8 caractères" autocomplete="new-password">
            <div class="password-strength">
                <div class="password-strength-bar" id="strength-bar"></div>
            </div>
            <div class="strength-text" id="strength-text"></div>
        </div>

        <div class="field">
            <label for="password2">Confirmer le mot de passe</label>
            <input type="password" id="password2" placeholder="••••••••" autocomplete="new-password">
        </div>

        <button class="btn-submit" id="btn-register" style="margin-top: 8px;">
            <span class="btn-text">Créer mon compte</span>
            <span class="spinner" id="spinner"></span>
        </button>

        <div class="auth-footer">
            Déjà un compte ?
            <a href="<?php echo home_url('/login'); ?>">Se connecter</a>
        </div>
    </div>
</div>

<script>
// Indicateur de force du mot de passe
document.getElementById('password').addEventListener('input', function () {
    const val = this.value;
    const bar  = document.getElementById('strength-bar');
    const text = document.getElementById('strength-text');
    let score  = 0;

    if (val.length >= 8)               score++;
    if (/[A-Z]/.test(val))             score++;
    if (/[0-9]/.test(val))             score++;
    if (/[^A-Za-z0-9]/.test(val))      score++;

    const levels = [
        { w: '0%',   color: 'transparent', label: '' },
        { w: '33%',  color: '#c62828',     label: 'Faible' },
        { w: '66%',  color: '#f57f17',     label: 'Moyen' },
        { w: '85%',  color: '#2e7d32',     label: 'Fort' },
        { w: '100%', color: '#1b5e20',     label: 'Très fort' },
    ];

    bar.style.width      = levels[score].w;
    bar.style.background = levels[score].color;
    text.textContent     = val.length > 0 ? levels[score].label : '';
    text.style.color     = levels[score].color;
});

document.getElementById('btn-register').addEventListener('click', async () => {
    const username  = document.getElementById('username').value.trim();
    const email     = document.getElementById('email').value.trim();
    const password  = document.getElementById('password').value;
    const password2 = document.getElementById('password2').value;
    const btnText   = document.querySelector('.btn-text');
    const spinner   = document.getElementById('spinner');
    const errorEl   = document.getElementById('alert-error');
    const successEl = document.getElementById('alert-success');

    errorEl.style.display  = 'none';
    successEl.style.display = 'none';

    // Validations côté client
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
    formData.append('action',    'billard_register');
    formData.append('nonce',     '<?php echo wp_create_nonce("billard_nonce"); ?>');
    formData.append('username',  username);
    formData.append('email',     email);
    formData.append('password',  password);
    formData.append('password2', password2);

    try {
        const res  = await fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
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
</script>

<?php wp_footer(); ?>
</body>
</html>
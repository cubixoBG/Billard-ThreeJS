<?php
/*
 * Template Name: Page Login
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
    <title>Connexion — Billard 3D</title>
    <?php wp_head(); ?>
</head>
<body>

<div class="auth-container">

    <div class="auth-logo">
        <div class="ball-icon"></div>
        <h1>Billard 3D</h1>
        <p>Connectez-vous pour jouer</p>
    </div>

    <div class="auth-card">
        <h2>Connexion</h2>

        <div class="alert error" id="alert-error"></div>
        <div class="alert success" id="alert-success"></div>

        <div class="field">
            <label for="username">Identifiant ou email</label>
            <input type="text" id="username" placeholder="Votre pseudo" autocomplete="username">
        </div>

        <div class="field">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" placeholder="••••••••" autocomplete="current-password">
        </div>

        <div class="row-remember">
            <input type="checkbox" id="remember">
            <label for="remember">Se souvenir de moi</label>
        </div>

        <button class="btn-submit" id="btn-login">
            <span class="btn-text">Jouer →</span>
            <span class="spinner" id="spinner"></span>
        </button>

        <div class="auth-footer">
            Pas encore de compte ?
            <a href="<?php echo home_url('/register'); ?>">S'inscrire gratuitement</a>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-login').addEventListener('click', async () => {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const remember = document.getElementById('remember').checked;
    const btnText  = document.querySelector('.btn-text');
    const spinner  = document.getElementById('spinner');
    const errorEl  = document.getElementById('alert-error');
    const successEl = document.getElementById('alert-success');

    errorEl.style.display = 'none';
    successEl.style.display = 'none';

    if (!username || !password) {
        errorEl.textContent = 'Veuillez remplir tous les champs.';
        errorEl.style.display = 'block';
        return;
    }

    // Loading state
    btnText.style.display = 'none';
    spinner.style.display = 'block';
    document.getElementById('btn-login').disabled = true;

    const formData = new FormData();
    formData.append('action', 'billard_login');
    formData.append('nonce', '<?php echo wp_create_nonce("billard_nonce"); ?>');
    formData.append('username', username);
    formData.append('password', password);
    formData.append('remember', remember);

    try {
        const res  = await fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            body: formData,
        });
        const data = await res.json();

        if (data.success) {
            successEl.textContent = 'Connexion réussie, redirection...';
            successEl.style.display = 'block';
            setTimeout(() => window.location.href = data.data.redirect, 800);
        } else {
            errorEl.textContent = data.data.message;
            errorEl.style.display = 'block';
            btnText.style.display = 'inline';
            spinner.style.display = 'none';
            document.getElementById('btn-login').disabled = false;
        }
    } catch (e) {
        errorEl.textContent = 'Erreur réseau, réessayez.';
        errorEl.style.display = 'block';
        btnText.style.display = 'inline';
        spinner.style.display = 'none';
        document.getElementById('btn-login').disabled = false;
    }
});

// Submit avec la touche Entrée
document.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') document.getElementById('btn-login').click();
});
</script>

<?php wp_footer(); ?>
</body>
</html>
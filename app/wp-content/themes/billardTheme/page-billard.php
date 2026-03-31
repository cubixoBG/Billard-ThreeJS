<?php
/**
 * billard
 */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billard3D - jeu</title>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/billard.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">

    <script type="importmap">
    {
        "imports": {
            "three": "https://unpkg.com/three@0.150.1/build/three.module.js",
            "three/addons/": "https://unpkg.com/three@0.150.1/examples/jsm/"
        }
    }
    </script>
</head>

<body>

    <div id="canvas-container" data-model="<?php echo get_template_directory_uri(); ?>/assets/models/snooker_table.glb">
        <div id="loading">
            <div class="loading-ball"></div>
            <div class="loading-text">Chargement de la table...</div>
            <div class="loading-bar-wrap">
                <div class="loading-bar" id="loading-bar"></div>
            </div>
        </div>

        <div id="ui">
            <div id="top-bar">
                <a href="<?php echo home_url('/'); ?>" id="back-btn">← Accueil</a>
            </div>
        </div>
    </div>

    <script type="module">
        import {
            scene, renderer,
            particles, allNeonMeshes, lamp1, lamp2, lamp3,
            modelLoaded
        } from '<?php echo get_template_directory_uri(); ?>/assets/js/billard/chargementBillard.js';
        import { initLaser } from '<?php echo get_template_directory_uri(); ?>/assets/js/billard/vue-laser.js';
        import * as THREE from 'three';

        const container = document.getElementById('canvas-container');

        const { camera, controls } = initLaser(scene, renderer, modelLoaded);

        // ─── RESIZE ──────────────────────────────────────────────────
        window.addEventListener('resize', () => {
            camera.aspect = container.clientWidth / container.clientHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(container.clientWidth, container.clientHeight);
        });

        // ─── ANIMATION ───────────────────────────────────────────────
        const clock = new THREE.Clock();

        function animate() {
            requestAnimationFrame(animate);
            const t = clock.getElapsedTime();

            allNeonMeshes.forEach(({ mesh, neonLight }, i) => {
                mesh.material.uniforms.uTime.value = t;
                neonLight.intensity = 2.0 + Math.sin(t * 1.8 + i * 2.1) * 0.25;
            });

            [lamp1, lamp2, lamp3].forEach((lamp, i) => {
                const light = lamp.children.find(c => c.isPointLight);
                if (light) light.intensity = 3.5 + Math.sin(t * 2.5 + i * 1.2) * 0.15;
            });

            particles.rotation.y = t * 0.008;
            controls.update();

            // ─── MISE À JOUR DU LASER ─────────────────────────────────
            if (window._laser) {
                const { laser, laserMat, whiteBall } = window._laser;
                const NB = window._NB_POINTS;
                laserMat.uniforms.uTime.value = t;

                const ballPos = new THREE.Vector3();
                whiteBall.getWorldPosition(ballPos);

                // Calcul de la direction opposée à la caméra (vue subjective)
                const dir = new THREE.Vector3();
                dir.subVectors(ballPos, camera.position);
                dir.y = 0.1;
                dir.normalize();

                const positions = laser.geometry.attributes.position.array;
                for (let i = 0; i < NB; i++) {
                    const p = i / (NB - 1);
                    const dist = p * 0.5;
                    positions[i * 3 + 0] = ballPos.x + dir.x * dist;
                    positions[i * 3 + 1] = ballPos.y;
                    positions[i * 3 + 2] = ballPos.z + dir.z * dist;
                }
                laser.geometry.attributes.position.needsUpdate = true;
            }
            renderer.render(scene, camera);
        }

        animate();
    </script>

</body>

</html>
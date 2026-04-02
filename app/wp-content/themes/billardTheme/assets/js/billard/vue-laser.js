import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

export function initLaser(scene, renderer, modelLoaded) {
    // 1. Création de la caméra
    const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 100);
    
    // 2. Création des contrôles
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.06;

    modelLoaded.then((model) => {
        const whiteBall = model.getObjectByName('Object_32');
        if (!whiteBall) return;

        const tableRef = model.getObjectByName('Object_107') || model;
        const tableBox = new THREE.Box3().setFromObject(tableRef);
        const tableCenter = new THREE.Vector3();
        tableBox.getCenter(tableCenter);
        const tableSize = tableBox.getSize(new THREE.Vector3());

        const ballPos = new THREE.Vector3();
        whiteBall.getWorldPosition(ballPos);

        let viewMode = 'ball';

        const setBallView = () => {
            window._cameraMode = 'ball';
            whiteBall.getWorldPosition(ballPos);
            camera.position.set(ballPos.x, ballPos.y + 0.3, ballPos.z + 1);
            controls.target.copy(ballPos);
            controls.enablePan = false;
            controls.enableRotate = true;
            controls.enableZoom = true;
            controls.minDistance = 0.5;
            controls.maxDistance = 5;
            controls.maxPolarAngle = Math.PI / 2.05;
            controls.update();
        };

        const setTopView = () => {
            const offset = 5;
            const angle = Math.PI / 4;

            camera.position.set(
                tableCenter.x + tableSize.x + offset,
                tableCenter.y + tableSize.y + offset * Math.tan(angle),
                tableCenter.z
            );
            controls.target.copy(tableCenter);

            camera.up.set(0, 1, 0);
            window._cameraMode = 'top';            
            controls.enableRotate = false;
            controls.enablePan = true;  
            controls.enableZoom = true;
            controls.minDistance = 2;
            controls.maxDistance = 2;
            controls.minPolarAngle = 0;
            controls.maxPolarAngle = Math.PI / 2;
            controls.update();
        };
        setBallView();

        window.addEventListener('keydown', (event) => {
            if (event.key.toLowerCase() === 'c') {
                if (viewMode === 'ball') {
                    viewMode = 'top';
                    setTopView();
                } else {
                    viewMode = 'ball';
                    setBallView();
                }
            }
        });

        // Shader Laser
        const laserMat = new THREE.ShaderMaterial({
            transparent: true,
            uniforms: { uTime: { value: 0 } },
            vertexShader: `
                attribute float aProgress;
                varying float vProgress;
                void main() {
                    vProgress = aProgress;
                    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
                }
            `,
            fragmentShader: `
                varying float vProgress;
                uniform float uTime;
                void main() {
                    float alpha = (1.0 - vProgress) * 0.8;
                    float pulse = 0.85 + 0.15 * sin(uTime * 6.0 - vProgress * 10.0);
                    gl_FragColor = vec4(1.0, 0.1, 0.1, alpha * pulse);
                }
            `,
        });

        const count = 30;
        window._NB_POINTS = count;

        const positions = new Float32Array(count * 3);
        const progress = new Float32Array(count);
        for (let i = 0; i < count; i++) progress[i] = i / (count - 1);

        const laserGeo = new THREE.BufferGeometry();
        laserGeo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        laserGeo.setAttribute('aProgress', new THREE.BufferAttribute(progress, 1));

        const laser = new THREE.Line(laserGeo, laserMat);
        scene.add(laser);

        window._laser = { laser, laserMat, whiteBall };
    });

    // On retourne les objets pour le fichier principal
    return { camera, controls };
}
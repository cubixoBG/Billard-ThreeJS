import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

// ─── SCENE ───────────────────────────────────────────────────
export const scene = new THREE.Scene();
scene.background = new THREE.Color(0x050507);
scene.fog = new THREE.FogExp2(0x050507, 0.035);

// ─── RENDERER ────────────────────────────────────────────────
const container = document.getElementById('canvas-container');
export const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
renderer.setSize(container.clientWidth, container.clientHeight);
renderer.shadowMap.enabled = true;
renderer.shadowMap.type = THREE.PCFSoftShadowMap;
renderer.outputColorSpace = THREE.SRGBColorSpace; // Version Three.js récente
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 0.6;
container.appendChild(renderer.domElement);

// ─── TEXTURE CANVAS ─────────────────────────────────────
function makeTextTexture(text) {
    const canvas = document.createElement('canvas');
    canvas.width = 2048;
    canvas.height = 256;
    const ctx = canvas.getContext('2d');
    ctx.font = 'bold 160px "Segoe UI", Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillStyle = '#86efac';
    ctx.shadowColor = '#86efac';
    ctx.shadowBlur = 30;
    
    // On dessine 3 fois pour le glow
    for (let i = 0; i < 3; i++) ctx.fillText(text, canvas.width / 2, canvas.height / 2);
    
    const texture = new THREE.CanvasTexture(canvas);
    return texture;
}

// ─── PANNEAU NÉON ───────────────────────────────────────────
function createNeonTextPanel(position, rotationY) {
    // Si wpSettings existe, on prend le texte, sinon valeur par défaut propre
    const finalTxt = (window.wpSettings && window.wpSettings.neonText) 
                     ? window.wpSettings.neonText 
                     : 'BILLARD 3D';

    const material = new THREE.ShaderMaterial({
        uniforms: {
            uTime: { value: 0 },
            uTexture: { value: makeTextTexture(finalTxt) },
        },
        transparent: true,
        depthWrite: false,
        vertexShader: `
            varying vec2 vUv;
            void main() {
                vUv = uv;
                gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
            }
        `,
        fragmentShader: `
            uniform float uTime;
            uniform sampler2D uTexture;
            varying vec2 vUv;
            void main() {
                vec4 tex = texture2D(uTexture, vUv);
                float glow = 0.85 + 0.15 * sin(uTime * 1.6);
                float scanline = 0.96 + 0.04 * sin(vUv.y * 80.0 + uTime * 3.0);
                vec3 color = tex.rgb * glow * scanline;
                gl_FragColor = vec4(color, tex.a * 0.9);
            }
        `
    });

    const mesh = new THREE.Mesh(new THREE.PlaneGeometry(7, 0.75), material);
    mesh.position.copy(position);
    mesh.rotation.y = rotationY;
    scene.add(mesh);

    const neonLight = new THREE.PointLight(0x86efac, 1.0, 5, 2);
    neonLight.position.copy(position);
    scene.add(neonLight);

    return { mesh, neonLight };
}

const TEXT_Y = 1.3;
export const allNeonMeshes = [
    createNeonTextPanel(new THREE.Vector3(0, TEXT_Y, -8.8), 0),
    createNeonTextPanel(new THREE.Vector3(0, TEXT_Y, 8.8), Math.PI),
    createNeonTextPanel(new THREE.Vector3(-8.8, TEXT_Y, 0), Math.PI / 2),
    createNeonTextPanel(new THREE.Vector3(8.8, TEXT_Y, 0), -Math.PI / 2),
];

// ─── SOL ─────────────────────────────────────────────────────
const floor = new THREE.Mesh(
    new THREE.PlaneGeometry(30, 30),
    new THREE.MeshStandardMaterial({ color: 0x0a0a0c, roughness: 0.95, metalness: 0.0 })
);
floor.rotation.x = -Math.PI / 2;
floor.position.y = -0.01;
floor.receiveShadow = true;
scene.add(floor);

// ─── MURS ────────────────────────────────────────────────────
const wallMat = new THREE.MeshStandardMaterial({ color: 0x0c0c0f, roughness: 0.9, metalness: 0.05 });

const wallBack = new THREE.Mesh(new THREE.PlaneGeometry(28, 10), wallMat);
wallBack.position.set(0, 5, -9);
scene.add(wallBack);

const wallFront = new THREE.Mesh(new THREE.PlaneGeometry(28, 10), wallMat.clone());
wallFront.rotation.y = Math.PI;
wallFront.position.set(0, 5, 9);
scene.add(wallFront);

const wallLeft = new THREE.Mesh(new THREE.PlaneGeometry(18, 10), wallMat.clone());
wallLeft.rotation.y = Math.PI / 2;
wallLeft.position.set(-9, 5, 0);
scene.add(wallLeft);

const wallRight = new THREE.Mesh(new THREE.PlaneGeometry(18, 10), wallMat.clone());
wallRight.rotation.y = -Math.PI / 2;
wallRight.position.set(9, 5, 0);
scene.add(wallRight);

// ─── LUMIÈRES ────────────────────────────────────────────────
scene.add(new THREE.AmbientLight(0x0a0f14, 0.8));

function createBilliardLamp(x, z) {
    const group = new THREE.Group();
    const pointLight = new THREE.PointLight(0xffe4a0, 3.5, 5.5, 1.8);
    pointLight.position.y = -0.15;
    pointLight.castShadow = true;
    pointLight.shadow.mapSize.set(512, 512);
    group.add(pointLight);
    group.position.set(x, 1.7, z);
    return group;
}

export const lamp1 = createBilliardLamp(0, -1.3);
export const lamp2 = createBilliardLamp(0, 0);
export const lamp3 = createBilliardLamp(0, 1.3);
scene.add(lamp1, lamp2, lamp3);

const rimLight = new THREE.DirectionalLight(0x3a5fff, 0.4);
rimLight.position.set(-5, 3, -5);
scene.add(rimLight);

// ─── PARTICULES ──────────────────────────────────────────────
const particleCount = 300;
const pos = new Float32Array(particleCount * 3);
for (let i = 0; i < particleCount; i++) {
    pos[i * 3] = (Math.random() - 0.5) * 18;
    pos[i * 3 + 1] = Math.random() * 8;
    pos[i * 3 + 2] = (Math.random() - 0.5) * 18;
}
const particleGeo = new THREE.BufferGeometry();
particleGeo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
export const particles = new THREE.Points(particleGeo, new THREE.PointsMaterial({
    color: 0xffffff, size: 0.015, transparent: true, opacity: 0.2, sizeAttenuation: true,
}));
scene.add(particles);

// ─── CHARGEMENT MODEL GLB ─────────────────────────────────────
const modelUrl = container.dataset.model;
export let model = null;
export const modelLoaded = new Promise((resolve) => {
    new GLTFLoader().load(modelUrl, (gltf) => {
        model = gltf.scene;
        const box = new THREE.Box3().setFromObject(model);
        const center = box.getCenter(new THREE.Vector3());
        const size = box.getSize(new THREE.Vector3());
        const scale = 3.5 / Math.max(size.x, size.y, size.z);

        model.scale.setScalar(scale);
        model.position.sub(center.multiplyScalar(scale));
        model.position.y = 0;

        model.traverse(n => { if(n.isMesh) { n.castShadow = n.receiveShadow = true; }});
        scene.add(model);
        
        const loaderScreen = document.getElementById('loading');
        if (loaderScreen) loaderScreen.classList.add('hidden');
        resolve(model);
    });
});
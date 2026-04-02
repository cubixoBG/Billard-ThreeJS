<?php 
/*
 * Template Name: testPhysique
 */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>test physique</title>
    <style>
        body, html { margin: 0; height: 100%; overflow: hidden; }
        #canvas { width: 100%; height: 100%; display: block; }
    </style>
    <script type="importmap">
    {
      "imports": {
        "three": "https://unpkg.com/three@0.150.1/build/three.module.js",
        "three/addons/": "https://unpkg.com/three@0.150.1/examples/jsm/",
        "cannon-es": "https://cdn.jsdelivr.net/npm/cannon-es@0.20.0/dist/cannon-es.js"
      }
    }
    </script>
</head>
<body>
    <canvas id="canvas"></canvas>
    <script type="module">
        import * as THREE from 'three';
        import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
        import * as CANNON from 'cannon-es';

        const canvas = document.getElementById('canvas');
        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0x0b0d12);

        const camera = new THREE.PerspectiveCamera(50, window.innerWidth / window.innerHeight, 0.1, 100);
        camera.position.set(0, 7, 10);

        const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(window.devicePixelRatio);

        const controls = new OrbitControls(camera, renderer.domElement);
        controls.target.set(0, 0.05, 0);
        controls.update();

        const light = new THREE.DirectionalLight(0xffffff, 1);
        light.position.set(5, 10, 5);
        scene.add(light);
        scene.add(new THREE.AmbientLight(0xaaaaaa, 0.6));

        const world = new CANNON.World({ gravity: new CANNON.Vec3(0, -9.82, 0) });

        const material = new CANNON.Material('default');
        world.defaultContactMaterial = new CANNON.ContactMaterial(material, material, { friction: 0.1, restitution: 0.85 });

        const tableGeom = new THREE.BoxGeometry(10, 0.2, 5);
        const tableMat = new THREE.MeshStandardMaterial({ color: 0x225522, metalness: 0.2, roughness: 0.8 });
        const tableMesh = new THREE.Mesh(tableGeom, tableMat);
        tableMesh.position.set(0, -0.1, 0);
        scene.add(tableMesh);

        const tableBody = new CANNON.Body({ mass: 0, material });
        tableBody.addShape(new CANNON.Box(new CANNON.Vec3(5, 0.1, 2.5)));
        tableBody.position.set(0, -0.1, 0);
        world.addBody(tableBody);

        const wallMat = new THREE.MeshStandardMaterial({ color: 0xff0000, transparent: true, opacity: 0.5 });
        const wallThickness = 0.2;
        const walls = [];

        const addWall = (x, y, z, sx, sy, sz) => {
            const mesh = new THREE.Mesh(new THREE.BoxGeometry(sx, sy, sz), wallMat);
            mesh.position.set(x, y, z);
            scene.add(mesh);
            const body = new CANNON.Body({ mass: 0, material });
            body.addShape(new CANNON.Box(new CANNON.Vec3(sx / 2, sy / 2, sz / 2)));
            body.position.set(x, y, z);
            world.addBody(body);
            walls.push({ mesh, body });
        };

        const halfX = 4.8;
        const halfZ = 2.3;
        const wallY = 0.35;

        addWall(0, wallY, -2.6, 10, 0.7, wallThickness); // back
        addWall(0, wallY, 2.6, 10, 0.7, wallThickness); // front
        addWall(-4.9, wallY, 0, wallThickness, 0.7, 5.2); // left
        addWall(4.9, wallY, 0, wallThickness, 0.7, 5.2); // right

        const ballGeom = new THREE.SphereGeometry(0.2, 32, 32);
        const ballMesh = new THREE.Mesh(ballGeom, new THREE.MeshStandardMaterial({ color: 0xffffff, metalness: 0.3, roughness: 0.4 }));
        scene.add(ballMesh);

        const ballBody = new CANNON.Body({ mass: 0.17, material });
        ballBody.addShape(new CANNON.Sphere(0.2));
        ballBody.position.set(0, 0.4, 0);
        ballBody.linearDamping = 0.2;
        ballBody.angularDamping = 0.2;
        world.addBody(ballBody);

        ballBody.velocity.set(2, 0, 1.2);

        // clique pour propulser la boule selon l'orientation de la caméra
        window.addEventListener('click', () => {
            const lookDir = new THREE.Vector3();
            camera.getWorldDirection(lookDir);
            lookDir.y = 0;
            lookDir.normalize();

            const power = 1.5;
            ballBody.applyImpulse(new CANNON.Vec3(lookDir.x * power, 0, lookDir.z * power), ballBody.position);
        });

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        const clock = new THREE.Clock();

        function animate() {
            requestAnimationFrame(animate);
            const dt = Math.min(clock.getDelta(), 1 / 30);
            world.step(1 / 60, dt, 3);

            ballMesh.position.copy(ballBody.position);
            ballMesh.quaternion.copy(ballBody.quaternion);

            renderer.render(scene, camera);
        }

        animate();
    </script>
</body>
</html>
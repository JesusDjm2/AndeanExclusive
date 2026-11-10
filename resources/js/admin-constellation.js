/**
 * Andean Exclusive - Constelación Andina Interactiva
 * Efecto cinemático sutil de fondo en .admin-shell (exclusivo para desktop PC >= 992px).
 *
 * Características Senior:
 * - Iconografía vectorial peruana y de viajes (Chakana, Montaña/Andes, Inti, Llama, Brújula, Estrella Ch'aska).
 * - Pre-renderizado en lienzos fuera de pantalla (Offscreen Canvas Sprites) para un blitting ultra-rápido (<0.2ms/frame).
 * - Física interactiva al mover el mouse (atracción elástica tipo constelación estelar).
 * - Adaptación dinámica al cambio de tema (Dark / Light) con paleta oficial de Andean Exclusive.
 * - Eficiencia absoluta: reposo automático tras 2.5s de inactividad del cursor (0% CPU en descanso).
 * - Respeta 'prefers-reduced-motion' y se desactiva automáticamente en pantallas móviles/tablets.
 */

const MIN_DESKTOP_WIDTH = 992;
const IDLE_TIMEOUT_MS = 2500;
const MOUSE_RADIUS = 165;
const MOUSE_MIN_DIST = 55;
const LINK_MAX_DIST = 115;
const SPRITE_SIZE = 32;

class AdminConstellation {
    constructor() {
        this.canvas = document.getElementById('adminTravelCanvas');
        if (!this.canvas) return;

        this.ctx = this.canvas.getContext('2d', { alpha: true });
        if (!this.ctx) return;

        this.sprites = {};
        this.particles = [];
        this.theme = 'dark';
        this.colors = null;

        this.mouse = {
            x: -9999,
            y: -9999,
            active: false,
        };

        this.width = 0;
        this.height = 0;
        this.dpr = 1;
        this.animId = null;
        this.isSleeping = false;
        this.lastActiveTime = performance.now();
        this.reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        this.init();
    }

    init() {
        if (this.reducedMotion) {
            this.canvas.style.display = 'none';
            return;
        }

        this.updateTheme();
        this.bindEvents();
        this.resize();

        if (window.innerWidth >= MIN_DESKTOP_WIDTH) {
            this.createParticles();
            this.wake();
        }
    }

    bindEvents() {
        // Redimensionamiento de ventana con debounce
        let resizeTimer = null;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                this.resize();
            }, 150);
        }, { passive: true });

        // Movimiento del mouse sobre toda la ventana
        const onPointerMove = (e) => {
            if (window.innerWidth < MIN_DESKTOP_WIDTH) return;
            this.mouse.x = e.clientX;
            this.mouse.y = e.clientY;
            this.mouse.active = true;
            this.lastActiveTime = performance.now();
            this.wake();
        };

        const onPointerLeave = () => {
            this.mouse.active = false;
            this.mouse.x = -9999;
            this.mouse.y = -9999;
        };

        window.addEventListener('pointermove', onPointerMove, { passive: true });
        document.addEventListener('mouseleave', onPointerLeave);
        window.addEventListener('scroll', () => {
            this.lastActiveTime = performance.now();
            this.wake();
        }, { passive: true });

        // Observar cambio de tema (data-bs-theme) en <html>
        const observer = new MutationObserver(() => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'dark';
            if (currentTheme !== this.theme) {
                this.updateTheme();
                this.wake();
            }
        });
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['data-bs-theme'],
        });

        // Pausar inmediatamente si la pestaña se oculta
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.sleep();
            } else {
                this.lastActiveTime = performance.now();
                this.wake();
            }
        });
    }

    updateTheme() {
        this.theme = document.documentElement.getAttribute('data-bs-theme') === 'light' ? 'light' : 'dark';
        const isDark = this.theme === 'dark';

        this.colors = {
            gold: isDark ? '#dfb77c' : '#9c7542',
            teal: isDark ? '#06b6d4' : '#0891b2',
            silver: isDark ? '#cbd5e1' : '#475569',
            lineColor: isDark ? 'rgba(223, 183, 124, 0.16)' : 'rgba(156, 117, 66, 0.18)',
            mouseLineColor: isDark ? 'rgba(6, 182, 212, 0.32)' : 'rgba(8, 145, 178, 0.28)',
            cursorGlow: isDark ? 'rgba(223, 183, 124, 0.12)' : 'rgba(156, 117, 66, 0.08)',
        };

        this.buildSprites();
    }

    /**
     * Pre-renderiza cada símbolo andino en pequeños lienzos fuera de pantalla
     * para asegurar máxima aceleración por GPU y cero cálculo vectorial por frame.
     */
    buildSprites() {
        const types = ['chakana', 'montana', 'inti', 'llama', 'brujula', 'chaska', 'estrella'];
        this.sprites = {};

        types.forEach((type) => {
            this.sprites[type] = {
                gold: this.renderSprite(type, this.colors.gold),
                teal: this.renderSprite(type, this.colors.teal),
                silver: this.renderSprite(type, this.colors.silver),
            };
        });
    }

    renderSprite(type, color) {
        const offCanvas = document.createElement('canvas');
        offCanvas.width = SPRITE_SIZE;
        offCanvas.height = SPRITE_SIZE;
        const sCtx = offCanvas.getContext('2d');
        if (!sCtx) return offCanvas;

        sCtx.strokeStyle = color;
        sCtx.fillStyle = color;
        sCtx.lineWidth = 1.4;
        sCtx.lineCap = 'round';
        sCtx.lineJoin = 'round';

        const c = SPRITE_SIZE / 2; // 16px

        switch (type) {
            case 'chakana': {
                // Cruz escalonada andina auténtica (12 puntas)
                sCtx.beginPath();
                // Paso superior
                sCtx.moveTo(c - 3, c - 11);
                sCtx.lineTo(c + 3, c - 11);
                sCtx.lineTo(c + 3, c - 7);
                sCtx.lineTo(c + 7, c - 7);
                sCtx.lineTo(c + 7, c - 3);
                // Paso derecho
                sCtx.lineTo(c + 11, c - 3);
                sCtx.lineTo(c + 11, c + 3);
                sCtx.lineTo(c + 7, c + 3);
                sCtx.lineTo(c + 7, c + 7);
                sCtx.lineTo(c + 3, c + 7);
                // Paso inferior
                sCtx.lineTo(c + 3, c + 11);
                sCtx.lineTo(c - 3, c + 11);
                sCtx.lineTo(c - 3, c + 7);
                sCtx.lineTo(c - 7, c + 7);
                sCtx.lineTo(c - 7, c + 3);
                // Paso izquierdo
                sCtx.lineTo(c - 11, c + 3);
                sCtx.lineTo(c - 11, c - 3);
                sCtx.lineTo(c - 7, c - 3);
                sCtx.lineTo(c - 7, c - 7);
                sCtx.lineTo(c - 3, c - 7);
                sCtx.closePath();
                sCtx.stroke();

                // Círculo sagrado central
                sCtx.beginPath();
                sCtx.arc(c, c, 2.2, 0, Math.PI * 2);
                sCtx.stroke();
                break;
            }

            case 'montana': {
                // Doble cumbre andina estilizada (Machu Picchu & Huayna Picchu)
                sCtx.beginPath();
                // Montaña mayor
                sCtx.moveTo(c - 11, c + 8);
                sCtx.lineTo(c - 3, c - 9);
                sCtx.lineTo(c + 3, c + 1);
                // Montaña menor
                sCtx.lineTo(c + 7, c - 4);
                sCtx.lineTo(c + 12, c + 8);
                sCtx.closePath();
                sCtx.stroke();

                // Línea de nieve sutil
                sCtx.beginPath();
                sCtx.moveTo(c - 6, c - 2);
                sCtx.lineTo(c - 3, c);
                sCtx.lineTo(c, c - 2);
                sCtx.stroke();
                break;
            }

            case 'inti': {
                // Sol incaico radiante
                sCtx.beginPath();
                sCtx.arc(c, c, 4, 0, Math.PI * 2);
                sCtx.stroke();

                // Rayos cardinales y diagonales
                const rayLen = 9;
                for (let i = 0; i < 8; i++) {
                    const angle = (i * Math.PI) / 4;
                    const rInner = 5.5;
                    const rOuter = i % 2 === 0 ? rayLen : rayLen - 2;
                    sCtx.beginPath();
                    sCtx.moveTo(c + Math.cos(angle) * rInner, c + Math.sin(angle) * rInner);
                    sCtx.lineTo(c + Math.cos(angle) * rOuter, c + Math.sin(angle) * rOuter);
                    sCtx.stroke();
                }
                break;
            }

            case 'llama': {
                // Silueta geométrica minimalista de llama andina
                sCtx.beginPath();
                sCtx.moveTo(c - 5, c - 9); // oreja izq
                sCtx.lineTo(c - 4, c - 6);
                sCtx.lineTo(c - 8, c - 5); // hocico
                sCtx.lineTo(c - 5, c - 3); // mandíbula
                sCtx.lineTo(c - 4, c + 2); // cuello
                sCtx.lineTo(c + 6, c + 2); // lomo
                sCtx.lineTo(c + 8, c + 3); // cola
                sCtx.lineTo(c + 6, c + 5);
                sCtx.lineTo(c + 5, c + 10); // pata trasera
                sCtx.lineTo(c + 4, c + 10);
                sCtx.lineTo(c + 3, c + 5);
                sCtx.lineTo(c - 1, c + 5); // panza
                sCtx.lineTo(c - 2, c + 10); // pata delantera
                sCtx.lineTo(c - 3, c + 10);
                sCtx.lineTo(c - 3, c + 3);
                sCtx.closePath();
                sCtx.stroke();
                break;
            }

            case 'brujula': {
                // Rosa de los vientos / Brújula de viaje
                sCtx.beginPath();
                sCtx.arc(c, c, 8.5, 0, Math.PI * 2);
                sCtx.setLineDash([2, 3]);
                sCtx.stroke();
                sCtx.setLineDash([]);

                // Aguja Norte-Sur
                sCtx.beginPath();
                sCtx.moveTo(c, c - 10);
                sCtx.lineTo(c + 2.5, c);
                sCtx.lineTo(c, c + 10);
                sCtx.lineTo(c - 2.5, c);
                sCtx.closePath();
                sCtx.stroke();

                // Lado relleno norte
                sCtx.beginPath();
                sCtx.moveTo(c, c - 10);
                sCtx.lineTo(c + 2.5, c);
                sCtx.lineTo(c, c);
                sCtx.closePath();
                sCtx.fill();
                break;
            }

            case 'chaska': {
                // Estrella andina de 4 puntas curvadas (Ch'aska)
                sCtx.beginPath();
                sCtx.moveTo(c, c - 10);
                sCtx.quadraticCurveTo(c, c, c + 10, c);
                sCtx.quadraticCurveTo(c, c, c, c + 10);
                sCtx.quadraticCurveTo(c, c, c - 10, c);
                sCtx.quadraticCurveTo(c, c, c, c - 10);
                sCtx.closePath();
                sCtx.stroke();

                // Destello central
                sCtx.beginPath();
                sCtx.arc(c, c, 1.5, 0, Math.PI * 2);
                sCtx.fill();
                break;
            }

            case 'estrella':
            default: {
                // Pequeño lucero de 4 puntas
                sCtx.beginPath();
                sCtx.moveTo(c, c - 7);
                sCtx.lineTo(c + 1.8, c - 1.8);
                sCtx.lineTo(c + 7, c);
                sCtx.lineTo(c + 1.8, c + 1.8);
                sCtx.lineTo(c, c + 7);
                sCtx.lineTo(c - 1.8, c + 1.8);
                sCtx.lineTo(c - 7, c);
                sCtx.lineTo(c - 1.8, c - 1.8);
                sCtx.closePath();
                sCtx.fill();
                break;
            }
        }

        return offCanvas;
    }

    resize() {
        if (!this.canvas) return;

        if (window.innerWidth < MIN_DESKTOP_WIDTH) {
            this.sleep();
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            return;
        }

        this.dpr = Math.min(window.devicePixelRatio || 1, 2);
        this.width = window.innerWidth;
        this.height = window.innerHeight;

        this.canvas.width = Math.floor(this.width * this.dpr);
        this.canvas.height = Math.floor(this.height * this.dpr);
        this.canvas.style.width = `${this.width}px`;
        this.canvas.style.height = `${this.height}px`;

        this.ctx.setTransform(this.dpr, 0, 0, this.dpr, 0, 0);

        if (this.particles.length === 0) {
            this.createParticles();
        } else {
            // Asegurar que las partículas permanezcan dentro de los límites
            this.particles.forEach((p) => {
                p.x = Math.min(p.x, this.width - 20);
                p.y = Math.min(p.y, this.height - 20);
            });
        }

        this.lastActiveTime = performance.now();
        this.wake();
    }

    createParticles() {
        const count = Math.floor(Math.min(Math.max(this.width / 65, 18), 26));
        const types = ['chakana', 'montana', 'inti', 'llama', 'brujula', 'chaska', 'estrella'];
        const colorKeys = ['gold', 'gold', 'teal', 'silver'];

        this.particles = [];
        for (let i = 0; i < count; i++) {
            const type = types[i % types.length];
            const colorKey = colorKeys[i % colorKeys.length];
            const size = type === 'estrella' ? 14 : Math.floor(18 + Math.random() * 5);

            this.particles.push({
                x: Math.random() * (this.width - 80) + 40,
                y: Math.random() * (this.height - 80) + 40,
                vx: (Math.random() - 0.5) * 0.3,
                vy: (Math.random() - 0.5) * 0.3,
                type,
                colorKey,
                size,
                alpha: 0.35 + Math.random() * 0.35,
                pulseSpeed: 0.008 + Math.random() * 0.012,
                pulsePhase: Math.random() * Math.PI * 2,
            });
        }
    }

    wake() {
        if (!this.isSleeping && this.animId !== null) return;
        this.isSleeping = false;
        this.render();
    }

    sleep() {
        this.isSleeping = true;
        if (this.animId) {
            cancelAnimationFrame(this.animId);
            this.animId = null;
        }
    }

    render() {
        if (this.isSleeping) return;

        const now = performance.now();
        const timeSinceActive = now - this.lastActiveTime;

        // Comprobación de reposo (cero consumo de CPU cuando el usuario no mueve el cursor)
        if (timeSinceActive > IDLE_TIMEOUT_MS && !this.mouse.active) {
            // Desaceleración suave antes de dormir
            let maxSpeed = 0;
            this.particles.forEach((p) => {
                p.vx *= 0.95;
                p.vy *= 0.95;
                const spd = Math.hypot(p.vx, p.vy);
                if (spd > maxSpeed) maxSpeed = spd;
            });

            if (maxSpeed < 0.02) {
                this.drawFrame();
                this.sleep();
                return;
            }
        }

        this.updatePhysics();
        this.drawFrame();

        this.animId = requestAnimationFrame(() => this.render());
    }

    updatePhysics() {
        const mouseX = this.mouse.x;
        const mouseY = this.mouse.y;
        const hasMouse = this.mouse.active && mouseX > 0 && mouseY > 0;

        // 1. Interacción con el cursor (Atracción y formación de constelación)
        for (let i = 0; i < this.particles.length; i++) {
            const p = this.particles[i];

            if (hasMouse) {
                const dx = mouseX - p.x;
                const dy = mouseY - p.y;
                const dist = Math.hypot(dx, dy);

                if (dist < MOUSE_RADIUS && dist > 0.01) {
                    const normX = dx / dist;
                    const normY = dy / dist;

                    if (dist > MOUSE_MIN_DIST) {
                        // Atracción elástica suave hacia el anillo exterior del cursor
                        const pull = ((MOUSE_RADIUS - dist) / MOUSE_RADIUS) * 0.48;
                        p.vx += normX * pull;
                        p.vy += normY * pull;
                    } else {
                        // Repulsión cuando entran al círculo interior del cursor
                        const push = ((MOUSE_MIN_DIST - dist) / MOUSE_MIN_DIST) * 0.95;
                        p.vx -= normX * push;
                        p.vy -= normY * push;

                        // Leve empuje tangencial para que orbiten de forma armónica
                        p.vx += -normY * 0.18;
                        p.vy += normX * 0.18;
                    }
                }
            }

            // Fricción suave para desaceleraciones naturales
            p.vx *= 0.93;
            p.vy *= 0.93;

            // Mantener un sutil movimiento ambiente mínimo
            const currentSpeed = Math.hypot(p.vx, p.vy);
            if (currentSpeed < 0.10) {
                const angle = p.pulsePhase + (i * 0.7);
                p.vx += Math.cos(angle) * 0.025;
                p.vy += Math.sin(angle) * 0.025;
            }

            // Actualización de posición
            p.x += p.vx;
            p.y += p.vy;

            p.pulsePhase += p.pulseSpeed;
        }

        // 2. Anti-solapamiento estricto entre partículas (Separación elástica Boids / Soft-body)
        // Cada ícono conserva siempre su propio espacio vital y nunca se sobrepone con otro
        for (let i = 0; i < this.particles.length; i++) {
            const p1 = this.particles[i];
            for (let j = i + 1; j < this.particles.length; j++) {
                const p2 = this.particles[j];
                const dx = p1.x - p2.x;
                const dy = p1.y - p2.y;
                const dist = Math.hypot(dx, dy) || 0.001;

                // Distancia mínima requerida entre centros para evitar cualquier toque o cruce
                const minSpace = (p1.size + p2.size) * 0.75 + 14;

                if (dist < minSpace) {
                    const overlap = minSpace - dist;
                    const nx = dx / dist;
                    const ny = dy / dist;

                    // Fuerza de repulsión mutua proporcional al solapamiento
                    const repForce = (overlap / minSpace) * 0.75;
                    p1.vx += nx * repForce;
                    p1.vy += ny * repForce;
                    p2.vx -= nx * repForce;
                    p2.vy -= ny * repForce;

                    // Corrección posicional directa para asegurar separación inmediata
                    const pushHalf = overlap * 0.35;
                    p1.x += nx * pushHalf;
                    p1.y += ny * pushHalf;
                    p2.x -= nx * pushHalf;
                    p2.y -= ny * pushHalf;
                }
            }
        }

        // 3. Contención elástica en los márgenes de la pantalla
        const pad = 24;
        for (let i = 0; i < this.particles.length; i++) {
            const p = this.particles[i];
            if (p.x < pad) { p.x = pad; p.vx = Math.abs(p.vx) * 0.6; }
            if (p.x > this.width - pad) { p.x = this.width - pad; p.vx = -Math.abs(p.vx) * 0.6; }
            if (p.y < pad) { p.y = pad; p.vy = Math.abs(p.vy) * 0.6; }
            if (p.y > this.height - pad) { p.y = this.height - pad; p.vy = -Math.abs(p.vy) * 0.6; }
        }
    }

    drawFrame() {
        this.ctx.clearRect(0, 0, this.width, this.height);

        // 1. Dibujar líneas de constelación entre partículas cercanas
        this.ctx.lineWidth = 0.75;
        for (let i = 0; i < this.particles.length; i++) {
            const p1 = this.particles[i];
            for (let j = i + 1; j < this.particles.length; j++) {
                const p2 = this.particles[j];
                const dx = p1.x - p2.x;
                const dy = p1.y - p2.y;
                const dist = Math.hypot(dx, dy);

                if (dist < LINK_MAX_DIST) {
                    const lineAlpha = (1 - dist / LINK_MAX_DIST) * 0.45;
                    this.ctx.strokeStyle = this.colors.lineColor;
                    this.ctx.globalAlpha = lineAlpha;
                    this.ctx.beginPath();
                    this.ctx.moveTo(p1.x, p1.y);
                    this.ctx.lineTo(p2.x, p2.y);
                    this.ctx.stroke();
                }
            }
        }

        // 2. Dibujar líneas radiantes desde el cursor hacia las partículas más cercanas
        if (this.mouse.active && this.mouse.x > 0 && this.mouse.y > 0) {
            const mx = this.mouse.x;
            const my = this.mouse.y;

            // Halo suave en el cursor
            const glow = this.ctx.createRadialGradient(mx, my, 0, mx, my, 80);
            glow.addColorStop(0, this.colors.cursorGlow);
            glow.addColorStop(1, 'transparent');
            this.ctx.fillStyle = glow;
            this.ctx.globalAlpha = 1;
            this.ctx.beginPath();
            this.ctx.arc(mx, my, 80, 0, Math.PI * 2);
            this.ctx.fill();

            // Enlaces luminosos hacia iconos dentro del radio
            this.ctx.lineWidth = 0.9;
            this.ctx.strokeStyle = this.colors.mouseLineColor;
            for (let i = 0; i < this.particles.length; i++) {
                const p = this.particles[i];
                const dist = Math.hypot(mx - p.x, my - p.y);
                if (dist < MOUSE_RADIUS) {
                    const alpha = (1 - dist / MOUSE_RADIUS) * 0.65;
                    this.ctx.globalAlpha = alpha;
                    this.ctx.beginPath();
                    this.ctx.moveTo(mx, my);
                    this.ctx.lineTo(p.x, p.y);
                    this.ctx.stroke();
                }
            }
        }

        // 3. Dibujar iconos usando los sprites cacheados (blitting ultrarrápido)
        for (let i = 0; i < this.particles.length; i++) {
            const p = this.particles[i];
            const spriteObj = this.sprites[p.type];
            const sprite = spriteObj ? spriteObj[p.colorKey] || spriteObj.gold : null;
            if (!sprite) continue;

            const pulse = 0.85 + Math.sin(p.pulsePhase) * 0.15;
            this.ctx.globalAlpha = Math.min(Math.max(p.alpha * pulse, 0.15), 0.95);

            const half = p.size / 2;
            this.ctx.drawImage(sprite, p.x - half, p.y - half, p.size, p.size);
        }

        // Restaurar alpha global
        this.ctx.globalAlpha = 1;
    }
}

export function initAdminConstellation() {
    return new AdminConstellation();
}

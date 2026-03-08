<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>production‑ready · blurred ambient video background</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0a0a0a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }

        /* main container – padding area shows smooth blurred video background */
        .video-ambient-pro {
            position: relative;
            width: 100%;
            max-width: 1280px;
            padding: 28px;                /* padding → shows ambient behind */
            border-radius: 32px;
            overflow: hidden;              /* keeps background inside border radius */
            box-shadow: 0 25px 40px -12px rgba(0,0,0,0.7);
            transition: box-shadow 0.2s;
            /* no background color – we use absolute canvas */
        }

        /* background canvas – draws blurred & softened video */
        .ambient-back-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: block;
            pointer-events: none;          /* lets clicks go to video */
            z-index: 1;
            opacity: 0.95;                  /* subtle blend, reduces harshness */
            filter: brightness(0.9) contrast(0.85);  /* lower contrast, softer */
            /* blur is applied via canvas context, but we can also add a tiny blur for safety */
            will-change: transform, filter;
        }

        /* overlay to further reduce contrast and smooth edges (production polish) */
        .ambient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, transparent 30%, rgba(0,0,0,0.15) 90%);
            z-index: 2;
            pointer-events: none;
            border-radius: inherit;          /* follows container rounding */
        }

        /* video container stays above background */
        .video-front {
            position: relative;
            z-index: 3;
            border-radius: 20px;             /* inner rounding */
            overflow: hidden;
            background: transparent;           /* let background show through? not needed */
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        /* video element – exact size requirements */
        .ambient-video {
            width: 100%;
            height: 550px;                    /* desktop base */
            object-fit: cover;                 /* cover area, may crop but keeps blur full */
            display: block;
            background: #0f0f0f;               /* tiny fallback */
        }

        /* fully responsive heights */
        @media screen and (max-width: 768px) {
            .video-ambient-pro {
                padding: 16px;
                border-radius: 24px;
            }
            .ambient-video {
                height: 350px;
            }
        }

        @media screen and (max-width: 480px) {
            .ambient-video {
                height: 250px;
            }
        }

        .info-badge {
            position: relative;
            z-index: 4;
            margin-top: 16px;
            text-align: center;
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
            backdrop-filter: blur(8px);
            background: rgba(0,0,0,0.2);
            padding: 8px 16px;
            border-radius: 40px;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
            border: 1px solid rgba(255,255,255,0.05);
        }
    </style>
</head>
<body>
    <div class="video-ambient-pro" id="ambientProContainer">
        <!-- background canvas – will be sized to wrapper, draws blurred video -->
        <canvas class="ambient-back-canvas" id="ambientCanvas"></canvas>
        <!-- soft overlay to lower contrast / smooth edges (like youtube's subtle dark gradient) -->
        <div class="ambient-overlay"></div>

        <!-- video layer (exactly your structure preserved) -->
        <div>
            <div class="video-front">
                <video 
                    class="ambient-video" 
                    id="mainVideo"
                    src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4"
                    autoplay 
                    loop 
                    muted 
                    playsinline
                    crossorigin="anonymous"
                ></video>
            </div>
        </div>

        <div class="info-badge">
            ⚡ ambient blur · real‑time softened background · contrast reduced
        </div>
    </div>

    <script>
        (function() {
            "use strict";

            // ---------- DOM elements ----------
            const container = document.getElementById('ambientProContainer');
            const video = document.getElementById('mainVideo');
            const bgCanvas = document.getElementById('ambientCanvas');
            
            if (!container || !video || !bgCanvas) return;

            // ---------- canvas context & sizing ----------
            const ctx = bgCanvas.getContext('2d', { willReadFrequently: true });
            
            // We'll use a blur level that's smooth but not overwhelming
            const BLUR_RADIUS = 28;          // pixels (applied via canvas filter)
            const SCALE_FACTOR = 1.05;        // slightly zoom background to fill padding & soften edges

            // resize observer to keep canvas exactly matching container
            const resizeObserver = new ResizeObserver(entries => {
                for (let entry of entries) {
                    if (entry.target === container) {
                        updateCanvasSize();
                        // also trigger a redraw when size changes
                        if (video.readyState >= 2) {
                            drawBackground();
                        }
                    }
                }
            });
            resizeObserver.observe(container);

            function updateCanvasSize() {
                const rect = container.getBoundingClientRect();
                const w = rect.width;
                const h = rect.height;
                
                // canvas pixel size set to match CSS (avoid blurry canvas)
                bgCanvas.width = w;
                bgCanvas.height = h;
                
                // reset drawing state after resize
                ctx.filter = `blur(${BLUR_RADIUS}px)`;
                // we will draw in drawBackground()
            }

            // initial size set
            updateCanvasSize();

            // ---------- core drawing: paint video onto canvas scaled to cover container + blur ----------
            function drawBackground() {
                // ensure video is ready and has dimensions
                if (video.readyState < video.HAVE_CURRENT_DATA || video.videoWidth === 0) {
                    return;
                }

                const canvasW = bgCanvas.width;
                const canvasH = bgCanvas.height;
                if (canvasW === 0 || canvasH === 0) return;

                // clear canvas (transparent default, but we'll draw over)
                ctx.clearRect(0, 0, canvasW, canvasH);

                // calculate source video rectangle that covers canvas proportionally
                const vidW = video.videoWidth;
                const vidH = video.videoHeight;
                if (vidW === 0 || vidH === 0) return;

                const targetW = canvasW * SCALE_FACTOR;
                const targetH = canvasH * SCALE_FACTOR;

                // find scale to cover canvas completely (may crop)
                const scale = Math.max(targetW / vidW, targetH / vidH);
                const srcDrawW = vidW * scale;
                const srcDrawH = vidH * scale;

                // center the video on canvas
                const offsetX = (canvasW - srcDrawW) / 2;
                const offsetY = (canvasH - srcDrawH) / 2;

                // set blur filter (applied to draw)
                ctx.filter = `blur(${BLUR_RADIUS}px)`;
                
                // draw the video frame onto canvas – the blur makes it smooth
                ctx.drawImage(video, 0, 0, vidW, vidH, offsetX, offsetY, srcDrawW, srcDrawH);

                // optional: apply an extra soft darkening directly on canvas to lower contrast
                // (we also have overlay div, but a tiny multiply can help)
                // we can keep it simple – the overlay div handles contrast reduction.
            }

            // ---------- animation loop – smooth 60fps update but we throttle to save performance ----------
            let rafId = null;
            let lastDraw = 0;
            const FRAME_THROTTLE = 80; // ms (~12fps) – smooth enough for background, less CPU

            function updateLoop(now) {
                rafId = requestAnimationFrame(updateLoop);
                
                if (!video.paused && !video.ended) {
                    if (now - lastDraw > FRAME_THROTTLE) {
                        drawBackground();
                        lastDraw = now;
                    }
                }
            }

            // start loop when video can play
            if (video.readyState >= 2) {
                rafId = requestAnimationFrame(updateLoop);
            } else {
                video.addEventListener('canplay', () => {
                    rafId = requestAnimationFrame(updateLoop);
                }, { once: true });
            }

            // also draw on seeking, resize, etc.
            video.addEventListener('seeked', () => {
                drawBackground();
            });

            video.addEventListener('play', () => {
                // immediate draw on play start
                drawBackground();
            });

            // initial draw when metadata loaded
            video.addEventListener('loadeddata', () => {
                drawBackground();
            });

            // fallback for CORS / error: canvas may be tainted? 
            // The video is from a CORS-friendly source, but if any issue occurs we can skip.
            // In case of tainted canvas, the drawImage will throw. We'll silently ignore.
            const originalDraw = drawBackground;
            drawBackground = function safeDraw() {
                try {
                    originalDraw();
                } catch (e) {
                    // CORS / taint – stop animation and keep last good frame or fallback.
                    console.warn('Ambient background: unable to read video (CORS). Fallback to dark.');
                    if (rafId) {
                        cancelAnimationFrame(rafId);
                        rafId = null;
                    }
                    // fill canvas with a dark gradient as fallback
                    const w = bgCanvas.width;
                    const h = bgCanvas.height;
                    if (w && h) {
                        ctx.filter = 'none';
                        const grad = ctx.createLinearGradient(0, 0, w*0.8, h);
                        grad.addColorStop(0, '#1a1a1a');
                        grad.addColorStop(1, '#2a2a2a');
                        ctx.fillStyle = grad;
                        ctx.fillRect(0, 0, w, h);
                    }
                }
            };
            // reassign and call original once.
            // We'll override the function reference.
            // But we need to keep the same name; better to wrap.
            // We'll replace the function with a safe version.
            // For simplicity, we override:
            const safeDrawBackground = function() {
                try {
                    originalDraw();
                } catch (e) {
                    console.warn('CORS restriction – ambient background disabled');
                    if (rafId) {
                        cancelAnimationFrame(rafId);
                        rafId = null;
                    }
                    // draw fallback gradient
                    const w = bgCanvas.width;
                    const h = bgCanvas.height;
                    if (w && h) {
                        ctx.filter = 'none';
                        const grad = ctx.createLinearGradient(0, 0, w*0.8, h);
                        grad.addColorStop(0, '#1e1e1e');
                        grad.addColorStop(1, '#2d2d2d');
                        ctx.fillStyle = grad;
                        ctx.fillRect(0, 0, w, h);
                    }
                }
            };

            // replace the draw function used in listeners
            // We'll reassign after defining, but careful with closures.
            // Instead, we modify the update loop to use safe version.
            // We'll override drawBackground reference.
            drawBackground = safeDrawBackground;

            // ensure listeners call the safe version
            // rebind events: easier to just re-set the loop
            // we already have drawBackground reassigned, so updateLoop uses new safe version.
            // also call once manually.
            if (video.readyState >= 2) {
                drawBackground();
            }

            // ---------- extra polish: window resize triggers redraw ----------
            window.addEventListener('resize', () => {
                // resize observer already handles canvas resize, but we can redraw
                if (video.readyState >= 2) {
                    drawBackground();
                }
            });

            // ---------- initial fallback if video takes time ----------
            setTimeout(() => {
                if (video.readyState < 2) {
                    // draw a neutral gradient until video loads
                    const w = bgCanvas.width;
                    const h = bgCanvas.height;
                    if (w && h) {
                        ctx.filter = 'blur(20px)';
                        const grad = ctx.createLinearGradient(0, 0, w, h);
                        grad.addColorStop(0, '#202020');
                        grad.addColorStop(1, '#353535');
                        ctx.fillStyle = grad;
                        ctx.fillRect(0, 0, w, h);
                    }
                }
            }, 300);

        })();
    </script>
</body>
</html>
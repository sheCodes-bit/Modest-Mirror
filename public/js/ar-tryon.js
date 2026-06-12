$(document).ready(function() {
    const video = document.getElementById('webcamVideo');
    const canvas = document.getElementById('arCanvas');
    const ctx = canvas.getContext('2d');
    const loadingScreen = document.getElementById('arLoading');
    const cameraStatus = document.getElementById('camera-status-badge');
    const facemeshStatus = document.getElementById('facemesh-status-badge');
    
    let activeOverlayImg = new Image();
    let isTracking = false;
    let currentLandmarks = null;

    // Calibration settings from product (retrieved dynamically)
    let productScaleFactor = 1.05;
    let productOffsetY = -0.05;

    // Manual offset controls (delta changes)
    let manualScale = 1.00;
    let manualOffsetX = 0.0; // in pixels
    let manualOffsetY = 0.0; // in pixels

    // Face shape configuration overrides
    let selectedFaceShape = 'auto'; // 'auto', 'oval', 'round', 'square', 'heart', 'long'
    let detectedFaceShape = 'oval'; // Default estimate

    // EMA Smoothing State Variables to eliminate tracking stutter
    let smoothedCenterX = null;
    let smoothedCenterY = null;
    let smoothedFaceWidth = null;
    let smoothedFaceHeight = null;
    let smoothedRollAngle = null;
    const emaAlpha = 0.22; // Exponential Moving Average smoothing factor

    const initialItem = $('.sidebar-product-item.active');
    if (initialItem.length > 0) {
        selectProduct(initialItem);
    } else {
        const firstItem = $('.sidebar-product-item').first();
        if (firstItem.length > 0) {
            selectProduct(firstItem);
        }
    }

    $('.sidebar-product-item').on('click', function() {
        selectProduct($(this));
    });

    function selectProduct(element) {
        $('.sidebar-product-item').removeClass('active');
        element.addClass('active');

        const productId = element.data('id');
        const productName = element.data('name');
        const productPrice = element.data('price');
        const overlayPath = element.data('overlay');
        const cartRoute = element.data('cart-route');
        const wishlistRoute = element.data('wishlist-route');
        
        const details = element.find('.text-muted').text().split('|');
        const fabric = details[0] ? details[0].trim() : '-';
        const color = details[1] ? details[1].trim() : '-';

        // Load specific product's AR properties if any, otherwise defaults
        productScaleFactor = parseFloat(element.data('scale') || 1.05);
        productOffsetY = parseFloat(element.data('offset-y') || -0.05);

        $('#toolbar-product-name').text(productName);
        $('#ar-cart-form').attr('action', cartRoute);
        $('#ar-wishlist-form').attr('action', wishlistRoute);
        
        $('#spec-fabric').text(fabric);
        $('#spec-color').text(color);
        $('#spec-description').text(`Luxury AR draping. Auto-calibrated for face width & shape with live tracking.`);

        activeOverlayImg.src = overlayPath;
    }

    // Manual Adjustments Event Listeners
    $('#btn-zoom-in').on('click', function() {
        manualScale = Math.min(2.00, manualScale + 0.05);
        updateUIValues();
    });
    $('#btn-zoom-out').on('click', function() {
        manualScale = Math.max(0.50, manualScale - 0.05);
        updateUIValues();
    });
    $('#btn-offset-up').on('click', function() {
        manualOffsetY -= 4.0;
        updateUIValues();
    });
    $('#btn-offset-down').on('click', function() {
        manualOffsetY += 4.0;
        updateUIValues();
    });
    $('#btn-offset-left').on('click', function() {
        manualOffsetX -= 4.0;
        updateUIValues();
    });
    $('#btn-offset-right').on('click', function() {
        manualOffsetX += 4.0;
        updateUIValues();
    });
    
    // Select face shape change listener
    $('#select-face-shape').on('change', function() {
        selectedFaceShape = $(this).val();
        updateUIValues();
    });

    function updateUIValues() {
        $('#zoom-val').text(manualScale.toFixed(2) + 'x');
        $('#offset-y-val').text(manualOffsetY.toFixed(1) + 'px');
        $('#offset-x-val').text(manualOffsetX.toFixed(1) + 'px');
    }

    const faceMesh = new FaceMesh({
        locateFile: (file) => {
            return `https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/${file}`;
        }
    });

    faceMesh.setOptions({
        maxNumFaces: 1,
        refineLandmarks: true,
        minDetectionConfidence: 0.5,
        minTrackingConfidence: 0.5
    });

    faceMesh.onResults(onResults);

    let streamRef = null;

    function startCamera() {
        // Stop any existing streams first
        if (streamRef) {
            streamRef.getTracks().forEach(track => track.stop());
        }

        navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: 'user'
            },
            audio: false
        })
        .then(stream => {
            streamRef = stream;
            video.srcObject = stream;
            video.addEventListener('loadedmetadata', () => {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                video.play();
                cameraStatus.innerHTML = '<i class="fa-solid fa-camera me-1"></i> Camera Active';
                cameraStatus.className = 'badge bg-success';
                
                requestAnimationFrame(processFrame);
            });
        })
        .catch(err => {
            console.error("Camera access error: ", err);
            loadingScreen.innerHTML = `
                <i class="fa fa-camera-slash text-danger mb-3" style="font-size: 3rem;"></i>
                <h4 class="brand-font">Webcam Access Denied</h4>
                <p class="text-white-50 text-center px-4" style="max-width: 400px; font-size: 0.9rem;">
                    Please enable camera permissions in your browser settings to run the virtual try-on experience.
                </p>
            `;
        });
    }

    async function processFrame() {
        if (streamRef && !video.paused && !video.ended) {
            try {
                await faceMesh.send({ image: video });
            } catch (e) {
                console.error("FaceMesh send error: ", e);
            }
        }
        if (streamRef) {
            requestAnimationFrame(processFrame);
        }
    }

    function onResults(results) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(results.image, 0, 0, canvas.width, canvas.height);

        if (results.multiFaceLandmarks && results.multiFaceLandmarks.length > 0) {
            if (!isTracking) {
                isTracking = true;
                loadingScreen.style.opacity = '0';
                setTimeout(() => { loadingScreen.style.display = 'none'; }, 400);
                facemeshStatus.innerHTML = '<i class="fa-solid fa-smile me-1"></i> Face Tracked';
                facemeshStatus.className = 'badge bg-success';
            }

            currentLandmarks = results.multiFaceLandmarks[0];
            drawHijabOverlay(currentLandmarks);
        } else {
            if (isTracking) {
                facemeshStatus.innerHTML = '<i class="fa-solid fa-face-meh me-1"></i> Finding Face';
                facemeshStatus.className = 'badge bg-warning text-dark';
            }
        }
    }

    function drawHijabOverlay(landmarks) {
        const getPt = (idx) => ({
            x: landmarks[idx].x * canvas.width,
            y: landmarks[idx].y * canvas.height
        });

        // Landmark points:
        // 10: Forehead Center
        // 152: Chin Bottom
        // 234: Left Cheekbone
        // 454: Right Cheekbone
        const ptForehead = getPt(10);
        const ptChin = getPt(152);
        const ptLeftCheek = getPt(234);
        const ptRightCheek = getPt(454);
        
        const dx = ptRightCheek.x - ptLeftCheek.x;
        const dy = ptRightCheek.y - ptLeftCheek.y;
        const faceWidth = Math.sqrt(dx*dx + dy*dy);

        const hx = ptChin.x - ptForehead.x;
        const hy = ptChin.y - ptForehead.y;
        const faceHeight = Math.sqrt(hx*hx + hy*hy);

        const centerX = (ptLeftCheek.x + ptRightCheek.x) / 2;
        const centerY = (ptForehead.y + ptChin.y) / 2;

        const rollAngle = Math.atan2(dy, dx);

        // Classify Face Shape from ratio
        const ratio = faceWidth / faceHeight;
        if (ratio < 0.76) {
            detectedFaceShape = 'long';
        } else if (ratio >= 0.76 && ratio < 0.82) {
            detectedFaceShape = 'oval';
        } else if (ratio >= 0.82 && ratio < 0.88) {
            detectedFaceShape = 'heart';
        } else if (ratio >= 0.88 && ratio < 0.94) {
            detectedFaceShape = 'square';
        } else {
            detectedFaceShape = 'round';
        }

        const activeFaceShape = selectedFaceShape === 'auto' ? detectedFaceShape : selectedFaceShape;
        $('#detected-shape-badge').text(activeFaceShape.toUpperCase() + (selectedFaceShape === 'auto' ? ' (Auto)' : ' (Manual)'));

        // Custom multiplier factor based on face shape to avoid clipping
        let shapeMultiplier = 1.0;
        if (activeFaceShape === 'round') shapeMultiplier = 1.05;
        if (activeFaceShape === 'square') shapeMultiplier = 1.06;
        if (activeFaceShape === 'heart') shapeMultiplier = 0.98;
        if (activeFaceShape === 'long') shapeMultiplier = 0.94;

        // Apply Exponential Moving Average (EMA) to eliminate frame overlay lag/jitter
        if (smoothedCenterX === null) {
            smoothedCenterX = centerX;
            smoothedCenterY = centerY;
            smoothedFaceWidth = faceWidth;
            smoothedFaceHeight = faceHeight;
            smoothedRollAngle = rollAngle;
        } else {
            smoothedCenterX = emaAlpha * centerX + (1 - emaAlpha) * smoothedCenterX;
            smoothedCenterY = emaAlpha * centerY + (1 - emaAlpha) * smoothedCenterY;
            smoothedFaceWidth = emaAlpha * faceWidth + (1 - emaAlpha) * smoothedFaceWidth;
            smoothedFaceHeight = emaAlpha * faceHeight + (1 - emaAlpha) * smoothedFaceHeight;
            
            // Adjust roll angle correctly for rotation wrapping
            let diff = rollAngle - smoothedRollAngle;
            while (diff < -Math.PI) diff += Math.PI * 2;
            while (diff > Math.PI) diff -= Math.PI * 2;
            smoothedRollAngle = smoothedRollAngle + emaAlpha * diff;
        }

        // Overlay sizing calculations
        const overlayWidth = smoothedFaceWidth * (600 / 220) * productScaleFactor * manualScale * shapeMultiplier;
        const overlayHeight = smoothedFaceHeight * (600 / 310) * productScaleFactor * manualScale;

        ctx.save();
        
        // Translate and Rotate based on smoothed metrics
        ctx.translate(smoothedCenterX + manualOffsetX, smoothedCenterY + manualOffsetY);
        ctx.rotate(smoothedRollAngle);
        
        // Offset Y is applied dynamically to keep crown aligned
        const pivotX = -overlayWidth * 0.50;
        const pivotY = -overlayHeight * 0.417 + (productOffsetY * overlayHeight);

        if (activeOverlayImg.complete) {
            ctx.drawImage(activeOverlayImg, pivotX, pivotY, overlayWidth, overlayHeight);
        }
        
        ctx.restore();
    }

    $('#btn-screenshot').on('click', function() {
        $('#flash-effect').show().css('opacity', '1').animate({ opacity: 0 }, 500, function() {
            $(this).hide();
        });

        // Mirror canvas rendering so snapshot matches screen viewport exactly
        const captureCanvas = document.createElement('canvas');
        captureCanvas.width = canvas.width;
        captureCanvas.height = canvas.height;
        const captureCtx = captureCanvas.getContext('2d');

        captureCtx.translate(canvas.width, 0);
        captureCtx.scale(-1, 1);
        captureCtx.drawImage(canvas, 0, 0);

        const dataUrl = captureCanvas.toDataURL('image/png');

        const link = document.createElement('a');
        link.download = `modestmirror_tryon_${Date.now()}.png`;
        link.href = dataUrl;
        link.click();

        if (isUserLoggedIn) {
            $.ajax({
                url: saveScreenshotRoute,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    image: dataUrl
                },
                success: function(response) {
                    if (response.success) {
                        showToast('Snapshot saved directly to your dashboard!');
                    }
                },
                error: function(err) {
                    console.error("Failed to save screenshot: ", err);
                }
            });
        } else {
            showToast('Photo downloaded! Login to save screenshots to your profile.');
        }
    });

    $('#btn-reset').on('click', function() {
        // Reset manual offset calibrations
        manualScale = 1.00;
        manualOffsetX = 0.0;
        manualOffsetY = 0.0;
        selectedFaceShape = 'auto';
        $('#select-face-shape').val('auto');
        updateUIValues();

        // Reset trackers
        isTracking = false;
        loadingScreen.style.display = 'flex';
        loadingScreen.style.opacity = '1';
        facemeshStatus.innerHTML = '<i class="fa fa-arrows-rotate me-1"></i> Recalibrating';
        facemeshStatus.className = 'badge bg-warning text-dark';
        
        // Reset EMA filters
        smoothedCenterX = null;
        smoothedCenterY = null;
        smoothedFaceWidth = null;
        smoothedFaceHeight = null;
        smoothedRollAngle = null;

        startCamera();
    });

    function showToast(message) {
        const toast = $('<div class="toast-popup font-monospace text-uppercase" style="position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); background: var(--primary-coffee); color: var(--background-beige); padding: 12px 24px; border-radius: 50px; z-index: 10000; box-shadow: var(--shadow-lg); font-size: 0.8rem; letter-spacing: 1px;"></div>');
        toast.text(message);
        $('body').append(toast);
        toast.hide().fadeIn(300).delay(3000).fadeOut(400, function() {
            $(this).remove();
        });
    }

    // Clean release on page unload
    window.addEventListener('beforeunload', () => {
        if (streamRef) {
            streamRef.getTracks().forEach(track => track.stop());
        }
    });

    startCamera();
});
